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
$to_name = input_check($_REQUEST['toname'],1);
$usern = input_check($_REQUEST['usern'],1);


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
	{
		die('Could not change to L2J database: ' . mysql_error());
	}

	if ($user_access_lvl < $sec_inc_admin)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		echo "<p>Changing name for - $usern</p>";
		if (strlen($to_name) == 0)
		{
			echo "<p class=\"dropmain\"><center><form method=\"post\" action=\"c-namechange.php\"><input name=\"toname\" type=\"text\" maxlength=\"35\" size=\"35\"><br><input value=\" Change Name \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"usern\" value=\"$usern\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"></form></center></p>";
		}
		else
		{
			$to_name = preg_replace('/[^A-Z,^a-z,^0-9,^À-ß,^à-ÿ]/','',$to_name);
			$sql = "select COUNT(*) from characters where char_name = '$to_name'";
			$result = mysql_query($sql,$con);
			$count = 0;
			while ($r_array = mysql_fetch_assoc($result))
			{	$count = $r_array['COUNT(*)'];	}
			if ($count > 0)
			{	
				echo "<p>Name $to_name already exists.</p>";	
				echo "<p class=\"dropmain\"><center><form method=\"post\" action=\"c-namechange.php\"><input name=\"toname\" type=\"text\" maxlength=\"35\" size=\"35\"><br><input value=\" Change Name \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"usern\" value=\"$usern\" type=\"hidden\"></form></center></p>";
			}
			else
			{
				$sql = "update characters set char_name='$to_name' where char_name = '$usern'";
				$result = mysql_query($sql,$con);
				echo "<p>Change Name Submitted.</p>";
			}
		}
	}
}

echo "</center></body></html>";

?>