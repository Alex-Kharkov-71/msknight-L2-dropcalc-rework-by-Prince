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
$action = input_check($_REQUEST['action'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
		$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
		mysql_query("SET NAMES 'utf8'", $con2);
		if (!$con2)
		{
			echo "Could Not Connect";
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
			echo "Could Not Connect";
			die('Could not connect: ' . mysql_error());
		}		
		if (!mysql_select_db("$db_l2jdb",$con))
		{
			die('Could not change to L2J database: ' . mysql_error());
		}
		echo "<center><table class=\"blanktab\"><tr><td class=\"noborderback\">";
		echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">Database Report</h2><p class=\"dropmain\">&nbsp;</p>";

		if ($action == 'go')
		{
			if (!$account_safe)
			{
				$sql = "delete FROM $dblog_l2jdb.accounts WHERE login NOT IN (SELECT account_name FROM characters)";
				$result = mysql_query($sql,$con2);
				$sql = "delete FROM $dblog_l2jdb.knightdrop WHERE name NOT IN (SELECT account_name FROM characters)";
				$result = mysql_query($sql,$con2);
			}
			$sql = "delete FROM characters WHERE account_name NOT IN (SELECT login FROM $dblog_l2jdb.accounts)";
			$result = mysql_query($sql,$con2);
			$sql = "delete FROM clan_data WHERE clan_id NOT IN (SELECT distinct clanid FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM items WHERE owner_id NOT IN (SELECT charId FROM characters) AND owner_id NOT IN (SELECT clan_id FROM clan_data)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_friends WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_hennas WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_macroses WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_quests WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_recipebook WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_shortcuts WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_skills WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_skills_save WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM character_subclasses WHERE charId NOT IN (SELECT charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM clan_wars WHERE clan1 NOT IN (SELECT distinct clan_id FROM clan_data) or clan1 NOT IN (SELECT distinct clan_id FROM clan_data)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM heroes WHERE charId NOT IN (SELECT distinct charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM olympiad_nobles WHERE charId NOT IN (SELECT distinct charId FROM characters)";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM pets WHERE item_obj_id NOT IN (SELECT object_id FROM items where item_id in (6648,6650,4425,6649,2375,4424,4423,4422,3502,3501,3500))";
			$result = mysql_query($sql,$con);
			$sql = "delete FROM siege_clans WHERE clan_id NOT IN (SELECT clan_id FROM clan_data)";
			$result = mysql_query($sql,$con);
			$sql = "select object_id from items where item_id = '6648' or item_id = '6650' or item_id = '4425' or item_id = '6649' or item_id = '2375' or item_id = '4424' or item_id = '4423' or item_id = '4422' or item_id = '3502' or item_id = '3501' or item_id = '3500'";
			$result = mysql_query($sql,$con);
			$counting = mysql_num_rows($result);
			$i = 0;
			while ($i < $counting)
			{
				$object_id = mysql_result($result,$i,"object_id");
				$sql = "select level FROM pets WHERE item_obj_id = '$object_id'";
				$result2 = mysql_query($sql,$con);
				$count2 = mysql_num_rows($result2);
				if (!$count2)
				{	
					$sql = "delete from items where object_id = '$object_id'";	
					$result2 = mysql_query($sql,$con);
				}
				$i++;
			}
			echo "<h2 class=\"dropmain\">After this pass ...</h2><p class=\"dropmain\">&nbsp;</p>";
		}

		$sql = "select login FROM $dblog_l2jdb.accounts WHERE login NOT IN (SELECT account_name FROM characters)";
		$result = mysql_query($sql,$con2);
		$count = mysql_num_rows($result);
		if ($account_safe)
		{	echo "<p class=\"dropmainwhite\"><font color=$green_code>Accounts without characters -  $count</font></p>";	}
		else
		{	echo "<p class=\"dropmainwhite\">Accounts without characters -  $count</p>";	}

		$sql = "select charId FROM characters WHERE account_name NOT IN (SELECT login FROM $dblog_l2jdb.accounts)";
		$result = mysql_query($sql,$con2);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Characters without accounts - $count</p>";

		$sql = "select item_id FROM items WHERE owner_id NOT IN (SELECT charId FROM characters) AND owner_id NOT IN (SELECT clan_id FROM clan_data)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Items without owners - $count</p>";

		$sql = "select charId FROM character_friends WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Friends lost  - $count</p>";

		$sql = "select charId FROM character_hennas WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Hennas lost  - $count</p>";

		$sql = "select id FROM character_macroses WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Unowned Macros  - $count</p>";

		$sql = "select charId FROM character_quests WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Unowned Quests  - $count</p>";

		$sql = "select charId FROM character_recipebook WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Lost Recipe Books  - $count</p>";

		$sql = "select charId FROM character_shortcuts WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Unowned Shortcuts  - $count</p>";

		$sql = "select charId FROM character_skills WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Lost Skills  - $count</p>";

		$sql = "select charId FROM character_skills_save WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Lost Skill Saves  - $count</p>";

		$sql = "select charId FROM character_subclasses WHERE charId NOT IN (SELECT charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Lost Subclasses  - $count</p>";

		$sql = "select clan_id FROM clan_data WHERE clan_id NOT IN (SELECT distinct clanid FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Clans Without Members  - $count</p>";

		$sql = "select clan1 FROM clan_wars WHERE clan1 NOT IN (SELECT distinct clan_id FROM clan_data) or clan1 NOT IN (SELECT distinct clan_id FROM clan_data)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">One sided or no sided wars  - $count</p>";

		$sql = "select charId FROM heroes WHERE charId NOT IN (SELECT distinct charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Lost Heroes  - $count</p>";

		$sql = "select charId FROM olympiad_nobles WHERE charId NOT IN (SELECT distinct charId FROM characters)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Lost Olympiad Nobles  - $count</p>";

		$sql = "select item_obj_id FROM pets WHERE item_obj_id NOT IN (SELECT object_id FROM items where item_id in (6648,6650,4425,6649,2375,4424,4423,4422,3502,3501,3500))";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Orphaned Pets  - $count</p>";

		$sql = "select object_id from items where item_id = '6648' or item_id = '6650' or item_id = '4425' or item_id = '6649' or item_id = '2375' or item_id = '4424' or item_id = '4423' or item_id = '4422' or item_id = '3502' or item_id = '3501' or item_id = '3500'";
		$result = mysql_query($sql,$con);
		$count = 0;
		$counting = mysql_num_rows($result);
		$i = 0;
		while ($i < $counting)
		{
			$object_id = mysql_result($result,$i,"object_id");
			$sql = "select item_obj_id FROM pets WHERE item_obj_id = '$object_id'";
			$result2 = mysql_query($sql,$con);
			$count2 = mysql_num_rows($result2);
			if (!$count2)
			{	$count++;	}
			$i++;
		}
		echo "<p class=\"dropmainwhite\">Callers without Pets  - $count</p>";

		$sql = "select castle_id FROM siege_clans WHERE clan_id NOT IN (SELECT clan_id FROM clan_data)";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		echo "<p class=\"dropmainwhite\">Clans Without Members  - $count</p>";

		echo "</td></tr></table></center>";

		if ($action == 'go')
		{	echo "<p class=\"dropmain\">&nbsp;</p><center><form method=\"post\" action=\"checkdb.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=go\"><input value=\"Re-Run Clean\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center><p class=\"dropmain\">&nbsp;</p>";	}

	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>