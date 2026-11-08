<?php 
error_reporting(E_ERROR | E_PARSE);

/*
importloc.php
Import of named locations (towns) from data/zones/*.xml (type TownZone) into knightloc.
Coordinates = centre of the zone polygon. Used for shop locations and location search.
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

		mysql_query("CREATE TABLE IF NOT EXISTS `knightloc` (`name` varchar(100) NOT NULL DEFAULT '', `x` int(11) NOT NULL DEFAULT 0, `y` int(11) NOT NULL DEFAULT 0, KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3", $con);
		mysql_query("truncate table knightloc", $con);
		xip_stat_reset($con, 'locations');

		$dir = $server_dir . 'data' . $svr_dir_delimit . 'zones' . $svr_dir_delimit;
		$files = xip_listXmlFiles($dir);
		$count = 0;
		$seen = array();

		foreach ($files as $path)
		{
			$xml = xip_loadXml($path);
			if (!$xml) continue;
			foreach ($xml->zone as $zone)
			{
				if ((string) $zone['type'] !== 'TownZone') continue;
				$name = trim((string) $zone['name']);
				$sx = 0; $sy = 0; $n = 0;
				foreach ($zone->node as $node)
				{	$sx += (int) $node['X']; $sy += (int) $node['Y']; $n++;	}
				if ($name === '' || $n == 0) continue;
				$x = (int) round($sx / $n); $y = (int) round($sy / $n);
				$key = $name . '|' . $x . '|' . $y;
				if (isset($seen[$key])) continue;
				$seen[$key] = 1;
				xip_q("insert into knightloc (name,x,y) values ('" . mysql_real_escape_string($name, $con) . "', $x, $y)", $con);
				$count++;
			}
		}
		echo "<p>Imported locations (TownZone): $count (files read: " . count($files) . ")</p>";
		xip_stat_flush($con, 'locations');
		xip_stat_report($con, 'locations');
	}
}

echo "</center></body></html>";

?>
