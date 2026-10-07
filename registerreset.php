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
include('authsendemail.php');

// Retrieve environment variables
$usrname = input_check($_REQUEST['usrname'],1);
$langval = input_check($_REQUEST['langval'],2);
$name1 = input_check($_REQUEST['name1'],1);
$email1 = preg_replace('/[&%$\/\\\|<>#£]/','',$_REQUEST['email1']);
$user = input_check($_REQUEST['user'],1);
$key = preg_replace('/[&%$\\\|<>#£]/','',$_REQUEST['key']);
$password1 = input_check($_REQUEST['psswd1'],0);
$password2 = input_check($_REQUEST['psswd2'],0);
$passwordchk = $_REQUEST['psswd1'];
$ipaddr = $_SERVER["REMOTE_ADDR"];
$sourceurl = $_SERVER["HTTP_REFERER"];

if (!$langval)
{	$langval = "0";	}

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

wrap_start_dummy($username, $token, $_GET, $_POST, $langval, $default_lang, $language_array, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);

if (!$allowpassreset)
{
	echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><h2 class=\"dropmain\">Sorry - reminder by drop calc has been disabled.</h2>";
	wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
	return 0;
}

$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
mysql_query("SET NAMES 'utf8'", $con2);
if (!$con2)
{
	echo "Could Not Connect";
	die('Wrap_start could not connect to logserver database: ' . mysql_error());
}		
if (!mysql_select_db("$dblog_l2jdb",$con2))
{
	die('Wrap_start could not change to logserver database: ' . mysql_error());
}
echo "<p class=\"dropmain\">&nbsp;</p>";

if ($user)
{
	$sql = "select request_key, emailcheck, password from $dblog_l2jdb.knightdrop where name = '$user'";
	$result_i = mysql_query($sql,$con2);
	if ($result_i)
	{
		$old_key = mysql_result($result_i,0,"request_key");
		$email_check = mysql_result($result_i,0,"emailcheck");
		$password = mysql_result($result_i,0,"password");
		if ($email_check)
		{
			if (strlen($old_key) < 5)
			{	echo "<h2 class=\"dropmain\">Error - stored key corrupt</h2>";	}
			else
			{
				if ($old_key == $key)
				{
					$sql = "update $dblog_l2jdb.knightdrop set request_key = '', emailcheck = 0 where name = '$user'";
					$result_i = mysql_query($sql,$con2);
					if (!$result_i)
					{	echo "<h2 class=\"dropmain\">Error - couldn't confirm account.</h2>";	}
					else
					{	
						$sql = "update $dblog_l2jdb.accounts set password = '$password' where login = '$user'";
						$result_i = mysql_query($sql,$con2);
						if (!$result_i)
						{	echo "<h2 class=\"dropmain\">Error - couldn't update password.</h2>";	}
						else
						{	echo "<h2 class=\"dropmain\">Success - account confirmed.</h2>";	}
					}
					
				}
				else
				{	echo "<h2 class=\"dropmain\">Error - Keys don't match!</h2>";	}
			}
		}
		else
		{
			if (strlen($old_key) < 5)
			{	echo "<h2 class=\"dropmain\">Error - Account already activated?</h2>";	}
			else
			{
				if ($old_key == $key)
				{
					if ($password1)
					{
						$passwordchk2 = preg_replace('/[^a-z,^A-Z,^0-9]/', '', $password1);
						if (($passwordchk != $password1) || ($passwordchk2 != $password1))
						{
							echo "<h2 class=\"dropmain\">$lang_passillchar</h2>";
							$password1 = "";
						}
						if ((strlen($password1) < 3) && ($usrname))
						{
							echo "<h2 class=\"dropmain\">$lang_pass_minthree</h2>";
							$password1 = "";
						}
						if (($password1 != $password2) && ($usrname))
						{
							echo "<h2 class=\"dropmain\">$lang_pass_nomatch</h2>";
							$password1 = "";
						}
						if ($password1)
						{
							$newpasskey = base64_encode(pack("H*", sha1(utf8_encode($password1))));
							$sql = "update $dblog_l2jdb.accounts set password = '$newpasskey' where login = '$user'";
							$result_i = mysql_query($sql,$con2);
							if ($result_i)
							{	echo "<h2 class=\"dropmain\">Change succeeded</h2>";	}
							else
							{	echo "<h2 class=\"dropmain\">Error - New password not recorded!</h2>";	}
							$sql = "update $dblog_l2jdb.knightdrop set request_key = '' where name = '$user'";
							$result_i = mysql_query($sql,$con2);
						}
					}
					if (!$password1)
					{
						echo "
						<p class=\"dropmain\">&nbsp;</p>
						<h2 class=\"dropmain\">Password reset form for $user</h2>
						<center><hr width=\"30%\"></center>
						<form action=\"registerreset.php\" method=\"post\">
						<center><table class=\"blanktab\"><input name=\"langval\" value=\"$langval\" type=\"hidden\">
							<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
							<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
							<input name=\"user\" value=\"$user\" type=\"hidden\">
							<input name=\"key\" value=\"$key\" type=\"hidden\">
						<tr><td class=\"noborderback\"><p class=\"heading2\">$lang_password - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"psswd1\" type=\"password\" value=\"\" maxlength=\"$max_pass_length\"></td></tr>
						<tr><td class=\"noborderback\"><p class=\"heading2\">$lang_confirm - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"psswd2\" type=\"password\" value=\"\" maxlength=\"$max_pass_length\"></td></tr>
						</table><p>&nbsp;</p><input value=\"Send Reminder\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></center>
						</form>	";
					}
				}
				else
				{	echo "<h2 class=\"dropmain\">Error - Keys don't match!</h2>";	}
			}	
		}
	}
	else
	{	echo "<h2 class=\"dropmain\">Error - Can't retrieve request key from database!</h2>";	}
	$usrname = " ";
	$name1 ="";
	$email1 = "";
}

if (($name1) || ($email1))
{
	$usrname = " ";
	
	if ($name1)
	{	$sql = "select name, email, request_time from $dblog_l2jdb.knightdrop where name = '$name1'";	}
	else
	{	$sql = "select name, email, request_time from $dblog_l2jdb.knightdrop where email = '$email1'";	}
	$result_i = mysql_query($sql,$con2);
	$cur_time = time();
	if ($result_i)
	{
		$old_name = mysql_result($result_i,0,"name");
		$old_time = mysql_result($result_i,0,"request_time");
		$old_email = mysql_result($result_i,0,"email");
		$old_time = $old_time + 300;
		if ($old_time < $cur_time)
		{
			srand(make_seed());
			$i = 0;
			$key = "";
			while ($i < 30)
			{
				$key = $key . chr(rand(65, 90));
				$i++;
			}
			
			$remind_key = base64_encode(pack("H*", sha1(utf8_encode($key))));
			$remind_key = preg_replace('/[+]/','a',$remind_key);
			$remind_key = preg_replace('/[?]/','f',$remind_key);
			$remind_key = preg_replace('/[&]/','r',$remind_key);
			$remind_key = preg_replace('/[=]/','q',$remind_key);
			$url = $sourceurl . "&user=" . $old_name . "&key=" . $remind_key;
			$sql = "update $dblog_l2jdb.knightdrop set request_time = $cur_time, request_key = '$remind_key' where email = '$old_email'";
			$result_i = mysql_query($sql,$con2);
			if ($result_i)
			{	
				if ($phpsmtp)
				{	$new_line = "<br>";	}
				else
				{	$new_line = "\n";	}
				$body = "Dear " . $old_name . ", $new_line $new_line We have received a request to reset your game password.  If you did not request this, please carry on as normal.  NO changes to your password have been made yet. $new_line $new_line ";
				$body = $body . "However, if you did request a new password, then please use this link to reset your password. $new_line $new_line ";
				if ($phpsmtp)
				{	$body = $body . "<a href=\"$url\">" . $url . "</a>";	}
				else
				{	$body = $body . $url;	}
				$body = $body . " $new_line $new_line A new password will be given to you, and you can then change this next time you log on. $new_line $new_line Enjoy your game! $new_line";
				$headers = 'From: ' . $e_mail_from . "\r\n" . 'Return-path: ' . $e_mail_from . 'Reply-To: ' . $e_mail_from . "\r\n";
				if ($phpsmtp)
				{
					authSendEmail($old_email, "L2J Password Reset", $body, $smtpserver, $smtpport, $smtptimeout, $smtpuser, $smtppassword, $smtplocalhost, $e_mail_from, $smtp_debug);
					echo("<h2 class=\"dropmain\">Reminder sent.</h2>");	
				}
				else
				{
					if (mail($old_email, "L2J Password Reset", $body, $headers)) 
					{	echo("<h2 class=\"dropmain\">Reminder sent.</h2>");	}
					else
					{	echo("<h2 class=\"dropmain\">Error - Failed to send e-mail.</h2>");	}
				}
			}
			else
			{	echo "<h2 class=\"dropmain\">Error - Couldn't set the reminder variable.</h2>";	}
		}
		else
		{	echo "<h2 class=\"dropmain\">Can't send two reminders within 5 minutes!</h2>";	}
	}
	else
	{	echo "<h2 class=\"dropmain\">Error - Can't find user account!</h2>";	}
}

if (!$usrname)
{	echo "
		<p class=\"dropmain\">&nbsp;</p>
		<h2 class=\"dropmain\">Remind by account name or e-mail</h2>
		<center><hr width=\"30%\"></center>
		<form action=\"registerreset.php\" method=\"post\">
		<center><table class=\"blanktab\"><input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<tr><td class=\"noborderback\"><p class=\"heading2\">Username - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"name1\" type=\"text\" value=\"\" maxlength=\"40\"></td></tr>
		</table><p>&nbsp;</p><input value=\"Send Reminder\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></center>
		</form>
		<center><hr width=\"30%\"></center>
		<form action=\"registerreset.php\" method=\"post\">
		<center><table class=\"blanktab\"><input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<tr><td class=\"noborderback\"><p class=\"heading2\">E-mail - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"email1\" type=\"text\" value=\"\" maxlength=\"50\"></td></tr>
		</table><p>&nbsp;</p><input value=\"Send Reminder\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></center>
		</form>
		";
}
echo "	<p class=\"dropmain\">&nbsp;</p>
	<center><img src=\"" . $images_dir . "bg_foot.gif\"></center>";
wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>