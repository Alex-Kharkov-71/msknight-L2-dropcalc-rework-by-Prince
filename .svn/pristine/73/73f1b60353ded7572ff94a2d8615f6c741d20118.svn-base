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
$password1 = input_check($_REQUEST['psswd1'],0);
$password2 = input_check($_REQUEST['psswd2'],0);
$email1 = preg_replace('/[&%$\/\\\|<>#£]/','',$_REQUEST['email1']);
$email2 = preg_replace('/[&%$\/\\\|<>#£]/','',$_REQUEST['email2']);
$passwordchk = $_REQUEST['psswd1'];
$usrnamechk = $_REQUEST['usrname'];
$ipaddr = $_SERVER["REMOTE_ADDR"];
$sourceurl = $_SERVER["HTTP_REFERER"];

if (!$langval)
{	$langval = "0";	}

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

wrap_start_dummy($username, $token, $_GET, $_POST, $langval, $default_lang, $language_array, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);

if (!$register_allow)
{
	echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><h2 class=\"dropmain\">Sorry - registration by drop calc has been disabled.</h2>";
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

if ($stopbanIPreg)
{
	$sql = "select login from $dblog_l2jdb.accounts where lastIP = '$ipaddr' and accessLevel < 0";
	$result = mysql_query($sql,$con2);
	$resultcount = mysql_num_rows($result);
	if ($resultcount)
	{
		$login = mysql_result($result,0,"login");
		writewarn("Account $login on ip $ipaddr has been banned.<br>If this is an error, please content an admin.");
		return 0;
	}
}

echo "<p class=\"dropmain\">&nbsp;</p>";
if ($usrname)
{
	$passwordchk2 = preg_replace('/[^a-z,^A-Z,^0-9]/', '', $password1);
        $usrnamechk2 = preg_replace('/[^a-z,^A-Z,^0-9,^_]/', '', $usrname);
	if (($passwordchk != $password1) || ($passwordchk2 != $password1))
	{
		echo "<h2 class=\"dropmain\">$lang_passillchar</h2>";
		$usrname = "";
		$usrnamechk = "";
		$usrnamechk2 = "";
	}
	if ($email1 != $email2)
	{
		echo "<h2 class=\"dropmain\">E-Mail addresses don't match</h2>";
		$usrname = "";
		$usrnamechk = "";
		$usrnamechk2 = "";
	}
	if ((strlen($email1) < 1) && ($usrname))
	{
		echo "<h2 class=\"dropmain\">E-Mail address is empty</h2>";
		$usrname = "";
	}
	if (($usrnamechk != $usrname) || ($usrnamechk2 != $usrname))
	{
		echo "<h2 class=\"dropmain\">$lang_passilluchar</h2>";
		$usrname = "";
	}
	if (((strcasecmp($usrname, "guest")) == 0) && ($usrname))
	{
		echo "<h2 class=\"dropmain\">$lang_pass_noguest</h2>";
		$usrname = "";
	}
	if ((strlen($password1) < 3) && ($usrname))
	{
		echo "<h2 class=\"dropmain\">$lang_pass_minthree</h2>";
		$usrname = "";
	}
	if (($password1 != $password2) && ($usrname))
	{
		echo "<h2 class=\"dropmain\">$lang_pass_nomatch</h2>";
		$usrname = "";
	}

	if (strlen($usrname) > $max_acc_length)
	{
		echo "<h2 class=\"dropmain\">$lang_pass_length $max_acc_length.</h2>";
		$usrname = "";
	}
	
	$sql = "select name from $dblog_l2jdb.knightdrop where email = '$email1'";
	$result_i = mysql_query($sql,$con2);
	if ($result_i)
	{
		$count = mysql_num_rows($result_i);
		if ($count > 0)
		{
			echo "<h2 class=\"dropmain\">E-mail address already registered</h2>";
			$usrname = "";
		}
	}
	
	if ($usrname)
	{
		$sql = "select login from $dblog_l2jdb.accounts where login = '$usrname'";
		$result_i = mysql_query($sql,$con2);
		$resultcount = mysql_num_rows($result_i);
		if (!$resultcount)
		{	
			$usrname = strtolower($usrname);
			$enc_password = base64_encode(pack("H*", sha1(utf8_encode($password1))));
			$todaydate = (time() * 1000);
			if (($allowpassreset) && ($emailcheck))
			{	$sql = "insert into $dblog_l2jdb.accounts (login, lastactive, accessLevel, lastip) values ('$usrname', '$todaydate', '0', '$ipaddr')";	}
			else
			{	$sql = "insert into $dblog_l2jdb.accounts (login, password, lastactive, accessLevel, lastip) values ('$usrname', '$enc_password', '$todaydate', '0', '$ipaddr')";	}
			$result_i = mysql_query($sql,$con2);
			if (!$result_i)
			{	echo "<h2 class=\"dropmain\">Error - Can't create user account!</h2>";	}
			else
			{
				$default_maps = 0;
				$default_recipe = 0;
				$default_character = 0;
				if ($all_newusers_maps)
				{	$default_maps = 999999999999999999;	}
				if ($all_newusers_recipe)
				{	$default_recipe = 999999999999999999;	}
				if ($all_newusers_character)
				{	$default_character = 999999999999999999;	}
				$timeofday = intval(time() / 60);
				if (($allowpassreset) && ($emailcheck))
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
					$sql = "insert into $dblog_l2jdb.knightdrop (name, lastaction, token, mapaccess, recipeaccess, gdaccess, characcess, lastheard, access_level, email, emailcheck, request_key, password) values ('$usrname', 0, 0, '$default_maps', '$default_recipe', 0, '$default_character', '$timeofday', 0, '$email1', 1, '$remind_key', '$enc_password')";	
				}
				else
				{	$sql = "insert into $dblog_l2jdb.knightdrop (name, lastaction, token, mapaccess, recipeaccess, gdaccess, characcess, lastheard, access_level, email) values ('$usrname', 0, 0, '$default_maps', '$default_recipe', 0, '$default_character', '$timeofday', 0, '$email1')";	}
				$result_i = mysql_query($sql,$con2);

				if (!$result_i)
				{	echo "<h2 class=\"dropmain\">Error - Can't create user tracking!<br>" . mysql_error() . "</h2>";	}
				else
				{	if (($allowpassreset) && ($emailcheck))
					{	
						echo "<h2 class=\"dropmain\">You are being sent an e-mail to activate your account.<br><br>Please Note<br>Unconfirmed accounts and those with no characters, may be removed without notice.<br><br>If you do not receive the e-mail, please contact an admin.</h2>";	
						$sourceurl = preg_replace('/register.php/','registerreset.php',$sourceurl);
						$url = $sourceurl . "&user=" . $usrname . "&key=" . $remind_key;
						$url = preg_replace('/registerreset.php&/','registerreset.php?',$url);
						if ($phpsmtp)
						{	$new_line = "<br>";	}
						else
						{	$new_line = "\n";	}
						$body = "Dear " . $usrname . ", $new_line $new_line Congratulations on registering your account.  If you did not request this, please ignore this e-mail.  If you did request this account, please follow the link below to activate it. $new_line $new_line ";
						if ($phpsmtp)
						{	$body = $body . "<a href=\"$url\">" . $url . "</a>";	}
						else
						{	$body = $body . $url;	}
						$body = $body . " $new_line $new_line Enjoy your game! $new_line";
						$headers = 'From: ' . $e_mail_from . "\r\n" . 'Return-path: ' . $e_mail_from . 'Reply-To: ' . $e_mail_from . "\r\n";
						if ($phpsmtp)
						{
							authSendEmail($email1, "L2J Password Reset", $body, $smtpserver, $smtpport, $smtptimeout, $smtpuser, $smtppassword, $smtplocalhost, $e_mail_from, $smtp_debug);
							echo("<h2 class=\"dropmain\">Reminder sent.</h2>");	
						}
						else
						{
							if (mail($email1, "L2J Password Reset", $body, $headers)) 
							{	echo("<h2 class=\"dropmain\">Reminder sent.</h2>");	}
							else
							{	echo("<h2 class=\"dropmain\">Error - Failed to send e-mail.</h2>");	}
						}
					}
					else
					{	echo "<h2 class=\"dropmain\">Congratulations!<br><br>Now you can login to the game<br>with your new account.<br><br>Please Note<br>Accounts with no characters are liable to be removed</h2>";	}
				}
			}
		}
		else
		{
			echo "<h2 class=\"dropmain\">$lang_pass_userexist</h2>";
			$usrname = "";
		}
	}
}
else
{
	echo "<p class=\"dropmain\">&nbsp;</p><p class=\"dropmain\">&nbsp;</p>";
}

if (!$usrname)
{	echo "
		<p class=\"dropmain\">&nbsp;</p>
		<h2 class=\"dropmain\">$lang_pguandp</h2>
		<center><hr width=\"30%\"></center>
		<form action=\"register.php\" method=\"post\">
		<center><table class=\"blanktab\"><input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<tr><td class=\"noborderback\"><p class=\"heading2\">$lang_name - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"usrname\" type=\"text\" value=\"\" maxlength=\"$max_acc_length\"></td></tr>
		<tr><td class=\"noborderback\"><p class=\"heading2\">$lang_password - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"psswd1\" type=\"password\" value=\"\" maxlength=\"$max_pass_length\"></td></tr>
		<tr><td class=\"noborderback\"><p class=\"heading2\">$lang_confirm - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"psswd2\" type=\"password\" value=\"\" maxlength=\"$max_pass_length\"></td></tr>
		<tr><td class=\"noborderback\"><p class=\"heading2\"><hr></p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><hr></td></tr>";
		if (($allowpassreset) && ($emailcheck))
		{	echo "<tr><td class=\"noborderback\" colspan=\"3\"><p class=\"heading2\">&nbsp;<br>An activation e-mail will be sent to your e-mail address.<br>&nbsp;</p></td></tr>";	}
		echo "<tr><td class=\"noborderback\"><p class=\"heading2\">E-mail - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"email1\" type=\"text\" value=\"\" maxlength=\"50\"></td></tr>
		<tr><td class=\"noborderback\"><p class=\"heading2\">Confirm - </p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name=\"email2\" type=\"text\" value=\"\" maxlength=\"50\"></td></tr>
		</table><p>&nbsp;</p><input value=\"$lang_createacc\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></center>
		</form>
		";
}
echo "	<p class=\"dropmain\">&nbsp;</p>
	<center><img src=\"" . $images_dir . "bg_foot.gif\"></center>";
wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>