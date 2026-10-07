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
$itemid = input_check($_REQUEST['itemid'],0);
$itemqty = input_check($_REQUEST['itemqty'],0);
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
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	if (!$con2)
	{
		echo "Could Not Connect";
		die('Evaluser could not connect to logondb: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{
		die('Evaluser could not change to logondb: ' . mysql_error());
	}
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{
		echo "<p class=\"popup\">Could Not Connect</p>";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Could not change to L2J database: ' . mysql_error());
	}

	echo "<p class=\"popup\">Item history</p>";

	if ($user_access_lvl < $sec_giveandtake)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		if ($itemid >= 0)
		{
			// If the request is for more than 1, then check to see if the item is stackable.
			$sql = "select * from $knight_db.itemlog where object_id = $itemid order by index_id desc";
			$result = mysql_query($sql,$con);
			$count = 0;
			while ($r_array = mysql_fetch_assoc($result))
			{	
				$timestamp = $r_array[timestamp];	
				$own_id = $r_array[owner_id];	
				$ench = $r_array[enchant_level];
				$outdate = date("Y M d H:i", $timestamp);
				$sql = "select char_name from characters where charId = $own_id";
				$result2 = mysql_query($sql,$con);
				$own_name = "unknown";
				while ($r_array = mysql_fetch_assoc($result2))
				{	$own_name = $r_array[char_name];	}
				if ($count == 0)
				{	echo "<table cellpadding=\"2\"><tr><td>Time</td><td>Owner</td><td>Ench</td></tr>";	}
				echo "<tr><td><p>$outdate</p></td><td><p>$own_name</p></td><td><p>$ench</p></td></tr>";
				$count++;
			}
			if ($count == 0)
			{	echo "<p>No history for this item</p>";	}
			else
			{	echo "</table>";	}
		}
		elseif ($itemid == -1)
		{
			$sql = "select * from $knight_db.itemlog where owner_id = $fromuser order by timestamp desc";
			$result = mysql_query($sql,$con);
			$count = 0;
			while ($r_array = mysql_fetch_assoc($result))
			{	
				$timestamp = $r_array[timestamp];	
				$own_id = $r_array[owner_id];	
				$ench = $r_array[enchant_level];
				$obj_id = $r_array[object_id];
				$outdate = date("Y M d H:i", $timestamp);
				$sql = "select item_id from items where object_id = $obj_id";
				$result2 = mysql_query($sql,$con);
				$item_name = "unknown";
				while ($r_array = mysql_fetch_assoc($result2))
				{	
					$name_id = $r_array[item_id];
					$sql = "select name from weapon where item_id = $name_id";
					$result3 = mysql_query($sql,$con);
					while ($r_array = mysql_fetch_assoc($result3))
					{	$item_name = $r_array[name];	}
					$sql = "select name from armor where item_id = $name_id";
					$result3 = mysql_query($sql,$con);
					while ($r_array = mysql_fetch_assoc($result3))
					{	$item_name = $r_array[name];	}
				}
				if ($count == 0)
				{	echo "<table cellpadding=\"2\"><tr><td>Time</td><td>Item</td><td>Ench</td></tr>";	}
				echo "<tr><td><p>$outdate</p></td><td><p>$item_name</p></td><td><p>$ench</p></td></tr>";
				$count++;
			}
			if ($count == 0)
			{	echo "<p>No history for this character</p>";	}
			else
			{	echo "</table>";	}
		}
		elseif ($itemid == -2)
		{
			$sql = "select account_name from characters where charId = $fromuser";
			$result = mysql_query($sql,$con);
			while ($r_array = mysql_fetch_assoc($result))
			{	$fromuser = $r_array[account_name];	}
			$sql = "select * from $knight_db.itemlog where owner_id in (select charId from characters where account_name = '$fromuser') order by timestamp desc";
			$result = mysql_query($sql,$con);
			$count = 0;
			while ($r_array = mysql_fetch_assoc($result))
			{	
				$timestamp = $r_array[timestamp];	
				$own_id = $r_array[owner_id];	
				$ench = $r_array[enchant_level];
				$obj_id = $r_array[object_id];
				$outdate = date("Y M d H:i", $timestamp);
				$sql = "select char_name from characters where charId = $own_id";
				$result2 = mysql_query($sql,$con);
				$own_name = "unknown";
				while ($r_array = mysql_fetch_assoc($result2))
				{	$own_name = $r_array[char_name];	}
				$sql = "select item_id from items where object_id = $obj_id";
				$result2 = mysql_query($sql,$con);
				
				$item_name = "unknown";
				while ($r_array = mysql_fetch_assoc($result2))
				{	
					$name_id = $r_array[item_id];
					$sql = "select name from weapon where item_id = $name_id";
					$result3 = mysql_query($sql,$con);
					while ($r_array = mysql_fetch_assoc($result3))
					{	$item_name = $r_array[name];	}
					$sql = "select name from armor where item_id = $name_id";
					$result3 = mysql_query($sql,$con);
					while ($r_array = mysql_fetch_assoc($result3))
					{	$item_name = $r_array[name];	}
				}
				if ($count == 0)
				{	echo "<table cellpadding=\"2\"><tr><td>Time</td><td>Item</td><td>Owner</td><td>Ench</td></tr>";	}
				echo "<tr><td><p>$outdate</p></td><td><p>$item_name</p></td><td><p>$own_name</p></td><td><p>$ench</p></td></tr>";
				$count++;
			}
			if ($count == 0)
			{	echo "<p>No history for this account</p>";	}
			else
			{	echo "</table>";	}
		}
		elseif ($itemid == -3)
		{
			$sql = "select account_name from characters where charId = $fromuser";
			$result = mysql_query($sql,$con);
			while ($r_array = mysql_fetch_assoc($result))
			{	$fromuser = $r_array[account_name];	}
			$acclist = "(";
			$sql = "select login from $dblog_l2jdb.accounts where lastIP = (select lastIP from $dblog_l2jdb.accounts where login = '$fromuser')";
			$result = mysql_query($sql,$con2);
			$count = 0;
			while ($r_array = mysql_fetch_assoc($result))
			{	
				if ($count > 0)
				{	$acclist = $acclist . ', ';	}
				$acclist = $acclist . "'" . $r_array[login]. "'";
				$count++;
				}
			$acclist = $acclist . ")";
			$sql = "select * from $knight_db.itemlog where owner_id in (select charId from characters where account_name in $acclist) order by timestamp desc";
			$result = mysql_query($sql,$con);
			$count = 0;
			while ($r_array = mysql_fetch_assoc($result))
			{	
				$timestamp = $r_array[timestamp];	
				$own_id = $r_array[owner_id];	
				$ench = $r_array[enchant_level];
				$obj_id = $r_array[object_id];
				$outdate = date("Y M d H:i", $timestamp);
				$sql = "select account_name, char_name from characters where charId = $own_id";
				$result2 = mysql_query($sql,$con);
				$own_name = "unknown";
				$acc_name = "unknown";
				while ($r_array = mysql_fetch_assoc($result2))
				{	
					$own_name = $r_array[char_name];
					$acc_name = $r_array[account_name];
				}
				$sql = "select item_id from items where object_id = $obj_id";
				$result2 = mysql_query($sql,$con);
				
				$item_name = "unknown";
				while ($r_array = mysql_fetch_assoc($result2))
				{	
					$name_id = $r_array[item_id];
					$sql = "select name from weapon where item_id = $name_id";
					$result3 = mysql_query($sql,$con);
					while ($r_array = mysql_fetch_assoc($result3))
					{	$item_name = $r_array[name];	}
					$sql = "select name from armor where item_id = $name_id";
					$result3 = mysql_query($sql,$con);
					while ($r_array = mysql_fetch_assoc($result3))
					{	$item_name = $r_array[name];	}
				}
				if ($count == 0)
				{	echo "<table cellpadding=\"2\"><tr><td>Time</td><td>Item</td><td>Owner</td><td>Account</td><td>Ench</td></tr>";	}
				echo "<tr><td><p>$outdate</p></td><td><p>$item_name</p></td><td><p>$own_name</p></td><td><p>$acc_name</p></td><td><p>$ench</p></td></tr>";
				$count++;
			}
			if ($count == 0)
			{	echo "<p>No history for this IP</p>";	}
			else
			{	echo "</table>";	}
		}
		else
		{
			$sql = "select * from $knight_db.itemloghist order by timestamp desc";
			$result = mysql_query($sql,$con);
			$count = 0;
			while ($r_array = mysql_fetch_assoc($result))
			{	
				$timestamp = $r_array[timestamp];	
				$owner = $r_array[owner];	
				$ench = $r_array[enchant_level];
				$obj_name = $r_array[objectname];
				$outdate = date("Y M d H:i", $timestamp);
	
				if ($count == 0)
				{	echo "<table cellpadding=\"2\"><tr><td>Time</td><td>Item</td><td>Owner</td><td>Ench</td></tr>";	}
				echo "<tr><td><p>$outdate</p></td><td><p>$obj_name</p></td><td><p>$owner</p></td><td><p>$ench</p></td></tr>";
				$count++;
			}
			if ($count == 0)
			{	echo "<p>No deleted items recorded</p>";	}
			else
			{	echo "</table>";	}
		}
	}
}

echo "</center></body></html>";

?>
