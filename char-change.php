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
$charname = input_check($_REQUEST['charname'],1);
$charnum = input_check($_REQUEST['charnum'],0);
$action = input_check($_REQUEST['action'],0);
$account = input_check($_REQUEST['account'],1);
$number = $_REQUEST['number'];
$subclass = $_REQUEST['subclass'];
$newown = input_check($_REQUEST['newown'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$xplevels = array(0, 1, 69, 364, 1169, 2885, 6039, 11288, 19424, 31379, 48230, 71203, 101678, 141194, 191455, 254331, 331868, 426289, 540001, 675597, 835863, 1023785, 1242547, 1495544, 1786380, 2118877, 2497078, 2925251, 3407898, 3949755, 4555797, 5231247, 5981577, 6812514, 7730045, 8740423, 9850167, 11066073, 12395216, 13844952, 15422930, 17137088, 18995666, 21007204, 23180551, 25524869, 28049636, 30764655, 33680053, 36806290, 40154163, 45525134, 51262491, 57383989, 63907912, 70853090, 80700832, 91162655, 102265882, 114038596, 126509653, 146308200, 167244337, 189364894, 212717908, 237352644, 271975263, 308443198, 346827154, 387199547, 429634523, 474207979, 532694979, 606322775, 696381369, 804225364, 931275364, 1151264834, 1511257834, 2099305234, 4200000000, 6300000000, 8820000000, 11844000000, 15472800000, 19827360000, 25314000000);


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

		$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
		mysql_query("SET NAMES 'utf8'", $con2);
		if (!$con2)
		{
			echo "Could Not Connect";
			die('Wrap_start could not connect to logserver database: ' . mysql_error());
		}		
		if (!mysql_select_db("$dblog_l2jdb",$con2))
		{
			die('Wrap_start could not change to logserver database: ' . mysql_error());
		}
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

		echo "<p class=\"dropmain\">&nbsp;</p><center><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum\" class=\"droph2\">Character - $charname</a></center>";

		if ($action == "changeacc")
		{
			$sql = "select login from $dblog_l2jdb.accounts where login LIKE '%$newown%'";
			$result = mysql_query($sql,$con2);
			if (!$result)
			{
				die('Could not read accounts list: ' . mysql_error());
			}
			else
			{
				echo "<h2 class=\"dropmain\">Move character to which account ...</h2><p class=\"dropmain\">&nbsp;</p>";
				$i=0;
				$col_check = 0;
				$no_of_acc = mysql_num_rows($result);
				echo "<center><table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" class=\"dropmain\"><tr>";
				if ( $no_of_acc > 0 )
				{
					while ($i < $no_of_acc)
					{
						$acc_name = mysql_result($result,$i,"login");
						echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"char-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charname=$charname&charnum=$charnum&action=transfer&account=$acc_name\" class=\"dropmain\">$acc_name</a></p></td>";
						$i++;
						$col_check++;
						if ($col_check == 7)
						{
							echo "</tr><tr>";
							$col_check = 0;
						}
					}
					if ($col_check > 0)
					{
						while ($col_check < 7)
						{
							echo "<td class=\"dropmain\">&nbsp;</td>";
							$col_check++;
						}
					}
				}
				else
				{	echo "<h2>-- No matching accounts found --</h2>";	}
				echo "</tr></table></center>";
			}
		}

		if ($action == "transfer")
		{
			$sql = "select login from $dblog_l2jdb.accounts where login = '$account'";
			$result = mysql_query($sql,$con2);
			if (!$result)
			{
				die('Could not read accounts list: ' . mysql_error());
			}
			else
			{
				$no_of_acc = mysql_num_rows($result);
				if (!$no_of_acc)
				{
					echo "<h2 class=\"dropmain\">Couldn't find account to transfer to.<br>Transfer failed.</h2>";
					
				}
				else
				{
					$sql = "update characters set account_name = '$account' where char_name = '$charname'";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	echo "<h2 class=\"dropmain\">Database error - transfer didn't complete.<br>" . mysql_error() . "</h2>";	}
					else
					{	echo "<h2 class=\"dropmain\">Transfer completed. Character now on account $account</h2>";	}
					$sql = "select char_name from characters where account_name = '$account'";
					$result = mysql_query($sql,$con);
					$char_count = mysql_num_rows($result);
					if ($char_count > $max_chars_per_acc)
					{	echo "<h2 class=\"dropmain\">WARNING - $account has more than $max_chars_per_acc characters.<br>Not all characters may be usable.</h2>";	}
				}

			}
			$action = "";
		}

		if ($action == "newlvl")
		{
			$sql = "update characters set accesslevel = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change level.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "karma")
		{
			$sql = "update characters set karma = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change karma.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "pvp")
		{
			$sql = "update characters set pvpkills = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change PVP Kills.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "pk")
		{
			$sql = "update characters set pkkills = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change PK Kills.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}


		if ($action == "ctitle")
		{
			$sql = "update characters set title = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change Title.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "xplvl")
		{
			$number = intval($number);
			if ($number < 0)
			{	$number = 0;	}
			if ($number > 85)
			{	$number = 85;	}
			$exp = $xplevels[$number];
			$sql = "update characters set level = '$number', exp = '$exp' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change xp.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "splvl")
		{
			$number = intval($number);
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update characters set sp = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change sp.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "dplvl")
		{
			$number = intval($number);
			if ($number < 0)
			{	$number = 0;	}
			$sql = "update characters set death_penalty_level = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change sp.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}
		
		if ($action == "vk")
		{
			$number = intval($number);
			$sql = "update characters set varka_ketra_ally = '$number' where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change Varka Ketra.<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "nobyes")
		{
			$number = intval($number);
			$sql = "update characters set nobless = 1 where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change Nobless<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "nobno")
		{
			$number = intval($number);
			$sql = "update characters set nobless = 0 where char_name = '$charname'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change Nobless<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "heroyes")
		{
			$number = intval($number);
			$sql = "update heroes set played = 1 where char_name = '$charname' and class_id = $subclass";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change Hero<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "herono")
		{
			$number = intval($number);
			$sql = "update heroes set played = 0 where char_name = '$charname' and class_id = $subclass";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change Hero<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "herocc")
		{
			$number = intval($number);
			$sql = "update heroes set `count` = $number where char_name = '$charname' and class_id = $subclass";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't change Hero<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "herodelete")
		{
			$sql = "delete from heroes where char_name = '$charname' and class_id = $subclass";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't delete Hero<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "makehero")
		{
			$sql = "insert into heroes (charId, char_name, class_id, count, played) values('$charnum', '$charname', '$subclass', '0', '0')";
			$result = mysql_query($sql,$con);
			if (!$result)
			{	echo "<h2 class=\"dropmain\">Database error - Couldn't create Hero<br>" . mysql_error() . "</h2>";	}
			else
			{	echo "<h2 class=\"dropmain\">Change completed.</h2>";	}
			$action = "";
		}

		if ($action == "")
		{
			$sql = "select charId, death_penalty_level, accesslevel, level, sp, karma, pvpkills, pkkills, clanid, title, nobless, subpledge, lvl_joined_academy, apprentice, sponsor, varka_ketra_ally from characters where char_name = '$charname'";
			$result = mysql_query($sql,$con);

			if (!$result)
			{	die('Could not retrieve user account from l2jdb table: ' . mysql_error());	}
			$char_id = mysql_result($result,0,"charId");
			echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td>";
			echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\">ID - $char_id</p></td></tr></table></table><p class=\"dropmain\">&nbsp;</p>";
			echo "<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\"><strong class=\"dropmain\">Account level - ";
			
			$char_lvl = mysql_result($result,0,"accesslevel");
			echo "$char_lvl</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"newlvl\"><select name=\"number\" OnChange=\"submit()\">";
//			<input name=\"number\" type=\"text\" value=\"$char_lvl\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- New Access Level\" type=\"submit\" class=\"bigbut2\"></td>
			if ($char_lvl == -1)
			{	echo "<option value=\"-1\" selected>-1 - Banned</option>";	}
			else
			{	echo "<option value=\"-1\">-1 - Banned</option>";	}
			if ($char_lvl == 0)
			{	echo "<option value=\"0\" selected>0 - Player</option>";	}
			else
			{	echo "<option value=\"0\">0 - Player</option>";	}
			$sql = "select accessLevel, name from access_levels order by accessLevel";
			$result2 = mysql_query($sql,$con);
			while ($r_array = mysql_fetch_assoc($result2))
			{	
				$access_lvl = $r_array['accessLevel'];
				$access_title = $r_array['name'];
				if ($char_lvl == $access_lvl)
				{	echo "<option value=\"$access_lvl\" selected>$access_lvl - $access_title</option>";	}
				else
				{	echo "<option value=\"$access_lvl\">$access_lvl - $access_title</option>";	}
			}
			echo "</select></form></tr></table></center>";
			echo "</td><td class=\"noborderback\">";
			
			$exp_lvl = mysql_result($result,0,"level");
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">XP level - ";
			echo "$exp_lvl</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"xplvl\"><input name=\"number\" type=\"text\" value=\"$exp_lvl\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><input value=\"<- New Level (0-85)\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "</td><td class=\"noborderback\">";
			$sp_lvl = mysql_result($result,0,"sp");
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">SP level - ";
			echo "$sp_lvl</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"splvl\"><input name=\"number\" type=\"text\" value=\"$sp_lvl\" maxlength=\"12\" size=\"12\"></td><td class=\"blanktab\"><input value=\"<- New SP Level\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "</td></tr></table></center>";
			echo "<p>&nbsp</p>";

			echo "<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_karma - ";
			$char_karma = mysql_result($result,0,"karma");
			echo "$char_karma</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"karma\"><input name=\"number\" type=\"text\" value=\"$char_karma\" maxlength=\"3\" size=\"4\"></td><td class=\"blanktab\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "</td><td class=\"noborderback\">";
			$char_pvp = mysql_result($result,0,"pvpkills");
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">PVP Kills - ";
			echo "$char_pvp</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"pvp\"><input name=\"number\" type=\"text\" value=\"$char_pvp\" maxlength=\"9\" size=\"9\"></td><td class=\"blanktab\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "</td><td class=\"noborderback\">";
			$char_pk = mysql_result($result,0,"pkkills");
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">PK Kills - ";
			echo "$char_pk</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"pk\"><input name=\"number\" type=\"text\" value=\"$char_pk\" maxlength=\"9\" size=\"9\"></td><td class=\"blanktab\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "</td></tr></table></center>";
			echo "<p>&nbsp</p>";

			echo "<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\"><font color=$green_code>Ketra(+)</font> <font color=$red_code>Varka(-)</font> = ";
			$char_vk = mysql_result($result,0,"varka_ketra_ally");
			if ($char_vk > 0)
			{	echo "<strong><font color=$green_code>$char_vk</font></strong>";	}
			elseif ($char_vk < 0)
			{	echo "<strong><font color=$red_code>$char_vk</font></strong>";	}
			else
			echo "$char_vk";
			echo "</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"vk\"><input name=\"number\" type=\"text\" value=\"$char_vk\" maxlength=\"3\" size=\"4\"></td><td class=\"blanktab\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "</td><td class=\"noborderback\">";
			$char_nobless = mysql_result($result,0,"nobless");
			if ($char_nobless)
			{	$char_nob = "<font color=$green_code>Yes</font>";	}
			else
			{	$char_nob = "<font color=$red_code>No</font>";	}
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Nobless - ";
			echo "$char_nob</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"nobyes\"><input name=\"number\" type=\"hidden\" value=\"1\"><input value=\"Yes\" type=\"submit\" class=\"bigbut2\"></td></form>
			<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"nobno\"><input name=\"number\" type=\"hidden\" value=\"1\"><input value=\"No\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "</td><td class=\"noborderback\">";
			
			echo "<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">";
			$ctitle = mysql_result($result,0,"title");
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\"><strong class=\"dropmain\">Title - ";
			echo "$ctitle</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"ctitle\"><input name=\"number\" type=\"text\" value=\"$ctitle\" maxlength=\"30\" size=\"30\"></td><td class=\"blanktab\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</td></tr></table></center></td></tr></table>";

			echo "</td></tr></table></center>";

			echo "<p>&nbsp</p>";
			
			echo "<a name=\"hero\"></a><center><table width=\"100%\" class=\"blanktab\"><tr><td><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\">Subclass</p></td><td class=\"lefthead\" colspan=\"2\"><p class=\"dropmain\">Count</p></td><td class=\"lefthead\" colspan=\"3\"><p class=\"dropmain\">Current?</p></td><td class=\"lefthead\">&nbsp;</td></tr>";	
			$sql = "select base_class, classid from characters where charId = '$charnum'";
			$result2 = mysql_query($sql,$con);
			$c_baseclass = mysql_result($result2,0,"base_class");
			$c_curclass = mysql_result($result2,0,"classid");
			$sql = "select class_id, class_index from character_subclasses where charId = '$charnum'";
			
			$result2 = mysql_query($sql,$con);
			$subclass_list = ARRAY(0);
			while ($r_array = mysql_fetch_assoc($result2))
			{	array_push($subclass_list, $r_array['class_index']);	}
			for ($i=0; $i < count($subclass_list); $i++)
			{
				$t_class = $subclass_list[$i];
				if ($t_class == 0)
				{
					$sql = "select class_name from class_list where id = $c_baseclass";
					$result3 = mysql_query($sql,$con);
					$cl_name = mysql_result($result3,0,"class_name");
				}
				else
				{
					$sql = "select class_id from character_subclasses where charId = '$char_id' and class_index = $t_class";
					$result3 = mysql_query($sql,$con);
					$cl_id = mysql_result($result3,0,"class_id");
					$sql = "select class_name from class_list where id = $cl_id";
					$result3 = mysql_query($sql,$con);
					$cl_name = mysql_result($result3,0,"class_name");
				}
				$sql = "select `count`, played from heroes where charId = '$char_id' and class_id = $t_class";
				$result3 = mysql_query($sql,$con);
				$found = 0;
				while ($r_array = mysql_fetch_assoc($result3))
				{	
					$char_count = $r_array['count'];
					$char_played = $r_array['played'];
					
					if ($char_played)
					{	$char_hero = "<strong><font color=$green_code>Yes</font></strong>";	}
					else
					{	$char_hero = "<font color=$red_code>No</font>";	}
					if (!$title_up)
					{	
						
						$title_up = 1;
					}
					echo "<tr><td class=\"blanktab\"><p class=\"dropmain\">$t_class</p></td><td><p class=\"dropmain\">$cl_name</p></td>";
					echo "<form method=\"post\" action=\"char-change.php#hero\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"subclass\" type=\"hidden\" value=\"$t_class\"><input name=\"action\" type=\"hidden\" value=\"herocc\"><input name=\"number\" type=\"text\" value=\"$char_count\" maxlength=\"3\" size=\"3\"></td><td class=\"blanktab\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></td></form>";
					echo "<td class=\"blanktab\">$char_hero</td>";
					echo "<form method=\"post\" action=\"char-change.php#hero\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"subclass\" type=\"hidden\" value=\"$t_class\"><input name=\"action\" type=\"hidden\" value=\"heroyes\"><input name=\"number\" type=\"hidden\" value=\"1\"><input value=\"Yes\" type=\"submit\" class=\"bigbut2\"></td></form>
					<form method=\"post\" action=\"char-change.php#hero\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"subclass\" type=\"hidden\" value=\"$t_class\"><input name=\"action\" type=\"hidden\" value=\"herono\"><input name=\"number\" type=\"hidden\" value=\"1\"><input value=\"No\" type=\"submit\" class=\"bigbut2\"></td></form>";
					echo "<form method=\"post\" action=\"char-change.php#hero\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"subclass\" type=\"hidden\" value=\"$t_class\"><input name=\"action\" type=\"hidden\" value=\"herodelete\"><input value=\"Delete\" type=\"submit\" class=\"bigbut2\"></td></form>";
					echo "</tr>";
					$found = 1;
				}
				if (!$found)
				{
					echo "<tr><td><p class=\"dropmain\">$t_class</p></td><td><p class=\"dropmain\">$cl_name</p></td>";
					echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\" colspan=\"6\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"subclass\" type=\"hidden\" value=\"$t_class\"><input name=\"action\" type=\"hidden\" value=\"makehero\"><input value=\"Make Class A Hero\" type=\"submit\" class=\"bigbut2\"></td></form>";
					echo "</tr>";
				}
			} 
			
			echo "</table></center></td><td>&nbsp;</td><td><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
			$char_dp = mysql_result($result,0,"death_penalty_level");
			echo "<td class=\"lefthead\" colspan=\"2\">Death&nbsp;Penalty&nbsp;-&nbsp;$char_dp</strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"char-change.php\"><td class=\"blanktab\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"dplvl\"><input name=\"number\" type=\"text\" value=\"$char_dp\" maxlength=\"3\" size=\"4\"></td><td class=\"blanktab\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</td>";
			echo "</table></center></td></tr></table></center><p>&nbsp</p>\n";
				
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><center>";
			echo "<form method=\"post\" action=\"char-change.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$char_id\"><input name=\"charname\" type=\"hidden\" value=\"$charname\"><input name=\"action\" type=\"hidden\" value=\"changeacc\"><input value=\"... Change Owning Account ...\" type=\"submit\" class=\"bigbut2\"><br>
			<input name=\"newown\" type=\"text\" value=\" \" maxlength=\"20\" size=\"20\"></form>";
			echo "</center></td></tr></table></center>";
			echo "<p>&nbsp</p>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			echo "<form method=\"post\" action=\"javascript:popit('delete.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charname=$charname','470','180');\"><td class=\"blanktab\"><input value=\"Delete Character\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
		}
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
