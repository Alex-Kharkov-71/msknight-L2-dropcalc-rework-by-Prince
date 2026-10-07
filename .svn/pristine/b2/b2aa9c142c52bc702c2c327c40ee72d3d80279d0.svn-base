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

$xplevels = array(0, 1, 69, 364, 1169, 2885, 6039, 11288, 19424, 31379, 48230, 71203, 101678, 141194, 191455, 254331, 331868, 426289, 540001, 675597, 835863, 1023785, 1242547, 1495544, 1786380, 2118877, 2497078, 2925251, 3407898, 3949755, 4555797, 5231247, 5981577, 6812514, 7730045, 8740423, 9850167, 11066073, 12395216, 13844952, 15422930, 17137088, 18995666, 21007204, 23180551, 25524869, 28049636, 30764655, 33680053, 36806290, 40154163, 45525134, 51262491, 57383989, 63907912, 70853090, 80700832, 91162655, 102265882, 114038596, 126509653, 146308200, 167244337, 189364894, 212717908, 237352644, 271975263, 308443198, 346827154, 387199547, 429634523, 474207979, 532694979, 606322775, 696381369, 804225364, 931275364, 1151264834, 1511257834, 2099305234, 4200000000, 6300000000, 8820000000, 11844000000, 15472800000, 19827360000, 25313999999);

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

// Print top of page
	echo "<p class=\"dropmain\">&nbsp;</p>
			<center>
	<table cellpadding=\"20\" class=\"dropmain\">
	<tr>
	<td class=\"dropmain\">
			<p class=\"dropmain\">&nbsp;</p>
			<h2 class=\"dropmainblack\">Grade Changes</h2>
			<center><table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\"><p class=\"left\">No grade 0-19</p></td>
			<td class=\"noborderback\"><p class=\"left\">D - 20-39</p></td>
			<td class=\"noborderback\"><p class=\"left\">C - 40-51</p></td>
			<td class=\"noborderback\"><p class=\"left\">B - 52-60</p></td>
			<td class=\"noborderback\"><p class=\"left\">A - 61-75</p></td>
			<td class=\"noborderback\"><p class=\"left\">S - 76-79</p></td>
			<td class=\"noborderback\"><p class=\"left\">S80 - 80-83</p></td>
			<td class=\"noborderback\"><p class=\"left\">S84 - 84-86</p></td>
			</tr></table></center>
			<p class=\"dropmain\">&nbsp;</p>
			<h2 class=\"dropmainblack\">Character XP levels</h2>
			<table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\">
			<center><table cellpadding=\"4\"  cellspacing=\"0\" class=\"dropmain\">
			<tr><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Lvl</strong></p></td><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">XP</strong></p></td></tr>";

	$i=0;
	while ($i < 100)
	{
		$xp = comaise($xplevels[$i]);
		echo "<tr><td class=\"dropmain\"><p class=\"left\">$i</p></td><td class=\"dropmain\"><p class=\"left\">$xp</p></td></tr>";
		$i++;
		if (($i == 20) || ($i == 40) || ($i == 60) || ($i == 80))
		{
			echo "</table></center></td><td class=\"noborderback\"><center><table cellpadding=\"4\"  cellspacing=\"0\" class=\"dropmain\">
			<tr><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Lvl</strong></p></td><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">XP</strong></p></td></tr>";
		}
	}
	echo "</table></center></td></tr></table>		
			<p class=\"dropmain\">&nbsp;</p>
		
	</td>
	</tr>
	</table></center>
	";

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>