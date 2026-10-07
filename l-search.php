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
include('map.php');
include('playermap.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$itemname = input_check($_REQUEST['itemname'],0);
$town_id = input_check($_REQUEST['town_id'],1);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	// If the script called itself with a town ID, then display the town.
	$row_track = 0;
	$col_track = 0;
	if ($town_id)
	{
		// Connect to DB
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
		// Query for user name
		$sql = "select name, x, y from knightloc where name = '$town_id' order by name";
		if (!$result = mysql_query($sql,$con))
			{
			die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
		// If return array empty, then username not found.
		$row = mysql_fetch_array($result);
		if (!$row)
		{
			$sql = "select description, loc_x, loc_y from teleport where description = '$town_id' order by description";
			if (!$result = mysql_query($sql,$con))
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			$row = mysql_fetch_array($result);
			if (!$row)
			{
				writeerror("Location data not found!");
				return 0;
			}
		}
		echo "<p class=\"dropmain\">&nbsp;</p></p><h2 class=\"dropmain\">$row[0]</h2><center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";

		map(array(array($row[1],$row[2],3)), $images_dir, 0, 1);
		echo "</td></tr><tr><td>";
		map(array(array($row[1],$row[2],3)), $images_dir, 0, 2);
		echo "</td></tr></table></center>";
	}
	else
	{
		if (strlen($itemname) < $minlenloc)   // We need at least three characacters, or we'll produce too many results.
		{	
			writewarn("Please give at least $minlenloc characters.");	
			wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
			return 0;
		}
		// Connect to DB
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
		$array_number = 0;
		// Query for location in the knightloc database
		$sql = "select name, x, y from knightloc where name like '%$itemname%' order by name";
		if (!$result = mysql_query($sql,$con))
			{
			die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
		// If return array empty, then no location found.
		$num = mysql_num_rows($result);
		$found = 0;
		if ($num > 0)
		{
			$found = 1;
			echo "<h2 class=\"dropmain\">&nbsp;</h2>";
			echo "<center><table width=\"70%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr><td colspan=\"2\" class=\"dropmain\"><h2 class=\"tablemain\">Location List</h2></td></tr></table><p class=\"dropmain\">&nbsp;</p><table width=\"80%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\">";
			$i=0;
			while ($i < $num) {
				if ($array_number == 0)
				{	$points = array(array(mysql_result($result,$i,"x"),mysql_result($result,$i,"y")));	}
				else
				{	array_push($points, array(mysql_result($result,$i,"x"),mysql_result($result,$i,"y")));	}
				$array_number++;
				$town_name = mysql_result($result,$i,"name");
				if ($col_track == 0)
				{	echo "<tr>"; }
				$col_track++;
				echo "<td class=\"dropmain\"><p class=\"center\"><a href=\"l-search.php?town_id=$town_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"dropmain\">$town_name</a></p><center><table border=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\"><a href=\"l-search.php?town_id=$town_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"dropmain\">";
				map_2(array(array(mysql_result($result,$i,"x"),mysql_result($result,$i,"y"),1)), $images_dir, 1);
				map_2(array(array(mysql_result($result,$i,"x"),mysql_result($result,$i,"y"),1)), $images_dir, 2);
				echo "</a></td></tr></table></center></td>";
				if ($col_track == 3)
				{
					echo "</tr>";
					$col_track = 0;
					$row_track++;
				}
				$i++;
			}
		}
		// Query for location in the teleporter database
		if (strlen($itemname) < 0)
		{	$sql = "select description, loc_x, loc_y from teleport order by description";	}
		else
		{	$sql = "select description, loc_x, loc_y from teleport where description like '%$itemname%' order by description";	}
		if (!$result = mysql_query($sql,$con))
			{
			die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
		// If return array empty, then no location found.
		$num = mysql_num_rows($result);
		if ($num > 0)
		{
			if ($found == 0)
			{
				echo "<h2 class=\"dropmain\">&nbsp;</h2>";
				echo "<center><table width=\"70%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr><td colspan=\"2\" class=\"dropmain\"><h2 class=\"tablemain\">Location List</h2></td></tr></table><p class=\"dropmain\">&nbsp;</p><table width=\"80%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\">";
			}
			$found = 1;
			$i=0;
			while ($i < $num) {
			$run_loop = 1;
				$description = mysql_result($result,$i,"description");
				if ((strpos($description, "->") > 0) && (strlen($itemname) > 0))
				{
				 	if ((strpos(mysql_result($result,$i,"description"), "$itemname")) < (strpos(mysql_result($result,$i,"description"), "->")))
					{	$run_loop = 0;	}
				}
				if ($run_loop == 1)
				{
					if ($array_number > 0)
					{
						$found_dup = 0;
						$i2 = 0;
						while ($i2 < $array_number) {
							$point = $points[$i2];
							$x_co = $point[0];
							$y_co = $point[1];	
							if (($x_co == mysql_result($result,$i,"loc_x")) && ($y_co == mysql_result($result,$i,"loc_y")))
							{	$found_dup = 1;	}
							$i2++;
						}
					}
					if ($found_dup == 0)
					{
						if ($array_number == 0)
						{	$points = array(array(mysql_result($result,$i,"loc_x"),mysql_result($result,$i,"loc_y")));	}
						else
						{	array_push($points, array(mysql_result($result,$i,"loc_x"),mysql_result($result,$i,"loc_y")));	}
						$array_number++;
						$town_name = mysql_result($result,$i,"description");
						if ($col_track == 0)
						{	echo "<tr>"; }
						$col_track++;
						echo "<td class=\"dropmain\"><p class=\"center\"><a href=\"l-search.php?town_id=$town_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"dropmain\">$town_name</a></p><center><table border=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\"><a href=\"l-search.php?town_id=$town_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"dropmain\">";
						map_2(array(array(mysql_result($result,$i,"loc_x"),mysql_result($result,$i,"loc_y"),1)), $images_dir,1);
						map_2(array(array(mysql_result($result,$i,"loc_x"),mysql_result($result,$i,"loc_y"),1)), $images_dir,2);
						echo "</a></td></tr></table></center></td>";
						if ($col_track == 3)
						{
							echo "</tr>";
							$col_track = 0;
							$row_track++;
						}
					}
				}
				$i++;
			}
		}
		if (($row_track > 0) && ($col_track > 0))
		{
			while ($col_track < 3)
			{
				echo "<td class=\"dropmain\">&nbsp;</td>";
				$col_track++;
			}
		}
		if ($found > 0)
		{
			echo "</table></center>";
			echo "<h2 class=\"dropmain\">";
		}
		else
		{
			writewarn("Sorry - no location found for $itemname.</h2>");
		}
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>