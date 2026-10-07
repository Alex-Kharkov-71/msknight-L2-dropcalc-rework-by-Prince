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

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}
// Guests are not allowed to use the clan item find function.
if ($username == "guest")
{
	writewarn("Guests can not use clan item find");
	wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
	return 0;
}
if ($evaluser)
{
	if (($prevent_cross_clan > 1) && ($user_access_lvl < $sec_inc_gmlevel))
	{	
		writewarn("$lang_clanifinddis");
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

	// Query for all owners and locations of items.
	// If someone does a search for adena, then this could end up being a long list!
	$sql = "select owner_id, count, enchant_level, loc, loc_data from items where item_id = $itemid order by owner_id, loc, loc_data";
	if (!$result = mysql_query($sql,$con))
	{
		die('Could not retrieve from items database: ' . mysql_error());
	}
	// If return array empty, then username not found.
	$row = mysql_fetch_array($result);
	$count_accs = mysql_num_rows($result);
	if (!$row)
	{
			writewarn("Sorry, no players holding the item.");
			return 0;
	}
	
	// Try and find clans that are linked to the users account characters.
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


	// Try and find the details on the object from each of the three content databases.
	// Looking simply for the name and crystal type.
	$obj_name = "<unknown>";
	$obj_grade = 0;
	$sql = "select name, crystal_type from knightarmour where item_id = $itemid";
	$result_i = mysql_query($sql,$con);
	$count_i = mysql_num_rows($result_i);
	if ($count_i)
	{	
		$obj_name = mysql_result($result_i,0,"name");	
		$obj_grade = mysql_result($result_i,0,"crystal_type");	
	}
	$sql = "select name, crystal_type from knightweapon where item_id = $itemid";
	$result_i = mysql_query($sql,$con);
	$count_i = mysql_num_rows($result_i);
	if ($count_i)
	{	
		$obj_name = mysql_result($result_i,0,"name");	
		$obj_grade = mysql_result($result_i,0,"crystal_type");	
	}
	$sql = "select name from knightetcitem where item_id = $itemid";
	$result_i = mysql_query($sql,$con);
	$count_i = mysql_num_rows($result_i);
	if ($count_i)
	{	
		$obj_name = mysql_result($result_i,0,"name");	
		$obj_grade = "N/A";	
	}
	
	echo "<p class=\"dropmain\">&nbsp</p>";
	
	// Now we cycle through the occurances of each item found.
	$i=0;
	$total_find = 0;
	$total_found = 0;
	$title_a = 0;
	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"80%\" class=\"dropmain\">";
	while ($i < $count_accs)
	{
		$c_owner = mysql_result($result,$i,"owner_id");
		$c_count = comaise(mysql_result($result,$i,"count"));
		$c_enchant = mysql_result($result,$i,"enchant_level");
		$c_loc = strtoupper(mysql_result($result,$i,"loc"));
		$c_location = mysql_result($result,$i,"loc_data");
		
		// If the item was found in a clan warehouse, then we need to execute a slightly different piece of code.
		if ($c_loc == "CLANWH")
		{
			// First, we need to determine if the item belongs to a character that is a member of one
			// of the clans that the users account is also linked to.  Or an admin will always see the entry
			$isinclan = 0;
			if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the item.
			{	$isinclan = 1;	}
			$i2=0;
			while ($i2 < $clan_member_count)
			{
				if ($c_owner == $char_clan_list[$i2])
				{	$isinclan = 1;	}
				$i2++;
			}
			if ($isinclan)
			{
				$total_find = 1;
				$total_found = $total_found + mysql_result($result,$i,"count");
				$clan_name = "None";
				$sql = "select clan_name from clan_data where clan_id = $c_owner";
				$result_clan = mysql_query($sql,$con);
				$clan_count = mysql_num_rows($result_clan);
				if ($clan_count)
				{	$clan_name = mysql_result($result_clan,0,"clan_name");	}
				if (!$title_a)
				{
					echo "<tr>";
					if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
					{	echo "<td class=\"drophead\"><p class=\"dropmain\">Char ID</p></td>";	}
					echo "<td class=\"drophead\"><p class=\"left\">&nbsp;</p></td><td class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Grade</p></td><td class=\"drophead\"><p class=\"center\">Qty</p></td><td class=\"drophead\"><p class=\"center\">Echnt.</p></td><td class=\"drophead\"><p class=\"center\">Owner</p></td><td class=\"drophead\"><p class=\"center\">Lvl</p></td><td class=\"drophead\"><p class=\"center\">$lang_clan</p></td><td class=\"drophead\"><p class=\"center\">Location</p></td>";
					if ($user_access_lvl >= $sec_giveandtake)
					{	echo "<td class=\"drophead\"><p class=\"dropmain\">Change</p></td>";	}
					echo "</tr>";
					$title_a = 1;
				}
				echo "<tr>";
				if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
				{	echo "<td class=\"dropmain\"><p class=\"dropmain\">$c_owner</p></td>";	}
				$itemid2 = item_check(0, $itemid, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
				echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$itemid2.gif\"></td><td class=\"dropmain\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$i_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itemid\" class=\"dropmain\">$obj_name</a></p></td>";
				echo "<td class=\"dropmain\">";
				if ($obj_grade == "s")
				{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
				elseif  ($obj_grade == "a")
				{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
				elseif  ($obj_grade == "b")
				{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
				elseif  ($obj_grade == "c")
				{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
				elseif  ($obj_grade == "d")
				{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
				elseif  ($itm_grade == "none")
				{ echo "$obj_grade"; }
				else
				{ echo "&nbsp;"; }
				echo "</td><td class=\"dropmain\"><p class=\"center\">$c_count</p></td><td class=\"dropmain\"><p class=\"center\">$c_enchant</p></td><td colspan=\"3\" class=\"dropmain\"><p class=\"center\">";
				if ($clan_name =="None")
				{	echo "Unknown warehouse $c_owner";	}
				else
				{	echo "<a href=\"cl-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&clannum=$c_owner\" class=\"dropmain\">$clan_name $lang_warehouse</a>";	}
				if ($c_loc == "CLANWH")
				{	$c_loc = "$lang_clanwareh";	}
				echo "</p></td><td class=\"dropmain\"><p class=\"center\">$c_loc</p></td>";

						echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('takeitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_owner&itemid=$itemid&itemqty=$c_count&usern=$u_char&location=CLANWH','400','200');\" class=\"dropmain\"><font color=$red_code>CQ</font></a></p></td>"; 

				echo "</tr>";
			}
		}
		else		// Find the user details for the person that's got the item.
		{
			$sql = "select account_name, char_name, level, clanid from characters where charId = $c_owner";
			$result2 = mysql_query($sql,$con);
			if ($result2)
			{
				$isinclan = 0;
				$u_account = mysql_result($result2,0,"account_name");
				$u_char = mysql_result($result2,0,"char_name");
				$u_clanid = mysql_result($result2,0,"clanid");
				$u_level = mysql_result($result2,0,"level");
				if ($u_account == $user_game_acc)		// If the character is one of the users, then always flag in clan
				{	$isinclan = 1;	}
				if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
				{	$isinclan = 1;	}
				$i2=0;
				while ($i2 < $clan_member_count)
				{
					if ($u_clanid == $char_clan_list[$i2])
					{	$isinclan = 1;	}
					$i2++;
				}
				if ($isinclan)
				{
					$total_find = 1;
					$total_found = $total_found + mysql_result($result,$i,"count");
					$clan_name = "None";
					$sql = "select clan_name from clan_data where clan_id = $u_clanid";
					$result_clan = mysql_query($sql,$con);
					$clan_count = mysql_num_rows($result_clan);
					if ($clan_count)
					{	$clan_name = mysql_result($result_clan,0,"clan_name");	}
					if (!$title_a)
					{
						echo "<tr>";
						if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
						{	echo "<td class=\"drophead\"><p class=\"dropmain\">Char ID</p></td>";	}
						echo "<td class=\"drophead\"><p class=\"left\">&nbsp;</p></td><td class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Grade</p></td><td class=\"drophead\"><p class=\"center\">Qty</p></td><td class=\"drophead\"><p class=\"center\">Echnt.</p></td><td class=\"drophead\"><p class=\"center\">Owner</p></td><td class=\"drophead\"><p class=\"center\">Lvl</p></td><td class=\"drophead\"><p class=\"center\">$lang_clan</p></td><td class=\"drophead\"><p class=\"center\">Location</p></td>";
						if ($user_access_lvl >= $sec_giveandtake)
						{	echo "<td class=\"drophead\"><p class=\"dropmain\">Change</p></td>";	}
						echo "</tr>";
						$title_a = 1;
					}
					echo "<tr>";
					if ($user_access_lvl >= $sec_inc_gmlevel)  // If user is a GM, always show the character link.
					{	echo "<td class=\"dropmain\"><p class=\"dropmain\">$c_owner</p></td>";	}
					$itemid2 = item_check(0, $itemid, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
					echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$itemid2.gif\"></td><td class=\"dropmain\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$i_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$itemid\" class=\"dropmain\">$obj_name</a></p></td>";
					echo "<td class=\"dropmain\">";
					if ($obj_grade == "s")
					{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
					elseif  ($obj_grade == "a")
					{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
					elseif  ($obj_grade == "b")
					{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
					elseif  ($obj_grade == "c")
					{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
					elseif  ($obj_grade == "d")
					{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
					elseif  ($itm_grade == "none")
					{ echo "&nbsp;"; }
					else
					{ echo "$obj_grade"; }
					echo "</td><td class=\"dropmain\"><p class=\"center\">$c_count</p></td><td class=\"dropmain\"><p class=\"center\">$c_enchant&nbsp;</p></td><td class=\"dropmain\"><p class=\"center\">";
					echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&charnum=$c_owner\" class=\"dropmain\">$u_char</a>";
					echo "</p></td><td class=\"dropmain\"><p class=\"center\">$u_level</p></td><td class=\"dropmain\"><p class=\"center\">";
					if ($clan_name =="None")
					{	echo "$clan_name";	}
					else
					{	echo "<a href=\"cl-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&clannum=$u_clanid\" class=\"dropmain\">$clan_name</a>";	}
					$c_removeloc = $c_loc;
					if ($c_loc == "INVENTORY")
					{	$c_loc = "$lang_inventory";	}
					elseif  ($c_loc == "WAREHOUSE")
					{	$c_loc = "$lang_warehouse";	}
					elseif  ($c_loc == "PAPERDOLL")
					{	$c_loc = "$lang_equipped";	}
					elseif  ($c_loc == "FREIGHT")
					{	
						$freight_loc = $freightloc[$c_location];
						$c_loc = "$lang_freight<br>($freight_loc)";	
					}
					echo "</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_loc</p></td>";
					if ($user_access_lvl >= $sec_giveandtake)
					{
						$sql2 = "select online from characters where char_name = '$u_char'";
						$result3 = mysql_query($sql2,$con);
						if ($result3)
						{
							$c_online = mysql_result($result3,0,"online");
							if (!$c_online)
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"javascript:popit('takeitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_owner&itemid=$itemid&itemqty=$c_count&usern=$u_char&location=$c_removeloc&binloc=$c_location','400','200');\" class=\"dropmain\"><font color=$red_code>CQ</font></a></p></td>"; }
							else
							{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
						}
					}
					echo "</tr>";
				}
			}
		}
		$i++;
	}
	echo "</table></center>";
	if (!$total_find)
	{
			writewarn("Sorry, no players holding the item.");
	}
	else
	{	
		$total_found = comaise($total_found);
		echo "<h2 class=\"dropmain\">Total - $total_found</h2>";
	}
	
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
