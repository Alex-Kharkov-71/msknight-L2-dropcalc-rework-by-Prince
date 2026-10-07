<?php 
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
header("Content-type: image/jpeg");
/*
Michelle Knight's Drop Calc - Version 5.0.0
Author - Michelle Knight
Copyright 2006/2007
Contact - dropcalc@msknight.com

GNU General Licence
Use and distribute freely, but leave headers intact and make no charge.
Code distributed without warantee or liability as to merchantability as
no charge is made for its use.  Use is at users risk.
*/

include('config.php');

function onlinetime($onlinetime)
{
	$onlinetime = intval($onlinetime / 60);
	$minutes = $onlinetime - (intval($onlinetime / 60) * 60);
	$onlinetime = intval($onlinetime / 60);
	$hours = $onlinetime - (intval($onlinetime / 24) * 24);
	$onlinetime = intval($onlinetime / 24);
	$days = $onlinetime - (intval($onlinetime / 30) * 30);
	$onlinetime = intval($onlinetime / 30);
	$onlinetext = "";
	if ($onlinetime > 0)
	{ $onlinetext = $onlinetime . "M,";	}
	if ($days > 0)
	{ $onlinetext = $onlinetext . $days . "d,";	}
	if ($hours > 0)
	{ $onlinetext = $onlinetext . $hours . "h,";	}
	if ($minutes > 0)
	{ $onlinetext = $onlinetext . $minutes . "m";	}
	return $onlinetext;
}

function checkport($ip, $port, $timeout) 
{
		$sock = @fsockopen($host, $port, $errno, $errstr, (float)$timeout);
		$online = ($sock>0);
 		if ($online) @fclose($sock);
 		return $online;
}

$con = mysql_connect($core_db_location, $core_db_user, $core_db_psswd);
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

$sql = "select * from $core_db_l2jdb.knightsettings";
$result_set = mysql_query($sql,$con);
if (!$result_set)
{	
	echo "<p class=\"popup\">Couldn't read the knightsettings table!!!</p>";
}
$gdon = mysql_result($result_set,0,"gd_on");
$gdstyle = mysql_result($result_set,0,"gd_style");
$gdsrvon = mysql_result($result_set,0,"gd_srvon");
$gdcompress = mysql_result($result_set,0,"gd_compress");

$character = $_REQUEST['c'];
$server = $_REQUEST['s'];

$file_loc = $images_loc_dir . 'gd' . $svr_dir_delimit;

$image = "gen0.jpg";

$con = mysql_connect($db_location,$db_user,$db_psswd);
mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
if (!$con)
{
	echo "<p class=\"popup\">Could Not Connect</p>";
	die('Could not connect: ' . mysql_error());
}		
if (!mysql_select_db("$db_l2jdb",$con))
{	die('Could not change to L2J database: ' . mysql_error());	}

$found=0;
$server_found = 0;

if (strlen($character) > 0)
{
	if ($gdon == 0)
	{	return 0;	}
	$split = preg_split('/[-]/', $character);
	$g_svr=$split[0];
	$g_chr=$split[1];
	if ($g_svr >= $max_servers)
	{	return 0;	}
	$g_name = $gameservers[$g_svr][0];
	$g_ip = $gameservers[$g_svr][1];
	$g_user = $gameservers[$g_svr][3];
	$g_passwd = $gameservers[$g_svr][4];
	$g_knightdb = $gameservers[$g_svr][2];
	$con2 = mysql_connect($g_ip, $g_user, $g_passwd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "<p class=\"popup\">Could Not Connect</p>";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$g_knightdb",$con2))
	{	die('Could not change to L2J database: ' . mysql_error());	}

	$result = mysql_query("select account_name, char_name, level, sex, maxHp, maxMp, maxCp, online, onlinetime, clanid, race, classid, base_class, karma from $g_knightdb.characters where charId = '$g_chr'",$con2);
	while ($r_array = mysql_fetch_assoc($result))
	{
		$a_name = $r_array['account_name'];
		$c_name = $r_array['char_name'];
		$c_lvl = $r_array['level'];
		$c_maxhp = $r_array['maxHp'];
		$c_maxcp = $r_array['maxCp'];
		$c_maxmp = $r_array['maxMp'];
		$c_sex = $r_array['sex'];
		$c_online = $r_array['online'];
		$c_onlinetime = $r_array['onlinetime'];
		$c_race = $r_array['race'];
		$c_base = $r_array['base_class'];
		$c_class = $r_array['classid'];
		$c_karma = $r_array['karma'];
		$c_clanid = $r_array['clanid'];
		$race = "unknown";
		$base = "gen0";
		if ($gdon == 2)
		{
			$con3 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
			mysql_query("SET NAMES 'utf8'", $con3);
			if (!$con3)
			{
				echo "Could Not Connect";
				die('Wrap_start could not connect to logserver database: ' . mysql_error());
			}		
			if (!mysql_select_db("$dblog_l2jdb",$con2))
			{
				die('Wrap_start could not change to logserver database: ' . mysql_error());
			}
			$result2 = mysql_query("select gdaccess from $dblog_l2jdb.knightdrop where name = '$a_name'",$con3);
			$gdtime = mysql_result($result2,0,"gdaccess");
			$today_time = time();
			if ($gdtime < $today_time)  // If a valid user has a usage time limit higher than today, then allow.
			{	return 0;	}
		}
		if ($c_race == 0)
		{
			$race = "Human";
			$base = "h";
		}
		elseif ($c_race == 1)
		{
			$race = "Elf";
			$base = "e";
		}
		elseif ($c_race == 2)
		{
			$race = "DarkElf";
			$base = "de";
		}
		elseif ($c_race == 3)
		{
			$race = "Orc";
			$base = "o";
		}
		elseif ($c_race == 4)
		{
			$race = "Dwarf";
			$base = "d";
		}
		elseif ($c_race == 5)
		{
			$race = "Kamael";
			$base = "k";
		}
		if ($c_sex == 1)
		{	$base = $base . 'f';	}
		else
		{	$base = $base . 'm';	}
		$is_mystic = in_array($c_base, $mystic_numbers);
		if ($is_mystic)
		{	$base = $base . 'm';	}
		else
		{	$base = $base . 'f';	}
		$result2 = mysql_query("select class_name from $g_knightdb.class_list where id = '$c_class'",$con2);
		$class_name = mysql_result($result2,0,"class_name");
		$c_classtitle = substr($class_name,strpos($class_name,'_')+1,(strlen($class_name)-strpos($class_name,'_')));
		$c_clan = "";
		if ($c_clanid)
		{
			$result2 = mysql_query("select clan_name from $g_knightdb.clan_data where clan_id = '$c_clanid'",$con2);
			$c_clan = mysql_result($result2,0,"clan_name");
		}
		$found = 1;
	}
	if ($found == 0)
	{	return 0;	}
}
elseif (strlen($server) > 0)
{
	if ($gdsrvon == 0)
	{	return 0;	}
	$server = intval($server);
	$base = "gen" . rand(0,5);
	$g_name = $gameservers[$server][0];
	$g_ip = $gameservers[$server][1];
	$g_user = $gameservers[$server][3];
	$g_passwd = $gameservers[$server][4];
	$g_knightdb = $gameservers[$server][2];
	$g_tip = $gameservers[$server][9];
	$g_port = $gameservers[$server][7];
	$con2 = mysql_connect($g_ip, $g_user, $g_passwd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (checkport($g_ip, $g_port, $g_timeout))
	{	$gs_status = 1;	}
	else
	{	$gs_status = 0;	}
	if (!$con2)
	{
		echo "<p class=\"popup\">Could Not Connect</p>";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$g_knightdb",$con2))
	{	die('Could not change to L2J database: ' . mysql_error());	}
	$result = mysql_query("select COUNT(*) from $g_knightdb.characters where online = 1",$con2);
	$s_online = mysql_result($result,0,"COUNT(*)");
	$result = mysql_query("select COUNT(*) from $g_knightdb.characters where race = 0",$con2);
	$s_hmn = mysql_result($result,0,"COUNT(*)");
	$result = mysql_query("select COUNT(*) from $g_knightdb.characters where race = 1",$con2);
	$s_elf = mysql_result($result,0,"COUNT(*)");
	$result = mysql_query("select COUNT(*) from $g_knightdb.characters where race = 2",$con2);
	$s_delf = mysql_result($result,0,"COUNT(*)");
	$result = mysql_query("select COUNT(*) from $g_knightdb.characters where race = 3",$con2);
	$s_orc = mysql_result($result,0,"COUNT(*)");
	$result = mysql_query("select COUNT(*) from $g_knightdb.characters where race = 4",$con2);
	$s_dwrf = mysql_result($result,0,"COUNT(*)");
	$result = mysql_query("select COUNT(*) from $g_knightdb.characters where race = 5",$con2);
	$s_kml = mysql_result($result,0,"COUNT(*)");
	$s_rate_a = "?";
	$s_rate_i = "?";
	$s_rate_s = "?";
	$result = mysql_query("select drop_chance_adena, drop_chance_item, drop_chance_spoil from $g_knightdb.knightsettings",$con2);
	while ($r_array = mysql_fetch_assoc($result))
	{
		$s_rate_a = $r_array['drop_chance_adena'];
		$s_rate_i = $r_array['drop_chance_item'];
		$s_rate_s = $r_array['drop_chance_spoil'];
	}
	$result2 = mysql_query("select COUNT(*) from $g_knightdb.clan_data",$con2);
	$cl_count = mysql_result($result2,0,"COUNT(*)");
	$result2 = mysql_query("select COUNT(*) from $g_knightdb.characters where clanid > 0",$con2);
	$cl_inclan = mysql_result($result2,0,"COUNT(*)");
	$result2 = mysql_query("select COUNT(*) from $g_knightdb.characters where clanid = 0",$con2);
	$cl_notinclan = mysql_result($result2,0,"COUNT(*)");
		
	$server_found = 1;
}
else
{	return 0;	}

$image_loc =  $file_loc . $base . '.jpg';

$im = imagecreatefromjpeg($image_loc);

if (strlen($character) > 0)
{
	$yellow = imagecolorallocate($im, 255, 255, 0);
	$orange = imagecolorallocate($im, 255, 110, 0);
	$white = imagecolorallocate($im, 255, 255, 255);
	$green = imagecolorallocate($im, 0, 255, 0);
	$red = imagecolorallocate($im, 255, 0, 0);
	if ($gdstyle == 1)
	{
		imagestring($im, 3, 114, 10, "Character:", $yellow );
		imagestring($im, 3, 114, 30, "Class:", $yellow);
		imagestring($im, 3, 114, 50, "Clan:", $yellow);
		imagestring($im, 3, 210, 10, $c_name, $white);
		imagestring($im, 3, 178, 30, $c_classtitle, $white);
		imagestring($im, 3, 170, 50, $c_clan, $white);
	}
	else
	{
		$kma_shift = 44 - (strlen($c_karma) * 7);
		imagestring($im, 3, 114, 7, "Character:", $yellow );
		imagestring($im, 3, 310+$kma_shift, 7, "Kma:", $yellow);
		imagestring($im, 3, 114, 24, "Level:", $yellow);
		imagestring($im, 3, 188, 24, "Class:", $yellow);
		imagestring($im, 3, 114, 40, "Online:", $yellow);
		imagestring($im, 3, 260, 40, "Clan:", $yellow);
		imagestring($im, 3, 114, 57, "MaxHP:", $yellow );
		imagestring($im, 3, 210, 57, "MaxMP:", $yellow );
		imagestring($im, 3, 308, 57, "MaxCP:", $yellow );
		imagestring($im, 3, 195, 7, $c_name, $white);
		if ($c_karma > 0)
		{	imagestring($im, 3, 344+$kma_shift, 7, $c_karma, $orange);	}
		else
		{	imagestring($im, 3, 344+$kma_shift, 7, $c_karma, $white);	}
		imagestring($im, 3, 162, 24, $c_lvl, $white);
		imagestring($im, 3, 240, 24, $c_classtitle, $white);
		imagestring($im, 3, 170, 40, onlinetime($c_onlinetime), $white);
		imagestring($im, 3, 300, 40, $c_clan, $white);
		imagestring($im, 3, 162, 57, $c_maxhp, $white);
		imagestring($im, 3, 260, 57, $c_maxmp, $white);
		imagestring($im, 3, 356, 57, $c_maxcp, $white);
	}
	
	if ($c_online)
	{	imageFilledEllipse($im, 6, 7, 7, 7, $green);	}
	else
	{	imageFilledEllipse($im, 6, 7, 7, 7, $red);	}
}
elseif ($server_found)
{
	$yellow = imagecolorallocate($im, 255, 255, 0);
	$red = imagecolorallocate($im, 200, 0, 0);
	$green = imagecolorallocate($im, 0, 255, 0);
	$white = imagecolorallocate($im, 255, 255, 255);
	$svr = $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"];
	$t_name = substr("http://" . substr($svr,0,strrpos($svr,'/')+1),0,35);
	if (substr($t_name,strlen($t_name)-1,1) <> "/")
	{	$t_name = $t_name . '/';	}
	imagestring($im, 3, 10, 7, "Server:", $yellow );
	imagestring($im, 3, 190, 7, "Online:", $yellow );
	imagestring($im, 3, 294, 7, "Adena:", $yellow );
	imagestring($im, 3, 10, 24, "Url:", $yellow );
	imagestring($im, 3, 294, 24, "Drop:", $yellow );
	imagestring($im, 3, 294, 40, "Spoil:", $yellow );
	imagestring($im, 3, 10, 40, "Clans:", $yellow );
	imagestring($im, 3, 94, 40, "Chr In/Not Clans:", $yellow );
	imagestring($im, 3, 10, 57, "Hmn:", $yellow );
	imagestring($im, 3, 66, 57, "Elf:", $yellow );
	imagestring($im, 3, 125, 57, "Dlf:", $yellow );
	imagestring($im, 3, 186, 57, "Orc:", $yellow );
	imagestring($im, 3, 248, 57, "Dwrf:", $yellow );
	imagestring($im, 3, 320, 57, "Kaml:", $yellow );
	if ($gs_status)
	{	imagestring($im, 3, 66, 7, $g_name, $green);	}
	else
	{	imagestring($im, 3, 66, 7, $g_name, $red);	}
	imagestring($im, 3, 250, 7, $s_online, $white);
	imagestring($im, 3, 44, 24, $t_name, $white);
	imagestring($im, 3, 338, 7, $s_rate_a . "x", $white);
	imagestring($im, 3, 338, 24, $s_rate_i . "x", $white);
	imagestring($im, 3, 338, 40, $s_rate_s . "x", $white);
	imagestring($im, 3, 56, 40, $cl_count, $white);
	imagestring($im, 3, 220, 40, $cl_inclan . "/" . $cl_notinclan, $white);
	imagestring($im, 3, 40, 57, $s_hmn, $white);
	imagestring($im, 3, 96, 57, $s_elf, $white);
	imagestring($im, 3, 155, 57, $s_delf, $white);
	imagestring($im, 3, 216, 57, $s_orc, $white);
	imagestring($im, 3, 288, 57, $s_dwrf, $white);
	imagestring($im, 3, 360, 57, $s_kml, $white);
	
}

if ($gdcompress == 1)
{	imagejpeg($im, '', 90);	}
elseif ($gdcompress == 2)
{	imagejpeg($im, '', 75);	}
elseif ($gdcompress == 3)
{	imagejpeg($im, '', 60);	}
else
{	imagejpeg($im, '', 100);	}
imagedestroy($im);

?>