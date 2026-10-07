<?php
/*
Michelle Knight's Drop Calc - Version 4
Author - Michelle Knight
Copyright 2006
Contact - dropcalc@msknight.com

GNU General Licence
Use and distribute freely, but leave headers intact and make no charge.
Change HTML code as necessary to fit your own site.
Code distributed without warantee or liability as to merchantability as
no charge is made for its use.  Use is at users risk.
*/


/* ****
IMPORTANT - Directory Notes - Directory separators

For Linux servers, delimeters are "/" 
For Windows 2000 servers, delimeters are "\\"
For Windows 2003 servers, delimeters are "//"
**** */

//Gamserverdb
$gameservers = ARRAY(
ARRAY("Title", "db_location", "db_database", "db_username", "db_password", 0, "server files location", gameserver_port, "knight_db", "gameserver_telnet_ip", "gameserver_telnet_port", "gameserver_Telnet_password", gameserver_timeout)
// ,ARRAY("Title", "db_location", "db_database", "db_username", "db_password", 0, "server files location", gameserver_port, "knight_db", "gameserver_telnet_ip", "gameserver_telnet_port", "gameserver_Telnet_password", gameserver_timeout)
);


//Logonserverdb
$dblog_location = "localhost";
$dblog_l2jdb = "l2jdblog";
$dblog_user = "username";
$dblog_psswd = "password";
$logsvr_location = "localhost";
$dblog_port = 2106;

// Login Telnet Settings
$log_telnet_host = "localhost";
$log_telnet_port = "9999";
$log_telnet_timeout = 10;

//----------Linux version
$login_dir = "/l2jserver/login";	// Dont forget the ending slash/backslashes!
$svr_dir_delimit = "/";
$images_loc_dir = "images/"; 		// server path to images ... relative to currect php directory.
//----------Windows 2000 version
//$login_dir = "\\l2jserver\\login\\";	// Dont forget the ending slash/backslashes!
//$svr_dir_delimit = "\\";
//$images_loc_dir = "images\\"; 		// server path to images ... relative to currect php directory.
//----------Windows 2003 version
//$login_dir = "//l2jserver//login//";	// Dont forget the ending slash/backslashes!
//$svr_dir_delimit = "//";
//$images_loc_dir = "images//"; 		// server path to images ... relative to currect php directory.

$images_dir = "images/";  	// Web path to images, always "/"

$skinslist = ARRAY("ToI - Blue Elf", "Frintezza", "Brushed Metal", "Simply Gray");

// Password for allowing deletion of accounts and characters.
$del_accchar = "l2j";

// Database backup system variables, including passwords required for each of the specific operations.

$conf_reference = "l2j";
$conf_diff = "l2j";
$conf_replay = "l2j";
$conf_backup = "l2j";
$conf_restore = "l2j";

// Tables which will be backed up, or merged.
$tables_merge = array('char_templates', 'clanhall', 'droplist', 'helper_buff_list', 'henna', 'henna_trees', 'locations', 'lvlupgain', 'mapregion', 'merchant_areas_list', 'merchant_buylists', 'merchant_lease', 'merchant_shopids', 'merchants', 'minions', 'npc', 'npcskills', 'pets_stats', 'raidboss_spawnlist', 'random_spawn', 'random_spawn_loc', 'seven_signs', 'seven_signs_festival', 'skill_learn', 'skill_spellbooks', 'skill_trees', 'spawnlist', 'teleport', 'zone');
$tables_backup = array('account_data', 'accounts', 'auction', 'auction_bid', 'auction_watch', 'boxaccess', 'boxes', 'character_friends', 'character_hennas', 'character_macroses', 'character_quests', 'character_recipebook', 'character_shortcuts', 'character_skills', 'character_skills_save', 'character_subclasses', 'characters', 'clan_data', 'clan_wars', 'forums', 'games', 'gameservers', 'heroes', 'items', 'knightdrop', 'olympiad_nobles', 'pets', 'posts', 'seven_signs_status', 'siege_clans', 'topic');

// Freight Location table.  Only for adjustment when new freight locations are added to the game.
// If you receive "loc x" instead of a freight location name, replace the loc code here with the correct freight location name.
$freightloc = array('Loc 1', 'Dark Elven Village', 'Talking Island Village', 'Elven Village', 'Orc Village', 'Gludin', 'Dwarven Village', 'Gludio', 'Dion', 'Giran', 'Oren Town', 'Hunters Village', 'Aden Town', 'Goddard Castle Town', 'Rune Castle Town', 'Heine', 'Loc 17', 'Loc 18', 'Loc 19', 'Loc 20', 'Loc 21', 'Loc 22', 'Loc 23', 'Loc 24', 'Loc 25', 'Loc 26', 'Loc 27', 'Loc 28', 'Loc 29', 'Loc 30');

// Countries and language files
$language_array = ARRAY(ARRAY('English', 'lang-eng.php',1),
			ARRAY('Deutsch', 'lang-deu.php',1),
			ARRAY('French', 'lang-fr.php',1),
			ARRAY('Polish', 'lang-pol.php',1),
			ARRAY('Italian','lang-it.php',1),
			ARRAY ('BR Portuguese', 'lang-ptbr.php',1),
			ARRAY ('Czech', 'lang-cz.php',1),
			ARRAY ('espa&#241;ol', 'lang-esp.php',1),
			ARRAY ('Russian', 'lang-ru.php',1));
setlocale(LC_ALL, 'ru_RU.UTF8'); //kind of prefered code page of your locale

// Base classs ID numbers of mystic characters	
$mystic_numbers = array(10, 11, 12, 13, 14, 15, 97, 98, 94, 95, 96, 25, 26, 29, 27, 28, 30, 103, 104, 105, 38, 39, 42, 40, 41, 43, 110, 111, 112, 49, 50, 51, 52, 115, 116);

//chat-log type 
$clog_type = "gamelike"; //native - as is; gamelike - formatted
//names of chats in clog.php (not in chat.log)
$chats = array(
		"shout",
		"all",
		"trade",
		"clan",
		"party",
		"alliance",
		"tell",
		"hero_voice",
		);
//colors for
$chat_colors = array(
	       	     '#c78354',    
		     '#fffdf9',
		     '#d8a3cf',
		     '#948ad1',
		     '#08fb09',
		     '#a0fbca',
		     '#be45b9',
		     '#3890fa',
	       	     );

// -----------------------------------------------
//
// Code to calculate database and skin to use.
//
// -----------------------------------------------

$server_id = preg_replace('/[&%$\/\|@<>#£]/','',$_REQUEST['server_id']);
$skin_id = preg_replace('/[&%$\/\|@<>#£]/','',$_REQUEST['skin_id']);
$max_servers = count($gameservers);
$max_skin = count($skinslist);
if (($server_id < 0) || (!$server_id))
{	$server_id = 0;	}
if ($server_id >= $max_servers)
{	$server_id = $max_servers - 1;	}
if (($skin_id < 0) || (!$skin_id))
{	$skin_id = 0;	}
if ($skin_id >= $max_skin)
{	$skin_id = $max_skin - 1;	}

$db_location = $gameservers[$server_id][1];
$db_l2jdb = $gameservers[$server_id][2];	
$db_user = $gameservers[$server_id][3];
$db_psswd = $gameservers[$server_id][4];
$server_dir = $gameservers[$server_id][6];
$knight_db = $gameservers[$server_id][8];
$telnet_host = $gameservers[$server_id][9];
$telnet_port = $gameservers[$server_id][10];
$telnet_password = $gameservers[$server_id][11];
$telnet_timeout = $gameservers[$server_id][12];
$core_db_location = $gameservers[0][1];
$core_db_l2jdb = $gameservers[0][2];	
$core_db_user = $gameservers[0][3];
$core_db_psswd = $gameservers[0][4];
$skin_dir = "$skin_id";
