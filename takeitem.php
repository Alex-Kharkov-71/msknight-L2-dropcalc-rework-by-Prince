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
$fromuser = input_check($_REQUEST['fromuser'],1);
$itemid = input_check($_REQUEST['itemid'],2);
$itemqty = input_check($_REQUEST['itemqty'],2);
$usern = input_check($_REQUEST['usern'],1);
$itemidgo = input_check($_REQUEST['itemidgo'],0);
$location = input_check($_REQUEST['location'],0);
$binloc = input_check($_REQUEST['binloc'],0);

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

	echo "<p class=\"popup\">Changing for $usern from $location</p>";

	if ($user_access_lvl < $sec_giveandtake)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		// If the request is for more than 1, then check to see if the item is stackable.
		if ($itemqty > 1)
		{
			$obj_stack = 0;
			$sql = "select name, consume_type, is_stackable from knightetcitem where item_id = $itemid";
			$result_i = mysql_query($sql,$con);

			if ($result_i)
			{
				$count_i = mysql_num_rows($result_i);
				if ($count_i)
				{
					$obj_name = mysql_result($result_i,0,"name");
					$obj_consume_type = mysql_result($result_i,0,"consume_type");
					$obj_stack = mysql_result($result_i,0,"is_stackable");		
					if (($obj_stack == true) || ($obj_consume_type == "asset"))
					{ $obj_stack = 1;	}
					echo "<p class=\"popup\">Item - $obj_name</p>";
				}
			}
	
			if (($itemqty > 1) && (!$obj_stack))
			{	
				echo "<p class=\"popup\">Non-stackable item!<br>1 or 0.</p>";
				$itemidgo = 0;
			}
		}
		// If we have entered with an item, then attempt to give it.
		if ($itemidgo)
		{
			// Try and find the details on the object from each of the three content databases.
			// Looking simply for the name and crystal type.
			$extra_comm = "";
			if ($location == "FREIGHT")
			{	$extra_comm = "AND loc_data = '$binloc'";	}
			if (!$itemqty)
			{	$sql = "delete from items where owner_id = '$fromuser' AND item_id = '$itemid' AND loc = '$location' $extra_comm limit 1";	}
			else
			{	$sql = "update items set count = $itemqty where owner_id = '$fromuser' AND item_id = '$itemid' AND loc = '$location' $extra_comm";	}
			$resultdis = mysql_query($sql,$con);

			if ($resultdis)
			{	echo "<p class=\"popup\">Change submitted</p>";	}
			else
			{	echo "<p class=\"popup\">!!! Change failed !!!</p>";	}
		}

		// Echo out the changing form.
		if ($itemqty)
		{
			echo "	<form action=\"takeitem.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"server_id\" type=\"hidden\" value=\"$server_id\">
			<input name=\"fromuser\" type=\"hidden\" value=\"$fromuser\">
			<input name=\"langval\" type=\"hidden\" value=\"$langval\">
			<input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
			<input name=\"usern\" type=\"hidden\" value=\"$usern\">
			<input name=\"location\" type=\"hidden\" value=\"$location\">
			<input name=\"binloc\" type=\"hidden\" value=\"$binloc\">
			<input name=\"itemidgo\" type=\"hidden\" value=\"1\">
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"popuptrans\">ID&nbsp;-&nbsp;$itemid</td>
			<td class=\"popuptrans\">&nbsp;&nbsp;&nbsp;Qty&nbsp;-&nbsp;<input name=\"itemqty\" maxlength=\"10\" size=\"12\" type=\"text\" value=\"$itemqty\" class=\"popup\"></td></tr>
			<tr><td colspan=\"2\"><input value=\"Change Item\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></td>
			</tr></table>
			</form>
			";
			echo "<br><form action=\"takeitem.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"server_id\" type=\"hidden\" value=\"$server_id\">
			<input name=\"fromuser\" type=\"hidden\" value=\"$fromuser\">
			<input name=\"langval\" type=\"hidden\" value=\"$langval\">
			<input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
			<input name=\"usern\" type=\"hidden\" value=\"$usern\">
			<input name=\"location\" type=\"hidden\" value=\"$location\">
			<input name=\"binloc\" type=\"hidden\" value=\"$binloc\">
			<input name=\"itemidgo\" type=\"hidden\" value=\"1\">
			<input name=\"itemqty\" type=\"hidden\" value=\"0\">
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr><td class=\"popuptrans\"><input value=\"DELETE Item\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></td>
			</tr></table>
			</form>
			";
		}
	}
}

echo "</center></body></html>";

?>
