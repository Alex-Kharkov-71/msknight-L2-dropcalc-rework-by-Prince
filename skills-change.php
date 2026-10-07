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
$charnum = input_check($_REQUEST['charnum'],0);
$action = input_check($_REQUEST['action'],0);
$skillid = input_check($_REQUEST['skillid'],0);
$level = input_check($_REQUEST['level'],0);
$subclass = input_check($_REQUEST['subclass'],0);

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
		
		if (($subclass > 3) || ($subclass < 1))
		{	$subclass = 0;	}

		$slip_out = "";
		$sql = "select class_id, class_index, level from character_subclasses where charId = $charnum order by class_index";
		$result = mysql_query($sql,$con);
		$count = 0;
		while ($r_array = mysql_fetch_assoc($result))
		{
			$subclass_id = $r_array['class_index'];
			$subclass_name = $r_array['class_id'];
			$subclass_level = $r_array['level'];
			$sql = "select class_name from class_list where id = $subclass_name";
			$result2 = mysql_query($sql,$con);
			$class_name = mysql_result($result2,0,"class_name");
			$slip_out = $slip_out . "<option value=\"skills-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&skillid=$skill_id&level=$skill_level&subclass=$subclass_id\"";
			if ($subclass == $subclass_id)
			{	$slip_out = $slip_out . " selected";	}
			$slip_out = $slip_out . ">Subclass " . $subclass_id . " - " . $class_name . " - " . $subclass_level . "</option>";
			$count++;
		}
		
		
		$sql = "select base_class, classid, char_name, level from characters where charId = $charnum";
		$result = mysql_query($sql,$con);
		$base_char_class = mysql_result($result,0,"base_class");
		$base_char_class_keep = mysql_result($result,0,"base_class");
		$character_class = mysql_result($result,0,"classid");
		$charname = mysql_result($result,0,"char_name");
		$character_level = mysql_result($result,0,"level");
		$character_level_keep = mysql_result($result,0,"level");
		$list_of_classes = $character_class;
		if ($subclass > 0)
		{
			$sql = "select class_id, level from character_subclasses where charId = $charnum and class_index = $subclass";
			$result = mysql_query($sql,$con);
			$base_char_class = mysql_result($result,0,"class_id");
			$character_level = mysql_result($result,0,"level");
		}
		
		$sql = "select parent_id from class_list where id = $base_char_class";
		$result = mysql_query($sql,$con);
		$parent_class = mysql_result($result,0,"parent_id");
		while ($parent_class > -1)
		{
			$list_of_classes = $list_of_classes . ', ' . $parent_class;
			$sql = "select parent_id from class_list where id = $parent_class";
			$result = mysql_query($sql,$con);
			$parent_class = mysql_result($result,0,"parent_id");
		}
		$sql = "select * from skill_trees where class_id in ($list_of_classes) order by skill_id, level";
		$result = mysql_query($sql,$con);
		echo "<p class=\"dropmain\">&nbsp;</p><center><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum\" class=\"droph2\">Character - $charname - Level $character_level</a></center>\n";
		
		if ($count > 0)
		{
			$sql = "select class_name from class_list where id = $base_char_class_keep";
			$result2 = mysql_query($sql,$con);
			$class_name = mysql_result($result2,0,"class_name");
			echo "<center><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"skills-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&skillid=$skill_id&level=$skill_level&subclass=0\">Main Class - " . $class_name . " - " . $character_level_keep . "</option>";
			echo $slip_out;
			echo "</select></p></center>";
		}
		
		$prev_skill = -1;
		echo "<center><table class=\"blanktab\"><tr><td><form method=\"post\" action=\"skills-change.php\"><input value=\" Clean Out All Class Skills \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$charnum\"><input name=\"action\" type=\"hidden\" value=\"2\"><input name=\"subclass\" type=\"hidden\" value=\"$subclass\"></form>";
		echo "</td><td width=\"100%\"><center><form method=\"post\" action=\"skills-change.php\"><input value=\" Grant Class Skills To Level \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$charnum\"><input name=\"action\" type=\"hidden\" value=\"3\"><input name=\"subclass\" type=\"hidden\" value=\"$subclass\"></form>";
		echo "</center></td><td><form method=\"post\" action=\"skills-change.php\"><input value=\" Grant All Class Skills \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$charnum\"><input name=\"action\" type=\"hidden\" value=\"4\"><input name=\"subclass\" type=\"hidden\" value=\"$subclass\"></form></td></tr></table></center>";
		echo "<center><table class=\"dropmain\" border=\"1\">";
		
		if ($action == 1)
		{
			if ($level > 0)
			{
				$sql = "select COUNT(*) from character_skills where skill_id = $skillid and charId = $charnum and class_index = $subclass";
				$result2 = mysql_query($sql,$con);
				$count = mysql_result($result2,0,"COUNT(*)");
				if ($count > 0)
				{	$sql = "update character_skills set skill_level = $level where skill_id = $skillid and charId = $charnum and class_index = $subclass";	}
				else
				{	
					$sql = "select name from skill_trees where skill_id = $skillid";
					$result2 = mysql_query($sql,$con);
					$skill_name = mysql_result($result2,0,"name");
					$sql = "insert into character_skills (charId, skill_id, skill_level, class_index) VALUE ($charnum, $skillid, $level, $subclass)";	
				}
			}
			$result2 = mysql_query($sql,$con);
		}
		if ($action == 2)
		{
			$sql = "select distinct skill_id from skill_trees";
			$result2 = mysql_query($sql,$con);
			$target = 0;
			while ($r_array = mysql_fetch_assoc($result2))
			{
				$sid = $r_array['skill_id'];
				if ($target == 0)
				{	
					$skills_list = $sid;
					$target = 1;
				}
				else
				{	$skills_list = $skills_list . ", " . $sid;	}
			}
			$sql = "delete from character_skills where skill_id in ($skills_list) and charId = $charnum and class_index = $subclass";
			$result2 = mysql_query($sql,$con);
		}
		while ($r_array = mysql_fetch_assoc($result))
		{
			$skill_name = $r_array['name'];
			$skill_level = $r_array['level'];
			$skill_id = $r_array['skill_id'];
			$skill_min_level = $r_array['min_level'];
			if (($action > 3) || (($action > 2) && ($character_level >= $skill_min_level)))
				{
					$sql = "delete from character_skills where skill_id = $skillid and charId = $charnum and class_index = $subclass";
					$result2 = mysql_query($sql,$con);
					$sql = "select COUNT(*) from character_skills where skill_id = $skill_id and charId = $charnum and class_index = $subclass";
					$result2 = mysql_query($sql,$con);
					$c_count = mysql_result($result2,0,"COUNT(*)");
					if ($c_count < 1)
					{	$sql = "insert into character_skills (charId, skill_id, skill_level, class_index) VALUE ($charnum, $skill_id, $skill_level, $subclass)";	}
					else
					{	$sql = "update character_skills set skill_level = $skill_level where skill_id = $skill_id and charId = $charnum and class_index = $subclass";	}
					$result2 = mysql_query($sql,$con);
					$char_skill_level = $skill_level;
				}
			if ($skill_id != $prev_skill)
			{	
				
				echo "<tr><td><p class=\"dropmain\">$skill_name</p></td><td><p><a href=\"skills-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&skillid=$skill_id&level=0\" class=\"dropmain\">0</a>";	
				$char_skill_level = 0;
				$sql = "select skill_level from character_skills where skill_id = $skill_id and charId = $charnum and class_index = $subclass";

				$result2 = mysql_query($sql,$con);
				while ($r_array2 = mysql_fetch_assoc($result2))
				{	$char_skill_level = $r_array2['skill_level'];	}
			}
			if ($skill_level <= $char_skill_level)
			{	$skill_show = "<font color=\"$blue_code\">$skill_level</font>";	}
			else
			{	$skill_show = "<font color=\"$red_code\">$skill_level</font>";	}
			echo " - <a href=\"skills-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&skillid=$skill_id&level=$skill_level&subclass=$subclass\" class=\"dropmain\">$skill_show</a>";
			$prev_skill = $skill_id;
			
		}
		if (($skill_id != $prev_skill) && ($prev_skill >= 0))
		{	echo "</p></td></tr>";	}
		echo "</table></center>";
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
