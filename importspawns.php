<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/*
importspawns.php
Новый файл - в оригинальном DropCalc данные о спавне читались напрямую из
spawnlist/custom_spawnlist/raidboss_spawnlist на сервере. В новом формате
спавн задан полигоном зоны, а не точкой - locx/locy/locz здесь это ЦЕНТРОИД
зоны, не настоящая точка спавна конкретного моба (см. README миграции).
*/

include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];

$langfile = $language_array[$langval][1];
include($langfile);

echo "<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>
<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
</head>
<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
<center>";

$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		require_once(__DIR__ . '/xmlparse_common.php');
		set_time_limit(0);
		$con = mysql_connect($db_location,$db_user,$db_psswd);
		mysql_query("SET NAMES 'utf8'", $con);
		mysql_query("SET character_set_results='utf8'", $con);
		if (!$con)
			{
			echo "<p class=\"popup\">Could Not Connect</p>";
			die('Could not connect: ' . mysql_error());
			}
		if (!mysql_select_db("$db_l2jdb",$con))
			{
			die('Could not change to L2J database: ' . mysql_error());
			}

		mysql_query("CREATE TABLE IF NOT EXISTS `knightspawnlist` (
			`npc_templateid` INT, `locx` INT, `locy` INT, `locz` INT, `count` INT DEFAULT 1,
			`loc_id` VARCHAR(100), `is_approximate` INT DEFAULT 1,
			KEY `npc_templateid` (`npc_templateid`))", $con);
		mysql_query("truncate table knightspawnlist", $con);

		// Полный полигон зоны (а не только центроид) - points хранится как
		// "x1,y1;x2,y2;x3,y3...". По просьбе - карта теперь рисует контур
		// зоны вместо точки по центру.
		mysql_query("CREATE TABLE IF NOT EXISTS `knightspawnzone` (
			`zone` VARCHAR(100) PRIMARY KEY, `minZ` INT, `maxZ` INT, `points` TEXT)", $con);
		mysql_query("truncate table knightspawnzone", $con);

		$dir = $server_dir . 'data' . $svr_dir_delimit . 'spawns' . $svr_dir_delimit;
		$files = xip_listXmlFiles($dir);
		$count = 0;

		foreach ($files as $path)
		{
			$xml = xip_loadXml($path);
			if (!$xml) continue;
			foreach ($xml->spawn as $spawn)
			{
				$zone = (string) $spawn['zone'];
				$xs = []; $ys = []; $minZ = $maxZ = null;
				foreach ($spawn->territory ?? [] as $terr)
				{
					$minZ = (int) $terr['minZ'];
					$maxZ = (int) $terr['maxZ'];
					foreach ($terr->node as $node)
					{	$xs[] = (int) $node['x']; $ys[] = (int) $node['y'];	}
				}
				if (empty($xs)) continue;
				$cx = (int) round(array_sum($xs) / count($xs));
				$cy = (int) round(array_sum($ys) / count($ys));
				$cz = ($minZ !== null && $maxZ !== null) ? (int) round(($minZ + $maxZ) / 2) : 0;

				$pointsStr = [];
				foreach ($xs as $i => $x)
				{	$pointsStr[] = $x . ',' . $ys[$i];	}
				$pointsStr = implode(';', $pointsStr);
				mysql_query("replace into knightspawnzone (zone,minZ,maxZ,points) values (
					'" . mysql_real_escape_string($zone,$con) . "', " . ($minZ ?? 0) . ", " . ($maxZ ?? 0) . ",
					'" . mysql_real_escape_string($pointsStr,$con) . "')", $con);

				foreach ($spawn->npc as $npc)
				{
					$npcId = (int) $npc['id'];
					$cnt = (int) ($npc['count'] ?? 1);
					mysql_query("insert into knightspawnlist (npc_templateid,locx,locy,locz,count,loc_id,is_approximate)
						values ($npcId, $cx, $cy, $cz, $cnt, '" . mysql_real_escape_string($zone,$con) . "', 1)", $con);
					$count++;
				}
			}
		}
		echo "<p>Импортировано строк спавна: $count (файлов обработано: " . count($files) . ")</p>";
	}
}

echo "</center></body></html>";

?>
