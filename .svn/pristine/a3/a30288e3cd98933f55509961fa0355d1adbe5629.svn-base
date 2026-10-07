
<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

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


function check_table($db_location,$db_user,$db_psswd,$db,$table,$fields)
{
	echo "<p><strong>Checking table $table</strong></p>";
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	if (!mysql_select_db("$db",$con))
	{	echo "<p><font color=\"#ff0000\">Can not select database $db on $db_location</font></p>";	}
	else
	{
		$result2 = mysql_query("show fields from $table",$con);
		$count = 0;
		$found = 0;
		foreach($fields as $key)
		{
			
			$count++;
			$found_boolean = 0;
			$i = 0;
			while ($r_array = mysql_fetch_assoc($result2)) 
			{
				$field = $r_array['Field'];
				if (strcasecmp($field, "$key") == 0)
				{ 
					$found++; 
					$found_boolean = 1;
					$field = $r_array['Field'];
				}
			}
			mysql_data_seek($result2,0);
			if (!$found_boolean)
			{	echo "<p><font color=\"#ff0000\">field $key is missing for $db $table</font></p>";	}
		}
		if ($count == $found)
		{	
			echo "<p>Table O.K.</p>";	
			return 1;
		}
	}
	return 0;
}

echo "<h2>Michelle's Dropcalc - checking system</h2>";
$ds = strlen($images_loc_dir);
$sourceurl = base64_encode(pack("H*", sha1(utf8_encode($_SERVER["HTTP_HOST"]))));
echo "<p>Source check = $sourceurl</p>";
$image_loc =  $images_loc_dir . 'gd' . $svr_dir_delimit . 'dff.jpg';
echo "<table border=\"1\"><tr><td><p>If there is an image to the right, GD GIF is installed and working.</p></td><td><img src=\"img2.php\"></td></tr></table>";
echo "<table border=\"1\"><tr><td><p>If there is an image to the right, GD JPG is installed and working.</p></td><td><img src=\"img1.php\"></td></tr></table>";
echo "<table border=\"1\"><tr><td><p>If there is an image to the right, GD JPG is installed and working.<br>If it fails, check the access to location...<br>$image_loc</p></td><td><img src=\"img3.php\"></td></tr></table>";
if ($ds < 2)
{	echo "<p><font color=\"#ff0000\">Check the variable - $ images_loc_dir - in the config.php file - should contain the directory location of the images folder.</font></p>";	}
echo "<p><strong>Checking logon database</strong></p><hr>";
$knightdroptable = check_table($dblog_location,$dblog_user,$dblog_psswd,$dblog_l2jdb,'knightdrop',ARRAY('name','lastaction','token','mapaccess','recipeaccess','gdaccess','boxingok','warnlevel','characcess','lastheard','ipaddr','access_level','email','request_time','request_key', 'emailcheck', 'password'));
check_table($dblog_location,$dblog_user,$dblog_psswd,$dblog_l2jdb,'cc',ARRAY('ci','cc','cn'));
check_table($dblog_location,$dblog_user,$dblog_psswd,$dblog_l2jdb,'ip',ARRAY('start','end','ci'));
check_table($dblog_location,$dblog_user,$dblog_psswd,$dblog_l2jdb,'knightipok',ARRAY('ip_addr'));

// Select an entry from the knightdrop table.  If it is empty, then assume a new installation and run the import routine silently.
if ($knightdroptable)
{
	echo "<p><strong>Checking Knightdrop population - knightdrop ";
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Wrap_start could not change to logserver database: $dblog_l2jdb on server $dblog_location with credentials supplied<br>' . mysql_error());	}
	$result_drop = mysql_query("select COUNT(*) from $dblog_l2jdb.knightdrop",$con2);
	if ($result_drop)
	{	
		$recsinknightdrop = mysql_result($result_drop,0,"COUNT(*)");	
		echo "$recsinknightdrop - accounts ";
		$result_drop = mysql_query("select COUNT(*) from $dblog_l2jdb.accounts",$con2);
		if ($result_drop)
		{	
			$recsinaccdrop = mysql_result($result_drop,0,"COUNT(*)");	
			echo "$recsinaccdrop - ";
			if ($recsinknightdrop == $recsinaccdrop)
			{	echo "Tables are O.K.";	}
			else
			{	echo "<font color=\"#ff0000\">don't match.  Correcting table.</font>";
				if (!mysql_query("insert ignore into $dblog_l2jdb.knightdrop (name, access_level) select login, accessLevel from $dblog_l2jdb.accounts",$con2))
				{	echo "</p><p>Couldn't import the logonserver accounts into the knightdrop table!!!<br>" . mysql_error();	}
			}
		}
		else
		{	echo "- <font color=\"#ff0000\">failed to read accounts table</font>";	}
	}
	else
	{	echo "- <font color=\"#ff0000\">failed to read knightdrop table</font>";	}
	echo "</strong></p>";
}

echo "<hr><p><strong>Checking Gameserver databases</strong></p><hr>";

	$g_array_count = count($gameservers);
	$i = 0;
	while ($i < $g_array_count)
	{
		$server_title = $gameservers[$i][0];
		$db_location = $gameservers[$i][1];
		$db_l2jdb = $gameservers[$i][2];	
		$db_user = $gameservers[$i][3];
		$db_psswd = $gameservers[$i][4];
		$knight_db = $gameservers[$i][8];
		$defaultskin = $gameservers[$i][5];
		echo "<p><strong>Checking gameserver $server_title</strong></p>";
		$ds = strlen($defaultskin);
		if ($ds > 2)
		{	echo "<p><font color=\"#ff0000\">Default skin reads - $defaultskin - should be a number.</font></p>";	}
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightduplicate',ARRAY('item','dupnum','orig'));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightitemdesc',ARRAY('id','description'));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightloc',ARRAY('name','x','y'));
		$knightrectable = check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightrecch',ARRAY('rec_name','rec_id','rec_item','level','makes','chance','multiplier'));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightrecipe',ARRAY('rec_id','makes','item','qty'));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightsettings',ARRAY('register_allow', 'guest_allow', 'guest_user_maps', 'low_graphic_allow', 'db_tokenexp',
			'sec_inc_gmlevel', 'sec_inc_admin', 'sec_giveandtake', 'sec_takeskill', 'sec_chatto', 'adjust_anounce', 'kick_player',
			'adjust_trust', 'reboot_server', 'adjust_shop', 'adjust_drops', 'sec_adj_notes', 'account_safe', 'guest_dropthru',
			'gm_play_show', 'max_acc_length', 'max_pass_length', 'all_users_maps', 'all_users_recipe', 'all_newusers_maps', 
			'all_newusers_recipe', 'recipe_depth', 'all_newusers_character', 'all_users_character', 'l2version',
			'drop_chance_adena', 'drop_chance_item', 'drop_chance_spoil', 'drop_chance_green', 'drop_chance_blue', 'sevensignall', 
			'top_ten', 'tten_number', 'tten_level', 'tten_pk', 'tten_pvp', 'tten_karma', 'tten_time', 'max_chars_per_acc', 
			'game_paranoia', 'guest_nosee_clanchars', 'check_boxing', 'emergency_teleport', 'prevent_cross_clan',
			'bot_scan_ban', 'auto_prune', 'display_country', 'stopbanIPreg', 'emailfrom', 'phpsmtp', 'enchntgmaccallow',
			'map_item_status', 'map_item_id', 'map_item_when', 'map_item_online', 'rec_item_status', 'rec_item_id',
			'rec_item_when', 'rec_item_online', 'char_item_status', 'char_item_id', 'char_item_when', 'char_item_online',
			'use_duplicate', 'hide_language', 'hide_server', 'hide_skin', 'user_change_server', 'user_change_skin', 'smtpdebug',
			'force_default_skin', 'show_online', 'default_lang', 'show_map', 'map_nudge', 'showmobpict', 'allowpassreset', 'menushowchars',
			'smtpserver', 'smtpport', 'smtptimeout', 'smtpusername', 'smtppassword', 'smtplocalhost', 'emailcheck', 'gd_compress',
			'minlenitem', 'minlenchar', 'minlenclan', 'minlenmobs', 'minlenacc', 'minlenloc', 'minlenrec', 'gd_on', 'gd_style', 'gd_srvon',
			'whosonlinegmlow', 'show_char_time', 'show_detail_char_time', 'delay_whosonline', 'delay_chat', 'delay_logs', 'gmintopten', 'php_type',
			'log_actions', 'log_duration' ));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightstats',ARRAY('date','hour','period','maxplayers','count','reports'));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knighttrust',ARRAY('account_name','char_name','level','race','class'));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightskills',ARRAY('skill_id','name'));
		check_table($db_location,$db_user,$db_psswd,$db_l2jdb,'knightskillmod',ARRAY('id','textident','textfrom','textto'));

		//check the knightdb database
		echo "<p><strong>Checking Knight Work database $knight_db</strong></p>";
		$con = mysql_connect($db_location,$db_user,$db_psswd);
		if (!mysql_select_db("$knight_db",$con))
		{	echo "<p><font color=\"#ff0000\">Can not select database $knight_db on $db_location</font><br>Run the following...</p><pre>create $knight_db;
CREATE TABLE `errors` (                 
          `error_text` text,                    
          `reason` text                         
        ); 
create table restartlog (                 
          `lines` text                         
        );
CREATE TABLE `accnotes` (                      
          `charname` varchar(45) NOT NULL default '',  
          `notenum` int(5) NOT NULL default '0',       
          `notemaker` varchar(50) default NULL,        
          `note` text,                                 
          PRIMARY KEY  (`charname`,`notenum`)          
        );</pre>";	}
		else
		{	if (!check_table($db_location,$db_user,$db_psswd,$knight_db,'errors',ARRAY('error_text','reason')))
			{	echo "<pre>use $knight_db;
CREATE TABLE `errors` (                 
          `error_text` text,                    
          `reason` text                         
        ); </pre>";	}
			if (!check_table($db_location,$db_user,$db_psswd,$knight_db,'restartlog',ARRAY('lines')))
			{	echo "<pre>use $knight_db;
create table restartlog (                 
          `lines` text                         
        ); </pre>";	}
			if (!check_table($db_location,$db_user,$db_psswd,$knight_db,'itemlog',ARRAY('object_id','index_id','timestamp','owner_id','enchant_level','this_run')))
			{	echo "<pre>use $knight_db;
create table itemlog (                      
	`object_id` int(11) NOT NULL default '0',  
	`index_id` int(11) NOT NULL default '0',  
	`timestamp` int(11) NOT NULL default '0', 
	`owner_id` int(11) default NULL,                 
	`enchant_level` int(11) default NULL, 
	`this_run` int(2) NOT NULL default '0',  
            PRIMARY KEY  (`object_id`,`index_id`)          
          );</pre>";	}
			if (!check_table($db_location,$db_user,$db_psswd,$knight_db,'accnotes',ARRAY('charname','notenum','notemaker','note')))
			{	echo "<pre>use $knight_db;
CREATE TABLE `accnotes` (                      
            `charname` varchar(45) NOT NULL default '',  
            `notenum` int(5) NOT NULL default '0',       
            `notemaker` varchar(50) default NULL,        
            `note` text,                                 
            PRIMARY KEY  (`charname`,`notenum`)          
          );</pre>";	}
		}
		
		$con = mysql_connect($db_location,$db_user,$db_psswd);
		if (!mysql_select_db("$db_l2jdb",$con))
		{	die('Wrap_start could not change to gameserver database: $dblog_l2jdb on server $dblog_location with credentials supplied<br>' . mysql_error());	}
		echo "<p><strong>Checking Knightskills population - skills ";
		$result_drop = mysql_query("select COUNT(*) from $db_l2jdb.knightskills",$con);
		if ($result_drop)
		{	
			$recsinrecdrop = mysql_result($result_drop,0,"COUNT(*)");	
			echo "$recsinrecdrop - ";
			if ($recsinrecdrop > 100)
			{	echo "Tables are O.K.";	}
			else
			{	echo "<font color=\"#ff0000\">not correct.  Have you imported skills?</font>";	}
		}
		else
		{	echo "- <font color=\"#ff0000\">failed to read knightskills table</font>";	}
		if ($knightdroptable)
		{
			echo "<p><strong>Checking Knightrecch population - recipes ";
			$con = mysql_connect($db_location,$db_user,$db_psswd);
			if (!mysql_select_db("$db_l2jdb",$con))
			{	die('Wrap_start could not change to logserver database: $dblog_l2jdb on server $dblog_location with credentials supplied<br>' . mysql_error());	}
			$result_drop = mysql_query("select COUNT(*) from $db_l2jdb.knightrecch",$con);
			if ($result_drop)
			{	
				$recsinrecdrop = mysql_result($result_drop,0,"COUNT(*)");	
				echo "$recsinrecdrop - ";
				if ($recsinrecdrop > 100)
				{	echo "Tables are O.K.";	}
				else
				{	echo "<font color=\"#ff0000\">not correct.  Have you imported recipes?</font>";	}
			}
			else
			{	echo "- <font color=\"#ff0000\">failed to read knightrecch table</font>";	}
			echo "</strong></p>";
		}
		
		echo "<hr>";
		$i++;
	}
?>
