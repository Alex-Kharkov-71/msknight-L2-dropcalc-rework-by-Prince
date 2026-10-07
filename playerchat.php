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


/* SERVER NOTES
The system will need to create a directory in order to store and manipulate the chat files.
To do this ...
1) Use a browser to run the chat watch script recursively.
2) While the script is running, allow the "other write" bit on your log directory.
3) Once it has created the knightchat directory, you can remove the "other write" bit.
*/

include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$lastlines = input_check($_REQUEST['lastlines'],0);
$character = input_check($_REQUEST['character'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.


if ($lastlines)
{
	$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $delay_chat, "playerchat.php?=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1&character=$character#bottomline", $low_graphic_allow, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
}
else
{
	$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", $low_graphic_allow, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
}

if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_chatto)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
		if ($bot_scan_ban)
		{	bot_scan($username, $token, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps, $telnet_host, $telnet_port, $telnet_timeout, $telnet_password);	}
	
		$dir_loc = $server_dir . 'log' . $svr_dir_delimit . 'knightchat';
		if (!is_dir($dir_loc))
		{	mkdir($dir_loc, 0700);	}

		$file_loc = $server_dir . 'log' . $svr_dir_delimit . 'knightchat' . $svr_dir_delimit . $character .'-chat.log';

		$command_string = "grep -i -e '\[$character ' -e ' $character\]' -e '\[$character\]' ";
		$command_string = $command_string . $server_dir . 'log' . $svr_dir_delimit  . 'chat.log';

		if ($lastlines)
		{
			$command_string = $command_string . ' | tail -60';
		}
		$command_string = $command_string . ' > ' . $server_dir . 'log' . $svr_dir_delimit . 'knightchat' . $svr_dir_delimit . $character .'-chat.log';
		$output = shell_exec($command_string);
		

		$lines = file($file_loc);
		$line_nums = count($lines);
		echo "<pre class=\"dropmain\">";
		if (!$lastlines)
		{
			echo "<form method=\"post\" action=\"playerchat.php\"><input value=\" <- View Last Lines -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"1\"><input name=\"character\" type=\"hidden\" value=\"$character\"></form>";
			foreach ($lines as $line_num => $line) 
			{
				$line = trim($line);
				if ($clog_type == 1)
				$line = colourise($line, $skin_dir, $svr_dir_delimit);
				echo "$line<br>";	
			}
		}
		else
		{
			echo "<form method=\"post\" action=\"playerchat.php\"><input value=\" <- View Whole File -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"character\" type=\"hidden\" value=\"$character\"></form>";
			foreach ($lines as $line_num => $line) 
			{
				$line = trim($line);
				if ($clog_type == 1)
				$line = colourise($line, $skin_dir, $svr_dir_delimit);
				echo "$line<br>";	
			}

		}
		echo "</pre><table class=\"dropmain\"><tr><td class=\"noborder\"><p class=\"dropmain\">Last update - ";
		echo date('l dS \of F Y h:i:s A');
		echo "</td><td class=\"noborder\" valign=\"center\" align=\"right\" width=\"300\"><form method=\"post\" action=\"javascript:popit('chattop.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$character','470','130');\"><input value=\" Chat with player \" type=\"submit\" class=\"bigbut2\"></form></p><a name=\"bottomline\"></a></td></tr></table>";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
