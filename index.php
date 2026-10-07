
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


function evalUserPass($username, $password, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb)
{
	// Connect to DB
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Wrap_start could not change to logserver database: ' . mysql_error());}

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

	// Query for user name
	if (!$result = mysql_query("select online from characters where online = '1'",$con))
	{	die('Could not retrieve from database: ' . mysql_error());	}

	// Query for user name
	$encrypt_pass = base64_encode(pack("H*", sha1(utf8_encode($password))));
	if (!$result = mysql_query("select password, accessLevel from $dblog_l2jdb.accounts where login = '$username'",$con2))
	{	die('Could not retrieve from logon database: ' . mysql_error());	}
	// If return array empty, then username not found.
	$row = mysql_fetch_array($result);
	if (!$row)
	{	return 0;	}

	// If password doesn't match, then return.
	if ($row[0] != $encrypt_pass)
	{	return 0;	}

	// Generate random token
	$token = "";
	for ($i=1; $i<=10; $i++)
	{  $token = ($token * 10) + rand()%9;	}
	$usr_access = mysql_result($result,0,"accessLevel");

	// If the user is in the gametables and the password checks, but the user isn't in the knightdrop 
	// (probably a new installation of the drop calc) then create a default entry for them.
	$result = mysql_query("select token, emailcheck from $dblog_l2jdb.knightdrop where name = '$username'",$con2);
	$result_count = mysql_num_rows($result); 
	if (!$result_count)
	{
		// Work out the number of hours heard from.
		$timeofday = intval(time() / 3600);
		$ipaddr = $_SERVER["REMOTE_ADDR"];
		$default_maps = '0';
		$default_recipe = '0';
		$default_character = '0';
		if ($all_newusers_maps)
		{	$default_maps = '999999999999999999';	}
		if ($all_newusers_recipe)
		{	$default_recipe = '999999999999999999';	}
		if ($all_newusers_character)
		{	$default_character = '999999999999999999';	}
		if (!mysql_query("insert into $dblog_l2jdb.knightdrop (name, lastaction, token, mapaccess, recipeaccess, characcess, lastheard, ipaddr, access_level) values ('$username', 0, 0, '$default_maps', '$default_recipe', '$default_character', '$timeofday' ,'$ipaddr' ,'$usr_access')",$con2))
		{	echo "<h2 class=\"dropmain\">Error - Couldn't insert user into knightdrop table.<br>" . mysql_error() . "</h2>";	}
	}
	
	$email_check = mysql_result($result,0,"emailcheck");
	if ($email_check)
	{	return -1;	}

	// Find out the number of minutes in the day from the clock and write to user profile.
	$timeofday = intval(time() / 60);
	$sql = "update $dblog_l2jdb.knightdrop set lastaction = '$timeofday', token = '$token', ipaddr = '$ipaddr' where name = '$username'";
	if (!$result = mysql_query($sql,$con2))
	{	die('Could not write time to knightdrop database: ' . mysql_error());	}
	return $token;
}


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
$logoffname = input_check($_REQUEST['logoffname'],1);
$langval = $_REQUEST['langval'];
if (strlen($langval) > 0)
{	$langval = input_check($langval,2);	}
else
{	$langval = $default_lang; }
// input_check($_REQUEST['langval'],2);
$userpasswd = input_check($_REQUEST['password'],0);
$action = input_check($_REQUEST['action'],0);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$lang_file = $language_array[$langval][1];
$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
mysql_query("SET NAMES 'utf8'", $con2);
if (!mysql_select_db("$dblog_l2jdb",$con2))
{	die('Wrap_start could not change to logserver database: ' . mysql_error());	}
	
if ($action == 'logoff')
{
	$sql = "update knightdrop set token = 0 where name = '$logoffname'";
	$result = mysql_query($sql,$con2);
	$username = "";
	$action = "";
}

// Select an entry from the knightdrop table.  If it is empty, then assume a new installation and run the import routine silently.
$result_drop = mysql_query("select name from $dblog_l2jdb.knightdrop",$con2);
$recsinknightdrop = 0;
if ($result_drop)
{	$recsinknightdrop = mysql_num_rows($result_drop);	}
if (!$recsinknightdrop)
{
	if (!mysql_query("insert into $dblog_l2jdb.knightdrop (name, access_level) select login, accessLevel from $dblog_l2jdb.accounts",$con2))
	{	echo "<p class=\"popup\">Couldn't import the accounts!!!<br>" . mysql_error() . "</p>";	}
}

// If user name is empty, then just exit out.  If password is empty print message, else carry on.
if (!empty($username))
{
	if (empty($userpasswd) && empty($token))
	{
		wrap_start_dummy($username, $token, $_GET, $_POST, $langval, $default_lang, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);
		writeerror("Sorry, didn't detect a password.");
		wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
	}
	else
	{
	if (empty($token))
	{	$token = evalUserPass($username, $userpasswd, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe);	}
	if	($token == 0)
		{
			wrap_start_dummy($username, $token, $_GET, $_POST, $langval, $default_lang, $language_array,  $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);
			writeerror("Username not found or password doesn't match.");
			wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		}	
		elseif ($token == -1)
		{
			wrap_start_dummy($username, $token, $_GET, $_POST, $langval, $default_lang, $language_array,  $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);
			writeerror("Account not authenticated.<br>Use the reminder system to authenticate the account.");
			wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		}	
		else
		{
		wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
		if ($access_lvl < $sec_inc_gmlevel)
		{
			if ($force_default_skin)
			{
				$user_change_skin = 0;
				$skin_id = $gameservers[$server_id][5];
				$skin_dir = "$skin_id";
				
			}
		}
		include($skin_dir . $svr_dir_delimit . "loggedin.php");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		}
	}
}
else
{
	wrap_start_dummy($username, $token, $_GET, $_POST, $langval, $default_lang, $language_array,  $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);
	if ($access_lvl < $sec_inc_gmlevel)
	{
		if ($force_default_skin)
		{
			$user_change_skin = 0;
			$skin_id = $gameservers[$server_id][5];
			$skin_dir = "$skin_id";
			
		}
	}
	include($skin_dir . $svr_dir_delimit . "login.php");
	wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
}

?>