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
$clan_search = input_check($_REQUEST['clannum'],0);
$makeleader = input_check($_REQUEST['makeleader'],0);

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

	if (($user_access_lvl >= $sec_inc_gmlevel) && ($makeleader))
	{
		$result = mysql_query("select charId, clanid from characters where char_name = '$makeleader'",$con);
		$id = mysql_result($result,0,"charId");
		$clanid = mysql_result($result,0,"clanid");
		$result = mysql_query("select clan_privs from characters where char_name = '$makeleader'",$con);
		$old_privs = mysql_result($result,0,"clan_privs");
		$result = mysql_query("update characters set clan_privs = '8388606' where char_name = '$makeleader'",$con);
		$result = mysql_query("select leader_id from clan_data where clan_id = '$clanid'",$con);
		$old_leader = mysql_result($result,0,"leader_id");
		$result = mysql_query("delete from character_skills where charId = '$old_leader' and skill_id = '246'",$con);
		$result = mysql_query("delete from character_skills where charId = '$old_leader' and skill_id = '247'",$con);
		$result = mysql_query("insert ignore into character_skills (charId, skill_id, skill_level, class_index, skill_name) values ('$id', 246, 1, 0, 'Seal of Ruler')",$con);
		$result = mysql_query("insert ignore into character_skills (charId, skill_id, skill_level, class_index, skill_name) values ('$id', 247, 1, 0, 'Build Headquarters')",$con);
		$result = mysql_query("update clan_data set leader_id = '$id' where clan_id = '$clanid'",$con);
		$result = mysql_query("update characters set clan_privs = '$old_privs' where charId = '$old_leader'",$con);
	}

	if ((!$clan_search) && (strlen($itemname) < $minlenclan))
	{	
		writewarn("Please give at least $minlenclan characters.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	// Query for clanids depending whether we have a specific clan ID, or a string to search for.
	if ($clan_search)
	{	$sql = "select clan_id, clan_name, clan_level, hascastle, ally_id, ally_name, leader_id from clan_data where clan_id = $clan_search";	}
	else
	{	$sql = "select clan_id, clan_name, clan_level, hascastle, ally_id, ally_name, leader_id from clan_data where clan_name like '%$itemname%' order by clan_name";	}
	if (!$result = mysql_query($sql,$con))
	{
		die('Could not retrieve from knightdrop database: ' . mysql_error());
	}

	// If return array empty, then no matching clan found.
	$row = mysql_fetch_array($result);
	$count_accs = mysql_num_rows($result);
	if ($count_accs == 1)
	{	$clan_search = mysql_result($result,0,"clan_id");	}
	if (!$row)
	{
			writewarn("Sorry, no clan accounts match $itemname");
			wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
			return 0;
	}
	
	
	// Try and find clans that are linked to the users account characters.
	$clan_member_count = 0;
	if ((!$user_game_acc) && ($username != "guest"))
	{	echo "<h2 class=\"dropmain\">Warning - Drop calc doesn't know your game account</h2>"; }
	else
	{
		$sql = "select distinct clanid, clan_privs from characters where account_name = '$user_game_acc'";
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
				$clan_priv = mysql_result($result_clan,$i,"clan_privs") & 8;
				if ($clan_res)
				{
					$char_clan_list[$clan_member_count] = $clan_res;
					$char_clan_priv[$clan_member_count] = $clan_priv;
					$clan_member_count++;
				}
				$i++;
			}
		}
	}
	echo "<p class=\"dropmain\">&nbsp</p>";
	
	// At this point, we know the users own clan list and have a list of search matching clans.
	
	$i=0;
	while ($i < $count_accs)
	{
		$clan_id = mysql_result($result,$i,"clan_id");
		$clan_name = mysql_result($result,$i,"clan_name");
		$clan_level = mysql_result($result,$i,"clan_level");
		$clan_castle = mysql_result($result,$i,"hascastle");
		$clan_hideout = 0;
		$clan_ally_id = mysql_result($result,$i,"ally_id");
		$clan_ally_name = mysql_result($result,$i,"ally_name");
		$clan_leader_id = mysql_result($result,$i,"leader_id");

		$clan_lead_name ="$lang_unknown";
		$leader_online = 1;
		$sql = "select char_name, online from characters where charId = $clan_leader_id";
		$result_lead = mysql_query($sql,$con);
		if ($result)
		{ 
			$clan_lead_name = mysql_result($result_lead,0,"char_name");
			$leader_online = mysql_result($result_lead,0,"online");
		}
		$clan_cast_name ="None";
		if ($clan_castle)
		{
			$sql = "select name from castle where id = $clan_castle";
			$result_lead = mysql_query($sql,$con);
			if ($result)
			{ $clan_cast_name = mysql_result($result_lead,0,"name"); }
			else
			{ $clan_cast_name = "$lang_unknown"; }
		}
		$clan_hide_name ="None";
		if ($clan_hideout)
		{
			$sql = "select name from clanhall where id = $clan_hideout";
			$result_lead = mysql_query($sql,$con);
			if ($result)
			{ $clan_hide_name = mysql_result($result_lead,0,"name"); }
			else
			{ $clan_hide_name = "$lang_unknown"; }
		}
		
		$found = 0;
		$found_privs = 0;
		if (($user_access_lvl < $sec_inc_gmlevel) && ($clan_member_count)) // If user is not admin, but is a member of clans, 
		{                                                                  // check if the user is a member of this clan.
			$i3 = 0;
			while ($i3 < $clan_member_count)
			{
				if ($clan_id == $char_clan_list[$i3])
				{	
					$found = 1;
					$found_privs = $found_privs + $char_clan_priv[$i3];
				}
				$i3++;
			}
		}
		
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\" class=\"dropmain\"><tr><td colspan=\"4\" class=\"dropmain\"><strong class=\"dropmain\"><font color=$blue_code>$lang_clan -</font> ";
		if (($found) || ($user_access_lvl >= $sec_inc_gmlevel)) // If user is not admin, but is a member of clans, show links where is a member.
		{
			echo "<a href=\"cl-search.php?$itemname=$clan_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&clannum=$clan_id\" class=\"dropmain\">";
		}
		echo "$clan_name";
		if (($found) || ($user_access_lvl >= $sec_inc_gmlevel)) // If user is not admin, but is a member of clans, show links where is a member.
		{
			echo "</a>";
		}
		echo "</strong></td><td colspan=\"3\" class=\"dropmain\"><strong class=\"dropmain\"><font color=$blue_code>Level -</font> $clan_level</strong></td><td colspan=\"2\" class=\"dropmain\"><strong class=\"dropmain\"><font color=$blue_code>$lang_leader -</font> $clan_lead_name</strong></td>";
		if ($user_access_lvl >= $sec_inc_gmlevel)
			{ echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td><td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td>"; }
		echo "</tr>";
		echo "<tr><td colspan=\"4\" class=\"dropmain\"><strong class=\"dropmain\"><font color=$blue_code>Castle -</font> $clan_cast_name</strong></td><td colspan=\"3\" class=\"dropmain\"><strong class=\"dropmain\"><font color=$blue_code>$lang_hideout -</font> $clan_hide_name</strong></td><td colspan=\"2\" class=\"dropmain\"><strong class=\"dropmain\"><font color=$blue_code>$lang_ally -</font> $clan_ally_name </strong></td>";
		if ($user_access_lvl >= $sec_inc_gmlevel)
			{ echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td><td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td>"; }
		echo "</tr>";
				
		
		if ((($guest_nosee_clanchars) && ($username == "guest")) || (($game_paranoia > 1) && ($user_access_lvl < $sec_inc_gmlevel)))
		{
		}
		else
		{
			echo "<tr><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Char&nbsp;Name</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">On?</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Lvl</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Race</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Sex</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$lang_class&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Hp</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Mp</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Cp</strong></p></td>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">AccLvl</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_makeleader</strong></p></td>"; }
			echo "</tr>";
			$sql = "select charId, account_name, char_name, classid, clanid, level, sex, maxhp, curhp, maxcp, curcp, maxmp, curmp, accesslevel, online from characters where clanid = \"$clan_id\" order by char_name";
			$result2 = mysql_query($sql,$con);
			$count_res = mysql_num_rows($result2);
			if (!$count_res)
			{ if ($user_access_lvl >= $sec_inc_gmlevel)
				{	echo "<tr><td colspan=\"12\" class=\"noborder\"><p class=\"dropmain\"><strong class=\"dropmain\">No Active Characters in the clan</strong></p></td></tr>\n"; }
				else
				{	echo "<tr><td colspan=\"10\" class=\"noborder\"><p class=\"dropmain\"><strong class=\"dropmain\">No Active Characters in the clan</strong></p></td></tr>\n"; }
			}
			else
			{
			}

			// Go through the characters that belong to that clan.
			$i2=0;
			while ($i2 < $count_res)
			{
				$c_num = mysql_result($result2,$i2,"charId");
				$c_accname = mysql_result($result2,$i2,"account_name");
				$c_name = mysql_result($result2,$i2,"char_name");
				$c_class = mysql_result($result2,$i2,"classid");
				$c_clanid = mysql_result($result2,$i2,"clanid");
				$c_level = mysql_result($result2,$i2,"level");
				$c_sex = mysql_result($result2,$i2,"sex");
				$c_mhp = mysql_result($result2,$i2,"maxhp");
				$c_chp = mysql_result($result2,$i2,"curhp");
				$c_mmp = mysql_result($result2,$i2,"maxmp");
				$c_cmp = mysql_result($result2,$i2,"curmp");
				$c_mcp = mysql_result($result2,$i2,"maxcp");
				$c_ccp = mysql_result($result2,$i2,"curcp");
				$c_alvl = mysql_result($result2,$i2,"accesslevel");
				$c_online = mysql_result($result2,$i2,"online");
				$c_race_n = "$lang_unknown";
				$c_class_n = "$lang_unknown";
				$c_class_s = "Unkonwn";
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
				echo "<tr><td class=\"dropmain\"><p class=\"left\">";

				if (($user_access_lvl >= $sec_inc_gmlevel) || ($found))  // If user is a GM, always show the character link.
				{	echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&charnum=$c_num\" class=\"dropmain\">$c_name</a>";	}
				else 
				{	echo "$c_name";	}

				echo "</p></td><td class=\"dropmain\"><p class=\"center\">";
				if ($c_online)
				{ echo "<font color=$green_code>Yes"; }
				else
				{ echo "<font color=$red_code>No"; }
				echo "</font></p></td><td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\"><font color=$green_code>$c_level</font></strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_race_n</p></td><td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">";
				if ($c_sex)
				{	echo "<img src=\"" . $images_dir . "female.gif\" width=\"10\" height=\"14\" border=\"0\">"; }
				else
				{	echo "<img src=\"" . $images_dir . "male.gif\" width=\"10\" height=\"14\" border=\"0\">"; }
				echo "</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_class_n</p></td><td class=\"dropmain\"><p class=\"center\">$c_chp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mhp</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_cmp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mmp</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_ccp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mcp</strong></p></td>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{ 
					echo "<td class=\"dropmain\"><p class=\"dropmain\">$c_alvl</p></td><td class=\"dropmain\"><p class=\"dropmain\">";
					if (($c_online) || ($leader_online))
					{	echo "&nbsp;</p></td>"; }
					else
					{	echo "<a href=\"cl-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&itemid=$itemid&itemsort=$itemsort&clannum=$clan_search&makeleader=$c_name\" class=\"dropmain\">ML</a></p></td>"; }
				}
				echo "</tr>\n";
				$i2++;
			}
		}
		echo "</table></center><p class=\"dropmain\">&nbsp;</p>\n";
		$i++;
	
	}
	echo "<p class=\"dropmain\">&nbsp</p>";

	// If it is one clan and the user has the privs, then list the items in the clan warehouse.
	$is_priv = 0;
	if ($user_access_lvl >= $sec_inc_gmlevel)
	{
		$found = 1;	
		$found_privs = 1;
	}
	if (($clan_search) && ($found))
	{
		if ($found_privs)
		{
			$sql = "select item_id, count, enchant_level, object_id from items where owner_id = $clan_search order by item_id";
			$result = mysql_query($sql,$con);
			$count_accs = mysql_num_rows($result);
			if (!$count_accs)
			{
					writewarn("$lang_clanwarehemp");
					wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
					return 0;
			}
			$title = 0;
			$i=0;
			while ($i < $count_accs)
			{
				$c_count = comaise(mysql_result($result,$i,"count"));
				$c_enchant = mysql_result($result,$i,"enchant_level");
				$c_item = mysql_result($result,$i,"item_id");
				$i_objid = mysql_result($result,$i,"object_id");
				if (!$title)
				{
					echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"80%\" class=\"dropmain\"><tr>";
					echo "<td class=\"drophead\"><p class=\"left\">&nbsp;</p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong>";
					echo "</p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Grade</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Qty</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Echnt.</strong></p></td>";
					if ($user_access_lvl >= $sec_giveandtake)
					{ echo "<td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Change</strong></p></td>"; }
					echo "</tr>";
					$title = 1;
				}
				$enchantable = 1;
				$sql = "select name, crystal_type from knightarmour where item_id = $c_item";
				$result2 = mysql_query($sql,$con);
				$count_res = mysql_num_rows($result2);
				if (!$count_res)
				{
					$sql = "select name, crystal_type from knightweapon where item_id = $c_item";
					$result2 = mysql_query($sql,$con);
					$count_res = mysql_num_rows($result2);
				}
				if (!$count_res)
				{
					$sql = "select name, crystal_type from knightetcitem where item_id = $c_item";
					$result2 = mysql_query($sql,$con);
					$count_res = mysql_num_rows($result2);
					$enchantable = 0;
				}
				$c_name = mysql_result($result2,0,"name");
				$c_grade = mysql_result($result2,0,"crystal_type");
				$c_item2 = item_check(0, $c_item, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
				echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$c_item2.gif\"></td><td class=\"dropmain\"><p class=\"left\"><a href=\"i-search.php?$itemname=$i_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$c_item\" class=\"dropmain\">$c_name</a>";
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
							if ($user_access_lvl >= $sec_giveandtake)
							{	echo "<a href=\"javascript:popit('takeelemental.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_objid&elementid=$elem_type&elementvalue=$elem_value&location=$i_loc','400','200');\" class=\"dropmain\"><font color=$blue_code>$elem_value</font></a>";	}
							else
							{	echo "$elem_value";	}
						}
						if ($e_count > 0)
						{	echo "</strong></font>";	}
				 check_item($c_item, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
				echo "</p></td><td class=\"dropmain\">";
				if ($c_grade == "s")
				{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
				elseif  ($c_grade == "a")
				{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
				elseif  ($c_grade == "b")
				{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
				elseif  ($c_grade == "c")
				{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
				elseif  ($c_grade == "d")
				{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
				elseif  ($c_grade == "none")
				{ echo "&nbsp;"; }
				else
				{ echo "$c_grade"; }
				echo "</td><td class=\"dropmain\"><p class=\"dropmain\">$c_count</p></td><td class=\"dropmain\"><p class=\"dropmain\">";
				if (($user_access_lvl < $sec_inc_gmlevel) || (!$enchantable))
				{	echo "$c_enchant</p></td>";	}
				else
				{	echo "<a href=\"javascript:popit('enchant.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&owner=$clan_search&location=CLANWH=$c_item&curenc=$c_enchant','400','200');\" class=\"dropmain\">$c_enchant</a></p></td>";	}
	
				echo "</p></td>";
				if ($user_access_lvl >= $sec_giveandtake)
				{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('takeitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$clan_id&itemid=$c_item&itemqty=$c_count&usern=$clan_name&location=CLANWH','400','200');\" class=\"dropmain\"><font color=$red_code>CQ</font></a></p></td>"; }
				echo "</tr>";
				$i++;
			}
			if ($title)
			{
				echo "</table></center>";
			}
		}
		else
		{	echo "<h2 class=\"dropmain\">You do not have viewing privileges for this clan warehouse.<br>Talk with your clan leader.</h2>";	}
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
