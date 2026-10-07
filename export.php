<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$title = preg_replace('/[&%$\/\\\|@<>#£]/','',$_REQUEST['title']);
$header = "Content-Disposition: attachment; filename=\"" . $title . ".l2j\"";
header($header);
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
$ipaddr = $_SERVER["REMOTE_ADDR"];
$character = input_check($_REQUEST['character'],1);
$account = input_check($_REQUEST['account'],1);
$clan = input_check($_REQUEST['clan'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

function export_character($character, $db_location,$db_user,$db_psswd, $db_l2jdb)
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
	
	if ($result = mysql_query("select * from characters where charId = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			$res_var = mysql_query("show fields from characters",$con);
			$char_name = $r_array['char_name'];
			echo "charstart#_#$char_name";
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if ((strcasecmp($field_var, "name") <> 0) && (strcasecmp($field_var, "charId") <> 0))
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_hennas where charId = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charhenna#_#$char_name";
			$res_var = mysql_query("show fields from character_hennas",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "charId") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_macroses where charId = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charmacroses#_#$char_name";
			$res_var = mysql_query("show fields from character_macroses",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "charId") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_quests where char_id = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charquests#_#$char_name";
			$res_var = mysql_query("show fields from character_quests",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "char_id") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_recipebook where char_id = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charrecipebook#_#$char_name";
			$res_var = mysql_query("show fields from character_recipebook",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "char_id") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_shortcuts where charId = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charshortcuts#_#$char_name";
			$res_var = mysql_query("show fields from character_shortcuts",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "charId") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_skills where charId = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charskills#_#$char_name";
			$res_var = mysql_query("show fields from character_skills",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "charId") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_skills_save where charId = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charskillssave#_#$char_name";
			$res_var = mysql_query("show fields from character_skills_save",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "charId") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from character_subclasses where charId = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charsubclass#_#$char_name";
			$res_var = mysql_query("show fields from character_subclasses",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "charId") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "r\n";
		}
	}
	$pet_number = 1;
	if ($result = mysql_query("select * from items where owner_id = '$character'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "charitem#_#$char_name";
			$item_id = 0;
			$res_var = mysql_query("show fields from items",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if ((strcasecmp($field_var, "owner_id") <> 0) && (strcasecmp($field_var, "object_id") <> 0))
				{	echo "#_#$field_var#_#$value";	}
				if ($field_var == 'item_id')
				{	$item_id = $value;	}
			}
			echo "\r\n";
			if (($item_id == 6648) || ($item_id == 6650) || ($item_id == 4425) || ($item_id == 6649) || ($item_id == 2375) || ($item_id == 4424) || ($item_ide == 4423) || ($item_id == 4422) || ($item_id == 3502) || ($item_ide == 3501) || ($item_ide == 3500))
			{
				$pet_id = $r_array['object_id'];
				if ($result2 = mysql_query("select * from pets where item_charId = '$pet_id'",$con))
				{
					while ($r_array2 = mysql_fetch_assoc($result2))
					{
						echo "charpet#_#$char_name";
						$res_var2 = mysql_query("show fields from pets",$con);
						while ($f_array2 = mysql_fetch_assoc($res_var2)) 
						{
							$field_var2 = $f_array2['Field'];
							$value2 = $r_array2[$field_var2];
							if (strcasecmp($field_var2, "item_charId") <> 0)
							{	echo "#_#$field_var2#_#$value2";	}
						}
						echo "\r\n";
					}
				}
			}
		}
	}
}

function export_account($account, $db_location,$db_user,$db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb)
{

	$con2 = mysql_connect($dblog_location,$dblog_user,$dblog_psswd);
	mysql_query("SET NAMES 'utf8'", $con2);
	if (!$con2)
	{
		echo "Could Not Connect";
		die('Wrap_start could not connect to logserver database: ' . mysql_error());
	}		
	if (!mysql_select_db("$dblog_l2jdb",$con2))
	{
		die('Wrap_start could not change to logserver database: ' . mysql_error());
	}
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
	
	if ($result = mysql_query("select * from $dblog_l2jdb.accounts where login = '$account'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			$res_var = mysql_query("show fields from $dblog_l2jdb.accounts",$con);
			$char_name = $r_array['char_name'];
			echo "accountstart#_#$account";
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "login") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select * from $dblog_l2jdb.knightdrop where name = '$account'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			echo "accountknight#_#$account";
			$res_var = mysql_query("show fields from $dblog_l2jdb.knightdrop",$con);
			while ($f_array = mysql_fetch_assoc($res_var)) 
			{
				$field_var = $f_array['Field'];
				$value = $r_array[$field_var];
				if (strcasecmp($field_var, "name") <> 0)
				{	echo "#_#$field_var#_#$value";	}
			}
			echo "\r\n";
		}
	}
	if ($result = mysql_query("select charId from characters where account_name = '$account'",$con))
	{
		while ($r_array = mysql_fetch_assoc($result))
		{
			$character = $r_array['charId'];
			export_character($character, $db_location,$db_user,$db_psswd, $db_l2jdb);
		}
	}
	
}

$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_giveandtake)
	{
		echo "--------------------\r\n";
		echo "Error - Permission level not high enough.\r\n";
		echo "--------------------\rn";
		return 0;
	}
	else
	{
		if ($character)
		{	
			echo"- CHARACTER EXPORT -\r\n";
			export_character($character, $db_location,$db_user,$db_psswd, $db_l2jdb);
		}
		if ($account)
		{	
			echo"- ACCOUNT EXPORT -\r\n";
			export_account($account, $db_location,$db_user,$db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb);
		}
		
	}
}
else
{
	echo "--------------------\r\n";
	echo "Error - Could not evaluate user.\r\n";
	echo "--------------------\r\n";
}


?>