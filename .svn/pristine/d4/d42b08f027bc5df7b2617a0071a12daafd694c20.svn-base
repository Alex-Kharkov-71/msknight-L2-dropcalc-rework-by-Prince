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
$settogive = input_check($_REQUEST['settogive'],0);

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

	if ($user_access_lvl < $sec_giveandtake)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
	{

		if ($itemid == "setgive")
		{
			$sql = "select level, race from characters where char_name = '$touser'";
			$result = mysql_query($sql,$con);
			$level = mysql_result($result,0,"level");
			$race = mysql_result($result,0,"race");
			if ($race <> 5)
			{
				// S grade
				$itms = array(6616, 6611, 6615, 6375, 6378, 6373, 6374, 6376, 6377, 6659, 6659, 6658, 6658, 6657);
				if ($level < 20)		// No grade
				{	$itms = array(6354, 4221, 4230, 1148, 4228, 4231, 4223, 4229, 909, 846, 846, 878, 878, 311);	}
				elseif ($level < 40)		// D grade
				{	$itms = array(2412, 2499, 396, 2494, 607, 2427, 914, 851, 851, 882, 882, 159, 323);	}
				elseif ($level < 52)		// C grade
				{	$itms = array(2503, 135, 2462, 2414, 356, 2497, 2438, 857, 857, 888, 888, 919, 333);	}
				elseif ($level < 61)		// B grade
				{	$itms = array(338, 4719, 5722, 211, 550, 2381, 110, 5738, 860, 860, 6660, 6660, 922);	}
				elseif ($level < 76)		// A grade
				{	$itms = array(344, 5648, 164, 5774, 2419, 2383, 2498, 5786, 924, 6662, 6662, 6661, 6661);	}
			}
			else	// If Kamael, issue light armour.
			{
				// S grade
				$itms = array(6616, 6611, 6615, 6375, 6378, 6373, 6374, 6376, 6377, 6659, 6659, 6658, 6658, 6657);	// not done
				if ($level < 20)		// No grade
				{	$itms = array(6354, 4221, 4230, 1148, 4228, 4231, 4223, 4229, 909, 846, 846, 878, 878, 311);	}	// not done
				elseif ($level < 40)		// D grade
				{	$itms = array(47, 395, 417, 606, 1124, 9584, 9224, 850, 850, 881, 881, 913);	}
				elseif ($level < 52)		// C grade
				{	$itms = array(2503, 135, 2462, 2414, 356, 2497, 2438, 857, 857, 888, 888, 919, 333);	}	// not done
				elseif ($level < 61)		// B grade
				{	$itms = array(338, 4719, 5722, 211, 550, 2381, 110, 5738, 860, 860, 6660, 6660, 922);	}	// not done
				elseif ($level < 76)		// A grade
				{	$itms = array(344, 5648, 164, 5774, 2419, 2383, 2498, 5786, 924, 6662, 6662, 6661, 6661);	}	// not done
			}
			//else
			$itmcount = count($itms);
			$sql = "select online, charId from characters where char_name = '$touser'";
			$result2 = mysql_query($sql,$con);
			$useronline = mysql_result($result2,0,"online");
			$userid = mysql_result($result2,0,"charId");
			$i2 = 0;
			$qty = 1;
			while ($i2 < $itmcount)
			{
				$itemid = $itms[$i2];
				if ($useronline)
				{					
					// Try and find the details on the object from each of the three content databases.
					// Looking simply for the name and crystal type.
					$obj_name = item_name($itemid, $db_location, $db_user, $db_psswd, $db_l2jdb);
					$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
					if($usetelnet)
					{
						$give_string = 'give ' . utf82lang ($touser) . ' ' . $itemid . ' ' . $qty;
						fputs($usetelnet, $telnet_password);
						fputs($usetelnet, "\r\n");
						fputs($usetelnet, $give_string);
						fputs($usetelnet, "\r\n");
						fputs($usetelnet, "msg ".utf82lang ($touser)." Admin gave you this: ".$qty." ".$obj_name."\r");
						fputs($usetelnet, "exit\r\n");
						fclose($usetelnet);
					}
					else
					{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
				}
				else
				{
					$run_count = 1;
					if (!$obj_stack)
					{
						$run_count = $qty;
						$qty = 1;
					}
					$i=0;
					while ($i < $run_count)
					{
						$sql = "select object_id from items order by object_id desc limit 1";
						$result3 = mysql_query($sql,$con);
						if (mysql_num_rows($result3) > 0)
						{	$new_id = mysql_result($result3,0,"object_id") + 1;	}
						else
						{	$new_id = 268435456;	}
						$sql = "insert into items (owner_id, object_id, item_id, count, loc, loc_data, enchant_level, mana_left) values ('$userid', '$new_id', '$itemid', '$qty', 'INVENTORY', '0', 0, -1)";
						$result3 = mysql_query($sql,$con);
						$i++;
					}
				}
				$i2++;
			}
			echo "<p class=\"popup\">Full set for class given to $touser.</p>";
			$itemid = "";
		}
		if ($itemid == "setgive2")
		{
			$sql = "select * from armorsets where id = $settogive";
			$result2 = mysql_query($sql,$con);
			$a_chest = mysql_result($result2, 0,"chest");
			$a_legs = mysql_result($result2, 0,"legs");
			$a_head = mysql_result($result2, 0,"head");
			$a_gloves = mysql_result($result2, 0,"gloves");
			$a_feet = mysql_result($result2, 0,"feet");
			$a_shield = mysql_result($result2, 0,"shield");
			$a_skill1 = mysql_result($result2, 0,"skill");
		
			$itms = array();		// Extract each of the skills in the list.
			$a_skill_list = preg_split("/;/",$a_skill1);
			$itmcount = count($a_skill_list);
			if ($itmcount > 0)
			{
				$i = 0;
				while ($i <$itmcount)
				{
					$a_sk_elem=$a_skill_list[$i];
					$a_sk_elem_part = preg_split("/-/",$a_sk_elem);
					$a_sk_elem_spart = $a_sk_elem_part[0];
					array_push($itms,$a_sk_elem_spart);
					$i++;
				}
			}

			$a_skill2 = mysql_result($result2, 0,"shield_skill_id");
			$a_skill3 = mysql_result($result2, 0,"enchant6skill");
			$itms = array($a_chest, $a_legs, $a_head, $a_gloves, $a_feet, $a_shield);
	
			$itmcount = count($itms);
			$sql = "select online, charId from characters where char_name = '$touser'";
			$result2 = mysql_query($sql,$con);
			$useronline = mysql_result($result2,0,"online");
			$userid = mysql_result($result2,0,"charId");
			$i2 = 0;
			$qty = 1;
			while ($i2 < $itmcount)
			{
				$itemid = $itms[$i2];
				if ($itemid > 0)
				{
					if ($useronline)
					{
						// Try and find the details on the object from each of the three content databases.
						// Looking simply for the name and crystal type.
						$obj_name = item_name($itemid, $db_location, $db_user, $db_psswd, $db_l2jdb);
						$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
						if($usetelnet)
						{
							$give_string = 'give ' . utf82lang ($touser) . ' ' . $itemid . ' ' . $qty;
							fputs($usetelnet, $telnet_password);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, $give_string);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, "msg ".utf82lang ($touser)." admin gave you this: ".$qty." ".$obj_name);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, "exit\r\n");
							fclose($usetelnet);
						}
						else
						{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
					}
					else
					{
						$run_count = 1;
						if (!$obj_stack)
						{
							$run_count = $qty;
							$qty = 1;
						}
						$i=0;
						while ($i < $run_count)
						{
							$sql = "select object_id from items order by object_id desc limit 1";
							$result3 = mysql_query($sql,$con);
							if (mysql_num_rows($result3) > 0)
							{	$new_id = mysql_result($result3,0,"object_id") + 1;	}
							else
							{	$new_id = 268435456;	}
							$sql = "insert into items (owner_id, object_id, item_id, count, loc, loc_data, enchant_level, mana_left) values ('$userid', '$new_id', '$itemid', '$qty', 'INVENTORY', '0', 0, -1)";
							$result3 = mysql_query($sql,$con);
							$i++;
						}
					}
				}
				$i2++;
			}
			array_push($itms,$a_skill2);
			array_push($itms,$a_skill3);
			$itmcount = count($itms);
			$i2 = 0;
			while ($i2 < $itmcount)
			{
				$itemid = $itms[$i2];
				if ($itemid > 0)
				{
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
							$char_result = mysql_query("select charId from characters where char_name = '$touser'",$con);
							$char_id = mysql_result($char_result,0,"charId");
							$sql = "delete from character_skills where charId = '$userid' and skill_id = '$itemid'";
							$skill_result = mysql_query($sql,$con);
							$sql = "insert into character_skills (charId, skill_id, skill_level, skill_name, class_index) values ('$userid', '$itemid', '$skill_max', '$skill_name', '0')";
							$skill_result = mysql_query($sql,$con);
							echo "Skill $skill_name level $qty given";
						}
					}
				}
				$i2++;
			}
			echo "<p class=\"popup\">Full set for class given to $touser.</p>";
			$itemid = "";
		}
		if ($itemid == "set")
		{
			echo "<p class=\"popup\">Are you sure that you want to<br>give $touser a full set of armour,<br>weapons and accesories?</p>";
			echo "<center><form action=\"giveitem.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"touser\" type=\"hidden\" value=\"$touser\"><input name=\"itemid\" type=\"hidden\" value=\"setgive\"><input value=\"Yes\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form></center>";
			echo "</center></body></html>";
			return 0;
		}
		if ($itemid == "set2")
		{
			echo "<p class=\"popup\">Are you sure that you want to<br>give $touser a full set of armour,<br>weapons and accesories?</p>";
			$sql = "select chest from armorsets where id = $settogive";
			$result2 = mysql_query($sql,$con);
			$setid = mysql_result($result2, 0,"chest");
			$sql = "select name, crystal_type from knightarmour where item_id = $setid";
			$result2 = mysql_query($sql,$con);
			$i_name = mysql_result($result2, 0,"name");
			$i_crystal = mysql_result($result2, 0,"crystal_type");
			echo "<p>[$i_crystal] $i_name ($setid)</p>";
			echo "<center><form action=\"giveitem.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"settogive\" type=\"hidden\" value=\"$settogive\"><input name=\"touser\" type=\"hidden\" value=\"$touser\"><input name=\"itemid\" type=\"hidden\" value=\"setgive2\"><input value=\"Yes\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form></center>";
			echo "</center></body></html>";
			return 0;
		}
		if ($itemid == "pet")
		{
			$sql = "select distinct type from pets_stats";
			$result = mysql_query($sql,$con);
			$pet_count = mysql_num_rows($result);
			echo "
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\" class=\"popuptrans\"\">";
			echo "<select onChange=\"document.location=options[selectedIndex].value;\"><option value=\"\">- Select Pet Type -</option>";
			$i=0;
			while ($i < $pet_count)
			{
				$c_var = mysql_result($result,$i,"type");
	    			echo "<option value=\"giveitem.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$touser&itemid=givepet&pet=$c_var\">$c_var</option>";
				$i++;
  			}
			echo "</select></td></tr></table>";
			echo "</center></body></html>";
			return 0;

			$itemid = "";
		}

		if ($itemid == "givepet")
		{
			$sql = "select min(level) as level from pets_stats where type = '$pet' group by type"; //to avoid level errors we give a pet of a minimal available level
			$result = mysql_query($sql,$con);
			$pet_lvl = mysql_result($result,0,"level");
			$sql = "select object_id from items order by object_id desc limit 1";
			$result = mysql_query($sql,$con);
			if (mysql_num_rows($result) > 0)
			{	$last_num = mysql_result($result,0,"object_id") + 1;	}
			else
			{	$last_num = 268435456;	}
			$sql = "select typeid, expmax, hpmax, mpmax, patk, pdef, matk, mdef, acc, evasion, crit, speed, atk_speed, cast_speed, feedmax, feedbattle, feednormal, loadmax, hpregen, mpregen from pets_stats where type = '$pet' and level = '$pet_lvl'";
            $result = mysql_query($sql,$con);
            $petvals = "'" . $last_num . "', '" . $pet_lvl . "', '" . mysql_result($result,0,"hpmax") . "', '" . mysql_result($result,0,"mpmax"). "', '" . mysql_result($result,0,"expmax") . "', '";
            $petvals = $petvals . mysql_result($result,0,"feedmax"). "'";
            $callertype = mysql_result($result,0,"typeid");
            $sql = "insert into pets (item_obj_id, level, curhp, curmp, exp, fed) values ($petvals)";
            $result = mysql_query($sql,$con);

			$caller = 6648;			// Assume buffalo pipe unless ...
			if ($callertype == 12781)		// baby kookaburra calls
			{	$caller = 6650;	}			// kookaburra chime
			elseif ($callertype == 12621)	// sin eater calls
			{	$caller = 8663;	}			// Penitents Manacles
			elseif ($callertype == 12782)		// baby cougar calls
			{	$caller = 6649;	}			// baby cougar chime
			elseif ($callertype == 12077)	//Wolf
			{	$caller = 2375;	}
			elseif ($callertype == 12528)	//Twilight Strider
			{	$caller = 4424;	}
			elseif ($callertype == 12527)	//Star Strider
			{	$caller = 4423;	}
			elseif ($callertype == 12526)	//Wind Strider
			{	$caller = 4422;	}
			elseif ($callertype == 12313)	//Hatchling of Twilight
			{	$caller = 3502;	}
			elseif ($callertype == 12312)	//Hatchling of Star
			{	$caller = 3501;	}
			elseif ($callertype == 12311)	//Hatchling of Wind
			{	$caller = 3500;	}
			elseif ($callertype == 16025)	//Black Wolf
			{	$caller = 9882;	}
			elseif ($callertype == 16030)	//Great Wolf
			{	$caller = 10163;	}
			elseif ($callertype == 16041)	//Fenir
			{	$caller = 10426;	}
			elseif ($callertype == 16034)	//Improved Baby Buffalo
			{	$caller = 10311;	}
			elseif ($callertype == 16036)	//Improved Baby Cougar
			{	$caller = 10312;	}
			elseif ($callertype == 16035)	//Improved Baby Kookaburra
			{	$caller = 10313;	}
			elseif ($callertype == 16037)	//White Great Wolf
			{	$caller = 10307;	}
			elseif ($callertype == 16042)	//White Fenrir
			{	$caller = 10611;	}
			$sql = "select charId from characters where char_name = '$touser'";
			$result = mysql_query($sql,$con);
            $owner = mysql_result($result,0, "charId");
            $sql = "insert into items (owner_id, object_id, item_id, count, enchant_level, loc, loc_data) values ('$owner', '$last_num', '$caller', '1', '$pet_lvl', 'INVENTORY', '0')";
            $result = mysql_query($sql,$con) or die(mysql_error());
            echo "<p class=\"popup\">Pet $pet level $pet_lvl given, caller in inventory.<br>User will have to re-log.<br>You can change pet's level in 'Pets' section.</p>";
            $itemid = "";
		}


		// If we have entered with an item, then attempt to give it.
		if ($itemid)
		{
			// Try and find the details on the object from each of the three content databases.
			// Looking simply for the name and crystal type.
			$obj_name = "<$lang_unknown>";
			$obj_grade = 0;
			$obj_stack = 0;
			$sql = "select name, crystal_type from knightarmour where item_id = $itemid";
			$result_i = mysql_query($sql,$con);
			$count_i = mysql_num_rows($result_i);
			if ($count_i)
			{	
				$obj_name = mysql_result($result_i,0,"name");	
				$obj_grade = mysql_result($result_i,0,"crystal_type");	
			}
			$sql = "select name, crystal_type from knightweapon where item_id = $itemid";
			$result_i = mysql_query($sql,$con);
			$count_i = mysql_num_rows($result_i);
			if ($count_i)
			{	
				$obj_name = mysql_result($result_i,0,"name");	
				$obj_grade = mysql_result($result_i,0,"crystal_type");	
			}
			$sql = "select name, consume_type, is_stackable from knightetcitem where item_id = $itemid";
			$result_i = mysql_query($sql,$con);
			$count_i = mysql_num_rows($result_i);
			if ($count_i)
			{	
				$obj_name = mysql_result($result_i,0,"name");	
				$obj_consume_type = mysql_result($result_i,0,"consume_type");	
				$obj_stack = mysql_result($result_i,0,"is_stackable");		
				$obj_grade = "N/A";	
				if (($obj_stack == "stackable") || ($obj_consume_type == "asset"))
				{ $obj_stack = 1;	}
			}

			if ($obj_name == "<$lang_unknown>")
			{
				echo "<p class=\"popup\">$itemid is not found.</p>";
				echo "</center></body></html>";
			}
			else
			{
				if (($qty > 10) && (!$obj_stack))
				{	echo "<p class=\"popup\">$obj_name is non-stackable item!<br>Can't give more than 10.</p>";	}
				else
				{
					$sql = "select online, charId from characters where char_name = '$touser'";
					$result2 = mysql_query($sql,$con);
					$useronline = mysql_result($result2,0,"online");
					$userid = mysql_result($result2,0,"charId");
					if ($useronline)
					{
						
						$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
						if($usetelnet)
						{
							$give_string = 'give ' . utf82lang ($touser) . ' ' . $itemid . ' ' . $qty;
							fputs($usetelnet, $telnet_password);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, $give_string);
							fputs($usetelnet, "\r\n");
							fputs($usetelnet, "msg ".utf82lang ($touser)." admin gave you : ".$qty." ".$obj_name."\r");
							fputs($usetelnet, "exit\r\n");
							fclose($usetelnet);
						}
						else
						{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
					}
					else
					{
						$run_count = 1;
						if (!$obj_stack)
						{
							$run_count = $qty;
							$qty = 1;
						}
						$i=0;
						while ($i < $run_count)
						{
							$sql = "select object_id from items order by object_id desc limit 1";
							$result3 = mysql_query($sql,$con);
							if (mysql_num_rows($result3) > 0)
							{	$new_id = mysql_result($result3,0,"object_id") + 1;	}
							else
							{	$new_id = 268435456;	}
							$sql = "insert into items (owner_id, object_id, item_id, `count`, loc, loc_data, enchant_level, mana_left) values ('$userid', '$new_id', '$itemid', '$qty', 'INVENTORY', '0', 0, -1)";
							$result3 = mysql_query($sql,$con);
							$i++;
						}
					}
					echo "<p class=\"popup\">$qty $obj_name given to $touser.</p>";
				}
			}
			$itemid = "";
		}

		if (!$itemid)
		{
			$sql = "select chest, id from armorsets order by id";
			$set_sel = "<select name=\"settogive\" OnChange=\"submit()\" class=\"field2\">";
			$result = mysql_query($sql,$con);
			while ($r_array = mysql_fetch_assoc($result))
			{
				$r_item = $r_array['chest'];
				$r_id = $r_array['id'];
				$sql = "select name, crystal_type from knightarmour where item_id = $r_item";
				$result2 = mysql_query($sql,$con);
				$i_name = mysql_result($result2, 0,"name");
				$i_crystal = mysql_result($result2, 0,"crystal_type");
				$set_sel = $set_sel . "<option value=$r_id>[$i_crystal] $i_name ($r_item)</option>";
			}
			$set_sel = $set_sel . "</select>";
			// Echo out the giving form.
			echo "	<form action=\"giveitem.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"touser\" type=\"hidden\" value=\"$touser\">
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"popup\">
			<tr>
			<td class=\"popuptrans\">ID&nbsp;-&nbsp;<input name=\"itemid\" maxlength=\"10\" size=\"4\" type=\"text\" value=\"0\" class=\"popup\"></td>
			<td class=\"popuptrans\">&nbsp;&nbsp;&nbsp;Qty&nbsp;-&nbsp;<input name=\"qty\" maxlength=\"10\" size=\"4\" type=\"text\" value=\"1\" class=\"popup\"></td></tr>
			<tr><td colspan=\"2\" class=\"popuptrans\"><input value=\"Give Item\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></td></form></tr>
			<tr><td colspan=\"2\" class=\"popuptrans\"><p>&nbsp;</p></td></tr>
			<tr><td class=\"popuptrans\"><form action=\"giveitem.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"touser\" type=\"hidden\" value=\"$touser\"><input name=\"itemid\" type=\"hidden\" value=\"pet\"><input value=\"Give Pet\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form></td>
			<td class=\"popuptrans\"><form action=\"giveitem.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"touser\" type=\"hidden\" value=\"$touser\"><input name=\"itemid\" type=\"hidden\" value=\"set\"><input value=\"Give Set\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form></td>
			</tr>
			<tr><td colspan=\"2\" class=\"popuptrans\"><form action=\"giveitem.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"touser\" type=\"hidden\" value=\"$touser\"><input name=\"itemid\" type=\"hidden\" value=\"set2\">$set_sel<br><input value=\"Give Chosen Set\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form></td></tr>
			</table>
			";
		}

	}
}

echo "</center></body></html>";

?>
