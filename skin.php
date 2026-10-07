<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include($skin_dir . $svr_dir_delimit . "skincols.php");

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


// --------------------------------------------------------
// -----              Common Code Block               -----
// --------------------------------------------------------

// - Wrap Start provides the main template for a page where the user has logged on and authenticated.

function wrap_it($wrapfile, $username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level)
{
	include("config.php");

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
		die('Wrap_start could not connect to gameserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Wrap_start could not change to gameserver database: ' . mysql_error());
	}
	$con3 = mysql_connect($core_db_location,$core_db_user,$core_db_psswd);
	mysql_query("SET NAMES 'utf8'", $con3);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Wrap_start could not connect to gameserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Wrap_start could not change to gameserver database: ' . mysql_error());
	}
	
	$sql = "select * from $core_db_l2jdb.knightsettings";
	$result_set = mysql_query($sql,$con3);
	if (!$result_set)
	{	
		echo "<p class=\"popup\">Couldn't read the knightsettings table!!!</p>";
	}
	$hide_language = mysql_result($result_set,0,"hide_language");
	$hide_server = mysql_result($result_set,0,"hide_server");
	$hide_skin = mysql_result($result_set,0,"hide_skin");
	$user_change_server = mysql_result($result_set,0,"user_change_server");
	$user_change_skin = mysql_result($result_set,0,"user_change_skin");
	$force_default_skin = mysql_result($result_set,0,"force_default_skin");
	$show_online = mysql_result($result_set,0,"show_online");
	$php_type = mysql_result($result_set,0,"php_type");
	$allowpassreset = mysql_result($result_set,0,"allowpassreset");
	$menushowchars = mysql_result($result_set,0,"menushowchars");
	
	$sql = "select accessLevel from $dblog_l2jdb.accounts where login = '$username'";
	$result_set = mysql_query($sql,$con2);
	if (!$result_set)
	{	
		echo "<p class=\"popup\">Couldn't read the knightsettings table!!!</p>";
	}
	if (mysql_num_rows($result_set)> 0 )
	{	$access_lvl = mysql_result($result_set,0,"accessLevel");	}
	else
	{	$access_lvl = 0;	}
	if ($access_lvl < $sec_inc_gmlevel)
	{
		if ($force_default_skin)
		{
			$user_change_skin = 0;
			$skin_id = $gameservers[$server_id][5];
			$skin_dir = "$skin_id";
			$sec_inc_gmlevel = 10;
		}
	}

	// Query for user name
	$sql = "select online from characters where online = '1'";
	if (!$result = mysql_query($sql,$con))
	{
		die('Could not retrieve from database: ' . mysql_error());
	}
	// If return array empty, then username not found.
	$row = mysql_fetch_array($result);
	$count_accs = mysql_num_rows($result);
	
	$in = $_SERVER['REQUEST_URI'];
	$in_pos = strrpos($in, "/") + 1;
	$in = substr($in, $in_pos, (strlen($in) - $in_pos));

	$l_array_count = count($language_array);
	if (($langval < 0) or ($langval > $l_array_count) or (strlen($langval) < 1))
	{	$langval = $default_lang;	}
	$language_found = 0;
	$language_string = "<form name=\"lang\" action=\"$in\">";
	$server_string = "<form name=\"server\" action=\"$in\">";
	$skin_string = "<form name=\"skin\" action=\"$in\">";
	$add_string = "";
	reset($HTTP_GET_VARS);
	while (list ($key, $val) = each ($HTTP_GET_VARS)) {
		if ($key == "password")
		{	
			$key = "token";	
			$val = $token;
		}
		if (($key <> "langval") && ($val <> "logoff") && ($key <> "server_id") && ($key <> "skin_id") && ($key <> "username") && ($key <> "token") && ($key <> "action") && ($key <> "password"))
		{	$add_string = $add_string .  "<input type=\"hidden\" name=\"" .$key . "\" value=\"" . $val . "\">";	}
	}
	reset($HTTP_POST_VARS);
	while (list ($key, $val) = each ($HTTP_POST_VARS)) {
		if ($key == "password")
		{	
			$key = "token";	
			$val = $token;
		}
		if (($key <> "langval") && ($val <> "logoff") && ($key <> "server_id") && ($key <> "skin_id") && ($key <> "username") && ($key <> "token") && ($key <> "action") && ($key <> "password"))
		{	$add_string = $add_string .  "<input type=\"hidden\" name=\"" .$key . "\" value=\"" . $val . "\">";	}
	}
	$language_string = $language_string . $add_string . "<input type=\"hidden\" name=\"username\" value=\"$username\"><input type=\"hidden\" name=\"token\" value=\"$token\"><input type=\"hidden\" name=\"server_id\" value=\"$server_id\"><input type=\"hidden\" name=\"skin_id\" value=\"$skin_id\"><select name=\"langval\" OnChange=\"submit()\" class=\"field2\">";
	$server_string = $server_string . $add_string . "<input type=\"hidden\" name=\"username\" value=\"$username\"><input type=\"hidden\" name=\"token\" value=\"$token\"><input type=\"hidden\" name=\"langval\" value=\"$langval\"><input type=\"hidden\" name=\"skin_id\" value=\"$skin_id\"><select name=\"server_id\" OnChange=\"submit()\" class=\"field2\">";
	$skin_string = $skin_string . $add_string . "<input type=\"hidden\" name=\"username\" value=\"$username\"><input type=\"hidden\" name=\"token\" value=\"$token\"><input type=\"hidden\" name=\"langval\" value=\"$langval\"><input type=\"hidden\" name=\"server_id\" value=\"$server_id\"><select name=\"skin_id\" OnChange=\"submit()\" class=\"field2\">";
	
	if ($l_array_count > 1)
	{
		$i = 0;
		while ($i < $l_array_count)
		{
			$language_entry = $language_array[$i];
			$language_title = $language_entry[0];
			$language_file = $language_entry[1];
		
			if ($langval == $i)
			{	$language_string = $language_string . "<option value=$i selected>$language_title</option>";	}
			else
			{	$language_string = $language_string . "<option value=$i>$language_title</option>";	}
			$i++;
		}
		$language_string = $language_string . "</select></form>";
	}
	else
	{	$language_title = $language_array[0][0];
		$language_string = "<form name=\"skin\"><input value=\"$language_title\" type=\"submit\" class=\"bigbut3\"></form>";	
	}

	$g_array_count = count($gameservers);
	if ($g_array_count > 1)
	{
		$i = 0;
		while ($i < $g_array_count)
		{
			$server_title = $gameservers[$i][0];
			
			if ($i == $server_id)
			{	
				$server_string = $server_string .  "<option value=$i selected>$server_title</option>";
			}
			else
			{	$server_string = $server_string .  "<option value=$i>$server_title</option>";	}
			$i++;
		}
		$server_string = $server_string . "</select></form>";
	}
	else
	{	$server_title = $gameservers[0][0];
		$server_string = "<form name=\"skin\"><input value=\"$server_title\" type=\"submit\" class=\"bigbut3\"></form>";	
	}
	$s_array_count = count($skinslist);
	if ($s_array_count > 1)
	{
		$i = 0;
		while ($i < $s_array_count)
		{
			$skin_title = $skinslist[$i];
		
			if ($i == $skin_id)
			{	
				$skin_string = $skin_string .  "<option value=$i selected>$skin_title</option>";
			}
			else
			{	$skin_string = $skin_string .  "<option value=$i>$skin_title</option>";	}
			$i++;
		}
		$skin_string = $skin_string . "</select></form>";
	}
	else
	{	$skin_title = $skinslist[0];
		$skin_string = "<form name=\"skin\"><input value=\"$skin_title\" type=\"submit\" class=\"bigbut3\"></form>";	
	}

	$chars_list = "";
	if ($menushowchars)
	{
		$result_set = mysql_query("select charId, char_name from characters where account_name = '$username'",$con3);
		while ($r_array = mysql_fetch_assoc($result_set))
		{
			if (strlen($chars_list) > 1)
			{	$chars_list = $chars_list . " - ";	}
			else 
			{	$chars_list = "";	}
			$char_name = $r_array['char_name'];
			$char_id = $r_array['charId'];
			$chars_list = $chars_list . "<a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$char_id\">$char_name</a>";
		}
	}
	$u_name = "<a href=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$username\">" . $username . "</a>";
	$lang_file = $language_array[$langval][1];
	include($lang_file);		// Import language variables.
	include($skin_dir . $svr_dir_delimit . $wrapfile);

}



// - WRAP START DUMMY - Responsible for the page that a user sees where they haven't logged in or authenticated to the system.

function wrap_it_dummy($wrapfile, $username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $default_lang, $language_array, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow)
{
	include("config.php");
// Countries and language files

	$con = mysql_connect($db_location,$db_user,$db_psswd);
	mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	$sql = "USE $db_l2jdb";

	if (!mysql_query($sql,$con))
	{
		die('Could not change to $db_l2jdb database: ' . mysql_error());
	}
	// Query for user name
	$sql = "select online from characters where online = '1'";
	if (!$result = mysql_query($sql,$con))
	{
		die('Could not retrieve from database: ' . mysql_error());
	}
	
	$con3 = mysql_connect($core_db_location, $core_db_user, $core_db_psswd);
	mysql_query("SET NAMES 'utf8'", $con3);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Wrap_start could not connect to gameserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Wrap_start could not change to gameserver database: ' . mysql_error());
	}
	
	$sql = "select * from $core_db_l2jdb.knightsettings";
	$result_set = mysql_query($sql,$con3);
	if (!$result_set)
	{	
		echo "<p class=\"popup\">Couldn't read the knightsettings table!!!</p>";
	}
	$hide_language = mysql_result($result_set,0,"hide_language");
	$hide_server = mysql_result($result_set,0,"hide_server");
	$hide_skin = mysql_result($result_set,0,"hide_skin");
	$user_change_server = mysql_result($result_set,0,"user_change_server");
	$user_change_skin = mysql_result($result_set,0,"user_change_skin");
	$force_default_skin = mysql_result($result_set,0,"force_default_skin");
	$show_online = mysql_result($result_set,0,"show_online");
	$php_type = mysql_result($result_set,0,"php_type");
	$allowpassreset = mysql_result($result_set,0,"allowpassreset");
	
	if ($force_default_skin)
	{
		$user_change_skin = 0;
		$skin_id = $gameservers[$server_id][5];
		$skin_dir = "$skin_id";
		$sec_inc_gmlevel = 10;
	}
	
	// If return array empty, then username not found.
	$row = mysql_fetch_array($result);
	$count_accs = mysql_num_rows($result);
	
	$in = $_SERVER['REQUEST_URI'];
	$in_pos = strrpos($in, "/") + 1;
	$in = substr($in, $in_pos, (strlen($in) - $in_pos));

	$l_array_count = count($language_array);
	if (($langval < 0) or ($langval > $l_array_count) or (strlen($langval) < 1))
	{	$langval = $default_lang;	}
	$language_found = 0;
	$language_string = "<form name=\"lang\" action=\"$in\">";
	$server_string = "<form name=\"server\" action=\"$in\">";
	$skin_string = "<form name=\"skin\" action=\"$in\">";
	$add_string = "";
	reset($HTTP_GET_VARS);
	while (list ($key, $val) = each ($HTTP_GET_VARS)) {
	if ($key == "password")
	{	$key = "token";	
		$val = $token;
	}
	if (($key <> "langval") && ($val <> "logoff") && ($key <> "server_id") && ($key <> "skin_id") && ($key <> "password"))
	{	$add_string = $add_string .  "<input type=\"hidden\" name=\"" .$key . "\" value=\"" . $val . "\">";	}
	}
	reset($HTTP_POST_VARS);
	while (list ($key, $val) = each ($HTTP_POST_VARS)) {
	if ($key == "password")
	{	$key = "token";	
		$val = $token;
	}
	if (($key <> "langval") && ($val <> "logoff") && ($key <> "server_id") && ($key <> "skin_id") && ($key <> "password"))
	{	$add_string = $add_string .  "<input type=\"hidden\" name=\"" .$key . "\" value=\"" . $val . "\">";	}
	}
	$language_string = $language_string . $add_string . "<input type=\"hidden\" name=\"server_id\" value=\"$server_id\"><input type=\"hidden\" name=\"skin_id\" value=\"$skin_id\"><select name=\"langval\" OnChange=\"submit()\" class=\"field2\">";
	$server_string = $server_string . $add_string . "<input type=\"hidden\" name=\"langval\" value=\"$langval\"><input type=\"hidden\" name=\"skin_id\" value=\"$skin_id\"><select name=\"server_id\" OnChange=\"submit()\" class=\"field2\">";
	$skin_string = $skin_string . $add_string . "<input type=\"hidden\" name=\"langval\" value=\"$langval\"><input type=\"hidden\" name=\"server_id\" value=\"$server_id\"><select name=\"skin_id\" OnChange=\"submit()\" class=\"field2\">";

	if ($l_array_count > 1)
	{
		$i = 0;
		while ($i < $l_array_count)
		{
			$language_entry = $language_array[$i];
			$language_title = $language_entry[0];
			$language_file = $language_entry[1];
		
			if ($langval == $i)
			{	$language_string = $language_string .  "<option value=$i selected>$language_title</option>";	}
			else
			{	$language_string = $language_string .  "<option value=$i>$language_title</option>";	}
			$i++;
		}
		$language_string = $language_string . "</select></form>";
	}
	else
	{	$language_title = $language_array[0][0];
		$language_string = "<form name=\"skin\"><input value=\"$language_title\" type=\"submit\" class=\"bigbut3\"></form>";	
	}
	$g_array_count = count($gameservers);
	if ($g_array_count > 1)
	{
		$i = 0;
		while ($i < $g_array_count)
		{
			$server_title = $gameservers[$i][0];
			
			if ($i == $server_id)
			{	
				$server_string = $server_string .  "<option value=$i selected>$server_title</option>";
			}
			else
			{	$server_string = $server_string .  "<option value=$i>$server_title</option>";	}
			$i++;
		}
		$server_string = $server_string . "</select></form>";
	}
	else
	{	$server_title = $gameservers[0][0];
		$server_string = "<form name=\"skin\"><input value=\"$server_title\" type=\"submit\" class=\"bigbut3\"></form>";	
	}
	$s_array_count = count($skinslist);
	if ($s_array_count > 1)
	{
		$i = 0;
		while ($i < $s_array_count)
		{
			$skin_title = $skinslist[$i];
		
			if ($i == $skin_id)
			{	
				$skin_string = $skin_string .  "<option value=$i selected>$skin_title</option>";
			}
			else
			{	$skin_string = $skin_string .  "<option value=$i>$skin_title</option>";	}
			$i++;
		}
		$skin_string = $skin_string . "</select></form>";
	}
	else
	{	$skin_title = $skinslist[0];
		$skin_string = "<form name=\"skin\"><input value=\"$skin_title\" type=\"submit\" class=\"bigbut3\"></form>";	
	}

	$lang_file = $language_array[$langval][1];
	include($lang_file);		// Import language variables.
	include($skin_dir . $svr_dir_delimit . $wrapfile);

}

function wrap_start($username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps)
{
	$result = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
	if ($result == 2)
	{	$username = "guest";	}
	if ($result == 0)
	{	$username = "";	}
	wrap_it("wrapopen.php", $username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
	return $result;
}

function wrap_start_dummy($username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $default_lang, $language_array, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow)
{
	wrap_it_dummy("wrapopendummy.php", $username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $default_lang, $language_array, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);
}

// WRAP END - Responsible for closing the tables properly before finishing the routine and returning the HTML page to the user.

function wrap_end($username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level)
{
	wrap_it("wrapclose.php", $username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
}

function wrap_end_dummy($username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level)
{
	wrap_it("wrapclosedummy.php", $username, $token, $HTTP_GET_VARS, $HTTP_POST_VARS, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
}

?>
