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
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$newpass1 = input_check($_REQUEST['newpass1'],0);
$newpass2 = input_check($_REQUEST['newpass2'],0);
$newemail1 = preg_replace('/[&%$\/\\\|<>#£]/','',$_REQUEST['newemail1']);
$newemail2 = preg_replace('/[&%$\/\\\|<>#£]/','',$_REQUEST['newemail2']);
$passwordchk = $_REQUEST['newpass1'];

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

echo "
<html>
<head>
<title>Michelle's Generic Drop Calc</title>
	<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
</head>

<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
";

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
		echo "Could Not Connect";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{
		die('Wrap_start could not change to logserver database: ' . mysql_error());
	}

	if ($newpass1)
	{
		$passwordchk2 = preg_replace('/[^a-z,^A-Z,^0-9]/', '', $newpass1);
		if (($passwordchk != $newpass1) || ($passwordchk2 != $newpass1))
		{
			echo "<center><p class=\"popup\">$lang_passillchar</p></center>";
			$newpass1 = "";
		}
		elseif (strlen($newpass1) < 3)
		{
			echo "<center><p class=\"popup\">$lang_pass_minthree</p></center>";
			$newpass1 = "";
		}
		elseif ($newpass1 != $newpass2)
		{
			echo "<center><p class=\"popup\">$lang_pass_nomatch</p></center>";
			$newpass1 = "";
		}
		else
		{
			$enc_password = base64_encode(pack("H*", sha1(utf8_encode($newpass1))));
			$sql = "update $dblog_l2jdb.accounts set password = '$enc_password' where login = '$username'";	
			$result_i = mysql_query($sql,$con2);
			if (!$result_i)
			{	echo "<center><p class=\"popup\">Error - Can't amend user account!</p></center>";	}
			else
			{	echo "<center><p class=\"popup\">$lang_pass_suceed</p></center>";	}
		}
	}
	if ($newemail1)
	{
		if ($newemail1 != $newemail2)
		{
			echo "<center><p class=\"popup\">E-mail addresses don't match.</p></center>";
			$newpass1 = "";
		}
		elseif (strlen($newemail1) < 1)
		{
			echo "<center><p class=\"popup\">E-mail was blank</p></center>";
			$newpass1 = "";
		}
		else
		{
			$sql = "select COUNT(*) from $dblog_l2jdb.knightdrop where email = '$newemail1'";
			$result_i = mysql_query($sql,$con2);
			$count = mysql_result($result_i,0,"COUNT(*)");
			if ($count > 0)
			{
				echo "<center><p class=\"popup\">E-mail addresses already registered.</p></center>";
				$newpass1 = "";
			}
			else
			{
				$sql = "update $dblog_l2jdb.knightdrop set email = '$newemail1' where name = '$username'";	
				$result_i = mysql_query($sql,$con2);
				if (!$result_i)
				{	echo "<center><p class=\"popup\">Error - Can't amend user account!</p></center>";	}
				else
				{	
					echo "<center><p class=\"popup\">E-mail change succeeded</p></center>";	
					$newpass1 = " ";
				}
			}
		}
	}
$newpass1 = "";
	if (!$newpass1)
	{
		echo "<h2 class=\"popmain\">Password</h2><center><form method=\"post\" action=\"cpasswd.php\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>
		<td class=\"blanktab\" valign=\"top\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><p class=\"dropmain\">$lang_password</p></td>
		<td class=\"blanktab\"><input name=\"newpass1\" type=\"password\" value=\"\" maxlength=\"40\"></td>
		<td class=\"blanktab\" rowspan=\"2\"><input value=\"$lang_changep\" type=\"submit\" class=\"bigbut2\"></td>
		</tr><tr>
		<td class=\"blanktab\"><p class=\"dropmain\">$lang_confirm</p></td>
		<td class=\"blanktab\"><input name=\"newpass2\" type=\"password\" value=\"\" maxlength=\"40\"></td>
		</tr></table></form></center>";
		echo "<h2 class=\"popmain\">E-mail</h2><center><form method=\"post\" action=\"cpasswd.php\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>
		<td class=\"blanktab\" valign=\"top\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><p class=\"dropmain\">E-Mail</p></td>
		<td class=\"blanktab\"><input name=\"newemail1\" type=\"text\" value=\"\" maxlength=\"50\"></td>
		<td class=\"blanktab\" rowspan=\"2\"><input value=\"Change Email\" type=\"submit\" class=\"bigbut2\"></td>
		</tr><tr>
		<td class=\"blanktab\"><p class=\"dropmain\">$lang_confirm</p></td>
		<td class=\"blanktab\"><input name=\"newemail2\" type=\"text\" value=\"\" maxlength=\"50\"></td>
		</tr></table></form></center>";
	}
	
}

echo "</body>
</html>";

?>