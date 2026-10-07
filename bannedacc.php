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
include('playermap.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)		// If insufficient access level, then terminate script.
	{
		echo "<p class=\"dropmain\">You don't have sufficient access.</p>";
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "<p class=\"dropmain\">Could Not Connect</p>";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Wrap_start could not change to logserver database: ' . mysql_error());	}
	echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"popmain\">Banned Accounts</h2>";
	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\">Login</p></td><td class=\"lefthead\"><p class=\"dropmain\">$lang_lastlogon</p></td></tr>";
	$result = mysql_query("select login, lastactive from accounts where accessLevel = -1 order by lastactive DESC",$con2);
	while ($r_array = mysql_fetch_assoc($result))
	{
		$a_login = $r_array['login'];
		$a_lasta = $r_array['lastactive'] / 1000;
		$a_lastaa = date('dS F Y \- h:iA T',$a_lasta);
		echo "<tr><td class=\"dropmain\"><p class=\"dropmain\"><a href=\"a-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&account=$a_login\" class=\"dropmain\">$a_login</a>";
		if ($result_note = mysql_query("select count(*) from $knight_db.accnotes where charname = '$a_login'",$con2))
		{
			$count_notes = mysql_result($result_note,0,"count(*)");
			if ($count_notes > 0)
			{	echo "&nbsp;<a href=\"acc-notes.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$a_login\"><font color=$yellow_code>[-$count_notes-]</font></a>";	}
		}
		echo "</p></td><td class=\"dropmain\"><p class=\"dropmain\">$a_lastaa</p></td></tr>";
	}
	echo "</table></center>";
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>