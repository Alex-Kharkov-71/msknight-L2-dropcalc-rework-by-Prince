<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/*
Michelle Knight's Drop Calc - Version 3
Author - Michelle Knight
Copyright 2006
Contact - dropcalc@msknight.com

GNU General Licence
Use and distribute freely, but leave headers intact and make no charge.
Change HTML code as necessary to fit your own site.
Code distributed without warantee or liability as to merchantability as
no charge is made for its use.  Use is at users risk.
*/


include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$next = (int) input_check($_REQUEST['next'],0);
$ia = $next;
$next++;

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

		// PHP8/L2JMobius fix: раньше это была прямая SQL-копия таблицы npc
		// с игрового сервера (create table knightnpc like npc). В
		// современных движках статика NPC в БД сервера больше не хранится -
		// только в XML. Поэтому своя фиксированная схема.
		mysql_query("CREATE TABLE IF NOT EXISTS `knightnpc` (
			`id` INT PRIMARY KEY, `name` VARCHAR(100), `title` VARCHAR(100), `type` VARCHAR(30), `level` INT DEFAULT 1,
			`race` VARCHAR(30), `sex` VARCHAR(10), `exp` FLOAT DEFAULT 0, `sp` FLOAT DEFAULT 0,
			`hp` FLOAT DEFAULT 0, `mp` FLOAT DEFAULT 0, `patk` FLOAT DEFAULT 0, `matk` FLOAT DEFAULT 0,
			`pdef` FLOAT DEFAULT 0, `mdef` FLOAT DEFAULT 0, `atkspd` INT DEFAULT 0, `accuracy` FLOAT DEFAULT 0,
			`attackrange` INT DEFAULT 0, `walkspd` FLOAT DEFAULT 0, `runspd` FLOAT DEFAULT 0,
			`aggro` INT DEFAULT 0, `is_aggressive` INT DEFAULT 0, `rhand` INT DEFAULT 0,
			`lhand` INT DEFAULT 0, `matkspd` INT DEFAULT 0, `isundead` INT DEFAULT 0)", $con);

		// Новая таблица - раньше дроп читался напрямую из таблицы droplist
		// игрового сервера, своей у DropCalc не было. chance - шанс предмета
		// внутри группы, group_chance - шанс самой группы (итоговый % = их
		// произведение/100 - см. замечание в README импортёра).
		// Новая таблица - раньше читалась из npcskills/custom_npcskills на
		// сервере. Данные есть в <skillList> внутри npc XML, просто раньше
		// не сохранялись в отдельную таблицу.
		mysql_query("CREATE TABLE IF NOT EXISTS `knightnpcskills` (
			`npcid` INT, `skillid` INT, `level` INT, KEY `npcid` (`npcid`))", $con);
		mysql_query("truncate table knightnpcskills", $con);

		mysql_query("CREATE TABLE IF NOT EXISTS `knightdroplist` (
			`mobid` INT, `itemid` INT, `min` INT, `max` INT, `chance` FLOAT, `group_chance` FLOAT DEFAULT NULL,
			`sweep` INT DEFAULT 0, `category` INT DEFAULT NULL,
			KEY `mobid` (`mobid`), KEY `itemid` (`itemid`))", $con);

		if ($next == 1)
		{
			mysql_query("truncate table knightnpc", $con);
			mysql_query("truncate table knightdroplist", $con);
		}

		$file_loc_a = $server_dir . 'data' . $svr_dir_delimit . 'stats' . $svr_dir_delimit . 'npcs' . $svr_dir_delimit;
		$name = xip_rangeName($ia);
		$file_loc = $file_loc_a . $name . "00-" . $name . "99.xml";
		$npc_count = 0;
		$drop_count = 0;
		echo "<p>NPC Import Routine</p><p>Processing - " . $name . "00-" . $name . "99.xml</p>";

		if (file_exists($file_loc))
		{
			if ($next < 1000)
			{	echo "<META content=\"5;url=importnpc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=$next\" http-equiv=refresh >\n";	}

			$xml = xip_loadXml($file_loc);
			if ($xml)
			{
				foreach ($xml->npc as $npc)
				{
					$id = (int) $npc['id'];
					$level = (int) ($npc['level'] ?? 1);
					$type = (string) $npc['type'];
					$name_ = (string) ($npc['name'] ?? '');
					$title = (string) ($npc['title'] ?? '');
					$race = (string) ($npc->race ?? '');
					$sex = (string) ($npc->sex ?? '');
					$exp = (float) ($npc->acquire['exp'] ?? 0);
					$sp = (float) ($npc->acquire['sp'] ?? 0);

					$stats = $npc->stats ?? null;
					$hp=$mp=$patk=$matk=$pdef=$mdef=$atkspd=$accuracy=$range=$walk=$run=0;
					if ($stats)
					{
						$hp = (float) ($stats->vitals['hp'] ?? 0);
						$mp = (float) ($stats->vitals['mp'] ?? 0);
						$patk = (float) ($stats->attack['physical'] ?? 0);
						$matk = (float) ($stats->attack['magical'] ?? 0);
						$atkspd = (int) ($stats->attack['attackSpeed'] ?? 0);
						$accuracy = (float) ($stats->attack['accuracy'] ?? 0);
						$range = (int) ($stats->attack['range'] ?? 0);
						$pdef = (float) ($stats->defence['physical'] ?? 0);
						$mdef = (float) ($stats->defence['magical'] ?? 0);
						$walk = (float) ($stats->speed->walk['ground'] ?? 0);
						$run = (float) ($stats->speed->run['ground'] ?? 0);
					}
					$aggro = (int) ($npc->ai['aggroRange'] ?? 0);
					$isAggr = ((string) ($npc->ai['isAggressive'] ?? 'false')) === 'true' ? 1 : 0;
					$rhand = (int) ($npc->equipment['rhand'] ?? 0);
					// lhand/matkspd: такого параметра в современном XML нет вообще - честный 0.
					// isundead: отдельного флага нет, но это и есть race=UNDEAD.
					$isUndead = (strtoupper($race) === 'UNDEAD') ? 1 : 0;

					mysql_query("insert into knightnpc (id,name,title,type,level,race,sex,exp,sp,hp,mp,patk,matk,pdef,mdef,
						atkspd,accuracy,attackrange,walkspd,runspd,aggro,is_aggressive,rhand,lhand,matkspd,isundead) values (
						$id, '" . mysql_real_escape_string($name_,$con) . "', '" . mysql_real_escape_string($title,$con) . "',
						'" . mysql_real_escape_string($type,$con) . "', $level, '" . mysql_real_escape_string($race,$con) . "',
						'" . mysql_real_escape_string($sex,$con) . "', $exp, $sp, $hp, $mp, $patk, $matk, $pdef, $mdef,
						$atkspd, $accuracy, $range, $walk, $run, $aggro, $isAggr, $rhand, 0, 0, $isUndead)", $con);
					$npc_count++;

					if (isset($npc->skillList))
					{
						foreach ($npc->skillList->skill as $sk)
						{
							$skId = (int) $sk['id'];
							$skLvl = (int) $sk['level'];
							mysql_query("insert into knightnpcskills (npcid,skillid,level) values ($id, $skId, $skLvl)", $con);
						}
					}

					if (isset($npc->dropLists))
					{
						$cat = 0;
						foreach ($npc->dropLists->drop->group ?? [] as $group)
						{
							$groupChance = (float) $group['chance'];
							foreach ($group->item as $it)
							{
								$iid=(int)$it['id']; $min=(int)$it['min']; $max=(int)$it['max']; $chance=(float)$it['chance'];
								mysql_query("insert into knightdroplist (mobid,itemid,min,max,chance,group_chance,sweep,category)
									values ($id,$iid,$min,$max,$chance,$groupChance,0,$cat)", $con);
								$drop_count++;
							}
							$cat++;
						}
						foreach ($npc->dropLists->spoil->item ?? [] as $it)
						{
							$iid=(int)$it['id']; $min=(int)$it['min']; $max=(int)$it['max']; $chance=(float)$it['chance'];
							mysql_query("insert into knightdroplist (mobid,itemid,min,max,chance,sweep)
								values ($id,$iid,$min,$max,$chance,1)", $con);
							$drop_count++;
						}
					}
				}
			}
			echo "<p>Импортировано NPC: $npc_count, строк дропа/спойла: $drop_count</p>";
		}
		else
		{
			if ($next < 1000)
			{	echo "<META content=\"1;url=importnpc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=$next\" http-equiv=refresh >\n";	}
		}
		if ($next == 1000)
		{	echo "<p>NPC import complete.</p>";	}
	}
}

echo "</center></body></html>";

?>
