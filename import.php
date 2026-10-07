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
The system will only operate assuming that telnet is active to the server.
Put the telnet configuration in to the config.php file.
*/

include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$new_account = input_check($_REQUEST['account'],1);
$ipaddr = $_SERVER["REMOTE_ADDR"];

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

// $user_game_acc

function convert_array($in_array, $start_pos)
{
	$string1 = "";
	$string2 = "";
	$total = count($in_array);
	while ($start_pos < $total)
	{
		$string1 = $string1 . "`" . $in_array[$start_pos] . "`";
		$start_pos++;
		$string2 = $string2 . "\"" . $in_array[$start_pos] . "\"";
		$start_pos++;
		if ($start_pos < $total)
		{
			$string1 = $string1 . ", ";
			$string2 = $string2 . ", ";
		}
	}
	if ($start_pos == 2)
	{	$return_string = "(clanid, " . $string1 . ") values (0, " . $string2 . ")";	}
	else
	{	$return_string = "(" . $string1 . ") values (" . $string2 . ")";	}
	return $return_string;
}

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_admin)		// If insufficient access level, then terminate script.
	{
		echo "<p class=\"dropmain\">You don't have sufficient access.</p>";
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	
	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "<p class=\"dropmain\">Could Not Connect</p>";
		die('Import could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{	die('Import could not change to logserver database: ' . mysql_error());	}
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
	
	if (!is_uploaded_file($_FILES['file']['tmp_name'])) 
	{
		echo "You did not upload a file!";
		unlink($_FILES['file']['tmp_name']);
		return 0;
	} 
	if ($_FILES['file']['size'] > 500000) 
	{
		echo "The file size is too large. 500,000 characters max.";
		unlink($_FILES['file']['tmp_name']);
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
    } 

	echo "<br><h2 class=\"dropmain\">Account / Character Import</h2><br>";
	
	$found_account = 0;
	$found_character = 0;
	$continue_import = 1;
	$filename = $_FILES['file']['tmp_name'];
	$handle = @fopen($filename, "r");
	if ($handle) 
	{
		while ($line_in = fgets($handle)) 
		{	
			if (strpos($line_in,'- ACCOUNT EXPORT -') !== false)
			{	$found_account = 1;	}
			if (strpos($line_in,'- CHARACTER EXPORT -') !== false)
			{	$found_character = 1;	}
			$half = substr($line_in, 0, 15);
			if (substr($line_in, 0, 15) == 'accountstart#_#')
			{
				$pos = strpos($line_in, '#_#', 16) - 15;
				$account = substr($line_in, 15, $pos);
				echo "<p class=\"dropmain\">Account -- $account ";
				$result = mysql_query("select COUNT(*) from $dblog_l2jdb.accounts where login = '$account'",$con);
				$char_count = mysql_result($result,0,"COUNT(*)");
				if ($new_account)
				{
					echo "<strong> - Previous account will be ignored - target account now '$new_account'.</strong>";
				}
				else
				{
					if ($char_count > 0)
					{
						echo "<strong><font color=$red_code> - Account already exists - import halted.</font></strong>";
						$continue_import = 0;
						unlink($HTTP_POST_FILES['file']['tmp_name']);
						wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
						return 0;
					}
					else
					{	echo "<strong><font color=$green_code> - Account O.K. to be added.</font></strong>";	}
				}
				echo "</p>";
				if (!$account_names)		
				{	$account_names = array($account);	}
				else
				{	array_push($account_names, $account);	}
			}
			$half = substr($line_in, 0, 12);
			if (substr($line_in, 0, 12) == 'charstart#_#')
			{
				$pos = strpos($line_in, '#_#', 12) - 12;
				$character = substr($line_in, 12, $pos);
				echo "<p class=\"dropmain\">Character -- $character ";
				$result = mysql_query("select COUNT(*) from characters where char_name = '$character'",$con);
				$char_count = mysql_result($result,0,"COUNT(*)");
				if ($char_count > 0)
				{
					echo "<strong><font color=$red_code> - Character already exists - not imported.</font></strong>";
				}
				else
				{	echo "<strong><font color=$green_code> - Character O.K. to be added.</font></strong>";	}
				echo "</p>";
				if (!$character_names)		// Add the spawn location to the map.
				{	$character_names = array($character);	}
				else
				{	array_push($character_names, $character);	}
			}
			$half = substr($line_in, 0, 10);
			if (substr($line_in, 0, 10) == 'charpet#_#')
			{
				$pos = strpos($line_in, '#_#name#_#', 15) + 10;
				$pos2 = strpos($line_in, '#_#', $pos) - $pos;
				$pet = substr($line_in, $pos, $pos2);
				echo "<p class=\"dropmain\">Pet -- $pet <strong><font color=$green_code> - Pet O.K. to be added.</font></strong></p>";
				if (!$pet_names)		// Add the spawn location to the map.
				{	$pet_names = array($pet);	}
				else
				{	array_push($pet_names, $pet);	}
			}
		}
		fclose($handle);
		if (($found_account == 0) && (strlen($new_account) < 1))
		{
			echo "<p class=\"dropmain\"><strong><font color=$red_code>No valid account name to act as source.  Import halted.</font></strong></p>";
			$continue_import = 0;
		}
		if (strlen($new_account) < 1)
		{	$use_accounts = 1;	}
		else
		{	$use_accounts = 0;	}
		$new_char_num = 0;
		echo "<p>$found_account) || ($found_character)) && $continue_import</p>";
		if ((($found_account) || ($found_character)) && $continue_import)
		{
			echo "<hr>";
			$handle = @fopen($filename, "r");
			if ($handle) 
			{
				while ($line_in = fgets($handle)) 
				{
					if (substr_count($line_in, '#_#') > 0)
					{
						$actions = split('#_#', $line_in);
						if (($actions[0] == 'accountstart') && ($use_accounts))
						{
							$new_account = $actions[1];
							$actions[0] = "login";
							$sql = "insert into $dblog_l2jdb.accounts " . convert_array($actions, 0);
							echo "<p>$sql</p>";
							if (!$result2 = mysql_query($sql,$con2))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'accountknight') && ($use_accounts))
						{
							$actions[0] = "name";
							$sql = "insert into $dblog_l2jdb.knightdrop " . convert_array($actions, 0);
							echo "<p>$sql</p>";
							if (!$result2 = mysql_query($sql,$con2))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if ($actions[0] == 'charstart')
						{
							$actions[0] = "char_name";
							$character = $actions[1];
							$result = mysql_query("select COUNT(*) from characters where char_name = '$character'",$con);
							$char_count = mysql_result($result,0,"COUNT(*)");
							if ($char_count == 0)
							{	echo "<p><font color=$green_code>Running Character - $actions[1]</font></p>";
								$result2 = mysql_query("select charId from characters order by charId DESC limit 1",$con);
								$new_char_num = mysql_result($result2,0,"charId") + 7;
								if ($new_char_num < 268475917)
								{	$new_char_num = 268475917;	}
								$actions[3] = $new_account;
								array_push($actions, "charId");
								array_push($actions, $new_char_num);
								$sql = "insert into characters " . convert_array($actions, 2);
								if (!$result2 = mysql_query($sql,$con))
								{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
							}
						}
						if (($actions[0] == 'charhenna') && ($char_count == 0))
						{
							$actions[0] = "charId";
							$actions[1] = $new_char_num;
							$sql = "insert into character_hennas " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charmacroses') && ($char_count == 0))
						{
							$actions[0] = "charId";
							$actions[1] = $new_char_num;
							$sql = "insert into character_macroses " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charquests') && ($char_count == 0))
						{
							$actions[0] = "char_id";
							$actions[1] = $new_char_num;
							$sql = "insert into character_quests " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charrecipebook') && ($char_count == 0))
						{
							$actions[0] = "char_id";
							$actions[1] = $new_char_num;
							$sql = "insert into character_recipebook " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charshortcuts') && ($char_count == 0))
						{
							$actions[0] = "charId";
							$actions[1] = $new_char_num;
							$sql = "insert into character_shortcuts " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charskills') && ($char_count == 0))
						{
							$actions[0] = "charId";
							$actions[1] = $new_char_num;
							$sql = "insert into character_skills " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charskillssave') && ($char_count == 0))
						{
							$actions[0] = "charId";
							$actions[1] = $new_char_num;
							$sql = "insert into character_skills_save " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charsubclass') && ($char_count == 0))
						{
							$actions[0] = "charId";
							$actions[1] = $new_char_num;
							$sql = "insert into character_subclasses " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charitem') && ($char_count == 0))
						{
							$result2 = mysql_query("select object_id from items order by object_id DESC limit 1",$con);
							$new_item = mysql_result($result2,0,"object_id") + 1;
							if ($new_item < 268435458)
							{	$new_item = 268435458;	}
							$actions[0] = "owner_id";
							$actions[1] = $new_char_num;
							array_push($actions, "object_id");
							array_push($actions, $new_item);
							$sql = "insert into items " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
						if (($actions[0] == 'charpet') && ($char_count == 0))
						{
							$actions[0] = "item_charId";
							$actions[1] = $new_item;
							$sql = "insert into pets " . convert_array($actions, 0);
							if (!$result2 = mysql_query($sql,$con))
							{	echo "<p class=\"dropmain\"><font color=$red_code>Failed to run query - " . mysql_error() . "</font><br><p>$sql</p></p>";	}
						}
					}
				}
				fclose($handle);
				echo "<hr><h2 class=\"dropmain\">Import Complete</h2>";
			}
		}
		else
		{	echo "<p class=\"dropmain\">Couldn't find the import header.</p>";	}
	}
}
unlink($HTTP_POST_FILES['file']['tmp_name']);

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>