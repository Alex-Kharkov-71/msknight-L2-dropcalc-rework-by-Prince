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
$pet = input_check($_REQUEST['pet'],0);
$pettype = input_check($_REQUEST['pettype'],0);
$action = input_check($_REQUEST['action'],0);
$character = input_check($_REQUEST['character'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
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
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Could not change to L2J database: ' . mysql_error());
	}


	if ($user_access_lvl < $sec_giveandtake)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
		if ($action == "delete")
		{
			$sql = "delete from pets where item_obj_id = '$pet'";
			$result = mysql_query($sql,$con);
			$sql = "delete from items where object_id = '$pet'";
			$result = mysql_query($sql,$con);
		}

		if ($action == "rename")
		{
			$sql = "update pets set name = null where item_obj_id = '$pet'";
			$result = mysql_query($sql,$con);
		}

		if ($action == "reunite3")
		{

			$sql = "select object_id from items where object_id = '$pet'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if ($count)
			{
				$sql = "select object_id from items order by object_id desc limit 1";
				$result = mysql_query($sql,$con);
				$last_num = mysql_result($result,0,"object_id") + 1;
				$sql = "update pets set item_obj_id = '$last_num' where item_obj_Id = '$pet'";
				$result = mysql_query($sql,$con);
				$pet = $last_num;
			}
			$caller = 6648;			// Assume buffalo pipe unless ...
			if ($pettype == "baby kookaburra")		// baby kookaburra calls
			{	$caller = 6650;	}			// kookaburra chime
			elseif ($pettype == "wyvern")		// wyvern calls
			{	$caller = 8663;	}			// Penitents Manacles
			elseif ($pettype == "baby cougar")		// baby cougar calls
			{	$caller = 6649;	}			// baby cougar chime
			elseif ($pettype == "wolf")
			{	$caller = 2375;	}
			elseif ($pettype == "strider_of_twilight")
			{	$caller = 4424;	}
			elseif ($pettype == "strider_of_star")
			{	$caller = 4423;	}
			elseif ($pettype == "strider_of_wind")
			{	$caller = 4422;	}
			elseif ($pettype == "hatchling_of_twilight")
			{	$caller = 3502;	}
			elseif ($pettype == "hatchling_of_star")
			{	$caller = 3501;	}
			elseif ($pettype == "hatchling_of_wind")
		{	$caller = 3500;	}
			elseif ($pettype == "Black_wolf")
			{	$caller = 9882;	}
			elseif ($pettype == "Fenrir")
			{	$caller = 10426; }
			elseif ($pettype == "great_wolf")
			{	$caller = 10163; }
			elseif ($pettype == "Improved Baby buffalo")
			{	$caller = 10311; }
			elseif ($pettype == "Improved Baby cougar")
			{	$caller = 10312; }
			elseif ($pettype == "Improved Baby kookaburra")
			{	$caller = 10313; }
			elseif ($pettype == "WFenrir")
			{	$caller = 10611; }
			elseif ($pettype == "WGreat_Wolf")
			{	$caller = 10307; }
			
			$sql = "insert into items (owner_id, object_id, item_id, count, enchant_level, loc, loc_data) values ('$character', '$pet', '$caller', '1', '0', 'INVENTORY', '0')";
			$result = mysql_query($sql,$con);
			echo "<center><table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			echo "<p class=\"dropmain\"><strong class=\"dropmain\">Pet $pet given, caller in inventory.<br>User will have to re-log</strong></p>";
			echo "</td></tr></table></center>";
		}
		if ($action == "reunite2")
		{
			$sql = "select char_name, charId from characters order by char_name";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\"Link pet to which character ...</h2><p class=\"dropmain\">&nbsp;</p>";
			$i=0;
			$col_check = 0;
			echo "<center><table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			while ($i < $count)
			{
				$char_name = mysql_result($result,$i,"char_name");
				$char_id = mysql_result($result,$i,"charId");
				echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"pets.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=reunite3&pet=$pet&pettype=$pettype&character=$char_id\" class=\"dropmain\">$char_name</a></p></td>";
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
			echo "</tr></table></center>";
			wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
			return 0;
		}

		if ($action == "reunite")
		{
			$sql = "select type from pets_stats where level = 1";
			$result = mysql_query($sql,$con);
			$pet_count = mysql_num_rows($result);
			echo "<p class=\"dropmain\">&nbsp;</p><p class=\"dropmain\">&nbsp;</p><center>
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"noborderback\">";
			echo "<select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"\">- Select Pet Type -</option>";
			$i=0;
			while ($i < $pet_count)
			{
				$c_var = mysql_result($result,$i,"type");
	    			echo "<option value=\"pets.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=reunite2&pet=$pet&pettype=$c_var\">$c_var</option>";
				$i++;
  			}
			echo "</select></td></tr></table></center>";
			wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
			return 0;
		}

		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" class=\"blanktab\"><tr><td width=\"50%\" class=\"noborder\" valign=\"top\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\">Owner</p></td><td class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Pet</p></td><td class=\"drophead\"><p class=\"dropmain\">Level</p></td><td class=\"drophead\"><p class=\"dropmain\">Del</p></td><td class=\"drophead\"><p class=\"dropmain\">$lang_erasename</p></td></tr>";
		$sql = "select item_obj_id, name, level from pets order by level";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		$trigger = intval($count / 2) - 1;
		$i=0;
		while ($i < $count)
		{
			$pet_owner = mysql_result($result,$i,"item_obj_id");
			$pet_name = mysql_result($result,$i,"name");
			$pet_level = mysql_result($result,$i,"level");
			if (!$pet_name)
			{	$pet_name = "$lang_unknown";	}

			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\"><strong class=\"dropmain\">";
			$owner_name = "";
			$pet_type = "";
			$pet_style = "&nbsp;";
			$sql = "select owner_id, item_id FROM items WHERE object_id = '$pet_owner'";
			$result2 = mysql_query($sql,$con);
			$count2 = mysql_num_rows($result2);
			if (!$count2)
			{	$owner_name = "<a href=\"pets.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=reunite&pet=$pet_owner\" class=\"dropmain\">Reunite</a>";	}
			else
			{
				$pet_type = mysql_result($result2,0,"item_id");
				$pet_style = "$lang_unknown";
				if ($pet_type == "6648")
				{	$pet_style = "Buffalo";	}
				elseif ($pet_type == "6650")
				{	$pet_style = "Kookaburra";	}
				elseif ($pet_type == "8663")
				{	$pet_style = "Wyvern";	}
				elseif ($pet_type == "6649")
				{	$pet_style = "Cougar";	}
				elseif ($pet_type == "2375")
				{	$pet_style = "Wolf";	}
				elseif ($pet_type == "4424")
				{	$pet_style = "Strider&nbsp;of&nbsp;Twilight";	}
				elseif ($pet_type == "4423")
				{	$pet_style = "Strider&nbsp;of&nbsp;Star";	}
				elseif ($pet_type == "4422")
				{	$pet_style = "Strider&nbsp;of&nbsp;Wind";	}
				elseif ($pet_type == "3502")
				{	$pet_style = "Hatchling&nbsp;of&nbsp;Twilight";	}
				elseif ($pet_type == "3501")
				{	$pet_style = "Hatchling&nbsp;of&nbsp;Star";	}
				elseif ($pet_type == "3500")
				{	$pet_style = "Hatchling&nbsp;of&nbsp;Wind";	}
				elseif ($pet_type == "9882")
				{	$pet_style = "Black&nbsp;Wolf";	}
				elseif ($pet_type == "10426")
				{	$pet_style = "Fenrir";	}
				elseif ($pet_type == "10163")
				{	$pet_style = "Great&nbsp;Wolf";	}
				elseif ($pet_type == "10311")
				{	$pet_style = "Improved&nbsp;Baby&nbsp;Buffalo";	}
				elseif ($pet_type == "10312")
				{	$pet_style = "Improved&nbsp;Baby&nbsp;Cougar";	}
				elseif ($pet_type == "10313")
				{	$pet_style = "Improved&nbsp;Baby&nbsp;Kookaburra";	}
				elseif ($pet_type == "10611")
				{	$pet_style = "White&nbsp;Fenrir";	}
				elseif ($pet_type == "10307")
				{	$pet_style = "White&nbsp;Great&nbsp;Wolf";	}
				
				$owner_id = mysql_result($result2,0,"owner_id");
				$sql = "select char_name FROM characters WHERE charId = '$owner_id'";
				$result2 = mysql_query($sql,$con);
				$count2 = mysql_num_rows($result2);
				if (!$count2)
				{
					$sql = "select clan_name FROM clan_data WHERE clan_id = '$owner_id'";
					$result2 = mysql_query($sql,$con);
					$count2 = mysql_num_rows($result2);
					if (!$count2)
					{
						$owner_name = "unknown owner";	
					}
					else
					{	$owner_name = mysql_result($result2,0,"clan_name") . " (CW)";	}
				}
				else
				{	$owner_name = mysql_result($result2,0,"char_name");	}
			
			}

			echo "$owner_name</strong></p></td><td class=\"dropmain\"><p class=\"dropmain\">$pet_name</p></td><td class=\"dropmain\"><p class=\"dropmain\">$pet_style</p></td><td class=\"dropmain\"><p class=\"dropmain\">";
			echo "<a href=\"javascript:popit('givepskill.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&pet=$pet_owner&ptype=$pet_type','400','200');\" class=\"dropmain\">$pet_level</a>";
			echo "</p></td><td class=\"dropmain\"><p class=\"dropmain\"><a href=\"pets.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=delete&pet=$pet_owner\" class=\"dropmain\">Del</a></p></td><td class=\"dropmain\"><p class=\"dropmain\"><a href=\"pets.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=rename&pet=$pet_owner\" class=\"dropmain\">$lang_name</a></p></td></tr>";
			if ($i == $trigger)
			{	echo "</table></td><td width=\"50%\" class=\"noborder\" valign=\"top\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\">Owner</p></td><td class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Pet</p></td><td class=\"drophead\"><p class=\"dropmain\">Level</p></td><td class=\"drophead\"><p class=\"dropmain\">Del</p></td><td class=\"drophead\"><p class=\"dropmain\">$lang_erasename</p></td></tr>";	}
			$i++;
		}
		echo "</table></center></td></tr></table>";
	}



}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>