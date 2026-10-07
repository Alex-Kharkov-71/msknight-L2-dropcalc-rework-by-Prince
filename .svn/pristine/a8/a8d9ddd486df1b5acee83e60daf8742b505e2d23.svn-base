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
$next = input_check($_REQUEST['next'],0);
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
		$item_count = 0;

		$item_d_crystallizable= "false";
		$item_d_crystal_type= "none";
		$item_d_sellable= "false";
		$item_d_destroyable= "true";
		$item_d_tradeable= "true";
		$item_d_depositable= "true";
		$item_d_is_stackable= "false";
		$item_d_is_questitem= "false";
		$item_d_dropable= "true";
		$item_d_duration= -1;
		$item_d_time= -1;
		$item_d_crystal_count= 0;
		$item_d_atk_speed= 0;
		$item_d_p_dam= 0;
		$item_d_rnd_dam= 0;
		$item_d_m_dam= 0;
		$item_d_p_def= 0;
		$item_d_m_def= 0;
		$item_d_icon= "";

		if ($ia == 0)
		{	$name = "000";	}
		elseif ($ia < 10)
		{	$name = "00" . $ia;	}
		elseif ($ia < 100)
		{	$name = "0" . $ia;	}
		else
		{	$name = "" . $ia;	}
		$file_loc = $file_loc_a . $name . "00-" . $name . "99.xml";
		$item_run = 0;
echo "<p>Item Import Routine</p><p>Processing - " . $name . "00-" . $name . "99.xml</p>";
		if (file_exists($file_loc))
		{
			if ($next < 1000)
			{	echo "<META content=\"5;url=importitems.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=$next\" http-equiv=refresh >\n";	}
			$lines = file($file_loc);
			$line_nums = count($lines);
			foreach ($lines as $line_num => $line) 
			{
				$keywords = preg_split("/[\s]*[\"][\s]*/", $line);
				$id = trim($keywords[0]);
				
				if ($item_run==1)
				{
					if ($id!="</item>")
					{
						$targ=strtolower(trim($keywords[1]));
						$content=trim($keywords[3]);
						$cont2=trim($keywords[5]);
						$purpose=trim($keywords[5]);
						if ($targ=="icon")	{	$item_d_icon=$content;	}
						if ($targ=="bodypart")	{	$item_d_bodypart=$content;	}
						if ($targ=="weight")	{	$item_d_weight=$content;	}
						if ($targ=="price")	{	$item_d_price=$content;	}
						if ($targ=="material")	{	$item_d_material=$content;	}
						if ($targ=="soulshots")	{	$item_d_soulshots=$content;	}
						if ($targ=="spiritshots")	{	$item_d_spiritshots=$content;	}
						if ($targ=="weapon_type")	{	$item_d_weaponType=$content;	}
						if ($targ=="crystal_count")	{	$item_d_crystal_count=$content;	}
						if ($targ=="crystal_type")	{	$item_d_crystal_type=$content;	}
						if ($targ=="random_damage")	{	$item_d_rnd_dam=$content;	}
						if ($content=="pAtkSpd")	{	$item_d_atk_speed=$cont2;	}
						if ($content=="pAtk")	{	$item_d_p_dam=$cont2;	}
						if ($content=="mAtk")	{	$item_d_m_dam=$cont2;	}
						if ($targ=="is_tradable")	{	$item_d_tradeable=$content;	}
						if ($targ=="is_dropable")	{	$item_d_dropable=$content;	}
						if ($targ=="is_sellable")	{	$item_d_sellable=$content;	}
						if ($targ=="is_stackable")	{	$item_d_is_stackable=$content;	}
						if ($targ=="is_questitem")	{	$item_d_is_questitem=$content;	}
						if ($content=="pAtkSpd")	{	$item_d_atk_speed=$cont2;	}
						if (($content=="pDef") && (strpos($purpose,"enchant") == 0))	{	$item_d_p_def=$cont2;	}
						if (($content=="mDef") && (strpos($purpose,"enchant") == 0))	{	$item_d_m_def=$cont2;	}
					}
					else
					{
						$item_run=0;
						if ($item_type == "weapon")
						{	
							$result = mysql_query("insert into `knightweapon` (`item_id`, `name`, `icon`, `bodypart`, `crystallizable`, `weight`, `soulshots`, `spiritshots`, `material`, `crystal_type`, `p_dam`, `rnd_dam`, `weaponType`, `critical`, `hit_modify`, `avoid_modify`, `shield_def`, `shield_def_rate`, `atk_speed`, `mp_consume`, `m_dam`, `duration`, `time`, `price`, `crystal_count`, `sellable`, `dropable`, `destroyable`, `tradeable`, `depositable`, `change_weaponId`, `skill`, `is_stackable`, `is_questitem`) values
(\"$item_id\", \"$item_name\", \"$item_d_icon\", \"$item_d_bodypart\", \"$item_d_crystallizable\", \"$item_d_weight\", \"$item_d_soulshots\", \"$item_d_spiritshots\", \"$item_d_material\", \"$item_d_crystal_type\", \"$item_d_p_dam\", \"$item_d_rnd_dam\", \"$item_d_weaponType\", \"$item_d_critical\", \"$item_d_hit_modify\", \"$item_d_avoid_modify\", \"$item_d_shield_def\", \"$item_d_shield_def_rate\", \"$item_d_atk_speed\", \"$item_d_mp_consume\", \"$item_d_m_dam\", \"$item_d_duration\", \"$item_d_time\", \"$item_d_price\", \"$item_d_crystal_count\", \"$item_d_sellable\", \"$item_d_dropable\", \"$item_d_destroyable\", \"$item_d_tradeable\", \"$item_d_depositable\", \"$item_d_change_weaponId\", \"$item_d_skill\", \"$item_d_is_stackable\", \"$item_d_is_questitem\")",$con);
						}
						elseif ($item_type == "armor")
						{	
							$result = mysql_query("insert into `knightarmour` (`item_id`, `name`, `icon`, `bodypart`, `crystallizable`, `weight`, `armor_type`, `material`, `crystal_type`, `avoid_modify`, `time`, `p_def`, `m_def`, `mp_bonus`, `duration`, `price`, `crystal_count`, `sellable`, `dropable`, `destroyable`, `tradeable`, `depositable`, `skill`, `is_stackable`, `is_questitem`) values
(\"$item_id\", \"$item_name\", \"$item_d_icon\",  \"$item_d_bodypart\", \"$item_d_crystallizable\", \"$item_d_weight\", \"$item_armor_type\", \"$item_d_material\", \"$item_d_crystal_type\", \"$item_d_avoid_modify\", \"$item_d_time\", \"$item_d_p_def\", \"$item_d_m_def\", \"$item_d_mp_bonus\", \"$item_d_duration\", \"$item_d_price\", \"$item_d_crystal_count\", \"$item_d_sellable\", \"$item_d_dropable\", \"$item_d_destroyable\", \"$item_d_tradeable\", \"$item_d_depositable\", \"$item_d_skill\", \"$item_d_is_stackable\", \"$item_d_is_questitem\")",$con);
						}
						else
						{	
							$result = mysql_query("insert into `knightetcitem` (`item_id`, `name`, `icon`,  `crystallizable`, `weight`, `consume_type`, `material`, `crystal_type`, `duration`, `time`,  `price`, `crystal_count`, `sellable`, `dropable`, `destroyable`, `tradeable`, `depositable`, `skill`, `is_stackable`, `is_questitem`) values
(\"$item_id\", \"$item_name\", \"$item_d_icon\",  \"$item_d_crystallizable\", \"$item_d_weight\",  \"$item_consume_type\", \"$item_d_material\", \"$item_d_crystal_type\", \"$item_d_duration\", \"$item_d_time\",  \"$item_d_price\", \"$item_d_crystal_count\", \"$item_d_sellable\", \"$item_d_dropable\", \"$item_d_destroyable\", \"$item_d_tradeable\", \"$item_d_depositable\", \"$item_d_skill\", \"$item_d_is_stackable\", \"$item_d_is_questitem\")",$con);
						}

					$item_d_bodypart= "";
					$item_d_icon= "";
					$item_d_crystallizable= "false";
					$item_d_weight= "";
					$item_d_soulshots= "";
					$item_d_spiritshots= "";
					$item_d_material= "";
					$item_d_crystal_type= "none";
					$item_d_p_dam= 0;
					$item_d_rnd_dam= 0;
					$item_d_weaponType= "";
					$item_d_critical= "";
					$item_d_hit_modify= "";
					$item_d_avoid_modify= "";
					$item_d_shield_def= "";
					$item_d_shield_def_rate= "";
					$item_d_atk_speed= 0;
					$item_d_mp_consume= "";
					$item_d_m_dam= 0;
					$item_d_duration= -1;
					$item_d_time= -1;
					$item_d_price= "";
					$item_d_crystal_count= 0;
					$item_d_sellable= "false";
					$item_d_dropable= "true";
					$item_d_destroyable= "true";
					$item_d_tradeable= "true";
					$item_d_depositable= "true";
					$item_d_change_weaponId= "";
					$item_d_skill= "";
					$item_d_armor_type= "";
					$item_d_avoid_modify= "";
					$item_d_p_def= 0;
					$item_d_m_def= 0;
					$item_d_mp_bonus= "";
					$item_d_is_stackable= "false";
					$item_d_is_questitem= "false";
					}
				}
				else
				{
					$item_id = trim($keywords[1]);
					$item_type = strtolower(trim($keywords[3]));
					$item_name = trim($keywords[5]);
					if (($item_type == "weapon") || ($item_type == "armor") || ($item_type == "etcitem"))
					{	$item_run=1;	}
				}
			}
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
