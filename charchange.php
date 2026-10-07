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

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$charnum = input_check($_REQUEST['charnum'],0);
$action = input_check($_REQUEST['action'],0);
$number = input_check($_REQUEST['number'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.


if ($l2version < 6)
{	$char_limits = array(array(4,3,2,6,3,2,4,3,2,6,3,2), array(4,3,2,6,3,2,4,3,2,6,3,2), array(4,3,2,6,3,2,4,3,2,6,3,2), array(4,3,2,6,3,2,4,3,2,6,3,2), array(4,3,2,6,3,2,0,0,0,0,0,0), array(4,2,2,6,2,2,0,0,0,0,0,0));	}

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{

	if (($user_access_lvl < $sec_inc_gmlevel) && (!$user_char_access))		// Guard against someone without permissions from just running the
	{																		// file anyway.
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}

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

	$sql = "select account_name, char_name, face, hairstyle, haircolor, sex, race, classid from characters where charId = '$charnum'";
	$result = mysql_query($sql,$con);

	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$char_acc = mysql_result($result,0,"account_name");
			$char_face = mysql_result($result,0,"face");
			$char_name = mysql_result($result,0,"char_name");
			$char_hair = mysql_result($result,0,"hairstyle");
			$char_colour = mysql_result($result,0,"haircolor");
			$char_sex = mysql_result($result,0,"sex");
			$char_race = mysql_result($result,0,"race");
			$char_class = mysql_result($result,0,"classid");
			
			echo "<center><table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" class=\"blanktab\" width=\"100%\"><tr>";
			echo "<td><center><form method=\"post\" action=\"charchange.php\"><input value=\" Male \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$charnum\"><input name=\"action\" type=\"hidden\" value=\"sex\"><input name=\"number\" type=\"hidden\" value=\"0\"></form></center></td>";
			echo "<td><center><h2>Character - $char_name</h2></center></td>";
			echo "<td><center><form method=\"post\" action=\"charchange.php\"><input value=\" Female \" type=\"submit\" class=\"bigbut\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"charnum\" type=\"hidden\" value=\"$charnum\"><input name=\"action\" type=\"hidden\" value=\"sex\"><input name=\"number\" type=\"hidden\" value=\"1\"></form></center></td>";
			echo "</tr></table></center>";
			
			if (($user_access_lvl <= $sec_inc_gmlevel) && (($username != $char_acc) || (!$user_char_access)))
			{
				writewarn("You don't have sufficient access.");
				wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
				return 0;
			}
			
			if ($action == "sex")
			{
				if ($number)
				{	$char_sex = 1;	}
				else
				{	$char_sex = 0;	}
			}
			
			if ($action == "hair")
			{	
				$hair_vars = split('[-]', $number);
				$char_colour = $hair_vars[0];
				$char_hair = $hair_vars[1];
			}
			if ($action == "face")
			{	$char_face = $number;	}
			
			$char_max = $char_limits[$char_race];
			$c_index = 0;
			if ($char_sex)
			{	$c_index = $c_index + 3;	}
			$is_mystic = in_array($char_class, $mystic_numbers);
			if ($is_mystic)
			{	$c_index = $c_index + 6;	}
			$max_hair = $char_max[$c_index];
			$c_index++;
			$max_colour = $char_max[$c_index];
			$c_index++;
			$max_face = $char_max[$c_index];
			
			if ($char_hair > $max_hair)
			{	$char_hair = $max_hair;	}
			if ($char_colour > $max_colour)
			{	$char_colour = $max_colour;	}
			if ($char_face > $max_face)
			{	$char_face = $max_face;	}
			
			$sql = "update characters set face='$char_face', hairstyle='$char_hair', haircolor='$char_colour', sex='$char_sex' where charId = '$charnum'";
			$result = mysql_query($sql,$con);
			
			$file_head = "h-";
			if ($char_race == 1)
			{	$file_head = "e-";	}
			elseif ($char_race == 2)
			{	$file_head = "de-";	}
			elseif ($char_race == 3)
			{	$file_head = "o-";	}
			elseif ($char_race == 4)
			{	$file_head = "d-";	}
			elseif ($char_race == 5)
			{	$file_head = "k-";	}
			if ($is_mystic)
			{	$file_head = $file_head . "m-";	}
			else
			{	$file_head = $file_head . "f-";	}
			if ($char_sex)
			{	$file_head = $file_head . "f-";	}
			else
			{	$file_head = $file_head . "m-";	}
			
			echo "<center><hr width=\"80%\"></center>";
			echo "<center><h2><center>Face</center></h2></center>";
			echo "<center><table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" class=\"blanktab\" width=\"100%\"><tr>";
			$trip = 0;
			$i = 0;
			while ($i <= $max_face)
			{
				$file_name = $file_head . "f-" . $i . ".jpg";
				$file_name = $images_dir . 'faces' . $svr_dir_delimit . $file_name;
				if ($i == $char_face)
				{	echo "<td valign=\"top\" class=\"noborder\"><p><center><img src=\"$file_name\" width=\"180\" border=\"10\" class=\"dropmain\"></center></p></td>";	}
				else
				{	echo "<td valign=\"top\" class=\"noborder\"><p><center><a href=\"charchange.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=face&number=$i\" class=\"dropmain\"><img src=\"$file_name\" width=\"180\" border=\"0\"></a></center></p></td>";	}
				$trip++;
				if (($trip == 3) && ($i < $max_face))
				{
					$trip = 0;
					echo "</tr><tr>";
				}
				$i++;
			}
			while ($trip < 3)
			{
				echo "<td valign=\"top\" class=\"noborder\">&nbsp;</td>";
				$trip++;
			}
			echo "</tr></table></center>";
			echo "<center><hr width=\"80%\"></center>";
			
			echo "<center><h2><center>Hair</center></h2></center>";
			
			$i = 0;
			while ($i <= $max_colour)
			{
				echo "<center><table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" class=\"blanktab\" width=\"100%\"><tr>";
				$trip = 0;
				$i2 = 0;
				while ($i2 <= $max_hair)
				{
					$file_name = $file_head . $i . "-" . $i2 . ".jpg";
					$file_name = $images_dir . 'faces' . $svr_dir_delimit . $file_name;
					if (($i == $char_colour) && ($i2 == $char_hair))
					{	echo "<td valign=\"top\" class=\"noborder\"><p><center><img src=\"$file_name\" width=\"180\" border=\"10\" class=\"dropmain\"></center></p></td>";	}
					else
					{	echo "<td valign=\"top\" class=\"noborder\"><p><center><a href=\"charchange.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&charnum=$charnum&action=hair&number=$i-$i2\" class=\"dropmain\"><img src=\"$file_name\" width=\"180\" border=\"0\"></center></p></td>";	}
					$trip++;
					if (($trip == 3) && ($i2 < $max_hair))
					{
						$trip = 0;
						echo "</tr><tr>";
					}
					$i2++;
				}
				while ($trip < 3)
				{
					echo "<td valign=\"top\" class=\"noborder\">&nbsp;</td>";
					$trip++;
				}
				echo "</tr></table></center>";
				$i++;
			}
		}
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>