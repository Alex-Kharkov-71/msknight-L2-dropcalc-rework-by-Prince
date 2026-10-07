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
$accountname = input_check($_REQUEST['accountname'],1);
$action = input_check($_REQUEST['action'],0);
$newtime = input_check($_REQUEST['number'],0);
$newtime2 = input_check($_REQUEST['number2'],0);
$passwordchk = $_REQUEST['number'];

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_admin)		// If the access is not correct, then terminate the script.
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
		// Connect to the required databases.
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
		$con = mysql_connect($db_location,$db_user,$db_psswd);
		mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
		if (!$con)
		{
			echo "Could Not Connect";
			die('Could not connect: ' . mysql_error());
		}		
		if (!mysql_select_db("$db_l2jdb",$con))
		{
			die('Could not change to L2J database: ' . mysql_error());
		}

		// Display the header together with a link to return the user to the account.
		echo "<p class=\"dropmain\">&nbsp;</p><center><a href=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$accountname\" class=\"droph2\">Account - $accountname</a></center>\n";

		// If there is a change to the map time, then record it.
		if ($action == "maptime")
		{
			if (($newtime > 0) && ($newtime < 999999999999999999))
			{	$newtime = time() + ($newtime * 86400);	}	// If it is not 0 (off) or maximum, then take it as a number of days from this clock point.
			$result = mysql_query("update $dblog_l2jdb.knightdrop set mapaccess = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace map access time in knightdrop table: ' . mysql_error());	}
		}
		
		// If there is a change to the character access time, then record it.
		if ($action == "chartime")
		{
			if (($newtime > 0) && ($newtime < 999999999999999999))
			{	$newtime = time() + ($newtime * 86400);	}	// If it is not 0 (off) or maximum, then take it as a number of days from this clock point.
			$result = mysql_query("update $dblog_l2jdb.knightdrop set characcess = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace char access time in knightdrop table: ' . mysql_error());	}
		}

		// If there is a change to the recipe access time, then record it.
		if ($action == "rectime")
		{
			if (($newtime > 0) && ($newtime < 999999999999999999))
			{	$newtime = time() + ($newtime * 86400);	}	// If it is not 0 (off) or maximum, then take it as a number of days from this clock point.
			$result = mysql_query("update $dblog_l2jdb.knightdrop set recipeaccess = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace recipe access time in knightdrop table: ' . mysql_error());	}
		}

		// If there is a change to the GD access time, then record it.
		if ($action == "gdtime")
		{
			if (($newtime > 0) && ($newtime < 999999999999999999))
			{	$newtime = time() + ($newtime * 86400);	}	// If it is not 0 (off) or maximum, then take it as a number of days from this clock point.
			$result = mysql_query("update $dblog_l2jdb.knightdrop set gdaccess = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace GD access time in knightdrop table: ' . mysql_error());	}
		}

		// If there is a change to the boxing permissions, then record it.
		if ($action == "boxing")
		{
			$result = mysql_query("update $dblog_l2jdb.knightdrop set boxingok = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace boxing level time in knightdrop table: ' . mysql_error());	}
		}

		// If there is a change to the boxing permissions, then record it.
		if ($action == "verified")
		{
			$result = mysql_query("update $dblog_l2jdb.knightdrop set verified = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace verified status time in knightdrop table: ' . mysql_error());	}
		}

		// If there is a change to the warning permissions, then record it
		if ($action == "warning")
		{
			$result = mysql_query("update $dblog_l2jdb.knightdrop set warnlevel = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace warning level time in knightdrop table: ' . mysql_error());	}
		}

		// If there is a change to the account access level, then record it.
		if ($action == "newlvl")
		{
			$result = mysql_query("update $dblog_l2jdb.accounts set accessLevel = '$newtime' where login = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace access leve in accounts table: ' . mysql_error());	}
			// Ensure that the knightdrop table is updated to match so that the security options work.
			$result = mysql_query("update $dblog_l2jdb.knightdrop set access_level = '$newtime' where name = '$accountname'",$con2);
			if (!$result)
			{	die('Could not replace access level in knightdrop table: ' . mysql_error());	}
		}

		// If there is a change to the password, then check it and then record it.
		if ($action == "passwd")
		{
			$passwordchk2 = preg_replace('/[^a-z,^A-Z,^0-9]/', '', $newtime);
			if (($passwordchk != $newtime) || ($passwordchk2 != $newtime))
			{	echo "<h2 class=\"dropmain\">Illegal characters in password.<br>Stick to alphanumeric.</h2><p class=\"dropmain\">&nbsp;</p>\n";	}
			elseif (strlen($newtime) < 3)
			{	echo "<h2 class=\"dropmain\">Password needs to be minimum three characters.</h2><p class=\"dropmain\">&nbsp;</p>\n";		}
			elseif ($newtime != $newtime2)
			{	echo "<h2 class=\"dropmain\">Passwords don't match.</h2><p class=\"dropmain\">&nbsp;</p>\n";	}
			else
			{			// If it has passed the checks, then change the password.
				$enc_password = base64_encode(pack("H*", sha1(utf8_encode($newtime))));
				$result_i = mysql_query("update $dblog_l2jdb.accounts set password = '$enc_password' where login = '$accountname'",$con2);
				$result_i = mysql_query("update $dblog_l2jdb.knightdrop set emailcheck = 0 where name = '$accountname'",$con2);
				if (!$result_i)
				{	echo "<h2 class=\"dropmain\">Error - Can't amend user account!</h2><p class=\"dropmain\">&nbsp;</p>\n";	}
				else
				{	echo "<h2 class=\"dropmain\">Password change succeeded!</h2><p class=\"dropmain\">&nbsp;</p>\n";	}
			}
		}

		// Read in the accounts settings from the accounts tables.
		$result = mysql_query("select lastactive, accessLevel from $dblog_l2jdb.accounts where login = '$accountname'",$con2);
		if (!$result)
		{	die('Could not retrieve user account from accounts table: ' . mysql_error());	}
		// Read the extra account settings from the knightdrop table.
		$result2 = mysql_query("select mapaccess, recipeaccess, gdaccess, characcess, boxingok, warnlevel, verified, email from $dblog_l2jdb.knightdrop where name = '$accountname'",$con2);
		if (!$result)
		{	die('Could not retrieve user account from knightdrop table: ' . mysql_error());	}
		$email = mysql_result($result2,0,"email");
		echo "<center><p class=\"dropmainwhite\">E-mail - $email</p></center><p class=\"dropmain\">&nbsp;</p>\n";
		
		// Start the wider table.
		echo "<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">\n";

		// Give the table for change of map access.
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Map Viewing Expiry - <br>\n";
		$map_exp = mysql_result($result2,0,"mapaccess");
		if ( $map_exp < 1 )
		{ 	$map_exp_time = "<font color=$red_code>Dissabled</font>";	}
		elseif ( $map_exp == 999999999999999999 )
		{ 	$map_exp_time = "<font color=$green_code>Unlimited</font>";	}
		elseif ($map_exp < time())
		{ 	$map_exp_time = "<font color=$red_code>" . date('dS F Y \- h:iA T',$map_exp) . "</font>";	}
		else
		{ 	$map_exp_time = "<font color=$green_code>" . date('dS F Y \- h:iA T',$map_exp) . "</font>";	}
		echo "$map_exp_time</strong></p></td></tr><tr>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=maptime&number=0\"><input value=\" Disable Maps \" type=\"submit\" class=\"bigbut2\"></form></td>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=maptime&number=999999999999999999\"><input value=\" Unlimited Maps \" type=\"submit\" class=\"bigbut2\"></form></td>\n";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"maptime\"><input name=\"number\" type=\"text\" value=\"33\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"maptime\"><input name=\"number\" type=\"text\" value=\"370\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr></table></center>\n";
		echo "</td><td class=\"noborderback\">\n";

		// Give the table for the change of character changing times
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Character Changing Expiry - <br>\n";
		$char_exp = mysql_result($result2,0,"characcess");
		if ( $char_exp < 1 )
		{ 	$char_exp_time = "<font color=$red_code>Dissabled</font>";	}
		elseif ( $char_exp == 999999999999999999 )
		{ 	$char_exp_time = "<font color=$green_code>Unlimited</font>";	}
		elseif ($char_exp < time())
		{ 	$char_exp_time = "<font color=$red_code>" . date('dS F Y \- h:iA T',$char_exp) . "</font>";	}
		else
		{ 	$char_exp_time = "<font color=$green_code>" . date('dS F Y \- h:iA T',$char_exp) . "</font>";	}
		echo "$char_exp_time</strong></p></td></tr><tr>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=chartime&number=0\"><input value=\" Disable Change \" type=\"submit\" class=\"bigbut2\"></form></td>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=chartime&number=999999999999999999\"><input value=\" Unlimited Change \" type=\"submit\" class=\"bigbut2\"></form></td>\n";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"chartime\"><input name=\"number\" type=\"text\" value=\"33\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"chartime\"><input name=\"number\" type=\"text\" value=\"370\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr></table></center>\n";
		echo "</td><td class=\"noborderback\">\n";
		
		// Give the table for the change of recipe calculator access permissions.
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Recipe Calc Access - <br>";
		$rec_exp = mysql_result($result2,0,"recipeaccess");
		if ( $rec_exp < 1 )
		{ 	$rec_exp_time = "<font color=$red_code>Dissabled</font>";	}
		elseif ( $rec_exp == 999999999999999999 )
		{ 	$rec_exp_time = "<font color=$green_code>Unlimited</font>";	}
		elseif ($rec_exp < time())
		{ 	$rec_exp_time = "<font color=$red_code>" . date('dS F Y \- h:iA T',$rec_exp) . "</font>";	}
		else
		{ 	$rec_exp_time = "<font color=$green_code>" . date('dS F Y \- h:iA T',$rec_exp) . "</font>";	}
		echo "$rec_exp_time</strong></p></td></tr><tr>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=rectime&number=0\"><input value=\" Disable Calc \" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=rectime&number=999999999999999999\"><input value=\" Unlimited Calc \" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"rectime\"><input name=\"number\" type=\"text\" value=\"33\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"rectime\"><input name=\"number\" type=\"text\" value=\"370\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>";
		echo "</tr></table></center>\n";
		
		
		echo "</td></tr></table><p>&nbsp</p>\n";
		
		// Give the table for the change of boxing permission.
		echo "<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">\n";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Boxing O.K. - ";
		$boxing_ok = mysql_result($result2,0,"boxingok");
		if ( $boxing_ok < 1 )
		{ 	echo "<font color=$red_code> No</font>";	}
		else 
		{ 	echo "<font color=$green_code> Yes</font>";	}
		echo "</strong></p></td></tr><tr>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=boxing\">\n";
		echo "<select name=\"number\"><option value=1>Yes</option><option value=0>No</option></select>\n";
		echo "<input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></form></td>\n";
		echo "</tr></table></center>\n";
		echo "</td><td class=\"noborderback\">\n";
		
		// Give the table for the change of GD access permissions.
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">GD Access - <br>";
		$gd_exp = mysql_result($result2,0,"gdaccess");
		if ( $gd_exp < 1 )
		{ 	$gd_exp_time = "<font color=$red_code>Dissabled</font>";	}
		elseif ( $gd_exp == 999999999999999999 )
		{ 	$gd_exp_time = "<font color=$green_code>Unlimited</font>";	}
		elseif ($gd_exp < time())
		{ 	$gd_exp_time = "<font color=$red_code>" . date('dS F Y \- h:iA T',$gd_exp) . "</font>";	}
		else
		{ 	$gd_exp_time = "<font color=$green_code>" . date('dS F Y \- h:iA T',$gd_exp) . "</font>";	}
		echo "$gd_exp_time</strong></p></td></tr><tr>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=gdtime&number=0\"><input value=\" Disable GD \" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=gdtime&number=999999999999999999\"><input value=\" Unlimited GD \" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"gdtime\"><input name=\"number\" type=\"text\" value=\"33\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>";
		echo "</tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"gdtime\"><input name=\"number\" type=\"text\" value=\"370\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- Give number of days\" type=\"submit\" class=\"bigbut2\"></td></form>";
		echo "</tr></table></center>\n";
		echo "</td><td class=\"noborderback\">\n";
		
		// Give the table for the changing in the warning levels
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Warning Level - ";
		$warnlevel = mysql_result($result2,0,"warnlevel");
		if ( $warnlevel < 1 )
		{ 	echo "<font color=$green_code> $warnlevel</font>";	}
		else 
		{ 	echo "<font color=$red_code> $warnlevel</font>";	}
		echo "</strong></p></td></tr><tr>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=warning\">\n";
		echo "<select name=\"number\">";
		$i=0;
		while ($i < 10)		// Create the drop down menu for the options, inserting "selected" for the current option.
		{
			echo "<option ";
			if ($i == $warnlevel)
			{	echo "selected ";	}
			echo "value=$i>$i</option>";
			$i++;
		}
		echo "</select>\n";
		echo "<input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></form></td></tr></table></center>\n";
		echo "</td></tr></table><p>&nbsp</p>\n";
		
		// Give the table for the change of verification.
		echo "<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">\n";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Verified Admin - ";
		$verified = mysql_result($result2,0,"verified");
		if ( $verified < 1 )
		{ 	echo "<font color=$red_code> No</font>";	}
		else 
		{ 	echo "<font color=$green_code> Yes</font>";	}
		echo "</strong></p></td></tr><tr>\n";
		echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=verified\">\n";
		echo "<select name=\"number\"><option value=1>Yes</option><option value=0>No</option></select>\n";
		echo "<input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></form></td>\n";
		echo "</tr></table></center>\n";
		echo "</td><td class=\"noborderback\">\n";
		echo "</td></tr></table><p>&nbsp</p>\n";

		// Display the table for the alteration of the access level, along with a reminder of what the current levels are set to.
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Account level - \n";
		$acc_lvl = mysql_result($result,0,"accessLevel");
		echo "$acc_lvl</strong></p></td></tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"newlvl\"><input name=\"number\" type=\"text\" value=\"$acc_lvl\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- New Level\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$sec_inc_admin</p></td><td class=\"lefthead\"><p class=\"dropmain\">Admin Access</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$adjust_shop</p></td><td class=\"lefthead\"><p class=\"dropmain\">Adjust Shops</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$adjust_drops</p></td><td class=\"lefthead\"><p class=\"dropmain\">Adjust Drops</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$adjust_anounce</p></td><td class=\"lefthead\"><p class=\"dropmain\">Adjust Anounce</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$adjust_trust</p></td><td class=\"lefthead\"><p class=\"dropmain\">Adjust Trusts</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$sec_adj_notes</p></td><td class=\"lefthead\"><p class=\"dropmain\">Adjust Notes</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$sec_takeskill</p></td><td class=\"lefthead\"><p class=\"dropmain\">Take Skills</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$reboot_server</p></td><td class=\"lefthead\"><p class=\"dropmain\">Reboot Server</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$sec_giveandtake</p></td><td class=\"lefthead\"><p class=\"dropmain\">Give/Take Items</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$kick_player</p></td><td class=\"lefthead\"><p class=\"dropmain\">Kick Player</p></td>\n";
		echo "</tr><tr><td class=\"lefthead\"><p class=\"dropmain\">$sec_inc_gmlevel</p></td><td class=\"lefthead\"><p class=\"dropmain\">GM Access</p></td>\n";
		echo "</tr></table></center>\n";
		echo "<p>&nbsp</p>\n";
		
		// Display the button to change the accounts password
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Change Password</strong></p></td></tr><tr>\n";
		echo "<form method=\"post\" action=\"acc-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accountname\" type=\"hidden\" value=\"$accountname\"><input name=\"action\" type=\"hidden\" value=\"passwd\"><p class=\"dropmain\">New Password ...</p><input name=\"number\" type=\"password\" value=\"\" maxlength=\"40\"><p class=\"dropmain\">Verify ...</p><input name=\"number2\" type=\"password\" value=\"\" maxlength=\"40\"></td><td class=\"blanktab\"><input value=\"Change Password\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr></table></center>\n";
		echo "<p>&nbsp</p>\n";
		
		// Display the button to change the accounts password
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Account/Character Import</strong></p></td></tr><tr>\n";
		echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"import.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&account=$accountname\"><td class=\"blanktab\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"500000\"><input name=\"file\" type=\"file\"></td><td class=\"blanktab\"><input value=\"Import File\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr></table></center>\n";
		echo "<p>&nbsp</p>\n";
		
		// Display the button to trigger deletion of the account.
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>\n";
		echo "<form method=\"post\" action=\"javascript:popit('delete.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accname=$accountname','470','180');\"><td class=\"blanktab\"><input value=\"Delete Account\" type=\"submit\" class=\"bigbut2\"></td></form>\n";
		echo "</tr></table></center>\n";
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>