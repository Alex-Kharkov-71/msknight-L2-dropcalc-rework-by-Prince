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

/* SERVER NOTES
The system will only operate assuming that telnet is active to the server.
Put the telnet configuration in to the config.php file.
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
$touser = input_check($_REQUEST['touser'],1);
$userid = input_check($_REQUEST['userid'],0);
$action = input_check($_REQUEST['action'],0);

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
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "<p class=\"popup\">Could Not Connect</p>";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{
		die('Wrap_start could not change to logserver database: ' . mysql_error());
	}
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

	echo "<p class=\"popup\">Make $touser a GM</p>";

	if ($user_access_lvl < $sec_inc_admin)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{

		if ($action)
		{
			$sql = "select accessLevel from access_levels order by accessLevel desc limit 1";
			$result = mysql_query($sql,$con2);
			$entry_level = mysql_result($result,0,"accessLevel");
			$sql = "select account_name from characters where char_name = '$touser'";
			$result = mysql_query($sql,$con2);
			$accname = mysql_result($result,0,"account_name");
			$sql = "update $dblog_l2jdb.accounts set accessLevel = '$sec_inc_gmlevel' where accessLevel < '$sec_inc_gmlevel' and login = '$accname'";
			$result = mysql_query($sql,$con2);
			$sql = "update $dblog_l2jdb.knightdrop set access_level = '$sec_inc_gmlevel' where access_level < '$sec_inc_gmlevel' and name = '$accname'";
			$result = mysql_query($sql,$con2);
			$sql = "update characters set accesslevel = '$entry_level' where accesslevel < 1 and charId = '$userid'";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4322, 1, 'Wind Walk', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4323, 1, 'Shield', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4324, 1, 'Bless The Body', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4325, 1, 'Vampiric Rage', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4326, 1, 'Regeneration', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4327, 1, 'Haste', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4328, 1, 'Bless The Soul', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4329, 1, 'Acumen', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4330, 1, 'Concentration', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4331, 1, 'Empower', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4338, 1, 'Life Cubic', 0)";
			$result = mysql_query($sql,$con);

			$sql = "delete from character_shortcuts where charId = '$userid' and page = '9'";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '0', '9', '2', '4322', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '1', '9', '2', '4323', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '2', '9', '2', '4324', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '3', '9', '2', '4325', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '4', '9', '2', '4326', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '5', '9', '2', '4327', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '6', '9', '2', '4328', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '7', '9', '2', '4329', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '8', '9', '2', '4330', '1', '0')";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '9', '9', '2', '4331', '1', '0')";
			$result = mysql_query($sql,$con);


			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 4, 2, 'Dash', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 239, 4, 'Expertise S', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 268, 1, 'Song Of Wind', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 298, 1, 'Totem Spirit Rabbit', 0)";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 7029, 4, 'Super Haste', 0)";
			$result = mysql_query($sql,$con);
			$sql = "delete from character_shortcuts where charId = '$userid' and page = '0' and slot = '7'";
			$result = mysql_query($sql,$con);
			$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '7', '0', '2', '7029', '1', '0')";
			$result = mysql_query($sql,$con);

			$sql = "delete from character_skills where charId = '$userid' and skill_id = '150'";		// remove weight limit before adding, in case a lower limit already exists.
			$result = mysql_query($sql,$con);
			$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) VALUES ($userid, 150, 3, 'Weight Limit 3', 0)";
			$result = mysql_query($sql,$con);

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9AKF'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Attack Force', '9AKF', '3,0,0,/attackforce;')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9RES'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Resurect', '9RES', '3,0,0,//res;')";
				$result = mysql_query($sql,$con);
				$sql = "delete from character_shortcuts where charId = '$userid' and page = '0' and slot = '10'";
				$result = mysql_query($sql,$con);
				$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '10', '0', '4', '$id', '-1', '0')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9ADM'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Admin Menu', '9ADM', '3,0,0,//admin;')";
				$result = mysql_query($sql,$con);
				$sql = "delete from character_shortcuts where charId = '$userid' and page = '0' and slot = '11'";
				$result = mysql_query($sql,$con);
				$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '11', '0', '4', '$id', '-1', '0')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9HEL'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Heal', '9HEL', '3,0,0,//heal;')";
				$result = mysql_query($sql,$con);
				$sql = "delete from character_shortcuts where charId = '$userid' and page = '0' and slot = '8'";
				$result = mysql_query($sql,$con);
				$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '8', '0', '4', '$id', '-1', '0')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9KIL'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Kill', '9KIL', '3,0,0,//kill;')";
				$result = mysql_query($sql,$con);
				$sql = "delete from character_shortcuts where charId = '$userid' and page = '0' and slot = '9'";
				$result = mysql_query($sql,$con);
				$sql = "insert into character_shortcuts (charId, slot, page, type, shortcut_id, level, class_index) Values ('$userid', '9', '0', '4', '$id', '-1', '0')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9GMS'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'GM Shop', '9GMS', '3,0,0,//gmshop;')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9DEL'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Delete', '9DEL', '3,0,0,//delete;')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9PAR'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Paralyse', '9PAR', '3,0,0,//para;')";
				$result = mysql_query($sql,$con);
			}

			$sql = "select id from character_macroses where charId = '$userid' and acronym = '9UPA'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				$sql = "select id from character_macroses where charId = '$userid' order by id desc limit 1";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$id = mysql_result($result,0,"id") + 1;
				}
				else
				{	$id = 1000;	}
				$sql = "insert into character_macroses (charId, id, icon, name, acronym, commands) VALUES ('$userid', '$id', '0', 'Un-Paralyse', '9UPA', '3,0,0,//unpara;')";
				$result = mysql_query($sql,$con);
			}

			echo "<p class=\"popup\">$touser is now a GM.</p>";
		}
		else
		{
			echo "<p class=\"popup\">Are you sure that you want to<br>make $touser a GM?</p>";
			echo "<center><form action=\"givegm.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"touser\" type=\"hidden\" value=\"$touser\"><input name=\"userid\" type=\"hidden\" value=\"$userid\"><input name=\"action\" type=\"hidden\" value=\"yes\"><input value=\"Yes\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form></center>";
		}


	}
}

echo "</center></body></html>";

?>