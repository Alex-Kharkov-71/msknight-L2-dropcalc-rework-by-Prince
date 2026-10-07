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
$commandname = input_check($_REQUEST['commandname'],0);
$new_command = input_check($_REQUEST['new_command'],0);
$new_group = $_REQUEST['new_group'];
$number = preg_replace('/[&%$\\\|<>#£]/','',$_REQUEST['number']);

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


		echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">Character Permission Settings</h2>";

		if (strlen($commandname) > 0)
		{
			$sql = "update admin_command_access_rights set accessLevels = $number where adminCommand ='$commandname'";
			$result2 = mysql_query($sql,$con);
		}
		if (strlen($new_command) > 6)
		{
		   	 $sql  = "INSERT INTO admin_command_access_rights VALUES ('$new_command', '$new_group')";
			 $result2 = mysql_query($sql,$con);
                }
		
		include('config-read.php');
		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Dropcalc Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings2.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Technical Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings3.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Cross Gameserver Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings4.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Character Permission Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "</tr></table>";
		echo "<p>&nbsp;</p>";

		$option_count = 0;
		$sql = "select accessLevel, name from access_levels order by accessLevel";
		$result2 = mysql_query($sql,$con);
		while ($r_array = mysql_fetch_assoc($result2))
		{	
			$access_lvl = $r_array['accessLevel'];
			$access_title = $r_array['name'];
			if ($option_count == 0)
			{	$options = array(array($access_lvl, $access_title));	}
			else
			{	array_push($options, array($access_lvl, $access_title));	}
			$option_count++;
		}
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
		$sql = "select * from admin_command_access_rights order by accessLevels, adminCommand";
		$result = mysql_query($sql,$con);
		$first_level = 0;
		
		echo "<tr><td><form method=\"post\" action=\"settings4.php\">
		<input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"commandname\" type=\"hidden\" value=\"$command\">
		<input name=\"new_command\" type=\"text\" length=\"32\" value=\"admin_\">
		<select name=\"new_group\">";		
		$e = 0;
			while ($e < $option_count) 
			{
				$opt = $options[$e];
				$o_num = $opt[0];
				$o_name = $opt[1];
				if ($o_num == $level)
				{	echo "<option value=\"$o_num\" selected>$o_num - $o_name</option>";	}
				else
				{	echo "<option value=\"$o_num\">$o_num - $o_name</option>";	}
				$e++;
			}
		//<input name=\"new_group\" type=\"text\" length=\"3\" value=\"1\" maxlength=\"3\">
		
		echo "<input type=\"submit\" value=\"Add\" class=\"bigbut2\">
		</form></td></tr>";
		while ($r_array = mysql_fetch_assoc($result))
		{	
			$command = $r_array['adminCommand'];
			$level = $r_array['accessLevels'];
			if (($first_level <> $level) && ($first_level > 0))
			{	
			echo "</table></center>&nbsp;<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
			}
			echo "<tr><td class=\"lefthead\"><p>$command</p></td><td>
			<form method=\"post\" action=\"settings4.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"commandname\" type=\"hidden\" value=\"$command\">
			<select name=\"number\" OnChange=\"submit()\">";
			$i = 0;
			while ($i < $option_count) 
			{
				$opt = $options[$i];
				$o_num = $opt[0];
				$o_name = $opt[1];
				if ($o_num == $level)
				{	echo "<option value=\"$o_num\" selected>$o_num - $o_name</option>";	}
				else
				{	echo "<option value=\"$o_num\">$o_num - $o_name</option>";	}
				$i++;
			} 
			echo "</select></form></td></tr>";
			$first_level = $level;
		}
		echo "</table></center>";
		echo "<p>&nbsp;</p>";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>