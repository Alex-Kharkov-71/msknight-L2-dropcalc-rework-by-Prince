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
		$sql = "delete from $knight_db.itemlog where object_id not in (select object_id from items);";
		$result = mysql_query($sql,$con);
		$sql = "select owner_id, object_id, enchant_level from items where item_id in (select item_id from armor) or item_id in (select item_id from weapon);";	
		$result = mysql_query($sql,$con);
		$timestamp =  time();
		$count = 0;
		while ($r_array = mysql_fetch_assoc($result))
		{
			$owner_id = $r_array[owner_id];
			$object_id = $r_array[object_id];
			$enchant_level = $r_array[enchant_level];
			$sql = "insert into $knight_db.itemlog (`object_id`, `index_id`, `timestamp`, `owner_id`, `enchant_level`, `this_run`) values ($object_id, 0, $timestamp, $owner_id, $enchant_level, 1)";
			$result2 = mysql_query($sql,$con);
			$count++;
		}
		echo "<p>$count items transferred.</p>";
	}
}

echo "</center></body></html>";

?>
