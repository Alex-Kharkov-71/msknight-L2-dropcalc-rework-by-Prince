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

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

echo "<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>";

$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
			</head>
			<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
			<center><p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
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
		$result = mysql_query("CREATE TABLE IF NOT EXISTS `knightetcitem` (
  `item_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `icon` varchar(50) NOT NULL DEFAULT '0',
  `crystallizable` enum('true','false') NOT NULL DEFAULT 'false',
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `consume_type` varchar(9) NOT NULL DEFAULT 'normal',
  `material` varchar(11) NOT NULL DEFAULT 'wood',
  `crystal_type` varchar(4) NOT NULL DEFAULT 'none',
  `duration` mediumint(5) NOT NULL DEFAULT '-1',
  `time` mediumint(5) NOT NULL DEFAULT '-1',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `crystal_count` smallint(4) unsigned NOT NULL DEFAULT '0',
  `sellable` enum('true','false') NOT NULL DEFAULT 'false',
  `dropable` enum('true','false') NOT NULL DEFAULT 'false',
  `destroyable` enum('true','false') NOT NULL DEFAULT 'false',
  `tradeable` enum('true','false') NOT NULL DEFAULT 'false',
  `depositable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_stackable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_questitem` enum('true','false') NOT NULL DEFAULT 'false',
  `skill` varchar(70) NOT NULL DEFAULT '0-0;',
  PRIMARY KEY (`item_id`))",$con);
		$result = mysql_query("CREATE TABLE IF NOT EXISTS `knightweapon` (
  `item_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(120) NOT NULL DEFAULT '0',
  `icon` varchar(50) NOT NULL DEFAULT '0',
  `bodypart` varchar(15) NOT NULL DEFAULT 'none',
  `crystallizable` enum('true','false') NOT NULL DEFAULT 'false',
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `soulshots` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `spiritshots` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `material` varchar(11) NOT NULL DEFAULT 'wood',
  `crystal_type` varchar(4) NOT NULL DEFAULT 'none',
  `p_dam` smallint(4) NOT NULL DEFAULT '0',
  `rnd_dam` smallint(3) NOT NULL DEFAULT '0',
  `weaponType` varchar(20) NOT NULL DEFAULT 'none',
  `critical` smallint(3) NOT NULL DEFAULT '0',
  `hit_modify` tinyint(2) NOT NULL DEFAULT '0',
  `avoid_modify` tinyint(2) NOT NULL DEFAULT '0',
  `shield_def` smallint(4) NOT NULL DEFAULT '0',
  `shield_def_rate` smallint(3) NOT NULL DEFAULT '0',
  `atk_speed` smallint(4) NOT NULL DEFAULT '0',
  `mp_consume` tinyint(1) NOT NULL DEFAULT '0',
  `m_dam` smallint(4) NOT NULL DEFAULT '0',
  `duration` mediumint(5) NOT NULL DEFAULT '-1',
  `time` mediumint(5) NOT NULL DEFAULT '-1',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `crystal_count` smallint(4) unsigned NOT NULL DEFAULT '0',
  `sellable` enum('true','false') NOT NULL DEFAULT 'false',
  `dropable` enum('true','false') NOT NULL DEFAULT 'false',
  `destroyable` enum('true','false') NOT NULL DEFAULT 'false',
  `tradeable` enum('true','false') NOT NULL DEFAULT 'false',
  `depositable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_stackable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_questitem` enum('true','false') NOT NULL DEFAULT 'false',
  `change_weaponId` smallint(5) unsigned NOT NULL DEFAULT '0',
  `skill` varchar(70) NOT NULL DEFAULT '0-0;',
  PRIMARY KEY (`item_id`))",$con);
		$result = mysql_query("CREATE TABLE IF NOT EXISTS `knightarmour` (
  `item_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(120) NOT NULL DEFAULT '',
  `icon` varchar(50) NOT NULL DEFAULT '0',
  `bodypart` varchar(15) NOT NULL DEFAULT 'none',
  `crystallizable` enum('true','false') NOT NULL DEFAULT 'false',
  `armor_type` varchar(5) NOT NULL DEFAULT 'none',
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `material` varchar(15) NOT NULL DEFAULT 'wood',
  `crystal_type` varchar(4) NOT NULL DEFAULT 'none',
  `avoid_modify` tinyint(2) NOT NULL DEFAULT '0',
  `duration` mediumint(5) NOT NULL DEFAULT '-1',
  `time` mediumint(5) NOT NULL DEFAULT '-1',
  `p_def` smallint(3) NOT NULL DEFAULT '0',
  `m_def` smallint(3) NOT NULL DEFAULT '0',
  `mp_bonus` smallint(3) NOT NULL DEFAULT '0',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `crystal_count` smallint(4) unsigned NOT NULL DEFAULT '0',
  `sellable` enum('true','false') NOT NULL DEFAULT 'false',
  `dropable` enum('true','false') NOT NULL DEFAULT 'false',
  `destroyable` enum('true','false') NOT NULL DEFAULT 'false',
  `tradeable` enum('true','false') NOT NULL DEFAULT 'false',
  `depositable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_stackable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_questitem` enum('true','false') NOT NULL DEFAULT 'false',
  `skill` varchar(70) DEFAULT '0-0;',
  PRIMARY KEY (`item_id`))",$con);
		if ($next == 1)
		{
			$sql = "truncate table knightetcitem";	
			$result = mysql_query($sql,$con);
			$sql = "truncate table knightweapon";	
			$result = mysql_query($sql,$con);
			$sql = "truncate table knightarmour";	
			$result = mysql_query($sql,$con);
		}


		$file_loc_a = $server_dir . 'data' . $svr_dir_delimit . 'stats' . $svr_dir_delimit . 'items' . $svr_dir_delimit;
		require_once(__DIR__ . '/xmlparse_common.php');
		$item_count = 0;
		$name = xip_rangeName($ia);
		$file_loc = $file_loc_a . $name . "00-" . $name . "99.xml";
		echo "<p>Item Import Routine</p><p>Processing - " . $name . "00-" . $name . "99.xml</p>";
		if (file_exists($file_loc))
		{
			if ($next < 1000)
			{	echo "<META content=\"5;url=importitems.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=$next\" http-equiv=refresh >\n";	}

			$xml = xip_loadXml($file_loc);
			if ($xml)
			{
				foreach ($xml->item as $item)
				{
					$item_id = (int) $item['id'];
					$item_type = strtolower((string) $item['type']);
					$item_name = (string) $item['name'];
					$sets = xip_readSets($item);
					$stats = xip_readStats($item);

					$item_d_icon = $sets['icon'] ?? '';
					$item_d_bodypart = $sets['bodypart'] ?? 'none';
					$item_d_weight = (int) ($sets['weight'] ?? 0);
					$item_d_price = (int) ($sets['price'] ?? 0);
					$item_d_material = $sets['material'] ?? 'wood';
					$item_d_crystal_type = $sets['crystal_type'] ?? 'none';
					$item_d_soulshots = (int) ($sets['soulshots'] ?? 0);
					$item_d_spiritshots = (int) ($sets['spiritshots'] ?? 0);
					$item_d_weaponType = $sets['weapon_type'] ?? 'none';
					$item_d_armor_type = $sets['armor_type'] ?? 'none';
					$item_d_consume_type = $sets['consume_type'] ?? 'normal';
					$item_d_crystal_count = (int) ($sets['crystal_count'] ?? 0);
					$item_d_rnd_dam = xip_escNum($stats['randomDamage'] ?? null);
					$item_d_atk_speed = xip_escNum($stats['pAtkSpd'] ?? null);
					$item_d_p_dam = xip_escNum($stats['pAtk'] ?? null);
					$item_d_m_dam = xip_escNum($stats['mAtk'] ?? null);
					// pDef для брони, sDef - для щитов (у щитов pDef не задаётся).
					$item_d_p_def = xip_escNum($stats['pDef'] ?? ($stats['sDef'] ?? null));
					$item_d_m_def = xip_escNum($stats['mDef'] ?? null);
					$item_d_critical = xip_escNum($stats['critRate'] ?? null);
					$item_d_avoid_modify = xip_escNum($stats['rEvas'] ?? null);
					// crystallizable v XML net - eto proizvodnoe ot crystal_count (esli zadan - kristallizuetsya).
					$item_d_crystallizable = isset($sets['crystallizable']) ? ($sets['crystallizable']==='true'?'true':'false') : (((int) ($sets['crystal_count'] ?? 0)) > 0 ? 'true' : 'false');
					$item_d_tradeable = isset($sets['is_tradable']) ? ($sets['is_tradable']==='true'?'true':'false') : 'true';
					$item_d_dropable = isset($sets['is_dropable']) ? ($sets['is_dropable']==='true'?'true':'false') : 'true';
					// Flagi v XML ukazany tolko kogda otlichayutsya ot umolchaniya, a umolchanie - true.
					$item_d_sellable = isset($sets['is_sellable']) ? ($sets['is_sellable']==='true'?'true':'false') : 'true';
					$item_d_destroyable = isset($sets['is_destroyable']) ? ($sets['is_destroyable']==='true'?'true':'false') : 'true';
					$item_d_depositable = isset($sets['is_depositable']) ? ($sets['is_depositable']==='true'?'true':'false') : 'true';
					$item_d_is_stackable = xip_bool2enum($sets, 'is_stackable', 'false');
					$item_d_is_questitem = xip_bool2enum($sets, 'is_questitem', 'false');
					$item_d_duration = -1;
					$item_d_time = -1;
					$item_d_mp_bonus = 0;
					$item_d_change_weaponId = (int) ($sets['change_weaponId'] ?? 0);
					$item_d_shield_def = 0;
					$item_d_shield_def_rate = 0;
					$item_d_hit_modify = 0;
					$item_d_mp_consume = (int) ($sets['mp_consume'] ?? 0);
					$item_d_skill = "0-0;";

					if ($item_type == "weapon")
					{
						$result = mysql_query("insert into `knightweapon` (`item_id`, `name`, `icon`, `bodypart`, `crystallizable`, `weight`, `soulshots`, `spiritshots`, `material`, `crystal_type`, `p_dam`, `rnd_dam`, `weaponType`, `critical`, `hit_modify`, `avoid_modify`, `shield_def`, `shield_def_rate`, `atk_speed`, `mp_consume`, `m_dam`, `duration`, `time`, `price`, `crystal_count`, `sellable`, `dropable`, `destroyable`, `tradeable`, `depositable`, `change_weaponId`, `skill`, `is_stackable`, `is_questitem`) values
(\"$item_id\", \"$item_name\", \"$item_d_icon\", \"$item_d_bodypart\", \"$item_d_crystallizable\", \"$item_d_weight\", \"$item_d_soulshots\", \"$item_d_spiritshots\", \"$item_d_material\", \"$item_d_crystal_type\", \"$item_d_p_dam\", \"$item_d_rnd_dam\", \"$item_d_weaponType\", \"$item_d_critical\", \"$item_d_hit_modify\", \"$item_d_avoid_modify\", \"$item_d_shield_def\", \"$item_d_shield_def_rate\", \"$item_d_atk_speed\", \"$item_d_mp_consume\", \"$item_d_m_dam\", \"$item_d_duration\", \"$item_d_time\", \"$item_d_price\", \"$item_d_crystal_count\", \"$item_d_sellable\", \"$item_d_dropable\", \"$item_d_destroyable\", \"$item_d_tradeable\", \"$item_d_depositable\", \"$item_d_change_weaponId\", \"$item_d_skill\", \"$item_d_is_stackable\", \"$item_d_is_questitem\")", $con);
					}
					elseif ($item_type == "armor")
					{
						$result = mysql_query("insert into `knightarmour` (`item_id`, `name`, `icon`, `bodypart`, `crystallizable`, `weight`, `armor_type`, `material`, `crystal_type`, `avoid_modify`, `time`, `p_def`, `m_def`, `mp_bonus`, `duration`, `price`, `crystal_count`, `sellable`, `dropable`, `destroyable`, `tradeable`, `depositable`, `skill`, `is_stackable`, `is_questitem`) values
(\"$item_id\", \"$item_name\", \"$item_d_icon\",  \"$item_d_bodypart\", \"$item_d_crystallizable\", \"$item_d_weight\", \"$item_d_armor_type\", \"$item_d_material\", \"$item_d_crystal_type\", \"$item_d_avoid_modify\", \"$item_d_time\", \"$item_d_p_def\", \"$item_d_m_def\", \"$item_d_mp_bonus\", \"$item_d_duration\", \"$item_d_price\", \"$item_d_crystal_count\", \"$item_d_sellable\", \"$item_d_dropable\", \"$item_d_destroyable\", \"$item_d_tradeable\", \"$item_d_depositable\", \"$item_d_skill\", \"$item_d_is_stackable\", \"$item_d_is_questitem\")", $con);
					}
					else
					{
						$result = mysql_query("insert into `knightetcitem` (`item_id`, `name`, `icon`,  `crystallizable`, `weight`, `consume_type`, `material`, `crystal_type`, `duration`, `time`,  `price`, `crystal_count`, `sellable`, `dropable`, `destroyable`, `tradeable`, `depositable`, `skill`, `is_stackable`, `is_questitem`) values
(\"$item_id\", \"$item_name\", \"$item_d_icon\",  \"$item_d_crystallizable\", \"$item_d_weight\",  \"$item_d_consume_type\", \"$item_d_material\", \"$item_d_crystal_type\", \"$item_d_duration\", \"$item_d_time\",  \"$item_d_price\", \"$item_d_crystal_count\", \"$item_d_sellable\", \"$item_d_dropable\", \"$item_d_destroyable\", \"$item_d_tradeable\", \"$item_d_depositable\", \"$item_d_skill\", \"$item_d_is_stackable\", \"$item_d_is_questitem\")", $con);
					}
					$item_count++;
				}
			}
			echo "<p>Импортировано предметов: $item_count</p>";
		}
		else
		{
			if ($next < 1000)
			{	echo "<META content=\"1;url=importitems.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=$next\" http-equiv=refresh >\n";	}
		}
		if ($next == 1000)
		{	echo "<p>Import Complete</p>";	}
	}
}

echo "</center></body></html>";

?>
