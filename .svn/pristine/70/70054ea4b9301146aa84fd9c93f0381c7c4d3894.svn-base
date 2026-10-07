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

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

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
		$sql="drop table knightnpc";
		$result = mysql_query($sql,$con);
		$sql="create table knightnpc like npc;";
		$result = mysql_query($sql,$con);
		$sql="alter table knightnpc drop primary key;";
		$result = mysql_query($sql,$con);
		$sql="alter table knightnpc modify column `id` mediumint(7) unsigned NOT NULL;";
		$result = mysql_query($sql,$con);
		$sql="alter table knightnpc add primary key (`id`);";
		$result = mysql_query($sql,$con);
		$sql="insert into knightnpc select * from npc;";
		$result = mysql_query($sql,$con);
		$sql="insert into knightnpc select * from custom_npc;";
		$result = mysql_query($sql,$con);
		$sql="alter table knightnpc add column `aggro` smallint(4) unsigned NOT NULL DEFAULT '0';";
		$result = mysql_query($sql,$con);
		$sql="update knightnpc, npcaidata set knightnpc.aggro = npcaidata.aggro where knightnpc.id=npcaidata.npcId;";
		$result = mysql_query($sql,$con);
		$sql="update knightnpc, custom_npcaidata set knightnpc.aggro = custom_npcaidata.aggro where knightnpc.id=custom_npcaidata.npcId;";
		$result = mysql_query($sql,$con);
		$sql="update knightnpc set knightnpc.aggro = 1 where aggro>0;";
		$result = mysql_query($sql,$con);
		echo "<p>NPC import complete.</p>";
	}
}

echo "</center></body></html>";

?>
