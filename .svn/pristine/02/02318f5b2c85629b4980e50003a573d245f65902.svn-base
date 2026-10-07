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

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
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
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Could not change to L2J database: ' . mysql_error());
	}
	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_sevens\" type=\"submit\" class=\"bigbut2\"></form></td>";
	if ($top_ten)
	{	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_topten\" type=\"submit\" class=\"bigbut2\"></form></td>";	}
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"trusted.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_trustedp\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"reference.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_classtree\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"castles.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_caststat\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"clanhall.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Clan Halls\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "</tr></table>";
		
	echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">$lang_tdwarves</h2>";
	$sql= "select * from knighttrust where race = 'Dwarf' order by level DESC";
	$result = mysql_query($sql,$con);
	if ($result)
	{
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
		echo "<tr><td class=\"drophead\"><strong class=\"dropmain\">$lang_name</strong></td><td class=\"drophead\"><strong class=\"dropmain\">Level</strong></td><td class=\"drophead\"><strong class=\"dropmain\">On Line?</strong></td><td class=\"drophead\"><strong class=\"dropmain\">Race</strong></td><td class=\"drophead\"><strong class=\"dropmain\">$lang_class</strong></td></tr>";
		$i=0;
		$count = mysql_num_rows($result);
		while ($i < $count) 
		{
			$c_name = mysql_result($result,$i,"char_name");
			$c_lvl = mysql_result($result,$i,"level");
			$c_race = mysql_result($result,$i,"race");
			$c_class = mysql_result($result,$i,"class");
			$sql= "select online, charId from characters where char_name = '$c_name'";
			$result2 = mysql_query($sql,$con);
			$on_line = "<font color=$red_code>?</font>";
			$charId = "";
			if ($result2)
			{
				$res2c = mysql_num_rows($result2);
				if ($res2c)
				{
					$on_l = mysql_result($result2,0,"online");
					$charId = mysql_result($result2,0,"charId");
					if ($on_l)
					{	$on_line = "<font color=$green_code>Yes</font>";	}
					else
					{	$on_line = "<font color=$red_code>No</font>";	}
				}
			}
			echo "<tr><td class=\"dropmain\">";
			if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
			{	echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charId\" class=\"dropmain\">$c_name</a>";	}
			else
			{	echo "$c_name";	}
			echo "</td><td class=\"dropmain\">$c_lvl</td><td class=\"dropmain\">$on_line</td><td class=\"dropmain\">$c_race</td><td class=\"dropmain\">$c_class</td></tr>";
			$i++;
		}
		echo "</table></center>";
	}

	echo "<h2 class=\"dropmain\">$lang_tothers</h2>";
	$sql= "select * from knighttrust where race <> 'Dwarf' order by level DESC";
	$result = mysql_query($sql,$con);
	if ($result)
	{
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
		echo "<tr><td class=\"drophead\"><strong class=\"dropmain\">$lang_name</strong></td><td class=\"drophead\"><strong class=\"dropmain\">Level</strong></td><td class=\"drophead\"><strong class=\"dropmain\">On Line?</strong></td><td class=\"drophead\"><strong class=\"dropmain\">Race</strong></td><td class=\"drophead\"><strong class=\"dropmain\">$lang_class</strong></td></tr>";
		$i=0;
		$count = mysql_num_rows($result);
		while ($i < $count) 
		{
			$c_name = mysql_result($result,$i,"char_name");
			$c_lvl = mysql_result($result,$i,"level");
			$c_race = mysql_result($result,$i,"race");
			$c_class = mysql_result($result,$i,"class");
			$sql= "select online, charId from characters where char_name = '$c_name'";
			$result2 = mysql_query($sql,$con);
			$on_line = "<font color=$red_code>?</font>";
			$charId = "";
			if ($result2)
			{
				$res2c = mysql_num_rows($result2);
				if ($res2c)
				{
					$on_l = mysql_result($result2,0,"online");
					$charId = mysql_result($result2,0,"charId");
					if ($on_l)
					{	$on_line = "<font color=$green_code>Yes</font>";	}
					else
					{	$on_line = "<font color=$red_code>No</font>";	}
				}
			}
			echo "<tr><td class=\"dropmain\">";
			if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
			{	echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charId\" class=\"dropmain\">$c_name</a>";	}
			else
			{	echo "$c_name";	}
			echo "</td><td class=\"dropmain\">$c_lvl</td><td class=\"dropmain\">$on_line</td><td class=\"dropmain\">$c_race</td><td class=\"dropmain\">$c_class</td></tr>";
			$i++;
		}
		echo "</table></center>";
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>