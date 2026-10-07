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

/* SERVER NOTES
The system will only operate assuming that telnet is active to the server.
Put the telnet configuration in to the config.php file.
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
$go = input_check($_REQUEST['go'],0);

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
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "<p class=\"popup\">Could Not Connect</p>";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{
		die('Wrap_start could not change to logserver database: ' . mysql_error());
	}

	echo "<p class=\"popup\">About to import logon accounts</p>";	

	if ($user_access_lvl < $sec_inc_admin)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		if ($go)
		{
			
			$sql = "drop table if exists $dblog_l2jdb.knightdrop";	
			$result_i = mysql_query($sql,$con2);
			if (!$result_i)
			{	
				echo "<p class=\"popup\">Couldn't drop the knightdrop table</p>";	
			}
			else
			{
				$sql = "CREATE TABLE $dblog_l2jdb.knightdrop ( 
				`name` varchar(45) NOT NULL default '', 
				`lastaction` int(11) default NULL, 
				`token` varchar(10) default NULL, 
				mapaccess int(20) default 0, 
				recipeaccess int(20) default 0, 
				`gdaccess` int(20) default 0,
				boxingok int(1) NULL, 
				warnlevel int(1) NULL, 
				`characcess` int(20) default '0',    
				`lastheard` int(20) default '0',   
				ipaddr varchar(30) default NULL, 
				`access_level` int(11) default 0,
				`email` varchar(50) default '',
				`request_time` int(20) default 0,
				`request_key` varchar(45),
				`emailcheck` int(1) NOT NULL default '0',
				`password` varchar(45) default NULL,
				PRIMARY KEY  (`name`), UNIQUE KEY `id` (`name`) )";
				$result_i = mysql_query($sql,$con2);
				if (!$result_i)
				{	
					echo "<p class=\"popup\">Couldn't create the knightdrop table!!!</p>";
				}

				$sql = "insert into $dblog_l2jdb.knightdrop (name, access_level) select login, accessLevel from $dblog_l2jdb.accounts";	
				$result_i = mysql_query($sql,$con2);
				if (!$result_i)
				{	
					echo "<p class=\"popup\">Couldn't import the accounts!!!</p>";
				}
				else
				{
					echo "<p class=\"popup\">Accounts data imported O.K.</p>";
					echo "<p class=\"popup\">NOTE - Please log in again.</p>";
				}
			}
		}
		else
		{
			// Echo out the giving form.
			echo "	
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"popuptrans\"><form action=\"importacc.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
			<input name=\"go\" type=\"hidden\" value=\"yes\">";
			echo "<input value=\"Click to import accounts\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\">";
			echo "</form></td>
			</tr></table>
			";
		}
	}
}

echo "</center></body></html>";

?>