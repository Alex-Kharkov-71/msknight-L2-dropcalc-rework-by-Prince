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


//
// ***** EXECUTION STARTS HERE *****
//

include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$to_clone = input_check($_REQUEST['toclone'],0);
$action = input_check($_REQUEST['action'],0);
$i_mobname = input_check($_REQUEST['name'],0);
$i_mobtitle = input_check($_REQUEST['title'],0);
$i_mobtype = input_check($_REQUEST['type'],0);
$i_agro = input_check($_REQUEST['agro'],2);
$i_showname = input_check($_REQUEST['showname'],2);
$i_runspd = input_check($_REQUEST['runspd'],2);
$i_walkspd = input_check($_REQUEST['walkspd'],2);
$i_attackrange = input_check($_REQUEST['atkrng'],2);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_admin)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
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

		echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">NPC Clone Tool</h2><p class=\"dropmain\">&nbsp;</p>";

		if ($action)
		{
			$sql = "select * from npc where id = '$to_clone' union select * from custom_npc where id = '$to_clone'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				echo "<h2 class=\"dropmain\">Problem retrieving $to_clone to clone</h2><p class=\"dropmain\">&nbsp;</p>";
			}
			else
			{
				$i_template = mysql_result($result,0,"idtemplate");
				$i_class = mysql_result($result,0,"class");
				$i_collision_radius = mysql_result($result,0,"collision_radius");
				$i_collision_height = mysql_result($result,0,"collision_height");
				$i_level = mysql_result($result,0,"level");
				$i_sex = mysql_result($result,0,"sex");
				$i_hp = mysql_result($result,0,"hp");
				$i_mp = mysql_result($result,0,"mp");
				$i_str = mysql_result($result,0,"str");
				$i_con = mysql_result($result,0,"con");
				$i_dex = mysql_result($result,0,"dex");
				$i_int = mysql_result($result,0,"int");
				$i_wit = mysql_result($result,0,"wit");
				$i_men = mysql_result($result,0,"men");
				$i_exp = mysql_result($result,0,"exp");
				$i_sp = mysql_result($result,0,"sp");
				$i_patk = mysql_result($result,0,"patk");
				$i_pdef = mysql_result($result,0,"pdef");
				$i_matk = mysql_result($result,0,"matk");
				$i_mdef = mysql_result($result,0,"mdef");
				$i_atkspd = mysql_result($result,0,"atkspd");
				$i_matkspd = mysql_result($result,0,"matkspd");
				$i_rhand = mysql_result($result,0,"rhand");
				$i_lhand = mysql_result($result,0,"lhand");
				$i_hpreg = mysql_result($result,0,"hpreg");
				$i_mpreg = mysql_result($result,0,"mpreg");
				$i_critical = mysql_result($result,0,"critical");
				$i_enchant = mysql_result($result,0,"enchant");
				$i_targetable = mysql_result($result,0,"targetable");
				$i_show_name = mysql_result($result,0,"show_name");
				$i_dropHerbGroup = mysql_result($result,0,"dropHerbGroup");
				$i_basestats = mysql_result($result,0,"basestats");
				$ii_aggro = 0;
				if ($i_agro)
				{	$ii_agro = 1;	}
				$sql = "select count(*) from custom_npc where id > 599999 and id < 700000";
				$result = mysql_query($sql,$con);
				$last_num = mysql_result($result,0,"count(*)");
				if ($last_num > 0)
				{
					$sql = "select id from custom_npc where id > 599999 and id < 700000 order by id desc limit 1";
					$result = mysql_query($sql,$con);
					$last_num = mysql_result($result,0,"id") + 1;
				}
				else
				{	$last_num = 600000;	}

				$sql = "insert into custom_npc (id, idtemplate, name, serversidename, title, serversidetitle, class, collision_radius, collision_height, level, sex, `type`, attackrange, hp, mp, str, con, dex, `int`, wit, men, `exp`, sp, patk, pdef, matk, mdef, hpreg, mpreg, critical, enchant, targetable, show_name, dropHerbGroup, basestats, atkspd, aggro, matkspd, rhand, lhand, walkspd, runspd) ";
				$sql = $sql . "VALUES ('$last_num', '$i_template', '$i_mobname', '1', '$i_mobtitle', '1', '$i_class', '$i_collision_radius', '$i_collision_height', '$i_level', '$i_sex', '$i_mobtype', ";
				$sql = $sql . "'$i_attackrange', '$i_hp', '$i_mp', '$i_str', '$i_con', '$i_dex', '$i_int', '$i_wit', '$i_men', '$i_exp', '$i_sp', '$i_patk', '$i_pdef', '$i_matk', '$i_mdef', ";
				$sql = $sql . "'$i_hpreg', '$i_mpreg', '$i_critical', '$i_enchant', '$i_targetable', '$i_showname', '$i_dropHerbGroup', '$i_basestats', ";
				$sql = $sql . "'$i_atkspd', '$ii_agro', '$i_matkspd', '$i_rhand', '$i_lhand', '$i_walkspd', '$i_runspd')";
				$result = mysql_query($sql,$con);
				echo "<h2 class=\"dropmain\">$to_clone cloned as ID $last_num</h2><p class=\"dropmain\">&nbsp;</p>";
			}
		}

		if (($to_clone) && (!$action))
		{
			$sql = "select * from npc where id = '$to_clone' union select * from custom_npc where id = '$to_clone'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if (!$count)
			{
				echo "<h2 class=\"dropmain\">NPC $to_clone not found</h2><p class=\"dropmain\">&nbsp;</p>";
			}
			else
			{
				$i_name = mysql_result($result,0,"name");
				$i_title = mysql_result($result,0,"title");
				$i_class = mysql_result($result,0,"class");
				$i_type = mysql_result($result,0,"type");
				$i_aggro = mysql_result($result,0,"aggro");
				$i_attackrange = mysql_result($result,0,"attackrange");
				$i_walkspd = mysql_result($result,0,"walkspd");
				$i_runspd = mysql_result($result,0,"runspd");
				$sql = "select distinct `type` from npc order by `type`";
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"3\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</p></td></tr>";
				echo "<form method=\"post\" action=\"npccreate.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"action\" type=\"hidden\" value=\"change\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"toclone\" type=\"hidden\" value=\"$to_clone\" maxlength=\"6\" size=\"6\">";
				echo "<tr><td class=\"blanktab\" colspan=\"3\"><input name=\"name\" type=\"text\" value=\"$i_name\" maxlength=\"200\" size=\"60\"></td></tr>";
				echo "<td class=\"lefthead\" colspan=\"3\"><p class=\"dropmain\"><strong class=\"dropmain\">Title</p></td></tr>";
				echo "<tr><td class=\"blanktab\" colspan=\"3\"><input name=\"title\" type=\"text\" value=\"$i_title\" maxlength=\"45\" size=\"45\"></td></tr>";

				echo "<tr><td class=\"lefthead\" colspan=\"3\"><p class=\"dropmain\"><strong class=\"dropmain\">Class</p></td></tr><tr><td class=\"blanktab\" colspan=\"2\"><p class=\"dropmain\">$i_class</p></td></tr>";
				echo "<tr><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_type</p></td><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Show Name</p></td><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Agro</p></td></tr>";
				echo "<tr><td class=\"blanktab\"><select name=\"type\">";
				$i=0;
				while ($i < $count)
				{
					$m_type = mysql_result($result,$i,"type");
					if ($m_type == $i_type)
					{	echo "<option value=\"$m_type\" selected>$m_type</option>";	}
					else
					{	echo "<option value=\"$m_type\">$m_type</option>";	}
					$i++;
				}
				echo "</select></td><td class=\"blanktab\"><select name=\"showname\">";
				if ($i_showname == 0)
				{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
				else
				{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
				echo "</select></td>";
				echo "<td class=\"blanktab\"><select name=\"agro\">";
				if ($i_aggro)
				{	echo "<option value=\"yes\" selected>Yes</option><option value=\"\">No</option>";	}
				else
				{	echo "<option value=\"yes\">Yes</option><option value=\"no\" selected>No</option>";	}
				echo "</select></td></tr>";
				echo "<tr><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Attack Range</p></td><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Walk Speed</p></td><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Run Speed</p></td></tr>";
				echo "<tr><td class=\"blanktab\"><input name=\"atkrng\" type=\"text\" value=\"$i_attackrange\" maxlength=\"11\" size=\"11\"></td><td class=\"blanktab\"><input name=\"walkspd\" type=\"text\" value=\"$i_walkspd\" maxlength=\"3\" size=\"3\"></td><td class=\"blanktab\"><input name=\"runspd\" type=\"text\" value=\"$i_runspd\" maxlength=\"3\" size=\"3\"></td></tr>";
				echo "</table><input value=\" <- Clone NPC -> \" type=\"submit\" class=\"bigbut2\"></form></center><p class=\"dropmain\">&nbsp;</p><p class=\"dropmain\">&nbsp;</p>";
			}
		}

		if ($action == "maptime")
		{
			if (($newtime > 0) && ($newtime < 999999999999999999))
			{
				$newtime = time() + ($newtime * 86400);
			}
			$sql = "update $dblog_l2jdb.knightdrop set mapaccess = '$newtime' where name = '$accountname'";
			$result = mysql_query($sql,$con2);
			if (!$result)
			{
				die('Could not replace map access time in knightdrop table: ' . mysql_error());
			}
		}

		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">NPC to clone - </p>";
		echo "<form method=\"post\" action=\"npccreate.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"toclone\" type=\"text\" value=\"$to_clone\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Clone\" type=\"submit\" class=\"bigbut2\"></td></form></tr></table></center>";



	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
