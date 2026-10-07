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
$town_id = input_check($_REQUEST['town_id'],0);
$action = input_check($_REQUEST['action'],0);
$mapview = input_check($_REQUEST['mapview'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

// Connect to DB
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
$sql = "USE $db_l2jdb";
if (!mysql_query($sql,$con))
{
	die('Could not change to L2J database: ' . mysql_error());
}

$low_res = 0;
$result = mysql_query("select access_level from $dblog_l2jdb.knightdrop where name = '$username'",$con2);
while ($r_array = mysql_fetch_assoc($result))
{
	$a_level = $r_array['access_level'];
	if (($a_level >= $sec_inc_gmlevel) && ($whosonlinegmlow > 0))
	{	$low_res = 1;	}
}
$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $delay_whosonline, "w-online.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&mapview=$mapview", $low_res, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($bot_scan_ban)
	{	bot_scan($username, $token, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps, $telnet_host, $telnet_port, $telnet_timeout, $telnet_password);	}
	
	// Query for user name
	if (!$result = mysql_query("select name, x, y from knightloc where name = '$town_id'",$con))
		{
		die('Could not retrieve from knightdrop database: ' . mysql_error());
		}
	if (($user_access_lvl >= $sec_inc_gmlevel) && ($action == "refresh"))
	{
		$result = mysql_query("update characters set online = 0",$con);
	}
	if (!$result = mysql_query("select account_name, charId, char_name, sex, accesslevel, race, level, punish_level, punish_timer, onlinetime from characters where online = '1' order by level",$con))
	{
		die('Could not retrieve from database: ' . mysql_error());
	}
	// If return array empty, then username not found.
	$row = mysql_fetch_array($result);
	$count_accs = mysql_num_rows($result);	
	echo "<center><h2 class=\"dropmain\">$lang_whosonline : $count_accs</h2></center>";
	$allow_map_change = 1;
	if (($show_map == 0) && ($user_access_lvl < $sec_inc_gmlevel))
	{	$mapview = 0;	
		$allow_map_change = 0;	}
	if (($show_map == 1) && ($username == 'guest'))
	{	$mapview = 0;	
		$allow_map_change = 0;	}
	if ($mapview)
	{
		if ($allow_map_change)
		{	echo "<center><table border=\"2\" cellspacing=\"5\" cellpadding=\"5\" class=\"dropmain\"><tr><td class=\"dropmain\"><a href=\"w-online.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&mapview=0\" class=\"dropmain\">Show Users</a></td></tr></table></center>";	}
		$result = mysql_query("select race,x,y,char_name,level,maxHp,curHp,maxCp,curCp,maxMp,curMp,karma,pvpkills,pkkills,title from characters where online = '1'",$con);
		$count = mysql_num_rows($result);
		$i=0;
		while ($i < $count)
		{
			$x = mysql_result($result,$i,"x");
			$y = mysql_result($result,$i,"y");
			$race = mysql_result($result,$i,"race");
			$char_name = mysql_result($result,$i,"char_name");
			$level = mysql_result($result,$i,"level");
			$maxHp = mysql_result($result,$i,"maxHp");
			$curHp = mysql_result($result,$i,"curHp");
			$maxCp = mysql_result($result,$i,"maxCp");
			$curCp = mysql_result($result,$i,"curCp");
			$maxMp = mysql_result($result,$i,"maxMp");
			$curMp = mysql_result($result,$i,"curMp");
			$karma = mysql_result($result,$i,"karma");
			$pvpkills = mysql_result($result,$i,"pvpkills");
			$pkkills = mysql_result($result,$i,"pkkills");
			$title = mysql_result($result,$i,"title");
			
			$c_race_n = "Unkonwn";
				if ($race == 0)
				{	$c_race_n = "$lang_human";	}
				elseif ($race == 1)
				{	$c_race_n = "$lang_elf";	}
				elseif ($race == 2)
				{	$c_race_n = "$lang_delf";	}
				elseif ($race == 3)
				{	$c_race_n = "$lang_orc";	}
				elseif ($race == 4)
				{	$c_race_n = "$lang_dwarf";	}
				elseif ($race == 5)
				{	$c_race_n = "$lang_kamael";	}
			
			if ($race > 2)
			{	$race = $race + 6;	}
						
			if (!$map_array)
			{
				$map_array = array(array($x, $y, $race, 
				$char_name, $level, $maxHp, $curHp, $maxCp,
				$curCp, $maxMp, $curMp, $karma, $pvpkills,
				$pkkills, $title, $c_race_n, $user_access_lvl,
				$sec_inc_gmlevel));
			}
			else
			{
				array_push($map_array, array($x, $y, $race, 
				$char_name, $level, $maxHp, $curHp, $maxCp,
				$curCp, $maxMp, $curMp, $karma, $pvpkills,
				$pkkills, $title, $c_race_n, $user_access_lvl,
				$sec_inc_gmlevel));
			}
			$i++;
		}
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
		echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_human</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "underg.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_elf</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "overg.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_delf</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "wfree.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_orc</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r1.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_dwarf</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r2.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_kamael</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r4.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
		echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_unknown</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r3.gif\"></td></tr>";
		echo "</table></center>\n";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
		map($map_array, $images_dir, $map_nudge, 1);
		map($map_array, $images_dir, $map_nudge, 2);
		echo "</td></tr></table></center>";
	}
	else
	{
		if ($allow_map_change)
		{	echo "<center><table border=\"2\" cellspacing=\"5\" cellpadding=\"5\" class=\"dropmain\"><tr><td class=\"dropmain\"><a href=\"w-online.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&mapview=1\" class=\"dropmain\">Show Map</a></td></tr></table></center>";	}
		if ($count_accs > 0)
		{
			$count = 0;
			$trip_count = 0;
			echo "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
			while ($count < $count_accs)
			{
				$char_name = mysql_result($result,$count,"char_name"); 
				$char_acc_name = mysql_result($result,$count,"account_name"); 
				$char_access = mysql_result($result,$count,"accesslevel");  
				$char_num = mysql_result($result,$count,"charId");  
				$char_sex = mysql_result($result,$count,"sex");
				$char_race = mysql_result($result,$count,"race");  
				$char_level = mysql_result($result,$count,"level");
				$char_injail = mysql_result($result,$count,"punish_level");
				$char_onlinetime = mysql_result($result,$count,"onlinetime");
				$char_jailtime = (mysql_result($result,$count,"punish_timer") / 1000);
				$char_jailtime_m = intval(($char_jailtime / 60));
				$char_jailtime_s = intval(($char_jailtime - ($char_jailtime_m * 60)));
				if ($char_jailtime_s < 1)
				{	$char_jailtime_s = "00";	}

				if ($user_access_lvl >= $sec_inc_gmlevel)
				{  
					if (!$result2 = mysql_query("select level from characters where account_name = '$char_acc_name'",$con))
					{
						die('Could not retrieve from database: ' . mysql_error());
					}
					$count_accs2 = mysql_num_rows($result2);
					$total_level = 0;
					$i = 0;
					while ($i < $count_accs2)
					{
						$a_level = mysql_result($result2,$i,"level");  
						$total_level = $total_level + $a_level;
						$i++;
					}
				}
				$c_race_n = "Unkonwn";
				if ($char_race == 0)
				{	$c_race_n = "$lang_human";	}
				elseif ($char_race == 1)
				{	$c_race_n = "$lang_elf";	}
				elseif ($char_race == 2)
				{	$c_race_n = "$lang_delf";	}
				elseif ($char_race == 3)
				{	$c_race_n = "$lang_orc";	}
				elseif ($char_race == 4)
				{	$c_race_n = "$lang_dwarf";	}
				elseif ($char_race == 5)
				{	$c_race_n = "$lang_kamael";	}
				if ($trip_count == 0)
				{	echo "<tr>";	}

				$colour_code = "<font color=$blue_code>";
				if (($gm_play_show < 1) || ($user_access_lvl >= $sec_inc_gmlevel))
				{
					if ($char_access > 0)
					{	$colour_code = "<font color=$green_code>";	}
				}
			
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{  
					if (!$result2 = mysql_query("select warnlevel, boxingok from $dblog_l2jdb.knightdrop where name = '$char_acc_name'",$con2))
					{	die('Could not retrieve from database: ' . mysql_error());	}
					$char_count = mysql_num_rows($result2);
					if (!$char_count)
					{
						die('Character not in knightdrop table.  Has "Import Accounts" in "System Tools" been run?');
					}
					$boxingok = mysql_result($result2,0,"boxingok");
					$warnlevel = mysql_result($result2,0,"warnlevel");

					$sql = "select lastIP, accessLevel from $dblog_l2jdb.accounts where login = '$char_acc_name'";
					if (!$result2 = mysql_query($sql,$con2))
					{
						die('Could not retrieve IP database: ' . mysql_error());
					}
					$lastip = mysql_result($result2,0,"lastIP");
					$ip_dup_count = 0;
					$sql = "select ip_addr from $dblog_l2jdb.knightipok where ip_addr = '$lastip'";
					$result3 = mysql_query($sql,$con2);
					if ($result3)
					{	$ip_dup_count = mysql_num_rows($result3);	}
					if (!$ip_dup_count)
					{
						if (($check_boxing) && ($boxingok == 0))
						{
							$account_level = mysql_result($result2,0,"accessLevel");
							$sql = "select login from $dblog_l2jdb.accounts where lastIP = '$lastip'";
							$result2 = mysql_query($sql,$con2);
							$sql = "select COUNT(*) from characters where online = 1 and account_name in (";
							$count3 = mysql_num_rows($result2);
							$i3 = 0;
							while ($i3 < $count3)
							{
								$gm_id = mysql_result($result2,$i3,"login");	
								$sql = $sql . "'" . $gm_id . "'";
								$i3++;
								if ($i3 < $count3)
								{	$sql = $sql . ", ";	}
							}
							if ($count3 == 0)
							{	$sql = $sql . "''";	}
							$sql = $sql . ")";
							$result2 = mysql_query($sql,$con);
							if (!$result2)
							{
								die('Could not extract boxing figures: ' . mysql_error());
							}

							$boxingcount = mysql_result($result2,0,"COUNT(*)");
							if (($boxingcount > 1) && ($account_level < $sec_inc_gmlevel))
							{	$colour_code = "<font color=$red_code>";	}
						}
					}
				}
				if (($gm_play_show < 2) || ($char_access < $sec_inc_gmlevel) || ($user_access_lvl >= $sec_inc_gmlevel))
				{
					echo "<td width=\"20%\" class=\"dropmain\"><p class=\"dropmainon\">";
					if ($user_access_lvl >= $sec_inc_gmlevel)
					{	echo "<a href=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$lastip&account=ipcheck\"><img src=\"" . $images_dir . "ip.gif\" border=\"0\" align=\"left\"></a>";	}
					if ($user_access_lvl >= $sec_inc_gmlevel)
					{	echo "<a href=\"a-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&account=$char_acc_name\" class=\"dropmain\"><img src=\"" . $images_dir . "ac.gif\" border=\"0\" align=\"right\"></a>";	}
					echo "<a href=\"c-search.php?$itemname=$char_name&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$char_num\">" . $colour_code . "<strong class=\"dropmainst\">$char_name</strong>";
					if (($user_access_lvl >= $sec_inc_gmlevel) && ($show_char_time))
						{	$onlinetime = onlinetime($char_onlinetime);
							echo "&nbsp;<small><font color=\"$white_code\">($onlinetime)</font></small>";	}
					if ($warnlevel > 0)
					{	echo "<strong><font color=$red_code> ($warnlevel)</font></strong>";	}
					if (($user_access_lvl >= $sec_inc_gmlevel) || ( $game_paranoia < 2))
					{
						echo "<br>$char_level&nbsp;-&nbsp;";
						if ($char_sex)
						{	echo "<img src=\"" . $images_dir . "female2.gif\" width=\"6\" height=\"8\" border=\"0\">";	}
						else
						{	echo "<img src=\"" . $images_dir . "male2.gif\" width=\"6\" height=\"8\" border=\"0\">";	}
						echo "&nbsp;$c_race_n";	
					}
					else
					{	echo "<br>$c_race_n";	}
					if ($user_access_lvl >= $sec_inc_gmlevel)
					{	
						echo "&nbsp;($total_level)<br>$char_acc_name";
						if (($user_access_lvl >= $sec_inc_gmlevel) && ($show_char_time))
						{	$result4 = mysql_query("select sum(onlinetime) from $db_l2jdb.characters where account_name = '$char_acc_name'",$con2);
							$acc_onlinetime = mysql_result($result4,0,"sum(onlinetime)");
							$onlinetime = onlinetime($acc_onlinetime);
							echo "&nbsp;<small><font color=\"$white_code\">($onlinetime)</font></small>";	}
						if ($result_note = mysql_query("select count(*) from $knight_db.accnotes where charname = '$char_acc_name'",$con2))
						{
							$count_notes = mysql_result($result_note,0,"count(*)");
							if ($count_notes > 0)
							{	echo "&nbsp;<a href=\"acc-notes.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$char_acc_name\"><font color=$yellow_code>[-$count_notes-]</font></a>";	}
						}
						if ($char_injail)
						{	echo "<br><font color=$red_code>[JAILED&nbsp;-&nbsp;$char_jailtime_m:$char_jailtime_s]</font>";	}
					}
					$on_line_time = "";
					if (($user_access_lvl >= $sec_inc_gmlevel) && ($show_char_time) && ($show_detail_c_time))
					{
						$result4 = mysql_query("select sum(onlinetime) from $db_l2jdb.characters where account_name in (select login from $dblog_l2jdb.accounts where lastip = '$lastip')",$con2);
						$acc_onlinetime = mysql_result($result4,0,"sum(onlinetime)");
						$on_line_time = "<small><font color=\"$white_code\">(" . onlinetime($acc_onlinetime) . ")</font></small>";
					}
					if (($colour_code == "<font color=$red_code>") || (($user_access_lvl >= $sec_inc_gmlevel) && ($show_char_time) && ($show_detail_c_time)))
					{	echo "<br>$lastip&nbsp;$on_line_time";	}
					echo "</font>";
					if (($user_access_lvl >= $sec_inc_gmlevel) && ($display_country))
					{
						$ip_num = iptonum($lastip);
						$sql = "SELECT ci FROM $dblog_l2jdb.ip WHERE $ip_num BETWEEN start AND end";
						$ip_result = mysql_query($sql,$con2);
						if ($ip_result)
						{	$ip_result_count = mysql_num_rows($ip_result);	}
						else
						{	$ip_result_count = 0;	}
						if ($ip_result_count)
						{
							$ip_c_num = mysql_result($ip_result,0,"ci");
							$sql = "SELECT cn FROM $dblog_l2jdb.cc WHERE ci = '$ip_c_num'";
							$ip_result = mysql_query($sql,$con2);
							$ip_country = mysql_result($ip_result,0,"cn");				
							echo "<br><font color=$green_code>$ip_country</font>";
						}
					}
					echo "</p></a></td>";
					$trip_count++;
					if ($trip_count == 5)
					{	echo "</tr>\n";	
						$trip_count = 0;}
				}
				$count++;
			}
			if ($trip_count > 0)
			{
				while ($trip_count < 5)
				{
					echo "<td width=\"20%\" class=\"dropmain\">&nbsp;</td>\n";
					$trip_count++;
				}
				echo "</tr>";		
			}
			echo "</table>";
		}
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{
			echo "<br><center><form method=\"post\" action=\"w-online.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=refresh\"><input value=\"Reset Online\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center>";
		}
	}
}
else
{	wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $delay_whosonline, "w-online.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id", $low_res);	}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>