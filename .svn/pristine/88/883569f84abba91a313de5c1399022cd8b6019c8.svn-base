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
$number = preg_replace('/[&%$\\\|<>#�]/','',$_REQUEST['number']);

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


		echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">DropCalc Settings Change</h2>";

		if ($action == "register")
		{
			$sql = "update knightsettings set register_allow = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "guestlogon")
		{
			$sql = "update knightsettings set guest_allow = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "guestmaps")
		{
			$sql = "update knightsettings set guest_user_maps = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "guestdropthru")
		{
			$sql = "update knightsettings set guest_dropthru = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "guestclannosee")
		{
			$sql = "update knightsettings set guest_nosee_clanchars = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "allumap")
		{
			$sql = "update knightsettings set all_users_maps = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "allurec")
		{
			$sql = "update knightsettings set all_users_recipe = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "alluchar")
		{
			$sql = "update knightsettings set all_users_character = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "alluseven")
		{
			$sql = "update knightsettings set sevensignall = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "newumap")
		{
			$sql = "update knightsettings set all_newusers_maps = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "newurec")
		{
			$sql = "update knightsettings set all_newusers_recipe = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "newuchar")
		{
			$sql = "update knightsettings set all_newusers_character = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptenshow")
		{
			$sql = "update knightsettings set top_ten = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptennumber")
		{
			$sql = "update knightsettings set tten_number = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptenlvl")
		{
			$sql = "update knightsettings set tten_level = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptenpk")
		{
			$sql = "update knightsettings set tten_pk = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptenpvp")
		{
			$sql = "update knightsettings set tten_pvp = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptenkarma")
		{
			$sql = "update knightsettings set tten_karma = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "toptenfame")
                {
                        $sql = "update knightsettings set tten_fame = '$number'";
                        $result = mysql_query($sql,$con);
                        if (!$result)
                        {
                                die('Could not update settings table: ' . mysql_error());
                        }
                }
		if ($action == "toptentime")
		{
			$sql = "update knightsettings set tten_time = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "emergencyt")
		{
			$sql = "update knightsettings set emergency_teleport = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "gmappear")
		{
			$sql = "update knightsettings set gm_play_show = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "gameparanoia")
		{
			$sql = "update knightsettings set game_paranoia = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "pcrossc")
		{
			$sql = "update knightsettings set prevent_cross_clan = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "recdepth")
		{
			$sql = "update knightsettings set recipe_depth = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "dropcg")
		{
			$number = intval($number);
			if ($number > 99)
			{	$number = 99;	}
			if ($number <= $drop_chance_blue)
			{	$number = $drop_chance_blue + 1;	}
			$sql = "update knightsettings set drop_chance_green = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "dropcb")
		{
			$number = intval($number);
			if ($number < 1)
			{	$number = 1;	}
			if ($number >= $drop_chance_green)
			{	$number = $drop_chance_green - 1;	}
			$sql = "update knightsettings set drop_chance_blue = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "mapitemp")
		{
			$sql = "update knightsettings set map_item_status = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "mapitemid")
		{
			$number = intval($number);
			if ($number >= 99999)
			{	$number = 99999;	}
			if ($number < 1)
			{	$number = 1;	}
			$sql = "update knightsettings set map_item_id = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "mapitemloc")
		{
			$sql = "update knightsettings set map_item_when = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "mapitemon")
		{
			$sql = "update knightsettings set map_item_online = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "recitemp")
		{
			$sql = "update knightsettings set rec_item_status = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "recitemid")
		{
			$number = intval($number);
			if ($number >= 99999)
			{	$number = 99999;	}
			if ($number < 1)
			{	$number = 1;	}
			$sql = "update knightsettings set rec_item_id = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "recitemloc")
		{
			$sql = "update knightsettings set rec_item_when = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "recitemon")
		{
			$sql = "update knightsettings set rec_item_online = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "charitemp")
		{
			$sql = "update knightsettings set char_item_status = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "charitemid")
		{
			$number = intval($number);
			if ($number >= 99999)
			{	$number = 99999;	}
			if ($number < 1)
			{	$number = 1;	}
			$sql = "update knightsettings set char_item_id = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "charitemloc")
		{
			$sql = "update knightsettings set char_item_when = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "charitemon")
		{
			$sql = "update knightsettings set char_item_online = '$number'";
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
		if ($action == "minlenitem")
		{
			$number = intval($number);
			if ($number >= 9)
			{	$number = 9;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set minlenitem = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "minlenchar")
		{
			$number = intval($number);
			if ($number >= 9)
			{	$number = 9;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set minlenchar = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "minlenclan")
		{
			$number = intval($number);
			if ($number >= 9)
			{	$number = 9;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set minlenclan = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "minlenmobs")
		{
			$number = intval($number);
			if ($number >= 9)
			{	$number = 9;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set minlenmobs = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "minlenacc")
		{
			$number = intval($number);
			if ($number >= 9)
			{	$number = 9;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set minlenacc = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "minlenloc")
		{
			$number = intval($number);
			if ($number >= 9)
			{	$number = 9;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set minlenloc = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "minlenrec")
		{
			$number = intval($number);
			if ($number >= 9)
			{	$number = 9;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set minlenrec = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		
		if ($action == "chatstyle")
		{
			$number = intval($number);
			if ($number >= 1)
			{	$number = 1;	}
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update knightsettings set chat_style = '$number'";
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
		echo "<p>&nbsp;</p>";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Register Account
		echo "<tr><form method=\"post\" action=\"settings.php\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"register\">Register Via Dropcalc</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($register_allow)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, people can register game accounts via the dropcalc.  If no, then you will need to provide an alternative registration method.</p></td></tr>";
		
		// Guest Logon
		echo "<tr><form method=\"post\" action=\"settings.php\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"guestlogon\">Allow Guest logon</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($guest_allow)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, the \"guest\" logon method works to allow unregistered users to access the dropcalc.</p></td></tr>";
		
		// Guest User maps
		echo "<tr><form method=\"post\" action=\"settings.php\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"guestmaps\">Allow Guest user map access</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($guest_user_maps)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, the \"guest\" users have access to the mob locations on maps.</p></td></tr>";
		
		// Guest Drop Through
		echo "<tr><form method=\"post\" action=\"settings.php\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"guestdropthru\">Translate failed logon to guest</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($guest_dropthru)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, then any \"failed\" logon attempt is logged on as a guest.</p></td></tr>";
		
		// Guest Not See Clan Characters
		echo "<tr><form method=\"post\" action=\"settings.php\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"guestclannosee\">Allow guests to see character in clans</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($guest_nosee_clanchars)
		{	echo "<option value=\"0\">Yes</option><option value=\"1\" selected>No</option>";	}
		else
		{	echo "<option value=\"0\" selected>Yes</option><option value=\"1\">No</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, then any guest can see the characters that belong to clans.</p></td></tr>";
		
		// Chat Style
		echo "<tr><form method=\"post\" action=\"settings.php\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"chatstyle\">View the chat logs in coloured or plain mode</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($clog_type)
		{	echo "<option value=\"0\">Plain</option><option value=\"1\" selected>Colour</option>";	}
		else
		{	echo "<option value=\"0\" selected>Plain</option><option value=\"1\">Colour</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, then any guest can see the characters that belong to clans.</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block1\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// All users see map locations
		echo "<tr><td class=\"dropmain\" valign=\"top\" rowspan=\"4\"><p class=\"left\">Allow all users to...</p></td><form method=\"post\" action=\"settings.php#block1\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"allumap\">See mob locations on maps</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($all_users_maps)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\" rowspan=\"4\"><p class=\"dropmainwhite\">If yes, then all registered users have access to these resources regardless of their account settings.  If later set to no, then the access rights revert to the individual account settings.</p></td></tr>";
		
		// All users access recipe calculator
		echo "<tr><form method=\"post\" action=\"settings.php#block1\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"allurec\">Use&nbsp;the&nbsp;Recipe Calculator</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($all_users_recipe)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// All users change character looks
		echo "<tr><form method=\"post\" action=\"settings.php#block1\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"alluchar\">Use&nbsp;the&nbsp;character changer</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($all_users_character)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// All users see detailed seven signs info
		echo "<tr><form method=\"post\" action=\"settings.php#block1\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"alluseven\">See detailed seven signs info.</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($sevensignall)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// New Users see map locations
		echo "<tr><td class=\"dropmain\" valign=\"top\" rowspan=\"3\"><p class=\"left\">Give any new user rights to...</p></td><form method=\"post\" action=\"settings.php#block1\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"newumap\">See mob locations on maps</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($all_newusers_maps)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\" rowspan=\"3\"><p class=\"dropmainwhite\">If any of these is set to yes, then newly registered user accounts will have unlimited access rights to that resource, unless specifically revoked later by an admin.</p><p class=\"dropmainwhite\">Useful where you want to give everyone the rights to access a resource, but later revoke it as a punishment.</p></td></tr>";
		
		// New Users access recipe calculator
		echo "<tr><form method=\"post\" action=\"settings.php#block1\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"newurec\">Use&nbsp;the&nbsp;Recipe&nbsp;Calculator</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($all_newusers_recipe)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";

		// New Users change character looks
		echo "<tr><form method=\"post\" action=\"settings.php#block1\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"newuchar\">Use&nbsp;the&nbsp;character changer</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($all_newusers_character)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block7\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Mob on item possession ... on/off
		echo "<tr><td class=\"dropmain\" valign=\"top\" rowspan=\"4\"><p class=\"left\">Mob loc on posession...</p></td><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mapitemp\">On/Off</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($map_item_status)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\" rowspan=\"4\"><p class=\"dropmainwhite\">If on, then the dropcalc will allow access to the mob location map, providing one of the players characters has access to the specific item and the set conditions.</p></td></tr>";
		
		// Mob on item possession ... item number
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mapitemid\">Item to posess</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"6\" size=\"6\" value=\"$map_item_id\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Mob on item possession ... item location
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mapitemloc\">Where is the item</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($map_item_when == 1)
		{	echo "<option value=\"0\">Posess</option><option value=\"1\" selected>Carrying</option><option value=\"2\">Wearing</option>";	}
		elseif ($map_item_when == 2)
		{	echo "<option value=\"0\">Posess</option><option value=\"1\">Carrying</option><option value=\"2\" selected>Wearing</option>";	}
		else
		{	echo "<option value=\"0\" selected>Posess</option><option value=\"1\">Carrying</option><option value=\"2\">Wearing</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Mob on item possession ... online?
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mapitemon\">Do they have to be online?</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($map_item_online)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// RecCalc on item possession ... on/off
		echo "<tr><td class=\"dropmain\" valign=\"top\" rowspan=\"4\"><p class=\"left\">Recipe Calculator on posession...</p></td><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"recitemp\">On/Off</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($rec_item_status)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\" rowspan=\"4\"><p class=\"dropmainwhite\">If on, then the dropcalc will allow access to the recipe calculator, providing one of the players characters has access to the specific item and the set conditions.</p></td></tr>";
		
		// RecCalc on item possession ... item number
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"recitemid\">Item to posess</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"6\" size=\"6\" value=\"$rec_item_id\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// RecCalc on item possession ... item location
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"recitemloc\">Where is the item</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($rec_item_when == 1)
		{	echo "<option value=\"0\">Posess</option><option value=\"1\" selected>Carrying</option><option value=\"2\">Wearing</option>";	}
		elseif ($rec_item_when == 2)
		{	echo "<option value=\"0\">Posess</option><option value=\"1\">Carrying</option><option value=\"2\" selected>Wearing</option>";	}
		else
		{	echo "<option value=\"0\" selected>Posess</option><option value=\"1\">Carrying</option><option value=\"2\">Wearing</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// RecCalc on item possession ... online?
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"recitemon\">Do they have to be online?</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($rec_item_online)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Char Changer on item possession ... on/off
		echo "<tr><td class=\"dropmain\" valign=\"top\" rowspan=\"3\"><p class=\"left\">Character Changer on posession...</p></td><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"charitemp\">On/Off</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($char_item_status)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\" rowspan=\"3\"><p class=\"dropmainwhite\">If on, then the dropcalc will allow access to the character changer, providing one of the players characters has access to the specific item and the set conditions.<br>Note that the character has to be off-line to be able to make changes.</p></td></tr>";
		
		// Char Changer on item possession ... item number
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"charitemid\">Item to posess</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"6\" size=\"6\" value=\"$char_item_id\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Char Changer on item possession ... item location
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"charitemloc\">Where is the item</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($char_item_when == 1)
		{	echo "<option value=\"0\">Posess</option><option value=\"1\" selected>Carrying</option><option value=\"2\">Wearing</option>";	}
		elseif ($char_item_when == 2)
		{	echo "<option value=\"0\">Posess</option><option value=\"1\">Carrying</option><option value=\"2\" selected>Wearing</option>";	}
		else
		{	echo "<option value=\"0\" selected>Posess</option><option value=\"1\">Carrying</option><option value=\"2\">Wearing</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		/*
		// Char Changer on item possession ... online?
		echo "<tr><form method=\"post\" action=\"settings.php#block7\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"charitemon\">Do they have to be online?</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($char_item_online)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		*/
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block2\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Is top ten on?
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptenshow\">Show Top Players</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($top_ten)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\" rowspan=\"7\"><p class=\"dropmainwhite\">If yes, then the Top Players is shown.  The amount of players in the list, and which attributes are shown, are set here.</p></td></tr>";
		
		// Top ten - number to show
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptennumber\">Number of players shown</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($tten_number == 1)
		{	echo "<option value=\"0\">10</option><option value=\"1\" selected>50</option><option value=\"2\">100</option>";	}
		elseif ($tten_number == 2)
		{	echo "<option value=\"0\">10</option><option value=\"1\">50</option><option value=\"2\" selected>100</option>";	}
		else
		{	echo "<option value=\"0\" selected>10</option><option value=\"1\">50</option><option value=\"2\">100</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Top ten - show level ?
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptenlvl\">Show Level</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($tten_level)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Top ten - show pk ?
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptenpk\">Show PK kills</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($tten_pk)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Top ten - show pvp ?
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptenpvp\">Show PVP Killss</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($tten_pvp)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Top ten - show karma ?
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptenkarma\">Show Karma</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($tten_karma)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		// Top ten - show fame ?
                echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptenfame\">Show Fame</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
                if ($tten_fame)
                {        echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";        }
                else
                {        echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";        }
                echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";

		// Top ten - show time ?
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptentime\">Show Online Time</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($tten_time)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form></tr>";
		
		// Show/Hide GM's in top ten
		echo "<tr><form method=\"post\" action=\"settings.php#block2\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"toptengm\">Hide/Show GM's in top ten</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($gmintopten)
		{	echo "<option value=\"0\">Hide</option><option value=\"1\" selected>Show</option>";	}
		else
		{	echo "<option value=\"0\" selected>Hide</option><option value=\"1\">Show</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Determines whether GM characters are hidden or shown in the top ten feature.</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block3\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Emergency Teleport
		echo "<tr><form method=\"post\" action=\"settings.php#block3\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"emergencyt\">Allow Emergency Teleport</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($emergency_teleport)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, then users have a button that can transport their character to the home town. This was introduced as a fix to clients that crash when the player logs on.</p></td></tr>";
		
		// GM Appearance
		echo "<tr><form method=\"post\" action=\"settings.php#block3\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"gmappear\">How do GM's appear in Whos Online</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($gm_play_show == 1)
		{	echo "<option value=\"0\">Green</option><option value=\"1\" selected>Blue</option><option value=\"2\">Invisible</option>";	}
		elseif ($gm_play_show == 2)
		{	echo "<option value=\"0\">Green</option><option value=\"1\">Blue</option><option value=\"2\" selected>Invisible</option>";	}
		else
		{	echo "<option value=\"0\" selected>Green</option><option value=\"1\">Blue</option><option value=\"2\">Invisible</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">This determines how GM's who are on-line, show up in the Whos Online.  Default is green, but they can be changed to blue to look like other players, or they can be hidden from the online list alltogether. This does not affect visibility or colour in game.</p></td></tr>";
		
		// Game Paranoia
		echo "<tr><form method=\"post\" action=\"settings.php#block3\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"gameparanoia\">Details Restrictions<br>(aka Paranoia setting)</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($game_paranoia == 1)
		{	echo "<option value=\"0\">Low</option><option value=\"1\" selected>Medium</option><option value=\"2\">High</option>";	}
		elseif ($game_paranoia == 2)
		{	echo "<option value=\"0\">Low</option><option value=\"1\">Medium</option><option value=\"2\" selected>High</option>";	}
		else
		{	echo "<option value=\"0\" selected>Low</option><option value=\"1\">Mediuum</option><option value=\"2\">High</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\"><strong>Low</strong> - Players and guests can see character account names and the positions of each other on the map.<br><strong>Medium</strong> - Stops players seeing other players positions on the map, and stops them seeing the accounts characters belong to.<br><strong>High</strong> - As medium but extra restrictions on character details that are shown.</p></td></tr>";
		
		// Prevent Cross Clan
		echo "<tr><form method=\"post\" action=\"settings.php#block3\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"pcrossc\">Prevent Cross Clan</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($prevent_cross_clan == 1)
		{	echo "<option value=\"0\">Open</option><option value=\"1\" selected>Strict</option><option value=\"2\">Tight</option>";	}
		elseif ($prevent_cross_clan == 2)
		{	echo "<option value=\"0\">Open</option><option value=\"1\">Strict</option><option value=\"2\" selected>Tight</option>";	}
		else
		{	echo "<option value=\"0\" selected>Open</option><option value=\"1\">Strict</option><option value=\"2\">Tight</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\"><strong>Open</strong> - Players can see items which are held by other players in the same clans as them.<br><strong>Strict</strong> - Players can not see others items, but can still use the clan item search.<br><strong>Tight</strong> - Same as strict, but clan item search is disabled.</p></td></tr>";
		
		// Maximum Recipe Depth
		echo "<tr><form method=\"post\" action=\"settings.php#block3\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"recdepth\">Maximum Recipe Depth</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		$i = 1;
		while ($i < 7)
		{	
			$i2 = $i+1;
			echo "<option value=\"$i\"";
			if ($i == $recipe_depth)
			{	echo " selected";	}
			echo ">$i</option>";	
			$i++;
		}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Maxium depth that the recipe calculator will allow users to go to.</p></td></tr>";
		
		// Drop Chance Green
		echo "<tr><form method=\"post\" action=\"settings.php#block3\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"dropcg\">Drop Chance Green</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$drop_chance_green\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The percentage a drop or spoil chance has to be before the percentage number turns from blue to green. Can not be higher than 99 or lower or equal to blue.</p></td></tr>";
		
		// Drop Chance Blue
		echo "<tr><form method=\"post\" action=\"settings.php#block3\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"dropcb\">Drop Chance Blue</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$drop_chance_blue\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The percentage a drop or spoil chance has to be before the percentage number turns from red to blue. Can not be higher than green or lower than 1.</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block5\"></a>";
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Min Length Search Items
		echo "<tr><form method=\"post\" action=\"settings.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"minlenitem\">Min chars for Items</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"1\" size=\"1\" value=\"$minlenitem\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Minimum of characters to run an item search.</p></td></tr>";
		
		// Min Length Search Chars
		echo "<tr><form method=\"post\" action=\"settings.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"minlenchar\">Min chars for Chars</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"1\" size=\"1\" value=\"$minlenchar\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Minimum of characters to run a character search.</p></td></tr>";
		
		// Min Length Search Clans
		echo "<tr><form method=\"post\" action=\"settings.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"minlenclan\">Min chars for Clans</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"1\" size=\"1\" value=\"$minlenclan\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Minimum of characters to run a clan search.</p></td></tr>";
		
		// Min Length Search Mobs
		echo "<tr><form method=\"post\" action=\"settings.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"minlenmobs\">Min chars for Mobs</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"1\" size=\"1\" value=\"$minlenmobs\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Minimum of characters to run a mob search.</p></td></tr>";
		
		// Min Length Search Account
		echo "<tr><form method=\"post\" action=\"settings.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"minlenacc\">Min chars for Accs</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"1\" size=\"1\" value=\"$minlenacc\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Minimum of characters to run an account search.</p></td></tr>";
		
		// Min Length Search Locations
		echo "<tr><form method=\"post\" action=\"settings.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"minlenloc\">Min chars for Locs</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"1\" size=\"1\" value=\"$minlenloc\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Minimum of characters to run a location search.</p></td></tr>";
		
		// Min Length Search Recipes
		echo "<tr><form method=\"post\" action=\"settings.php#block5\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"minlenrec\">Min chars for Recs</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"1\" size=\"1\" value=\"$minlenrec\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Minimum of characters to run a recipe search.</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p>";

	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>