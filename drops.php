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
$action = input_check($_REQUEST['action'],0);
$mobid = input_check($_REQUEST['mobid'],0);
$itemid = input_check($_REQUEST['itemid'],0);
$number = input_check($_REQUEST['number'],0);
$adena = input_check($_REQUEST['adena'],0);
$dispby = input_check($_REQUEST['dispby'],0);
$min = input_check($_REQUEST['min'],0);
$max = input_check($_REQUEST['max'],0);
$drop = input_check($_REQUEST['drop'],0);
$spct = input_check($_REQUEST['spct'],0);
$category = input_check($_REQUEST['category'],0);
$serverpct = input_check($_REQUEST['serverpct'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $adjust_drops)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
		// PHP8/L2JMobius fix: stranitsa pravila drop mobov v tablitse droplist igrovogo
		// servera (changedropmin/max/..., add, delete). V L2JMobius drop zadan v XML
		// datapaka (data/stats/npcs), tablitsy net - pravit nechego. Sama
		// funktsiya otklyuchena; drop smotrite na stranitsakh poiska mobov i predmetov.
		writewarn("Drop adjustment is not available on L2JMobius: drops are defined in the XML datapack (data/stats/npcs). Use the Monster and Item search pages to view drops.");
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>