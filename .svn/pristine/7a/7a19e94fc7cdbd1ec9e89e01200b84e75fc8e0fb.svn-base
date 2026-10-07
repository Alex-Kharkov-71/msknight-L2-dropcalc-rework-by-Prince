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

	$sql = "select distinct clanid from characters where account_name = '$username' and clanid > 0";
	$clan_result = mysql_query($sql,$con);
	$clan_count = mysql_num_rows($clan_result);
	if ($clan_count)
	{
		$i = 0;
		while ($i < $clan_count)
		{
			$clan_id = mysql_result($clan_result,$i,"clanid");
			$sql = "select clan_name from clan_data where clan_id = '$clan_id'";
			$result2 = mysql_query($sql,$con);
			$clan_title = mysql_result($result2,0,"clan_name");
			if (!$i)
			{	$clan_names = array($clan_title);	}
			else
			{	array_push($clan_names, $clan_title);	}
			$i++;
		}
	}

	if (!$rec_id)
	{	
		if (strlen($itemname) < $minlenrec)
		{	
			writewarn("Please give at least $minlenrec characters.");
			wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
			return 0;
		}
		$sql="select rec_name, rec_id, rec_item, level, makes, chance, multiplier, xml_id from knightrecch where rec_name like '%$itemname%' order by $orderby";
	}
	else
	{	$sql="select rec_name, rec_id, rec_item, level, makes, chance, multiplier, xml_id from knightrecch where rec_id = '$rec_id'";	}
	$result = mysql_query($sql,$con);
	$count = mysql_num_rows($result);
	if (!$count)
	{
		writewarn("Sorry, no recipes match $itemname");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	if ($count == 1)
	{	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\">&nbsp;</p></td><td class=\"lefthead\"><p class=\"dropmain\">Recipe</p></td><td class=\"drophead\"><p class=\"dropmain\">Chance</p></td><td class=\"drophead\"><p class=\"dropmain\">Level</p></td><td class=\"drophead\"><p class=\"dropmain\">Makes</p></td>";	}
	else
	{	echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"dropmain\">&nbsp;</p></td>";
		echo "<td class=\"lefthead\"><p class=\"dropmain\"><center><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id&orderby=1\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Recipe</strong><br><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id&orderby=2\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
		echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id&orderby=3\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Chance</strong><br><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id&orderby=4\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
		echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id&orderby=5\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Level</strong><br><a href=\"r-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&recid=$rec_id&orderby=6\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";	
	}

	if ($user_rec_access)
	{	
		echo "<td class=\"drophead\"><p class=\"dropmain\">Calc</p></td>";
		if ($clan_count)
		$i=0;
		while ($i < $clan_count)
		{	
			echo "<td class=\"drophead\"><p class=\"dropmain\">$clan_names[$i]</p></td>";
			$i++;
		}
	}
	echo "</tr>";
	$i=0;
	while ($i < $count)
	{
		$rec_name = mysql_result($result,$i,"rec_name");
		$rec_id = mysql_result($result,$i,"rec_id");
		$rec_item = mysql_result($result,$i,"rec_item");
		$rec_makes = mysql_result($result,$i,"makes");
		$rec_level = mysql_result($result,$i,"level");
		$rec_chance = mysql_result($result,$i,"chance");
		$rec_multiply = mysql_result($result,$i,"multiplier");
		$rec_xml = mysql_result($result,$i,"xml_id");
		echo "<tr><td class=\"dropmain\"><img src=\"" . $images_dir . "rec" . $rec_level . ".gif\"></td><td class=\"dropmain\"><p class=\"left\"><strong>";
		if ($count == 1)
		{	echo "<a href=\"i-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$rec_makes\" class=\"dropmain\">";	}
		else
		{	echo "<a href=\"r-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&clan=&recid=$rec_id\" class=\"dropmain\">";	}
		echo "$rec_name</a></strong></p></td><td class=\"dropmain\"><p class=\"dropmain\">$rec_chance%</p></td><td class=\"dropmain\"><p class=\"dropmain\">$rec_level</p></td>";
		if ($count == 1)
		{	echo "<td class=\"dropmain\"><p class=\"dropmain\">$rec_multiply</p></td>";	}
		if ($user_rec_access)
		{	
			echo "<td class=\"dropmain\"><a href=\"reccalc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&clan=&recipe=$rec_xml\" class=\"dropmain\"><img src=\"" . $images_dir . "calc3.bmp\"></a></td>";	
			if ($clan_count)
			$i2=0;
			while ($i2 < $clan_count)
			{	
				$clan_id = mysql_result($clan_result,$i2,"clanid");
				echo "<td class=\"dropmain\"><a href=\"reccalc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&clan=$clan_id&recipe=$rec_xml\" class=\"dropmain\"><img src=\"" . $images_dir . "calc1.bmp\"></a></td>";
				$i2++;
			}
		}
		echo "</tr>";
		$i++;
	}
	echo "</table></center><p>&nbsp;</p>";

	if ($count == 1)
	{
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\">&nbsp;</td><td class=\"lefthead\"><p class=\"dropmain\">Item</p></td><td class=\"drophead\"><p class=\"dropmain\">Qty</p></td></tr>";
		$sql = "select `item`, qty from knightrecipe where rec_id = '$rec_id'";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		$i=0;
		while ($i < $count)
		{
			$item_id = mysql_result($result,$i,"item");
			$item_qty = mysql_result($result,$i,"qty");
			$sql = "select name, material from knightetcitem where item_id = '$item_id'";
			$result2 = mysql_query($sql,$con);
			$item_name = mysql_result($result2,0,"name");
			$item_type = mysql_result($result2,0,"material");
			$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
			$item_id2 = item_check(0, $item_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
			echo "<tr><td class=\"dropmain\"><img src=\"" . $images_dir . "items/$item_id2.gif\"></td>";
			echo "<td class=\"dropmain\"><p class=\"left\"><a href=\"i-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">$item_name</a></p></td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$item_qty</p></td></tr>";
			$i++;
		}
		echo "</table></center>";
	}
	
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
