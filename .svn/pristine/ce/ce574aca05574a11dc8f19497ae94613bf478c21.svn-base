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
$ip = input_check($_REQUEST['ipaddr'],0);
$action = input_check($_REQUEST['action'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
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
		echo "<p class=\"dropmain\">Could Not Connect</p>";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{
		die('Wrap_start could not change to logserver database: ' . mysql_error());
	}	

	if ($user_access_lvl < $sec_inc_admin)
	{
		echo "<p class=\"dropmain\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		$sql = "CREATE TABLE IF NOT EXISTS $dblog_l2jdb.knightipok (  `ip_addr` varchar(45) default NULL,  PRIMARY KEY  (`ip_addr`))";
		$result = mysql_query($sql,$con2);
			
		if ($action == "delete")
		{
			$sql = "delete from $dblog_l2jdb.knightipok where ip_addr = '$ip'";
			$result = mysql_query($sql,$con2);
		}
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
		echo "<td class=\"lefthead\"><p class=\"dropmain\">Country</p></td>";
		echo "<td class=\"drophead\"><p class=\"dropmain\">IP</p></td>";
		echo "<td class=\"drophead\"><p class=\"dropmain\">Delete</p></td></tr>";
		$sql = "select ip_addr from $dblog_l2jdb.knightipok";
		$result = mysql_query($sql,$con2);
		$count = mysql_num_rows($result);
		$i=0;
		while ($i < $count)
		{
			$ip_addr = mysql_result($result,$i,"ip_addr");
			$ip_num = iptonum($ip_addr);
			$ip_country = "&nbsp;";
			$sql = "SELECT ci FROM $dblog_l2jdb.ip WHERE $ip_num BETWEEN start AND end";
			$ip_result = mysql_query($sql,$con2);
			if ($ip_result)
			{	$ip_result_count = mysql_num_rows($ip_result);	}
			else
			{	$ip_result_count = 0;	}
			if ($ip_result_count)
			{
				$ip_c_num = mysql_result($ip_result,0,"ci");
				$sql = "SELECT cn FROM $dblog_l2jdb.cc WHERE ci = '$ip_c_num'";
				$ip_result = mysql_query($sql,$con2);
				$ip_country = mysql_result($ip_result,0,"cn");				
			}
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$ip_country</p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$ip_addr</p></td>";
			echo "<td class=\"dropmain\"><p class=\"left\"><a href=\"ip-table.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&ipaddr=$ip_addr&action=delete\" class=\"dropmain\"><font color=$red_code>DEL</font></a></p></td></tr>";
			$i++;
		}
		echo "</table></center>";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>