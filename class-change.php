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
$classid = input_check($_REQUEST['classid'],0);
$baseclass = input_check($_REQUEST['baseclass'],0);
$subclass = input_check($_REQUEST['subclass'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.


function skill_table($username, $token, $langval, $server_id, $skin_id, $charnum,  $db_location, $db_user, $db_psswd, $db_l2jdb, $skill_id, $parent_class, $character_class, $base_c_class, $classid, $subclass, $level, $class_array, $green_code, $red_code, $base_char_class_keep)
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
		
		$sql = "select class_name, id from class_list where parent_id = $skill_id";
		$result = mysql_query($sql,$con);
		$table_show = 0;
		while ($r_array = mysql_fetch_assoc($result))
		{
			if ($table_show == 0)
			{
				$table_show = 1;
				if ($parent_class == 0)
				{	echo "<tr><td>";	}
				echo "<table border=\"1\" class=\"dropmain\"><tr>\n";
			}
			$class_name = $r_array['class_name'];
			$class_id = $r_array['id'];
			if (in_array($class_id, $class_array))
			{	echo "<td><center><strong><p class=\"dropmain\">$class_name</p></strong>";	}
			else
			{	
				$sql = "select COUNT(*) from knightsubclassmap where subclass = $class_id and class = $base_char_class_keep";
				$result3 = mysql_query($sql,$con);
				$s_count = mysql_result($result3,0,"COUNT(*)");
				if (($s_count > 0) || ($subclass == 0))
				{	echo "<td><center><p class=\"dropmain\"><a href=\"class-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&classid=$classid&baseclass=$class_id&subclass=$subclass#anchor\" class=\"dropmain\"><font color=$green_code>$class_name</font></a></p>";	}
				else
				{	echo "<td><center><p class=\"dropmain\"><a href=\"class-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&classid=$classid&baseclass=$class_id&subclass=$subclass#anchor\" class=\"dropmain\"><font color=$red_code>$class_name</font></a></p>";	}
			} 

			skill_table($username, $token, $langval, $server_id, $skin_id, $charnum,  $db_location, $db_user, $db_psswd, $db_l2jdb, $class_id, 1, $character_class, $base_c_class, $classid, $subclass, $level, $class_array, $green_code, $red_code, $base_char_class_keep);
			echo "</center></td>";
		}
		if ($table_show == 1)
		{	
			echo "</tr></table>";
			if ($parent_class == 0)
			{	echo "</td></tr>";	}
		}
}

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
		
		// Make sure that the subclass is within valid range.
		if (($subclass > 3) || ($subclass < 0))
		{	$subclass = 0;	}

	
		if ($action == 1)
		{
			// If the current character class is the one being changed, then also change the current active class
			$sql = "select classid from characters where charId = $charnum";
			$result = mysql_query($sql,$con);
			$old_base_class = mysql_result($result,0,"classid");
			if ($subclass > 0)
			{
				$sql = "selcet class_id from character_subclasses where charId = $charnum and class_index = $subclass";
				$result = mysql_query($sql,$con);
				$previous_class = mysql_result($result,0,"classid");
			}
			else
			{
				$sql = "select base_class from characters where charId = $charnum";
				$result = mysql_query($sql,$con);
				$previous_class = mysql_result($result,0,"base_class");
			}
			if ($old_base_class == $previous_class)
			{
				$sql = "update characters set classid = $baseclass where charId = $charnum";
				$result = mysql_query($sql,$con);
			}
			if ($subclass > 0)
			{
				$sql = "update character_subclasses set class_id = $baseclass where charId = $charnum and class_index = $subclass";
				$result = mysql_query($sql,$con);
			}
			else
			{
				// Update the base class of the character and if necessary change the race.
				$sql = "update characters set base_class = $baseclass where charId = $charnum";
				$result = mysql_query($sql,$con);

				$sql = "select class_name from class_list where id = $baseclass";
				$result = mysql_query($sql,$con);
				$class_name = substr(mysql_result($result,0,"class_name"), 0 , 2);
				$race = 0;
				if ($class_name == "E_")
				{	$race = 1;	}
				elseif ($class_name == "DE")
				{	$race = 2;	}
				elseif ($class_name == "O_")
				{	$race = 3;	}
				elseif ($class_name == "D_")
				{	$race = 4;	}
				elseif ($class_name == "K_")
				{	$race = 5;	}
				$sql = "update characters set race = $race where charId = $charnum";
				$result = mysql_query($sql,$con);
			}
		}

		// Generate the list of subclasses, if they exist, ready to form part of the drop down list.
		$slip_out = "";
		$sql = "select class_id, class_index, level from character_subclasses where charId = $charnum order by class_index";
		$result = mysql_query($sql,$con);
		$count = 0;
		while ($r_array = mysql_fetch_assoc($result))
		{
			$subclass_id = $r_array['class_index'];
			$subclass_number = $r_array['class_id'];
			$subclass_level = $r_array['level'];
			$sql = "select class_name from class_list where id = $subclass_number";
			$result2 = mysql_query($sql,$con);
			$class_name = mysql_result($result2,0,"class_name");
			$slip_out = $slip_out . "<option value=\"class-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&skillid=$skill_id&level=$skill_level&subclass=$subclass_id\"";
			if ($subclass == $subclass_id)
			{	$slip_out = $slip_out . " selected";	}
			$slip_out = $slip_out . ">Subclass " . $subclass_id . " - " . $class_name . " - " . $subclass_level . "</option>";
			$count++;
		}
		
		// Find the basic information on the main class of the character.
		$sql = "select base_class, classid, char_name, level from characters where charId = $charnum";
		$result = mysql_query($sql,$con);
		$base_char_class = mysql_result($result,0,"base_class");
		$base_char_class_keep = mysql_result($result,0,"base_class");
		$character_class = mysql_result($result,0,"classid");
		$charname = mysql_result($result,0,"char_name");
		$level = mysql_result($result,0,"level");
		$class_array = array($base_char_class);
		
		echo "<p class=\"dropmain\">&nbsp;</p><center><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum\" class=\"droph2\">Character - $charname</a></center>\n";
		
		// If the main class is less than level 75, then it is not legitimate for subclassing - show a warning.
		if (($level < 75) && ($count > 0))
		{	echo "<p class=\"dropmain\">&nbsp;</p><center><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum\" class=\"droph2\">Problem - Subclasses present while main character below level 75.</a></center><p class=\"dropmain\">&nbsp;</p>";	}
		
		// If there are subclasses on the character, then display the drop down list and also check the subclasses to ensure that they are valid
		// choices for the main class.
		if ($count > 0)
		{
			$sql = "select class_name from class_list where id = $base_char_class";
			$result2 = mysql_query($sql,$con);
			$class_name = mysql_result($result2,0,"class_name");
			echo "<center><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"class-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&skillid=$skill_id&level=$skill_level&subclass=0\">Main Class - " . $class_name . " - " . $level . "</option>";
			echo $slip_out;
			echo "</select></p></center>";

			$sql = "select class_id from character_subclasses where charId = $charnum";
			$result2 = mysql_query($sql,$con);
			while ($r_array = mysql_fetch_assoc($result2))
			{
				$sub_class_id = $r_array['class_id'];
				array_push($class_array, $sub_class_id);
				$sql = "select COUNT(*) from knightsubclassmap where class = $base_char_class and subclass = $sub_class_id";
				$result3 = mysql_query($sql,$con);
				$r_count = mysql_result($result3,0,"COUNT(*)");
				if ($r_count != 1)
				{	
					$sql = "select class_name from class_list where id = $sub_class_id";
					$result3 = mysql_query($sql,$con);
					$sub_name = mysql_result($result3,0,"class_name");
					echo "<center><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum\" class=\"droph2\">Problem - Subclass $sub_name not suitable for base class.</a></center>";	
				}
			}
		}
		
		// If we are dealing with a subclass, then replace the base class details with those of the sub class.
		if ($subclass > 0)
		{
			$sql = "select level, class_id from character_subclasses where charId = $charnum and class_index = $subclass";
			$result = mysql_query($sql,$con);
			$base_char_class = mysql_result($result,0,"class_id");
			$level = mysql_result($result,0,"level");
		}
		
		// Go through all of the base level classes in turn.
		$sql = "select class_name, id from class_list where parent_id = -1";
		$result = mysql_query($sql,$con);
		while ($r_array = mysql_fetch_assoc($result))
		{
			$class_name = $r_array['class_name'];
			$base_class = $r_array['id'];
			if ($base_char_class == $base_class)	// Insert an anchor to help focus on the class in question.
			{	echo "<a name=\"anchor\" id=\"anchor\"></a>";	}
			if (in_array($base_class, $class_array))	// If the class is one of the characters classes, then just display the name with no link.
			{	echo "<center><table border=\"1\" class=\"dropmain\"><tr><td><center><strong><p class=\"dropmain\">$class_name</p></strong></center></td></tr>\n";	}
			else
			{	
				$sql = "select COUNT(*) from knightsubclassmap where subclass = $base_class and class = $base_char_class";
				$result3 = mysql_query($sql,$con);
				$s_count = mysql_result($result3,0,"COUNT(*)");
				if (($s_count > 0) || ($subclass == 0))		// ... otherwise if we are operating on the base class, use green text for valid option; red for a subclass as the root class will be below level 40.
				{	echo "<center><table border=\"1\" class=\"dropmain\"><tr><td><center><p class=\"dropmain\"><a href=\"class-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&classid=$character_class&baseclass=$base_class&subclass=$subclass#anchor\" class=\"dropmain\"><font color=$green_code>$class_name</font></a></p></center></td></tr>\n";	}
				else
				{	echo "<center><table border=\"1\" class=\"dropmain\"><tr><td><center><p class=\"dropmain\"><a href=\"class-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=1&classid=$character_class&baseclass=$base_class&subclass=$subclass#anchor\" class=\"dropmain\"><font color=$red_code>$class_name</font></a></p></center></td></tr>\n";	}
			}
			// Start the recursive routine to examine all the subclasses of the base class.
			skill_table($username, $token, $langval, $server_id, $skin_id, $charnum, $db_location, $db_user, $db_psswd, $db_l2jdb, $base_class, 0, $character_class, $base_class, $character_class, $subclass, $level, $class_array, $green_code, $red_code, $base_char_class_keep);
			echo "</table></center>";
		}
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>