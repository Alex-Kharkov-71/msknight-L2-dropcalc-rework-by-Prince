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


//
// ***** EXECUTION STARTS HERE *****
//

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
$castle = input_check($_REQUEST['castle'],0);
$action = input_check($_REQUEST['action'],0);
$number = input_check($_REQUEST['number'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
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
	{	die('Could not change to L2J database: ' . mysql_error());	}
	if (($user_access_lvl >= $sec_inc_admin) && ($action == "hour"))
	{	$result = mysql_query("update castle set siegehourofday = '$number' where `id` = '$castle'",$con);	}
	if (($user_access_lvl >= $sec_inc_admin) && ($action == "day"))
	{	$result = mysql_query("update castle set siegedayofweek = '$number' where `id` = '$castle'",$con);	}
	
	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_sevens\" type=\"submit\" class=\"bigbut2\"></form></td>";
	if ($top_ten)
	{	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_topten\" type=\"submit\" class=\"bigbut2\"></form></td>";	}
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"trusted.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_trustedp\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"reference.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_classtree\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"castles.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_caststat\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"clanhall.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Clan Halls\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "</tr></table>";

	$hall_result = mysql_query("select name, ownerid, lease, `desc`, location, grade from clanhall",$con);
	$hall_count = 0;
	$siege_day = array("$lang_unknown", "$lang_sunday", "$lang_monday", "$lang_tuesday", "$lang_wednesday", "$lang_thursday", "$lang_friday", "$lang_saturday");
	while ($r_array = mysql_fetch_assoc($hall_result))
	{
		if ($hall_count == 0)
		{
			echo "<p class=\"dropmain\">&nbsp;</p><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			echo "<td class=\"lefthead\"><p class=\"dropmain\">Hall</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">$lang_owner</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">Lease</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">Location</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">Grade</p></td></tr>";
		}
		$hall_count = 1;
		$hall_name = $r_array['name'];
		$hall_owner = $r_array['ownerid'];
		$hall_lease = comaise($r_array['lease']);
		$hall_desc = $r_array['desc'];
		$hall_location = $r_array['location'];
		$hall_grade = $r_array['grade'];
		$clan_name = "$lang_unknown";
		$clan_result = mysql_query("select clan_name from clan_data where clan_id = '$hall_owner'",$con);
		if ($clan_result)
		{	
			$clan_count = mysql_num_rows($clan_result);
			if ($clan_count)
			{	$clan_name = mysql_result($clan_result,0,"clan_name");	}
			else
			{	$clan_name = "$lang_unknown";	}
		}
		echo "<tr><td class=\"left\"><p class=\"dropmain\">$hall_name";
		if ($hall_desc)
		echo "<br><font class=\"descrip\">$hall_desc</font>";
		echo "</p></td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;$clan_name&nbsp;</p></td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$hall_lease</p></td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$hall_location</p></td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$hall_grade</p></td></tr>";
	}

	if ($hall_count == 0)
	{	writewarn("Sorry, no Clan Halls in the system");	}
	else
	{	echo "</table></center>";	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>