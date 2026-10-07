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


include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$action = input_check($_REQUEST['action'],0);
$mobid = input_check($_REQUEST['mobid'],0);
$itemid = input_check($_REQUEST['itemid'],0);
$number = input_check($_REQUEST['number'],0);
$adena = input_check($_REQUEST['adena'],0);
$dispby = input_check($_REQUEST['dispby'],0);
$min = input_check($_REQUEST['min'],0);
$max = input_check($_REQUEST['max'],0);
$drop = input_check($_REQUEST['drop'],0);
$spct = input_check($_REQUEST['spct'],0);
$category = input_check($_REQUEST['category'],0);
$serverpct = input_check($_REQUEST['serverpct'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $adjust_drops)
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

		if ($dispby == "item")
		{	echo "<p>&nbsp;</p><h2 class=\"dropmain\">Adjustment for item - id $itemid</h2>";	}
		else
		{	echo "<p>&nbsp;</p><h2 class=\"dropmain\">Adjustment for mob - id $mobid</h2>";	}


	}
	$drop_engine = 0;
	$result2 = mysql_query("show fields from droplist",$con);
	while ($r_array = mysql_fetch_assoc($result2)) 
	{
		if (strcasecmp($r_array['Field'], "category") == 0)
		{ $drop_engine = 1; }
	}
	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\">";
	echo "<td class=\"drophead\"><p class=\"drophead\">ID</p></td>";
	if ($drop_engine)
	{	echo "<td class=\"drophead\"><p class=\"drophead\">Cat.</p></td>";	}
	echo "<td class=\"drophead\"><p class=\"drophead\">Min</p></td><td class=\"drophead\"><p class=\"drophead\">Max</p></td><td class=\"drophead\"><p class=\"drophead\">Method</p></td><td class=\"drophead\"><p class=\"drophead\">Rate?</p></td><td class=\"drophead\"><p class=\"drophead\">%</p></td><td class=\"drophead\"><p class=\"drophead\">&nbsp</p></td></tr><tr>";
	echo "<form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$mobid\"><input name=\"itemid\" type=\"hidden\" value=\"$itemid\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"add\"><td class=\"dropmain\"><p class=\"dropmain\">
				<input name=\"number\" type=\"text\" value=\"0\" maxlength=\"9\" size=\"6\"></p></td>";
	if ($drop_engine)
	{	echo "<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"category\" type=\"text\" value=\"1\" maxlength=\"3\" size=\"3\"></p></td>";	}
	echo "<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"min\" type=\"text\" value=\"0\" maxlength=\"12\" size=\"9\"></p></td>";
	echo "<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"max\" type=\"text\" value=\"0\" maxlength=\"12\" size=\"9\"></p></td>";
	echo "<td class=\"dropmain\"><p class=\"dropmain\"><select name=\"drop\"><option value=\"drops\">- Drop -</option><option value=\"spoils\">- Spoil -</option></select></p></td>";
	echo "<td class=\"dropmain\"><p class=\"dropmain\"><select name=\"spct\"><option value=\"server\">- Server -</option><option value=\"standard\">- Standard -</option></select></p></td>";
	echo "<td class=\"dropmain\"><p class=\"dropmain\"><input name=\"serverpct\" type=\"text\" value=\"0\" maxlength=\"12\" size=\"9\"></p></td>";
	echo "<td class=\"drophead\"><input value=\"<-ADD\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "</tr></table></center>";

	if ($action)
	{
		$sql = "";
		if ($action == "changecat")
		{	
			if ($number < 0)
			{ $number = 1;	}
			if ($number > 999)
			{ $number = 1;	}
			$sql = "update droplist set category = $number where mobid = $mobid and itemid = $itemid and category = '$category'";
		}
		if ($action == "delspoil")
		{	if ($drop_engine)
			{	$sql = "delete from droplist where mobid = $mobid and itemid = $itemid and category = '-1'";	}
			else
			{	$sql = "delete from droplist where mobid = $mobid and itemid = $itemid and sweep = '1'";	}
		}
		if ($action == "deldrop")
		{
			if ($drop_engine)
			{	$sql = "delete from droplist where mobid = $mobid and itemid = $itemid and category = '$category'";	}
			else
			{	$sql = "delete from droplist where mobid = $mobid and itemid = $itemid and sweep = '0'";	}
		}
		if ($action == "changedropmin")
		{	$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set min = $number where mobid = $mobid and itemid = $itemid and category = '$category'";	}
			else
			{	$sql = "update droplist set min = $number where mobid = $mobid and itemid = $itemid and sweep = '0'";	}
		}
		if ($action == "changespoilmin")
		{	$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set min = $number where mobid = $mobid and itemid = $itemid and category = '-1'";	}
			else
			{	$sql = "update droplist set min = $number where mobid = $mobid and itemid = $itemid and sweep = '1'";	}
		}
		if ($action == "changedropmax")
		{	$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set max = $number where mobid = $mobid and itemid = $itemid and category = '$category'";	}
			else
			{	$sql = "update droplist set max = $number where mobid = $mobid and itemid = $itemid and sweep = '0'";	}
		}
		if ($action == "changespoilmax")
		{	$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set max = $number where mobid = $mobid and itemid = $itemid and category = '-1'";	}
			else
			{	$sql = "update droplist set max = $number where mobid = $mobid and itemid = $itemid and sweep = '1'";	}
		}
		if ($action == "changedropstd")
		{	
			$number *=10000; 
			$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and category = '$category'";	}
			else
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and sweep = '0'";	}
		}
		if ($action == "changespoilstd")
		{	
			$number *=10000;
			$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and category = '-1'";	}
			else
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and sweep = '1'";	}
		}
		if ($action == "changedropserver")
		{	
			$number *=10000; 
			if ($adena == "yes")
			{	$number /=$drop_chance_adena;	}
			else
			{	$number /=$drop_chance_item;	}
			$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and category = '$category'";	}
			else
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and sweep = '0'";	}
		}
		if ($action == "changespoilserver")
		{	
			$number *=10000;
			if ($adena == "yes")
			{	$number /=$drop_chance_adena;	}
			else
			{	$number /=$drop_chance_spoil;	}
			$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and category = '-1'";	}
			else
			{	$sql = "update droplist set chance = $number where mobid = $mobid and itemid = $itemid and sweep = '1'";	}
		}
		if ($action == "changecorenum")
		{	
			$number = intval($number);
			if ($drop_engine)
			{	$sql = "update droplist set itemid = $number where mobid = $mobid and itemid = $itemid and category = '$category'";	}
			else
			{	$sql = "update droplist set itemid = $number where mobid = $mobid and itemid = $itemid and sweep = '$category'";	}
			if ($dispby == "item")
			{
				if ($drop_engine)
				{	$sql = "update droplist set mobid = $number where mobid = $mobid and itemid = $itemid and category = '$category'";	}
				else
				{	$sql = "update droplist set mobid = $number where mobid = $mobid and itemid = $itemid and sweep = '$category'";	}
			}
		}
		if ($sql)
		{	$result2 = mysql_query($sql,$con);	}
		if ($action == "add")
		{
			$error_finding = 0;
			$number = intval($number);
			if ($dispby == "item")
			{
				$mobid = $number;
				$sql = "select name from npc where id = $number union select name from custom_npc where id = $number";  // Try armour database
				$result3 = mysql_query($sql,$con);
				$count_rows = mysql_num_rows($result3);
				if (!$count_rows)
				{
					$error_finding = 1;
				}
				if ($error_finding)
				{	echo "<h2 class=\"dropmain\">Monster id $number not found in database</h2>";	}
			}
			else
			{
				$itemid = $number;
				$sql = "select name from armor where item_id = $number";  // Try armour database
				$result3 = mysql_query($sql,$con);
				$count_rows = mysql_num_rows($result3);
				if (!$count_rows)
				{
					$sql = "select name from weapon where item_id = $number"; // Try weapons database
					$result3 = mysql_query($sql,$con);
					$count_rows = mysql_num_rows($result3);
					if (!$count_rows)
					{
						$sql = "select name from etcitem where item_id = $number"; // Try etc_items database
						$result3 = mysql_query($sql,$con);
						$count_rows = mysql_num_rows($result3);
						if (!$count_rows)
						{
							$error_finding = 1;
						}
					}
				}
				if ($error_finding)
				{	echo "<h2 class=\"dropmain\">Item id $number not found in database</h2>";	}
			}
			if ($error_finding == 0)
			{
				if ($drop == "spoils")
				{	if ($drop_engine)
					{	$sql = "select chance from droplist where mobid = $mobid and itemid = $itemid and category = '-1'";	}
					else
					{	$sql = "select chance from droplist where mobid = $mobid and itemid = $itemid and sweep = '1'";	}
				}		
				else
				{	if ($drop_engine)
					{	$sql = "select chance from droplist where mobid = $mobid and itemid = $itemid and category = '$category'";	}
					else
					{	$sql = "select chance from droplist where mobid = $mobid and itemid = $itemid and sweep = '0'";	}
				}
				$result3 = mysql_query($sql,$con);
				$count_rows = mysql_num_rows($result3);
				if ($count_rows)
				{	echo "<h2 class=\"dropmain\">Mob already $drop this item.</h2>";	}
				else
				{
					$min = intval($min);
					$max = intval($max);
					$item_chance = $serverpct;
					if ($spct == "server")
					{
						if ($itemid == 57)  // Adjust the drop chance acording to item type.
						{
							$item_chance /= $drop_chance_adena;
						}
						elseif ($drop == "spoils")
						{
						   $item_chance /= $drop_chance_spoil;
						}
						else
						{
							$item_chance /= $drop_chance_item;
						}
					}
					$item_chance *= 10000;
					$item_chance = intval($item_chance);
					if ($drop == "spoils")
					{	if ($drop_engine)
						{	$sql = "insert into droplist (mobid, itemid, min, max, chance, category) values ($mobid, $itemid, $min, $max, $item_chance, '-1')";	}
						else
						{	$sql = "insert into droplist (mobid, itemid, min, max, chance, sweep) values ($mobid, $itemid, $min, $max, $item_chance, '1')";	}
					}
					else
					{	if ($drop_engine)
						{	$sql = "insert into droplist (mobid, itemid, min, max, chance, category) values ($mobid, $itemid, $min, $max, $item_chance, '$category')";	}
						else
						{	$sql = "insert into droplist (mobid, itemid, min, max, chance, sweep) values ($mobid, $itemid, $min, $max, $item_chance, '0')";	}
					}
					$result3 = mysql_query($sql,$con);
				}
			}
		}
	}


	// Now go through all the items that the mob drops or spwans and add them to an array.
	if ($dispby == "item")
	{	
		if ($drop_engine)
		{	$sql = "select mobid, min, max, category, chance from droplist where itemid = $itemid order by category, chance DESC";	}
		else
		{	$sql = "select mobid, min, max, sweep, chance from droplist where itemid = $itemid";	}
		$t_title = "Mob ID";
		$a_id = "mobId";
	}
	else
	{	
		if ($drop_engine)
		{	$sql = "select itemid, min, max, category, chance from droplist where mobId = $mobid order by category, chance DESC";	}
		else
		{	$sql = "select itemid, min, max, sweep, chance from droplist where mobId = $mobid";	}
		$t_title = "Item ID";
		$a_id = "itemId";
	}
	$result2 = mysql_query($sql,$con);
	$count_r = mysql_num_rows($result2);
	$item_cat = 0;
	if (mysql_fetch_array($result2))
	{
		$itm_array = ARRAY();
		$i=0;
		while ($i < $count_r) 
		{
			$i_array = mysql_fetch_row($result2);
			if ($dispby == "item")
			{	$item_id = mysql_result($result2,$i,"mobId");	}
			else
			{	$item_id = mysql_result($result2,$i,"itemid");	}
			if ($drop_engine)
			{	$item_cat = mysql_result($result2,$i,"category");
				if ($item_cat < 0)
				{	$item_sweep = 1;	}
				else
				{	$item_sweep = 0;	}
			}
			else
			{	$item_sweep = mysql_result($result2,$i,"sweep");	}
			$item_chance = mysql_result($result2,$i,"chance");
			$raw_chance = $item_chance;

			$error_finding = 0;
			if (!$dispby == "item")
			{
				$sql = "select name from armor where item_id = $item_id";  // Try armour database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$sql = "select name from weapon where item_id = $item_id"; // Try weapons database
					$result3 = mysql_query($sql,$con);
					if (!mysql_fetch_array($result3))
					{
						$sql = "select name from etcitem where item_id = $item_id"; // Try etc_items database
						$result3 = mysql_query($sql,$con);
						if (!mysql_fetch_array($result3))
						{
							$error_finding = 1;
						}
					}
				}
			}
			else
			{
				$sql = "select name from npc where id = $item_id union select name from custom_npc where id = $item_id";  // Try armour database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$error_finding = 1;
				}
				$res = mysql_error();
			}

			if ($error_finding == 1)
			{
				$item_name = "ERROR";
			}
			else
			{
				$item_name = mysql_result($result3,0,"name");
			}

			if ($item_id == 57)  // Adjust the drop chance acording to item type.
			{
				$item_chance *= $drop_chance_adena;
			}
			elseif (!$item_sweep)
			{
			   $item_chance *= $drop_chance_item;
			}
			else
			{
				$item_chance *= $drop_chance_spoil;
			}

			$item_chance /=10000;
			$raw_chance /=10000;
			array_push($itm_array, array($item_chance,mysql_result($result2,$i,$a_id),$item_name,mysql_result($result2,$i,"min"),mysql_result($result2,$i,"max"),$item_sweep,$raw_chance,$item_cat));
			$i++;
		}
		reset($itm_array);
		echo "<p class=\"dropmain\">&nbsp;</p>";
		echo "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		echo "<tr><td valign=\"top\" width=\"50%\" class=\"noborder\"><center>";

		// Go through the array displaying the drop items and putting the spoiled items in another array. 
		// (as the data is wiped from this one as we go through it.
		$i=0;
		$spoil_count = 0;
		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\">";
		if ($drop_engine)
		{	echo "<td colspan=\"14\" class=\"drophead\"><p class=\"drophead\">DROPS</p></td></tr><tr class=\"thead\">";	}
		else
		{	echo "<td colspan=\"12\" class=\"drophead\"><p class=\"drophead\">DROPS</p></td></tr><tr class=\"thead\">";	}
		echo "<td class=\"lefthead\">$t_title</td><td class=\"drophead\"><p class=\"left\">&nbsp;</p></td>";
		echo "<td width=\"150\" class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td>";
		if ($drop_engine)
		{	echo "<td class=\"drophead\"><p class=\"left\">Cat.</p></td><td class=\"drophead\"><p class=\"left\">&nbsp;</p></td>";	}
		echo "<td class=\"drophead\"><p class=\"left\">Min</p></td><td class=\"drophead\"><p class=\"left\">&nbsp;</p></td><td class=\"drophead\"><p class=\"left\">Max</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td><td class=\"drophead\"><p class=\"left\">Std %</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td><td class=\"drophead\"><p class=\"dropmain\">Server %</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td></tr>";
		$bg_colour = 1;
		$last_bg = -1;
		while ($i < $count_r) 
		{
			list($k1) = each($itm_array);
			$i_array = $itm_array[$k1];
			$item_id = $i_array[1];
			$item_name = $i_array[2];
			$item_min = $i_array[3];
			$item_max = $i_array[4];
			if ($drop_engine)
			{	$item_cat = $i_array[7];
				if ($item_cat < 0)
				{	$item_sweep = 1;	}
				else
				{	$item_sweep = 0;	}
			}
			else
			{	$item_sweep = $i_array[5];	}
			if ($drop_engine)
			{
				if ($item_cat <> $last_bg)
				{
					$bg_colour = 1 - $bg_colour;
					$last_bg = $item_cat;
				}
			}
			if ($bg_colour == 0)
			{	
				$bg_class = "dropmain";	
				$bg_class2 = "left";	
			}
			else
			{	
				$bg_class = "dropsec";	
				$bg_class2 = "left2";	
			}
			$item_chance = $i_array[0];
			$item_raw = $i_array[6];
			if (!$dispby == "item")
			{	$i_mob = $mobid;
				$i_item = $item_id;	}
			else
			{	$i_mob = $item_id;
				$i_item = $itemid;	}
			$adena = "no";
			if ($i_item == 57)
			{	$adena = "yes";	}
			
			if ($item_sweep < 1)
			{
				echo "<tr>";
				echo "<td class=\"$bg_class2\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changecorenum\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"category\" type=\"hidden\" value=\"$item_cat\">
				<input name=\"number\" type=\"text\" value=\"$item_id\" maxlength=\"6\" size=\"6\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"$bg_class2\"><p class=\"dropmain\">";
				if ($dispby == "item")
				{	echo "<a href=\"m-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$item_id\" class=\"dropmain\">";	}
				else
				{	echo "<a href=\"i-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">";	}
				echo "$item_name</a></p></td>";
				if ($drop_engine)
				{	echo "<td class=\"$bg_class\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changecat\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"category\" type=\"hidden\" value=\"$item_cat\">
				<input name=\"number\" type=\"text\" value=\"$item_cat\" maxlength=\"3\" size=\"3\"></p></td>";	
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";}
				echo "<td class=\"$bg_class\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changedropmin\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"category\" type=\"hidden\" value=\"$item_cat\">
				<input name=\"number\" type=\"text\" value=\"$item_min\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"$bg_class\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changedropmax\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"category\" type=\"hidden\" value=\"$item_cat\">
				<input name=\"number\" type=\"text\" value=\"$item_max\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"$bg_class\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changedropstd\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"category\" type=\"hidden\" value=\"$item_cat\">
				<input name=\"number\" type=\"text\" value=\"$item_raw\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"$bg_class\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changedropserver\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"category\" type=\"hidden\" value=\"$item_cat\">
				<input name=\"number\" type=\"text\" value=\"$item_chance\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"$bg_class\"><p class=\"dropmain\"><a href=\"drops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&mobid=$i_mob&itemid=$i_item&action=deldrop&category=$item_cat&dispby=$dispby\" class=\"dropmain\"><font color=$red_code>DEL</font></a></p></td>";
				echo "</tr>";
			}
			else
			{
				$spoil_count++;
				if (!$spoil_array)
				{
					$spoil_array = array(array($item_id, $item_name, $item_min, $item_max, $item_chance, $item_raw));
				}
				else
				{
					array_push($spoil_array, array($item_id, $item_name, $item_min, $item_max, $item_chance, $item_raw));
				}
			}
			$i++;
		}
		echo "</table>";
		echo "</center><p>&nbsp;</p><center>";

		// go through the spoil array and simply display the numbers, as the calculations have been done in the array scan before.
		$i=0;
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\">";
		echo "<td colspan=\"11\" class=\"drophead\"><p class=\"drophead\">SPOILS</p></td></tr><tr class=\"thead\">";
		echo "<td class=\"lefthead\">$t_title</td><td class=\"drophead\"><p class=\"left\">&nbsp;</p></td>";
		echo "<td width=\"150\" class=\"drophead\"><p class=\"dropmain\">$lang_name</p></td><td class=\"drophead\"><p class=\"left\">Min</p></td><td class=\"drophead\"><p class=\"left\">&nbsp;</p></td><td class=\"drophead\"><p class=\"left\">Max</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td><td class=\"drophead\"><p class=\"left\">Std %</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td><td class=\"drophead\"><p class=\"dropmain\">Server %</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp</p></td><td class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td></tr>";

		if ($spoil_array)
		{
			while ($i < $spoil_count) 
			{
				list($k1) = each($spoil_array);
				$i_array = $spoil_array[$k1];
				$item_id = $i_array[0];
				$item_name = $i_array[1];
				$item_min = $i_array[2];
				$item_max = $i_array[3];
				$item_chance = $i_array[4];
				if (!$dispby == "item")
				{	$i_mob = $mobid;
					$i_item = $item_id;	}
				else
				{	$i_mob = $item_id;
					$i_item = $itemid;	}
				$adena = "no";
				if ($i_item == 57)
				{	$adena = "yes";	}

				$item_raw = $i_array[5];
				echo "<tr>";
				echo "<tr>";
				echo "<td class=\"left\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changecorenum\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"category\" type=\"hidden\" value=\"-1\">
				<input name=\"number\" type=\"text\" value=\"$item_id\" maxlength=\"6\" size=\"6\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"left\"><p class=\"dropmain\">";
				if ($dispby == "item")
				{	echo "<a href=\"m-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$item_id\" class=\"dropmain\">";	}
				else
				{	echo "<a href=\"i-search.php?itemid=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">";	}
				echo "$item_name</a></p></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changespoilmin\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"number\" type=\"text\" value=\"$item_min\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changespoilmax\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"number\" type=\"text\" value=\"$item_max\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changespoilstd\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"number\" type=\"text\" value=\"$item_raw\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\"><form method=\"post\" action=\"drops.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
				<input name=\"mobid\" type=\"hidden\" value=\"$i_mob\"><input name=\"itemid\" type=\"hidden\" value=\"$i_item\">
				<input name=\"dispby\" type=\"hidden\" value=\"$dispby\"><input name=\"action\" type=\"hidden\" value=\"changespoilserver\"><input name=\"adena\" type=\"hidden\" value=\"$adena\">
				<input name=\"number\" type=\"text\" value=\"$item_chance\" maxlength=\"12\" size=\"9\"></p></td>";
				echo "<td class=\"drophead\"><input value=\"<-\" type=\"submit\" class=\"bigbut2\"></form></td>";
				echo "<td class=\"dropmain\"><p class=\"dropmain\"><a href=\"drops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&mobid=$i_mob&itemid=$i_item&action=delspoil&dispby=$dispby\" class=\"dropmain\"><font color=$red_code>DEL</font></a></p></td>";
				echo "</tr>";
				$i++;
			}
		}
		echo "</table>";
		echo "</center></td></table>";
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>