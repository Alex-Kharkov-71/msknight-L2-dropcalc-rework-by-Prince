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

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_adj_notes)		// If the access level is not correct, then terminate the script.
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
		// Connect to the required databases
		$con = mysql_connect($db_location,$db_user,$db_psswd);
		mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
		if (!$con)
		{
			echo "Could Not Connect";
			die('Could not connect: ' . mysql_error());
		}		
		if (!mysql_select_db("$db_l2jdb",$con))
		{	die('Could not change to L2J database: ' . mysql_error());	}

		// Create the knight database, taking advantage of the normal configuration that grants the creation of databases and that their
		// creators have full permissions on the created DB.  This is where the notes will be stored.
		$result = mysql_query("create database if not exists $knight_db",$con);
		$result = mysql_query("CREATE TABLE $knight_db.accnotes (`charname` varchar(45) NOT NULL default '', `notenum` int(5) default NULL, `notemaker` varchar(50) default NULL, `note` varchar(300) default NULL,  PRIMARY KEY  (`charname`, `notenum`))",$con);

		// Display the title of the account name, comprising of a link to return to the account details.
		echo "<p class=\"dropmain\">&nbsp;</p><a href=\"a-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&account=$accountname\" class=\"dropmain\"><h2 class=\"dropmain\">Account - $accountname</h2></a><p class=\"dropmain\">&nbsp;</p>\n";

		// If the action is set to record a new note, then record it in the table.
		if (($action == "recnote") && ($newtime))
		{
			$newtime = preg_replace('/\'/','`',$newtime);
			$count = 0;
			$name_user = $username . " - " . date('dS F Y \- h:iA T');
			// Find the highest numbered note in the table against this account.
			$result = mysql_query("select notenum from $knight_db.accnotes where charname = '$accountname' order by notenum DESC limit 1",$con);
			$query_count = mysql_num_rows($result);
			if ($query_count)									// If a previous note exists, then extract its number.
			{	$count = mysql_result($result,0,"notenum");	}
			$count++;		// Increase the index of the note, and record it.
			$result = mysql_query("insert into $knight_db.accnotes (`charname`, `notenum`, `notemaker`, `note`) values('$accountname', '$count', '$name_user', '$newtime')",$con);
		}

		// If there are less than 99999 notes recorded against the account, then allow one more to be recorded.  Else sound a warning.
		// For any account to have ramped up that number of warnings then they should have been banned from the server long, long ago.
		if ($count < 99999)
		{
			echo "<center><p class=\"dropmainwhite\">Notes are NOT deletable via the dropcalc<br>Do not leave a note without good reason</p></center>\n";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Add a comment - </strong></p></td></tr><tr>\n";
			echo "<td class=\"blanktab\"><form method=\"post\" action=\"acc-notes.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$accountname&action=recnote\"><input name=\"number\" type=\"text\" value=\"\" maxlength=\"300\" size=\"100\"></td><td><input value=\" Submit \" type=\"submit\" class=\"bigbut2\"></form></td>\n";
			echo "</tr></table></center><p>&nbsp</p>\n";
		}
		else
		{	echo "<center><h2 class=\"dropmain\">Maximum Comments Recorded</h2></center>";	}

		// Recall any previous notes made against this account, and display them in order of the most recently recorded first.
		$count = 0;
		$result = mysql_query("select notemaker, note from $knight_db.accnotes where charname = '$accountname' order by notenum DESC",$con);
		$trip = 0;
		while ($r_array = mysql_fetch_assoc($result))
		{
			$recname = $r_array['notemaker'];
			$note = $r_array['note'];
			echo "<p class=\"dropmainwhite\"><strong><font color=$blue_code>$recname</font></strong><br>$note</p>\n";
			$trip = 1;
		}
		if ($trip == 0)
		{	echo "<center><h2 class=\"dropmain\">No comments recorded</h2></center>";	}
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>