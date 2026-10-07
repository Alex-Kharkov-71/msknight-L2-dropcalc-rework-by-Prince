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
$sendchat = input_check($_REQUEST['sendchat'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

echo "
<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>
	<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
</head>

<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
<center>
";

$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
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
		$id = substr(base64_encode(pack("H*", sha1(utf8_encode($username)))), 0, 3);
		if ($sendchat)
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'announce (' . $id . ') ' . $sendchat;
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}


		// Echo out the giving form.
		echo "	
		<p class=\"popup\">Make Announcement</p>
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
		<tr>
		<td class=\"noborderback\"><form action=\"makeannounce.php\"><input name=\"sendchat\" maxlength=\"93\" size=\"50\" type=\"text\" value=\"\" class=\"popup\"></td></tr>
		<tr><td class=\"noborderback\" align=\"left\"><input value=\"Send Announcement\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\">
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"touser\" type=\"hidden\" value=\"$touser\">
		</form></td>
		</tr></table>

		";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
