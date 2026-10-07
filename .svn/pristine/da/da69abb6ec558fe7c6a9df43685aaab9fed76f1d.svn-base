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
	
	function date_to_timestamp ($Date)
	{
		$split_date = split('[/.-]', $Date);
		$timestamp = mktime($split_date[3], $split_date[4], $split_date[5], $split_date[1], $split_date[0], $split_date[2]);
		$timestamp = $timestamp*1000;
		return $timestamp;
	}
	
	function timestamp_to_date ($timestamp)
	{
		$date = date("d/m/Y", $timestamp/1000);
		return $date;
	}
	
	if (($user_access_lvl >= $sec_inc_admin) && ($action == "plusday"))		
	{	
		$result = mysql_query("update castle set siegeDate = siegeDate+86400000 where `id` = '$castle'",$con);	
	}
	if (($user_access_lvl >= $sec_inc_admin) && ($action == "minusday"))		
	{	
		$result = mysql_query("update castle set siegeDate = siegeDate-86400000 where `id` = '$castle'",$con);	
	}
	if (($user_access_lvl >= $sec_inc_admin) && ($action == "plusmin"))		
	{	
		$result = mysql_query("update castle set siegeDate = siegeDate+3600000 where `id` = '$castle'",$con);	
	}
	if (($user_access_lvl >= $sec_inc_admin) && ($action == "minusmin"))		
	{	
		$result = mysql_query("update castle set siegeDate = siegeDate-3600000 where `id` = '$castle'",$con);	
	}
	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_sevens\" type=\"submit\" class=\"bigbut2\"></form></td>";
	if ($top_ten)
	{	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_topten\" type=\"submit\" class=\"bigbut2\"></form></td>";	}
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"trusted.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_trustedp\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"reference.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_classtree\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"castles.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_caststat\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"clanhall.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Clan Halls\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "</tr></table>";

	$castle_result = mysql_query("select id, name, taxpercent, siegeDate from castle",$con);
	$castle_count = 0;
	while ($r_array = mysql_fetch_assoc($castle_result))
	{
		if ($castle_count == 0)
		{
			echo "<p class=\"dropmain\">&nbsp;</p><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			echo "<td class=\"lefthead\"><p class=\"dropmain\">$lang_castle</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">$lang_owner</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">$lang_tax</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">$lang_sday</p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">$lang_stime</p></td></tr>";
		}
		$castle_count = 1;
		$castle_id = $r_array['id'];
		$castle_name = $r_array['name'];
		$castle_tax = $r_array['taxpercent'];
		$siegeDate = timestamp_to_date($r_array['siegeDate']);
		$siegeTime = date("H",$r_array['siegeDate']/1000);
		$clan_name = "$lang_unknown";
		$clan_result = mysql_query("select clan_name from clan_data where hascastle = '$castle_id'",$con);
		if ($clan_result)
		{	
			$clan_count = mysql_num_rows($clan_result);
			if ($clan_count)
			{	$clan_name = mysql_result($clan_result,0,"clan_name");	}
			else
			{	$clan_name = "$lang_unknown";	}
		}
		echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$castle_name</p></td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$clan_name</p></td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$castle_tax%</p></td>";
		if ($user_access_lvl < $sec_inc_admin)
		{	
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$siegeDate</p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$siegeTime:00</p></td></tr>";
		}
		else
		{	
			echo "<td class=\"dropmain\"><table><tr><td><p class=\"dropmain\">$siegeDate</p></td><td>";
			echo "<form action=\"castles.php\">
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\">
		<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
		<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"castle\" type=\"hidden\" value=\"$castle_id\">
		<input name=\"action\" type=\"hidden\" value=\"plusday\">
		<input value=\"+\" type=\"submit\" class=\"bigbut2\"></form></td><td>";
		if (($r_array['siegeDate']/1000) > time())
		{
			echo "<form action=\"castles.php\">
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\">
		<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
		<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"castle\" type=\"hidden\" value=\"$castle_id\">
		<input name=\"action\" type=\"hidden\" value=\"minusday\">
		<input value=\"-\" type=\"submit\" class=\"bigbut2\"></form>";
		}
		echo "</td></tr></table></td>";
		echo "<td class=\"dropmain\"><table><tr><td><p class=\"dropmain\">$siegeTime:00</p></td><td>";
		echo "<form action=\"castles.php\">
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\">
		<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
		<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"castle\" type=\"hidden\" value=\"$castle_id\">
		<input name=\"action\" type=\"hidden\" value=\"plusmin\">
		<input value=\"+\" type=\"submit\" class=\"bigbut2\"></form></td><td>";
		if (($r_array['siegeDate']/1000) > time())
		{
			echo "<form action=\"castles.php\">
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\">
		<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
		<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"castle\" type=\"hidden\" value=\"$castle_id\">
		<input name=\"action\" type=\"hidden\" value=\"minusmin\">
		<input value=\"-\" type=\"submit\" class=\"bigbut2\"></form>";
		}
		echo "</td></tr></table></tr>";

		}
	}

	if ($castle_count == 0)
	{	writewarn("Sorry, no Castles in the system");	}
	else
	{	echo "</table></center>";	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>