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
$itemname = input_check($_REQUEST['itemname'],0);
$itemid = input_check($_REQUEST['itemid'],0);
$itemsort = input_check($_REQUEST['itemsort'],0);
$spec_account = input_check($_REQUEST['account'],1);
$show_hidden = input_check($_REQUEST['showhidden'],0);
$force_all = input_check($_REQUEST['forceall'],0);
$esearch = input_check($_REQUEST['esearch'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	// Open up necessary database connections.
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "Could Not Connect";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Wrap_start could not change to logserver database: ' . mysql_error());	}
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{	die('Could not change to L2J database: ' . mysql_error());	}

	// If we are running under a paranoia level, the user isn't a GM and the account requested isn't the users own account, then terminate.
	if (($game_paranoia) && ($user_access_lvl < $sec_inc_gmlevel))
	{
		if ($username != $itemname)
		{
			writewarn("Sorry, Admin has disabled account view.");
			return 0;
		}
		else
		{	$spec_account = $username;	}
	}
	
	// Try a search for the requested account name in the database.  If we are running an IP check, then pull out all that match that accounts IP.
	if ((!$spec_account) && (strlen($itemname) < $minlenacc))
	{	
		writewarn("Please give at least $minlenacc characters.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	if ($esearch)
	{
		$sql = "select name from $dblog_l2jdb.knightdrop where email like '%$itemname%'";
		$result = mysql_query($sql,$con2);
		$count = mysql_num_rows($result);
		if ($count)
		{
			$i = 0;
			$acc_list = "(";
			while ($i < $count)
			{
				$gm_id = mysql_result($result,$i,"name");	
				$acc_list = $acc_list . "'" . $gm_id . "'";
				$i++;
				if ($i < $count)
				{	$acc_list = $acc_list . ", ";	}
			}
			$acc_list = $acc_list . ")";
			$sql = "select login, lastactive, lastIP, accessLevel from $dblog_l2jdb.accounts where login in $acc_list order by lastactive DESC";	
		}
		else
		{
			$sql = "select login, lastactive, lastIP, accessLevel from $dblog_l2jdb.accounts where login = '' order by lastactive DESC";	
		}
	}
	else
	{
		$sql = "select login, lastactive, lastIP, accessLevel from $dblog_l2jdb.accounts where login like '%$itemname%' order by lastactive DESC";	
		if ((!$spec_account) && (!$force_all) && (strlen($itemname) == 0))
		{	$sql = "select login, lastactive, lastIP, accessLevel from $dblog_l2jdb.accounts where login like '%$itemname%' order by lastactive DESC limit 40";	}
	}
	if ($spec_account == 'ipcheck')
	{
		$sql = "select login, lastactive, lastIP, accessLevel from $dblog_l2jdb.accounts where lastIP = '$itemname' order by lastactive DESC";
		$spec_account = "";
	}
	if (!$result = mysql_query($sql,$con2))
	{	die('Could not retrieve from knightdrop database: ' . mysql_error());	}
	// If return array empty, then username not found.
	$row = mysql_fetch_array($result);
	$count_accs = mysql_num_rows($result);
	if (!$row)
	{
		writewarn("Sorry, no user accounts match $itemname");
		return 0;
	}
	
	// Try and find clans that are linked to the users account characters.
	$clan_member_count = 0;
	if ((!$user_game_acc) && ($username != "guest"))
	{	echo "<h2 class=\"dropmain\">Warning - Drop calc doesn't know your game account</h2>"; }
	else
	{
		$result_clan = mysql_query("select distinct clanid from characters where account_name = '$user_game_acc'",$con);
		if (!$result_clan)
		{	echo "<h2 class=\"dropmain\">Warning - None of your characters are in clans</h2>"; }
		else
		{
			while ($r_array = mysql_fetch_assoc($result_clan))
			{
				$clan_res = $r_array['clanid'];
				$char_clan_list[$clan_member_count] = $clan_res;
				$clan_member_count++;
			}
		}
	}
	echo "<p class=\"dropmain\">&nbsp</p>";
	
	// If we are dealing with a specific account, then retrieve the search info for that account.
	if ($spec_account)
	{
		$result = mysql_query("select login, lastactive, lastIP, accessLevel from $dblog_l2jdb.accounts where login = '$spec_account'",$con2);
		$count_accs = mysql_num_rows($result);
	}
	if (!$count_accs)
	{
		writewarn("Sorry, no user accounts match $spec_account");
		return 0;
	}

	// Display the control button for GM's so they can see accounts which have no characters against them.
	if (($user_access_lvl >= $sec_inc_gmlevel) && ($account_safe))
	{
		if ($show_hidden)
		{	echo "<center><table><tr><td class=\"blanktab\"><form method=\"post\" action=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&itemid=$itemid&itemsort=$itemsort&account=$spec_account\"><input value=\" Hide No-Character Accounts \" type=\"submit\" class=\"bigbut2\"></form></td></tr></table></center>\n";	}
		else
		{	echo "<center><table><tr><td class=\"blanktab\"><form method=\"post\" action=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&itemid=$itemid&itemsort=$itemsort&account=$spec_account&showhidden=yes\"><input value=\" Show No-Character Accounts \" type=\"submit\" class=\"bigbut2\"></form></td></tr></table></center>\n";	}
	}

	if ((!$spec_account) && (!$force_all) && (strlen($itemname) == 0))
	{	 echo "<table><tr><td class=\"blanktab\"><form method=\"post\" action=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$itemname&itemid=$itemid&itemsort=$itemsort&account=$spec_account&forceall=1\"><input value=\" Show more than top 40 accounts \" type=\"submit\" class=\"bigbut2\"></form></td></tr></table>\n";	}
	
	// Search resultant list of accounts.
	mysql_data_seek($result,0);
	while ($r_array = mysql_fetch_assoc($result))
	{
		$acc_name = $r_array['login'];
		$a_a_id = substr(base64_encode(pack("H*", sha1(utf8_encode($acc_name)))), 0, 3);
		$acc_lastlogon = $r_array['lastactive'] / 1000;
		$acc_llogon = date('dS F Y \- h:iA',$acc_lastlogon);
		$acc_ip = $r_array['lastIP'];
		$acc_name2 = strtoupper($acc_name);
		$acc_lvl = $r_array['accessLevel'];
		
		// Search characters for any that match to the users account.
		$result2 = mysql_query("select charId, account_name, char_name, classid, clanid, level, sex, maxhp, curhp, maxcp, curcp, maxmp, curmp, accesslevel, online, onlinetime from characters where account_name = \"$acc_name\" order by char_name",$con);
		$count_res = 0;
		$c_access = 0;
		$c_onl = 0;
		$a_num = count($result2);
		while ($r_array = mysql_fetch_assoc($result2))  // Go through the characters accounts and total the number of character access levels and if any are online.
		{
			$c_alvl = $r_array['accesslevel'];
			$c_online = $r_array['online'];
			$c_access = $c_access + $c_alvl;
			$c_onl = $c_onl + $c_online;
			$num = mysql_num_rows($result2);
			$count_res++;
		}
		if ($count_res)
		{	mysql_data_seek($result2,0);	}

		
		// If the account has characters to it, or account safe is off, or we are otherwise specifically instructed to show accs with no characters ...
		if (($count_res > 0) || ($account_safe == 0) || ($show_hidden)  || (($user_access_lvl >= $sec_inc_gmlevel) && ($spec_account)))
		{
			// Pull back the warning level for the account
			$result_class = mysql_query("select warnlevel, email from $dblog_l2jdb.knightdrop where name = '$acc_name2'",$con2);
			$warn_level = mysql_result($result_class,0,"warnlevel");
			$a_email = mysql_result($result_class,0,"email");
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" class=\"dropmain\"><tr><td class=\"dropmain\"><strong class=\"dropmain\"><font color=$blue_code>$acc_name2";
			// If the user is a GM, then show the warning level, and ascertain if there are any notes against the account.
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{ 
				if ($show_char_time)
				{
					$result4 = mysql_query("select sum(onlinetime) from $db_l2jdb.characters where account_name = '$acc_name2'",$con2);
					$acc_onlinetime = mysql_result($result4,0,"sum(onlinetime)");
					$onlinetime = onlinetime($acc_onlinetime);
					echo "&nbsp;<small><font color=\"$white_code\">($onlinetime)</font></small>";
				}
				echo "&nbsp($acc_lvl&nbsp;-&nbsp;$a_a_id)"; 
				if ($warn_level)
				{	echo "</font>&nbsp;&nbsp;<font color=$red_code>(&nbsp;$warn_level&nbsp;)";	}
				if ($result_note = mysql_query("select count(*) from $knight_db.accnotes where charname = '$acc_name2'",$con2))
				{
					$count_notes = mysql_result($result_note,0,"count(*)");
					if ($count_notes > 0)
					{	echo "&nbsp;<a href=\"acc-notes.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$acc_name2\"><font color=$yellow_code>[-$count_notes-]</font></a>";	}
				}
			}
			echo "</font></strong>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{ echo "<p class=\"dropmain\"><small>$a_email</small></p>";	}
			echo "</td>";
			
			// Display the account details, and if the user is a GM, include the last known IP address and a link to a DNS lookup.
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{ echo "<td colspan=\"11\" class=\"dropmain\">"; }
			else
			{ echo "<td colspan=\"10\" class=\"dropmain\">"; }
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"blanktab\" width=\"100%\"><tr><td class=\"blanktab\"><p class=\"dropmain\"><strong class=\"dropmain\">";
			if ($acc_lvl < 0)
			{	echo "<font color=$red_code>Banned&nbsp;&nbsp;</font>";	}
			echo "<font color=$blue_code>$lang_lastlogon -</font></strong> <strong class=\"dropmain\">$acc_llogon</strong>";
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{	echo "&nbsp;&nbsp;&nbsp; IP&nbsp;-&nbsp;$acc_ip";	
				echo "&nbsp;&nbsp;<a href=\"http://www.dnsstuff.com/tools/whois.ch?ip=$acc_ip\" target=\"new\">(dnsstuff)</a></p>";	
			}  // GM's get to see the users last known IP.

			// If the user is a GM, then add the control buttons for the account editing options.
			if ($user_access_lvl >= $sec_inc_gmlevel)
			{
				echo "</tr><tr><td><table class=\"blanktab\"><tr>";
				if ((($acc_lvl < $sec_inc_gmlevel) && ($user_access_lvl >= $kick_player)) || ($user_access_lvl >= $sec_inc_admin))
				{
					if ($acc_lvl >= 0)
					{	echo "<td><form method=\"post\" action=\"javascript:popit('bana.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$acc_name','400','130');\"><input value=\" Ban Account \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
					elseif ($acc_lvl == -1)
					{	echo "<td><form method=\"post\" action=\"javascript:popit('bana.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$acc_name&unban=1','400','130');\"><input value=\" Un-Ban Account \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
				}
				echo "<td><form method=\"post\" action=\"a-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemname=$acc_ip&itemid=$itemid&itemsort=$itemsort&account=ipcheck&showhidden=$show_hidden\"><input value=\" View Same IP \" type=\"submit\" class=\"bigbut2\"></form></td>";			
				echo "<td><form method=\"post\" action=\"acc-change.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$acc_name\"><input value=\" Edit Account \" type=\"submit\" class=\"bigbut2\"></form></td>";			
				echo "<td><form method=\"post\" action=\"acc-notes.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&accountname=$acc_name\"><input value=\" Notes \" type=\"submit\" class=\"bigbut2\"></form></td>";			
				if ($user_access_lvl >= $sec_inc_admin)
				{	echo "<td><form method=\"post\" action=\"javascript:popit('ip-legit.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&ip=$acc_ip','400','130');\"><input value=\" IP Box OK \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
				if ($user_access_lvl >= $sec_inc_admin)
				{	echo "<td><form method=\"post\" action=\"javascript:popit('export.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&account=$acc_name&title=acc_$acc_name','100','50');\"><input value=\" Export Account \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
				echo "</tr></table></td>";
			}

			echo "</tr></table></td></tr>\n";
			if (!$count_res)
			{
				// If no characters, then display the message (with the extra column for the GM option.
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<tr><td colspan=\"12\" class=\"dropmain\"><p class=\"dropmain\"><strong class=\"dropmain\">No Active Characters</strong></p></td></tr>\n"; }
				else
				{ echo "<tr><td colspan=\"11\" class=\"dropmain\"><p class=\"dropmain\"><strong class=\"dropmain\">No Active Characters</strong></p></td></tr>\n"; }
			}
			else
			{	// ... but if there are characters, then display the column headings.
				echo "<tr><td class=\"drophead\"><p class=\"left\"><strong class=\"dropmain\">Char Name</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">On?</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Lvl</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Race</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Sex</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">$lang_class</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">$lang_clan</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Hp</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Mp</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Cp</strong></p></td>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Acc Lvl</strong></p></td>"; }
				echo "</tr>\n";
			}
			$a_num = count($result2);
			while ($r_array = mysql_fetch_assoc($result2))  // Go through the characters accounts for each that matches the player account.
			{
				$c_num = $r_array['charId'];
				$c_accname = $r_array['account_name'];
				$c_name = $r_array['char_name'];
				$c_class = $r_array['classid'];
				$c_clanid = $r_array['clanid'];
				$c_level = $r_array['level'];
				$c_sex = $r_array['sex'];
				$c_mhp = $r_array['maxhp'];
				$c_chp = $r_array['curhp'];
				$c_mmp = $r_array['maxmp'];
				$c_cmp = $r_array['curmp'];
				$c_mcp = $r_array['maxcp'];
				$c_ccp = $r_array['curcp'];
				$c_alvl = $r_array['accesslevel'];
				$c_online = $r_array['online'];
				$c_onlinetime = $r_array['onlinetime'];
				$c_race_n = "$lang_unknown";
				$c_class_n = "$lang_unknown";
				$c_class_s = "Unkonwn";
				$result_class = mysql_query("select class_name from class_list where id = $c_class",$con);
				$class_count =  mysql_num_rows($result_class);
				if ($class_count >0)
				{	$c_class_s = mysql_result($result_class,0,"class_name");	}
				if (substr($c_class_s,0,2) == "H_")
				{
					$c_race_n = "$lang_human";				// Calculte the characters class.
					$c_class_n = substr($c_class_s,2);
				}
				elseif (substr($c_class_s,0,2) == "E_")
				{
					$c_race_n = "$lang_elf";
					$c_class_n = substr($c_class_s,2);
				}
				elseif (substr($c_class_s,0,3) == "DE_")
				{
					$c_race_n = "$lang_delf";
					$c_class_n = substr($c_class_s,3);
				}
				elseif (substr($c_class_s,0,2) == "O_")
				{
					$c_race_n = "$lang_orc";
					$c_class_n = substr($c_class_s,2);
				}
				elseif (substr($c_class_s,0,2) == "D_")
				{
					$c_race_n = "$lang_dwarf";
					$c_class_n = substr($c_class_s,2);
				}
				elseif (substr($c_class_s,0,2) == "K_")
				{
					$c_race_n = "$lang_kamael";
					$c_class_n = substr($c_class_s,2);
				}
				if (!$c_clanid)
				{ $c_clan_n = "$lang_none"; }
				else
				{
					$result_clan = mysql_query("select clan_id, clan_name from clan_data where clan_id = $c_clanid",$con);
					if (!result_clan)
					{	$c_clan_n = "$lang_unknown"; }
					else 
					{ $c_clan_n = mysql_result($result_clan,0,"clan_name"); }
				}
				echo "<tr><td class=\"dropmain\"><p class=\"left\">";
	
				if (($user_access_lvl >= $sec_inc_gmlevel) && ($show_char_time))  // If user is a GM, always show the character link.
				{	echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&charnum=$c_num\" class=\"dropmain\">$c_name</a>";	
					$onlinetime = onlinetime($c_onlinetime);
					echo "&nbsp;<small><font color=\"$white_code\">($onlinetime)</font></small>";
				}
				elseif (($user_access_lvl < $sec_inc_gmlevel) && (($clan_member_count) || ($c_accname == $user_game_acc))) // If user is not admin, but is a member of clans, show links where is a member.
				{
					if ($c_accname == $user_game_acc)
					{	$found = 1;	}
					else
					{	$found = 0;	}
					$i3 = 0;
					while ($i3 < $clan_member_count)
					{
						if ($c_clanid == $char_clan_list[$i3])
						{	$found = 1;	}
						$i3++;
					}
					if ($found)
					{ echo "<a href=\"c-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&charnum=$c_num\" class=\"dropmain\">$c_name</a>";	}
					else 
					{	echo "$c_name";	}
				}
				else
				{	echo "$c_name";	}
				echo "</p></td><td class=\"dropmain\"><p class=\"center\">";
				if ($c_alvl < 0)
				{	echo "<font color=$red_code>Banned</font>";	}
				elseif ($c_online)
				{ echo "<font color=$green_code>Yes"; }
				else
				{ echo "<font color=$red_code>No"; }
				echo "</font></p></td><td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\"><font color=$green_code>$c_level</font></strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_race_n</p></td><td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">";
				if ($c_sex)
				{	echo "<img src=\"" . $images_dir . "female.gif\" width=\"10\" height=\"14\" border=\"0\">"; }
				else
				{	echo "<img src=\"" . $images_dir . "male.gif\" width=\"10\" height=\"14\" border=\"0\">"; }
				echo "</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_class_n</p></td><td class=\"dropmain\"><p class=\"center\">";
				if ($c_clan_n == "$lang_none")
				{ echo "$c_clan_n"; }
				else
				{
					if (($user_access_lvl >= $sec_inc_gmlevel) && ($c_clanid > 0))  // If user is a GM, always show the clan link.
					{	echo "<a href=\"cl-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&clannum=$c_clanid\" class=\"dropmain\">$c_clan_n</a>";	}
					if (($user_access_lvl < $sec_inc_gmlevel) && ($clan_member_count)) // If user is not admin, but is a member of clans, show links where is a member.
					{
						if ($c_accname == $user_game_acc)
						{	$found = 1;	}
						else
						{	$found = 0;	}

						$i3 = 0;
						while ($i3 < $clan_member_count)
						{
							if ($c_clanid == $char_clan_list[$i3])
							{	$found = 1;	}
							$i3++;
						}
						if ($found)
						{ echo "<a href=\"cl-search.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&clannum=$c_clanid\" class=\"dropmain\">$c_clan_n</a>";	}
						else 
						{	echo "$c_clan_n";	}
					}
					elseif ((!$clan_member_count) && ($user_access_lvl < $sec_inc_gmlevel))	// If user not member of clans, just show the name
					{	echo "$c_clan_n";	}
				}
				echo "</p></td><td class=\"dropmain\"><p class=\"center\">$c_chp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mhp</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_cmp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mmp</strong></p></td><td class=\"dropmain\"><p class=\"center\">$c_ccp&nbsp;/&nbsp;<strong class=\"dropmain\">$c_mcp</strong></p></td>";
				if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$c_alvl</p></td>"; }
				echo "</tr>\n";
			}
			echo "</table></center><br>\n";
		}
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
