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
$sort = input_check($_REQUEST['sort'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if (!$top_ten)
	{
		writewarn("Top Ten switched off by Admin.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_sevens\" type=\"submit\" class=\"bigbut2\"></form></td>";
	if ($top_ten)
	{	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_topten\" type=\"submit\" class=\"bigbut2\"></form></td>";	}
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"trusted.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_trustedp\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"reference.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_classtree\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"castles.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_caststat\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"clanhall.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Clan Halls\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "</tr></table>";
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
	
	echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">$lang_topten</h2>";
	if ($gmintopten)
	{        $sql = "select char_name, level, karma, fame, pvpkills, pkkills, clanid, onlinetime from characters ";        }
	else
	{        $sql = "select char_name, level, karma, fame, pvpkills, pkkills, clanid, onlinetime from characters where accesslevel = 0 ";        }
	if ($sort == "karma")
	{	$sql = $sql . "order by karma DESC ";	}
        elseif ($sort == "fame")
        {        $sql = $sql . "order by fame DESC ";        }
	elseif ($sort == "pvp")
	{	$sql = $sql . "order by pvpkills DESC ";	}
	elseif ($sort == "pk")
	{	$sql = $sql . "order by pkkills DESC ";	}
	elseif ($sort == "time")
	{	$sql = $sql . "order by onlinetime DESC ";	}
	else
	{	$sql = $sql . "order by exp DESC ";	}
	if ($tten_number == 0)
	{	$sql = $sql . "limit 10";	}
	elseif ($tten_number == 1)
	{	$sql = $sql . "limit 50";	}
	else
	{	$sql = $sql . "limit 100";	}
	$result = mysql_query($sql,$con);
	$count = 0;
	if ($result)
	{	$count = mysql_num_rows($result);	}
	
	echo "<center><table class=\"dropmain\" cellpadding=\"5\" cellspacing=\"0\"><tr>";
	echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">$lang_character</strong></p></td>";
	if ($tten_level)
	{	echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">$lang_level<a href=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></strong></p></td>";	}
	echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">$lang_clan</strong></p></td>";
	if ($tten_pk)
	{	echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">PK<a href=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&sort=pk\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></strong></p></td>";	}
	if ($tten_pvp)
	{	echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">PvP<a href=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&sort=pvp\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></strong></p></td>";	}
	if ($tten_karma)
	{	echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">$lang_karma<a href=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&sort=karma\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></strong></p></td>";	}
        if ($tten_fame)
        {        echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">$lang_fame<a href=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&sort=fame\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></strong></p></td>";        }
	if ($tten_time)
	{	echo "<td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Time Online<a href=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&sort=time\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></strong></p></td>";	}
	echo "</tr>";
	$i=0;
	while ($i < $count)
	{
		$char_clan = mysql_result($result,$i,"clanid");
		$char_name = mysql_result($result,$i,"char_name");
		$char_lvl= mysql_result($result,$i,"level");
		$karma = mysql_result($result,$i,"karma");
                $fame = mysql_result($result,$i,"fame");
		$pvp = mysql_result($result,$i,"pvpkills");
		$pk = mysql_result($result,$i,"pkkills");
		$ontime = mysql_result($result,$i,"onlinetime");
		echo "<tr>";
		echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$char_name</strong></p></td>";
		if ($tten_level)
		{	echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$char_lvl</strong></p></td>";	}
		$clan_name = "&nbsp;";
		$sql = "select clan_name from clan_data where clan_id = '$char_clan'";
		$result2 = mysql_query($sql,$con);
		if ($result2)
		{	if (mysql_num_rows($result2))
			{	$clan_name = mysql_result($result2,0,"clan_name");	}
		}
		echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$clan_name</strong></p></td>";	

		if ($tten_pk)
		{	echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$pk</strong></p></td>";	}
		if ($tten_pvp)
		{	echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$pvp</strong></p></td>";	}
		if ($tten_karma)
		{	echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$karma</strong></p></td>";	}
                if ($tten_fame)
                {        echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$fame</strong></p></td>";        }
		if ($tten_time)
		{	
			$onlinetime = onlinetime($ontime);
			echo "<td class=\"dropmain\"><p class=\"left\"><strong class=\"dropmain\">$onlinetime</strong></p></td>";	
		}
		echo "</tr>";
		$i++;
	}
	echo "</table></center>";

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>