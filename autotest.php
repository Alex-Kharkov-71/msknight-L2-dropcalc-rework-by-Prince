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
$touser = input_check($_REQUEST['touser'],1);
$kick = input_check($_REQUEST['kick'],0);
$unban = input_check($_REQUEST['unban'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

echo "<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>
<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
</head>
<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
<center>";


$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
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
		echo "<p class=\"popup\">Could Not Connect</p>";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{	die('Could not change to L2J database: ' . mysql_error());	}

	if ($user_access_lvl < $sec_inc_admin)			// If not the correct access rights, then terminate the script.
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{		// Insert a test character designed to be detected by the security system.
		$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
		if($usetelnet)
		{
			$sql = "insert into characters (char_name, maxhp) values ('@AdminTestChar@', '100000')";	
			$result = mysql_query($sql,$con);
			$give_string = 'announce Security test character created.';
			fputs($usetelnet, $telnet_password);
			fputs($usetelnet, "\r\n");
			fputs($usetelnet, $give_string);
			fputs($usetelnet, "\r\n");
			fputs($usetelnet, "exit\r\n");
			fclose($usetelnet);
			echo "<p class=\"popup\">Security test character created.</p>";
		}
	}
}

echo "</center></body></html>";

?>