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
$clan = input_check($_REQUEST['clan'],0);
$recipe = input_check($_REQUEST['recipe'],0);
$qty = input_check($_REQUEST['qty'],0);
$depth = input_check($_REQUEST['depth'],0);
$registered_type = input_check($_REQUEST['regtype'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

function calc_recipe($db_location, $db_user, $db_psswd, $db_l2jdb, $recipe, $characters, $charnames, $count, $qty, $time, $blue_code, $green_code, $red_code, $username, $token, $recipe_depth, $images_dir, $depth, $langval, $registered_type)
{

global $max_rec_make;

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


	$sql = "select rec_id, rec_name, chance, makes, multiplier, rec_item from knightrecch where xml_id = '$recipe'";

	$result = mysql_query($sql,$con);
	$recipe_id = mysql_result($result,0,"rec_id");
	$rec_name = mysql_result($result,0,"rec_name");
	$rec_chance = mysql_result($result,0,"chance");
	$rec_makes = mysql_result($result,0,"makes");
	$rec_multiply = mysql_result($result,0,"multiplier");
	$rec_item = mysql_result($result,0,"rec_item");
	$qty2 = $qty * $rec_multiply;
	if ($time == 0)
	{	
		echo "<h2 class=\"dropmain\">$qty2 $rec_name ($rec_chance%&nbsp;chance)</h2>";	
		echo "<center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr>
				<td class=\"dropmain\" valign=\"top\"><p class=\"left\">Registered</p></td>
				<td class=\"dropmain3\" valign=\"top\"><p class=\"left\">Owned but not Registered</p></td>
				<td class=\"dropmain2\" valign=\"top\"><p class=\"left\">Neither owned or Registered</p></td>
				</tr></table></center>";
	}
	$sql = "select item, qty from knightrecipe where rec_id = '$recipe_id'";
	$result = mysql_query($sql,$con);
	$count = mysql_num_rows($result);
	if ($count < 1)
	{
		echo "<h2 class=\"dropmain\">No Items Found to Make Recipe !</h2>";
	}
	else
	{
		$max_rec_make = 999999;
		$i=0;
		$recipe_found_register = 0;
		$i2=0;
		$char_count = count($characters);
		$sql = "select COUNT(*) from character_recipebook where id = '$recipe' and charId in (";
		$sql2 = "select COUNT(*) from items where item_id = '$rec_item' and owner_id in (";
		while ($i2 < $char_count)
		{
			$character = $characters[$i2];
			$sql = $sql . $character;
			$sql2 = $sql2 . $character;
			$i2++;
			if ($i2 < $char_count)
			{
				$sql = $sql . ", ";
				$sql2 = $sql2 . ", ";
			}
		}
		$sql = $sql . ")";
		$sql2 = $sql2 . ")";
		$result_i = mysql_query($sql,$con);
		$count_i = mysql_num_rows($result_i);
		if ($count_i > 0)
		{	
			$count_i = mysql_result($result_i,0,"COUNT(*)");
			if ($count_i > 0)
			{	
				$recipe_found_register = 1;	}
			else
			{
				$recipe_found_register = -1;
				$result_i = mysql_query($sql2,$con);
				$count_i = mysql_num_rows($result_i);
				if ($count_i > 0)
				{	
					$count_i = mysql_result($result_i,0,"COUNT(*)");
					if ($count_i > 0)
					{	$recipe_found_register = 0;	}
				}
			}
		}
		
		while ($i < $count)
		{
			$item_no = mysql_result($result,$i,"item");
			$item_qty = (mysql_result($result,$i,"qty") * $qty);
			$itm_qty = mysql_result($result,$i,"qty");
			
			$sql = "select name, crystal_type from knightarmour where item_id = $item_no";
			$result_i = mysql_query($sql,$con);
			$count_i = mysql_num_rows($result_i);
			if ($count_i)
			{	
				$obj_name = mysql_result($result_i,0,"name");	
				$obj_grade = mysql_result($result_i,0,"crystal_type");	
			}
			$sql = "select name, crystal_type from knightweapon where item_id = $item_no";
			$result_i = mysql_query($sql,$con);
			$count_i = mysql_num_rows($result_i);
			if ($count_i)
			{	
				$obj_name = mysql_result($result_i,0,"name");	
				$obj_grade = mysql_result($result_i,0,"crystal_type");	
			}
			$sql = "select name, crystal_type from knightetcitem where item_id = $item_no";
			$result_i = mysql_query($sql,$con);
			$count_i = mysql_num_rows($result_i);
			if ($count_i)
			{	
				$obj_name = mysql_result($result_i,0,"name");	
				$obj_grade = mysql_result($result_i,0,"crystal_type");	
			}

			if ($count_i)
			{	
				$total_count = 0;
				$item_no2 = item_check(0, $item_no, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
				echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\" width=\"100%\"><tr>";
				if ($recipe_found_register < 0)
				{	echo "<td class=\"dropmain2\" valign=\"top\">";	}
				elseif ($recipe_found_register > 0)
				{	echo "<td class=\"dropmain\" valign=\"top\">";	}
				else
				{	echo "<td class=\"dropmain3\" valign=\"top\">";	}
				echo "<p class=\"left\"><strong class=\"dropmain\"><img src=\"" . $images_dir . "items/$item_no2.gif\"> $item_qty <a href=\"i-search.php?$itemname=$i_name&itemid=$item_no&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"dropmain\">$obj_name</a>  :  ";
				
				$i2=0;
				$trip_num = 0;
				while ($i2 < $char_count)
				{
					$character = $characters[$i2];
					$charname = $charnames[$i2];
					$sql = "select sum(`count`) from items where owner_id = '$character' and item_id = '$item_no'";
					$result2 = mysql_query($sql,$con);
					$qty_count = mysql_result($result2,0,"sum(`count`)");
					if ($qty_count)
					{
						if ($trip_num == 1)
						{	echo "<font color=$green_code>";	}
						elseif ($trip_num == 2)
						{	echo "<font color=$red_code>";	}
						else
						{	echo "<font color=$blue_code>";	}
						echo "$charname&nbsp;$qty_count</font> - ";
						$total_count = $total_count + $qty_count;
						$trip_num++;
						if ($trip_num == 2)
						{	$trip_num = 0;	}
					}
					$i2++;
				}
				echo "</strong></p></td>";

				$sql = "select distinct rec_id from knightrecch where makes = '$item_no'";
				$result2 = mysql_query($sql,$con);
				$qty_count = mysql_num_rows($result2);
				$item_qty2 = $item_qty - $total_count;
				if ($item_qty2 < 0)
				{	$item_qty2 = 0;	}
				if (($qty_count) && ($time < $depth))
				{
					echo "<td class=\"dropmain\" rowspan=\"2\">";	
					$i2=0;
					$time_next = $time + 1;
					$can_recipe = 0;
					$max_rec_backup = $max_rec_make;
					$next_recipe_num = mysql_result($result2,0,"rec_id");
					$sql = "select multiplier from knightrecch where rec_id = '$next_recipe_num'";
					$result3 = mysql_query($sql,$con);
					$result4 = mysql_query("select xml_id from knightrecch where rec_id='$next_recipe_num'",$con);
					$next_recipe_num = mysql_result($result4,0,"xml_id");
					$next_multiplier = mysql_result($result3,0,"multiplier");
					if ($next_multiplier < 1)
					{	$next_multiplier = 1;	}
					$item_qty3 = intval(($item_qty2 / $next_multiplier));
					$item_qty4 = $item_qty3 * $next_multiplier;
					if ($item_qty4 < $item_qty2)
					{	$item_qty3++;	}
					while ($i2 < $qty_count)
					{
						calc_recipe($db_location, $db_user, $db_psswd, $db_l2jdb, $next_recipe_num, $characters, $charnames, $count, $item_qty3, $time_next, $blue_code, $green_code, $red_code, $username, $token, $recipe_depth, $images_dir, $depth, $langval, $registered_type);						
						$can_recipe = $can_recipe + $max_rec_make;
						$i2++;
					}
					$max_rec_make = $max_rec_backup;
					echo "</td>";
				}
				else
				{	$can_recipe = 0;	}

				$total_needed = $item_qty - $total_count - $can_recipe;
				$can_make = intval(($total_count+$can_recipe) / $itm_qty);
				if ($total_needed < 0)
				{	$total_needed = 0;	}
				echo "</tr><tr>";
				if ($recipe_found_register < 0)
				{	echo "<td class=\"dropmain2\" valign=\"top\">";	}
				elseif ($recipe_found_register > 0)
				{	echo "<td class=\"dropmain\" valign=\"top\">";	}
				else
				{	echo "<td class=\"dropmain3\" valign=\"top\">";	}
				echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\" class=\"blanktab\"><tr><td width=\"50%\" class=\"noborder\" valign=\"top\"><p class=\"left\"><strong class=\"dropmain\">Got&nbsp;-&nbsp;$total_count<br>Rec&nbsp;-&nbsp;$can_recipe</strong></p></td><td width=\"50%\" class=\"noborder\" valign=\"top\"><p class=\"left\"><strong class=\"dropmain\">";
				if ($total_needed == 0)
				{	echo "<font color=$green_code>";	}
				else
				{	echo "<font color=$red_code>";	}
				echo "Need&nbsp;-&nbsp;$total_needed</font><br>Make&nbsp;-&nbsp;$can_make</strong></p></td></tr></table></td></tr></table>\n";

				if ($can_make < $max_rec_make)
				{	$max_rec_make = $can_make;	}
				$i++;
			}
			else
			{	echo "<h2 class=\"dropmain\">Item $item_no not found in the tables!</h2>";	}
		}
	}
	if ($time == 0)
	{	$max_rec_make = $max_rec_make * $rec_multiply;	}
	if (($recipe_found_register < 0) && ($registered_type < 2))
	{	$max_rec_make = 0;	}
	if (($recipe_found_register < 1) && ($registered_type < 1))
	{	$max_rec_make = 0;	}
}


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
	
	if (!$depth)
	{	$depth = $recipe_depth;	}

	if (!$user_rec_access)
	{
		echo "<h2 class=\"dropmain\">Sorry, You don't have access to the recipe calculator.</h2>";
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}

	// Try and find clans that are linked to the users account characters.  Compile a list of clans that all the users game
	// account is linked with.
	$clan_member_count = 0;
	if ((!$user_game_acc) && ($username != "guest"))
	{	
		echo "<h2 class=\"dropmain\">Warning - Drop calc doesn't know your game account<br>Compiling personal calculation.</h2>"; 
		$clan = "";
	}
	else
	{
		$sql = "select distinct clanid from characters where account_name = '$user_game_acc' and clanid = '$clan'";
		$result_clan = mysql_query($sql,$con);
		if (!$result_clan)
		{	
			echo "<h2 class=\"dropmain\">Warning - None of your characters are in clan $clan<br>Compiling personal calculation.</h2>"; 
			$clan = "";
		}

	}

	$qty = intval($qty);
	if (!$qty)
	{	$qty = 1;	}
	$sql = "select charId, char_name from characters where account_name = '$username'";
	if ($clan)
	{	$sql = "select charId, char_name from characters where clanid = '$clan'";	}
	$result = mysql_query($sql,$con);
	$count = mysql_num_rows($result);
	if ($count < 1)
	{
		echo "<h2 class=\"dropmain\">No characters in that clan or account.</h2>";
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	$characters = ARRAY(mysql_result($result,0,"charId"));
	$charnames = ARRAY(mysql_result($result,0,"char_name"));

	$i = 1;
	while ($i < $count)
	{
		array_push($characters, mysql_result($result,$i,"charId"));
		array_push($charnames, mysql_result($result,$i,"char_name"));
		$i++;
	}
	if ($clan)
	{
		array_push($characters, $clan);
		array_push($charnames, "$lang_warehouse");
		$i++;
	}
	echo "<center><table width=\"100%\" class=\"blanktab\"><tr><td class=\"noborderback\"><center><form method=\"post\" action=\"reccalc.php\"><select name=\"regtype\">";
	if ($registered_type == 1)
	{	echo "<option value=0>Registered only</option><option value=1 selected>Registered and Owned</option><option value=2>All</option>";	}
	elseif ($registered_type == 2)
	{	echo "<option value=0>Registered only</option><option value=1>Registered and Owned</option><option value=2 selected>All</option>";	}
	else
	{	
		$registered_type = 0;
		echo "<option value=0 selected>Registered only</option><option value=1>Registered and Owned</option><option value=2>All</option>";	
	}
	echo "</select><input value=\" <- Change \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"clan\" type=\"hidden\" value=\"$clan\"><input name=\"depth\" type=\"hidden\" value=\"$depth\"><input name=\"recipe\" type=\"hidden\" value=\"$recipe\"><input name=\"qty\" type=\"hidden\" value=\"$qty\"></form></center></td>";

	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"reccalc.php\"><input name=\"qty\" maxlength=\"4\" size=\"4\" type=\"text\" value=\"$qty\" class=\"popup\"><input value=\" <- Recipe Iteration \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"clan\" type=\"hidden\" value=\"$clan\"><input name=\"depth\" type=\"hidden\" value=\"$depth\"><input name=\"recipe\" type=\"hidden\" value=\"$recipe\"><input name=\"regtype\" type=\"hidden\" value=\"$registered_type\"></form></center></td><td class=\"noborderback\">";
	echo "<center><form method=\"post\" action=\"reccalc.php\"><input name=\"qty\" type=\"hidden\" value=\"$qty\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"clan\" type=\"hidden\" value=\"$clan\"><select name=\"depth\">";
	$i=0;
	while ($i < $recipe_depth)
	{
		$i++;
		echo "<option ";
		if ($i == $depth)
		{	echo "selected ";	}
		echo "value=$i>$i</option>";
	}
	echo "</select><input name=\"recipe\" type=\"hidden\" value=\"$recipe\"><input name=\"regtype\" type=\"hidden\" value=\"$registered_type\"><input value=\" <- Depth \" type=\"submit\" class=\"bigbut2\"></form></center></td></tr></table></center>";

	$ac_depth = $depth - 1;
	calc_recipe($db_location, $db_user, $db_psswd, $db_l2jdb, $recipe, $characters, $charnames, $count, $qty, 0, $blue_code, $green_code, $red_code, $username, $token, $recipe_depth, $images_dir, $ac_depth, $langval, $registered_type);
	echo "<h2 class=\"dropmain\">Can Make - $max_rec_make</h2>";

	echo "<p class=\"dropmain\">&nbsp;</p>";
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
