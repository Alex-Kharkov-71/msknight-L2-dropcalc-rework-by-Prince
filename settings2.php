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
$action = input_check($_REQUEST['action'],0);
$number = preg_replace('/[&%$\\\|<>#£]/','',$_REQUEST['number']);

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


		echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">Technical Settings</h2>";

		if ($action == "accountsafe")
		{
			$sql = "update knightsettings set account_safe = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "checkbox")
		{
			$sql = "update knightsettings set check_boxing = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "lowga")
		{
			$sql = "update knightsettings set low_graphic_allow = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "botscan")
		{
			$sql = "update knightsettings set bot_scan_ban = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "timezone")
		{
			$sql = "update knightsettings set auto_prune = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "stopipban")
		{
			$sql = "update knightsettings set stopbanIPreg = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "displaycountry")
		{
			$sql = "update knightsettings set display_country = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "maxacc")
		{
			$number = intval($number);
			if ($number > 45)
			{	$number = 45;	}
			if ($number <= 8)
			{	$number = 8;	}
			$sql = "update knightsettings set max_acc_length = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "maxpass")
		{
			$number = intval($number);
			if ($number > 45)
			{	$number = 45;	}
			if ($number <= 8)
			{	$number = 8;	}
			$sql = "update knightsettings set max_pass_length = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "leveladmin")
		{
			$number = intval($number);
			if ($number > 999)
			{	$number = 999;	}
			if ($number <= $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel+1;	}
			if ($number <= $sec_giveandtake)
			{	$number = $sec_giveandtake+1;	}
			if ($number <= $sec_takeskill)
			{	$number = $sec_takeskill+1;	}
			if ($number <= $sec_chatto)
			{	$number = $sec_chatto+1;	}
			if ($number <= $adjust_anounce)
			{	$number = $adjust_anounce+1;	}
			if ($number <= $kick_player)
			{	$number = $kick_player+1;	}
			if ($number <= $adjust_trust)
			{	$number = $adjust_trust+1;	}
			if ($number <= $reboot_server)
			{	$number = $reboot_server+1;	}
			if ($number <= $adjust_shop)
			{	$number = $adjust_shop+1;	}
			if ($number <= $adjust_drops)
			{	$number = $adjust_drops+1;	}
			if ($number <= $sec_adj_notes)
			{	$number = $sec_adj_notes+1;	}
			$sql = "update knightsettings set sec_inc_admin = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelgandt")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set sec_giveandtake = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelenchant")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set sec_enchant = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelgandts")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set sec_takeskill = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelshops")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set adjust_shop = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "leveldrops")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set adjust_drops = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "leveltrust")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set adjust_trust = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelanounce")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set adjust_anounce = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelreboot")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set reboot_server = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelnotes")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set sec_adj_notes = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelkick")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set kick_player = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelchat")
		{
			$number = intval($number);
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin - 1;	}
			if ($number < $sec_inc_gmlevel)
			{	$number = $sec_inc_gmlevel;	}
			$sql = "update knightsettings set sec_chatto = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "levelgm")
		{
			$number = intval($number);
			if ($number < 10)
			{	$number = 10;	}
			if ($number >= $sec_inc_admin)
			{	$number = $sec_inc_admin-1;	}
			if ($number > $sec_giveandtake)
			{	$number = $sec_giveandtake;	}
			if ($number > $sec_takeskill)
			{	$number = $sec_takeskill;	}
			if ($number > $sec_chatto)
			{	$number = $sec_chatto;	}
			if ($number > $adjust_anounce)
			{	$number = $adjust_anounce;	}
			if ($number > $kick_player)
			{	$number = $kick_player;	}
			if ($number > $adjust_trust)
			{	$number = $adjust_trust;	}
			if ($number > $reboot_server)
			{	$number = $reboot_server;	}
			if ($number > $adjust_shop)
			{	$number = $adjust_shop;	}
			if ($number > $adjust_drops)
			{	$number = $adjust_drops;	}
			if ($number > $sec_adj_notes)
			{	$number = $sec_adj_notes;	}
			$sql = "update knightsettings set sec_inc_gmlevel = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "maxchars")
		{
			$number = intval($number);
			if ($number >= 20)
			{	$number = 20;	}
			if ($number < 7)
			{	$number = 7;	}
			$sql = "update knightsettings set max_chars_per_acc = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "l2version")
		{
			$number = intval($number);
			if ($number >= 20)
			{	$number = 20;	}
			if ($number < 1)
			{	$number = 1;	}
			$sql = "update knightsettings set l2version = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "tokenexp")
		{
			$number = intval($number);
			if ($number >= 600)
			{	$number = 600;	}
			if ($number < 30)
			{	$number = 30;	}
			$sql = "update knightsettings set db_tokenexp = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "dropa")
		{
			$number = intval($number * 100);
			if ($number >= 9999999)
			{	$number = 9999999;	}
			if ($number < 1)
			{	$number = 1;	}
			$number = $number / 100;
			$sql = "update knightsettings set drop_chance_adena = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "dropi")
		{
			$number = intval($number * 100);
			if ($number >= 9999999)
			{	$number = 9999999;	}
			if ($number < 1)
			{	$number = 1;	}
			$number = $number / 100;
			$sql = "update knightsettings set drop_chance_item = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "drops")
		{
			$number = intval($number * 100);
			if ($number >= 9999999)
			{	$number = 9999999;	}
			if ($number < 1)
			{	$number = 1;	}
			$number = $number / 100;
			$sql = "update knightsettings set drop_chance_spoil = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		
		if ($action == "usedup")
		{
			$sql = "update knightsettings set use_duplicate = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "defaultlang")
		{
			$sql = "update knightsettings set default_lang = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptengm")
		{
			$sql = "update knightsettings set gmintopten = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "gmwholow")
		{
			$sql = "update knightsettings set whosonlinegmlow = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "showchartime")
		{
			$sql = "update knightsettings set show_char_time = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "showchartimea")
		{
			$sql = "update knightsettings set show_detail_char_time = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "refreshw")
		{
			if ($number < 10)
			{	$number = 10;	}
			$sql = "update knightsettings set delay_whosonline = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "refreshc")
		{
			if ($number < 5)
			{	$number = 5;	}
			$sql = "update knightsettings set delay_chat = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "refreshl")
		{
			if ($number < 5)
			{	$number = 5;	}
			$sql = "update knightsettings set delay_logs = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "showmap")
		{
			$sql = "update knightsettings set show_map = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "mapnudge")
		{
			$sql = "update knightsettings set map_nudge = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "showmobpict")
		{
			$sql = "update knightsettings set showmobpict = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "enchntgmaccallow")
		{
			$sql = "update knightsettings set enchntgmaccallow = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		
		if ($action == "logactions")
		{
			$sql = "update knightsettings set log_actions = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		
		if ($action == "logduration")
		{
			$sql = "update knightsettings set log_duration = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		
		include('config-read.php');
		
		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Dropcalc Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings2.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Technical Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings3.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Cross Gameserver Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings4.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Character Permission Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "</tr></table>";

		echo "<p>&nbsp;</p><a name=\"block4\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Is account safe on?
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"accountsafe\">Account Safe</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($account_safe)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If you are running multiple gameservers off the one logon server, then account safe will prevent the database clean up from deleting accounts which have no characters against them, in case they have characters on a different gameserver.</p></td></tr>";

		// Check Boxing
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"checkbox\">Check for Dual Boxing</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($check_boxing)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If set to yes, when a GM views the whos online, then if more than one character is online from the same IP, or the same account is on twice, then the GM sees offending characters as red.</p></td></tr>";
		
		// Allow low res graphics
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"lowga\">Low Graphics Allow</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($low_graphic_allow)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">This allows the system to use the alternate style sheet in the skin, so that GM's viewing chat logs, etc. load lower res graphics to make the process quicker and use less bandwidth.</p></td></tr>";
		
		// Low res Who's online for GM's when watching who's online
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"gmwholow\">Low res Who's Online for GM's</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($whosonlinegmlow)
		{	echo "<option value=\"0\">High Res</option><option value=\"1\" selected>Low Res</option>";	}
		else
		{	echo "<option value=\"0\" selected>High Res</option><option value=\"1\">Low Res</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Determines whether GM' see low res backgrounds when they view the Who's Online.  This option is only active if the global low res option is set.</p></td></tr>";
		
		// Bot Scan Ban
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"botscan\">Bot Scan Ban</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($bot_scan_ban)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">When set to yes, then whenever someone runs the who's online screen, or when a GM looks at a chat or server log file, a check is run.  Any accounts which are not correctly authorised as GM's are kicked and banned.  Any characters found to be in excess of 50,000 maxhp or maxmp, are kicked and deleted.</p></td></tr>";
		
		// Stop Banned IP from registering
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"stopipban\">Prevent Banned IP re-registering</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($stopbanIPreg)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If using the dropcalcs registration system, then this option will prevent any IP which belongs to a banned account, from registering a new account.</p></td></tr>";
		
		// Activity logging
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"logactions\">Activity log level</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		$in = 0;
		while ($in < 6)
		{
			if ($log_action == $in)
			{	echo "<option value=\"$in\" selected>$in</option>";	}
			else
			{	echo "<option value=\"$in\">$in</option>";	}
			$in++;
		}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The level to which the dropcalc will record activity taken on it by GM's.  0 is off.</p></td></tr>";
		
		// Activity log duration
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"logduration\">Activity log duration (months)</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		$in = 1;
		while ($in < 13)
		{
			if ($log_duration == $in)
			{	echo "<option value=\"$in\" selected>$in</option>";	}
			else
			{	echo "<option value=\"$in\">$in</option>";	}
			$in++;
		}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Duration, in aproximate months (blocks of 30 days), that an activity log will be kept.</p></td></tr>";
		
		// Time Zone
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"timezone\">Time Zone</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		$i = -12;
		while ($i < 13)
		{	if ($auto_prune == $i)
			{	echo "<option value=\"$i\" selected>$i</option>";	}
			else
			{	echo "<option value=\"$i\">$i</option>";	}
			$i++;
		}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The dropcalc needs to know the time difference from GMT.  As the dropcalc can be on a different server than the gameserver, taking the system time could prove to be wrong.</p></td></tr>";
		
		// Display country
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"displaycountry\">Show Country Information</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($display_country)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If the admin has loaded the country identification tables, then setting this option to Yes, will show a GM the country of the players who are online.</p></td></tr>";
		
		// Max Acc Length
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"maxacc\">Max Username Length</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$max_acc_length\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The maximum user name length allowed.  Between 8 and 45.</p></td></tr>";
		
		// Max Pass Length
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"maxpass\">Max Password Length</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$max_pass_length\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The maximum password length allowed.  Between 8 and 45.</p></td></tr>";
		
		// Hide enchanted items that are on non-GM characters owned by GM accounts
		echo "<tr><form method=\"post\" action=\"settings2.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"enchntgmaccallow\">Ignore enchanted items on non-GM chars owned by GM accounts.</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($enchntgmaccallow)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If set to yes, then the enchanted items check in the tools setcion, will not report over enchanted items which are on non-gm characters that are linked to GM accounts.</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block5\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Max Acc Length
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"leveladmin\">Admin Access Level</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$sec_inc_admin\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which a user has full admin access to the dropcalc functions.  Must be higher than any other access level.</p></td></tr>";
		
		// Give and take skills
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelgandts\">Give And Take Skills</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$sec_takeskill\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone is allowed to give or take skills to or from a character.  Must be less than admin level and higher or equal to entry level GM.</p></td></tr>";
		
		// Adjust enchant
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelenchant\">Adjust enchantment</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$sec_enchant\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone is allowed to alter the drops and spoils.</p></td></tr>";
		
		// Adjust drops
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"leveldrops\">Adjust drops</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$adjust_drops\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone is allowed to alter the drops and spoils.</p></td></tr>";
		
		// Adjust shops
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelshops\">Adjust shops</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$adjust_shop\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone is allowed to alter the items sold in shops.</p></td></tr>";
		
		// Adjust trust
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"leveltrust\">Adjust trusts</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$adjust_trust\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone can change a characters trust status.</p></td></tr>";
		
		// Adjust notes
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelnotes\">Adjust notes</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$sec_adj_notes\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone can add notes to a users account.</p></td></tr>";
		
		// Adjust Announcements
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelanounce\">Adjust anouncements</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$adjust_anounce\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\"><strong>Not yet implemented</strong> - The level someone can adjust the server announcements.</p></td></tr>";
		
		// Reboot server
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelreboot\">Reboot server</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$reboot_server\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone can reboot the servers.</p></td></tr>";
		
		// Give and take items
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelgandt\">Give And Take Items</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$sec_giveandtake\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone is allowed to give or take items to or from a character.  Must be less than admin level and higher or equal to entry level GM.</p></td></tr>";
		
		// Kick Player
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelkick\">Kick Player</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$kick_player\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone can kick a player from the game.</p></td></tr>";
		
		// Chat to player
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelchat\">Chat to player</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$sec_chatto\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone can chat to a player via the dropcalc.</p></td></tr>";
		
		// Entry GM level
		echo "<tr><form method=\"post\" action=\"settings2.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"levelgm\">Entry GM Level</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$sec_inc_gmlevel\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The account level at which someone becomes a GM to the dropcalc.  Can not be the same as or higher than the admin level, and can not be higher than any other setting.</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block6\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Max Chars per account
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"maxchars\">Max Chars Per Account</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$max_chars_per_acc\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Doesn't control anything, other than the warning you receive when transfering characters to accounts.  When the client can handle more characters per account, then up this number.</p></td></tr>";
		
		// L2 Version
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"l2version\">L2 Version</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$l2version\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Used by the character changer to know which hair and face sets to use.  L2-C5 is number 5.  No other numbers implemented yet.</p></td></tr>";
		
		// Token Expiry
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"tokenexp\">Token Expiry</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"3\" size=\"3\" value=\"$db_tokenexp\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Length of inactivity, in minutes, before the dropcalc times out the session.  Between 30 and 600 minutes.</p></td></tr>";
		
		// Drop Chance Adena
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"dropa\">Adena Drop Rate</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"10\" size=\"10\" value=\"$drop_chance_adena\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Servers multiplication rate for adena drop.</p></td></tr>";
		
		// Drop Chance Items
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"dropi\">Item Drop Rate</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"10\" size=\"10\" value=\"$drop_chance_item\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Servers multiplication rate for item drop chance.</p></td></tr>";
		
		// Drop Chance Spoil
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"drops\">Spoil Rate</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"10\" size=\"10\" value=\"$drop_chance_spoil\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Servers multiplication rate for item spoil chance.</p></td></tr>";
				
		// Use duplicate item ID table
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"usedup\">Use duplicate item graphics</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($use_duplicate)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If set, then the system will use the installed duplicate item table to minimise duplicate item and skill icons from being sent to the browsing client.</p></td></tr>";
			
		// Set default language
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"defaultlang\">Default Language</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		$l_array_count = count($language_array);
		$i = 0;
		while ($i < $l_array_count)
		{
			$language_entry = $language_array[$i];
			$language_title = $language_entry[0];
			$language_file = $language_entry[1];
			
			if ($i == $default_lang)
			{	echo "<option value=$language_file selected>$language_title</options>";	}
			else
			{	echo "<option value=$language_file>$language_title</options>";	}
			$i++;
		}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Sets the default language that the dropcalc will use on initial load.</p></td></tr>";

		// Show time on line for characters
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"showchartime\">Show Online time for characters</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($show_char_time)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, displays the online times for characters and accounts.</p></td></tr>";
		
		// Show time on line for ip addresses
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"showchartimea\">Show online times against IP.</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($show_detail_c_time)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If this and the online time for characters are both yes, then the onine time for IP addresses is shown.</p></td></tr>";
		
		// Who's online refresh time
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"refreshw\">Refresh rate for Who's Online</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$delay_whosonline\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Controls the refresh rate for the Who's Online screen.  Minimum is ten seconds.</p></td></tr>";
		
		// Chat refresh rate
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"refreshc\">Refresh rate for Chat logs</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$delay_chat\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Controls the refresh rate for the chat logs.  Minimum is five seconds.</p></td></tr>";
		
		// Log refresh rate
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"refreshl\">Refresh rate for system logs</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$delay_logs\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Controls the refresh rate for the systemt logs.  Minimum is five seconds.</p></td></tr>";
		
		// Allow users to see the whos online map.
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"showmap\">Whether to show server online population map</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($show_map == 1)
		{	echo "<option value=\"0\">GM Only</option><option value=\"1\" selected>Users only</option><option value=\"2\">Users and guests</option>";	}
		elseif ($show_map == 2)
		{	echo "<option value=\"0\">GM Only</option><option value=\"1\">Users only</option><option value=\"2\" selected>Users and guests</option>";	}
		else
		{	echo "<option value=\"0\" selected>GM Only</option><option value=\"1\">Users only</option><option value=\"2\">Users and guests</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">This option will allow either authenticated users, or all users to see the online population on a large map when viewing the Whos Online area.</p></td></tr>";
		
		// Whether to nudge the map position crosses for players positions on the maps.
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mapnudge\">Nudge the crosses for characters that are in the same area.</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($map_nudge)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If on, then the system will, 'nudge' the players map location crosses so that people can get a clearer picture of how many people are actually in that one lcoation.  This does add to the processing time, however.</p></td></tr>";
		
		// Whether or not to show the mob pictures if they exist in the system.
		echo "<tr><form method=\"post\" action=\"settings2.php#block6\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"showmobpict\">Show the pictures of mobs if they are available in the system.</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($showmobpict)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If this is enabled, then people who are looking at a monsters details will also receive a picture of the monster if there is one present in the system.</p></td></tr>";
	
		echo "</table></center>";

		
		echo "<p>&nbsp;</p>";

	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>