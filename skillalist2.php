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
$username = input_check($_REQUEST['username']);
$token = input_check($_REQUEST['token']);
$langval = input_check($_REQUEST['langval']);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$file = input_check($_REQUEST['file']);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.
$file2 = $file + 1;
echo "<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>";
if ($file < 100)
{	echo "<META content=\"10;url=skillalist2.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&file=$file2\" http-equiv=refresh >";	}
echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
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
	if ($user_access_lvl < $sec_inc_admin)		// If insufficient access level, then terminate script.
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
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

	$file_base = $server_dir . 'data' . $svr_dir_delimit . 'stats' . $svr_dir_delimit . 'skills' . $svr_dir_delimit;
	$s = $file;
	while (strlen($s) < 2)
	{	$s = '0' . $s;	}
	$file_loc = $file_base . $s . '00-' . $s . '99.xml';
	$handle = fopen($file_loc, "r");
	$skill_on = 99999;
	while (!feof($handle))
	{
		if ($php_type >= 1)
		{	$line = stream_get_line($handle, 10000, "\n"); }
		else
		{	$line = fgets($handle, 1000); }
		if (strpos($line,'skill id='))
		{	
			$val = substr($line,strpos($line,'"')+1,7);
			$val = substr($val,0,strpos($val,'"'));
			$skill_on = intval($val);
			echo "<p>$s - $skill_on</p>";
		}
		elseif ($skill_on < 99999)
		{
					
		}
			
	}
	fclose($handle);
	echo "<p>$s</p>";
}

echo "</center></body></html>";

?>