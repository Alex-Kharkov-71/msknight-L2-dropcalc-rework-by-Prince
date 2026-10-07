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

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$lastlines = input_check($_REQUEST['lastlines'],0);
$action = input_check($_REQUEST['action'],0);
$zone = input_check($_REQUEST['zone'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

if ($lastlines)
{
	$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $delay_whosonline, "playerchat.php?=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1&character=$character#bottomline", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
}
else
{
	$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
}

if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		writewarn("Sorry.  You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
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
		if (!$con)
		{
			echo "Could Not Connect";
			die('Could not connect: ' . mysql_error());
		}		
		if (!mysql_select_db("$db_l2jdb",$con))
		{
			die('Could not change to L2J database: ' . mysql_error());
		}
		
		$is_statsrunning = 0;
		$result = mysql_query("show tables",$con);
		while ($res = @mysql_fetch_array($result))
		{
			$table_name = $res[0];
			if ($table_name == "knightstats")
			{	$is_statsrunning = 1;	}
		}

		echo "<p class=\"dropmain\">&nbsp;</p><center><table class=\"dropmain\"><tr>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Server Status \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"status\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Server Performance \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"performance\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" World \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"world\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Map (online) \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mapo\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Map (all) \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mapa\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" PK players \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"pkers\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Zones \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"zones\"></form></td></tr><tr>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Mob Spots \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"mobs\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Raid Boss Spawns \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"raidmobs\"></form></td>\n";
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Boss Spawns \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"bossmobs\"></form></td>\n";
		if ($is_statsrunning)
		{	echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Online Stats \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"playerstats\"></form></td>\n";	}
		else
		{	echo "<td class=\"noborder\">&nbsp;</td>\n";	}
		echo "<td class=\"noborder\"><center><form method=\"post\" action=\"statistics.php\"><input value=\" Serv Banner \" type=\"submit\" class=\"bigbut2\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"serverbanner\"></form></td>\n";
		echo "<td class=\"noborder\">&nbsp;</td>\n";
		echo "<td class=\"noborder\">&nbsp;</td>\n";
		echo "</tr></table></center><p class=\"dropmain\">&nbsp;</p>\n";
		
		if ($action=="playerstats")
		{
			$result2 = mysql_query("select maxplayers from knightstats limit 1",$con);
			while ($r_array = mysql_fetch_assoc($result2))
			{
				$count = $r_array['maxplayers'];
				$result = mysql_query("select maxplayers from knightstats where date = '2147483647' limit 1",$con);
				while ($r_array = mysql_fetch_assoc($result))
				{
					$max_p = $r_array['maxplayers'];
					echo "<h2 class=\"dropmain\">Maximum players recorded online - $max_p</h2>";
				}
				$minutes = intval(time() / 60);
				$minutes = $minutes + ($auto_prune * 60);
				$hours = intval($minutes / 60);
				$minutes = $minutes - ($hours * 60);
				$date = intval($hours / 24);
				$today_date = time();
				$runloop = 1;
				$result = mysql_query("select period from knightstats where date < '2147483000' order by period DESC limit 1",$con);
				$max_period = mysql_result($result,0,"period");
				$colspans = $max_period + 1;
				$colspand = $colspans * 24;
				$g_width = intval(30 / $colspans);
				if ($g_width < 1)
				{	$g_width = 1;	}
				
				$result = mysql_query("select maxplayers from knightstats where date < '2147483000' order by maxplayers DESC limit 1",$con);
				$max_players = mysql_result($result,0,"maxplayers");
				if ($max_players < 1)
				{	$maxplayers = 1;	}
				$run_count = 0;
				while ($runloop)
				{
					$result = mysql_query("select hour, period, count from knightstats where date = '$date' order by hour, period",$con);
					$runloop = mysql_num_rows($result);
					if ($runloop)
					{
						$daily_total = 0;
						$daily_reports = 0;
						$date_out = date('l dS \of F Y', $today_date);
						echo "<center><table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" class=\"dropmain\"><tr><td colspan=\"2\"></td><td colspan=\"$colspand\"><strong><p class=\"dropmain\">$date_out</p></strong></td></tr><tr><td valign=\"top\" class=\"dropmain\"><p class=\"dropmain\">$max_players</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"250\"></td>";
						$res = @mysql_fetch_array($result);
						$res_h = $res[0];
						$res_p = $res[1];
						$res_c = $res[2];
						$i_hour = 0;
						while ($i_hour < 24)
						{
							$i_period = 0;
							while ($i_period <= $max_period)
							{
								$total = 0;
								if (($i_period == $res_p) && ($i_hour == $res_h))
								{
									$total = $res_c;
									$res = @mysql_fetch_array($result);
									$res_h = $res[0];
									$res_p = $res[1];
									$res_c = $res[2];
									$daily_reports++;
									$daily_total = $daily_total + $total;
								}
								if ($total > 0)
								{
									$graph_colour = $red_code;
									if (($i_hour / 2) == (intval($i_hour / 2)))
									{	$graph_colour = $green_code;	}
									$height = intval((250 / $max_players) * $total);
									echo "<td id=\"cssbody=[hntbdystat] cssheader=[hnthdrstat] body=[$total]\" valign=\"bottom\" class=\"dropmain\">
											<table width=\"$g_width\" height=\"$height\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"blanktab\"><tr><td bgcolor=\"$graph_colour\"><img src=\"" . $images_dir . "blank.gif\" width=\"$g_width\" height=\"$height\"></td></tr></table>
										</td>";
								}
								else
								{	echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"$g_width\" height=\"1\"></td>";	}
								$i_period++;
							}
							$i_hour++;
						}
						echo "</tr><tr><td class=\"dropmain\"><p class=\"dropmain\">Hr-</p></td><td><img src=\"" . $images_dir . "blank.gif\" width=\"1\" height=\"1\"></td>";
						$i_hour = 0;
						while ($i_hour < 24)
						{
							echo "<td colspan=\"$colspans\" class=\"dropmain\"><p class=\"dropmain\">$i_hour</p></td>";
							$i_hour++;
						}
						if ($daily_reports > 0)
						{	$daily_total = intval($daily_total / $daily_reports);	}
						else
						{	$daily_total = 0;	}
						echo "</tr><tr><td colspan=\"2\"></td><td colspan=\"$colspand\"><strong><p class=\"dropmain\">Daily Average - $daily_total</p></strong></td></tr></table></center><p>&nbsp;</p>";
					}
					$date--;
					$today_date = $today_date-86400;
					$run_count++;
					if ($run_count == 10)
					{	$runloop = 0;	}
				}
			}
		}
		elseif ($action=="performance")
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'performance';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fgets($usetelnet, 128) ;
				fgets($usetelnet, 128) ;
				fgets($usetelnet, 128) ;
				fgets($usetelnet, 128) ;
				echo "<center><table class=\"noborder\" cellpadding=\"5\"><tr><td class=\"dropmain\"><pre class=\"dropstat\">";
				while (!feof($usetelnet))
				{
					$line = fgets($usetelnet, 128);
					echo "$line";
				}
				fclose($usetelnet);
				echo "</pre></td></tr></table></center>";
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}
		elseif ($action=="pkers")
		{
			echo "<center><table class=\"dropmain\" cellpadding=\"5\" cellspacing=\"0\">";
			echo "<tr><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Character</strong></p></td><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Account</strong></p></td><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">PK</strong></p></td><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">PvP</strong></p></td><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Karma</strong></p></td></tr>";
			$sql = "select account_name, char_name, charId, karma, pvpkills, pkkills from characters where pkkills > 0 or pvpkills > 0 order by pkkills desc, pvpkills desc";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			$i=0;
			while ($i < $count)
				{
					$acc_name = mysql_result($result,$i,"account_name");
					$char_name = mysql_result($result,$i,"char_name");
					$char_id= mysql_result($result,$i,"charId");
					$karma = mysql_result($result,$i,"karma");
					$pvp = mysql_result($result,$i,"pvpkills");
					$pk = mysql_result($result,$i,"pkkills");
					echo "<tr><td class=\"dropmain\"><p class=\"left\"><a href=\"c-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$char_id\" class=\"dropmain\">$char_name</a></p></td><td class=\"dropmain\"><p class=\"left\"><a href=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$acc_name\" class=\"dropmain\">$acc_name</a></p></td><td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\"><font color=$red_code>$pk</font></strong></p></td><td class=\"dropmain\"><p class=\"left\"><font color=$green_code>$pvp</font></p></td><td class=\"dropmain\"><p class=\"left\"><font color=$red_code>$karma</font></p></td></tr>";
					$i++;
				}
			echo "</table></center>";
		}
		elseif ($action=="status")
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'status';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fgets($usetelnet, 128) ;
				fgets($usetelnet, 128) ;
				fgets($usetelnet, 128) ;
				fgets($usetelnet, 128) ;
				echo "<center><table class=\"noborder\" cellpadding=\"5\"><tr><td class=\"dropmain\"><pre class=\"dropstat\">";
				while (!feof($usetelnet))
				{
					$line = fgets($usetelnet, 128);
					echo "$line";
				}
				fclose($usetelnet);
				echo "</pre></td></tr></table></center>";
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}
		elseif ($action=="serverbanner")
		{
			echo "<p class=\"dropmainwhite\">The banner system requires that the GD system is enabled in your PHP installation, and that it can handle JPG files, otherwise the images won't show..</p>";
			if ($gdsrvon == 0)
			{	echo "<h2 class=\"dropmainwhite\">WARNING - SERVER GD SET TO OFF.<br>Images won't show.</h2>";	}
			$i = 0;
			while ($i < $max_servers)
			{
				$a = $_SERVER["HTTP_HOST"];
				$b = $_SERVER["PHP_SELF"];
				if (substr($a,strlen($a)-1,1) == "/")
				{	$a = substr($a,0,strlen($a)-1);	}
				if (substr($a,0,1) == "/")
				{	$a = substr($a,1,strlen($a)-1);	}
				if (substr($b,0,1) <> "/")
				{	$b = '/'.$b;	}
				$b = preg_replace('/statistics.php/','',$b);
				if (substr($b,strlen($b)-1,1) <> "/")
				{	$b = $b.'/';	}
				$t_name =  "http://" . $a . $b;
				if (substr($t_name,strlen($t_name)-1,1) <> "/")
				{	$t_name = $t_name . '/';	}
				$t_name = $t_name . "gd.php?s=" . $i; 
				echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr><td class=\"dropmain\"><p class=\"center\">&lt;img src=\"$t_name\" alt=\"\" /&gt;<br>
				[img]$t_name";
				echo "[/img]</p></td></tr></table></center>";
				echo "<center><img src=\"$t_name\"></center><p>&nbsp;</p>";
				$i++;
			}
		}
		elseif (($action=="mapo") || ($action=="mapa"))
		{
			if ($action=="mapo")
			{	
				$sql="select race, x, y from characters where online = '1'";	
				echo "<h2 class=\"dropmain\">On Line Players</h2>";
			}
			else
			{	
				$sql="select race, x, y from characters";	
				echo "<h2 class=\"dropmain\">All Players</h2>";
			}
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			$i=0;

			while ($i < $count)
			{
				$x = mysql_result($result,$i,"x");
				$y = mysql_result($result,$i,"y");
				$race = mysql_result($result,$i,"race");
				if ($race > 2)
				{	$race = $race + 6;	}
				if (!$map_array)
				{
					$map_array = array(array($x, $y, $race));
				}
				else
				{
					array_push($map_array, array($x, $y, $race));
				}
				$i++;
			} 
			echo "<center><table border=\"10\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\">";
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_human</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "underg.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_elf</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "overg.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_delf</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "wfree.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_orc</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r1.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_dwarf</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r2.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_kamael</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r4.gif\"></td><td class=\"dropmain\">&nbsp;</td>";
			echo "<td class=\"dropmain\"><p class=\"dropmain\">$lang_unknown</p></td><td class=\"dropmain\"><img src=\"" . $images_dir . "r3.gif\"></td></tr>";
			echo "</table></center>\n";
			echo "<center><table border=\"30\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			map($map_array, $images_dir, $map_nudge,1);
			echo "</td></tr><tr><td>";
			map($map_array, $images_dir, $map_nudge,2);
			echo "</td></tr></table></center>";
		}
		elseif ($action=="mobs")
		{
			$map_right = 229388;
			$map_left = -165886;
			$map_top = -262143;
			$map_bottom = 261000;
						
			$graphic_width = 750;
			$graphic_height = 1084;
				
			$offset_x = 0;
			$offset_y = 0;
				
			$map_file = $images_dir. "aden.jpg";
			
			if ($map_right > $map_left)
			{
				$x_scale = difnums($map_left, $map_right);
				$base_x = $map_left;
			}
			else
			{
				$x_scale = difnums($map_right, $map_left);
				$base_x = $map_right;
			}
			
			if ($map_bottom > $map_top)
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_top;
			}
			else
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_bottom;
			}
			$x_scale = $x_scale / $graphic_width;
			$y_scale = $y_scale / $graphic_height;
			$image_tempblank = $images_dir . "blank.gif";
			$image_temp0 = $images_dir . "temp0.gif";
			$image_temp1 = $images_dir . "temp1.gif";
			$image_temp2 = $images_dir . "temp2.gif";
			$image_temp3 = $images_dir . "temp3.gif";
			$image_temp4 = $images_dir . "temp4.gif";
			$image_temp5 = $images_dir . "temp5.gif";
			$image_temp6 = $images_dir . "temp6.gif";
			$image_temp7 = $images_dir . "temp7.gif";
			$image_temp8 = $images_dir . "temp8.gif";
			$image_temp9 = $images_dir . "temp9.gif";
			echo "<h2 class=\"dropmain\">Mob Hotspots</h2>";
			echo "<center><table class=\"blanktab\"><tr>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[0-9]</p></td><td class=\"noborderback\"><img src=\"$image_temp0\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[20-29]</p></td><td class=\"noborderback\"><img src=\"$image_temp2\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[40-49]</p></td><td class=\"noborderback\"><img src=\"$image_temp4\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[60-69]</p></td><td class=\"noborderback\"><img src=\"$image_temp6\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[80-89]</p></td><td class=\"noborderback\"><img src=\"$image_temp8\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "</tr><tr>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[10-19]</p></td><td class=\"noborderback\"><img src=\"$image_temp1\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[30-39]</p></td><td class=\"noborderback\"><img src=\"$image_temp3\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[50-59]</p></td><td class=\"noborderback\"><img src=\"$image_temp5\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[70-79]</p></td><td class=\"noborderback\"><img src=\"$image_temp7\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[90+]</p></td><td class=\"noborderback\"><img src=\"$image_temp9\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "</tr></table></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";

			echo "\n<div style=\"position: relative;\"><img src=\"$map_file\" alt=\"\" width=\"$graphic_width\" height=\"$graphic_height\" border=\"0\"><div>";
			$sql = "select level, `id` from npc where type = 'L2Monster'union select level, `id` from custom_npc where type = 'L2Monster'";
			$result4 = mysql_query($sql,$con);
			$count_r4 = mysql_num_rows($result4);
			$i4 = 0;
			while ($i4 < $count_r4)
			{
				$template_id = mysql_result($result4,$i4,"id");
				$map_tag = 12;
				$mob_level = mysql_result($result4,$i4,"level");				
				$map_tag = intval($mob_level / 10);
				$point_dat = $images_dir. "temp" . $map_tag . ".gif";
				$sql = "select locx, locy, loc_id, count, npc_templateid from spawnlist where npc_templateid = $template_id";
				$result2 = mysql_query($sql,$con);
				$count_r2 = mysql_num_rows($result2);
				if (mysql_fetch_array($result2))
				{
					$i2=0;
					while ($i2 < $count_r2) 
					{
						$mob_id = mysql_result($result2,$i2,"npc_templateid");
						if ((mysql_result($result2,$i2,"locx") <> 0) || (mysql_result($result2,$i2,"locy") <> 0))
						{
							$x_co = mysql_result($result2,$i2,"locx");
							$y_co = mysql_result($result2,$i2,"locy");
							if (($x_co <= $map_right) && ($x_co >=$map_left) && ($y_co <= $map_bottom) && ($y_co >= $map_top))
							{
								$x_co = intval( difnums($x_co,$base_x) / $x_scale) - 1;
								$y_co = intval( difnums($y_co,$base_y) / $y_scale) - 1;
								echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 3; height: 3\" border=\"0\">\n";
							}
						}						
						else
						{
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
									if (($locat_x <> 0) || ($locat_y <> 0))
									{
										if (($locat_x <= $map_right) && ($locat_x >=$map_left) && ($locat_y <= $map_bottom) && ($locat_y >= $map_top))
										{
											$x_co = intval( difnums($locat_x,$base_x) / $x_scale) - 1;
											$y_co = intval( difnums($locat_y,$base_y) / $y_scale) - 1;
											echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 3; height: 3\" border=\"0\">\n";
										}
									}
									$i3++;
								}
							}
						}
						$i2++;
					}
				}
				$i4++;
			}
			echo "</div></div>\n";
			echo "</td></tr></table></center>";
			
			$graphic_width = 312;
			$graphic_height = 1000;
			$map_file = $images_dir. "gracia.jpg";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			echo "\n<div style=\"position: relative;\"><img src=\"$map_file\" alt=\"\" width=\"$graphic_width\" height=\"$graphic_height\" border=\"0\"><div>";
			$map_right = -166144;
			$map_left = -329450;
			$map_top = -246560;
			$map_bottom = 259838;

			$offset_x = 0;
			$offset_y = 0;	
			
			if ($map_right > $map_left)
			{
				$x_scale = difnums($map_left, $map_right);
				$base_x = $map_left;
			}
			else
			{
				$x_scale = difnums($map_right, $map_left);
				$base_x = $map_right;
			}
			
			if ($map_bottom > $map_top)
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_top;
			}
			else
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_bottom;
			}
			$x_scale = $x_scale / $graphic_width;
			$y_scale = $y_scale / $graphic_height;
			$image_tempblank = $images_dir . "blank.gif";
			$image_temp0 = $images_dir . "temp0.gif";
			$image_temp1 = $images_dir . "temp1.gif";
			$image_temp2 = $images_dir . "temp2.gif";
			$image_temp3 = $images_dir . "temp3.gif";
			$image_temp4 = $images_dir . "temp4.gif";
			$image_temp5 = $images_dir . "temp5.gif";
			$image_temp6 = $images_dir . "temp6.gif";
			$image_temp7 = $images_dir . "temp7.gif";
			$image_temp8 = $images_dir . "temp8.gif";
			$image_temp9 = $images_dir . "temp9.gif";
			$sql = "select level, `id` from npc where type = 'L2Monster' union select level, `id` from custom_npc where type = 'L2Monster'";
			$result4 = mysql_query($sql,$con);
			$count_r4 = mysql_num_rows($result4);
			$i4 = 0;
			while ($i4 < $count_r4)
			{
				$template_id = mysql_result($result4,$i4,"id");
				$map_tag = 12;
				$mob_level = mysql_result($result4,$i4,"level");				
				$map_tag = intval($mob_level / 10);
				$point_dat = $images_dir. "temp" . $map_tag . ".gif";
				$sql = "select locx, locy, loc_id, count, npc_templateid from spawnlist where npc_templateid = $template_id";
				$result2 = mysql_query($sql,$con);
				$count_r2 = mysql_num_rows($result2);
				if (mysql_fetch_array($result2))
				{
					$i2=0;
					while ($i2 < $count_r2) 
					{
						$mob_id = mysql_result($result2,$i2,"npc_templateid");
						if ((mysql_result($result2,$i2,"locx") <> 0) || (mysql_result($result2,$i2,"locy") <> 0))
						{
							$x_co = mysql_result($result2,$i2,"locx");
							$y_co = mysql_result($result2,$i2,"locy");
							if (($x_co <= $map_right) && ($x_co >=$map_left) && ($y_co <= $map_bottom) && ($y_co >= $map_top))
							{
								$x_co = intval( difnums($x_co,$base_x) / $x_scale) - 1;
								$y_co = intval( difnums($y_co,$base_y) / $y_scale) - 1;
								echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 3; height: 3\" border=\"0\">\n";
							}
						}						
						else
						{
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
									if (($locat_x <> 0) || ($locat_y <> 0))
									{
										if (($locat_x <= $map_right) && ($locat_x >=$map_left) && ($locat_y <= $map_bottom) && ($locat_y >= $map_top))
										{
											$x_co = intval( difnums($locat_x,$base_x) / $x_scale) - 1;
											$y_co = intval( difnums($locat_y,$base_y) / $y_scale) - 1;
											echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 3; height: 3\" border=\"0\">\n";
										}
									}
									$i3++;
								}
							}
						}
						$i2++;
					}
				}
				$i4++;
			}
			echo "</div></div>\n";
			echo "</td></tr>";
			echo "</table></center>";
		}
		elseif ($action=="raidmobs")
		{
			$map_right = 229388;
			$map_left = -129000;
			$map_top = -262143;
			$map_bottom = 261000;
					
			$graphic_width = 750;
			$graphic_height = 1084;
			
			$offset_x = 0;
			$offset_y = 0;
			
			$map_file = $images_dir. "map.jpg";
			
			if ($map_right > $map_left)
			{
				$x_scale = difnums($map_left, $map_right);
				$base_x = $map_left;
			}
			else
			{
				$x_scale = difnums($map_right, $map_left);
				$base_x = $map_right;
			}
			
			if ($map_bottom > $map_top)
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_top;
			}
			else
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_bottom;
			}
			$x_scale = $x_scale / $graphic_width;
			$y_scale = $y_scale / $graphic_height;
			$image_tempblank = $images_dir . "blank.gif";
			$image_temp0 = $images_dir . "temp0.gif";
			$image_temp1 = $images_dir . "temp1.gif";
			$image_temp2 = $images_dir . "temp2.gif";
			$image_temp3 = $images_dir . "temp3.gif";
			$image_temp4 = $images_dir . "temp4.gif";
			$image_temp5 = $images_dir . "temp5.gif";
			$image_temp6 = $images_dir . "temp6.gif";
			$image_temp7 = $images_dir . "temp7.gif";
			$image_temp8 = $images_dir . "temp8.gif";
			$image_temp9 = $images_dir . "temp9.gif";
			echo "<h2 class=\"dropmain\">Raid Boss Spawns</h2>";
			echo "<center><table class=\"blanktab\"><tr>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[0-9]</p></td><td class=\"noborderback\"><img src=\"$image_temp0\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[20-29]</p></td><td class=\"noborderback\"><img src=\"$image_temp2\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[40-49]</p></td><td class=\"noborderback\"><img src=\"$image_temp4\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[60-69]</p></td><td class=\"noborderback\"><img src=\"$image_temp6\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[80-89]</p></td><td class=\"noborderback\"><img src=\"$image_temp8\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "</tr><tr>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[10-19]</p></td><td class=\"noborderback\"><img src=\"$image_temp1\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[30-39]</p></td><td class=\"noborderback\"><img src=\"$image_temp3\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[50-59]</p></td><td class=\"noborderback\"><img src=\"$image_temp5\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[70-79]</p></td><td class=\"noborderback\"><img src=\"$image_temp7\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[90+]</p></td><td class=\"noborderback\"><img src=\"$image_temp9\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "</tr></table></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			echo "\n<div style=\"position: relative;\"><img src=\"$map_file\" alt=\"\" width=\"750\" height=\"1084\" border=\"0\"><div>";
			$sql = "select `level`, `id` from npc where type = 'L2RaidBoss' union select `level`, `id` from custom_npc where type = 'L2RaidBoss'";
			$result4 = mysql_query($sql,$con);
			$count_r4 = mysql_num_rows($result4);
			$i4 = 0;
			while ($i4 < $count_r4)
			{
				$template_id = mysql_result($result4,$i4,"id");
				$map_tag = 12;
				$mob_level = mysql_result($result4,$i4,"level");				
				$map_tag = intval($mob_level / 10);
				$point_dat = $images_dir. "temp" . $map_tag . ".gif";
				$sql = "select loc_x, loc_y from raidboss_spawnlist where boss_id = $template_id";
				$result2 = mysql_query($sql,$con);
				$count_r2 = mysql_num_rows($result2);
				if (mysql_fetch_array($result2))
				{
					$i2=0;
					while ($i2 < $count_r2) 
					{
						$x_co = mysql_result($result2,$i2,"loc_x");
						$y_co = mysql_result($result2,$i2,"loc_y");
						if (($x_co <= $map_right) && ($x_co >=$map_left) && ($y_co <= $map_bottom) && ($y_co >= $map_top))
						{
							$x_co = intval( difnums($x_co,$base_x) / $x_scale) - 3;
							$y_co = intval( difnums($y_co,$base_y) / $y_scale) - 3;
							echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\">\n";
						}
						$i2++;
					}
				}
				$i4++;
			}

			echo "</div></div>\n";
			echo "</td></tr></table></center>";
			
			$graphic_width = 312;
			$graphic_height = 1000;
			$map_file = $images_dir. "gracia.jpg";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			echo "\n<div style=\"position: relative;\"><img src=\"$map_file\" alt=\"\" width=\"$graphic_width\" height=\"$graphic_height\" border=\"0\"><div>";
			$map_right = -166144;
			$map_left = -329450;
			$map_top = -246560;
			$map_bottom = 259838;

			$offset_x = 0;
			$offset_y = 0;	
			
			$map_file = $images_dir. "map.jpg";
			
			if ($map_right > $map_left)
			{
				$x_scale = difnums($map_left, $map_right);
				$base_x = $map_left;
			}
			else
			{
				$x_scale = difnums($map_right, $map_left);
				$base_x = $map_right;
			}
			
			if ($map_bottom > $map_top)
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_top;
			}
			else
			{
				$y_scale = difnums($map_top, $map_bottom);
				$base_y = $map_bottom;
			}
			$x_scale = $x_scale / $graphic_width;
			$y_scale = $y_scale / $graphic_height;
			$image_tempblank = $images_dir . "blank.gif";
			$image_temp0 = $images_dir . "temp0.gif";
			$image_temp1 = $images_dir . "temp1.gif";
			$image_temp2 = $images_dir . "temp2.gif";
			$image_temp3 = $images_dir . "temp3.gif";
			$image_temp4 = $images_dir . "temp4.gif";
			$image_temp5 = $images_dir . "temp5.gif";
			$image_temp6 = $images_dir . "temp6.gif";
			$image_temp7 = $images_dir . "temp7.gif";
			$image_temp8 = $images_dir . "temp8.gif";
			$image_temp9 = $images_dir . "temp9.gif";
		
			$sql = "select `level`, `id` from npc where type = 'L2RaidBoss' union select `level`, `id` from custom_npc where type = 'L2RaidBoss'";
			$result4 = mysql_query($sql,$con);
			$count_r4 = mysql_num_rows($result4);
			$i4 = 0;
			while ($i4 < $count_r4)
			{
				$template_id = mysql_result($result4,$i4,"id");
				$map_tag = 12;
				$mob_level = mysql_result($result4,$i4,"level");				
				$map_tag = intval($mob_level / 10);
				$point_dat = $images_dir. "temp" . $map_tag . ".gif";
				$sql = "select loc_x, loc_y from raidboss_spawnlist where boss_id = $template_id";
				$result2 = mysql_query($sql,$con);
				$count_r2 = mysql_num_rows($result2);
				if (mysql_fetch_array($result2))
				{
					$i2=0;
					while ($i2 < $count_r2) 
					{
						$x_co = mysql_result($result2,$i2,"loc_x");
						$y_co = mysql_result($result2,$i2,"loc_y");
						if (($x_co <= $map_right) && ($x_co >=$map_left) && ($y_co <= $map_bottom) && ($y_co >= $map_top))
						{
							$x_co = intval( difnums($x_co,$base_x) / $x_scale) - 3;
							$y_co = intval( difnums($y_co,$base_y) / $y_scale) - 3;
							echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\">\n";
						}
						$i2++;
					}
				}
				$i4++;
			}

			echo "</div></div>\n";
			echo "</td></tr></table></center>";
		}
		elseif ($action=="bossmobs")
		{
				$map_right = 229388;
				$map_left = -129000;
				$map_top = -262143;
				$map_bottom = 261000;
						
				$graphic_width = 750;
				$graphic_height = 1084;
				
				$offset_x = 0;
				$offset_y = 0;
				
				$map_file = $images_dir. "map.jpg";
				
				if ($map_right > $map_left)
				{
					$x_scale = difnums($map_left, $map_right);
					$base_x = $map_left;
				}
				else
				{
					$x_scale = difnums($map_right, $map_left);
					$base_x = $map_right;
				}
				
				if ($map_bottom > $map_top)
				{
					$y_scale = difnums($map_top, $map_bottom);
					$base_y = $map_top;
				}
				else
				{
					$y_scale = difnums($map_top, $map_bottom);
					$base_y = $map_bottom;
				}
				$x_scale = $x_scale / $graphic_width;
				$y_scale = $y_scale / $graphic_height;
			$image_tempblank = $images_dir . "blank.gif";
			$image_temp0 = $images_dir . "temp0.gif";
			$image_temp1 = $images_dir . "temp1.gif";
			$image_temp2 = $images_dir . "temp2.gif";
			$image_temp3 = $images_dir . "temp3.gif";
			$image_temp4 = $images_dir . "temp4.gif";
			$image_temp5 = $images_dir . "temp5.gif";
			$image_temp6 = $images_dir . "temp6.gif";
			$image_temp7 = $images_dir . "temp7.gif";
			$image_temp8 = $images_dir . "temp8.gif";
			$image_temp9 = $images_dir . "temp9.gif";
			echo "<h2 class=\"dropmain\">Boss Spawns</h2>";
			echo "<center><table class=\"blanktab\"><tr>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[0-9]</p></td><td class=\"noborderback\"><img src=\"$image_temp0\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[20-29]</p></td><td class=\"noborderback\"><img src=\"$image_temp2\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[40-49]</p></td><td class=\"noborderback\"><img src=\"$image_temp4\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[60-69]</p></td><td class=\"noborderback\"><img src=\"$image_temp6\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[80-89]</p></td><td class=\"noborderback\"><img src=\"$image_temp8\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "</tr><tr>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[10-19]</p></td><td class=\"noborderback\"><img src=\"$image_temp1\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[30-39]</p></td><td class=\"noborderback\"><img src=\"$image_temp3\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[50-59]</p></td><td class=\"noborderback\"><img src=\"$image_temp5\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[70-79]</p></td><td class=\"noborderback\"><img src=\"$image_temp7\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">[90+]</p></td><td class=\"noborderback\"><img src=\"$image_temp9\" width=\"20\" height=\"20\"></td><td class=\"noborderback\"><img src=\"$image_tempblank\" width=\"20\" height=\"20\"></td>";
			echo "</tr></table></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";

			echo "\n<div style=\"position: relative;\"><img src=\"$map_file\" alt=\"\" width=\"750\" height=\"1084\" border=\"0\"><div>";
			$sql = "select level, `id` from npc where type = 'L2Boss' union select level, `id` from custom_npc where type = 'L2Boss'";
			$result4 = mysql_query($sql,$con);
			$count_r4 = mysql_num_rows($result4);
			$i4 = 0;
			while ($i4 < $count_r4)
			{
				$template_id = mysql_result($result4,$i4,"id");
				$map_tag = 12;
				$mob_level = mysql_result($result4,$i4,"level");				
				$map_tag = intval($mob_level / 10);
				$point_dat = $images_dir. "temp" . $map_tag . ".gif";
				$sql = "select locx, locy from spawnlist where npc_templateid = $template_id";
				$result2 = mysql_query($sql,$con);
				$count_r2 = mysql_num_rows($result2);
				if (mysql_fetch_array($result2))
				{
					$i2=0;
					while ($i2 < $count_r2) 
					{
							$x_co = mysql_result($result2,$i2,"locx");
							$y_co = mysql_result($result2,$i2,"locy");
							$x_co = intval( difnums($x_co,$base_x) / $x_scale) - 3;
							$y_co = intval( difnums($y_co,$base_y) / $y_scale) - 3;
							echo "<img src=\"$point_dat\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: 7; height: 7\" border=\"0\">\n";
						$i2++;
					}
				}
				$i4++;
			}

			echo "</div></div>\n";
			echo "</td></tr></table></center>";
		}
		elseif ($action=="zones")
		{
			echo "<center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			echo "<select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"\">- Select Zone -</option>";
			$zone_list="---";
	
			$file_loc = $server_dir . 'data' . $svr_dir_delimit . 'zones' . $svr_dir_delimit . 'zone.xml';
			$handle = @fopen($file_loc, "r");
			if ($handle) 
			{
				while ($line_in = fgets($handle))
				{
					if (strpos($line_in, "<zone") > 0)
					{
						$zone_delimeter = "\"";
						if (strpos($line_in, "'") > 0)
						{	$zone_delimeter = "'";	}
						$zidpos = strpos($line_in, "id=")+4;
						$zone_in = substr($line_in, $zidpos);
						$zidpos = strpos($zone_in, $zone_delimeter);
						$zone_in = substr($zone_in, 0, $zidpos);
						$ztypepos = strpos($line_in, "type=")+6;
						$zone_type = substr($line_in, $ztypepos);
						$ztypepos = strpos($zone_type, $zone_delimeter);
						$zone_type = substr($zone_type, 0, $ztypepos);
						
						if (strpos($zone_list, $zone_type) == 0)
						{	
							$p = strpos($zone_list, $zone_type);
							echo "<option value=\"statistics.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=zones&zone=$zone_type\">$zone_type</option>";	
							$zone_list = $zone_list . $zone_type . "-";
							echo "<p>$p -$zone_type- $zone_list</p>";
						}
						
						$zone_colour = 1;
						if (strlen($zone) == 0)
						{
							if (($zone_type == "Town") || ($zone_type == "PeaceTown"))
							{	$zone_colour = 2;	}
							if ($zone_type == "WaterZone")
							{	$zone_colour = 0;	}
							if ($zone_type == "PoisonZone")
							{	$zone_colour = 8;	}
							if ($zone_type == "DamageZone")
							{	$zone_colour = 9;	}
							if ($zone_type == "SwampZone")
							{	$zone_colour = 10;	}
							if ($zone_type == "FishingZone")
							{	$zone_colour = 11;	}
						}
						
						if ((strlen($zone) == 0) || ($zone == $zone_type))
						$sql="select x, y from zone_vertices where id = '$zone_in'";	
						$result = mysql_query($sql,$con);
						$count = mysql_num_rows($result);
						$i=0;

						while ($i < $count)
						{
							$x = mysql_result($result,$i,"x");
							$y = mysql_result($result,$i,"y");
							if (!$map_array)
							{
								$map_array = array(array($x, $y, $zone_colour));
							}
							else
							{
								array_push($map_array, array($x, $y, $zone_colour));
							}
							$i++;
						}
					}
				}
				fclose($handle);
			}
			else
			{	echo "<p>Couldn't open file for reading.</p>";	}
			
			
			echo "</select></tr></table></center>";
			
			echo "<h2 class=\"dropmain\">Zone - $zone</h2>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			map($map_array, $images_dir, 0, 1);
			echo "</td></tr></table></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\">";
			map($map_array, $images_dir, 0, 2);
			echo "</td></tr></table></center>";
		}
		else
		{
			if ($is_statsrunning)
			{
				$result = mysql_query("select maxplayers from knightstats where date = '2147483647'",$con);
				$count = mysql_num_rows($result);
				if ($count)
				{
					$max_p = mysql_result($result,0,"maxplayers");
					echo "<h2 class=\"dropmain\">Maximum players recorded online - $max_p</h2>";
				}
			}
			
			echo "<center><table class=\"noborder\" cellpadding=\"5\">";
			$result = mysql_query("SELECT login FROM $dblog_l2jdb.accounts where accessLevel < $sec_inc_gmlevel",$con2);
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT login FROM $dblog_l2jdb.accounts where accessLevel < 0",$con2);
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Accounts</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count - ($count2 banned)</p></td></tr>";
			$result = mysql_query("SELECT login FROM $dblog_l2jdb.accounts where accessLevel >= '$sec_inc_gmlevel' and accessLevel < '$sec_inc_admin'",$con2);
			$count = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">GM Accounts</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td></tr>";
			$result = mysql_query("SELECT login FROM $dblog_l2jdb.accounts where accessLevel >= '$sec_inc_admin'",$con2);
			$count = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Admin Accounts</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where accessLevel < $sec_inc_gmlevel");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where accessLevel < 0");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Characters</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count - ($count2 banned)</p></td></tr>";			
			$result = mysql_query("SELECT charId FROM characters where accessLevel > 0");
			$count = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">GM Characters</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td></tr>";	
			$result = mysql_query("SELECT clan_id FROM clan_data");
			$count = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">No. of clans</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td></tr>";
			$result = mysql_query("SELECT `count` FROM spawnlist");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT loc_id FROM locations");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Spawns</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count basic - $count2 extended</p></td></tr>";

			$result = mysql_query("SELECT count FROM items RIGHT OUTER JOIN characters ON characters.charId=items.owner_id where item_id = '57' AND accesslevel='0'");
			$adena = 0;
			if ($result)
			{
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$num = mysql_result($result,$i,"count");
					$adena = $adena + $num;
					$i++;
				}
			}
			$adena = comaise($adena);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Adena in world</p></td><td class=\"dropmain\"><p class=\"dropmain\">$adena</p></td></tr>";

                        $result = mysql_query("SELECT count FROM items RIGHT OUTER JOIN characters ON characters.charId=items.owner_id where item_id = '57' AND accesslevel!='0'");
			$adena = 0;
			if ($result)
			{
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$num = mysql_result($result,$i,"count");
					$adena = $adena + $num;
					$i++;
				}
			}
			$adena = comaise($adena);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">GM Adena</p></td><td class=\"dropmain\"><p class=\"dropmain\">$adena</p></td></tr>";

                        $result = mysql_query("SELECT count FROM items RIGHT OUTER JOIN characters ON characters.charId=items.owner_id where item_id = '5575' AND accesslevel='0'");
			$adena = 0;
			if ($result)
			{
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$num = mysql_result($result,$i,"count");
					$adena = $adena + $num;
					$i++;
				}
			}
			$adena = comaise($adena);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Ancient Adena in world</p></td><td class=\"dropmain\"><p class=\"dropmain\">$adena</p></td></tr>";

                        $result = mysql_query("SELECT count FROM items RIGHT OUTER JOIN characters ON characters.charId=items.owner_id where item_id = '5575' AND accesslevel!='0'");
			$adena = 0;
			if ($result)
			{
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$num = mysql_result($result,$i,"count");
					$adena = $adena + $num;
					$i++;
				}
			}
			$adena = comaise($adena);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">GM Ancient Adena</p></td><td class=\"dropmain\"><p class=\"dropmain\">$adena</p></td></tr>";

                        $result = mysql_query("SELECT count FROM items RIGHT OUTER JOIN characters ON characters.charId=items.owner_id where item_id = '6673' AND accesslevel='0'");
			$adena = 0;
			if ($result)
			{
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$num = mysql_result($result,$i,"count");
					$adena = $adena + $num;
					$i++;
				}
			}
			$adena = comaise($adena);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Festival Adena in world</p></td><td class=\"dropmain\"><p class=\"dropmain\">$adena</p></td></tr>";

                        $result = mysql_query("SELECT count FROM items RIGHT OUTER JOIN characters ON characters.charId=items.owner_id where item_id = '6673' AND accesslevel!='0'");
			$adena = 0;
			if ($result)
			{
				$count = mysql_num_rows($result);
				$i=0;
				while ($i < $count)
				{
					$num = mysql_result($result,$i,"count");
					$adena = $adena + $num;
					$i++;
				}
			}
			$adena = comaise($adena);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">GM Festival Adena</p></td><td class=\"dropmain\"><p class=\"dropmain\">$adena</p></td></tr>";
			echo "</table></center><p class=\"dropmain\">&nbsp;</p>";

			echo "<center><table class=\"noborder\" cellpadding=\"5\">";
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">Race</p></td><td class=\"dropmain\"><p class=\"dropmain\">$lang_total</p></td><td class=\"dropmain\"><p class=\"dropmain\">Online</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where race = 0");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where race = 0 and online = 1");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_human</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count2</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where race = 1");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where race = 1 and online = 1");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_elf</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count2</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where race = 2");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where race = 2 and online = 1");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_delf</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count2</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where race = 3");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where race = 3 and online = 1");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_orc</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count2</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where race = 4");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where race = 4 and online = 1");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_dwarf</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count2</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where race = 5");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where race = 5 and online = 1");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_kamael</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count2</p></td></tr>";
			$result = mysql_query("SELECT charId FROM characters where race > 5");
			$count = mysql_num_rows($result);
			$result = mysql_query("SELECT charId FROM characters where race > 5 and online = 1");
			$count2 = mysql_num_rows($result);
			echo "<tr><td class=\"dropmain\"><p class=\"dropmain\">$lang_unknown</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count</p></td><td class=\"dropmain\"><p class=\"dropmain\">$count2</p></td></tr>";
			echo "</table></center>";
	
		}


	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
