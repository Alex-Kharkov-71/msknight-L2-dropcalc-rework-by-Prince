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
include('map.php');
include('playermap.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$itemname = input_check($_REQUEST['itemname'],0);
$monsterid = input_check($_REQUEST['monsterid'],0);
$monstersort = input_check($_REQUEST['monstersort'],0);
$monsadmshow = input_check($_REQUEST['adminshow'],0);
$monsdetreq = input_check($_REQUEST['detreq'],0);
$monsdetshow = input_check($_REQUEST['detshow'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

global $user_access_lvl;

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	// If the script called itself with a monster, then look up that monsters details.
	if ($monsterid)
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
		$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro, sex, pdef, mdef, rhand, lhand, walkspd, runspd, atkspd, patk, matk, matkspd from knightnpc where id = '$monsterid'";
		if (!$result = mysql_query($sql,$con))
		{
			die('Could not retrieve from knightdrop database: ' . mysql_error());
		}
		// If return array empty, then username not found.
		$row = mysql_fetch_array($result);
		if (!$row)
		{
				writeerror("Monster data not found!");
				return 0;
		}

		// Check if the underground tab is enabled in the spawnlist table.  If it is, then we can use this to calculate underground abiliites.
		$sql = "show fields from spawnlist";
		if (!$result2 = mysql_query($sql,$con))
			{
			die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
		$count_r = mysql_num_rows($result2);
		$res = mysql_fetch_array($result2);
		$i2=0;
		$underground = 0;
		while ($i2 < $count_r) 
		{
			$i_id = mysql_result($result2,$i2,"field");
			if ($i_id == "ugnd")
			{ $underground = 1; }
			$i2++;
		}

		// Retrieve detailed mob informtion and put in variables.
		$mob_id = mysql_result($result,$i,"id");
		$mob_name = mysql_result($result,$i,"name");
		$mob_type = mysql_result($result,$i,"type");
		$mob_level = mysql_result($result,$i,"level");
		$mob_hp = mysql_result($result,$i,"hp");
		$mob_mp = mysql_result($result,$i,"mp");
		$mob_exp = mysql_result($result,$i,"exp");
		$mob_sp = mysql_result($result,$i,"sp");
		$mob_atkrange = mysql_result($result,$i,"attackrange");
		$mob_aggro = mysql_result($result,$i,"aggro");
		$sql = "select id from spawnlist where npc_templateid = $mob_id";
		$result2 = mysql_query($sql,$con);
		$mob_spawn = mysql_num_rows($result2);
		$mob_sex = mysql_result($result,$i,"sex");
		$mob_patk = mysql_result($result,$i,"patk");
		$mob_matk = mysql_result($result,$i,"matk");
		$mob_pdef = mysql_result($result,$i,"pdef");
		$mob_mdef = mysql_result($result,$i,"mdef");
		$mob_walk = mysql_result($result,$i,"walkspd");
		$mob_run = mysql_result($result,$i,"runspd");
		$mob_patks = mysql_result($result,$i,"atkspd");
		$mob_matks = mysql_result($result,$i,"matkspd");
		$mob_left = mysql_result($result,$i,"lhand");
		$mob_right = mysql_result($result,$i,"rhand");

		
		// Look up the name of what is in the Mob's left hand
		if (!$mob_left)
		{ $mob_lefth = "Nothing"; }
		else
		{
			$error_finding = 0;
			$sql = "select name from armor where item_id = $mob_left";  // Try armour database
			$result3 = mysql_query($sql,$con);
			if (!mysql_fetch_array($result3))
			{
				$sql = "select name from weapon where item_id = $mob_left"; // Try weapons database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$sql = "select name from etcitem where item_id = $mob_left"; // Try etc_items database
					$result3 = mysql_query($sql,$con);
					if (!mysql_fetch_array($result3))
					{
						$error_finding = 1;
					}
				}
			}
			if ($error_finding)
			{ $mob_lefth = "Unknown"; }
			else
			{ $mob_lefth = mysql_result($result3,0,"name"); }
		}
		
		// Lok up the name of what is in the mobs right hand.
		if (!$mob_right)
		{ $mob_righth = "Nothing"; }
		else
		{
			$error_finding = 0;
			$sql = "select name from armor where item_id = $mob_right";  // Try armour database
			$result3 = mysql_query($sql,$con);
			if (!mysql_fetch_array($result3))
			{
				$sql = "select name from weapon where item_id = $mob_right"; // Try weapons database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$sql = "select name from etcitem where item_id = $mob_right"; // Try etc_items database
					$result3 = mysql_query($sql,$con);
					if (!mysql_fetch_array($result3))
					{
						$error_finding = 1;
					}
				}
			}
			if ($error_finding)
			{ $mob_righth = "Unknown"; }
			else
			{ $mob_righth = mysql_result($result3,0,"name"); }
		}

		//Search through the spawnlog and locations database to find all the spawn points for the mob and put them in the map database.
		$mob_spawnnum = 0;
		$mob_days = 0;
		$mob_dayt = 0;
		$mob_nights = 0;
		$mob_nightt = 0;
		$mob_normals = 0;
		$mob_normalt = 0;
		if ($underground)
		{	$sql = "select locx, locy, locz, loc_id, count, ugnd, periodOfDay from spawnlist where npc_templateid = $mob_id";	}
		else
		{	$sql = "select locx, locy, locz, loc_id, count, periodOfDay from spawnlist where npc_templateid = $mob_id";	}
		$result2 = mysql_query($sql,$con);
		$count_r2 = mysql_num_rows($result2);
		if (mysql_fetch_array($result2))
		{
			$i2=0;
			$mob_count = 0;
			while ($i2 < $count_r2) 
			{
				$map_tag = 2;
				if ($underground)   // If dealing with an underground capable database, then adjust the map icons accordingly.
				{
					if (mysql_result($result2,$i2,"ugnd") == 2)
					{ $map_tag = 0; }
					if (mysql_result($result2,$i2,"ugnd") == 1)
					{ $map_tag = 1; }
				}
				if ((mysql_result($result2,$i2,"locx") <> 0) || (mysql_result($result2,$i2,"locy") <> 0) || (mysql_result($result2,$i2,"locz") <> 0))
				{
					$mob_spawnnum++;
					$mob_count++;
					if ( mysql_result($result2,$i2,"periodOfDay") == 1 )
					{
						$mob_days++;
						$mob_dayt++;
						$map_tag = 0;
					}
					elseif ( mysql_result($result2,$i2,"periodOfDay") == 2 )
					{
						$mob_nights++;
						$mob_nightt++;
						$map_tag = 1;
					}
					else
					{
						$mob_normals++;
						$mob_normalt++;
						$map_tag = 2;
					}
					if (!$map_array)
					{
						$map_array = array(array((mysql_result($result2,$i2,"locx")), (mysql_result($result2,$i2,"locy")), $map_tag));
					}
					else
					{
						array_push($map_array, array((mysql_result($result2,$i2,"locx")), (mysql_result($result2,$i2,"locy")), $map_tag));
					}
				}						
				else
				{
					$mob_count = $mob_count + mysql_result($result2,$i2,"count");
					if ( mysql_result($result2,$i2,"periodOfDay") == 1 )
					{
						$mob_days++;
					}
					elseif ( mysql_result($result2,$i2,"periodOfDay") == 2 )
					{
						$mob_nights++;
					}
					else
					{
						$mob_normals++;
					}
					$location_id = mysql_result($result2,$i2,"loc_id");
					$sql = "select loc_x, loc_y, loc_y, loc_zmin from locations where loc_id = $location_id";
					$result3 = mysql_query($sql,$con);
					$count_r3 = mysql_num_rows($result3);
					if (mysql_fetch_array($result3))
					{	
						$i3=0;
						while ($i3 < $count_r3)
						{
							$locat_x = mysql_result($result3,$i3,"loc_x");
							$locat_y = mysql_result($result3,$i3,"loc_y");
							$locat_z = mysql_result($result3,$i3,"loc_zmin");
							if ( mysql_result($result2,$i2,"periodOfDay") == 1 )
							{
								$mob_dayt++;
								$map_tag = 0;
							}
							elseif ( mysql_result($result2,$i2,"periodOfDay") == 2 )
							{
								$mob_nightt++;
								$map_tag = 1;
							}
							else
							{
								$mob_normalt++;
								$map_tag = 2;
							}
							if (($locat_x <> 0) || ($locat_y <> 0) || ($locat_z <> 0))
							{
								$mob_spawnnum++;
								if (!$map_array)
								{
									$map_array = array(array($locat_x, $locat_y, $map_tag));
								}
								else
								{
									array_push($map_array, array($locat_x, $locat_y, $map_tag));
								}
							}
							$i3++;
						}
					}
				}
				$i2++;
			}
		}
	
		// Display detailed information about the mob.
		echo "<p class=\"dropmain\">&nbsp;</p>";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
		echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">$lang_name</strong></td><td width=\"230\" class=\"dropmain\">";
		if ($mob_aggro > 0)
		{ echo "<font color=$red_code><strong class=\"dropmain\">$mob_name</strong></font>"; }
		else
		{ echo "<font color=$green_code><strong class=\"dropmain\">$mob_name</strong></font>"; }
		echo "<td class=\"dropmain\">&nbsp;</td><td class=\"lefthead\"><strong class=\"dropmain\">Sex</strong></td>";
		if ($mob_sex == "male")
		{ echo "<td width=\"150\" class=\"dropmain\"><center><img src=\"" . $images_dir . "male.gif\" width=\"10\" height=\"14\" border=\"0\"></center></td>"; }
		else
		{ echo "<td width=\"150\" class=\"dropmain\"><center><img src=\"" . $images_dir . "female.gif\" width=\"10\" height=\"14\" border=\"0\"></center></td>"; }
		echo "<td valign=\"center\" align=\"center\" rowspan=\"7\">";
		if ($user_map_access)
			{
				echo "<p class=\"dropmain\">&nbsp;<p class=\"dropmain\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
				map_2($map_array, $images_dir);
				echo "</td></tr></table>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<p class=\"dropmain\"><br>Mob ID - $mob_id</p>"; }
				echo "<p class=\"dropmain\"><strong class=\"dropmain\">$lang_spawn - $mob_count&nbsp;/&nbsp;$mob_spawnnum</strong><br><br>";
				echo "<table cellpadding=\"3\" class=\"noborder\"><tr><td class=\"dropmain\"><center><font color=$green_code>$lang_day</font></center></td><td class=\"dropmain\"><center><font color=$red_code>$lang_night</font></center></td><td class=\"dropmain\"><center>Perm</center></td></tr><tr><td class=\"dropmain\"><font color=$green_code>$mob_days/$mob_dayt</font></td><td class=\"dropmain\"><font color=$red_code>$mob_nights/$mob_nightt</font></td><td class=\"dropmain\">$mob_normals/$mob_normalt</td></tr></table>";
				echo "</p></center><p class=\"dropmain\">&nbsp;<p class=\"dropmain\">";
			}
			else
			{
			echo "<p class=\"dropmain\">&nbsp;<p class=\"dropmain\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\"><img src=\"" . $images_dir . "map2.jpg\" width=\"104\" height=\"150\"></td></tr></table><p class=\"dropmain\"><strong class=\"dropmain\">$lang_spawn - $mob_count&nbsp;/&nbsp;$mob_spawnnum</strong><br><br>";
			echo "<table cellpadding=\"3\" class=\"noborder\"><tr><td class=\"dropmain\"><center><font color=$green_code>$lang_day</font></center></td><td class=\"dropmain\"><center><font color=$red_code>$lang_night</font></center></td><td class=\"dropmain\"><center>Perm</center></td></tr><tr><td class=\"dropmain\"><font color=$green_code>$mob_days/$mob_dayt</font></td><td class=\"dropmain\"><font color=$red_code>$mob_nights/$mob_nightt</font></td><td class=\"dropmain\">$mob_normals/$mob_normalt</td></tr></table>";
			echo "</p></center><p class=\"dropmain\">&nbsp;<p class=\"dropmain\">";
			}
		echo "</td>";
		echo "</td></tr>";

		echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">$lang_type</strong></td><td class=\"dropmain\"><font color=#6B5D10>";
		if ($mob_undead)
		{ echo "$lang_undead"; }
		else
		{ echo "Normal"; }
		echo " </font>$mob_type</td>";
		echo "<td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"lefthead\"><strong class=\"dropmain\">Level</strong></td>";
		echo "<td class=\"dropmain\">$mob_level</td>";
		
		echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Hp/Mp</strong></td>";
		echo "<td class=\"dropmain\"><font color=$red_code>$mob_hp</font>&nbsp;/&nbsp;$mob_mp</td>";
		echo "<td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"lefthead\"><strong class=\"dropmain\">Exp</strong></td>";
		echo "<td class=\"dropmain\">$mob_exp</td>";

		echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Range</strong></td>";
		echo "<td class=\"dropmain\">$mob_atkrange</td>";
		echo "<td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"lefthead\"><strong class=\"dropmain\">Sp</strong></td>";
		echo "<td class=\"dropmain\">$mob_sp</td>";
		
		echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">P/M.atk</strong></td>";
		echo "<td class=\"dropmain\">$mob_patk&nbsp;/&nbsp;$mob_matk</td>";
		echo "<td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"lefthead\"><strong class=\"dropmain\">P/M.def</strong></td>";
		echo "<td class=\"dropmain\">$mob_pdef&nbsp;/&nbsp;$mob_mdef</td>";
		
		echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Walk/Run</strong></td>";
		echo "<td class=\"dropmain\">$mob_walk&nbsp;/&nbsp;$mob_run</td>";
		echo "<td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"lefthead\"><strong class=\"dropmain\">P/M.AtkSpd</strong></td>";
		echo "<td class=\"dropmain\">$mob_patks&nbsp;/&nbsp;$mob_matks</td>";

		echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Left Hand</strong></td>";
		echo "<td class=\"dropmain\">$mob_lefth</td>";
		echo "<td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"lefthead\"><strong class=\"dropmain\">Right Hand</strong></td>";
		echo "<td class=\"dropmain\">$mob_righth</td>";
		
		echo "</table></center>";
		
		
		// Now go through all the items that the mob drops or spwans and add them to an array.
		$drop_engine = 0;
		$sql = "show fields from droplist";
		$result2 = mysql_query($sql,$con);
		while ($r_array = mysql_fetch_assoc($result2)) 
		{
			if (strcasecmp($r_array['Field'], "category") == 0)
			{ $drop_engine = 1; }
		}
		$sql = "select itemid, min, max, sweep, chance from droplist where mobId = $mob_id";
		if ($drop_engine)
		{	$sql = "select itemid, min, max, category, chance from droplist where mobId = $mob_id";	}
		$result2 = mysql_query($sql,$con);
		$count_r = mysql_num_rows($result2);
		
		if (mysql_fetch_array($result2))
		{
			$itm_array = ARRAY();
			$i=0;
			while ($i < $count_r) 
			{
				$i_array = mysql_fetch_row($result2);
				$item_id = mysql_result($result2,$i,"itemId");
				if ($drop_engine)
				{
					$item_category = mysql_result($result2,$i,"category");
					if ($item_category < 0)
					{	$item_sweep = 1;	}
					else
					{	$item_sweep = 0;	}
				}
				else
				{
					$item_sweep = mysql_result($result2,$i,"sweep");
				}
				$item_chance = mysql_result($result2,$i,"chance");
			
				$error_finding = 0;
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
					if ($drop_engine)
					{	$item_chance *= $drop_chance_item;	}
					else
					{	$item_chance *= $drop_chance_adena;	}
				}
				elseif (!$item_sweep)
				{
				   $item_chance *= $drop_chance_item;
				}
				else
				{
					$item_chance *= $drop_chance_spoil;
				}
				if ($item_chance > 1000000)
				{
					$item_chance = 1000000;
				}
				$item_chance /=10000;
				array_push($itm_array, array($item_chance,mysql_result($result2,$i,"itemId"),$item_name,mysql_result($result2,$i,"min"),mysql_result($result2,$i,"max"),mysql_result($result2,$i,"sweep")));
				$i++;
			}
			arsort($itm_array);  // Sort the array based on the target key, which in this case is the chance percentage.
			reset($itm_array);

			echo "<p class=\"dropmain\">&nbsp;</p>";
			echo "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
			echo "<tr><td valign=\"top\" width=\"50%\" class=\"noborder\"><center>";

			// Go through the array displaying the drop items and putting the spoiled items in another array. 
			// (as the data is wiped from this one as we go through it.
			$i=0;
			$spoil_count = 0;
			echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\">";
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\">&nbsp;</td>"; }
			echo "<td colspan=\"5\" class=\"drophead\"><p class=\"drophead\">DROPS</p></td></tr><tr class=\"thead\">";
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"lefthead\">ID</td>"; }
			echo "<td width=\"32\" class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td><td width=\"150\" class=\"drophead\"><p class=\"left\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Min&nbsp;/&nbsp;Max</p></td><td class=\"drophead\"><p class=\"dropmain\">Chance</p></td></tr>";
			while ($i < $count_r) 
			{
				list($k1) = each($itm_array);
				$i_array = $itm_array[$k1];
				$item_id = $i_array[1];
				$item_name = $i_array[2];
				$item_min = $i_array[3];
				$item_max = $i_array[4];
				$item_sweep = $i_array[5];
				$item_chance = $i_array[0];
				if (($drop_engine) && ($item_id == 57))
				{
					$item_min = $item_min * $drop_chance_adena;
					$item_max = $item_max * $drop_chance_adena;
				}
				if ($item_sweep < 1)
				{
					echo "<tr>";
					if ($user_access_lvl >= $sec_inc_gmlevel)
					{ echo "<td class=\"left\"><p class=\"dropmain\">$item_id</p></td>"; }
					$item_id2 = item_check(0, $item_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
					echo "<td class=\"dropmain\"><p class=\"dropmain\"><img src=\"" . $images_dir . "items/$item_id2.gif\"></p></td>";
					if ($item_id == 57)
					{
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">$item_name</a> <strong class=\"dropmain\"> [ $item_min&nbsp;/&nbsp;$item_max ]</strong></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td>";
					}
					else
					{
					echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">$item_name</a></p></td>";
					echo "<td class=\"dropmain\"><p class=\"dropmain\">$item_min&nbsp;/&nbsp;<strong class=\"dropmain\">$item_max</strong></p></td>";
					}
					echo "<td class=\"dropmain\"><p class=\"dropmain\">";
					if ($item_chance >= 70)
					{ echo "<strong class=\"dropmain\"><font color=$green_code>$item_chance</font></strong>"; }
					elseif ($item_chance >= 10)
					{ echo "<strong class=\"dropmain\"><font color=$blue_code>$item_chance</font></strong>"; }
					else
					{ echo "<strong class=\"dropmain\"><font color=$red_code>$item_chance</font></strong>"; }
					echo "</p></td>";
					echo "</tr>";
				}
				else
				{
					$spoil_count++;
					if (!$spoil_array)
					{
						$spoil_array = array(array($item_id, $item_name, $item_min, $item_max, $item_chance));
					}
					else
					{
						array_push($spoil_array, array($item_id, $item_name, $item_min, $item_max, $item_chance));
					}
				}
				$i++;
			}
			echo "</table>";
			echo "</center></td>";
			echo "<td valign=\"top\" width=\"50%\" class=\"noborder\"><center>";

			// go through the spoil array and simply display the numbers, as the calculations have been done in the array scan before.
			$i=0;
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr class=\"thead\">";
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\">&nbsp;</td>"; }
			echo "<td colspan=\"5\" class=\"drophead\"><p class=\"dropmain\">SPOILS</p></td></tr><tr class=\"thead\">";
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"lefthead\">ID</td>"; }
			echo "<td width=\"32\" class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td><td width=\"150\" class=\"drophead\"><p class=\"left\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Min&nbsp;/&nbsp;Max</p></td><td class=\"drophead\"><p class=\"dropmain\">Chance</p></td></tr>";
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
					if (($drop_engine) && ($item_id == 57))
					{
						$item_min = $item_min * $drop_chance_adena;
						$item_max = $item_max * $drop_chance_adena;
					}
					echo "<tr>";
					if ($user_access_lvl >= $sec_inc_gmlevel)
					{ echo "<td class=\"left\"><p class=\"dropmain\">$item_id</p></td>"; }
					$item_id2 = item_check(0, $item_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
					echo "<td class=\"dropmain\"><p class=\"dropmain\"><img src=\"" . $images_dir . "items/$item_id2.gif\"></p></td>";
					if ($item_id == 57)
					{
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">$item_name</a> <strong class=\"dropmain\"> [ $item_min&nbsp;/&nbsp;$item_max ]</strong></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">&nbsp;</p></td>";
					}
					else
					{
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">$item_name</a></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$item_min&nbsp;/&nbsp;<strong class=\"dropmain\">$item_max</strong></p></td>";
					}
					echo "<td class=\"dropmain\"><p class=\"dropmain\">";
					if ($item_chance >= 70)
					{ echo "<strong class=\"dropmain\"><font color=$green_code>$item_chance</font></strong>"; }
					elseif ($item_chance >= 10)
					{ echo "<strong class=\"dropmain\"><font color=$blue_code>$item_chance</font></strong>"; }
					else
					{ echo "<strong class=\"dropmain\"><font color=$red_code>$item_chance</font></strong>"; }
					echo "</p></td>";
					echo "</tr>";
					$i++;
				}
			}

			echo "</table>";
			echo "</center></td></table>";
		}
		
		// If the user has map access, then display the big map with the mobs on it.
		if ($user_map_access)
		{
			echo "<p class=\"dropmain\">&nbsp;</p><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td colspan=\"3\" class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_mapkey</strong></p></td></tr><tr>";
			echo "<td class=\"dropmain\">&nbsp<img src=\"" . $images_dir . "underg.gif\" width=\"7\" height=\"7\" border=\"0\"> - $lang_day&nbsp;</td>";
			echo "<td class=\"dropmain\">&nbsp<img src=\"" . $images_dir . "overg.gif\" width=\"7\" height=\"7\" border=\"0\"> - $lang_night&nbsp;</td>";
			echo "<td class=\"dropmain\">&nbsp<img src=\"" . $images_dir . "wfree.gif\" width=\"7\" height=\"7\" border=\"0\"> - $lang_always&nbsp;</td>";
			echo "</tr></table></center>";
			echo "<p class=\"dropmain\">&nbsp;<p class=\"dropmain\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			map($map_array, $images_dir, 0);
			echo "</td></tr></table></center><p class=\"dropmain\">&nbsp;<p class=\"dropmain\">";
		}
	}
	else
	
	// We are at this point if the code has not been called with a specific item id ...
	// therefore we are doing a search based on keyword or other request.
	{
		$num_in = "0";
		if (($itemname > 0) && ($itemname < 100))
		{	$num_in = "1";	}
		if ($monsdetreq)
		{	$num_in = "2";	}
		if ((strlen($itemname) < 3) && ($num_in == "0"))   // We need at least three characacters, or we'll produce too many results.
		{
		writewarn("Please give at least three characters.");
		}
		else
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
			$sql = "select distinct type from npc where type <> 'L2Monster' and type <> 'L2Minion' union select distinct type from custom_npc where type <> 'L2Monster' and type <> 'L2Minion'";
			$result = mysql_query($sql,$con);
			if ($monsdetshow)
			{
				echo "<center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=0-10&detshow='2'\" class=\"dropmain\">1-10</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=11-20&detshow='2'\" class=\"dropmain\">11-20</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=21-30&detshow='2'\" class=\"dropmain\">21-30</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=31-40&detshow='2'\" class=\"dropmain\">31-40</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=41-50&detshow='2'\" class=\"dropmain\">41-50</a></td>
				<td rowspan=\"2\"><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"\">- $lang_sct -</option>";
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$c_var = mysql_result($result,$i,"type");
		    		echo "<option value=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=$c_var&detshow='2'\">$c_var</option>";
					$i++;
  				}
				echo "</select></p></td></tr><tr>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=51-60&detshow='2'\" class=\"dropmain\">51-60</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=61-70&detshow='2'\" class=\"dropmain\">61-70</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=71-80&detshow='2'\" class=\"dropmain\">71-80</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=80plus&detshow='2'\" class=\"dropmain\">80+</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=Undead&detshow='2'\" class=\"dropmain\">$lang_undead</a></td>
				</tr></table></center>";
			}
			// Query for monster name
			if (!$monstersort)
			{
				$monstersort = "level";
			}			
			if ($num_in == "1")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where level = $itemname order by $monstersort"; }
			elseif ($num_in == "0")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where name like '%$itemname%' order by $monstersort";  }
			elseif ($monsdetreq == "0-10")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 11 order by $monstersort";  }
			elseif ($monsdetreq == "11-20")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 21 and level > 10 order by $monstersort";  }
			elseif ($monsdetreq == "21-30")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 31 and level > 20 order by $monstersort"; }
			elseif ($monsdetreq == "31-40")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 41 and level > 30 order by $monstersort"; }
			elseif ($monsdetreq == "41-50")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 51 and level > 40 order by $monstersort"; }
			elseif ($monsdetreq == "51-60")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 61 and level > 50 order by $monstersort"; }
			elseif ($monsdetreq == "61-70")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 71 and level > 60 order by $monstersort"; }
			elseif ($monsdetreq == "71-80")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 81 and level > 70 order by $monstersort"; }
			elseif ($monsdetreq == "80plus")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level > 80 order by $monstersort"; }
			elseif ($monsdetreq == "Undead")
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where isundead = 1 order by $monstersort";  }
			else
			{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where type = '$monsdetreq' order by $monstersort";	}
			if (!$result = mysql_query($sql,$con))
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			// If return array empty, then mobname not found.
			
			$count_r = mysql_num_rows($result);
			
			if (!mysql_fetch_array($result))
			{
				writeerror("Sorry, no matching monsters found!");
				return 0;
			}
			else
			{
				echo "<p class=\"dropmain\">&nbsp;</p>";			
				if (($mob_spawn > 0) || ($user_access_lvl >= $sec_inc_gmlevel)) // If the user is admin level, the display button to allow them to see
				{																	// mobs that hve no spawns in current database.
					echo "<center><table class=\"blanktab\" cellpadding=\"5\" class=\"dropmain\"><tr><td class=\"noborder\"><form method=\"post\" action=\"m-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$itemname\"><input value=\" - Normal View -\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"detreq\" type=\"hidden\" value=\"$monsdetreq\"><input name=\"detshow\" type=\"hidden\" value=\"$monsdetshow\"></form></td>";
					echo "<td class=\"noborder\"><form method=\"post\" action=\"m-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$itemname\"><input value=\" - Show Spawned and Non-Spawned -\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"adminshow\" type=\"hidden\" value=\"1\"><input name=\"detreq\" type=\"hidden\" value=\"$monsdetreq\"><input name=\"detshow\" type=\"hidden\" value=\"$monsdetshow\"></form></td>";
					echo "<td class=\"noborder\"><form method=\"post\" action=\"m-search.php\"><input name=\"itemname\" type=\"hidden\" value=\"$itemname\"><input value=\" - Show Non-Spawned Only -\" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"adminshow\" type=\"hidden\" value=\"2\"><input name=\"detreq\" type=\"hidden\" value=\"$monsdetreq\"><input name=\"detshow\" type=\"hidden\" value=\"$monsdetshow\"></form></td></tr></table></center>";
				}

				echo "<p class=\"dropmain\">&nbsp;</p>";
				echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
				echo "<tr class=\"thead\">";
				if ($user_access_lvl >= $sec_inc_gmlevel)  // If admin, then add an extra column to show mob ID.
				{ echo "<td class=\"drophead\">ID</td>"; }
				echo "<td width=\"250\" class=\"lefthead\"><p class=\"dropmain\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=name DESC&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_name</strong><br><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=name&adminshow=$monsadmshow&&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
				echo "<td class=\"drophead\"><p class=\"center\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=type DESC, aggro DESC&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_type</strong><br><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=type, aggro&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
				echo "<td class=\"drophead\"><p class=\"center\">$lang_undead</p></td>";
				echo "<td class=\"drophead\"><p class=\"center\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=level DESC&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Level</strong><br><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=level&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
				echo "<td class=\"drophead\"><p class=\"center\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=hp DESC, mp DESC&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Hp/Mp</strong><br><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=hp, mp&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
				echo "<td class=\"drophead\"><p class=\"center\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=exp DESC&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Exp</strong><br><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=exp&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
				echo "<td class=\"drophead\"><p class=\"center\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=sp DESC&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Sp</strong><br><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=sp&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
				echo "<td class=\"drophead\"><p class=\"center\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=attackrange DESC&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Range</strong><br><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monstersort=attackrange&adminshow=$monsadmshow&detreq=$monsdetreq&detshow=$monsdetshow\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
				echo "<td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">$lang_spawn</strong><br><font color=$green_code>$lang_day</font>&nbsp;-&nbsp;<font color=$red_code>$lang_night</font>&nbsp;-&nbsp;Perm</p></td>";
				echo "</tr>\n";
				$i=0;
				while ($i < $count_r) 
				{		// Display each mob found in turn.
					$mob_id = mysql_result($result,$i,"id");
					$mob_name = mysql_result($result,$i,"name");
					$mob_type = mysql_result($result,$i,"type");
					$mob_level = mysql_result($result,$i,"level");
					$mob_hp = mysql_result($result,$i,"hp");
					$mob_mp = mysql_result($result,$i,"mp");
					$mob_exp = mysql_result($result,$i,"exp");
					$mob_sp = mysql_result($result,$i,"sp");
					$mob_atkrange = mysql_result($result,$i,"attackrange");
					$mob_aggro = mysql_result($result,$i,"aggro");
					$mob_undead = mysql_result($result,$i,"isundead");
					$mob_spawn = 0;
					$mob_days = 0;
					$mob_dayt = 0;
					$mob_nights = 0;
					$mob_nightt = 0;
					$mob_normals = 0;
					$mob_normalt = 0;
					$sql = "select locx, locy, locz, loc_id from spawnlist where npc_templateid = $mob_id";
					$result2 = mysql_query($sql,$con);
					$count_r2 = mysql_num_rows($result2);
					if (mysql_fetch_array($result2))
					{
						$mob_spwn = mobcount($mob_id,$db_location,$db_user,$db_psswd, $db_l2jdb);
						$mob_spawn = $mob_spwn[1];
						$mob_count = $mob_spwn[0];
						$mob_days = $mob_spwn[2];
						$mob_dayt = $mob_spwn[3];
						$mob_nights = $mob_spwn[4];
						$mob_nightt = $mob_spwn[5];
						$mob_normals = $mob_spwn[6];
						$mob_normalt = $mob_spwn[7];
					}
					else
					{ $mob_count = 0; }
					// For non admin users, show mobs who have got spawn points.
					if ($user_access_lvl < $sec_inc_gmlevel)
					{
						if ($mob_spawn > 0)
						{
							echo "<tr>";
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"dropmain\">$mob_id</td>"; }
							echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$mob_id\" class=\"dropmain\">$mob_name</a></p></td><td class=\"dropmain\"><p class=\"dropmain\">";
							if ($mob_aggro > 0) { echo "<font color=#ff0000>"; }
							echo "$mob_type";
							if ($mob_aggro > 0) { echo "</font>"; }
							echo "</p></td><td class=\"dropmain\"><p class=\"dropmain\">";
							if ($mob_undead)
							{	echo "Yes";	}
							else 
							{	echo "&nbsp";	}
							echo "</p></td><td class=\"dropmain\"><p class=\"center\">$mob_level</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_hp&nbsp;/&nbsp;$mob_mp</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_exp</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_sp</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_atkrange</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">";
							echo "<font color=$green_code>$mob_days/$mob_dayt</font>&nbsp;-&nbsp;<font color=$red_code>$mob_nights/$mob_nightt</font>&nbsp;-&nbsp;$mob_normals/$mob_normalt";
/* $mob_count</strong>&nbsp;/&nbsp;$mob_spawn */
							echo "</strong></p></td></tr>";
						}
					}
					else
					{
						$show_mob = "0";
						if (($mob_spawn > 0) && (!$monsadmshow))
						{	$show_mob = "1";	}
						if (($mob_spawn == 0) && ($monsadmshow == "2"))
						{	$show_mob = "1";	}
						if ($monsadmshow == "1")
						{	$show_mob = "1";	}
						if ($show_mob)
						{
							echo "<tr>";
							if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"dropmain\">$mob_id</td>"; }
							echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$mob_id\" class=\"dropmain\">$mob_name</a></p></td><td class=\"dropmain\"><p class=\"dropmain\">";
							if ($mob_aggro > 0) { echo "<font color=$red_code>"; }
							echo "$mob_type";
							if ($mob_aggro > 0) { echo "</font>"; }
							echo "</p></td><td class=\"dropmain\"><p class=\"dropmain\">";
							if ($mob_undead)
							{	echo "Yes";	}
							else 
							{	echo "&nbsp";	}
							echo "</p></td><td class=\"dropmain\"><p class=\"center\">$mob_level</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_hp&nbsp;/&nbsp;$mob_mp</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_exp</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_sp</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\">$mob_atkrange</p></td>";
							echo "<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">";
							echo "<font color=$green_code>$mob_days/$mob_dayt</font>&nbsp;-&nbsp;<font color=$red_code>$mob_nights/$mob_nightt</font>&nbsp;-&nbsp;$mob_normals/$mob_normalt";
/* $mob_count</strong>&nbsp;/&nbsp;$mob_spawn */
							echo "</strong></p></td></tr>";
						}
					}
				$i++;
				}
			echo "</table></center><p class=\"dropmain\">&nbsp;</p>";
			}
		}
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>