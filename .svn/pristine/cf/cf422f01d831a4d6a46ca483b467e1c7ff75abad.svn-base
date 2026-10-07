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
$action = input_check($_REQUEST['action'],0);
$sec_map = input_check($_REQUEST['secmap'],0);

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
		die('Could not change to $db_l2jdb database: ' . mysql_error());
	}

	$mobnum = intval($itemname);
	if ($mobnum > 0)
	{
		$sql = "select id from knightnpc where id = '$mobnum' union select id from custom_npc where id = '$mobnum'";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);
		if ($count)
		{	$monsterid = $mobnum;	}
	}

	// If the script called itself with a monster, then look up that monsters details.
	if ($monsterid)
	{
		$i = 0;
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

		// Retrieve detailed mob informtion and put in variables.
		$mob_id = mysql_result($result,$i,"id");
		$mob_name = mysql_result($result,$i,"name");
		$mob_type = mysql_result($result,$i,"type");
		$mob_level = mysql_result($result,$i,"level");
		$mob_hp = round(mysql_result($result,$i,"hp"));
		$mob_mp = round(mysql_result($result,$i,"mp"));
		$mob_exp = mysql_result($result,$i,"exp");
		$mob_sp = mysql_result($result,$i,"sp");
		$mob_atkrange = mysql_result($result,$i,"attackrange");
		$mob_aggro = mysql_result($result,$i,"aggro");
		$sql = "select * from spawnlist where npc_templateid = $mob_id";
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
			$sql = "select name from knightarmour where item_id = $mob_left";  // Try armour database
			$result3 = mysql_query($sql,$con);
			if (!mysql_fetch_array($result3))
			{
				$sql = "select name from knightweapon where item_id = $mob_left"; // Try weapons database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$sql = "select name from knightetcitem where item_id = $mob_left"; // Try etc_items database
					$result3 = mysql_query($sql,$con);
					if (!mysql_fetch_array($result3))
					{
						$error_finding = 1;
					}
				}
			}
			if ($error_finding)
			{ $mob_lefth = "$lang_unknown"; }
			else
			{ $mob_lefth = mysql_result($result3,0,"name"); }
		}
		
		// Lok up the name of what is in the mobs right hand.
		if (!$mob_right)
		{ $mob_righth = "Nothing"; }
		else
		{
			$error_finding = 0;
			$sql = "select name from knightarmour where item_id = $mob_right";  // Try armour database
			$result3 = mysql_query($sql,$con);
			if (!mysql_fetch_array($result3))
			{
				$sql = "select name from knightweapon where item_id = $mob_right"; // Try weapons database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$sql = "select name from knightetcitem where item_id = $mob_right"; // Try etc_items database
					$result3 = mysql_query($sql,$con);
					if (!mysql_fetch_array($result3))
					{
						$error_finding = 1;
					}
				}
			}
			if ($error_finding)
			{ $mob_righth = "$lang_unknown"; }
			else
			{ $mob_righth = mysql_result($result3,0,"name"); }
		}

		$sql = "show fields from spawnlist";
		if (!$result2 = mysql_query($sql,$con))
		{
			die('Could not retrieve fields from spawnlist database: ' . mysql_error());
		}
		$count_r = mysql_num_rows($result2);
		$i2=0;
		$daynight = 0;
		while ($i2 < $count_r) 
		{
			$i_id = mysql_result($result2,$i2,"field");
			if (strcasecmp($i_id, "periodofday") == 0)
			{ $daynight = 1; }
			$i2++;
		}

		//Search through the spawnlog and locations database to find all the spawn points for the mob and put them in the map database.
		$mob_spawnnum = 0;
		$mob_days = 0;
		$mob_dayt = 0;
		$mob_nights = 0;
		$mob_nightt = 0;
		$mob_normals = 0;
		$mob_normalt = 0;
		if ($daynight)
		{	$sql = "select locx, locy, locz, loc_id, count, periodOfDay from spawnlist where npc_templateid = $mob_id";	}
		else
		{	$sql = "select locx, locy, locz, loc_id, count from spawnlist where npc_templateid = $mob_id";	}
		$result2 = mysql_query($sql,$con);
		$count_r2 = mysql_num_rows($result2);
		if (mysql_fetch_array($result2))
		{
			$i2=0;
			$mob_count = 0;
			while ($i2 < $count_r2) 
			{
				$map_tag = 2;
				if ((mysql_result($result2,$i2,"locx") <> 0) || (mysql_result($result2,$i2,"locy") <> 0) || (mysql_result($result2,$i2,"locz") <> 0))
				{
					$mob_spawnnum++;
					$mob_count++;
					if ( $daynight )
					{	$periodofday = mysql_result($result2,$i2,"periodOfDay");	}
					else
					{	$periodofday = 0;	}
					if ( $periodofday == 1 )
					{
						$mob_days++;
						$mob_dayt++;
						$map_tag = 0;
					}
					elseif ( $periodofday == 2 )
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
					if ( $daynight )
					{	$periodofday = mysql_result($result2,$i2,"periodOfDay");	}
					else
					{	$periodofday = 0;	}
					if ( $periodofday == 1 )
					{
						$mob_days++;
					}
					elseif ( $periodofday == 2 )
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
							if ( $periodofday == 1 )
							{
								$mob_dayt++;
								$map_tag = 0;
							}
							elseif ( $periodofday == 2 )
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
		$sql = "select loc_x, loc_y, loc_z from raidboss_spawnlist where boss_id = $mob_id";
		$result2 = mysql_query($sql,$con);
		$count_r2 = mysql_num_rows($result2);
		if (mysql_fetch_array($result2))
		{
			$i2=0;
			while ($i2 < $count_r2) 
			{
				if ((mysql_result($result2,$i2,"loc_x") <> 0) || (mysql_result($result2,$i2,"loc_y") <> 0) || (mysql_result($result2,$i2,"loc_z") <> 0))
				{
					$mob_spawnnum++;
					$mob_count++;
					$mob_normals++;
					$mob_normalt++;
					$locat_x = mysql_result($result2,$i2,"loc_x");
					$locat_y = mysql_result($result2,$i2,"loc_y");
					if (!$map_array)
					{
						$map_array = array(array($locat_x, $locat_y, 0));
					}
					else
					{
						array_push($map_array, array($locat_x, $locat_y, 0));
					}
				}
				$i2++;
			}
		}
		
		$sql = "select boss_id, amount_min, amount_max from minions where minion_id = $mob_id";
		$result2 = mysql_query($sql,$con);
		$count_r2 = mysql_num_rows($result2);
		if (mysql_fetch_array($result2))
		{
			$i2=0;
			while ($i2 < $count_r2) 
			{
				$boss_id = mysql_result($result2,$i3,"boss_id");
				$minion_spawns = mysql_result($result2,$i3,"amount_max");
				$minion_spawn_min = mysql_result($result2,$i3,"amount_min");
				$sql = "select loc_x, loc_y, loc_z from raidboss_spawnlist where boss_id = $boss_id";
				$result3 = mysql_query($sql,$con);
				$count_r3 = mysql_num_rows($result3);
				if (mysql_fetch_array($result3))
				{
					$i3=0;
					while ($i3 < $count_r3) 
					{
						if ((mysql_result($result3,$i3,"loc_x") <> 0) || (mysql_result($result3,$i3,"loc_y") <> 0) || (mysql_result($result2,$i3,"loc_z") <> 0))
						{
							$mob_spawnnum += $minion_spawns;
							$mob_count += $minion_spawn_min;
							$mob_normals += $minion_spawn_min;
							$mob_normalt += $minion_spawns;
							$locat_x = mysql_result($result3,$i3,"loc_x");
							$locat_y = mysql_result($result3,$i3,"loc_y");
							if (!$map_array)
							{
								$map_array = array(array($locat_x, $locat_y, 0));
							}
							else
							{
								array_push($map_array, array($locat_x, $locat_y, 0));
							}
						}
						$i3++;
					}
					
				}
				$sql = "select locx, locy, locz from spawnlist where npc_templateid = $boss_id union select locx, locy, locz from custom_spawnlist where npc_templateid = $boss_id";
				$result3 = mysql_query($sql,$con);
				$count_r3 = mysql_num_rows($result3);
				if (mysql_fetch_array($result3))
				{
					$i3=0;
					while ($i3 < $count_r3) 
					{
						if ((mysql_result($result3,$i3,"locx") <> 0) || (mysql_result($result3,$i3,"locy") <> 0) || (mysql_result($result2,$i3,"locz") <> 0))
						{
							$mob_spawnnum += $minion_spawns;
							$mob_count += $minion_spawn_min;
							$mob_normals += $minion_spawn_min;
							$mob_normalt += $minion_spawns;
							$locat_x = mysql_result($result3,$i3,"locx");
							$locat_y = mysql_result($result3,$i3,"locy");
							if (!$map_array)
							{
								$map_array = array(array($locat_x, $locat_y, 0));
							}
							else
							{
								array_push($map_array, array($locat_x, $locat_y, 0));
							}
						}
						$i3++;
					}
				}
				$i2++;
			}
		}
		
	$result3 = mysql_query("select groupId from random_spawn where npcId = $mob_id",$con);
	while ($r_array = mysql_fetch_assoc($result3))		
	{
		$mob_group = $r_array['groupId'];
		$count = 0;
		$result4 = mysql_query("select x, y, z from random_spawn_loc where groupId = $mob_group",$con);
		while ($r_array = mysql_fetch_assoc($result4))		
		{
			if (!$map_array)
			{	
				$map_array = array(array($r_array['x'], $r_array['y'], 0));	
				$map_locs = array(array($r_array['x'], $r_array['y'], $r_array['z']));	
			}
			else
			{
				array_push($map_array, array($r_array['x'], $r_array['y'], 0));	
				array_push($map_locs, array($r_array['x'], $r_array['y'], $r_array['z']));	
			}
			if ($count == 0)
			{	
				$mob_normals++;	
				$mob_spawnnum++;	
			}
			$mob_normalt++;
			$mob_count++;
			$count = 1;
		}
	}
		// Display detailed information about the mob.
		echo "<p class=\"dropmain\">&nbsp;</p>";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr><td class=\"noborderback\">";
		$image_loc = $images_loc_dir . "mobs" . $svr_dir_delimit . $mob_id . ".jpg";
		if (($showmobpict) && (file_exists($images_loc_dir . "mobs" . $svr_dir_delimit . $mob_id . ".jpg")))
		{	
			echo "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"dropmain\">";
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">$lang_name</strong></td><td width=\"200\" class=\"dropmain\">";
			if ($mob_aggro > 0)
			{ echo "<strong class=\"dropmain\"><font color=$red_code>$mob_name</font></strong></td>"; }
			else
			{ echo "<strong class=\"dropmain\"><font color=$green_code>$mob_name</font></strong></td>"; }
			echo "<td class=\"noborderback\" rowspan=\"15\"><img src=\"" . $images_dir . "mobs/" . $mob_id . ".jpg\"></td>";
			echo "</tr>";
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Sex</strong></td>";
			if ($mob_sex == "male")
			{ echo "<td width=\"150\" class=\"dropmain\"><center><img src=\"" . $images_dir . "male.gif\" width=\"10\" height=\"14\" border=\"0\"></center></td>"; }
			else
			{ echo "<td width=\"150\" class=\"dropmain\"><center><img src=\"" . $images_dir . "female.gif\" width=\"10\" height=\"14\" border=\"0\"></center></td></tr></tr>"; }
	
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">$lang_type</strong></td><td class=\"dropmain\"><font color=#6B5D10>";
			echo "Normal";
			echo " </font>$mob_type</td>";
			echo "</tr><tr>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Level</strong></td>";
			echo "<td class=\"dropmain\">$mob_level</td></tr>";
			
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Hp/Mp</strong></td>";
			echo "<td class=\"dropmain\"><font color=$red_code>$mob_hp</font>&nbsp;/&nbsp;$mob_mp</td>";
			echo "</tr><tr>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Exp</strong></td>";
			echo "<td class=\"dropmain\">$mob_exp</td></tr>";
	
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Range</strong></td>";
			echo "<td class=\"dropmain\">$mob_atkrange</td>";
			echo "</tr><tr>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Sp</strong></td>";
			echo "<td class=\"dropmain\">$mob_sp</td></tr>";
			
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">P/M.atk</strong></td>";
			echo "<td class=\"dropmain\">$mob_patk&nbsp;/&nbsp;$mob_matk</td>";
			echo "</tr><tr>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">P/M.def</strong></td>";
			echo "<td class=\"dropmain\">$mob_pdef&nbsp;/&nbsp;$mob_mdef</td></tr>";
			
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Walk/Run</strong></td>";
			echo "<td class=\"dropmain\">$mob_walk&nbsp;/&nbsp;$mob_run</td>";
			echo "</tr><tr>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">P/M.AtkSpd</strong></td>";
			echo "<td class=\"dropmain\">$mob_patks&nbsp;/&nbsp;$mob_matks</td></tr>";
	
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Left Hand</strong></td>";
			echo "<td class=\"dropmain\">$mob_lefth</td>";
			echo "</tr><tr>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Right Hand</strong></td>";
			echo "<td class=\"dropmain\">$mob_righth</td></tr>";
	
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{	echo "<td class=\"lefthead\"><center><form method=\"post\" action=\"javascript:popit('m-locs.php?username=$username&token=$token&langval=$langval&server_id=$server_id&mob=$mob_id','500','400');\"><input value=\"Mob Locations\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form>";	}
			else
			{	echo "<tr><td class=\"lefthead\">&nbsp;</td>";	}
			if ($action=="showskills")
			{	echo "<td class=\"dropmain\"><center><form method=\"post\" action=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&monsterid=$monsterid&monstersort=$monstersort&adminshow=$monsadmshow&detreq=$monsdetreq&detshow$monsdetshow&action=\"><input value=\"Hide Skills\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
			else
			{	echo "<td class=\"dropmain\"><center><form method=\"post\" action=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&monsterid=$monsterid&monstersort=$monstersort&adminshow=$monsadmshow&detreq=$monsdetreq&detshow$monsdetshow&action=showskills\"><input value=\"Show Skills\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
			echo "</tr></table>";
		}
		else
		{
			echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\">";
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">$lang_name</strong></td><td width=\"230\" class=\"dropmain\">";
			if ($mob_aggro > 0)
			{ echo "<strong class=\"dropmain\"><font color=$red_code>$mob_name</font></strong></td>"; }
			else
			{ echo "<strong class=\"dropmain\"><font color=$green_code>$mob_name</font></strong></td>"; }
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td><td class=\"lefthead\"><strong class=\"dropmain\">Sex</strong></td>";
			if ($mob_sex == "male")
			{ echo "<td width=\"150\" class=\"dropmain\"><center><img src=\"" . $images_dir . "male.gif\" width=\"10\" height=\"14\" border=\"0\"></center></td>"; }
			else
			{ echo "<td width=\"150\" class=\"dropmain\"><center><img src=\"" . $images_dir . "female.gif\" width=\"10\" height=\"14\" border=\"0\"></center></td></tr></tr>"; }

			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">$lang_type</strong></td><td class=\"dropmain\"><font color=#6B5D10>";
			echo "Normal"; 
			echo " </font>$mob_type</td>";
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Level</strong></td>";
			echo "<td class=\"dropmain\">$mob_level</td></tr>";
			
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Hp/Mp</strong></td>";
			echo "<td class=\"dropmain\"><font color=$red_code>$mob_hp</font>&nbsp;/&nbsp;$mob_mp</td>";
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Exp</strong></td>";
			echo "<td class=\"dropmain\">$mob_exp</td></tr>";
	
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Range</strong></td>";
			echo "<td class=\"dropmain\">$mob_atkrange</td>";
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Sp</strong></td>";
			echo "<td class=\"dropmain\">$mob_sp</td></tr>";
			
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">P/M.atk</strong></td>";
			echo "<td class=\"dropmain\">$mob_patk&nbsp;/&nbsp;$mob_matk</td>";
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">P/M.def</strong></td>";
			echo "<td class=\"dropmain\">$mob_pdef&nbsp;/&nbsp;$mob_mdef</td></tr>";
			
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Walk/Run</strong></td>";
			echo "<td class=\"dropmain\">$mob_walk&nbsp;/&nbsp;$mob_run</td>";
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">P/M.AtkSpd</strong></td>";
			echo "<td class=\"dropmain\">$mob_patks&nbsp;/&nbsp;$mob_matks</td></tr>";
	
			echo "<tr><td class=\"lefthead\"><strong class=\"dropmain\">Left Hand</strong></td>";
			echo "<td class=\"dropmain\">$mob_lefth</td>";
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td>";
			echo "<td class=\"lefthead\"><strong class=\"dropmain\">Right Hand</strong></td>";
			echo "<td class=\"dropmain\">$mob_righth</td></tr>";
	
			echo "<tr><td class=\"lefthead\">&nbsp;</td>";
			if ($action=="showskills")
			{	echo "<td class=\"dropmain\"><center><form method=\"post\" action=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&monsterid=$monsterid&monstersort=$monstersort&adminshow=$monsadmshow&detreq=$monsdetreq&detshow$monsdetshow&action=\"><input value=\"Hide Skills\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
			else
			{	echo "<td class=\"dropmain\"><center><form method=\"post\" action=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&monsterid=$monsterid&monstersort=$monstersort&adminshow=$monsadmshow&detreq=$monsdetreq&detshow$monsdetshow&action=showskills\"><input value=\"Show Skills\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
			echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"30\">&nbsp;</td>";
			echo "<td class=\"lefthead\">&nbsp;</td>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{	echo "<td class=\"dropmain\"><center><form method=\"post\" action=\"javascript:popit('m-locs.php?username=$username&token=$token&langval=$langval&server_id=$server_id&mob=$mob_id','500','400');\"><input value=\"Mob Locations\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td></tr>";	}
			else
			{	echo "<td class=\"dropmain\">&nbsp;</td>";	}
			echo "</tr></table>";
		}
		echo "</td><td valign=\"center\" align=\"center\">";
		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
		if ($user_map_access)
			{
				echo "<p class=\"dropmain\">&nbsp;<p class=\"dropmain\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
				map_2($map_array, $images_dir,1);
				echo "</td><td>";
				map_2($map_array, $images_dir,2);
				echo "</td></tr></table>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<p class=\"dropmain\"><br>Mob ID - $mob_id</p>"; }
				echo "<p class=\"dropmain\"><strong class=\"dropmain\">$lang_spawn - $mob_count&nbsp;/&nbsp;$mob_spawnnum</strong><br><br>";
				echo "<table cellpadding=\"3\" class=\"noborder\"><tr><td class=\"dropmain\"><center><font color=$green_code>$lang_day</font></center></td><td class=\"dropmain\"><center><font color=$red_code>$lang_night</font></center></td><td class=\"dropmain\"><p class=\"center\">Perm</p></td></tr><tr><td class=\"dropmain\"><font color=$green_code>$mob_days/$mob_dayt</font></td><td class=\"dropmain\"><font color=$red_code>$mob_nights/$mob_nightt</font></td><td class=\"dropmain\"><p class=\"center\">$mob_normals/$mob_normalt</p></td></tr></table></p>";
				if ($user_access_lvl >= $adjust_drops)
				{ echo "<form action=\"drops.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<input name=\"mobid\" type=\"hidden\" value=\"$mob_id\">
					<input value=\"Adj. Drops\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form>";	}
				echo "</center>";
			}
			else
			{
			echo "<p class=\"dropmain\">&nbsp;<p class=\"dropmain\"><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\"><img src=\"" . $images_dir . "map2.jpg\" width=\"104\" height=\"150\"></td></tr></table><p class=\"dropmain\"><strong class=\"dropmain\">$lang_spawn - $mob_count&nbsp;/&nbsp;$mob_spawnnum</strong><br><br>";
			echo "<table cellpadding=\"3\" class=\"noborder\"><tr><td class=\"dropmain\"><center><font color=$green_code>$lang_day</font></center></td><td class=\"dropmain\"><center><font color=$red_code>$lang_night</font></center></td><td class=\"dropmain\"><p class=\"center\">Perm</p></td></tr><tr><td class=\"dropmain\"><font color=$green_code>$mob_days/$mob_dayt</font></td><td class=\"dropmain\"><font color=$red_code>$mob_nights/$mob_nightt</font></td><td class=\"dropmain\"><p class=\"center\">$mob_normals/$mob_normalt</p></td></tr></table>";
			echo "</p></center><p class=\"dropmain\">&nbsp;<p class=\"dropmain\">";
			}
		echo "</td></tr></table>";
		echo "</td></tr></table></center>";
		
		if ($action=="showskills")
		{
			echo "<center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><strong class=\"dropmain\">Mob Skill</strong></td><td class=\"lefthead\"><strong class=\"dropmain\">Level</strong></td></tr>";
			$result2 = mysql_query("select skillid, level from knightnpcskills where npcid = '$mob_id' union select skillid, level from custom_npcskills where npcid = '$mob_id'",$con);
			while ($r_array = mysql_fetch_assoc($result2)) 
			{
				$skill_name = "unknown";
				$skill_id = $r_array['skillid'];
				$skill_lvl = $r_array['level'];
				$result3 = mysql_query("select name from knightskills where skill_id = '$skill_id'",$con);
				while ($r_array = mysql_fetch_assoc($result3)) 
				{	$skill_name = $r_array['name'];	}
				echo "<tr><td class=\"dropmain\">$skill_name</td><td class=\"dropmain\">$skill_lvl</td></tr>";
			}
			echo "</table></center>";
		}
		
		// Now go through all the items that the mob drops or spwans and add them to an array.
		$drop_engine = 0;
		$sql = "show fields from droplist";
		$result2 = mysql_query($sql,$con);
		while ($r_array = mysql_fetch_assoc($result2)) 
		{
			if (strcasecmp($r_array['Field'], "category") == 0)
			{ $drop_engine = 1; }
		}
		$sql = "select itemid, min, max, sweep, chance from droplist where mobId = $mob_id union select itemid, min, max, sweep, chance from custom_droplist where mobId = $mob_id";
		if ($drop_engine)
		{	$sql = "select itemid, min, max, category, chance from droplist where mobId = $mob_id union select itemid, min, max, category, chance from custom_droplist where mobId = $mob_id order by category, chance DESC";	}
		$result2 = mysql_query($sql,$con);
		$count_r = mysql_num_rows($result2);
		$item_category = 0;
		if (mysql_fetch_array($result2))
		{
			$itm_array = ARRAY();
			$itm_carray = ARRAY();
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
				$sql = "select name from knightarmour where item_id = $item_id";  // Try armour database
				$result3 = mysql_query($sql,$con);
				if (!mysql_fetch_array($result3))
				{
					$sql = "select name from knightweapon where item_id = $item_id"; // Try weapons database
					$result3 = mysql_query($sql,$con);
					if (!mysql_fetch_array($result3))
					{
						$sql = "select name from knightetcitem where item_id = $item_id"; // Try etc_items database
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
				{	$item_chance *= $drop_chance_adena;	}
				elseif (!$item_sweep)
				{   $item_chance *= $drop_chance_item;	}
				else
				{	$item_chance *= $drop_chance_spoil;	}
				
				$item_chance /=10000;

				$i_min = mysql_result($result2,$i,"min");
				$i_max = mysql_result($result2,$i,"max");
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
				if ($item_chance > 100)
				{	$item_chance = 100;	}
				if ($item_sweep == 0)
				{
					if (array_key_exists($item_category, $itm_carray))
					{	$itm_carray[$item_category] = $itm_carray[$item_category] + $item_chance;	}
					else 
					{	$itm_carray[$item_category] = $item_chance;	}
				}
				array_push($itm_array, array($item_chance,mysql_result($result2,$i,"itemId"),$item_name,$i_min,$i_max,$item_sweep, $item_category));
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
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\">&nbsp;</td>"; }
			echo "<td colspan=\"5\" class=\"drophead\"><p class=\"drophead\">DROPS</p></td></tr><tr class=\"thead\">";
			if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"lefthead\">ID</td>"; }
			echo "<td width=\"32\" class=\"drophead\"><p class=\"dropmain\">&nbsp;</p></td><td width=\"150\" class=\"drophead\"><p class=\"left\">$lang_name</p></td><td class=\"drophead\"><p class=\"dropmain\">Min&nbsp;/&nbsp;Max</p></td><td class=\"drophead\"><p class=\"dropmain\">Chance</p></td></tr>";
			$bg_colour = 1;
			$last_bg = -1;
			$item_cat = 0;

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
				if ($drop_engine)
				{
					$item_cat = $i_array[6];
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
				if ($item_sweep < 1)
				{
					echo "<tr>";
					if ($user_access_lvl >= $sec_inc_gmlevel)
					{ echo "<td class=\"$bg_class2\"><p class=\"dropmain\">$item_id</p></td>"; }
					$item_id2 = item_check(0, $item_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
					echo "<td class=\"$bg_class\"><p class=\"dropmain\"><img src=\"" . $images_dir . "items/$item_id2.gif\"></p></td>";
					if ($item_id == 57)
					{
						echo "<td class=\"$bg_class2\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">$item_name</a> <strong class=\"dropmain\"> [ $item_min&nbsp;/&nbsp;$item_max ]</strong></p></td>";
						echo "<td class=\"$bg_class\"><p class=\"dropmain\">&nbsp;</p></td>";
					}
					else
					{
					echo "<td class=\"$bg_class2\"><p class=\"dropmain\"><a href=\"i-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"dropmain\">$item_name</a></p></td>";
					echo "<td class=\"$bg_class\"><p class=\"dropmain\">$item_min&nbsp;/&nbsp;<strong class=\"dropmain\">$item_max</strong></p></td>";
					}
					echo "<td class=\"$bg_class\"><p class=\"dropmain\">";
					// At this point, we have the total chance for that catagory
					// plus the individual item chance within the catagory.
					if ($drop_engine)
					{
						$itm_total = $itm_carray[$item_cat];
						if ($itm_total > 100)
						{	$item_chance = intval(((100/$itm_total)*$item_chance)*10000)/10000;	}
					}
					if ($item_chance > 100)
					{	$item_chance = 100;	}
					if ($item_chance >= 70)
					{ echo "<strong class=\"$bg_class\"><font color=$green_code>$item_chance%</font></strong>"; }
					elseif ($item_chance >= 10)
					{ echo "<strong class=\"$bg_class\"><font color=$blue_code>$item_chance%</font></strong>"; }
					else
					{ echo "<strong class=\"$bg_class\"><font color=$red_code>$item_chance%</font></strong>"; }
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
			echo "<td colspan=\"5\" class=\"drophead\"><p class=\"drophead\">SPOILS</p></td></tr><tr class=\"thead\">";
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
					if ($item_chance > 100)
					{	$item_chance = 100;	}
					if ($item_chance >= 70)
					{ echo "<strong class=\"dropmain\"><font color=$green_code>$item_chance%</font></strong>"; }
					elseif ($item_chance >= 10)
					{ echo "<strong class=\"dropmain\"><font color=$blue_code>$item_chance%</font></strong>"; }
					else
					{ echo "<strong class=\"dropmain\"><font color=$red_code>$item_chance%</font></strong>"; }
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
			map($map_array, $images_dir, 0, 1);
			echo "</td></tr></table>";
			echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			map($map_array, $images_dir, 0, 2);
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
		if ((strlen($itemname) < $minlenmobs) && ($num_in == "0"))   // We need at least three characacters, or we'll produce too many results.
		{	writewarn("Please give at least $minlenmobs characters."); }
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
				die('Could not change to $db_l2jdb database: ' . mysql_error());
			}
			$sql = "select distinct type from knightnpc where type <> 'L2Monster' and type <> 'L2Minion' union select distinct type from custom_npc where type <> 'L2Monster' and type <> 'L2Minion' order by type";
			$result = mysql_query($sql,$con);
			if ($monsdetshow)
			{
				echo "<center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr>
				<td class=\"dropmain\"><a href=\"m-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=0-10&detshow='2'\" class=\"dropmain\">1-10</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=11-20&detshow='2'\" class=\"dropmain\">11-20</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=21-30&detshow='2'\" class=\"dropmain\">21-30</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=31-40&detshow='2'\" class=\"dropmain\">31-40</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=41-50&detshow='2'\" class=\"dropmain\">41-50</a></td>
				<td rowspan=\"2\"><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"\">- $lang_sct -</option>";
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$c_var = mysql_result($result,$i,"type");
		    		echo "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=$c_var&detshow=2\">$c_var</option>";
					$i++;
  				}
				echo "</select></p></td>";
				if ($user_map_access)
				{	echo "<td rowspan=\"2\" class=\"dropmain\"><a href=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=maps&detshow=2\" class=\"dropmain\">$lang_maps</a></td>";	}
				echo "</tr><tr><td class=\"dropmain\"><a href=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=51-60&detshow=2\" class=\"dropmain\">51-60</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=61-70&detshow=2\" class=\"dropmain\">61-70</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=71-80&detshow=2\" class=\"dropmain\">71-80</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=81-90&detshow=2\" class=\"dropmain\">81-90</a></td>
				<td class=\"dropmain\"><a href=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=91over&detshow=2\" class=\"dropmain\">91+</a></td>
				</tr></table></center>";
			}
			
			if ($monsdetreq == "99999")
			{
				wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
				return 1;
			}
			
			if (($monsdetreq == "maps") && ($user_map_access))
			{
				echo "<center><h2>Map - <select onChange=\"document.location=options[selectedIndex].value;\">";
				$sql = "select distinct dungeon_name from knightdungeon order by dungeon_name";
				$result = mysql_query($sql,$con);
				while ($r_array = mysql_fetch_assoc($result))
				{
					$d_name = $r_array['dungeon_name'];
					if ($d_name == $monsdetshow)
					{	echo "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$d_name&detreq=maps\" selected>$d_name</option>";	}
					else
					{	echo "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$d_name&detreq=maps\">$d_name</option>";	}
				}
				echo "</select>";
				if (strlen($monsdetshow) > 3 )
				{
					$sql = "select COUNT(*) from knightdungeon where dungeon_name = '$monsdetshow'";
					$result = mysql_query($sql,$con);
					$rec_count = mysql_result($result,0,"COUNT(*)");
					if ($rec_count > 1)
					{
						echo " - <select onChange=\"document.location=options[selectedIndex].value;\">";
						$sql = "select sub_name from knightdungeon where dungeon_name = '$monsdetshow' order by sub_name";
						$result = mysql_query($sql,$con);
						while ($r_array = mysql_fetch_assoc($result))
						{
							$s_name = $r_array['sub_name'];
							if ($s_name == $sec_map)
							{	echo "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=mapsv\" selected>$s_name</option>";	}
							else
							{	echo "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&secmap=$s_name\">$s_name</option>";	}
						}
						echo "</select>";
					}
				}
				echo "</h2></center>";
				if (strlen($monsdetshow) < 3 )
				{	
					$sql = "select * from knightdungeon order by dungeon_name limit 1";	
				}
				else
				{	
					$sql = "select * from knightdungeon where dungeon_name = '$monsdetshow'";	
					if (strlen($sec_map) > 0)
					{	$sql = "select * from knightdungeon where dungeon_name = '$monsdetshow' and sub_name = '$sec_map'";	}
				}
				$result = mysql_query($sql,$con);
				$d_name = mysql_result($result,0,"dungeon_name");
				$d_map = mysql_result($result,0,"mapname");
				$d_xmin = mysql_result($result,0,"xmin");
				$d_xmax = mysql_result($result,0,"xmax");
				$d_ymin = mysql_result($result,0,"ymin");
				$d_ymax = mysql_result($result,0,"ymax");
				$d_zmin = mysql_result($result,0,"zmin");
				$d_zmax = mysql_result($result,0,"zmax");
				$d_mapx = mysql_result($result,0,"mapx");
				$d_mapy = mysql_result($result,0,"mapy");
				$offset_x = 0;
				$offset_y = 0;
				$x_scale = difnums($d_xmin, $d_xmax);
				$y_scale = difnums($d_ymin, $d_ymax);
				$x_scale = $x_scale / $d_mapx;
				$y_scale = $y_scale / $d_mapy;
				$mob_id_list ="--";
				$map_file = $images_dir . $svr_dir_delimit . "maps". $svr_dir_delimit . $d_map . ".jpg";
				echo "\n<center><table><tr><td><div style=\"position: relative;\"><table><tr><td><img src=\"$map_file\" alt=\"\" width=\"$d_mapx\" height=\"$d_mapy\" border=\"0\"><div>";
				
				// Go through the standard spawnlist table.
				$sql = "select * from spawnlist where locx <= $d_xmax and locx >= $d_xmin and locy <= $d_ymax and locy >= $d_ymin and locz <= $d_zmax and locz >= $d_zmin";
				$result = mysql_query($sql,$con);
				$option_str = "<select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=0&secmap=$sec_map\">-None</option>";
				while ($r_array = mysql_fetch_assoc($result))
				{
					$mob_x = $r_array['locx'];
					$mob_y = $r_array['locy'];
					$mob_id = $r_array['npc_templateid'];
					$point_dat = $images_dir. "r2.gif";
					$mob_agro = 1;
					$mob_name = "Unknown or NPC";
					$sql = "select aggro, name, level from knightnpc where idTemplate = $mob_id";
					$result2 = mysql_query($sql,$con);
					while ($r_array2 = mysql_fetch_assoc($result2))
					{
						$mob_agro = $r_array2["aggro"];
						$mob_name = $r_array2["name"];
						$mob_level = $r_array2["level"];
						if ($mob_agro > 0)
						{	
							$point_dat = $images_dir. "overg.gif";	
							$mob_name = $mob_name . "*";
						}
						$mob_name = $mob_name . " (" . $mob_level . ")";
					}
					if ($action == $mob_id)
					{	$point_dat = $images_dir. "target2.gif";	}
					$x_co = intval( difnums($mob_x,$d_xmin) / $x_scale);
					$y_co = intval( difnums($mob_y,$d_ymin) / $y_scale);
					echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\" title=\"$mob_name\">\n";
					if (strpos($mob_id_list, "-".$mob_id."-") < 1)
					{
						$mob_id_list = $mob_id_list . $mob_id . "-";
						if ($mob_id == $action)
						{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$mob_id&secmap=$sec_map\" selected>$mob_name</option>";	}
						else
						{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$mob_id&secmap=$sec_map\">$mob_name</option>";	}
					}
				}
				
				// Pull out the regular bosses
				$sql = "select * from raidboss_spawnlist where loc_x <= $d_xmax and loc_x >= $d_xmin and loc_y <= $d_ymax and loc_y >= $d_ymin and loc_z <= $d_zmax and loc_z >= $d_zmin";
				$result = mysql_query($sql,$con);
				while ($r_array = mysql_fetch_assoc($result))
				{
					$mob_x = $r_array['loc_x'];
					$mob_y = $r_array['loc_y'];
					$mob_id = $r_array['boss_id'];
					$point_dat = $images_dir. "r2.gif";
					$mob_agro = 1;
					$mob_name = "Unknown or NPC";
					$sql = "select aggro, name, level from knightnpc where idTemplate = $mob_id";
					$result2 = mysql_query($sql,$con);
					while ($r_array2 = mysql_fetch_assoc($result2))
					{
						$mob_agro = $r_array2["aggro"];
						$mob_name = $r_array2["name"];
						$mob_level = $r_array2["level"];
						if ($mob_agro > 0)
						{	
							$point_dat = $images_dir. "overg.gif";	
							$mob_name = $mob_name . "*";
						}
						$mob_name = $mob_name . " (" . $mob_level . ")";
					}
					if ($action == $mob_id)
					{	$point_dat = $images_dir. "target2.gif";	}
					$x_co = intval( difnums($mob_x,$d_xmin) / $x_scale);
					$y_co = intval( difnums($mob_y,$d_ymin) / $y_scale);
					echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\" title=\"$mob_name\">\n";
					if (strpos($mob_id_list, "-".$mob_id."-") < 1)
					{
						$mob_id_list = $mob_id_list . $mob_id . "-";
						if ($mob_id == $action)
						{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$mob_id&secmap=$sec_map\" selected>$mob_name</option>";	}
						else
						{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$mob_id&secmap=$sec_map\">$mob_name</option>";	}
					}
					// This is where things get mathematically manic. We have to pull out any minions and place them around the boss
					// We assume the minion is at a radius of 80 in game pixels and we will rotate them at 150 degrees each placement turn...
					// we are thus losing 60 degrees on each revolution assuring us of at least 9 minion placements before we start to get cramped.
					// Remembering that at this point, $mob_x abd $mob_y still contain the real-world x/y co-ordinates of the boss.
					$degrees = 0;
					$degree_step = 150;
					$radius = 80;
					$sql =  "select * from minions where boss_id = $mob_id";
					$result2 = mysql_query($sql,$con);
					while ($r_array2 = mysql_fetch_assoc($result2))
					{
						$minion_id = $r_array2['minion_id'];
						$min_min = $r_array2['amount_min'];
						$min_max = $r_array2['amount_max'] - $min_min;	// This gives us the "range" of mobs to place  eg, (7 - 5 = 2) = 2 - we've got a range of 2 mobs.
						$min_place = intval($min_max / 2) + $min_min;	// The integer of the result, divided by 2, (2/2 = 1 - integer is thus 1.) - add on the minimum of 5
																		// and we would thus be placing 6 minions.
						$point_dat = $images_dir. "r2.gif";			// Assume passive mob in case unknown.
						$sql = "select aggro, name, level from knightnpc where idTemplate = $minion_id";		// Pull all minions for this boss.
						$result3 = mysql_query($sql,$con);
						while ($r_array3 = mysql_fetch_assoc($result3))
						{
							$min_agro = $r_array3["aggro"];
							$min_name = $r_array3["name"];
							$min_level = $r_array3["level"];
							if ($min_agro > 0)								// If the mob is agro, change the pointer.
							{	
								$point_dat = $images_dir. "overg.gif";	
								$min_name = $min_name . "*";	
							}
							$min_name = $min_name . " (" . $min_level . ")";
						}
						if ($action == $minion_id)							// If this is the selected mob, change the pointer to blue cross hairs.
						{	$point_dat = $images_dir. "target2.gif";	}
						while ($min_place > 0)
						{
							$min_x = $mob_x - ($radius * cos(deg2rad($degrees)));	// Calculate the X Y co-ords based on the radius and degree.
							$min_y = $mob_y + ($radius * sin(deg2rad($degrees)));							
							
							$x_co = intval( difnums($min_x,$d_xmin) / $x_scale);	// Convert the mob co-ords to the map scale.
							$y_co = intval( difnums($min_y,$d_ymin) / $y_scale);
							echo "\n<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\" title=\"$min_name\">\n";
							
							$degrees = $degrees + $degree_step; // Knock on the degree counter.
							while ($degrees > 359)
							{	$degrees = $degrees - 360;	}
							$min_place--;
						}
						if (strpos($mob_id_list, "-".$minion_id."-") < 1)	// Check to see if the mob is already in the list of mobs on the map. If not, add it.
						{
							$mob_id_list = $mob_id_list . $minion_id . "-";
							if ($minion_id == $action)							// If this $minion_id is one of the selected mobs ($action) then we need to pre-select it.
							{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$minion_id&secmap=$sec_map\" selected>$min_name</option>";	}
							else
							{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$minion_id&secmap=$sec_map\">$min_name</option>";	}
						}
					} // and this is the end of the minion code!
				} 
				
				//Pull out the grand bosses
				$sql = "select * from grandboss_data where loc_x <= $d_xmax and loc_x >= $d_xmin and loc_y <= $d_ymax and loc_y >= $d_ymin and loc_z <= $d_zmax and loc_z >= $d_zmin";
				$result = mysql_query($sql,$con);
				while ($r_array = mysql_fetch_assoc($result))
				{
					$mob_x = $r_array['loc_x'];
					$mob_y = $r_array['loc_y'];
					$mob_id = $r_array['boss_id'];
					$point_dat = $images_dir. "r2.gif";
					$mob_agro = 1;
					$mob_name = "Unknown or NPC";
					$sql = "select aggro, name, level from knightnpc where idTemplate = $mob_id";
					$result2 = mysql_query($sql,$con);
					while ($r_array2 = mysql_fetch_assoc($result2))
					{
						$mob_agro = $r_array2["aggro"];
						$mob_name = $r_array2["name"];
						$mob_level = $r_array2["level"];
						if ($mob_agro > 0)
						{	
							$point_dat = $images_dir. "overg.gif";	
							$mob_name = $mob_name . "*";
						}
						$mob_name = $mob_name . " (" . $mob_level . ")";
					}
					if ($action == $mob_id)
					{	$point_dat = $images_dir. "target2.gif";	}
					$x_co = intval( difnums($mob_x,$d_xmin) / $x_scale);
					$y_co = intval( difnums($mob_y,$d_ymin) / $y_scale);
					echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\" title=\"$mob_name\">\n";
					if (strpos($mob_id_list, "-".$mob_id."-") < 1)
					{
						$mob_id_list = $mob_id_list . $mob_id . "-";
						if ($mob_id == $action)
						{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$mob_id&secmap=$sec_map\" selected>$mob_name</option>";	}
						else
						{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$mob_id&secmap=$sec_map\">$mob_name</option>";	}
					}
					// This is where things get mathematically manic. We have to pull out any minions and place them around the boss
					// We assume the minion is at a radius of 80 in game pixels and we will rotate them at 150 degrees each placement turn...
					// we are thus losing 60 degrees on each revolution assuring us of at least 9 minion placements before we start to get cramped.
					// Remembering that at this point, $mob_x abd $mob_y still contain the real-world x/y co-ordinates of the boss.
					$degrees = 0;
					$degree_step = 150;
					$radius = 80;
					$sql =  "select * from minions where boss_id = $mob_id";
					$result2 = mysql_query($sql,$con);
					while ($r_array2 = mysql_fetch_assoc($result2))
					{
						$minion_id = $r_array2['minion_id'];
						$min_min = $r_array2['amount_min'];
						$min_max = $r_array2['amount_max'] - $min_min;	// This gives us the "range" of mobs to place  eg, (7 - 5 = 2) = 2 - we've got a range of 2 mobs.
						$min_place = intval($min_max / 2) + $min_min;	// The integer of the result, divided by 2, (2/2 = 1 - integer is thus 1.) - add on the minimum of 5
																		// and we would thus be placing 6 minions.
						$point_dat = $images_dir. "r2.gif";			// Assume passive mob in case unknown.
						$sql = "select aggro, name, level from knightnpc where idTemplate = $minion_id";		// Pull all minions for this boss.
						$result3 = mysql_query($sql,$con);
						while ($r_array3 = mysql_fetch_assoc($result3))
						{
							$min_agro = $r_array3["aggro"];
							$min_name = $r_array3["name"];
							$min_level = $r_array3["level"];
							if ($min_agro > 0)								// If the mob is agro, change the pointer.
							{	
								$point_dat = $images_dir. "overg.gif";	
								$min_name = $min_name . "*";
							}
							$min_name = $min_name . " (" . $min_level . ")";
						}
						if ($action == $minion_id)							// If this is the selected mob, change the pointer to blue cross hairs.
						{	$point_dat = $images_dir. "target2.gif";	}
						while ($min_place > 0)
						{
							$min_x = $mob_x - ($radius * cos(deg2rad($degrees)));	// Calculate the X Y co-ords based on the radius and degree.
							$min_y = $mob_y + ($radius * sin(deg2rad($degrees)));							
							
							$x_co = intval( difnums($min_x,$d_xmin) / $x_scale);	// Convert the mob co-ords to the map scale.
							$y_co = intval( difnums($min_y,$d_ymin) / $y_scale);
							echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\" title=\"$min_name\">\n";
							
							$degrees = $degrees + $degree_step; // Knock on the degree counter.
							while ($degrees > 359)
							{	$degrees = $degrees - 360;	}
							$min_place--;
						}
						if (strpos($mob_id_list, "-".$minion_id."-") < 1)	// Check to see if the mob is already in the list of mobs on the map. If not, add it.
						{
							$mob_id_list = $mob_id_list . $minion_id . "-";
							if ($minion_id == $action)							// If this $minion_id is one of the selected mobs ($action) then we need to pre-select it.
							{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$minion_id&secmap=$sec_map\" selected>$min_name</option>";	}
							else
							{	$option_str = $option_str . "<option value=\"m-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&maps=$c_var&detshow=$monsdetshow&detreq=maps&action=$minion_id&secmap=$sec_map\">$min_name</option>";	}
						}
					} // and this is the end of the minion code!
				} 
				echo "</div></td></tr></table></div></td></tr></table></center>\n";
				$option_str = $option_str . "</select>";
				echo "<center><h2>Mob Highlight - $option_str</h2></center>";
			}
			else
			{
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
				elseif ($monsdetreq == "81-90")
				{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level < 91 and level > 80 order by $monstersort"; }
				elseif ($monsdetreq == "91over")
				{	$sql = "select id, name, type, level, hp, mp, exp, sp, attackrange, aggro from knightnpc where (type = 'L2Monster' or type = 'L2Minion' or type = 'L2RaidBoss') and level > 90 order by $monstersort"; }
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
					writeerror("Sorry, no monsters matching $itemname found!");
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
						$mob_hp = round(mysql_result($result,$i,"hp"));
						$mob_mp = round(mysql_result($result,$i,"mp"));
						$mob_exp = mysql_result($result,$i,"exp");
						$mob_sp = mysql_result($result,$i,"sp");
						$mob_atkrange = mysql_result($result,$i,"attackrange");
						$mob_aggro = mysql_result($result,$i,"aggro");
						$mob_spawn = 0;
						$mob_days = 0;
						$mob_dayt = 0;
						$mob_nights = 0;
						$mob_nightt = 0;
						$mob_normals = 0;
						$mob_normalt = 0;
						$mob_count = 0;
						$mob_spwn = mobcount($mob_id,$db_location,$db_user,$db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb);
						if ($mob_spwn[0] > 0)
						{
							$mob_spawn = $mob_spwn[1];
							$mob_count = $mob_spwn[0];
							$mob_days = $mob_spwn[2];
							$mob_dayt = $mob_spwn[3];
							$mob_nights = $mob_spwn[4];
							$mob_nightt = $mob_spwn[5];
							$mob_normals = $mob_spwn[6];
							$mob_normalt = $mob_spwn[7];
						}
						// For non admin users, show mobs who have got spawn points.
						if ($user_access_lvl < $sec_inc_gmlevel)
						{
							if (($mob_spawn > 0) || ($mob_type == 'L2RaidBoss') || ($mob_type == 'L2Boss') || ($mob_type == 'L2Minion'))
							{
								echo "<tr>";
								if ($user_access_lvl >= $sec_inc_gmlevel)
								{ echo "<td class=\"dropmain\">$mob_id</td>"; }
								echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"m-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&monsterid=$mob_id\" class=\"dropmain\">$mob_name</a></p></td><td class=\"dropmain\"><p class=\"dropmain\">";
								if ($mob_aggro > 0) { echo "<font color=#ff0000>"; }
								echo "$mob_type";
								if ($mob_aggro > 0) { echo "</font>"; }
								echo "</p></td><td class=\"dropmain\"><p class=\"center\">$mob_level</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_hp&nbsp;/&nbsp;$mob_mp</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_exp</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_sp</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_atkrange</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">";
								echo "<font color=$green_code>$mob_days/$mob_dayt</font>&nbsp;-&nbsp;<font color=$red_code>$mob_nights/$mob_nightt</font>&nbsp;-&nbsp;$mob_normals/$mob_normalt";
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
								echo "</p></td><td class=\"dropmain\"><p class=\"center\">$mob_level</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_hp&nbsp;/&nbsp;$mob_mp</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_exp</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_sp</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\">$mob_atkrange</p></td>";
								echo "<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">";
								echo "<font color=$green_code>$mob_days/$mob_dayt</font>&nbsp;-&nbsp;<font color=$red_code>$mob_nights/$mob_nightt</font>&nbsp;-&nbsp;$mob_normals/$mob_normalt";
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

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
