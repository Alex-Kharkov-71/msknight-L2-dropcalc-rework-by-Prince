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
	if ($user_access_lvl < $kick_player)		// If insufficient access level, then terminate script.
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

	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "<p class=\"popup\">Could Not Connect</p>";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Wrap_start could not change to logserver database: ' . mysql_error());	}

	if ($unban)
	{	echo "<p class=\"popup\">About to un-ban $touser</p>";	}
	else
	{	echo "<p class=\"popup\">About to ban $touser</p>";	}

	$result = mysql_query("select accessLevel from $dblog_l2jdb.accounts where login = '$touser'",$con2);
	$op_admin = mysql_result($result,0,"accessLevel");
	if (($op_admin > 0) && ($user_access_lvl < $sec_inc_admin))
	{
		echo "<p class=\"popup\">Only Admins can ban GM accounts.</p>";
		echo "</center></body></html>";
		return 0;
	}
	if ($user_access_lvl < $kick_player)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		// If we have entered with an item, then attempt to give it.
		if ($kick)
		{
			// Look up the user and see if they are on line.
			if ($unban)
			{	$sql = "update $dblog_l2jdb.accounts set accessLevel=0 where login = '$touser'";	}
			else
			{	
				$sql = "update $dblog_l2jdb.accounts set accessLevel=-1 where login = '$touser'";	
				$result_i = mysql_query($sql,$con2);
				$sql = "update $dblog_l2jdb.knightdrop set token=0 where name = '$touser'";	
			}
			$result_i = mysql_query($sql,$con2);
			if ($result_i)
			{	
				if ($unban)
				{	echo "<p class=\"popup\">$touser is now un-banned</p>";	}
				else
				{	
					echo "<p class=\"popup\">$touser is now banned</p>";	
					$result_i = mysql_query("select char_name from $db_l2jdb.characters where online = 1 and account_name = '$touser'",$con);
					while ($r_array = mysql_fetch_assoc($result_i))
					{
						$char_name = $r_array['char_name'];
						$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
						if($usetelnet)
						{
							$give_string = 'kick ' . utf82lang ($char_name) ;
							$give_string2 = 'msg ' . utf82lang ($char_namer) . ' ADMIN HAS BANNED YOUR ACCOUNT.';
							$give_string3 = 'msg ' . utf82lang ($char_name) . ' PLEASE TALK WITH YOUR ADMIN TEAM.';
							fputs($usetelnet, $telnet_password);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, $give_string2);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, $give_string3);
							fputs($usetelnet, "\r\n");
							fclose($usetelnet);
							sleep(5);
							$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
							fputs($usetelnet, $telnet_password);
							fputs($usetelnet, $give_string);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, "exit\r\n");
							fclose($usetelnet);
							echo "<p class=\"popup\">Kick command sent</p>";
						}
						else
						{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
					}
				}	
			}
			else
			{	echo "<p class=\"popup\">Couldn't write to character database.,/p>";	}
		}
		else
		{
			// Echo out the giving form.
			echo "	
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"popuptrans\"><form action=\"bana.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"touser\" type=\"hidden\" value=\"$touser\">
			<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
			<input name=\"kick\" type=\"hidden\" value=\"1\">";
			if ($unban)
			{	echo "<input value=\"Un-ban Account\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\">
				<input name=\"unban\" type=\"hidden\" value=\"1\">";	}
			else
			{	echo "<input value=\"Ban Account\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\">";	}
			echo "</form></td>
			</tr></table>
			";
		}
	}
}

echo "</center></body></html>";

?>