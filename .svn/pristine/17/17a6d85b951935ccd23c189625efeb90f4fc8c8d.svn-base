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
$itemname = input_check($_REQUEST['itemname'],0);
$lastlines = input_check($_REQUEST['lastlines'],0);
$include = input_check($_REQUEST['include'],2);
$action = input_check($_REQUEST['action'],1);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

if ($include == 0)		// Ensure that we are at least going to be looking at something
{	$include = 65535;	}
$include_str = base_convert($include, 10, 2);	// Convert to binary string and ensure 8 bit length.
while (strlen($include_str) < 16)
{	$include_str = "0" . $include_str;	}
$show_all = substr($include_str,15,1);
$show_tell = substr($include_str,14,1);
$show_trade = substr($include_str,13,1);
$show_shout = substr($include_str,12,1);
$show_party = substr($include_str,11,1);
$show_clan = substr($include_str,10,1);
$show_alliance = substr($include_str,9,1);
$show_petgm = substr($include_str,8,1);
$show_herovoice = substr($include_str,7,1);
$show_partycom = substr($include_str,6,1);
if (($action == "addall") && ($show_all == "0"))
{	$include = $include + 1;	}
if (($action == "hideall") && ($show_all == "1"))
{	$include = $include - 1;	}
if ($action == "onlyall")
{	$include = 1;	}
if (($action == "addtell") && ($show_tell == "0"))
{	$include = $include + 2;	}
if (($action == "hidetell") && ($show_tell == "1"))
{	$include = $include - 2;	}
if ($action == "onlytell")
{	$include = 2;	}
if (($action == "addtrade") && ($show_trade == "0"))
{	$include = $include + 4;	}
if (($action == "hidetrade") && ($show_trade == "1"))
{	$include = $include - 4;	}
if ($action == "onlytrade")
{	$include = 4;	}
if (($action == "addshout") && ($show_shout == "0"))
{	$include = $include + 8;	}
if (($action == "hideshout") && ($show_shout == "1"))
{	$include = $include - 8;	}
if ($action == "onlyshout")
{	$include = 8;	}
if (($action == "addparty") && ($show_party == "0"))
{	$include = $include + 16;	}
if (($action == "hideparty") && ($show_party == "1"))
{	$include = $include - 16;	}
if ($action == "onlyparty")
{	$include = 16;	}
if (($action == "addclan") && ($show_clan == "0"))
{	$include = $include + 32;	}
if (($action == "hideclan") && ($show_clan == "1"))
{	$include = $include - 32;	}
if ($action == "onlyclan")
{	$include = 32;	}
if (($action == "addalliance") && ($show_alliance == "0"))
{	$include = $include + 64;	}
if (($action == "hidealliance") && ($show_alliance == "1"))
{	$include = $include - 64;	}
if ($action == "onlyalliance")
{	$include = 64;	}
if (($action == "addpetgm") && ($show_petgm == "0"))
{	$include = $include + 128;	}
if (($action == "hidepetgm") && ($show_petgm == "1"))
{	$include = $include - 128;	}
if ($action == "onlypetgm")
{	$include = 128;	}
if (($action == "addherov") && ($show_herovoice == "0"))
{	$include = $include + 256;	}
if (($action == "hideherov") && ($show_herovoice == "1"))
{	$include = $include - 256;	}
if ($action == "onlyherov")
{	$include = 256;	}
if (($action == "addpartyc") && ($show_partycom == "0"))
{	$include = $include + 512;	}
if (($action == "hidepartyc") && ($show_partycom == "1"))
{	$include = $include - 512;	}
if ($action == "onlypartyc")
{	$include = 512;	}

$include_str = base_convert($include, 10, 2);	// Re-run the conversion after any adjustments.
while (strlen($include_str) < 16)
{	$include_str = "0" . $include_str;	}
$show_all = substr($include_str,15,1);
$show_tell = substr($include_str,14,1);
$show_trade = substr($include_str,13,1);
$show_shout = substr($include_str,12,1);
$show_party = substr($include_str,11,1);
$show_clan = substr($include_str,10,1);
$show_alliance = substr($include_str,9,1);
$show_petgm = substr($include_str,8,1);
$show_herovoice = substr($include_str,7,1);
$show_partycom = substr($include_str,6,1);

if ($lastlines)
{
	$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $delay_logs, "clog.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&include=$include&lastlines=1#bottomline", $low_graphic_allow, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
}
else
{
	$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", $low_graphic_allow, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
}

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
		echo "<table><tr>";
		if ($show_all == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View All\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addall\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide All\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hideall\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_tell == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Tell\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addtell\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Tell\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hidetell\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_trade == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Trade\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addtrade\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Trade\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hidetrade\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_shout == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Shout\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addshout\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Shout\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hideshout\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_party == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Party\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addparty\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Party\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hideparty\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_clan == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Clan\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addclan\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Clan\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hideclan\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_alliance == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Alliance\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addalliance\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Alliance\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hidealliance\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_petgm == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View PetGM\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addpetgm\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide PetGM\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hidepetgm\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		
		echo "</tr><tr>";		
		
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		if ($show_herovoice == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Hero V\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addherov\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Hero V\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hideherov\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		if ($show_herovoice == "0")
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"View Party Com\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"addpartyc\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}
		else
		{	echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Hide Party Com\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"hidepartyc\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";	}

		echo "</tr><tr>";	
		
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only All\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlyall\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Tell\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlytell\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Trade\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlytrade\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Shout\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlyshout\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Party\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlyparty\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Clan\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlyclan\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Alliance\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlyalliance\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only PetGM\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlypetgm\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		
		echo "</tr><tr>";	
		
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Hero V\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlyherov\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";
		echo "<td><form method=\"post\" action=\"clog.php\"><input value=\"Only Party Com\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"$lastlines\"><input name=\"action\" type=\"hidden\" value=\"onlypartyc\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form></td>";

		echo "</tr></table>";
		
		$file_loc = $server_dir . 'log' . $svr_dir_delimit . 'chat.log';

		if ($lastlines)
		{
			$i=1;
			echo "<form method=\"post\" action=\"clog.php\"><input value=\" <- View Whole File -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form>";
			echo"<pre class=\"dropmain\">";
			$handle = fopen($file_loc, "r");
			fseek($handle, -10000, SEEK_END);
			while (!feof($handle))
			{
				if ($php_type >= 1)
				{	$line = stream_get_line($handle, 100000, "\n"); }
				else
				{	$line = fgets($handle, 1000); }
				if ($i == 1)
				{	$lines = array($line);	}
				else
				{	array_push($lines, $line);	}
				$i++;
			}
			$start = $i - 82;
			if ($start < 1)
			{	$start = 1;	}
			while ($start < $i)
			{
				$one_line = trim($lines[$start]);
				$show_check = 1;
				if (($show_all == "0") && (strpos($one_line,"] ALL ") > 0))
				{	$show_check = 0;	}
				if (($show_tell == "0") && (strpos($one_line,"] TELL ") > 0))
				{	$show_check = 0;	}
				if (($show_trade == "0") && (strpos($one_line,"] TRADE ") > 0))
				{	$show_check = 0;	}
				if (($show_shout == "0") && (strpos($one_line,"] SHOUT ") > 0))
				{	$show_check = 0;	}
				if (($show_party == "0") && (strpos($one_line,"] PARTY ") > 0))
				{	$show_check = 0;	}
				if (($show_clan == "0") && (strpos($one_line,"] CLAN ") > 0))
				{	$show_check = 0;	}
				if (($show_alliance == "0") && (strpos($one_line,"] ALLIANCE ") > 0))
				{	$show_check = 0;	}
				if (($show_petgm == "0") && (strpos($one_line,"] PETITION_GM ") > 0))
				{	$show_check = 0;	}
				if (($show_herovoice == "0") && (strpos($one_line,"] HERO_VOICE ") > 0))
				{	$show_check = 0;	}
				if (($show_partycom == "0") && (strpos($one_line,"] PARTYROOM_COMMANDER ") > 0))
				{	$show_check = 0;	}
				if ($show_check == 1)
				{
					if ($clog_type == 1)
					$one_line = trim(colourise($one_line, $skin_dir, $svr_dir_delimit));
					if (strlen($line) > 0)
					{	echo "$one_line<br>";	}
				}
				$start++;
			}
		}
		else
		{
			$lines = file($file_loc);
			$line_nums = count($lines);
			echo "<form method=\"post\" action=\"clog.php\"><input value=\" <- View Last Lines -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"1\"><input name=\"include\" type=\"hidden\" value=\"$include\"></form>";
			echo "<pre class=\"dropmain\">";
			foreach ($lines as $line_num => $line) 
			{
				$line = trim($line);
				$show_check = 1;
				if (($show_all == "0") && (strpos($line,"] ALL ") > 0))
				{	$show_check = 0;	}
				if (($show_tell == "0") && (strpos($line,"] TELL ") > 0))
				{	$show_check = 0;	}
				if (($show_trade == "0") && (strpos($line,"] TRADE ") > 0))
				{	$show_check = 0;	}
				if (($show_shout == "0") && (strpos($line,"] SHOUT ") > 0))
				{	$show_check = 0;	}
				if (($show_party == "0") && (strpos($line,"] PARTY ") > 0))
				{	$show_check = 0;	}
				if (($show_clan == "0") && (strpos($line,"] CLAN ") > 0))
				{	$show_check = 0;	}
				if (($show_alliance == "0") && (strpos($line,"] ALLIANCE ") > 0))
				{	$show_check = 0;	}
				if (($show_petgm == "0") && (strpos($line,"] PETITION_GM ") > 0))
				{	$show_check = 0;	}
				if (($show_herovoice == "0") && (strpos($line,"] HERO_VOICE ") > 0))
				{	$show_check = 0;	}
				if (($show_partycom == "0") && (strpos($line,"] PARTYROOM_COMMANDER ") > 0))
				{	$show_check = 0;	}
				if ($show_check == 1)
				{
					if ($clog_type == 1)
					$line = trim(colourise($line, $skin_dir, $svr_dir_delimit));
					if (strlen($line) > 0)
					{	echo $line . "<br>";	}
				}
		    }
		}
		echo "</pre><table class=\"dropmain\"><tr><td class=\"noborder\"><p class=\"dropmain\">Last update - ";
		echo date('l dS \of F Y h:i:s A');
		echo "</td><td class=\"noborder\" valign=\"center\" align=\"right\" width=\"300\">";
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{
			echo "<form method=\"post\" action=\"javascript:popit('makeannounce.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','470','130');\"><input value=\" Make Announcement \" type=\"submit\" class=\"bigbut2\"></form>";
		}
		echo "<a name=\"bottomline\"></a></td></tr></table>";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $low_graphic_allow);

?>