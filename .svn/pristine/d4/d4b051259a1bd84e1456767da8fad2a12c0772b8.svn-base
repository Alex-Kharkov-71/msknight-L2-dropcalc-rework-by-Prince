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
include('playermap.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$itemname = input_check($_REQUEST['itemname'],0);
$rec_id = input_check($_REQUEST['recid'],0);
$orderby = input_check($_REQUEST['orderby'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
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
	if ($orderby == 1)
	{	$orderby = 'rec_name DESC';	}
	elseif ($orderby == 2)
	{	$orderby = 'rec_name';	}
	elseif ($orderby == 3)
	{	$orderby = 'chance DESC, rec_name';	}
	elseif ($orderby == 4)
	{	$orderby = 'chance, rec_name';	}
	elseif ($orderby == 5)
	{	$orderby = 'level DESC, rec_name';	}
	elseif ($orderby == 6)
	{	$orderby = 'level, rec_name';	}
	else
	{	$orderby = 'rec_name';	}


	$sql = "select name, skill_id from knightskills where name like '%$itemname%' order by name";
	$skill_result = mysql_query($sql,$con);
	$skill_count = mysql_num_rows($skill_result);
	$notsplit = 1;
	if ($skill_count)
	{
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\" width=\"100%\"><tr><td valign=\"top\" class=\"noborder\">";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
		echo "<td class=\"lefthead\"><p class=\"dropmain\">ID</p></td>";
		echo "<td class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td>";
		echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td></tr>";
	
		$i = 0;
		while ($i < $skill_count)
		{
			$skill_id = mysql_result($skill_result,$i,"skill_id");
			$skill_name = mysql_result($skill_result,$i,"name");
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$skill_id</p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$skill_name</p></td>";
			$skill_id2 = item_check(1, $skill_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
			if (strlen($skill_id2) == 1) {$zcount = '000';}
			if (strlen($skill_id2) == 2) {$zcount = '00';}
			if (strlen($skill_id2) == 3) {$zcount = '0';}
			if (strlen($skill_id2) == 4) {$zcount = '';}
			echo "<td class=\"dropmain\"><p class=\"dropmain\"><img src=\"" . $images_dir . "skills/skill" . $zcount . $skill_id2 . ".gif\"></p></td></tr>";
			$i++;
			if (($notsplit) && ($i > ($skill_count / 2)))
			{
				echo "</td></tr></table></center><td valign=\"top\" class=\"noborder\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
				echo "<td class=\"lefthead\"><p class=\"dropmain\">ID</p></td>";
				echo "<td class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td>";
				echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td></tr>";
				$notsplit = 0;
			}
			
		}
		echo "</table></center></td></tr></table></center>";
	}
	else
	{
		writewarn("Sorry, no skills match $itemname");
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>