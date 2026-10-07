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
$ipaddr = $_SERVER["REMOTE_ADDR"];
$touser = input_check($_REQUEST['touser'],1);
$touser_online = input_check($_REQUEST['touseronline'],0);
$itemid = input_check($_REQUEST['itemid'],0);
$pet = input_check($_REQUEST['pet'],0);
$qty = input_check($_REQUEST['qty'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

echo "<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>
<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
</head>
<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
<center>";


$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
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

	echo "<p class=\"popup\">Giving to $touser</p>";

	if ($user_access_lvl < $sec_takeskill)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		// If we have entered with an item, then attempt to give it.
		if ($itemid)
		{
			// Try and find the details on the object from each of the three content databases.
			// Looking simply for the name and crystal type.
			$sql = "select name, level from skill_trees where skill_id = '$itemid' order by level desc limit 1";
			$skill_result = mysql_query($sql,$con);
			if ($skill_result)
			{
				$skill_count = mysql_num_rows($skill_result);
				if (($itemid == '395') || ($itemid == '396') || ($itemid == '1374') || ($itemid == '1375') || ($itemid == '1376') || ($itemid == '7029'))
				{	$skill_count = 99;	}
				if ($skill_count)
				{
					if ($skill_count == 99)
					{
						$skill_max = 1;
						if ($itemid == '395')
						{	$skill_name = "Heroic Miracle";	}
						if ($itemid == '396')
						{	$skill_name = "Heroic Berserker";	}
						if ($itemid == '1374')
						{	$skill_name = "Heroic Valor";	}
						if ($itemid == '1375')
						{	$skill_name = "Heroic Grandure";	}
						if ($itemid == '1376')
						{	$skill_name = "Heroic Dread";	}
						if ($itemid == '7029')
						{	
							$skill_max = 4;
							$skill_name = "Super Haste";
						}
					}
					else
					{
						$skill_name = mysql_result($skill_result,0,"name");	
						$skill_max = mysql_result($skill_result,0,"level");	
					}
					if ($qty > $skill_max)
					{
						echo "<p class=\"popup\">Max level for this skill is $skill_max.</p>";
						$qty = $skill_max;
					}
					if ($qty < 0)
					{
						echo "<p class=\"popup\">To remove a skill, use the character editing.</p>";
					}
					else
					{
						$char_result = mysql_query("select charId from characters where char_name = '$touser'",$con);
						$char_id = mysql_result($char_result,0,"charId");
						$sql = "delete from character_skills where charId = '$char_id' and skill_id = '$itemid'";

						$skill_result = mysql_query($sql,$con);
						$sql = "insert into character_skills (charId, skill_id, skill_level, class_index) values ('$char_id', '$itemid', '$qty', '0')";
						$skill_result = mysql_query($sql,$con);
						echo "Skill $skill_name level $qty given";
					}
				}
				else
				{	echo "<p class=\"popup\">Skill $itemid not found.</p>";	}
			}
			else
			{	echo "<p class=\"popup\">Couldn't query skill list.</p>";	}
			$itemid = "";
		}

		if (!$itemid)
		{
			// Echo out the giving form.
			echo "	<form action=\"giveskill.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"touser\" type=\"hidden\" value=\"$touser\">
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"popuptrans\">ID&nbsp;-&nbsp;<input name=\"itemid\" maxlength=\"10\" size=\"4\" type=\"text\" value=\"0\" class=\"popup\"></td>
			<td class=\"popuptrans\">&nbsp;&nbsp;&nbsp;Level&nbsp;-&nbsp;<input name=\"qty\" maxlength=\"10\" size=\"4\" type=\"text\" value=\"1\" class=\"popup\"></td></tr>
			<tr><td colspan=\"2\"><input value=\"Give Skill\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></td></form></table>";
		}

	}
}

echo "</center></body></html>";

?>
