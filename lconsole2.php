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

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$itemname = input_check($_REQUEST['itemname'],0);
$lastlines = input_check($_REQUEST['lastlines'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

// $lastlines = "";

if ($lastlines)
{
	$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $delay_logs, "lconsole2.php?=&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline", $low_graphic_allow, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
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
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
		$file_loc = $login_dir . 'log' . $svr_dir_delimit . 'game' . $svr_dir_delimit . '_all.txt';

		if ($lastlines)
		{
			$i=1;
			echo "<form method=\"post\" action=\"lconsole2.php\"><input value=\" <- View Whole File -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"></form>";
			echo"<pre class=\"dropmain\">";
			$handle = fopen($file_loc, "r");
			fseek($handle, -10000, SEEK_END);
			while (!feof($handle))
			{
				if ($php_type >= 1)
				{	$line = stream_get_line($handle, 100000, "\n"); }
				else
				{	$line = fgets($handle, 1000); }
				if ($i == 1)
				{	$lines = array($line);	}
				else
				{	array_push($lines, $line);	}
				$i++;
			}
			$start = $i - 82;
			if ($start < 1)
			{	$start = 1;	}
			if ($php_type)
			{	while ($start < $i)
				{	echo $lines[$start] . "\n";
					$start++;} 
			}
			else
			{	while ($start < $i)
				{	echo $lines[$start];
					$start++;} 
			}
			fclose($handle);
		}
		else
		{
			$lines = file($file_loc);
			$line_nums = count($lines);
			echo "<form method=\"post\" action=\"lconsole2.php\"><input value=\" <- View Last Lines -> \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"lastlines\" type=\"hidden\" value=\"1\"></form>";
			echo "<pre class=\"dropmain\">";
			foreach ($lines as $line_num => $line) {
			echo "$line";	}
		}
		echo "
</pre>
<a name=\"bottomline\"></a><p class=\"heading2\">Last update - ";
		echo date('l dS \of F Y h:i:s A');
		echo "</p>";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $low_graphic_allow);

?>
