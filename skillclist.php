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
$action = input_check($_REQUEST['action'],0);
$skillid = input_check($_REQUEST['skillid'],0);
$skillident = input_check(preg_replace('/%%%@/','&',preg_replace('/@@/','"',$_REQUEST['skillident'])),0);
$skillfrom = input_check(preg_replace('/%%%@/','&',preg_replace('/@@/','"',$_REQUEST['skillfrom'])),0);
$skillto = input_check(preg_replace('/%%%@/','&',preg_replace('/@@/','"',$_REQUEST['skillto'])),0);


$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_admin)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
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
		
		if ($action == "delete")
		{	$result = mysql_query("delete from knightskillmod where id = '$skillid' and textident = '$skillident' and textfrom = '$skillfrom' and textto = '$skillto' limit 1",$con);	}
		if ($action == "add")
		{	
			$skillid = intval($skillid);
			if ($skillid < 1)
			{	echo "<h2>Error - Skill ID resolved to 0 or non number.</h2>";	}
			else
			{	$result = mysql_query("insert ignore into knightskillmod (id, textident, textfrom, textto) values ('$skillid', '$skillident', '$skillfrom', '$skillto')",$con);	}
		}
		
		echo "<h2 class=\"dropmain\">Reminder - Don't use single quotes.</h2>";

		// Display the form to post in changes to the 
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><form method=\"post\" action=\"skillclist.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$mobid\"><input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><td class=\"drophead\"><input value=\"Refresh\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "</tr></table></center><br>";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\"><td class=\"lefthead\">ID</td><td class=\"lefthead\">Identifyer</td><td class=\"lefthead\">From</td><td class=\"lefthead\">To</td><td class=\"lefthead\">&nbsp</td></tr>";
		echo "<tr><form method=\"post\" action=\"skillclist.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$mobid\"><input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"add\">
				<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"skillid\" type=\"text\" value=\"0\" maxlength=\"6\" size=\"6\"></p></td>
				<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"skillident\" type=\"text\" value=\"\" maxlength=\"100\" size=\"30\"></p></td>
				<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"skillfrom\" type=\"text\" value=\"\" maxlength=\"100\" size=\"30\"></p></td>
				<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"skillto\" type=\"text\" value=\"\" maxlength=\"100\" size=\"30\"></p></td>
				<td class=\"drophead\"><input value=\"<-ADD\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "</tr></table></center><br>";
		
		// Display the current table contents in a table.
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\"><td class=\"lefthead\">ID</td><td class=\"lefthead\">Identifyer</td><td class=\"lefthead\">From</td><td class=\"lefthead\">To</td><td class=\"lefthead\">Del</td></tr>";

		$result = mysql_query("select id, textident, textfrom, textto from knightskillmod order by id",$con);
		while ($r_array = mysql_fetch_assoc($result))
		{
			$s_id = $r_array['id'];
			$s_ident = $r_array['textident'];
			$s_from = $r_array['textfrom'];
			$s_to = $r_array['textto'];
//			$s_ident = preg_replace('/"/','\"',$s_ident);
//			$s_from = preg_replace('/"/','\"',$s_from);
//			$s_to = preg_replace('/"/','\"',$s_to);
			echo "<tr><td class=\"dropmain\">$s_id</td><td class=\"dropmain\">$s_ident</td><td class=\"dropmain\">$s_from</td><td class=\"dropmain\">$s_to</td>";
			$s_ident = preg_replace('/"/','@@',$s_ident);
			$s_from = preg_replace('/"/','@@',$s_from);
			$s_to = preg_replace('/"/','@@',$s_to);
			$s_ident = preg_replace('/&/','%%%@',$s_ident);
			$s_from = preg_replace('/&/','%%%@',$s_from);
			$s_to = preg_replace('/&/','%%%@',$s_to);
			echo "<td class=\"dropmain\"><a href=\"skillclist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=delete&skillid=$s_id&skillto=$s_to&skillfrom=$s_from&skillident=$s_ident\"><font color=$red_code>DEL</font></a></td></tr>";
		}
		echo "</table></center>";
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>