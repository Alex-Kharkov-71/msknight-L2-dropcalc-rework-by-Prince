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
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
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

		$sql = "select COUNT(*) from $knight_db.itemlog where `this_run` = 1";	
		$result = mysql_query($sql,$con);
		$t_count = 0;
		while ($r_array = mysql_fetch_assoc($result))
		{	$t_count = $r_array['COUNT(*)'];	}

		$sql = "select * from $knight_db.itemlog where `this_run` = 1";	
		$result = mysql_query($sql,$con);
		$count = 0;
		while ($r_array = mysql_fetch_assoc($result))
		{
			$owner_id = $r_array[owner_id];
			$object_id = $r_array[object_id];
			$enchant_level = $r_array[enchant_level];
			$timestamp = $r_array[timestamp];
			$sql = "select index_id, owner_id, enchant_level from $knight_db.itemlog where object_id = $object_id and this_run = 0 order by index_id desc limit 1";
			$result2 = mysql_query($sql,$con);
			$index_id = 0;
			while ($r_array = mysql_fetch_assoc($result2))
			{	
				$index_id = $r_array[index_id];	
				// If there is no change in owner or enchantment, then there is no change.
				$own_id = $r_array[owner_id];	
				$ench = $r_array[enchant_level];	
				if (($ench == $enchant_level) and ($own_id == $owner_id))
				{	$index_id = -1;	}
			}
			$index_id++;
			if ($index_id > 0)
			{
				$sql = "update $knight_db.itemlog set index_id = $index_id, this_run = 0 where `object_id` = $object_id and timestamp = $timestamp";
				$result2 = mysql_query($sql,$con);
			}
			else 
			{
				$sql = "delete from $knight_db.itemlog where `object_id` = $object_id and timestamp = $timestamp";
				$result2 = mysql_query($sql,$con);
			}
			$count++;
		}
		echo "<p>$count items integrated.</p>";
		if ($t_count == $count)
		{	echo "<p>All items successfully integrated.</p>";	}
	}
}

echo "</center></body></html>";

?>
