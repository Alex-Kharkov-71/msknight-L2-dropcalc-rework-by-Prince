<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/*
Michelle Knight's Drop Calc - Version 5.0.0
Author - Michelle Knight
Copyright 2006/2007
Contact - dropcalc@msknight.com

GNU General Licence
Use and distribute freely, but leave headers intact and make no charge.
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
$linenum = input_check($_REQUEST['linenum'],0);
$sendchat = input_check($_REQUEST['sendchat'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)		// If the access level is not correct, then terminate the script.
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{		// Create the buttons to make an announcement in-game.
		echo "<center><form method=\"post\" action=\"javascript:popit('makeannounce.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','470','130');\"><input value=\" Make Announcement \" type=\"submit\" class=\"bigbut2\"></form><p class=\"dropmain\">&nbsp;</p>\n";
		// Locate the file that stores the server announcements.
		$file_loc = $server_dir . 'data' . $svr_dir_delimit . 'announcements.txt';

		// Read in the file and echo it to the screen in a table.
		echo "<table class=\"dropmain\">";
		$handle = @fopen($file_loc, "r");
		if ($handle) 
		{
 			while ($line_in = fgets($handle)) 
			{	echo "<tr><td class=\"dropmain\"><pre class=\"dropmaintable\">".lang2utf8($line_in)."</pre></td></tr>";	}
			fclose($handle);
		}
		echo "</table><p class=\"dropmain\">&nbsp</p>";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>