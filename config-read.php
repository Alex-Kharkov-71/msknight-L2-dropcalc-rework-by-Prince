
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


// Create control system database and record the tables.
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

	$sql = "select * from knightsettings";
	$result_set = mysql_query($sql,$con);
	if (!$result_set)
	{	
		echo "<p class=\"popup\">Couldn't read the knightsettings table!!!</p>";
	}
	
	$register_allow = mysql_result($result_set,0,"register_allow");
	$guest_allow = mysql_result($result_set,0,"guest_allow");
	$guest_user_maps = mysql_result($result_set,0,"guest_user_maps");
	$low_graphic_allow = mysql_result($result_set,0,"low_graphic_allow");
	$db_tokenexp = mysql_result($result_set,0,"db_tokenexp");
	$sec_inc_gmlevel = mysql_result($result_set,0,"sec_inc_gmlevel");
	$sec_inc_admin = mysql_result($result_set,0,"sec_inc_admin");
	$sec_giveandtake = mysql_result($result_set,0,"sec_giveandtake");
	$sec_takeskill = mysql_result($result_set,0,"sec_takeskill");
	$sec_enchant = mysql_result($result_set,0,"sec_enchant");
	$sec_chatto = mysql_result($result_set,0,"sec_chatto");
	$adjust_anounce = mysql_result($result_set,0,"adjust_anounce");
	$kick_player = mysql_result($result_set,0,"kick_player");
	$adjust_trust = mysql_result($result_set,0,"adjust_trust");
	$reboot_server = mysql_result($result_set,0,"reboot_server");
	$adjust_shop = mysql_result($result_set,0,"adjust_shop");
	$adjust_drops = mysql_result($result_set,0,"adjust_drops");
	$sec_adj_notes = mysql_result($result_set,0,"sec_adj_notes");
	$account_safe = mysql_result($result_set,0,"account_safe");
	$guest_dropthru = mysql_result($result_set,0,"guest_dropthru"); 
	$gm_play_show = mysql_result($result_set,0,"gm_play_show");
	$max_acc_length = mysql_result($result_set,0,"max_acc_length");
	$max_pass_length = mysql_result($result_set,0,"max_pass_length");
	$all_users_maps = mysql_result($result_set,0,"all_users_maps");
	$all_users_recipe = mysql_result($result_set,0,"all_users_recipe");
	$all_newusers_maps = mysql_result($result_set,0,"all_newusers_maps");
	$all_newusers_recipe = mysql_result($result_set,0,"all_newusers_recipe");
	$recipe_depth = mysql_result($result_set,0,"recipe_depth");
	$all_newusers_character = mysql_result($result_set,0,"all_newusers_character");
	$all_users_character = mysql_result($result_set,0,"all_users_character");
	$l2version = mysql_result($result_set,0,"l2version");
	$drop_chance_adena = mysql_result($result_set,0,"drop_chance_adena");
	$drop_chance_item = mysql_result($result_set,0,"drop_chance_item");
	$drop_chance_spoil = mysql_result($result_set,0,"drop_chance_spoil");
	$drop_chance_green = mysql_result($result_set,0,"drop_chance_green");
	$drop_chance_blue = mysql_result($result_set,0,"drop_chance_blue"); 
	$sevensignall = mysql_result($result_set,0,"sevensignall");
	$top_ten = mysql_result($result_set,0,"top_ten");
	$tten_number = mysql_result($result_set,0,"tten_number");
	$tten_level = mysql_result($result_set,0,"tten_level");
	$tten_pk = mysql_result($result_set,0,"tten_pk");
	$tten_pvp = mysql_result($result_set,0,"tten_pvp");
	$tten_karma = mysql_result($result_set,0,"tten_karma");
        $tten_fame = mysql_result($result_set,0,"tten_fame");
	$tten_time = mysql_result($result_set,0,"tten_time");
	$max_chars_per_acc = mysql_result($result_set,0,"max_chars_per_acc");
	$game_paranoia = mysql_result($result_set,0,"game_paranoia");
	$guest_nosee_clanchars = mysql_result($result_set,0,"guest_nosee_clanchars"); 
	$check_boxing = mysql_result($result_set,0,"check_boxing");
	$emergency_teleport = mysql_result($result_set,0,"emergency_teleport"); 
	$prevent_cross_clan = mysql_result($result_set,0,"prevent_cross_clan");
	$bot_scan_ban = mysql_result($result_set,0,"bot_scan_ban");
	$auto_prune = mysql_result($result_set,0,"auto_prune");
	$display_country = mysql_result($result_set,0,"display_country");
	$stopbanIPreg = mysql_result($result_set,0,"stopbanIPreg");
	$map_item_status = mysql_result($result_set,0,"map_item_status");
	$map_item_id = mysql_result($result_set,0,"map_item_id");
	$map_item_when = mysql_result($result_set,0,"map_item_when");
	$map_item_online = mysql_result($result_set,0,"map_item_online");
	$rec_item_status = mysql_result($result_set,0,"rec_item_status");
	$rec_item_id = mysql_result($result_set,0,"rec_item_id");
	$rec_item_when = mysql_result($result_set,0,"rec_item_when");
	$rec_item_online = mysql_result($result_set,0,"rec_item_online");
	$char_item_status = mysql_result($result_set,0,"char_item_status");
	$char_item_id = mysql_result($result_set,0,"char_item_id");
	$char_item_when = mysql_result($result_set,0,"char_item_when");
	$char_item_online = mysql_result($result_set,0,"char_item_online");
	$use_duplicate = mysql_result($result_set,0,"use_duplicate");
	$default_lang_c = mysql_result($result_set,0,"default_lang");
	$whosonlinegmlow = mysql_result($result_set,0,"whosonlinegmlow");
	$show_char_time = mysql_result($result_set,0,"show_char_time");
	$show_detail_c_time = mysql_result($result_set,0,"show_detail_char_time");
	$delay_whosonline = mysql_result($result_set,0,"delay_whosonline");
	$delay_chat = mysql_result($result_set,0,"delay_chat");
	$delay_logs = mysql_result($result_set,0,"delay_logs");
	$gmintopten = mysql_result($result_set,0,"gmintopten");
	$show_map = mysql_result($result_set,0,"show_map");
	$map_nudge = mysql_result($result_set,0,"map_nudge");
	$showmobpict = mysql_result($result_set,0,"showmobpict");
	$enchntgmaccallow = mysql_result($result_set,0,"enchntgmaccallow");
	$minlenitem = mysql_result($result_set,0,"minlenitem");
	$minlenchar = mysql_result($result_set,0,"minlenchar");
	$minlenclan = mysql_result($result_set,0,"minlenclan");
	$minlenmobs = mysql_result($result_set,0,"minlenmobs");
	$minlenacc = mysql_result($result_set,0,"minlenacc");
	$minlenloc = mysql_result($result_set,0,"minlenloc");
	$minlenrec = mysql_result($result_set,0,"minlenrec");
	$log_action = mysql_result($result_set,0,"log_actions");
	$log_duration = mysql_result($result_set,0,"log_duration");
	$clog_type = mysql_result($result_set,0,"chat_style");
	$default_lang = 0;
	$i = 0;
	$l_array_count = count($language_array);
	while ($i < $l_array_count)
	{
		$language_entry = $language_array[$i];
		$language_file = $language_entry[1];
		if ($default_lang_c == $language_file)
		$default_lang = $i;
		$i++;
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
	
	$hide_language = mysql_result($result_set,0,"hide_language");
	$hide_server = mysql_result($result_set,0,"hide_server");
	$hide_skin = mysql_result($result_set,0,"hide_skin");
	$user_change_server = mysql_result($result_set,0,"user_change_server");
	$user_change_skin = mysql_result($result_set,0,"user_change_skin");
	$force_default_skin = mysql_result($result_set,0,"force_default_skin");
	$show_online = mysql_result($result_set,0,"show_online");
	$php_type = mysql_result($result_set,0,"php_type");
	$e_mail_from = mysql_result($result_set,0,"emailfrom");
	$phpsmtp = mysql_result($result_set,0,"phpsmtp");
	$smtpserver = mysql_result($result_set,0,"smtpserver");
	$smtpport = mysql_result($result_set,0,"smtpport");
	$smtptimeout = mysql_result($result_set,0,"smtptimeout");
	$smtpuser = mysql_result($result_set,0,"smtpusername");
	$smtppassword = mysql_result($result_set,0,"smtppassword");
	$smtplocalhost = mysql_result($result_set,0,"smtplocalhost");
	$smtp_debug = mysql_result($result_set,0,"smtpdebug");
	$allowpassreset = mysql_result($result_set,0,"allowpassreset");
	$emailcheck = mysql_result($result_set,0,"emailcheck");
	$gdon = mysql_result($result_set,0,"gd_on");
	$gdstyle = mysql_result($result_set,0,"gd_style");
	$gdsrvon = mysql_result($result_set,0,"gd_srvon");
	$gdcompress = mysql_result($result_set,0,"gd_compress");
	$menushowchars = mysql_result($result_set,0,"menushowchars");
?>