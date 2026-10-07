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
$pet = input_check($_REQUEST['pet'],0);
$ptype = input_check($_REQUEST['ptype'],0);
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
	$pet_style = "unknown";
	if ($ptype == "6648")
	{	$pet_style = "Buffalo";		
		$pet_db = "baby buffalo";	}
	elseif ($ptype == "6650")
	{	$pet_style = "Kookaburra";		
		$pet_db = "baby kookaburra";	}
	elseif ($ptype == "4425")
	{	$pet_style = "Wyvern";		
		$pet_db = "wyvern";	}
	elseif ($ptype == "6649")
	{	$pet_style = "Cougar";		
		$pet_db = "baby cougar";	}
	elseif ($ptype == "2375")
	{	$pet_style = "Wolf";		
		$pet_db = "wolf";	}
	elseif ($ptype == "4424")
	{	$pet_style = "Strider&nbsp;of&nbsp;Twilight";		
		$pet_db = "strider_of_twilight";	}
	elseif ($ptype == "4423")
	{	$pet_style = "Strider&nbsp;of&nbsp;Star";		
		$pet_db = "strider_of_star";	}
	elseif ($ptype == "4422")
	{	$pet_style = "Strider&nbsp;of&nbsp;Wind";		
		$pet_db = "strider_of_wind";	}
	elseif ($ptype == "3502")
	{	$pet_style = "Hatchling&nbsp;of&nbsp;Twilight";		
		$pet_db = "hatchling_of_twilight";	}
	elseif ($ptype == "3501")
	{	$pet_style = "Hatchling&nbsp;of&nbsp;Star";	
		$pet_db = "hatchling_of_star";	}
	elseif ($ptype == "3500")
	{	$pet_style = "Hatchling&nbsp;of&nbsp;Wind";		
		$pet_db = "hatchling_of_star";	}

	echo "<p class=\"popup\">Level for $pet_style</p>";

	if ($user_access_lvl < $sec_giveandtake)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{
		// If we have entered with an item, then attempt to give it.
		if ($qty)
		{
			// Try and find the details on the object from each of the three content databases.
			// Looking simply for the name and crystal type.
			

			$sql = $sql . " where item_charId = '$pet'";
			$result = mysql_query($sql,$con);
			
			$sql = "select level from pets_stats where type = '$pet_db' order by level desc limit 1";

			$skill_result = mysql_query($sql,$con);
			if ($skill_result)
			{
				$skill_count = mysql_num_rows($skill_result);
				if ($skill_count)
				{
					$skill_max = mysql_result($skill_result,0,"level");	
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

						$sql = "update pets set level = $qty where item_charId = '$pet'";
						$skill_result = mysql_query($sql,$con);
						$sql = "select expmax, hpmax, mpmax, patk, pdef, matk, mdef, acc, evasion, crit, speed, atk_speed, cast_speed, feedmax, feedbattle, feednormal, loadmax, hpregen, mpregen from pets_stats where type = '$pet_db' and level = '$qty'";
						$result = mysql_query($sql,$con);
						$sql = "update pets set maxhp = '" . mysql_result($result,0,"hpmax") . "', curhp = '" . mysql_result($result,0,"hpmax") . "', maxmp = '" . mysql_result($result,0,"mpmax") . "', curmp = '" . mysql_result($result,0,"mpmax") . "', acc = '" . mysql_result($result,0,"acc") . "', crit = '" . mysql_result($result,0,"crit") . "', evasion = '" . mysql_result($result,0,"evasion");
						$sql = $sql . "', maxmp = '" . mysql_result($result,0,"mpmax") . "', matk = '" . mysql_result($result,0,"matk") . "', mdef = '" . mysql_result($result,0,"mdef") . "', mspd = '" . mysql_result($result,0,"atk_speed") . "', patk = '" . mysql_result($result,0,"patk") . "', pdef = '" . mysql_result($result,0,"pdef") . "', pspd = '" . mysql_result($result,0,"speed");
						$sql = $sql . "', fed = '" . mysql_result($result,0,"feedmax") . "', max_fed = '" . mysql_result($result,0,"feedmax") . "', wit = '" . mysql_result($result,0,"loadmax") . "', exp = '" . mysql_result($result,0,"expmax") . "', sp = '" . mysql_result($result,0,"speed");
						$sql = $sql . "' where item_charId = '$pet'";
						$result = mysql_query($sql,$con);
						echo "Skill level $qty given";
					}
				}
				else
				{	echo "<p class=\"popup\">Pet type not found.</p>";	}
			}
			else
			{	echo "<p class=\"popup\">Couldn't query skill list.</p>";	}
			$itemid = "";
		}

		if (!$itemid)
		{
			// Echo out the giving form.
			echo "	<form action=\"givepskill.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"pet\" type=\"hidden\" value=\"$pet\">
			<input name=\"ptype\" type=\"hidden\" value=\"$ptype\">
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"popuptrans\">&nbsp;&nbsp;&nbsp;Level&nbsp;-&nbsp;<input name=\"qty\" maxlength=\"10\" size=\"4\" type=\"text\" value=\"1\" class=\"popup\"></td></tr>
			<tr><td><input value=\"Change Level\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></td></form></table>";
		}

	}
}

echo "</center></body></html>";

?>