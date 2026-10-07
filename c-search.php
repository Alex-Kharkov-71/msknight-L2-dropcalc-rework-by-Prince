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
$itemid = input_check($_REQUEST['itemid'],0);
$itemsort = input_check($_REQUEST['itemsort'],0);
$charsort = input_check($_REQUEST['charsort'],0);
$spec_account = input_check($_REQUEST['charnum'],0);
$view_skills = input_check($_REQUEST['viewskills'],0);
$action = input_check($_REQUEST['action'],0);
$single_found = "0";

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
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

	// Try and find clans that are linked to the users account characters.  Compile a list of clans that all the users game
	// account is linked with.
	$clan_member_count = 0;
	if ((!$user_game_acc) && ($username != "guest"))
	{	echo "<h2 class=\"dropmain\">Warning - Drop calc doesn't know your game account</h2>"; }
	else
	{
		$sql = "select distinct clanid from characters where account_name = '$user_game_acc'";
		$result_clan = mysql_query($sql,$con);
		if (!$result_clan)
		{	echo "<h2 class=\"dropmain\">Warning - None of your characters are in clans</h2>"; }
		else
		{
			$count = mysql_num_rows($result_clan);
			$i = 0;
			while ($i < $count)
			{
				$clan_res = mysql_result($result_clan,$i,"clanid");
				if ($clan_res)
				{
					$char_clan_list[$clan_member_count] = $clan_res;
					$clan_member_count++;
				}
				$i++;
			}
		}
	}

	echo "<p class=\"dropmain\">&nbsp</p>";
	
	// If we are dealing with a spefic account, then we query for the one character account, otherwise we go for a wider sweep, sorted by name.
	if (!$charsort)
	{	$charsort = "char_name";	}
	if ((!$spec_account) && (strlen($itemname) < $minlenchar))
	{	
		writewarn("Please give at least $minlenchar characters.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	$clan_leader = 0;
	if ($spec_account)
	{
		$sql = "select charId, account_name, char_name, accesslevel, classid, clanid, level, sex, maxhp, curhp, maxcp, curcp, maxmp, curmp, sp, accesslevel, online, onlinetime, x, y, punish_level from characters where charId = $spec_account";
		$result = mysql_query($sql,$con);
		$count_accs = mysql_num_rows($result);
		$sql = "select COUNT(*) from clan_data where leader_id = '$spec_account'";
		$result2 = mysql_query($sql,$con);
		$clan_leader = mysql_result($result2,0,"COUNT(*)");
	}
	else
	{
		$sql = "select charId, account_name, race, char_name, accesslevel, classid, clanid, level, sex, maxhp, curhp, maxcp, curcp, maxmp, curmp, sp, accesslevel, online, onlinetime, x, y, punish_level from characters where char_name like '%$itemname%' order by $charsort";
		$result = mysql_query($sql,$con);
		$count_accs = mysql_num_rows($result);
		$single_found = "1";
	}
	// If we didn't find any characters that matched the search request ...
	if (!$count_accs)
	{
		writewarn("Sorry, no characters match $itemname");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	// If we found only one character, then treat it as a specific account search.
	if ($count_accs == 1)
	{	
		$spec_account = mysql_result($result,0,"charId");
		$single_found = "";
	}


	// If we are dealing with a specific account, then start the more complex table heading that provides the map area alongside.	
	if (!$spec_account)
	{	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" class=\"dropmain\"><tr>
			<td class=\"drophead\"><p class=\"left\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=char_name DESC&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Char&nbsp;Name</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>
			<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=online DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">On?</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=online, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
		if (($game_paranoia < 2) || ($user_access_lvl >= $sec_inc_gmlevel))
		{	echo "<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=level DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Lvl</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=level, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";	}
		echo "<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=race DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Race</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=race, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>
			<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=sex DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Sex</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=sex, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>
			<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=race DESC, classid DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_class</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=race, classid, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>
			<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=clanid DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_clan</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=clanid, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
		if (($game_paranoia < 2) || ($user_access_lvl >= $sec_inc_gmlevel))
		{	echo "<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=maxhp DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Hp</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=maxhp, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>
			<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=maxmp DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Mp</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=maxmp, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>
			<td class=\"drophead\"><p class=\"center\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=maxcp DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Cp</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=maxcp, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";	}
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{ echo "<td class=\"drophead\"><p class=\"dropmain\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=accesslevel DESC, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">AccLvl</strong><br><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charsort=accesslevel, char_name&itemname=$itemname\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>"; }  // Add the acess level info if user is an admin.
		echo "</tr>";
	}
	$i=0;  				// Traversing all characters found - will execute once for specific character searches.
	$single_clan_found = 0;
	while ($i < $count_accs)
	{
		// Pull out the character details.
		$c_num = mysql_result($result,$i,"charId");
		$c_accname = mysql_result($result,$i,"account_name");
		$c_name = mysql_result($result,$i,"char_name");
		$c_access = mysql_result($result,$i,"accesslevel");
		$c_class = mysql_result($result,$i,"classid");
		$c_clanid = mysql_result($result,$i,"clanid");
		$c_level = mysql_result($result,$i,"level");
		$c_sex = mysql_result($result,$i,"sex");
		$c_mhp = mysql_result($result,$i,"maxhp");
		$c_chp = mysql_result($result,$i,"curhp");
		$c_mmp = mysql_result($result,$i,"maxmp");
		$c_cmp = mysql_result($result,$i,"curmp");
		$c_mcp = mysql_result($result,$i,"maxcp");
		$c_ccp = mysql_result($result,$i,"curcp");
		$c_sp = comaise(mysql_result($result,$i,"sp"));
		$c_alvl = mysql_result($result,$i,"accesslevel");
		$c_jailed = mysql_result($result,$i,"punish_level");
		$c_online = mysql_result($result,$i,"online");
		$c_onlinetime = mysql_result($result,$i,"onlinetime");
		$char_x = mysql_result($result,$i,"x");
		$char_y = mysql_result($result,$i,"y");
		$c_race_n = "$lang_unknown";
		$c_class_n = "$lang_unknown";
		$c_class_s = "$lang_unknown";
		$sql = "select class_name from class_list where id = $c_class";
		$result_class = mysql_query($sql,$con);
		$class_count =  mysql_num_rows($result_class);
		if ($class_count >0)
		{	$c_class_s = mysql_result($result_class,0,"class_name");	}
		if (substr($c_class_s,0,2) == "H_")
		{
			$c_race_n = "$lang_human";
			$c_class_n = substr($c_class_s,2);
		}
		elseif (substr($c_class_s,0,2) == "E_")
		{
			$c_race_n = "$lang_elf";
			$c_class_n = substr($c_class_s,2);
		}
		elseif (substr($c_class_s,0,3) == "DE_")
		{
			$c_race_n = "$lang_delf";
			$c_class_n = substr($c_class_s,3);
		}
		elseif (substr($c_class_s,0,2) == "O_")
		{
			$c_race_n = "$lang_orc";
			$c_class_n = substr($c_class_s,2);
		}
		elseif (substr($c_class_s,0,2) == "D_")
		{
			$c_race_n = "$lang_dwarf";
			$c_class_n = substr($c_class_s,2);
		}
		elseif (substr($c_class_s,0,2) == "K_")
		{
			$c_race_n = "$lang_kamael";
			$c_class_n = substr($c_class_s,2);
		}
		if (!$c_clanid)			// Find clan name if member of a clan.
		{ $c_clan_n = "$lang_none"; }
		else
		{
			$sql = "select clan_id, clan_name from clan_data where clan_id = $c_clanid";
			$result_clan = mysql_query($sql,$con);
			if (!$result_clan)
			{	$c_clan_n = "$lang_unknown"; }
			else 
			{ $c_clan_n = mysql_result($result_clan,0,"clan_name"); }
		}


		if (($action=="trust") && ($user_access_lvl >= $adjust_trust))
		{
			$sql="insert into knighttrust (account_name,char_name,level,race,class) values ('$c_accname','$c_name',$c_level,'$c_race_n','$c_class_n')";
			$result_trust = mysql_query($sql,$con);
		}
		if (($action=="untrust") && ($user_access_lvl >= $adjust_trust))
		{
			$sql="delete from knighttrust where account_name = '$c_accname' and char_name = '$c_name'";
			$result_trust = mysql_query($sql,$con);
		}
		if (($action == "teleport") && ($user_access_lvl >= $sec_inc_gmlevel))
		{
			$sql = "update characters set x=82610, y=148298, z=-3469 where char_name='$c_name'";
			$result6 = mysql_query($sql,$con);
			$char_x = 82610;
			$char_y = 148298;
		}
		if (($action == "wipeinvent2") && ($user_access_lvl >= $sec_giveandtake))
		{
			$sql = "delete from items where owner_id='$c_num' and loc='INVENTORY' and item_id <> 57";
			$result6 = mysql_query($sql,$con);
		}
		if (($action == "wipeinvent") && ($user_access_lvl >= $sec_giveandtake))
			{	echo "<br><center><form method=\"post\" action=\"c-search.php\"><input value=\" <-- CONFIRM INVENTORY WIPE --> \" type=\"submit\" class=\"bigbut2\"><input name=\"action\" type=\"hidden\" value=\"wipeinvent2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"></form>";
				echo "</center>";
			}
		if (($action == "wipewarehouse2") && ($user_access_lvl >= $sec_giveandtake))
		{
			$sql = "delete from items where owner_id='$c_num' and (loc='WAREHOUSE' or loc='FREIGHT')";
			$result6 = mysql_query($sql,$con);
		}
		if (($action == "wipewarehouse") && ($user_access_lvl >= $sec_giveandtake))
			{	echo "<br><center><form method=\"post\" action=\"c-search.php\"><input value=\" <-- CONFIRM WAREHOUSE WIPE --> \" type=\"submit\" class=\"bigbut2\"><input name=\"action\" type=\"hidden\" value=\"wipewarehouse2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"></form>";
				echo "</center>";
			}
		if (($action == "teleporte") && ($emergency_teleport) && (!$c_online) && ($c_accname == $username))
		{
			$sql = "";
			if ($c_race_n == "$lang_human")
			{
				$sql = "update characters set x=-84298, y=244587, z=-3725 where char_name='$c_name'";
				$char_x = -84298;
				$char_y = 244587;
			}
			elseif ($c_race_n == "$lang_elf")
			{
				$sql = "update characters set x=46944, y=51536, z=-2972 where char_name='$c_name'";
				$char_x = 46944;
				$char_y = 51536;
			}
			elseif ($c_race_n == "$lang_delf")
			{
				$sql = "update characters set x=10499, y=17017, z=-4585 where char_name='$c_name'";
				$char_x = 9806;
				$char_y = 15667;
			}
			elseif ($c_race_n == "$lang_orc")
			{
				$sql = "update characters set x=-44866, y=-112328, z=-234 where char_name='$c_name'";
				$char_x = -44866;
				$char_y = -112328;
			}
			elseif ($c_race_n == "$lang_dwarf")
			{
				$sql = "update characters set x=115210, y=-178145, z=-919 where char_name='$c_name'";
				$char_x = 115210;
				$char_y = -178145;
			}
			elseif ($c_race_n == "$lang_kamael")
			{
				$sql = "update characters set x=-118076, y=46943, z=365 where char_name='$c_name'";
				$char_x = 115210;
				$char_y = -178145;
			}
			if ($sql)
			{	$result6 = mysql_query($sql,$con);	}
		}
		if (($action == "addskill") && ($user_access_lvl >= $sec_takeskill) && ($c_online < 1))
		{
			$sql = "select level from skill_trees where skill_id = $itemid order by level desc limit 1";
			$result6 = mysql_query($sql,$con);
			$mav_lvl = mysql_result($result6,0,"level");
			$sql = "update character_skills set skill_level = skill_level + 1 where charId = $spec_account and skill_id = $itemid and skill_level < $mav_lvl";
			$result6 = mysql_query($sql,$con);
		}
		if (($action == "delskill") && ($user_access_lvl >= $sec_takeskill) && ($c_online < 1))
		{
			$sql = "update character_skills set skill_level = skill_level - 1 where charId = $spec_account and skill_id = $itemid and skill_level > 1";
			$result6 = mysql_query($sql,$con);
		}		
		if (($action == "teleports") && ($user_access_lvl >= $sec_inc_gmlevel))
		{
			$sql = "";
			if ($c_race_n == "$lang_human")
			{
				$sql = "update characters set x=-84298, y=244587, z=-3725 where char_name='$c_name'";
				$char_x = -84298;
				$char_y = 244587;
			}
			elseif ($c_race_n == "$lang_elf")
			{
				$sql = "update characters set x=46944, y=51536, z=-2972 where char_name='$c_name'";
				$char_x = 46944;
				$char_y = 51536;
			}
			elseif ($c_race_n == "$lang_delf")
			{
				$sql = "update characters set x=10499, y=17017, z=-4585 where char_name='$c_name'";
				$char_x = 9806;
				$char_y = 15667;
			}
			elseif ($c_race_n == "$lang_orc")
			{
				$sql = "update characters set x=-44866, y=-112328, z=-234 where char_name='$c_name'";
				$char_x = -44866;
				$char_y = -112328;
			}
			elseif ($c_race_n == "$lang_dwarf")
			{
				$sql = "update characters set x=115210, y=-178145, z=-919 where char_name='$c_name'";
				$char_x = 115210;
				$char_y = -178145;
			}
			if ($sql)
			{	$result6 = mysql_query($sql,$con);	}
		}


		// Admin Menu System applied only if dealing with a specific account.
		if (($user_access_lvl >= $sec_inc_gmlevel) && ($spec_account))
		{
			echo "<center><table class=\"dropmain\"><tr><td class=\"noborder\" colspan=\"7\"><p class=\"center\"><strong class=\"dropmain\">Administration Tools</strong></p></td></tr><tr>";	
			if (!$c_online)
			{
				echo "<td class=\"noborder\"><center><form method=\"post\" action=\"c-search.php\"><input value=\" Teleport to Giran \" type=\"submit\" class=\"bigbut2\"><input name=\"action\" type=\"hidden\" value=\"teleport\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"></form>";
				echo "</center></td>";
			}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if ($user_access_lvl >= $sec_giveandtake)
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('giveitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name','400','300');\"><input value=\" Give Items \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}

			if ($user_access_lvl >= $adjust_trust)
			{
				$sql="select account_name from knighttrust WHERE account_name = '$c_accname' AND char_name = '$c_name'";
				$result_trust = mysql_query($sql,$con);
				$trusted = 0;
				while ($r_array = mysql_fetch_assoc($result_trust))
				{	$trusted = 1;	}
				if ($trusted)
				{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"c-search.php\"><input value=\" Untrust \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"itemid\" type=\"hidden\" value=\"$itemid\"><input name=\"itemname\" type=\"hidden\" value=\"$itemname\"><input name=\"itemsort\" type=\"hidden\" value=\"$itemsort\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"><input name=\"action\" type=\"hidden\" value=\"untrust\"></form>";	}
				else
				{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"c-search.php\"><input value=\" Trust \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"itemid\" type=\"hidden\" value=\"$itemid\"><input name=\"itemname\" type=\"hidden\" value=\"$itemname\"><input name=\"itemsort\" type=\"hidden\" value=\"$itemsort\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"><input name=\"action\" type=\"hidden\" value=\"trust\"></form>";	}
			}
			else
			{	echo "<td class=\"noborder\"></td>";	}			

			echo "<td class=\"noborder\"><center><form method=\"post\" action=\"playerchat.php\"><input value=\" View Chat \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"character\" type=\"hidden\" value=\"$c_name\"><input name=\"lastlines\" type=\"hidden\" value=\"1\"></form></td>";
			if (($c_online) && ($user_access_lvl >= $kick_player))
			{
				echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('kickp.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name','400','130');\"><input value=\" Kick Player \" type=\"submit\" class=\"bigbut2\"></form></td>";
			}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"c-search.php\"><input value=\" Wipe Inventory \" type=\"submit\" class=\"bigbut2\"><input name=\"action\" type=\"hidden\" value=\"wipeinvent\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"></form>";
				echo "</center></td>";
			}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if ($user_access_lvl >= $sec_inc_admin) 
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('givegm.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name&userid=$spec_account','400','200');\"><input value=\" Make a GM \" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}


			echo "</tr><tr>";
			if (!$c_online)
			{
				echo "<td class=\"noborder\" colspan=\"2\"><center><form method=\"post\" action=\"c-search.php\"><input value=\" Teleport to Home Town \" type=\"submit\" class=\"bigbut2\"><input name=\"action\" type=\"hidden\" value=\"teleports\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"></form>";
				echo "</center></td>";
			}
			else
			{	echo "<td class=\"noborder\" colspan=\"2\"></td>";	}

			if ((!$c_online) && ($user_access_lvl >= $sec_inc_admin))
			{
				echo "<td class=\"noborder\" colspan=\"2\"><center><form method=\"post\" action=\"char-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charname=$c_name&charnum=$spec_account\"><input value=\" Change Character \" type=\"submit\" class=\"bigbut2\"></form></td>";
			}
			else
			{	echo "<td class=\"noborder\" colspan=\"2\"></td>";	}

			// To ban a player, a user needs to have kick player privileges.  Only an administrator, however, can ban a player
			// who is above normal game player status.
			if ((!$c_online) && (($user_access_lvl >= $sec_inc_admin) || (($c_access < 1) && ($user_access_lvl >= $kick_player))))
			{
				if ($c_access >= 0)
				{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('banp.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name','400','130');\"><input value=\" Ban Player \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
				elseif ($c_access == -1)
				{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('banp.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name&unban=1','400','130');\"><input value=\" Un-Ban Player \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
				else
				{	echo "<td class=\"noborder\"></td>";	}
			}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"c-search.php\"><input value=\" Wipe Warehouse \" type=\"submit\" class=\"bigbut2\"><input name=\"action\" type=\"hidden\" value=\"wipewarehouse\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"></form>";
				echo "</center></td>";
			}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if (($user_access_lvl >= $sec_takeskill) && (!$c_online))
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('giveskill.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name','400','200');\"><input value=\" Give Skill \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}


			echo "</tr><tr>";
			if ($user_access_lvl >= $sec_inc_admin) 
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"class-change.php#anchor\"><input value=\" Class Change \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"></form></center></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if ($user_access_lvl >= $sec_inc_admin) 
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"skills-change.php\"><input value=\" Change Class Skills \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"></form></center></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if ((!$c_online) && ($user_access_lvl >= $sec_inc_admin))
			{
				echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('export.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&character=$c_num&title=char_$c_name','100','50');\"><input value=\" Export \" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "</center></td>";
			}
			else
			{	echo "<td class=\"noborder\" colspan=\"2\"></td>";	}
			if ($c_jailed)
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('jail.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name&action=unjailp','400','200');\"><input value=\" Unjail \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
			elseif ($c_online)
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popit('jail.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$c_name','400','200');\"><input value=\" Jail \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if ($user_access_lvl >= $sec_giveandtake)
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popot('itemhist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=-1&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','600','500');\"><input value=\" Char Itm Hist \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if ($user_access_lvl >= $sec_giveandtake)
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popot('itemhist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=-2&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','600','500');\"><input value=\" Acc Itm Hist \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			if ($user_access_lvl >= $sec_giveandtake)
			{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"javascript:popot('itemhist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=-3&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','600','500');\"><input value=\" Ip Itm Hist \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
			else
			{	echo "<td class=\"noborder\"></td>";	}
			echo "<td class=\"noborder\"></td>";
			echo "</tr></table></center>";
		}
		

		// If dealing with a specific account, then display the first table headers.
		if ($spec_account)
		{	
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"80%\" class=\"blanktab\"><tr><td class=\"noborder\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Char&nbsp;Name</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">On?</strong></p></td>";
			if (($game_paranoia == 2) && ($user_access_lvl < $sec_inc_gmlevel))
			{	}
			else
			{	echo "<td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Lvl</strong></p></td>";	}
			echo "<td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Race</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Sex</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">$lang_class</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">$lang_clan</strong></p></td></tr>";
		}

		echo "<tr><td class=\"dropmain\"><p class=\"left\">";

		if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
		{
			echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&charnum=$c_num\" class=\"dropmain\">$c_name</a>";	
			$single_clan_found = 1;
		}
		elseif (($user_access_lvl < $sec_inc_gmlevel) && (($clan_member_count) || ($c_accname == $user_game_acc))) // If user is not admin, but is a member of clans, show links where is a member.
		{
			if ($c_accname == $user_game_acc)
			{	$found = 1;	}
			else
			{	$found = 0;	}
			$i3 = 0;
			while ($i3 < $clan_member_count)
			{
					if ($c_clanid == $char_clan_list[$i3])
				{	$found = 1;	}
				$i3++;
			}
			if ($found)
			{
				echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&charnum=$c_num\" class=\"dropmain\">$c_name</a>";	
				$single_clan_found = 1;
			}
			else 
			{	echo "$c_name";	}
		}
		else
		{	echo "$c_name";	}
		if (($user_access_lvl >= $sec_inc_gmlevel) && ($show_char_time))  // If user is a GM, always show the character link.
		{	$onlinetime = onlinetime($c_onlinetime);
			echo "&nbsp;<small><font color=\"$white_code\">($onlinetime)</font></small>";	
		}
		echo "</p></td><td class=\"dropmain\"><p class=\"center\">";  // On line green if user is currently on line.
		if ($c_access < 0)
		{	echo "<font color=$red_code>Banned</font>";	}
		elseif ($c_online)
		{ echo "<font color=$green_code>Yes"; }
		else
		{ echo "<font color=$red_code>No"; }
		echo "</font></p></td>";
		if (($game_paranoia == 2) && ($user_access_lvl < $sec_inc_gmlevel))
		{	}
		else
		{	echo "<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\"><font color=$green_code>$c_level</font></strong></p></td>";	}
		echo "<td class=\"dropmain\"><p class=\"center\">$c_race_n</p></td><td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">";
		if ($c_sex)
		{	echo "<img src=\"" . $images_dir . "female.gif\" width=\"10\" height=\"14\" border=\"0\">"; }
		else
		{	echo "<img src=\"" . $images_dir . "male.gif\" width=\"10\" height=\"14\" border=\"0\">"; }
		echo "</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_class_n</p></td><td class=\"dropmain\"><p class=\"center\">";
		if ($c_clan_n == "$lang_none")
		{ echo "$c_clan_n"; }
		else
		{
			if ((!$clan_member_count) && ($user_access_lvl < $sec_inc_gmlevel))	// If user not member of clans, just show the name
			{	echo "$c_clan_n";	}
			if (($user_access_lvl >= $sec_inc_gmlevel) && ($c_clanid > 0))  // If user is a GM, always show the character link.
			{	echo "<a href=\"cl-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&clannum=$c_clanid\" class=\"dropmain\">$c_clan_n</a>";	}
			if (($user_access_lvl < $sec_inc_gmlevel) && ($clan_member_count)) // If user is not admin, but is a member of clans, show links where is a member.
			{
				if ($found)
				{ echo "<a href=\"cl-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&clannum=$c_clanid\" class=\"dropmain\">$c_clan_n</a>";	}
				else 
				{	echo "$c_clan_n";	}
			}
		}
		echo "</p></td>";

		// If dealing with a specific account, then split the tables so that the other half of the information comes on a second table.
		if ($spec_account)
		{	echo "</tr></table></center>";
			if (($user_access_lvl >= $sec_inc_admin) && (!$c_online))
			{	echo "<p class=\"dropmain\"><center><form method=\"post\" action=\"javascript:popit('c-namechange.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&usern=$c_name','400','200');\"><input value=\" Change Name \" type=\"submit\" class=\"bigbut2\"></form></center></p>";	}
			if (($user_access_lvl >= $sec_inc_gmlevel) || (($user_char_access) && ($username == $c_accname)) && (!$c_online))
			{	echo "<p class=\"dropmain\"><center><form method=\"post\" action=\"charchange.php\"><input value=\" Change Appearance \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"></form></center></p>";	}
			else
			{	echo "<p class=\"dropmain\">&nbsp;</p>";	}
			
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Account</strong></p></td>";

		if (($game_paranoia == 2) && ($user_access_lvl < $sec_inc_gmlevel) && ($username <> $c_accname))
		{	}
		else
		{	echo"<td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Hp</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Mp</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Cp</strong></p></td>";	}
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{ echo "<td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">AccLvl</strong></p></td>"; }
			echo "</tr>\n<tr>";	

			if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
			{	echo "<td class=\"dropmain\"><p class=\"center\"><a href=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&account=$c_accname\" class=\"dropmain\">$c_accname</a>";
				if (($user_access_lvl >= $sec_inc_gmlevel) && ($show_char_time))
				{	$result4 = mysql_query("select sum(onlinetime) from $db_l2jdb.characters where account_name = '$c_accname'",$con);
					$acc_onlinetime = mysql_result($result4,0,"sum(onlinetime)");
					$onlinetime = onlinetime($acc_onlinetime);
					echo "&nbsp;<small><font color=\"$white_code\">($onlinetime)</font></small>";	
				}
				echo "</p></td>";	}
			else
			{
				if (($game_paranoia) && ($c_accname != $user_game_acc))
				{	echo "<td class=\"dropmain\"><p class=\"center\">-&nbsp;Hidden&nbsp;-</p></td>";	}
				else
				{
					if ((($found) && (!$game_paranoia)) || ($c_accname == $user_game_acc))
					{ echo "<td class=\"dropmain\"><p class=\"center\"><a href=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$c_accname\" class=\"dropmain\">$c_accname</a></p></td>";	}
					else 
					{	echo "<td class=\"dropmain\"><p class=\"center\">$c_accname</p></td>";	}
				}
			}
		}
		if (($game_paranoia == 2) && ($user_access_lvl < $sec_inc_gmlevel) && ($username <> $c_accname))
		{	}
		else
		{	echo "<td class=\"dropmain\"><p class=\"center\">$c_chp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mhp</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_cmp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mmp</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_ccp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mcp</strong></p></td>";	}
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$c_alvl</p></td>"; }	// Show the access level if user is a GM.
		echo "</tr>\n<tr>";	
		$i++;
	}
	echo "</table></center>";
	if (($game_paranoia == 2) && ($user_access_lvl < $sec_inc_gmlevel) && ($username <> $c_accname))
	{	}
	else
	{	$result2 = mysql_query("select class_id, level from character_subclasses where charId = '$spec_account' order by class_index",$con);
		$subclasscount = mysql_num_rows($result2);
		if ($subclasscount)
		{
			echo "<br><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\" class=\"dropmain\">";
			echo "<tr><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Subclass</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Lvl</strong></p></td></tr>";
			while ($r_array = mysql_fetch_assoc($result2))
			{
				$class_id = $r_array['class_id'];
				$c_level = $r_array['level'];
				$result3 = mysql_query("select class_name from class_list where id = '$class_id'",$con);
				$sub_class = class_rename(mysql_result($result3,0,"class_name"));
				echo "<tr><td class=\"dropmain\">$sub_class</td><td class=\"dropmain\">$c_level</td></tr>";
			}
			echo "</table></center>";
		}	
	}

	// - If dealing with a specific account, then show the map information on the right hand side, or show a blank map if the user
	// hasn't got map access.
	if ($spec_account)
	{
		echo "</td><td align=\"left\" class=\"noborder\">";
		$map_array = array(array($char_x, $char_y, 1));
		if (($game_paranoia) && ($user_access_lvl < $sec_inc_gmlevel))
		{	$user_map_access = 0;	}
		if ($user_map_access)
		{
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\"  class=\"blanktab\"><tr><td class=\"noborderback\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			map_2($map_array, $images_dir,1);
			echo "</td><td>";
			map_2($map_array, $images_dir,2);
			echo "</td></tr></table></center></td></tr>";
		}
		else
		{
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\"  class=\"blanktab\"><tr><td class=\"noborderback\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\"><img src=\"" . $images_dir . "map2.jpg\" width=\"104\" height=\"150\"></td></tr></table></center></td></tr>";
		}

		if (($clan_leader == 1) or ($user_access_lvl >= $sec_inc_gmlevel))
		{
			$result5 = mysql_query("select sum(`count`) from items where item_id = '57' and owner_id = '$c_num'",$con);
			$adena = comaise(mysql_result($result5,0,"sum(`count`)"));
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\"><small><font color=$green_code>Sp</font>-<font color=$red_code>Adena</font>&nbsp;-&nbsp;<font color=$green_code>$c_sp</font>-<font color=$red_code>$adena</font></small></p></td></tr>\n";
		}
		if (($emergency_teleport) && (!$c_online) && ($c_accname == $username))
		{	echo "<tr><td class=\"noborderback\"><center><form method=\"post\" action=\"c-search.php\"><input value=\" Emergency Teleport \" type=\"submit\" class=\"bigbut2\"><input name=\"action\" type=\"hidden\" value=\"teleporte\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"$view_skills\"></form></center></td></tr>";	}
		echo "</table></center></td></tr></table>";
	}
	echo "<p class=\"dropmain\">&nbsp;</p>\n";

	// - If we have been dealing with a list of players, whether one or more were found, then that is all we need to do here.
	// ... so wrap up the html code and finish the function.
	if (($count_accs > 1) || ($single_found))
	{ $count_accs = 0; }
	if (!$count_accs)
	{ wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0; }
	

// To reach this point, we are looking at a specific character, so we need to display either the skill or inventory information.

	// If the user is able to see an individual characters details, but is not linked to their clans, or is not an administrator,
	// then the get the bums rush.
	if (!$single_clan_found)
	{ 
		writewarn("You are not linked to this characters clans.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	if (($prevent_cross_clan) && ($user_access_lvl < $sec_inc_gmlevel) && ($username != $c_accname))
	{ 
		writewarn("$lang_invendissable");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}

	echo "<img src=\"" . $images_dir . "spacer.gif\" width=\"900\" height=\"1\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr><td class=\"noborderback\"><form method=\"post\" action=\"c-search.php\"><input value=\" <- $lang_itemview -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"\"></form></td><td class=\"noborderback\"><p>&nbsp;&nbsp;&nbsp;</p></td><td class=\"noborderback\">
		<form method=\"post\" action=\"c-search.php\"><input value=\" <- $lang_skillview -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"yes\"></form></td><td class=\"noborderback\"><p>&nbsp;&nbsp;&nbsp;</p></td><td class=\"noborderback\">
		<form method=\"post\" action=\"c-search.php\"><input value=\" <- $lang_recipeview -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"recipe\"></form></td></tr></table>
		</center>";	
		
	// Default is to view items if skill has not been specifically requested.
	if (!$view_skills)
	{
		$sql = "select item_id, object_id, count, enchant_level, loc, loc_data from items where owner_id = $spec_account order by loc, item_id, loc_data";
		$result = mysql_query($sql,$con);
		if (!$result)
		{ wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
			return 0; }
		$count = mysql_num_rows($result);
		
		$main_loop = 0;
		while ($main_loop < 5)
		{
			if ($main_loop == 0)
			{	$title_text = "$lang_wearing";
				$srch_text = "PAPERDOLL";	}
			if ($main_loop == 1)
			{	$title_text = "$lang_inventory ";
				$srch_text = "INVENTORY";	}
			if ($main_loop == 2)
			{	$title_text = "$lang_warehouse";
				$srch_text = "WAREHOUSE";	}
			if ($main_loop == 3)
			{	$title_text = "$lang_freight";
				$srch_text = "FREIGHT";	}
			if ($main_loop == 4)
			{	$title_text = "$lang_unknown";
				$srch_text = "UNKNOWN";	}
			$main_title = 0;
			$title_a = 0;
			$title_b = 0;
			$title_c = 0;
			$i=0;
			while ($i < $count)
			{
				$i_loc = mysql_result($result,$i,"loc");
				$i_id = mysql_result($result,$i,"item_id");
				$i_count = comaise(mysql_result($result,$i,"count"));
				$i_ench = mysql_result($result,$i,"enchant_level");
				$i_objid = mysql_result($result,$i,"object_id");
				if (($main_loop == 3) && ($i_loc == "FREIGHT"))
				{
					$i_binloc = mysql_result($result,$i,"loc_data");
					$i_merchloc = $freightloc[$i_binloc];
				}
				$srch_result = 0;
				if ($main_loop < 4)
				{
					if ($i_loc == $srch_text)
					{	$srch_result = 1;	}
				}
				else
				{
					if (($i_loc != "PAPERDOLL") && ($i_loc != "INVENTORY") && ($i_loc != "WAREHOUSE") && ($i_loc != "FREIGHT"))
					{	$srch_result = 1;	}
				}
				if ($srch_result)
				{
					if (!$main_title)
					{
						echo "<h2 class=\"dropmain\">- $title_text -</h2>\n";
						echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\" width=\"80%\">\n";
						$main_title = 1;
					}
					$sql = "select item_id, name, m_def, weight, price, p_def, crystal_type, bodypart, armor_type from knightarmour where item_id = $i_id";
					$result2 = mysql_query($sql,$con);
					$count_val = mysql_num_rows($result2);
					if ($count_val)
					{
						if (!$title_a)
						{
							echo "<tr>";
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
							echo "<td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"center\">Qty</p></td><td width=\"250\" class=\"lefthead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Grade</p></td><td class=\"drophead\" colspan=\"2\"><p class=\"center\">Body<br>Part</p></td><td class=\"drophead\"><p class=\"center\">P.Def</p></td><td class=\"drophead\"><p class=\"center\">MP</p></td><td class=\"drophead\"><p class=\"dropmain\">Ench.</p></td><td class=\"drophead\"><p class=\"dropmain\">Weight</p></td><td class=\"drophead\"><p class=\"drophead\">Price</p></td><td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
							if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td>"; }
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td>"; }
							echo "</tr>\n";
							$title_a = 1;
						}
						$itm_id = mysql_result($result2,0,"item_id");
						$itm_name = mysql_result($result2,0,"name");
						$itm_bonus = mysql_result($result2,0,"m_def");
						$itm_weight = mysql_result($result2,0,"weight");
						$itm_price = comaise(mysql_result($result2,0,"price"));
						$itm_pdef = mysql_result($result2,0,"p_def");
						$itm_grade = mysql_result($result2,0,"crystal_type");
						$itm_body_part = part_name(mysql_result($result2,0,"bodypart"));
						$itm_armor_type = mysql_result($result2,0,"armor_type");
						
						if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"dropmain\">$itm_id</td>"; }
						$itm_id2 = item_check(0, $itm_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$itm_id2.gif\"></td><td class=\"dropmain\"><p class=\"dropmain\"><strong class=\"dropmain\">$i_count</strong></p></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$i_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itm_id\" class=\"dropmain\">$itm_name</a>";
						$result3 = mysql_query("select * from item_elementals where itemId = $i_objid",$con);
						$e_count = 0;
						while ($r_array = mysql_fetch_assoc($result3))
						{
							$elem_type = $r_array[elemType];
							$elem_value = $r_array[elemValue];
							$elem_name = element_name($elem_type);
							if ($e_count == 0)
							{	echo "<br><font class=\"descrip\"><strong>";	}
							else
							{	echo " - ";	}
							$e_count++;
							echo "$elem_name ";
							if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
							{	echo "<a href=\"javascript:popit('takeelemental.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_objid&elementid=$elem_type&elementvalue=$elem_value&location=$i_loc','400','200');\" class=\"dropmain\"><font color=$blue_code>$elem_value</font></a>";	}
							else
							{	echo "$elem_value";	}
						}
						if ($e_count > 0)
						{	echo "</strong></font>";	}
						if ($main_loop == 3)
						{  echo " ($i_merchloc)";	}
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($itm_grade == "s84")
						{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
						elseif  ($itm_grade == "s80")
						{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
						elseif  ($itm_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($itm_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($itm_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($itm_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($itm_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						elseif  ($itm_grade == "none")
						{ echo "&nbsp;"; }
						else
						{ echo "$itm_grade"; }
						echo "</td>";
						echo "<td class=\"dropmain\" colspan=\"2\"><p class=\"dropmain\">$itm_body_part</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_pdef</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_bonus</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;";
						if ($user_access_lvl < $sec_enchant)
						{	echo "$i_ench</p></td>";	}
						else
						{
							if (($c_online) && (($i_loc == "PAPERDOLL") || ($i_loc == "INVENTORY")))
							{	echo "$i_ench</p></td>";	}
							else
							{	echo "<a href=\"javascript:popit('enchant.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&owner=$spec_account&location=$i_loc&itemid=$i_objid&curenc=$i_ench','400','200');\" class=\"dropmain\">$i_ench</a></p></td>";	}
						}
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$itm_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itm_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
						{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('takeitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$itm_id&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','400','200');\" class=\"dropmain\"><font color=$red_code>CQ</font></a></p></td>"; }
						if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('itemhist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$i_objid&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','400','300');\" class=\"dropmain\"><font color=$red_code>HIST</font></a></p></td>"; }						
						echo "</tr>";
					}
				}
				$i++;
			}
			$i=0;
			while ($i < $count)
			{
				$i_loc = mysql_result($result,$i,"loc");
				$i_id = mysql_result($result,$i,"item_id");
				$i_count = comaise(mysql_result($result,$i,"count"));
				$i_ench = mysql_result($result,$i,"enchant_level");
				$i_objid = mysql_result($result,$i,"object_id");
				if (($main_loop == 3) && ($i_loc == "FREIGHT"))
				{
					$i_binloc = mysql_result($result,$i,"loc_data");
					$i_merchloc = $freightloc[$i_binloc];
				}
				$srch_result = 0;
				if ($main_loop < 4)
				{
					if ($i_loc == $srch_text)
					{	$srch_result = 1;	}
				}
				else
				{
					if (($i_loc != "PAPERDOLL") && ($i_loc != "INVENTORY") && ($i_loc != "WAREHOUSE") && ($i_loc != "FREIGHT"))
					{	$srch_result = 1;	}
				}
				if ($srch_result)
				{
					if (!$main_title)
					{
						echo "<h2 class=\"dropmain\">- $title_text -</h2>";
						echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
						$main_title = 1;
					}
					$sql = "select item_id, name, crystal_type, price, weight, atk_speed, p_dam, m_dam, mp_consume, soulshots, spiritshots, avoid_modify, shield_def, shield_def_rate, bodypart, weaponType from knightweapon where item_id = $i_id";
					$result2 = mysql_query($sql,$con);
					$count_val = mysql_num_rows($result2);
					if ($count_val)
					{
						if (!$title_b)
						{
							echo "<tr>";
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
							echo "<td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"center\">Qty</p></td><td width=\"250\" class=\"lefthead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Grade</p></td><td class=\"drophead\"><p class=\"dropmain\">Type/Part</p></td><td class=\"drophead\"><p class=\"dropmain\">P/M.atk</p></td><td class=\"drophead\"><p class=\"dropmain\">SS/SpS/MP</p></td><td class=\"drophead\"><p class=\"dropmain\">Speed</p></td><td class=\"drophead\"><p class=\"dropmain\">Ench.</p></td><td class=\"drophead\"><p class=\"dropmain\">Weight</p></td><td class=\"drophead\"><p class=\"drophead\">Price</p></td><td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
							if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td>"; }
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td>"; }
							echo "</tr>\n";
							$title_b = 1;
						}
						$itm_id = mysql_result($result2,0,"item_id");
						$itm_name = mysql_result($result2,0,"name");
						$itm_bodypart = mysql_result($result2,0,"bodypart");
						$itm_weaponType = part_name(mysql_result($result2,0,"weaponType"));
						$itm_grade = mysql_result($result2,0,"crystal_type");
						$itm_price = comaise(mysql_result($result2,0,"price"));
						$itm_weight = mysql_result($result2,0,"weight");
						$itm_atkspd = mysql_result($result2,0,"atk_speed");
						$itm_pdam = mysql_result($result2,0,"p_dam");
						$itm_mdam = mysql_result($result2,0,"m_dam");
						$itm_mpc = mysql_result($result2,0,"mp_consume");
						$itm_ss = mysql_result($result2,0,"soulshots");
						$itm_sps = mysql_result($result2,0,"spiritshots");
						$itm_amod = mysql_result($result2,0,"avoid_modify");
						$itm_sdef = mysql_result($result2,0,"shield_def");
						$itm_sdefr = mysql_result($result2,0,"shield_def_rate");
						if ($itm_sdef)
						{
							$itm_pdam = $itm_sdef;
							$itm_mdam = $itm_sdefr;
							$itm_atkspd = $itm_amod;
						}
						echo "<tr>";
						if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"dropmain\">$itm_id</td>"; }
						$itm_id2 = item_check(0, $itm_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$itm_id2.gif\"></td><td class=\"dropmain\"><p class=\"dropmain\"><strong class=\"dropmain\">$i_count</strong></p></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$itm_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itm_id\" class=\"dropmain\">$itm_name</a>";
						$result3 = mysql_query("select * from item_elementals where itemId = $i_objid",$con);
						$e_count = 0;
						while ($r_array = mysql_fetch_assoc($result3))
						{
							$elem_type = $r_array[elemType];
							$elem_value = $r_array[elemValue];
							$elem_name = element_name($elem_type);
							if ($e_count == 0)
							{	echo "<br><font class=\"descrip\"><strong>";	}
							else
							{	echo " - ";	}
							$e_count++;
							echo "$elem_name ";
							if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
							{	echo "<a href=\"javascript:popit('takeelemental.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_objid&elementid=$elem_type&elementvalue=$elem_value&location=$i_loc','400','200');\" class=\"dropmain\"><font color=$blue_code>$elem_value</font></a>";	}
							else
							{	echo "$elem_value";	}
						}
						if ($e_count > 0)
						{	echo "</strong></font>";	}
						if ($main_loop == 3)
						{  echo " ($i_merchloc)";	}
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($itm_grade == "s84")
						{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
						elseif  ($itm_grade == "s80")
						{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
						elseif  ($itm_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($itm_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($itm_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($itm_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($itm_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						elseif  ($itm_grade == "none")
						{ echo "&nbsp;"; }
						else
						{ echo "$itm_grade"; }
						echo "</td>";
						if ($itm_bodypart == "rhand")
						{$itm_bodypart="One Handed";}
						elseif ($itm_bodypart == "lrhand")
						{$itm_bodypart="Two Handed";}
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_weaponType&nbsp;/<br>&nbsp;$itm_bodypart</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_pdam&nbsp;/&nbsp;$itm_mdam</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">x<font color=$green_code>$itm_ss</font>&nbsp;/&nbsp;x<font color=#6B5D10>$itm_sps</font>&nbsp;/&nbsp;<font color=$blue_code>$itm_mpc</font></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_atkspd</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;";
						if ($user_access_lvl < $sec_enchant)
						{	echo "$i_ench</p></td>";	}
						else
						{
							if (($c_online) && (($i_loc == "PAPERDOLL") || ($i_loc == "INVENTORY")))
							{	echo "$i_ench</p></td>";	}
							else
							{	echo "<a href=\"javascript:popit('enchant.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&owner=$spec_account&location=$i_loc&itemid=$i_objid&curenc=$i_ench','400','200');\" class=\"dropmain\">$i_ench</a></p></td>";	}
						}
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$itm_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itm_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
						{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('takeitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$itm_id&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','400','200');\" class=\"dropmain\"><font color=$red_code>CQ</font></a></p></td>"; }
						if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('itemhist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$i_objid&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','400','300');\" class=\"dropmain\"><font color=$red_code>HIST</font></a></p></td>"; }						
						echo "</tr>";
					}
				}
				$i++;
			}	
			$i=0;
			while ($i < $count)
			{
				$i_loc = mysql_result($result,$i,"loc");
				$i_id = mysql_result($result,$i,"item_id");
				$i_count = comaise(mysql_result($result,$i,"count"));
				$i_ench = mysql_result($result,$i,"enchant_level");
				$i_objid = mysql_result($result,$i,"object_id");
				if (($main_loop == 3) && ($i_loc == "FREIGHT"))
				{
					$i_binloc = mysql_result($result,$i,"loc_data");
					$i_merchloc = $freightloc[$i_binloc];
				}
				$srch_result = 0;
				if ($main_loop < 4)
				{
					if ($i_loc == $srch_text)
					{	$srch_result = 1;	}
				}
				else
				{
					if (($i_loc != "PAPERDOLL") && ($i_loc != "INVENTORY") && ($i_loc != "WAREHOUSE") && ($i_loc != "FREIGHT"))
					{	$srch_result = 1;	}
				}
				if ($srch_result)
				{
					if (!$main_title)
					{
						echo "<h2 class=\"dropmain\">- $title_text -</h2>";
						echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
						$main_title = 1;
					}
					$sql = "select item_id, name, crystal_type, weight, material, price, consume_type from knightetcitem where item_id = $i_id";
					$result2 = mysql_query($sql,$con);
					$count_val = mysql_num_rows($result2);
					if ($count_val)
					{
						if (!$title_c)
						{
							echo "<tr>";
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
							echo "<td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"center\">Qty</p></td><td width=\"250\" class=\"lefthead\" colspan=\"3\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Grade</p></td><td class=\"drophead\" colspan=\"2\"><p class=\"dropmain\">Material</p></td><td class=\"drophead\"><p class=\"dropmain\">Ench.</p></td><td class=\"drophead\"><p class=\"dropmain\">Weight</p></td><td class=\"drophead\"><p class=\"drophead\">Price</p></td><td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
							if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td>"; }
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td>"; }
							echo "</tr>\n";
							$title_c = 1;
						}
						$itm_id = mysql_result($result2,0,"item_id");
						$itm_name = mysql_result($result2,0,"name");
						$itm_weight = mysql_result($result2,0,"weight");
						$itm_price = comaise(mysql_result($result2,0,"price"));
						$itm_grade = mysql_result($result2,0,"crystal_type");
						$itm_mat = mysql_result($result2,0,"material");
						$itm_consume_type = mysql_result($result2,0,"consume_type");
						if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_id</p></td>"; }
						$itm_id2 = item_check(0, $itm_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$itm_id2.gif\"></td><td class=\"dropmain\"><p class=\"dropmain\"><strong class=\"dropmain\">$i_count</strong></p></td>";
						echo "<td class=\"left\" colspan=\"3\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$itm_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itm_id\" class=\"dropmain\">$itm_name</a>";
						$result3 = mysql_query("select * from item_elementals where itemId = $i_objid",$con);
						$e_count = 0;
						while ($r_array = mysql_fetch_assoc($result3))
						{
							$elem_type = $r_array[elemType];
							$elem_value = $r_array[elemValue];
							$elem_name = element_name($elem_type);
							if ($e_count == 0)
							{	echo "<br><font class=\"descrip\"><strong>";	}
							else
							{	echo " - ";	}
							$e_count++;
							echo "$elem_name ";
							if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
							{	echo "<a href=\"javascript:popit('takeelemental.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_objid&elementid=$elem_type&elementvalue=$elem_value&location=$i_loc','400','200');\" class=\"dropmain\"><font color=$blue_code>$elem_value</font></a>";	}
							else
							{	echo "$elem_value";	}
						}
						if ($e_count > 0)
						{	echo "</strong></font>";	}
						if ($main_loop == 3)
						{  echo " ($i_merchloc)";	}
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($itm_grade == "s84")
						{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
						elseif  ($itm_grade == "s80")
						{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
						elseif  ($itm_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($itm_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($itm_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($itm_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($itm_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						elseif  ($itm_grade == "none")
						{ echo "&nbsp;"; }
						else
						{ echo "$itm_grade"; }
						echo "</td>";
						echo "<td class=\"dropmain\" colspan=\"2\"><p class=\"dropmain\">";
						if ($itm_mat == "adamantaite")
						{ echo "<img src=\"" . $images_dir . "items/1024.gif\" title=\"adamantaite\">"; }
						elseif ($itm_mat == "liquid")
						{ echo "<img src=\"" . $images_dir . "items/1764.gif\" title=\"liquid\">"; }
						elseif ($itm_mat == "paper")
						{ echo "<img src=\"" . $images_dir . "items/1695.gif\" title=\"paper\">"; }
						elseif ($itm_mat == "crystal")
						{ echo "<img src=\"" . $images_dir . "items/3365.gif\" title=\"crystal\">"; }
						elseif ($itm_mat == "steel")
						{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"steel\">"; }
						elseif ($itm_mat == "fine_steel")
						{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"fine_steel\">"; }
						elseif ($itm_mat == "bone")
						{ echo "<img src=\"" . $images_dir . "items/1872.gif\" title=\"bone\">"; }
						elseif ($itm_mat == "bronze")
						{ echo "<img src=\"" . $images_dir . "items/626.gif\" title=\"bronze\">"; }
						elseif ($itm_mat == "cloth")
						{ echo "<img src=\"" . $images_dir . "items/1729.gif\" title=\"cloth\">"; }
						elseif ($itm_mat == "gold")
						{ echo "<img src=\"" . $images_dir . "items/1289.gif\" title=\"gold\">"; }
						elseif ($itm_mat == "leather")
						{ echo "<img src=\"" . $images_dir . "items/1689.gif\" title=\"leather\">"; }
						elseif ($itm_mat == "mithril")
						{ echo "<img src=\"" . $images_dir . "items/1876.gif\" title=\"mithril\">"; }
						elseif ($itm_mat == "silver")
						{ echo "<img src=\"" . $images_dir . "items/1873.gif\" title=\"silver\">"; }
						elseif ($itm_mat == "wood")
						{ echo "<img src=\"" . $images_dir . "items/2109.gif\" title=\"wood\">"; }
						else
						{ echo "$itm_mat"; }
						echo "</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;$i_ench</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$itm_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$itm_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itm_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						if (($user_access_lvl >= $sec_giveandtake) && (!$c_online))
						{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('takeitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$itm_id&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','400','200');\" class=\"dropmain\"><font color=$red_code>CQ</font></a></p></td>"; }
						
						if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('itemhist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$i_objid&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','400','300');\" class=\"dropmain\"><font color=$red_code>HIST</font></a></p></td>"; }						
						echo "</tr>";
					}
				}
				$i++;
			}
			if ($main_title)
			{
				echo "</table></center>";
			}
			$main_loop++;
		}	
	}
	elseif ($view_skills == "recipe")
	{
		if ($user_rec_access)
		{	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\"><img src=\"" . $images_dir . "calc1.bmp\"></td><td class=\"dropmain\"><p class=\"dropmain\">$lang_clancalc</td><td class=\"dropmain\"><p class=\"dropmain\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
				<td class=\"dropmain\"><img src=\"" . $images_dir . "calc3.bmp\"></td><td class=\"dropmain\"><p class=\"dropmain\">Personal Calculation</td>
				</tr></table></center>";	
		}
		$sql = "select id, type from character_recipebook where charId = $spec_account order by id";
		$result2 = mysql_query($sql,$con);
		$count_val = mysql_num_rows($result2);
		$count_half = ($count_val / 2);
		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" class=\"blanktab\"><tr><td width=\"50%\" class=\"noborder\" valign=\"top\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
		echo "<tr>";
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">ID</strong></p></td>";	}
		echo "<td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Skill</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Chance</strong></p></td>";
		if ($user_rec_access)
		{	echo "<td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Rec Calc</strong></p></td>";	}

		echo "</tr>";
		$i=0;
		while ($i < $count_half)
		{
			$s_id = mysql_result($result2,$i,"id");
			$s_type = mysql_result($result2,$i,"type");
			$sql = "select rec_name, level, chance, rec_id from knightrecch where xml_id = '$s_id'";
			$result3 = mysql_query($sql,$con);
			if (mysql_num_rows($result3) > 0)
			{
				$s_name = mysql_result($result3,0,"rec_name");
				$s_chance = mysql_result($result3,0,"chance");
				$s_level = mysql_result($result3,0,"level");
				$rec_id = mysql_result($result3,0,"rec_id");
			}
			else
			{
				$s_name = "Unknown";
				$s_chance = 0;
				$s_level = 0;
				$rec_id = 0;
			}
			echo "<tr>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{	echo "<td class=\"dropmain\">$s_id</td>";	}
			echo "<td class=\"dropmain\">";
			echo "<img src=\"" . $images_dir . "rec" . $s_level . ".gif\">";

			echo "</td><td class=\"dropmain\"><p class=\"dropmain\">";
			if ($rec_id)
			{	echo "<a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\">$s_name</a>";	}
			else
			{	echo "$s_name";	}
			echo "</p></td><td class=\"dropmain\"><p class=\"dropmain\">$s_chance%</p></td>";
			if ($user_rec_access)
			{	if ($s_chance)
				{
					echo "<td class=\"dropmain\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
					if ($c_clanid)
					{	echo "<td class=\"noborderback\"><a href=\"reccalc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&clan=$c_clanid&recipe=$s_id\" class=\"dropmain\"><img src=\"" . $images_dir . "calc1.bmp\"></a></td>";	}
					echo "<td class=\"noborderback\"><a href=\"reccalc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recipe=$s_id\" class=\"dropmain\"><img src=\"" . $images_dir . "calc3.bmp\"></a>";
					echo "</td></tr></table></center></td>";	
				}
				else	{	echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td>";	}
			}
			echo "</tr>";
			$i++;
		}
		echo "</td></tr></table></center></td><td width=\"50%\" class=\"noborder\" valign=\"top\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
		echo "<tr>";
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">ID</strong></p></td>";	}
		echo "<td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Skill</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Chance</strong></p></td>";
		if ($user_rec_access)
		{	echo "<td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Rec Calc</strong></p></td>";	}

		echo "</tr>";
		while ($i < $count_val)
		{
			$s_id = mysql_result($result2,$i,"id");
			$s_type = mysql_result($result2,$i,"type");
			$sql = "select rec_name, level, chance, rec_id from knightrecch where xml_id = '$s_id'";
			$result3 = mysql_query($sql,$con);
			if (mysql_num_rows($result3) > 0)
			{
				$s_name = mysql_result($result3,0,"rec_name");
				$s_chance = mysql_result($result3,0,"chance");
				$s_level = mysql_result($result3,0,"level");
				$rec_id = mysql_result($result3,0,"rec_id");
			}
			else
			{
				$s_name = "Unknown";
				$s_chance = 0;
				$s_level = 0;
				$rec_id = 0;
			}
			echo "<tr>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{	echo "<td class=\"dropmain\">$s_id</td>";	}
			echo "<td class=\"dropmain\">";
			echo "<img src=\"" . $images_dir . "rec" . $s_level . ".gif\">";

			echo "</td><td class=\"dropmain\"><p class=\"dropmain\">";
			if ($rec_id)
			{	echo "<a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\">$s_name</a>";	}
			else
			{	echo "$s_name";	}
			echo "</p></td><td class=\"dropmain\"><p class=\"dropmain\">$s_chance%</p></td>";
			if ($user_rec_access)
			{	if ($s_chance)
				{
					echo "<td class=\"dropmain\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
					if ($c_clanid)
					{	echo "<td class=\"noborderback\"><a href=\"reccalc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&clan=$c_clanid&recipe=$s_id\" class=\"dropmain\"><img src=\"" . $images_dir . "calc1.bmp\"></a></td>";	}
					echo "<td class=\"noborderback\"><a href=\"reccalc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recipe=$s_id\" class=\"dropmain\"><img src=\"" . $images_dir . "calc3.bmp\"></a>";
					echo "</td></tr></table></center></td>";	
				}
				else	{	echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td>";	}
			}
			echo "</tr>";
			$i++;
		}
		echo"</td></tr></table></center></td></tr></table>";
	}
	else
	// Display skill view if requested.
	{
		$result2 = mysql_query("select class_name from class_list where id = '$c_class'",$con);
		$main_class = class_rename(mysql_result($result2,0,"class_name"));
		$classes = array($main_class);
		$result2 = mysql_query("select class_id from character_subclasses where charId = '$spec_account' order by class_index",$con);
		$subclasscount = mysql_num_rows($result2);
		if ($subclasscount)
		{
			while ($r_array = mysql_fetch_assoc($result2))
			{
				$class_id = $r_array['class_id'];
				$result3 = mysql_query("select class_name from class_list where id = '$class_id'",$con);
				$sub_class = class_rename(mysql_result($result3,0,"class_name"));
				array_push($classes, $sub_class);
			}
		}
		
		$iskill = 0;
		while ($iskill <= $subclasscount)
		{
			if ($iskill > 0)
			{	echo "<h2>$classes[$iskill]</h2>";	}
			$sql = "select skill_id, skill_level, class_index from character_skills where charId = $spec_account and class_index = '$iskill' order by skill_id, class_index";
			$result2 = mysql_query($sql,$con);
			$count_val = mysql_num_rows($result2);
			$count_half = ($count_val / 2);
			echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" class=\"blanktab\"><tr><td width=\"50%\" class=\"noborder\" valign=\"top\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
			echo "<tr>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{	echo "<td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">ID</strong></p></td>";	}
			echo "<td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Skill</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Level</strong></p></td>";
			if (($user_access_lvl >= $sec_takeskill) && ($c_online < 1))
			{	echo "<td class=\"lefthead\" colspan=\"2\"><p class=\"center\"><strong class=\"dropmain\">Alter</strong></p></td><td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">Take</strong></p></td>";	}
			echo "</tr>";
			$i=0;
			while ($i < $count_half)
			{
				$s_id = mysql_result($result2,$i,"skill_id");
				$s_level = mysql_result($result2,$i,"skill_level");
				$c_index = mysql_result($result2,$i,"class_index");
				$sql = "select name from knightskills where skill_id = $s_id";
				$result3 = mysql_query($sql,$con);
				$s_name = mysql_result($result3,0,"name");
				echo "<tr>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{	echo "<td class=\"dropmain\">$s_id</td>";	}
				$s_id2 = item_check(1, $s_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
				if (strlen($s_id2) == 1) {$zcount = '000';}
				if (strlen($s_id2) == 2) {$zcount = '00';}
				if (strlen($s_id2) == 3) {$zcount = '0';}
				if (strlen($s_id2) == 4) {$zcount = '';}
				echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "skills/skill" . $zcount . $s_id2 . ".gif\"></td><td class=\"dropmain\"><p class=\"dropmain\">$s_name</p></td><td class=\"dropmain\"><p class=\"dropmain\">$s_level</p></td>";
				if (($user_access_lvl >= $sec_takeskill) && ($c_online < 1))
				{	$skill_name = preg_replace('/\'/','',$s_name);
					echo "<td><form method=\"post\" action=\"c-search.php\"><input value=\"+\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"yes\"><input name=\"action\" type=\"hidden\" value=\"addskill\"><input name=\"itemid\" type=\"hidden\" value=\"$s_id\"></form></td>";
					echo "<td><form method=\"post\" action=\"c-search.php\"><input value=\"-\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"yes\"><input name=\"action\" type=\"hidden\" value=\"delskill\"><input name=\"itemid\" type=\"hidden\" value=\"$s_id\"></form></td>";
					echo "<td class=\"dropmain\"><a href=\"javascript:popit('takeskill.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$s_id&itemqty=1&usern=$c_name&skilln=$skill_name&location=$i_loc&binloc=$i_binloc','400','200');\" class=\"dropmain\"><font color=$red_code>Del</font></a></td>";
				}
				echo "</tr>";
				$i++;
			}
			echo "</td></tr></table></center></td><td width=\"50%\" class=\"noborder\" valign=\"top\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
			echo "<tr>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{	echo "<td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">ID</strong></p></td>";	}
			echo "<td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Skill</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Level</strong></p></td>";
			if (($user_access_lvl >= $sec_takeskill) && ($c_online < 1))
			{	echo "<td class=\"lefthead\" colspan=\"2\"><p class=\"center\"><strong class=\"dropmain\">Alter</strong></p></td><td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">Take</strong></p></td>";	}
			echo "</tr>";
			while ($i < $count_val)
			{
				$s_id = mysql_result($result2,$i,"skill_id");
				$s_level = mysql_result($result2,$i,"skill_level");
				$c_index = mysql_result($result2,$i,"class_index");
				$sql = "select name from knightskills where skill_id = $s_id";
				$result3 = mysql_query($sql,$con);
				$s_name = "unknown";
				while ($r_array = mysql_fetch_assoc($result3))
				{	$s_name =$r_array['name'];	}
				echo "<tr>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{	echo "<td class=\"dropmain\">$s_id</td>";	}
				$s_id2 = item_check(1, $s_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
				if (strlen($s_id2) == 1) {$zcount = '000';}
				if (strlen($s_id2) == 2) {$zcount = '00';}
				if (strlen($s_id2) == 3) {$zcount = '0';}
				if (strlen($s_id2) == 4) {$zcount = '';}
				echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "skills/skill" . $zcount . $s_id2 . ".gif\"></td><td class=\"dropmain\"><p class=\"dropmain\">$s_name</p></td><td class=\"dropmain\"><p class=\"dropmain\">$s_level</p></td>";
				if (($user_access_lvl >= $sec_takeskill) && ($c_online < 1))
				{	$skill_name = preg_replace('/\'/','',$s_name);
					echo "<td><form method=\"post\" action=\"c-search.php\"><input value=\"+\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"yes\"><input name=\"action\" type=\"hidden\" value=\"addskill\"><input name=\"itemid\" type=\"hidden\" value=\"$s_id\"></form></td>";
					echo "<td><form method=\"post\" action=\"c-search.php\"><input value=\"-\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$spec_account\"><input name=\"viewskills\" type=\"hidden\" value=\"yes\"><input name=\"action\" type=\"hidden\" value=\"delskill\"><input name=\"itemid\" type=\"hidden\" value=\"$s_id\"></form></td>";
					echo "<td class=\"dropmain\"><a href=\"javascript:popit('takeskill.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=$s_id&itemqty=1&usern=$c_name&skilln=$skill_name&location=$i_loc&binloc=$i_binloc','400','200');\" class=\"dropmain\"><font color=$red_code>Del</font></a></td>";
				}
				echo "</tr>";
				$i++;
			}
			echo"</td></tr></table></center></td></tr></table>";
			$iskill++;
		}
	}
	echo "<p class=\"dropmain\">&nbsp;</p>";
	
	if ($user_access_lvl >= $sec_inc_gmlevel)
	{	$gd_access = 1;	}
	else
	{	$gd_access = 0;	}
	if (($user_access_lvl < $sec_inc_gmlevel) && ($username == $c_accname))
	{
		$result2 = mysql_query("select gdaccess from $dblog_l2jdb.knightdrop where name = '$c_accname'",$con2);
		$error = mysql_error();
		$gdtime = mysql_result($result2,0,"gdaccess");
		$today_time = time();
		if ($gdtime >= $today_time)
		{	$gd_access = 1;	}
	}

	if (($gdon == 1) or ($gd_access))
		{
			$a = $_SERVER["HTTP_HOST"];
			$b = $_SERVER["PHP_SELF"];
			if (substr($a,strlen($a)-1,1) == "/")
			{	$a = substr($a,0,strlen($a)-1);	}
			if (substr($a,0,1) == "/")
			{	$a = substr($a,1,strlen($a)-1);	}
			if (substr($b,0,1) <> "/")
			{	$b = '/'.$b;	}
			$b = preg_replace('/c-search.php/','',$b);
			if (substr($b,strlen($b)-1,1) <> "/")
			{	$b = $b.'/';	}
			$t_name =  "http://" . $a . $b;
			if (substr($t_name,strlen($t_name)-1,1) <> "/")
			{	$t_name = $t_name . '/';	}
			$t_name = $t_name . "gd.php?c=" . $server_id . "-" . $spec_account; 
			echo "<center><p class=\"dropmain\">This code is used to show your character signature in forums...</p><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr><td class=\"dropmain\"><p class=\"center\">&lt;img src=\"$t_name\" alt=\"\" /&gt;<br>
				[img]$t_name";
			echo "[/img]</p></td></tr></table></center>";
			echo "<center><img src=\"$t_name\"></center><p>&nbsp;</p>";
		}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
