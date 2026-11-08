<?php 
error_reporting(E_ERROR | E_PARSE);

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
include_once('xmlparse_common.php');

// Parses a Mobius Java quest: returns array(1 => item ids given, 2 => npc ids involved).
function dc_parse_quest_java($src)
{
	$src = preg_replace('~//[^\n]*|/\*.*?\*/~s', '', $src);
	$const = array();
	if (preg_match_all('/\bint\s+(\w+)\s*=\s*(\d+)\s*;/', $src, $mm, PREG_SET_ORDER))
	{	foreach ($mm as $x) { $const[$x[1]] = array((int) $x[2]); }	}
	if (preg_match_all('/\bint\s*\[\]\s*(\w+)\s*=\s*(?:new\s+int\s*\[\]\s*)?\{([^}]*)\}/', $src, $mm, PREG_SET_ORDER))
	{	foreach ($mm as $x) { $const[$x[1]] = array_map('intval', preg_split('/\s*,\s*/', trim($x[2]))); }	}
	$resolve = function ($args, $skip) use ($const) {
		$out = array();
		$parts = array_slice(array_map('trim', explode(',', $args)), $skip);
		foreach ($parts as $p)
		{
			if (preg_match('/^\d+$/', $p)) { $out[] = (int) $p; }
			elseif (isset($const[$p])) { $out = array_merge($out, $const[$p]); }
		}
		return $out;
	};
	$items = array(); $npcs = array();
	if (preg_match_all('/\b(?:giveItems|rewardItems)\s*\(\s*[^,()]+,\s*([^,()]+)/', $src, $mm))
	{	foreach ($mm[1] as $a) { $items = array_merge($items, $resolve($a, 0)); }	}
	if (preg_match_all('/\b(?:addStartNpc|addTalkId|addFirstTalkId|addKillId|addSpawnId)\s*\(([^;]*)\)\s*;/', $src, $mm))
	{	foreach ($mm[1] as $a) { $npcs = array_merge($npcs, $resolve($a, 0)); }	}
	$items = array_values(array_unique(array_filter($items, function ($v) { return $v > 0; })));
	$npcs = array_values(array_unique(array_filter($npcs, function ($v) { return $v > 0; })));
	return array(1 => $items, 2 => $npcs);
}

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$next = input_check($_REQUEST['next'],0);
$next++;

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

echo "<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>";

$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
			</head>
			<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
			<center><p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		$con = mysql_connect($db_location,$db_user,$db_psswd);
		mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
		if (!$con)
			{
			echo "<p class=\"popup\">Could Not Connect</p>";
			die('Could not connect: ' . mysql_error());
			}		
		if (!mysql_select_db("$db_l2jdb",$con))
			{
			die('Could not change to L2J database: ' . mysql_error());
			}
		$result = mysql_query("CREATE TABLE IF NOT EXISTS `knightquestrun` (`quest_id` int(4) NOT NULL default '0',`type` int(1) NOT NULL default '0',`target_id` int(11) NOT NULL default '0', KEY `qiest_id` (`quest_id`), UNIQUE quest_id (quest_id,type,target_id))",$con);
		mysql_query("alter table knightquests modify `name` varchar(120) default NULL",$con);
		$ix = mysql_query("show index from knightquestrun where Key_name = 'quest_id' and Column_name = 'type'",$con);
		if ($ix && mysql_num_rows($ix) == 0)
		{
			mysql_query("truncate table knightquestrun",$con);
			mysql_query("alter table knightquestrun drop index quest_id, add unique quest_id (quest_id,type,target_id)",$con);
		}
		$result = mysql_query("CREATE TABLE IF NOT EXISTS `knightquests` (`quest_id` int(4) NOT NULL default '0', `name` varchar(50) default NULL, KEY `quest_id` (`quest_id`))",$con);
		$qdir = $server_dir . 'data' . $svr_dir_delimit . 'scripts' . $svr_dir_delimit . 'quests' . $svr_dir_delimit;
		echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
			</head>
			<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
			<center>";
		xip_stat_reset($con, 'quests');
		mysql_query("truncate table knightquests",$con);
		mysql_query("truncate table knightquestrun",$con);
		$dirs = @glob($qdir . 'Q[0-9]*', GLOB_ONLYDIR);
		if (!is_array($dirs) || !count($dirs))
		{	echo "<p style=\"color:red\">Quest folders not found: " . htmlspecialchars($qdir) . "</p>";	}
		else
		{
			sort($dirs);
			$noitems = 0;
			foreach ($dirs as $dir)
			{
				$base = basename($dir);
				if (!preg_match('/^Q0*(\d+)_(.+)$/', $base, $m)) { continue; }
				$quest_number = (int) $m[1];
				$quest_name = mysql_real_escape_string(preg_replace('/(?<=[a-z])(?=[A-Z])/', ' ', $m[2]), $con);
				xip_q("insert into knightquests (quest_id, name) values ('$quest_number', '$quest_name')",$con);
				$src = @file_get_contents($dir . $svr_dir_delimit . $base . '.java');
				if ($src === false) { continue; }
				$res = dc_parse_quest_java($src);
				if (!count($res[1])) { $noitems++; }
				foreach ($res[1] as $id) { xip_q("insert ignore into knightquestrun (quest_id, type, target_id) values ('$quest_number', 1, '$id')",$con); }
				foreach ($res[2] as $id) { xip_q("insert ignore into knightquestrun (quest_id, type, target_id) values ('$quest_number', 2, '$id')",$con); }
			}
			xip_stat_flush($con, 'quests');
			echo "<p>Importing ended. Quests without detected items: $noitems</p>";
			xip_stat_report($con, 'quests');
		}
	}
}

echo "</center></body></html>";

?>
