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
$enchant = input_check($_REQUEST['enchant'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if (($evaluser) && ($username != "guest"))
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{	
		writewarn("You do not have permission to use this function.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "Could Not Connect";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Wrap_start could not change to logserver database: ' . mysql_error());	}

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
	
	if ($enchntgmaccallow)
	{
		$sql = "select login from $dblog_l2jdb.accounts where accessLevel >= '$sec_inc_gmlevel'";
		$result = mysql_query($sql,$con2);
	
		if ($result)
		{
			$sql = "select charId from characters where account_name in (";
			$count = mysql_num_rows($result);
			$i = 0;
			while ($i < $count)
			{
				$gm_id = mysql_result($result,$i,"login");	
				$sql = $sql . "'" . $gm_id . "'";
				$i++;
				if ($i < $count)
				{	$sql = $sql . ", ";	}
			}
			$sql = $sql . ")";
		}
	}
	else
	{	$sql = "select charId from characters where access_level >= '$sec_inc_gmlevel'";	}
	
	$result = mysql_query($sql,$con);
	$sql = "select owner_id, item_id, count, enchant_level, loc, loc_data from items where enchant_level > '$enchant' ";
	if ($result)
	{
		$sql = $sql . " and owner_id not in (";
		$count = mysql_num_rows($result);
		$i = 0;
		while ($i < $count)
		{
			$gm_id = mysql_result($result,$i,"charId");	
			$sql = $sql . $gm_id;
			$i++;
			if ($i < $count)
			{	$sql = $sql . ", ";	}
		}
		$sql = $sql . ")";
	}

	// Query for all owners and locations of items.
	// If someone does a search for adena, then this could end up being a long list!
	$sql = $sql . " order by owner_id, item_id, loc, loc_data";
	if (!$result = mysql_query($sql,$con))
	{
		die('Could not retrieve from items database: ' . mysql_error());
	}
	// If return array empty, then username not found.
	$row = mysql_fetch_array($result);
	$count_accs = mysql_num_rows($result);
	if (!$row)
	{
			writewarn("Sorry, no players holding $enchant or above enchanted items.");
			return 0;
	}
	


	
	
	echo "<p class=\"dropmain\">&nbsp</p>";
	
	// Now we cycle through the occurances of each item found.
	$i=0;
	$total_find = 0;
	$title_a = 0;
	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"80%\" class=\"dropmain\">";
	while ($i < $count_accs)
	{
		$c_owner = mysql_result($result,$i,"owner_id");
		$itemid = mysql_result($result,$i,"item_id");
		$c_count = comaise(mysql_result($result,$i,"count"));
		$c_enchant = mysql_result($result,$i,"enchant_level");
		$c_loc = strtoupper(mysql_result($result,$i,"loc"));
		$c_location = mysql_result($result,$i,"loc_data");
		
		// Try and find the details on the object from each of the three content databases.
		// Looking simply for the name and crystal type.
		$is_item_etcitem = 0;
		$obj_name = "<$lang_unknown>";
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
			$is_item_etcitem = 1;
		}
		
		if (!$is_item_etcitem)
		{
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
				$sql = "select account_name, char_name, level, clanid, accesslevel from characters where charId = $c_owner";
				$result2 = mysql_query($sql,$con);
				if ($result2)
				{
					$isinclan = 0;
					$u_account = mysql_result($result2,0,"account_name");
					$u_char = mysql_result($result2,0,"char_name");
					$u_clanid = mysql_result($result2,0,"clanid");
					$u_level = mysql_result($result2,0,"level");
					$u_access = mysql_result($result2,0,"accesslevel");
					$sql = "select accessLevel from $dblog_l2jdb.accounts where login = '$u_account'";
					$result3 = mysql_query($sql,$con);
					$uac_access = mysql_result($result3,0,"accessLevel");
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
						else
						{ echo "&nbsp;"; }
						echo "</td><td class=\"dropmain\"><p class=\"center\">$c_count</p></td><td class=\"dropmain\"><p class=\"center\">$c_enchant&nbsp;</p></td><td class=\"dropmain\"><p class=\"center\">";
						echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&charnum=$c_owner\" class=\"dropmain\">$u_char</a>";
						if ($u_access < 0)
						{	echo "<font color=$red_code>&nbsp;[Banned]</font>";	}
						echo "<br><a href=\"a-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&account=$u_account\" class=\"dropmain\">[$u_account]</a>";
						if ($uac_access < 0)
						{	echo "<font color=$red_code>&nbsp;[Banned]</font>";	}
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
						else
						{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
						echo "</tr>";
					}
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
	
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
