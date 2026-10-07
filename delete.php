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
$password = input_check($_REQUEST['password'],0);
$character = input_check($_REQUEST['charname'],1);
$dormant = input_check($_REQUEST['dormant'],0);
$account = input_check($_REQUEST['accname'],1);
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

	if ($account)
	{	echo "<h2 class=\"dropmain\">Deleting Account - $account</h2>";	}
	elseif ($dormant)
	{	
		if ($dormant == 'six')
		{	$borderline = (time() - 16070400) * 1000;	}
		elseif ($dormant == 'three')
		{	$borderline = (time() - 8035200) * 1000;	}
		else
		{	$borderline = (time() - 32140800) * 1000;	}
		$timeline = date('dS F Y',($borderline / 1000));
		echo "<h2 class=\"dropmain\">Deleting dormants before $timeline</h2>";	
	}
	else 
	{	echo "<h2 class=\"dropmain\">Deleting Character - $character</h2>";	}

	if ($user_access_lvl < $sec_inc_admin )
	{
		echo "<h2 class=\"dropmain\">You don't have sufficient access.</h2>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		// If we have entered with an item, then attempt to give it.
		
		if ($go)
		{
			if ($password != $del_accchar )
			{
				echo "<h2 class=\"dropmain\">Wrong password supplied.</h2>";
				wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
				return 0;
			}
			if (!$sixmonth)
			{
				$online = 0;  //  Check for any accounts on line - in the case of dormant account, no bother checking.
				if ($account)
				{	$sql="select level from characters where online = '1' and account_name = '$account'";	}
				else
				{	$sql="select level from characters where online = '1' and char_name = '$character'";	}
				$result = mysql_query($sql,$con);
				$count = mysql_num_rows($result);
				if ($count)
				{	
					echo "<p class=\"popup\">Linked characters are currently on-line</p>";	
					echo "</center></body></html>";
					return 0;
				}
			}

			if ($account)
			{	delete_acc($account, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $knight_db);	}
			elseif (!$dormant)
			{	delete_char($character, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb);	}	
			else
			{
				if ($dormant == 'six')
				{	$borderline = (time() - 16070400) * 1000;	}
				elseif ($dormant == 'three')
				{	$borderline = (time() - 8035200) * 1000;	}
				else
				{	$borderline = (time() - 32140800) * 1000;	}
				$sql="select login from $dblog_l2jdb.accounts where lastactive < '$borderline' and accessLevel < '$sec_inc_gmlevel'";
				$result = mysql_query($sql,$con2);
				if ($result)
				{
					$count = mysql_num_rows($result);
					$i=0;
					while ($i < $count)
					{	
						$accountname = mysql_result($result,$i,"login");
						delete_acc($accountname, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $knight_db);		
						$i++;
					}
					echo "<h2 class=\"popmain\">$count dormant accounts found.</h2>";
				}
				else
				{	echo "<h2 class=\"popmain\">No dormant accounts found.</h2>";	}
			}
			echo "<p class=\"popup\">&nbsp;</p><h2 class=\"popmain\">Delete Completed.</h2>";


		}
		else
		{
			// Echo out the password form.
			echo "<p class=\"popup\">&nbsp;</p><center><form action=\"delete.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"accname\" type=\"hidden\" value=\"$account\"><input name=\"go\" type=\"hidden\" value=\"go\"><input name=\"dormant\" type=\"hidden\" value=\"$dormant\"><input name=\"charname\" type=\"hidden\" value=\"$character\"><input name=\"password\" type=\"text\" value=\"\" maxlength=\"20\" size=\"12\" class=\"popupcentre\"><input value=\" <- Password to delete \" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form>
			";
		}
	}
}

echo "</center></body></html>";

?>