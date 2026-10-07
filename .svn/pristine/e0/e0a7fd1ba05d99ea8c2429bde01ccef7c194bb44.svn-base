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
$adminshow = input_check($_REQUEST['adminshow'],0);
$i_sort = input_check($_REQUEST['sort'],0);

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

	$itemnum = intval($itemname);
	if ($itemnum > 0)
	{
		$sql = "select item_id, price from knightarmour where item_id = '$itemnum'";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		if ($count)
		{	
			$itemid = $itemnum;
			$orig_price =  mysql_result($result,0,"price");
		}
		$sql = "select item_id, price from knightetcitem where item_id = '$itemnum'";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		if ($count)
		{	$itemid = $itemnum;	
			$orig_price =  mysql_result($result,0,"price");
		}
		$sql = "select item_id, price from knightweapon where item_id = '$itemnum'";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		if ($count)
		{	$itemid = $itemnum;	
			$orig_price =  mysql_result($result,0,"price");
		}
	}
				$total_match = 0;

	// If the script called itself with an item, then look up that items details.
	if ($itemid)
	{
		echo "<p class=\"dropmain\">&nbsp;</p>";
		// Connect to DB


		$found_item = 0;
		// Query for item
		$sql = "select item_id, name, m_def, weight, price, p_def, crystal_type, bodypart, armor_type from knightarmour where item_id = $itemid ";
		if (!$result = mysql_query($sql,$con))
		{
			die('Could not query database: ' . mysql_error());
		}
		// If return array empty, then item not found.
		$row = mysql_fetch_array($result);
		if ($row)
		{
			$i_id = mysql_result($result,0,"item_id");
			$i_name = mysql_result($result,0,"name");
			$i_bonus = mysql_result($result,0,"m_def");
			$i_weight = mysql_result($result,0,"weight");
			$i_price = comaise(mysql_result($result,0,"price"));
			$orig_price =  mysql_result($result,0,"price");
			$i_pdef = mysql_result($result,0,"p_def");
			$i_grade = mysql_result($result,0,"crystal_type");
			$i_body_part = part_name(mysql_result($result,0,"bodypart"));
			$i_armor_type = mysql_result($result,0,"armor_type");
			
			if (($mob_spawn > 0) || ($user_access_lvl >= $sec_inc_gmlevel))
			{
				if ($adminshow)
				{
					echo "<center><form method=\"post\" action=\"i-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$i_name\"><input value=\" - Admin Hide Non-Spawned -\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemid\" type=\"hidden\" value=\"$i_id\"></form></center>\n";
				}
				else
				{
					echo "<center><form method=\"post\" action=\"i-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$i_name\"><input value=\" - Admin Show Non-Spawned -\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemid\" type=\"hidden\" value=\"$i_id\"><input name=\"adminshow\" type=\"hidden\" value=\"yes\"></form></center>\n";
				}
			}
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
					{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
			echo "<td class=\"drophead\">&nbsp;";
			if ($user_access_lvl >= $adjust_drops)
			{	echo "<form action=\"drops.php\">
				<input name=\"username\" type=\"hidden\" value=\"$username\">
				<input name=\"token\" type=\"hidden\" value=\"$token\">
				<input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
				<input name=\"langval\" type=\"hidden\" value=\"$langval\">
				<input name=\"dispby\" type=\"hidden\" value=\"item\">
				<input value=\"Adj. Drops\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form>";	
			}
			echo "</td><td width=\"250\" class=\"lefthead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"drophead\">Grade</p></td><td class=\"drophead\"><p class=\"dropmain\">Body Part</p></td><td class=\"drophead\"><p class=\"dropmain\">Armor Type</p></td><td class=\"drophead\"><p class=\"dropmain\">P.Def</p></td><td class=\"drophead\"><p class=\"dropmain\">MP</p></td><td class=\"drophead\"><p class=\"dropmain\">Weight</p></td><td class=\"drophead\"><p class=\"drophead\">Price</p></td><td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td><td class=\"drophead\"><p class=\"dropmain\">Rec</p></td></tr><tr>";
			
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"dropmain\">$i_id</td>"; }
			$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
			echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$i_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
			check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
			echo "</p></td>";
			echo "<td class=\"dropmain\">";
			if ($i_grade == "s84")
			{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
			elseif  ($i_grade == "s80")
			{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
			elseif  ($i_grade == "s")
			{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
			elseif  ($i_grade == "a")
			{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
			elseif  ($i_grade == "b")
			{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
			elseif  ($i_grade == "c")
			{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
			elseif  ($i_grade == "d")
			{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
			elseif  ($i_grade == "none")
			{ echo "&nbsp;"; }
			else
			{ echo "$i_grade"; }
			echo "</td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_body_part</p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_armor_type</p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_pdef</p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_bonus</p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
			echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
			echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
			$rec_result = mysql_query("select rec_id, level from knightrecch where makes = '$i_id' or rec_item = '$i_id'",$con);
			$rec_count = mysql_num_rows($rec_result);
			if ($rec_count)
			{	
				$rec_id = mysql_result($rec_result,0,"rec_id");
				$rec_level = mysql_result($rec_result,0,"level");
				echo "<td class=\"dropmain\"><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\"><img src=\"" . $images_dir . "rec" . $rec_level . ".gif\"></a></td>";	
			}
			else
			{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
			echo "</tr>";
			echo "</table></center>";
			$found_item = 1;
		}
		else
		{
			$sql = "select item_id, name, bodypart, weaponType, crystal_type, price, weight, atk_speed, p_dam, m_dam, mp_consume, soulshots, spiritshots, avoid_modify, shield_def, shield_def_rate from knightweapon where item_id = $itemid";
			if (!$result = mysql_query($sql,$con))
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			// If return array empty, then nothing found in armour.
			
			$count_r = mysql_num_rows($result);
			if (mysql_fetch_array($result))
			{
				$total_match = 1;
				
				$i_id = mysql_result($result,0,"item_id");
				$i_name = mysql_result($result,0,"name");
				$i_bodypart = mysql_result($result,0,"bodypart");
				$i_bodypart_title = part_name($i_bodypart);
				$i_weaponType = mysql_result($result,0,"weaponType");
				$i_grade = mysql_result($result,0,"crystal_type");
				$i_price = comaise(mysql_result($result,0,"price"));
				$orig_price =  mysql_result($result,0,"price");
				$i_weight = mysql_result($result,0,"weight");
				$i_atkspd = mysql_result($result,0,"atk_speed");
				$i_pdam = mysql_result($result,0,"p_dam");
				$i_mdam = mysql_result($result,0,"m_dam");
				$i_mpc = mysql_result($result,0,"mp_consume");
				$i_ss = mysql_result($result,0,"soulshots");
				$i_sps = mysql_result($result,0,"spiritshots");
				$i_amod = mysql_result($result,0,"avoid_modify");
				$i_sdef = mysql_result($result,0,"shield_def");
				$i_sdefr = mysql_result($result,0,"shield_def_rate");
				if ($i_sdef)
				{
					$i_pdam = $i_sdef;
					$i_mdam = $i_sdefr;
					$i_atkspd = $i_amod;
				}
				if (($mob_spawn > 0) || ($user_access_lvl >= $sec_inc_gmlevel))
				{
					if ($adminshow)
					{
						echo "<center><form method=\"post\" action=\"i-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$i_name\"><input value=\" - Admin Hide Non-Spawned -\" type=\"submit\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemid\" type=\"hidden\" value=\"$i_id\"></form></center>\n";
					}
					else
					{
						echo "<center><form method=\"post\" action=\"i-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$i_name\"><input value=\" - Admin Show Non-Spawned -\" type=\"submit\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemid\" type=\"hidden\" value=\"$i_id\"><input name=\"adminshow\" type=\"hidden\" value=\"yes\"></form></center>\n";
					}
				}
				echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"drophead\"><p class=\"drophead\">ID</p></td>"; }
				echo "<td class=\"drophead\">&nbsp;";
			if ($user_access_lvl >= $adjust_drops)
			{	echo "<form action=\"drops.php\">
				<input name=\"username\" type=\"hidden\" value=\"$username\">
				<input name=\"token\" type=\"hidden\" value=\"$token\">
				<input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
				<input name=\"langval\" type=\"hidden\" value=\"$langval\">
				<input name=\"dispby\" type=\"hidden\" value=\"item\">
				<input name=\"skin_id\" type=\"hidden\" value=\"$skin_id\">
				<input name=\"server_id\" type=\"hidden\" value=\"$server_id\">
				<input value=\"Adj. Drops\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form>";
			}
			echo "</td><td width=\"250\" class=\"lefthead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Grade</p></td><td class=\"drophead\"><p class=\"dropmain\">Body Part</p></td><td class=\"drophead\"><p class=\"dropmain\">P/M.atk</p></td><td class=\"drophead\"><p class=\"dropmain\">SS/SpS/MP</p></td><td class=\"drophead\"><p class=\"dropmain\">Speed</p></td><td class=\"drophead\"><p class=\"dropmain\">Weight</p></td><td class=\"drophead\"><p class=\"drophead\">Price</p></td><td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td><td class=\"drophead\"><p class=\"dropmain\">Rec</p></td></tr><tr>";
				
				if ($user_access_lvl >= $sec_inc_gmlevel)
					{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_id</p></td>"; }
				$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
				echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
				echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
				check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
				echo "</p></td>";
				echo "<td class=\"dropmain\">";
				if ($i_grade == "s84")
				{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
				elseif  ($i_grade == "s80")
				{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
				elseif  ($i_grade == "s")
				{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
				elseif  ($i_grade == "a")
				{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
				elseif  ($i_grade == "b")
				{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
				elseif  ($i_grade == "c")
				{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
				elseif  ($i_grade == "d")
				{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
				elseif  ($i_grade == "none")
				{ echo "&nbsp;"; }
				else
				{ echo "$i_grade"; }
				echo "</td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weaponType&nbsp;/&nbsp;$i_bodypart_title</p></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_pdam&nbsp;/&nbsp;$i_mdam</p></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\">x<font color=$green_code>$i_ss</font>&nbsp;/&nbsp;x<font color=#6B5D10>$i_sps</font>&nbsp;/&nbsp;<font color=$blue_code>$i_mpc</font></p></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_atkspd</p></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
				echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
				echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
				$rec_result = mysql_query("select rec_id, level from knightrecch where makes = '$i_id' or rec_item = '$i_id'",$con);
				$rec_count = mysql_num_rows($rec_result);
				if ($rec_count)
				{	
					$rec_id = mysql_result($rec_result,0,"rec_id");
					$rec_level = mysql_result($rec_result,0,"level");
					echo "<td class=\"dropmain\"><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\"><img src=\"" . $images_dir . "rec" . $rec_level . ".gif\"></a></td>";	
				}
				else
				{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
				echo "</tr>";				
				echo "</table></center>";
				$found_item = 1;
			}
			else
			{
				$sql = "select item_id, name, crystal_type, weight, material, price from knightetcitem where item_id = $itemid";
				if (!$result = mysql_query($sql,$con))
				{
					die('Could not retrieve from knightdrop database: ' . mysql_error());
				}
				
				$count_r = mysql_num_rows($result);

				if (mysql_fetch_array($result))
				{
	
					$i_id = mysql_result($result,0,"item_id");
					$i_name = mysql_result($result,0,"name");
					$i_weight = mysql_result($result,0,"weight");
					$i_price = comaise(mysql_result($result,0,"price"));
					$orig_price =  mysql_result($result,0,"price");
					$i_grade = mysql_result($result,0,"crystal_type");
					$i_mat = mysql_result($result,0,"material");
					if (($mob_spawn > 0) || ($user_access_lvl >= $sec_inc_gmlevel))
					{
						if ($adminshow)
						{
							echo "<center><form method=\"post\" action=\"i-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$i_name\"><input value=\" - Admin Hide Non-Spawned -\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemid\" type=\"hidden\" value=\"$i_id\"></form></center>\n";
						}
						else
						{
							echo "<center><form method=\"post\" action=\"i-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$i_name\"><input value=\" - Admin Show Non-Spawned -\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemid\" type=\"hidden\" value=\"$i_id\"><input name=\"adminshow\" type=\"hidden\" value=\"yes\"></form></center>\n";
						}
					}
					echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
					if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"drophead\"><p class=\"drophead\">ID</p></td>"; }
					echo "<td class=\"drophead\">&nbsp;";
			if ($user_access_lvl >= $adjust_drops)
			{	echo "<form action=\"drops.php\">
				<input name=\"username\" type=\"hidden\" value=\"$username\">
				<input name=\"token\" type=\"hidden\" value=\"$token\">
				<input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
				<input name=\"langval\" type=\"hidden\" value=\"$langval\">
				<input name=\"dispby\" type=\"hidden\" value=\"item\">
				<input name=\"skin_id\" type=\"hidden\" value=\"$skin_id\">
				<input name=\"server_id\" type=\"hidden\" value=\"$server_id\">
				<input value=\"Adj. Drops\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form>";	
			}
			echo "</td><td width=\"250\" class=\"lefthead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Grade</p></td><td class=\"drophead\"><p class=\"dropmain\">Material</p></td><td class=\"drophead\"><p class=\"dropmain\">Weight</p></td><td class=\"drophead\"><p class=\"drophead\">Price</p></td><td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td><td class=\"drophead\"><p class=\"dropmain\">Rec</p></td></tr><tr>";
					if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_id</p></td>"; }
					$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
					echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
					echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
					check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
					echo "</p></td>";
					echo "<td class=\"dropmain\">";
					if ($i_grade == "s84")
					{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
					elseif  ($i_grade == "s80")
					{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
					elseif  ($i_grade == "s")
					{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
					elseif  ($i_grade == "a")
					{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
					elseif  ($i_grade == "b")
					{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
					elseif  ($i_grade == "c")
					{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
					elseif  ($i_grade == "d")
					{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
					elseif  ($i_grade == "none")
					{ echo "&nbsp;"; }
					else
					{ echo "$i_grade"; }
					echo "</td>";
					echo "<td class=\"dropmain\"><p class=\"dropmain\">";
					if ($i_mat == "adamantaite")
					{ echo "<img src=\"" . $images_dir . "items/1024.gif\" title=\"adamantaite\">"; }
					elseif ($i_mat == "liquid")
					{ echo "<img src=\"" . $images_dir . "items/1764.gif\" title=\"liquid\">"; }
					elseif ($i_mat == "paper")
					{ echo "<img src=\"" . $images_dir . "items/1695.gif\" title=\"paper\">"; }
					elseif ($i_mat == "crystal")
					{ echo "<img src=\"" . $images_dir . "items/3365.gif\" title=\"crystal\">"; }
					elseif ($i_mat == "steel")
					{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"steel\">"; }
					elseif ($i_mat == "fine_steel")
					{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"fine_steel\">"; }
					elseif ($i_mat == "bone")
					{ echo "<img src=\"" . $images_dir . "items/1872.gif\" title=\"bone\">"; }
					elseif ($i_mat == "bronze")
					{ echo "<img src=\"" . $images_dir . "items/626.gif\" title=\"bronze\">"; }
					elseif ($i_mat == "cloth")
					{ echo "<img src=\"" . $images_dir . "items/1729.gif\" title=\"cloth\">"; }
					elseif ($i_mat == "gold")
					{ echo "<img src=\"" . $images_dir . "items/1289.gif\" title=\"gold\">"; }
					elseif ($i_mat == "leather")
					{ echo "<img src=\"" . $images_dir . "items/1689.gif\" title=\"leather\">"; }
					elseif ($i_mat == "mithril")
					{ echo "<img src=\"" . $images_dir . "items/1876.gif\" title=\"mithril\">"; }
					elseif ($i_mat == "silver")
					{ echo "<img src=\"" . $images_dir . "items/1873.gif\" title=\"silver\">"; }
					elseif ($i_mat == "wood")
					{ echo "<img src=\"" . $images_dir . "items/2109.gif\" title=\"wood\">"; }
					else
					{ echo "$i_mat"; }
					echo "</p></td>";
					echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
					echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
					echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
					$rec_result = mysql_query("select rec_id, level from knightrecch where makes = '$i_id' or rec_item = '$i_id'",$con);
					$rec_count = mysql_num_rows($rec_result);
					if ($rec_count)
					{	
						$rec_id = mysql_result($rec_result,0,"rec_id");
						$rec_level = mysql_result($rec_result,0,"level");
						echo "<td class=\"dropmain\"><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\"><img src=\"" . $images_dir . "rec" . $rec_level . ".gif\"></a></td>";	
					}
					else
					{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
					echo "</tr>";
					echo "</table></center>";
					echo "<p class=\"dropmain\">&nbsp;</p>";
					$found_item = 1;
				}
			}
		}
		
		if ($found_item)
		{
			$sql = "select price, shop_id from merchant_buylists where item_id = $itemid union select price, shop_id from custom_merchant_buylists where item_id = $itemid order by price";
			$result2 = mysql_query($sql,$con);
			$count_r = mysql_num_rows($result2);
			$shop_shown = 0;
			if ($count_r)
			{	
				
				$i=0;
				while ($i < $count_r) 
				{
					if (mysql_result($result2,$i,"price") <= 0)
					{	$i_price = comaise($orig_price);	}
					else
					{	$i_price = comaise(mysql_result($result2,$i,"price"));	}
					$i_shopid = mysql_result($result2,$i,"shop_id");

					$sql = "select npc_id from merchant_shopids where shop_id = $i_shopid union select npc_id from custom_merchant_shopids where shop_id = $i_shopid";
					$result3 = mysql_query($sql,$con);
					$count_r2 = mysql_num_rows($result3);
					if ($result3)
					{
						if ($count_r2)
						{	$i_npcid = mysql_result($result3,0,"npc_id");	}
						else
						{	$i_npcid = "CUST";	}
						if (($i_npcid <> "gm") && ($i_npcid <> "CUST"))
						{
							$sql = "select name from npc where id = $i_npcid union select name from custom_npc where id = $i_npcid";
							$result6 = mysql_query($sql,$con);
							if ($result6)
							{	$count_recs = mysql_num_rows($result6);
								if ($count_recs)
								{	$i_trader = mysql_result($result6,0,"name");	}
								else
								{	$i_trader = "$lang_unknown";	}
							}
							else
							{	$i_trader = "$lang_unknown";	}
							$i_area_name = "Unknown or Custom Shop";
							$i_area_tax = "n/a or custom";
							$i_area_chaotic = -1;
							$i_area_name = shop_loc($i_npcid, $db_location, $db_user, $db_psswd, $db_l2jdb, $lang_unknown);
							if (!$shop_shown)
							{
								echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\"><td colspan=\"5\" class=\"drophead\"><p class=\"dropmain\">SHOPS</p></td></tr><tr class=\"thead\">";
								echo "<td width=\"180\" class=\"drophead\"><p class=\"dropmain\">Trader Name</p></td><td width=\"180\" class=\"drophead\"><p class=\"dropmain\">Shop Area</p></td><td width=\"70\" class=\"drophead\"><p class=\"left\">Price</p></td><td width=\"60\" class=\"drophead\"><p class=\"dropmain\">$lang_tax</p></td><td class=\"drophead\"><p class=\"dropmain\">Chaotic ?</p></td></tr>";
								$shop_shown = 1;
							}
							echo "<tr><td class=\"dropmain\"><p class=\"dropmain\"><strong class=\"dropmain\"><a href=\"m-search.php?$itemname=$mob_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$i_npcid\" class=\"dropmain\">$i_trader</a></strong></p></td><td class=\"dropmain\"><p class=\"dropmain\"><a href=\"l-search.php?itemname=$i_area_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"dropmain\">$i_area_name</a></p></td>";
							echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td><td class=\"dropmain\">$i_area_tax</td>";
							if ($i_area_chaotic > 0)
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code><strong class=\"dropmain\">Yes</strong></font></p></td>"; }
							elseif ($i_area_chaotic == 0)
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><font color=$green_code>No</font></p></td>"; }
							else
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\"><font color=$yellow_code>Unknown</font></p></td>"; }
							echo "</tr>";
						}
					}
					$i++;
				}
				if ($shop_shown)
				{
					echo "</table></center>";
				}
			} 
			
			$result5 = mysql_query("show table status like 'knightquestrun'",$con);
			while ($r_array = mysql_fetch_assoc($result5))
			{
				$result6 = mysql_query("select quest_id from knightquestrun where target_id = '$itemid' and type = 1",$con);
				$title = 0;
				while ($r_array = mysql_fetch_assoc($result6))
				{
					if ($title == 0)
					{	
						echo "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" class=\"blanktab\"><tr><td colspan=\"2\" class=\"lefthead\"><p class=\"dropmain\">Quests Involved In</p></td></tr>";
						$title = 1;
					}
					$quest_id = $r_array['quest_id'];
					$result7 = mysql_query("select name from knightquests where quest_id = '$quest_id'",$con);
					$quest_name = mysql_result($result7,0,"name");
					echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$quest_name</p></td><td class=\"dropmain\"><p class=\"dropmain\">";
					$result7 = mysql_query("select target_id from knightquestrun where quest_id = '$quest_id' and type = 2",$con);
					$chars_used = 0;
					while ($r_array = mysql_fetch_assoc($result7))
					{
						$char_num = $r_array['target_id'];
						if ($chars_used == 1)
						{	echo ", ";	}
						$result8 = mysql_query("select name, level, `type`, aggro from knightnpc where id = '$char_num'",$con);
						$m_name = mysql_result($result8,0,"name");
						$m_level = mysql_result($result8,0,"level");
						$m_type = mysql_result($result8,0,"type");
						$m_aggro = mysql_result($result8,0,"aggro");
						echo "<a href=\"m-search.php?$itemname=$mob_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$char_num\" class=\"dropmain\">";
						if (($m_type == "L2Monster") || ($m_type == "L2Minion") || ($m_type == "L2Boss") || ($m_type == "L2RaidBoss"))
						{	
							if ($m_aggro)
							{	echo "<font color=$red_code>$m_name</font></a>";	}
							else
							{	echo "<font color=$green_code>$m_name</font></a>";	}
							echo "<small>($m_level)</small>";
						}
						else
						{	echo "$m_name</a>";	}
						$chars_used = 1;
					}
					echo "&nbsp;</p></td></tr>";
				}
				if ($title == 1)
				{	echo "</table></center>";	}
			}
			
			echo "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
			echo "<tr><td valign=\"top\" width=\"50%\" class=\"noborder\"><center>";
			echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td colspan=\"7\" class=\"lefthead\"><p class=\"dropmain\">DROPS</p></td></tr><tr class=\"thead\">";
			echo "<td width=\"170\" class=\"drophead\"><p class=\"left\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Lvl</p></td><td class=\"drophead\"><p class=\"dropmain\">Min/Max</p></td><td class=\"drophead\" colspan=\"3\"><p class=\"dropmain\">$lang_spawn<br><font color=$green_code>$lang_day...</font><font color=$red_code>$lang_night...</font>$lang_always</p></td><td class=\"drophead\"><p class=\"dropmain\">Chance</p></td></tr>";
			
			$drop_engine = 0;
			$sql = "show fields from droplist";
			$result2 = mysql_query($sql,$con);
			while ($r_array = mysql_fetch_assoc($result2)) 
			{
				if (strcasecmp($r_array['Field'],"category") == 0)
				{ $drop_engine = 1; }
			}
			$sql = "select mobid, min, max, sweep, chance from droplist where itemid = $itemid union select mobid, min, max, sweep, chance from custom_droplist where itemid = $itemid";
			if ($drop_engine)
			{	$sql = "select mobid, min, max, category, chance from droplist where itemid = $itemid union select mobid, min, max, category, chance from custom_droplist where itemid = $itemid";	}
			$result2 = mysql_query($sql,$con);
			
			$itm_array = ARRAY();
			while ($r_array = mysql_fetch_assoc($result2))
			{
				$mob_id = $r_array['mobid'];
				$item_chance = $r_array['chance'];
				
				if ($drop_engine)
				{
					$item_category = $r_array['category'];
					if ($item_category < 0)
					{	$item_sweep = 1;	}
					else
					{	$item_sweep = 0;	}
				}
				else
				{
					$item_sweep = $r_array['sweep'];
				}
				if ($drop_engine)
				{	
					if ($item_sweep)
					{	$t_pcnt = 100;	}
					else
					{
						$catg = $r_array['category'];
						$result3 = mysql_query("select chance from droplist where mobId = $mob_id and category = $catg union select chance from custom_droplist where mobId = $mob_id and category = $catg",$con);
						$t_pcnt = 0;
						while ($r_array3 = mysql_fetch_assoc($result3))
						{
							$t_pcntp = $r_array3['chance']/10000;
							if ($item_id == 57)
							{	$t_pcntp *= $drop_chance_adena;	}
							elseif (!$item_sweep)
							{   $t_pcntp  *= $drop_chance_item;	}
							else
							{	$t_pcntp *= $drop_chance_spoil;	}
							if ($t_pcntp > 100)
							{	$t_pcntp = 100;	}
							$t_pcnt += $t_pcntp;
						}
					}
				}
				else
				{	$t_pcnt = 0.001;	}
				if ($item_id == 57)
				{	$item_chance *= $drop_chance_adena;	}
				elseif (!$item_sweep)
				{   $item_chance *= $drop_chance_item;	}
				else
				{	$item_chance *= $drop_chance_spoil;	}
				$item_chance /=10000;
				$i_min = $r_array['min'];
				$i_max = $r_array['max'];
				if ($item_chance > 100)
				{
					$chance_multiply = intval($item_chance / 100);
					$i_min = $i_min * $chance_multiply;
					$chance_multiply *= 100;
					if ($chance_multiply < $item_chance)
					{	$chance_multiply += 100;	}
					$chance_multiply /= 100;
					$i_max = $i_max * $chance_multiply;	
				}
				if ($drop_engine)
				{	
					if ($t_pcnt > 100)
					{	
						if ($item_sweep)
						{	$item_chance = 100;	}
						else
						{	$item_chance = intval(((100/$t_pcnt)*$item_chance)*10000)/10000;	}
					}
				}
				
				$error_finding = 0;
				$sql = "select name, type, level from npc where id = $mob_id union select name, type, level from custom_npc where id = $mob_id";  // Try armour database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$mob_name = "ERROR";
					$mob_level = 0;
					$mob_type = "";
				}
				else
				{
					$mob_name = mysql_result($result3,0,"name");
					$mob_level = mysql_result($result3,0,"level");
					$mob_type = "";
					if (mysql_result($result3,0,"type") == "L2RaidBoss")
					{	$mob_type = " <font color=$red_code><small>(raidboss)</small></font>";	}
					elseif (mysql_result($result3,0,"type") == "L2Minion")
					{	$mob_type = " <font color=$red_code><small>(minion)</small></font>";	}
					elseif (mysql_result($result3,0,"type") == "L2Boss")
					{	$mob_type = " <font color=$red_code><small>(boss)</small></font>";	}
					elseif (mysql_result($result3,0,"type") == "L2PenaltyMonster")
					{	$mob_type = " <font color=$red_code><small>(penalty)</small></font>";	}
				}
				
				array_push($itm_array, array($item_chance,$r_array['mobid'],$mob_name,$i_min,$i_max , $item_sweep, $mob_level, $mob_type, mysql_result($result3,0,"type")));
			}
			
			$count_r = count($itm_array);

			if ($count_r > 0)
			{
				arsort($itm_array);
				reset($itm_array);
			}

			while (list($k1) = each($itm_array)) 
			{
				$i_array = $itm_array[$k1];
				$mob_id = $i_array[1];
				$mob_name = $i_array[2];
				$mob_level = $i_array[6];
				$mob_type = $i_array[7];
				$mob_style = $i_array[8];
				$item_min = $i_array[3];
				$item_max = $i_array[4];
				$item_sweep = $i_array[5];
				$item_chance = $i_array[0];
				if (($drop_engine) && ($itemid == 57))
				{
					$item_min = $item_min * $drop_chance_adena;
					$item_max = $item_max * $drop_chance_adena;
				}
				$mob_spwn = mobcount($mob_id,$db_location,$db_user,$db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb);
				$mob_spawnnum = $mob_spwn[1];
				$mob_count = $mob_spwn[0];
				$mob_days = $mob_spwn[2];
				$mob_dayt = $mob_spwn[3];
				$mob_nights = $mob_spwn[4];
				$mob_nightt = $mob_spwn[5];
				$mob_normals = $mob_spwn[6];
				$mob_normalt = $mob_spwn[7];

				if (($mob_spawnnum > 0) || (($user_access_lvl >= $sec_inc_gmlevel) && ($adminshow)) || ($mob_style == 'L2Boss') || ($mob_style == 'L2RaidBoss') || ($mob_style == 'L2Minion') || ($mob_style == 'L2PenaltyMonster'))
				{
					if ($item_sweep < 1)
					{
						echo "<tr>";
						
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"m-search.php?$itemname=$mob_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$mob_id\" class=\"dropmain\">$mob_name $mob_type</a></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code><strong class=\"dropmain\">$mob_level</strong></font></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$item_min&nbsp;/&nbsp;<strong class=\"dropmain\">$item_max</strong></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\"><font color=$green_code>$mob_days/$mob_dayt</font></p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code>$mob_nights/$mob_nightt</font></p></td><td class=\"dropmain\"><p class=\"dropmain\">$mob_normals/$mob_normalt</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">";
						if ($item_chance >= $drop_chance_green)
						{ echo "<strong class=\"dropmain\"><font color=$green_code>$item_chance%</font></strong>"; }
						elseif ($item_chance >= $drop_chance_blue)
						{ echo "<strong class=\"dropmain\"><font color=$blue_code>$item_chance%</font></strong>"; }
						else
						{ echo "<strong class=\"dropmain\"><font color=$red_code>$item_chance%</font></strong>"; }
						echo "</p></td>";
						
						echo "</tr>";
					}
					else
					{
						$spoil_count++;
						if (!$spoil_array)
						{
						$spoil_array = array(array($mob_id, $mob_name, $item_min, $item_max, $item_chance, $mob_spawnnum, $mob_level, $mob_count, $mob_days, $mob_dayt, $mob_nights, $mob_nightt, $mob_normals, $mob_normalt, $mob_type));
						}
						else
						{
							array_push($spoil_array, array($mob_id, $mob_name, $item_min, $item_max, $item_chance, $mob_spawnnum, $mob_level, $mob_count, $mob_days, $mob_dayt, $mob_nights, $mob_nightt, $mob_normals, $mob_normalt, $mob_type));
						}
					}
				}
			}
			
			echo "</table>";
			echo "</center></td>";
			echo "<td valign=\"top\" width=\"50%\" class=\"noborder\"><center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td colspan=\"7\" class=\"lefthead\"><p class=\"dropmain\">SPOILS</p></td></tr><tr class=\"thead\">";
			echo "<td width=\"170\" class=\"lefthead\"><p class=\"left\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Lvl</p></td><td class=\"drophead\"><p class=\"dropmain\">Min/Max</p></td><td class=\"drophead\" colspan=\"3\"><p class=\"dropmain\">$lang_spawn<br><font color=$green_code>$lang_day...</font><font color=$red_code>$lang_night...</font>$lang_always</p></td><td class=\"drophead\"><p class=\"dropmain\">Chance</p></td></tr>";
	
			$i=0;
			if ($spoil_array)
			{
				while ($i < $spoil_count) 
				{
					list($k1) = each($spoil_array);
					$i_array = $spoil_array[$k1];
					$mob_id = $i_array[0];
					$mob_name = $i_array[1];
					$mob_level = $i_array[6];
					$item_min = $i_array[2];
					$item_max = $i_array[3];
					$item_chance = $i_array[4];
					if (($drop_engine) && ($itemid == 57))
					{
						$item_min = $item_min * $drop_chance_adena;
						$item_max = $item_max * $drop_chance_adena;
					}
					if ($item_chance > 100)
					{	$item_chance = 100;	}
					$mob_spawns = $i_array[5];
					$mob_count = $i_array[7];
					$mob_days = $i_array[8];
					$mob_dayt = $i_array[9];
					$mob_nights = $i_array[10];
					$mob_nightt = $i_array[11];
					$mob_normals = $i_array[12];
					$mob_normalt = $i_array[13];
					$mob_type = $i_array[14];
					echo "<tr>";
					echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"m-search.php?$itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$mob_id\" class=\"dropmain\">$mob_name $mob_type</a></p></td>";
					echo "<td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code><strong class=\"dropmain\">$mob_level</strong></font></p></td>";
					echo "<td class=\"dropmain\"><p class=\"dropmain\">$item_min&nbsp;/&nbsp;<strong class=\"dropmain\">$item_max</strong></p></td>";
					echo "<td class=\"dropmain\"><p class=\"dropmain\"><font color=$green_code>$mob_days/$mob_dayt</font></p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code>$mob_nights/$mob_nightt</font></p></td><td class=\"dropmain\"><p class=\"dropmain\">$mob_normals/$mob_normalt</p></td>";
					echo "<td class=\"dropmain\"><p class=\"dropmain\">";
					if ($item_chance >= $drop_chance_green)
					{ echo "<strong class=\"dropmain\"><font color=$green_code>$item_chance%</font></strong>"; }
					elseif ($item_chance >= $drop_chance_blue)
					{ echo "<strong class=\"dropmain\"><font color=$blue_code>$item_chance%</font></strong>"; }
					else
					{ echo "<strong class=\"dropmain\"><font color=$red_code>$item_chance%</font></strong>"; }
					echo "</p></td>";
					echo "</tr>";
					$i++;
				}
			}
	
			echo "</table>";
			echo "</center></td></table><p class=\"dropmain\">&nbsp;</p>";
		}
		else
		{
			writewarn("Can't find the item.");
		}
	}
	else
	{
		if (strlen($itemname) < $minlenitem)
		{	writewarn("Please give at least $minlenitem characters.");	}
		else
		{
			// Connect to DB

			$order_by = "order by name";
			if ($i_sort == "2")
			{	$order_by = "order by name";	}
			elseif ($i_sort == "3")
			{	$order_by = "order by name desc";	}
					elseif ($i_sort == "4")
			{	$order_by = "order by weight, name";	}
			elseif ($i_sort == "5")
			{	$order_by = "order by weight desc, name desc";	}
			elseif ($i_sort == "6")
			{	$order_by = "order by price, name";	}
			elseif ($i_sort == "7")
			{	$order_by = "order by price desc, name desc";	}
			$order_by2 = $order_by;
			$order_by3 = $order_by;
			if ($i_sort == "8")
			{	$order_by = "order by p_def, name";	}
			elseif ($i_sort == "9")
			{	$order_by = "order by p_def desc, name desc";	}
			elseif ($i_sort == "10")
			{	$order_by = "order by m_def, name";	}
			elseif ($i_sort == "11")
			{	$order_by = "order by m_def desc, name desc";	}
			if ($i_sort == "12")
			{	$order_by2 = "order by p_dam, name";	}
			elseif ($i_sort == "13")
			{	$order_by2 = "order by p_dam desc, name desc";	}
			elseif ($i_sort == "14")
			{	$order_by2 = "order by m_dam, name";	}
			elseif ($i_sort == "15")
			{	$order_by2 = "order by m_dam desc, name desc";	}
			elseif ($i_sort == "16")
			{	$order_by2 = "order by soulshots, name";	}
			elseif ($i_sort == "17")
			{	$order_by2 = "order by soulshots desc, name desc";	}
			elseif ($i_sort == "18")
			{	$order_by2 = "order by spiritshots, name";	}
			elseif ($i_sort == "19")
			{	$order_by2 = "order by spiritshots desc, name desc";	}
			elseif ($i_sort == "20")
			{	$order_by2 = "order by mp_consume, name";	}
			elseif ($i_sort == "21")
			{	$order_by2 = "order by mp_consume desc, name desc";	}
			elseif ($i_sort == "22")
			{	$order_by2 = "order by atk_speed, name";	}
			elseif ($i_sort == "23")
			{	$order_by2 = "order by atk_speed desc, name desc";	}
			elseif ($i_sort == "24")
			{	$order_by3 = "order by material, name";	}
			elseif ($i_sort == "25")
			{	$order_by3 = "order by material desc, name desc";	}
			elseif ($i_sort == "28")
			{	$order_by3 = "order by bodypart desc, name";	}
			elseif ($i_sort == "29")
			{	$order_by3 = "order by bodypart desc, name desc";	}
			elseif ($i_sort == "30")
			{	$order_by3 = "order by armor_type desc, name";	}
			elseif ($i_sort == "31")
			{	$order_by3 = "order by armor_type desc, name desc";	}
			elseif ($i_sort == "32")
			{	$order_by3 = "order by weaponType desc, name";	}
			elseif ($i_sort == "33")
			{	$order_by3 = "order by weaponType desc, name desc";	}

			$title = 0;
			$sql = "select item_id, name, m_def, weight, price, p_def, crystal_type, bodypart, armor_type from knightarmour where name like '%$itemname%' ";
			$grade = 0;
			while ($grade < 8)
			{

				// Query for item name in weapon database
				if ($i_sort < 2)
				{
					$grade_s = $grade;
				if ($i_sort == 1)
				{	$grade_s = 6 - $grade;	}
				if ($grade_s == 0)
				{	$sql_q = $sql . "and crystal_type = 'none' " . $order_by;	}
				elseif ($grade_s == 1)
				{	$sql_q = $sql . "and crystal_type = 'd' " . $order_by;	}
				elseif ($grade_s == 2)
				{	$sql_q = $sql . "and crystal_type = 'c' " . $order_by;	}
				elseif ($grade_s == 3)
				{	$sql_q = $sql . "and crystal_type = 'b' " . $order_by;	}
				elseif ($grade_s == 4)
				{	$sql_q = $sql . "and crystal_type = 'a' " . $order_by;	}
				elseif ($grade_s == 5)
				{	$sql_q = $sql . "and crystal_type = 's' " . $order_by;	}
				elseif ($grade_s == 6)
				{	$sql_q = $sql . "and crystal_type = 's80' " . $order_by;}
				elseif ($grade_s == 7)
				{	$sql_q = $sql . "and crystal_type = 's84' " . $order_by;}
				}
				else
				{
					$sql_q = $sql . $order_by;
					$grade = 6;
				}
				if (!$result = mysql_query($sql_q,$con))
				{
					die('Could not retrieve from knightdrop database: ' . mysql_error());
				}
				// If return array empty, then nothing found in armour.
			
				$count_r = mysql_num_rows($result);
				if (mysql_fetch_array($result))
				{
					$i=0;
					$total_match = 1;
					if (!$title)
					{
						echo "<p class=\"dropmain\">&nbsp;</p>";
						echo "<h2 class=\"dropmain\">$lang_arm_and_a </h2>";
						echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
						if ($user_access_lvl >= $sec_inc_gmlevel)
								{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
						echo "<td class=\"drophead\">&nbsp;</td>";
						echo "<td width=\"250\" class=\"lefthead\"><p class=\"dropmain\"><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=3\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_name</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=2\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=1\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Grade</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=0\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=29\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Body Part</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=28\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=31\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Armor Type</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=30\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=9\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">P.Def</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=8\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=11\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">M.Def</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=10\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=5\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Weight</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=4\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=7\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Price</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=6\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\">Rec</p></td>";
						echo "</tr><tr>";
						$title = 1;
					}
					while ($i < $count_r) 
					{
						$i_id = mysql_result($result,$i,"item_id");
						$i_name = mysql_result($result,$i,"name");
						$i_bonus = mysql_result($result,$i,"m_def");
						$i_weight = mysql_result($result,$i,"weight");
						$i_price = comaise(mysql_result($result,$i,"price"));
						$i_pdef = mysql_result($result,$i,"p_def");
						$i_grade = mysql_result($result,$i,"crystal_type");
						$i_body_part = part_name(mysql_result($result,$i,"bodypart"));
						$i_armor_type = mysql_result($result,$i,"armor_type");
						if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"dropmain\">$i_id</td>"; }
						
						$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($i_grade == "s84")
						{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
						elseif  ($i_grade == "s80")
						{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
						elseif  ($i_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($i_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($i_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($i_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($i_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						elseif  ($i_grade == "none")
						{ echo "&nbsp;"; }
						else
						{ echo "$i_grade"; }
						echo "</td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_body_part</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_armor_type</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_pdef</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_bonus</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						$sql2 = "select rec_id, level from knightrecch where makes = '$i_id'";
						$rec_result = mysql_query($sql2,$con);
						$rec_count = mysql_num_rows($rec_result);
						if ($rec_count)
						{	
							$rec_id = mysql_result($rec_result,0,"rec_id");
							$rec_level = mysql_result($rec_result,0,"level");
							echo "<td class=\"dropmain\"><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\"><img src=\"" . $images_dir . "rec" . $rec_level . ".gif\"></a></td>";	
						}
						else
						{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
						echo "</tr>";
						$i++;
					}
				}
				$grade++;
			}
			if ($title)
			{	echo "</table></center><p class=\"dropmain\">&nbsp;</p>";	}
			
			// Query for item name in weapon database
			$title = 0;
			$sql = "select item_id, name, bodypart, weaponType, crystal_type, price, weight, atk_speed, p_dam, m_dam, mp_consume, soulshots, spiritshots, avoid_modify, shield_def, shield_def_rate from knightweapon where name like '%$itemname%' ";
			$grade = 0;
			while ($grade < 8)
			{

				// Query for item name in weapon database
				if ($i_sort < 2)
				{
					$grade_s = $grade;
				if ($i_sort == 1)
				{	$grade_s = 6 - $grade;	}
				if ($grade_s == 0)
				{	$sql_q = $sql . "and crystal_type = 'none' " . $order_by;	}
				elseif ($grade_s == 1)
				{	$sql_q = $sql . "and crystal_type = 'd' " . $order_by;	}
				elseif ($grade_s == 2)
				{	$sql_q = $sql . "and crystal_type = 'c' " . $order_by;	}
				elseif ($grade_s == 3)
				{	$sql_q = $sql . "and crystal_type = 'b' " . $order_by;	}
				elseif ($grade_s == 4)
				{	$sql_q = $sql . "and crystal_type = 'a' " . $order_by;	}
				elseif ($grade_s == 5)
				{	$sql_q = $sql . "and crystal_type = 's' " . $order_by;	}
				elseif ($grade_s == 6)
				{	$sql_q = $sql . "and crystal_type = 's80' " . $order_by;}
				elseif ($grade_s == 7)
				{	$sql_q = $sql . "and crystal_type = 's84' " . $order_by;}
				}
				else
				{
					$sql_q = $sql . $order_by2;
					$grade = 6;
				}

				if (!$result = mysql_query($sql_q,$con))
				{
					die('Could not retrieve from knightdrop database: ' . mysql_error());
				}
				// If return array empty, then nothing found in armour.
			
				$count_r = mysql_num_rows($result);
				if (mysql_fetch_array($result))
				{
					$i=0;
					$total_match = 1;
					if (!$title)
					{
						echo "<p class=\"dropmain\">&nbsp;</p>";
						echo "<h2 class=\"dropmain\">$lang_weapon</h2>";
						echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
						if ($user_access_lvl >= $sec_inc_gmlevel)
								{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
						echo "<td class=\"drophead\">&nbsp;</td>";
						echo "<td width=\"250\" class=\"lefthead\"><p class=\"dropmain\"><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=3\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_name</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=2\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=1\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Grade</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=0\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=33\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Weapon Type</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=32\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=13\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=15\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">P/M.atk</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=12\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=14\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=17\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=19\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=21\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br>";
						echo "<strong class=\"dropmain\">SS/SpS/MP</strong>";
						echo "<br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=16\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=18\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=20\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=23\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Speed</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=22\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=5\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Weight</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=4\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=7\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Price</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=6\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\">Rec</p></td>";
						echo "</tr><tr>";		
						$title = 1;
					}
					while ($i < $count_r) 
					{
						$i_id = mysql_result($result,$i,"item_id");
						$i_name = mysql_result($result,$i,"name");
						$i_bodypart = mysql_result($result,$i,"bodypart");
						$i_bodypart_title = part_name($i_bodypart);
						$i_weaponType = mysql_result($result,$i,"weaponType");
						$i_grade = mysql_result($result,$i,"crystal_type");
						$i_price = comaise(mysql_result($result,$i,"price"));
						$i_weight = mysql_result($result,$i,"weight");
						$i_atkspd = mysql_result($result,$i,"atk_speed");
						$i_pdam = mysql_result($result,$i,"p_dam");
						$i_mdam = mysql_result($result,$i,"m_dam");
						$i_mpc = mysql_result($result,$i,"mp_consume");
						$i_ss = mysql_result($result,$i,"soulshots");
						$i_sps = mysql_result($result,$i,"spiritshots");
						$i_amod = mysql_result($result,$i,"avoid_modify");
						$i_sdef = mysql_result($result,$i,"shield_def");
						$i_sdefr = mysql_result($result,$i,"shield_def_rate");
						if ($i_sdef)
						{
							$i_pdam = $i_sdef;
							$i_mdam = $i_sdefr;
							$i_atkspd = $i_amod;
						}
						if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_id</p></td>"; }
						$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($i_grade == "s84")
						{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
						elseif  ($i_grade == "s80")
						{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
						elseif  ($i_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($i_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($i_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($i_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($i_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						elseif  ($i_grade == "none")
						{ echo "&nbsp;"; }
						else
						{ echo "$i_grade"; }
						echo "</td>";
						if ($i_bodypart == "rhand")
						{$i_bodypart="One Handed";}
						elseif ($i_bodypart == "lrhand")
						{$i_bodypart="Two Handed";}
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weaponType&nbsp;/&nbsp;$i_bodypart_title</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_pdam&nbsp;/&nbsp;$i_mdam</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">x<font color=$green_code>$i_ss</font>&nbsp;/&nbsp;x<font color=#6B5D10>$i_sps</font>&nbsp;/&nbsp;<font color=$blue_code>$i_mpc</font></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_atkspd</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						$sql2 = "select rec_id, level from knightrecch where makes = '$i_id'";
						$rec_result = mysql_query($sql2,$con);
						$rec_count = mysql_num_rows($rec_result);
						if ($rec_count)
						{	
							$rec_id = mysql_result($rec_result,0,"rec_id");
							$rec_level = mysql_result($rec_result,0,"level");
							echo "<td class=\"dropmain\"><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\"><img src=\"" . $images_dir . "rec" . $rec_level . ".gif\"></a></td>";	
						}
						else
						{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
						echo "</tr>";
					$i++;
					}
				}
				$grade++;
			}
			if ($title)
			{	echo "</table></center><p class=\"dropmain\">&nbsp;</p>";	}
			
			// Query etcitem database.
			$title = 0;
			$sql = "select item_id, name, crystal_type, weight, material, price from knightetcitem where name like '%$itemname%' ";
			$grade = 0;
			while ($grade < 8)
			{

				// Query for item name in weapon database
				if ($i_sort < 2)
				{
					$grade_s = $grade;
				if ($i_sort == 1)
				{	$grade_s = 6 - $grade;	}
				if ($grade_s == 0)
				{	$sql_q = $sql . "and crystal_type = 'none' " . $order_by;	}
				elseif ($grade_s == 1)
				{	$sql_q = $sql . "and crystal_type = 'd' " . $order_by;	}
				elseif ($grade_s == 2)
				{	$sql_q = $sql . "and crystal_type = 'c' " . $order_by;	}
				elseif ($grade_s == 3)
				{	$sql_q = $sql . "and crystal_type = 'b' " . $order_by;	}
				elseif ($grade_s == 4)
				{	$sql_q = $sql . "and crystal_type = 'a' " . $order_by;	}
				elseif ($grade_s == 5)
				{	$sql_q = $sql . "and crystal_type = 's' " . $order_by;	}
				elseif ($grade_s == 6)
				{	$sql_q = $sql . "and crystal_type = 's80' " . $order_by;}
				elseif ($grade_s == 7)
				{	$sql_q = $sql . "and crystal_type = 's84' " . $order_by;}
				}
				else
				{
					$sql_q = $sql . $order_by3;
					$grade = 6;
				}
				if (!$result = mysql_query($sql_q,$con))
				{
					die('Could not retrieve from knightdrop database: ' . mysql_error());
				}
			
				$count_r = mysql_num_rows($result);
				if (mysql_fetch_array($result))
				{
					$i=0;
					$total_match = 1;
					if (!$title)
					{
						echo "<p class=\"dropmain\">&nbsp;</p>";
						echo "<h2 class=\"dropmain\">Other Items</h2>";
						echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
						if ($user_access_lvl >= $sec_inc_gmlevel)
								{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
						echo "<td class=\"drophead\">&nbsp;</td>";
						echo "<td width=\"250\" class=\"lefthead\"><p class=\"dropmain\"><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=3\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_name</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=2\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=1\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Grade</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=0\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=25\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Material</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=24\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=27\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_itemtype</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=26\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=5\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Weight</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=4\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=7\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Price</strong><br><a href=\"i-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=&itemstyle=$i_style&itemsrch=$i_searchid&sort=6\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
						echo "<td class=\"drophead\"><p class=\"dropmain\">Rec</p></td>";
						echo "</tr><tr>";		
						$title = 1;
					}
					while ($i < $count_r) 
					{
						$i_id = mysql_result($result,$i,"item_id");
						$i_name = mysql_result($result,$i,"name");
						$i_weight = mysql_result($result,$i,"weight");
						$i_price = comaise(mysql_result($result,$i,"price"));
						$i_grade = mysql_result($result,$i,"crystal_type");
						$i_mat = mysql_result($result,$i,"material");
						if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_id</p></td>"; }
						$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($i_grade == "s84")
						{ echo "<img src=\"" . $images_dir . "l_grade_7.gif\">"; }
						elseif  ($i_grade == "s80")
						{ echo "<img src=\"" . $images_dir . "l_grade_6.gif\">"; }
						elseif  ($i_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($i_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($i_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($i_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($i_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						elseif  ($i_grade == "none")
						{ echo "&nbsp;"; }
						else
						{ echo "$i_grade"; }
						echo "</td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">";
						if ($i_mat == "adamantaite")
						{ echo "<img src=\"" . $images_dir . "items/1024.gif\" title=\"adamantaite\">"; }
						elseif ($i_mat == "liquid")
						{ echo "<img src=\"" . $images_dir . "items/1764.gif\" title=\"liquid\">"; }
						elseif ($i_mat == "paper")
						{ echo "<img src=\"" . $images_dir . "items/1695.gif\" title=\"paper\">"; }
						elseif ($i_mat == "crystal")
						{ echo "<img src=\"" . $images_dir . "items/3365.gif\" title=\"crystal\">"; }
						elseif ($i_mat == "steel")
						{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"steel\">"; }
						elseif ($i_mat == "fine_steel")
						{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"fine_steel\">"; }
						elseif ($i_mat == "bone")
						{ echo "<img src=\"" . $images_dir . "items/1872.gif\" title=\"bone\">"; }
						elseif ($i_mat == "bronze")
						{ echo "<img src=\"" . $images_dir . "items/626.gif\" title=\"bronze\">"; }
						elseif ($i_mat == "cloth")
						{ echo "<img src=\"" . $images_dir . "items/1729.gif\" title=\"cloth\">"; }
						elseif ($i_mat == "gold")
						{ echo "<img src=\"" . $images_dir . "items/1289.gif\" title=\"gold\">"; }
						elseif ($i_mat == "leather")
						{ echo "<img src=\"" . $images_dir . "items/1689.gif\" title=\"leather\">"; }
						elseif ($i_mat == "mithril")
						{ echo "<img src=\"" . $images_dir . "items/1876.gif\" title=\"mithril\">"; }
						elseif ($i_mat == "silver")
						{ echo "<img src=\"" . $images_dir . "items/1873.gif\" title=\"silver\">"; }
						elseif ($i_mat == "wood")
						{ echo "<img src=\"" . $images_dir . "items/2109.gif\" title=\"wood\">"; }
						else
						{ echo "$i_mat"; }
						echo "</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_type</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						$sql2 = "select rec_id, level from knightrecch where makes = '$i_id'";
						$rec_result = mysql_query($sql2,$con);
						$rec_count = mysql_num_rows($rec_result);
						if ($rec_count)
						{	
							$rec_id = mysql_result($rec_result,0,"rec_id");
							$rec_level = mysql_result($rec_result,0,"level");
							echo "<td class=\"dropmain\"><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id\" class=\"dropmain\"><img src=\"" . $images_dir . "rec" . $rec_level . ".gif\"></a></td>";	
						}
						else
						{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
						echo "</tr>";
					$i++;
					}
				}
				$grade++;
			}
			if ($title)
			{	echo "</table></center><p class=\"dropmain\">&nbsp;</p>";	}
			if (!$total_match)
			{ writewarn("Sorry, couldn't find \"$itemname\" in database."); }
		}
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
