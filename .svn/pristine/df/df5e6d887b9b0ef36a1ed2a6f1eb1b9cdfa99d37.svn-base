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
$owner = input_check($_REQUEST['owner'],0);
$location = input_check($_REQUEST['location'],0);
$itemid = input_check($_REQUEST['itemid'],0);
$curenc = input_check($_REQUEST['curenc'],0);
$newenc = input_check($_REQUEST['newenc'],0);

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
	
	echo "<p class=\"popup\">Changing Enchantment</p>";

	if ($user_access_lvl < $sec_enchant)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		// If we have entered with an item, then attempt to give it.
		if ($newenc)
		{
			// Try and find the details on the object from each of the three content databases.
			// Looking simply for the name and crystal type.
			
			if ($newenc > 65535)
			{	$newenc = 65535;	}
			if ($newenc < 1)
			{	$newenc = 0;	}

			$sql = "update items set enchant_level = $newenc where owner_id = '$owner' and object_id = '$itemid' and loc = '$location' and enchant_level = '$curenc'";
			$result = mysql_query($sql,$con);

			echo "<p class=\"popup\">Enchantment changed to $newenc.</p>";
			$newenc = "";
		}

		if (!$newenc)
		{
			// Echo out the giving form.
			echo "	<form action=\"enchant.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"langval\" type=\"hidden\" value=\"$langval\">
			<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
			<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"location\" type=\"hidden\" value=\"$location\">
			<input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
			<input name=\"curenc\" type=\"hidden\" value=\"$curenc\">
			<input name=\"owner\" type=\"hidden\" value=\"$owner\">
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"popuptrans\">&nbsp;&nbsp;&nbsp;Enchantment&nbsp;-&nbsp;<input name=\"newenc\" maxlength=\"10\" size=\"5\" type=\"text\" value=\"1\" class=\"popup\"></td></tr>
			<tr><td><input value=\"Change Level\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\">
			</form>
			<form action=\"enchant.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"langval\" type=\"hidden\" value=\"$langval\">
			<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
			<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"location\" type=\"hidden\" value=\"$location\">
			<input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
			<input name=\"curenc\" type=\"hidden\" value=\"$curenc\">
			<input name=\"owner\" type=\"hidden\" value=\"$owner\">
			<input name=\"newenc\" type=\"hidden\" value=\"-1\">
			<input value=\"Set to ZERO\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\">
			</td>
			</tr></form></table>";
		}

	}
}

echo "</center></body></html>";

?>