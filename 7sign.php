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


include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$action = input_check($_REQUEST['action'],0);
$linenum = input_check($_REQUEST['linenum'],0);
$sendchat = input_check($_REQUEST['sendchat'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_sevens\" type=\"submit\" class=\"bigbut2\"></form></td>";
	if ($top_ten)
	{	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_topten\" type=\"submit\" class=\"bigbut2\"></form></td>";	}
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"trusted.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_trustedp\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"reference.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_classtree\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"castles.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_caststat\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"clanhall.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Clan Halls\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "</tr></table>";

	echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">$lang_sevens</h2><p class=\"dropmain\">&nbsp;</p>";
	
// -------------------
//
// Start of Daedalus clock code.
//
// -------------------

@mysql_connect ( $db_location, $db_user, $db_psswd ) or die ('Coudn\'t connect to host');
@mysql_select_db( $db_l2jdb ) or die ('Couldn\'t select database');

$sql = @mysql_query('SELECT active_period,date,dawn_stone_score,dusk_stone_score,dawn_festival_score,dusk_festival_score,avarice_owner,gnosis_owner,strife_owner FROM `seven_signs_status`') or die('Query failed!');
$query = @mysql_fetch_array($sql);

$twilScore = $query['dawn_stone_score'] + $query['dawn_festival_score'];
$dawnScore = $query['dusk_stone_score'] + $query['dusk_festival_score'];
$totalScore = $query['dawn_stone_score'] + $query['dusk_stone_score'] + $query['dawn_festival_score'] + $query['dusk_festival_score'];

$dawnPoint = ($totalScore == 0) ? 0 : round(($dawnScore / $totalScore) * 1000);
$twilPoint = ($totalScore == 0) ? 0 : round(($twilScore / $totalScore) * 1000);

$weekday = '\'' . date("w",$currTime) . '\'';
$nthDay = date("w")+1;
$currTime11 = '\'' . date('m/d/Y h:iA T') . '\'';
$currTime = '\'' . date('d.m.Y H:i') . '\'';
$ssStatus = $query['active_period'];

$maxPointWidth = 150;
$seal1 = $query['avarice_owner'];
$seal2 = $query['gnosis_owner'];
$seal3 = $query['strife_owner'];

echo "
<!-- Generation Script -->
<script language=\"javascript\" type=\"text/javascript\">";
echo "</script>
<center><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"569\">
  <tr valign=\"top\">
    <td style=\"background: url(" . $images_dir . "ssqviewbg.jpg)\" height=\"225\"><table>
          <tr valign=\"top\">
            <td><table style=\"margin: 18px 0px 0px 54px\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"141\">
                  <tr align=\"middle\" height=\"26\">
                    <td style=\"background: url(" . $images_dir . "ssqviewimg1.gif); COLOR:#fff; font-size:11px;\">
						<script language=\"JavaScript\" type=\"text/javascript\">
						if (0 == $ssStatus) {
						document.write('Preparation');
						}
						else if (1 == $ssStatus) {
						document.write('Competition <b style=\"color:#E10000\"> $lang_day ' + $nthDay + '</b>');
						}
						else if (2 == $ssStatus) {
						document.write('Calculation');
						}
						else if (3 == $ssStatus) {
						document.write('Seal Effect <b style=\"color:#E10000\"> $lang_day ' + $nthDay + '</b>');
						}
						</script>
					</td>
                  </tr>
              </table>
              <table style=\"margin: 3px 0px 0px 10px\" cellspacing=\"0\" cellpadding=\"0\" width=\"141\" border=\"0\">
                  <tr>
                    <td></td>
                    <td><img height=\"16\" src=\"" . $images_dir . "timebox1.jpg\" width=\"140\" border=\"0\" /></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td valign=\"bottom\" rowspan=\"2\"><img height=\"125\" src=\"" . $images_dir . "timebox2.jpg\" width=\"45\" border=\"0\" /></td>
                    <td>
						<script language=\"JavaScript\" type=\"text/javascript\">
						var timeImage;
						var tempImageNum;
						
						if (1 == $ssStatus) {
							tempImageNum = $nthDay;
						}
						else if (0 == $ssStatus) {
							tempImageNum = 0;
						}
						else if (3 == $ssStatus || 2 == $ssStatus) {
							tempImageNum = $nthDay + 7;
						}
						
						timeImage = 'time'+tempImageNum+'.jpg';
						document.write('<img src=\"" . $images_dir . "'+ timeImage +'\" width=\"140\" height=\"139\" border=\"0\">');									
						</script>
					</td>
                    <td valign=\"bottom\" rowspan=\"2\"><img height=\"125\" src=\"" . $images_dir . "timebox3.jpg\" width=\"66\" border=\"0\" /></td>
                  </tr>
                  <tr> 
                    <td><img height=\"12\" src=\"" . $images_dir . "timebox4.jpg\" width=\"140\" border=\"0\" /></td>
                  </tr>
              </table></td>
            <td><table style=\"margin: 27px 0px 0px 22px\" cellspacing=\"0\" cellpadding=\"0\" width=\"200\" border=\"0\">
                  <tr align=\"middle\" bgcolor=\"#606d6f\" height=\"17\">
                    <td style=\"color:#fff; font-size:11px;\">
						<script language=\"JavaScript\" type=\"text/javascript\">
						document.write ($currTime);
						</script>
					</td>
                  </tr>
              </table>
              <table style=\"margin: 21px 0px 0px 22px\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                <colgroup>
                <col width=\"74\" />
                <col width=\"*\" />
                </colgroup>
                  <tr>
                    <td style=\"font-size:11px; color:#000;\"><img style=\"margin: 0px 6px 5px 0px\" height=\"1\" src=\"" . $images_dir . "dot.gif\" width=\"1\" border=\"0\" />$lang_dawn</td>
                    <td style=\"color: #000; font-size:11px;\">
						<script language=\"JavaScript\" type=\"text/javascript\">
						var twilPointWidth = $maxPointWidth * $twilPoint / 1000;
						document.write('<img src=\"" . $images_dir . "ssqbar2.gif\" width=\"' + twilPointWidth + '\" height=\"9\" border=\"0\"> ' + $twilPoint);
						</script>
					</td>
                  </tr>
                  <tr>
                    <td colspan=\"2\" height=\"7\"></td>
                  </tr>
                  <tr> 
                    <td style=\"font-size:11px; color:#000;\"><img style=\"margin: 0px 6px 5px 0px\" height=\"1\" src=\"" . $images_dir . "dot.gif\" width=\"1\" border=\"0\" />$lang_dusk</td>
                    <td style=\"color: #000; font-size:11px;\">
						<script language=\"JavaScript\" type=\"text/javascript\">
						var dawnPointWidth = $maxPointWidth * $dawnPoint / 1000;
						document.write('<img src=\"" . $images_dir . "ssqbar1.gif\" width=\"' + dawnPointWidth + '\" height=\"9\" border=\"0\"> ' + $dawnPoint);
						</script>
					</td>
                  </tr>
              </table>
              <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
                  <tr valign=\"bottom\" align=\"middle\" height=\"95\">
                    <td>
						<script language=\"JavaScript\" type=\"text/javascript\">
						if (3 == $ssStatus)
						{
							if (0 == $seal1)
								document.write('<img src=\"" . $images_dir . "bongin1close.gif\" width=\"85\" height=\"86\" border=\"0\">');
							else
								document.write('<img src=\"" . $images_dir . "bongin1open.gif\" width=\"85\" height=\"86\" border=\"0\">');
						}else{
							document.write('<img src=\"" . $images_dir . "bongin1.gif\" width=\"85\" height=\"86\" border=\"0\">');
						}
						</script>
					</td><td>
						<script language=\"JavaScript\" type=\"text/javascript\">
						if (3 == $ssStatus)
						{
							if (0 == $seal2)
								document.write('<img src=\"" . $images_dir . "bongin2close.gif\" width=\"85\" height=\"86\" border=\"0\">');
							else
								document.write('<img src=\"" . $images_dir . "bongin2open.gif\" width=\"85\" height=\"86\" border=\"0\">');
						}else{
							document.write('<img src=\"" . $images_dir . "bongin2.gif\" width=\"85\" height=\"86\" border=\"0\">');
						}
						</script>
					</td><td>
						<script language=\"JavaScript\" type=\"text/javascript\">
						if (3 == $ssStatus)
						{
							if (0 == $seal3)
								document.write('<img src=\"" . $images_dir . "bongin3close.gif\" width=\"85\" height=\"86\" border=\"0\">');
							else
								document.write('<img src=\"" . $images_dir . "bongin3open.gif\" width=\"85\" height=\"86\" border=\"0\">');
						}else{
							document.write('<img src=\"" . $images_dir . "bongin3.gif\" width=\"85\" height=\"86\" border=\"0\">');
						}
						</script>
					</td> 
                  </tr> 
                  <tr> 
                    <td colspan=\"3\"><div align=\"center\" style=\"margin-left:10px;\"><img height=\"16\" src=\"" . $images_dir . "bonginname.gif\" width=\"258\" border=\"0\" /> </div></td> 
                  </tr> 
              </table></td> 
          </tr> 
      </table></td></tr></table></center> ";
	  
// -------------------
//
// End of Daedalus clock code.
//
// -------------------

if (($sevensignall) || ($user_access_lvl >= $sec_inc_gmlevel))		// If the user is a GM, or detailed 7Sign stats has been allowed ...
{
	$dawn_1_r = 0;		// Initialise all counts.
	$dawn_1_g = 0;
	$dawn_1_b = 0;
	$dawn_1_a = 0;
	$dawn_2_r = 0;
	$dawn_2_g = 0;
	$dawn_2_b = 0;
	$dawn_2_a = 0;
	$dawn_3_r = 0;
	$dawn_3_g = 0;
	$dawn_3_b = 0;
	$dawn_3_a = 0;
	$dusk_1_r = 0;
	$dusk_1_g = 0;
	$dusk_1_b = 0;
	$dusk_1_a = 0;
	$dusk_2_r = 0;
	$dusk_2_g = 0;
	$dusk_2_b = 0;
	$dusk_2_a = 0;
	$dusk_3_r = 0;
	$dusk_3_g = 0;
	$dusk_3_b = 0;
	$dusk_3_a = 0;

	$con = mysql_connect($db_location,$db_user,$db_psswd);		// Connect to the gameserver database.
	mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{	die('Could not change to L2J database: ' . mysql_error());	}
	
	// Try and find the total number of stones collected for Dawn that matter in this period for seal 1.
	$result = mysql_query("select sum(red_stones), sum(green_stones), sum(blue_stones) from seven_signs where cabal='dawn' and seal='1'",$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$dawn_1_r = mysql_result($result,0,"sum(red_stones)");
			$dawn_1_g = mysql_result($result,0,"sum(green_stones)");
			$dawn_1_b = mysql_result($result,0,"sum(blue_stones)");
		}
	}
	// Try and find the total number of stones collected for Dawn that matter in this period for seal 2.
	$result = mysql_query("select sum(red_stones), sum(green_stones), sum(blue_stones) from seven_signs where cabal='dawn' and seal='2'",$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$dawn_2_r = mysql_result($result,0,"sum(red_stones)");
			$dawn_2_g = mysql_result($result,0,"sum(green_stones)");
			$dawn_2_b = mysql_result($result,0,"sum(blue_stones)");
		}
	}
	// Try and find the total number of stones collected for Dawn that matter in this period for seal 3.
	$result = mysql_query("select sum(red_stones), sum(green_stones), sum(blue_stones) from seven_signs where cabal='dawn' and seal='3'",$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$dawn_3_r = mysql_result($result,0,"sum(red_stones)");
			$dawn_3_g = mysql_result($result,0,"sum(green_stones)");
			$dawn_3_b = mysql_result($result,0,"sum(blue_stones)");
		}
	}
	// Try and find the total number of stones collected for Dusk that matter in this period for seal 1.
	$result = mysql_query("select sum(red_stones), sum(green_stones), sum(blue_stones) from seven_signs where cabal='dusk' and seal='1'",$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$dusk_1_r = mysql_result($result,0,"sum(red_stones)");
			$dusk_1_g = mysql_result($result,0,"sum(green_stones)");
			$dusk_1_b = mysql_result($result,0,"sum(blue_stones)");
		}
	}
	// Try and find the total number of stones collected for Dusk that matter in this period for seal 2.
	$result = mysql_query("select sum(red_stones), sum(green_stones), sum(blue_stones) from seven_signs where cabal='dusk' and seal='2'",$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$dusk_2_r = mysql_result($result,0,"sum(red_stones)");
			$dusk_2_g = mysql_result($result,0,"sum(green_stones)");
			$dusk_2_b = mysql_result($result,0,"sum(blue_stones)");
		}
	}
	// Try and find the total number of stones collected for Dusk that matter in this period for seal 3.
	$result = mysql_query("select sum(red_stones), sum(green_stones), sum(blue_stones) from seven_signs where cabal='dusk' and seal='3'",$con);
	if ($result)
	{
		$count = mysql_num_rows($result);
		if ($count)
		{
			$dusk_3_r = mysql_result($result,0,"sum(red_stones)");
			$dusk_3_g = mysql_result($result,0,"sum(green_stones)");
			$dusk_3_b = mysql_result($result,0,"sum(blue_stones)");
		}
	}

	$dawn_1_points = comaise((10 * $dawn_1_r) + (5 * $dawn_1_g) + (3 * $dawn_1_b));	// Convert all the totals ready for display.
	$dawn_2_points = comaise((10 * $dawn_2_r) + (5 * $dawn_2_g) + (3 * $dawn_2_b));
	$dawn_3_points = comaise((10 * $dawn_3_r) + (5 * $dawn_3_g) + (3 * $dawn_3_b));
	$dusk_1_points = comaise((10 * $dusk_1_r) + (5 * $dusk_1_g) + (3 * $dusk_1_b));
	$dusk_2_points = comaise((10 * $dusk_2_r) + (5 * $dusk_2_g) + (3 * $dusk_2_b));
	$dusk_3_points = comaise((10 * $dusk_3_r) + (5 * $dusk_3_g) + (3 * $dusk_3_b));
	
	$dawn_1_r = comaise($dawn_1_r);		// Convert the individual results ready for display.
	$dawn_1_g = comaise($dawn_1_g);
	$dawn_1_b = comaise($dawn_1_b);
	$dawn_2_r = comaise($dawn_2_r);
	$dawn_2_g = comaise($dawn_2_g);
	$dawn_2_b = comaise($dawn_2_b);
	$dawn_3_r = comaise($dawn_3_r);
	$dawn_3_g = comaise($dawn_3_g);
	$dawn_3_b = comaise($dawn_3_b);
	$dusk_1_r = comaise($dusk_1_r);
	$dusk_1_g = comaise($dusk_1_g);
	$dusk_1_b = comaise($dusk_1_b);
	$dusk_2_r = comaise($dusk_2_r);
	$dusk_2_g = comaise($dusk_2_g);
	$dusk_2_b = comaise($dusk_2_b);
	$dusk_3_r = comaise($dusk_3_r);
	$dusk_3_g = comaise($dusk_3_g);
	$dusk_3_b = comaise($dusk_3_b);

	// Averice seal percentages
	if (($dawn_1_points) || ($dusk_1_points))	// If either have got points, then the calculation for percentages can work, otherwise
	{											// it can end in divide by zero.
		$dawn_a_per = intval((100 / ($dawn_1_points + $dusk_1_points)) * $dawn_1_points);
		$dusk_a_per = 100 - $dawn_a_per;
	}
	else
	{
		$dawn_a_per = 0;
		$dusk_a_per = 0;
	}
	
	// Gnosis seal percentages
	if (($dawn_2_points) || ($dusk_2_points))	// If either have got points, then the calculation for percentages can work, otherwise
	{											// it can end in divide by zero.
		$dawn_g_per = intval((100 / ($dawn_2_points + $dusk_2_points)) * $dawn_2_points);
		$dusk_g_per = 100 - $dawn_g_per;
	}
	else
	{
		$dawn_g_per = 0;
		$dusk_g_per = 0;
	}
	
	// Strife seal percentages
	if (($dawn_3_points) || ($dusk_3_points))	// If either have got points, then the calculation for percentages can work, otherwise
	{											// it can end in divide by zero.
		$dawn_s_per = intval((100 / ($dawn_3_points + $dusk_3_points)) * $dawn_3_points);
		$dusk_s_per = 100 - $dawn_s_per;
	}
	else
	{
		$dawn_s_per = 0;
		$dusk_s_per = 0;
	}

	// Retrieve the festival scores.
	$result = mysql_query("select dawn_stone_score, dawn_festival_score, dusk_stone_score, dusk_festival_score from seven_signs_status",$con);
	$dawn_fes = mysql_result($result,0,"dawn_festival_score");
	$dusk_fes = mysql_result($result,0,"dusk_festival_score");
	$dawn_stone = mysql_result($result,0,"dawn_stone_score");
	$dusk_stone = mysql_result($result,0,"dusk_stone_score");
	
	// Overall points for the festivals.
	if (($dawn_fes) || ($dusk_fes))		// If either have got points, then the calculation for percentages can work, otherwise
	{									// it can end in divide by zero.
		$dawn_fes_point = intval((500 / ($dawn_fes + $dusk_fes)) * $dawn_fes);
		$dusk_fes_point = 500 - $dawn_fes_point;
	}
	else
	{
		$dusk_fes_point = 0;
		$dawn_fes_point = 0;
	}
	
	// Overall points for the stones.
	if (($dawn_stone) || ($dusk_stone))		// If either have got points, then the calculation for percentages can work, otherwise
	{									// it can end in divide by zero.
		$dawn_stone_point = intval((500 / ($dawn_stone + $dusk_stone)) * $dawn_stone);
		$dusk_stone_point = 500 - $dawn_stone_point;
	}
	else
	{
		$dusk_stone_point = 0;
		$dawn_stone_point = 0;
	}
	
	// Percentages for the dawn and dusk paricpation in the festivals and the stones.
	$dawn_fes_pcnt = $dawn_fes_point / 5;
	$dusk_fes_pcnt = $dusk_fes_point / 5;
	$dawn_stone_pcnt = $dawn_stone_point / 5;
	$dusk_stone_pcnt = $dusk_stone_point / 5;
	
	// Percentages and totals for all scores properly rounded.
	$total_score = $dusk_fes_point + $dawn_fes_point + $dusk_stone_point + $dawn_stone_point;
	if ($total_score)
	{
		$dawn_total = $dawn_fes_point + $dawn_stone_point;
		$dusk_total = $dusk_fes_point + $dusk_stone_point;
		$dawn_total_pcnt =  intval(((100 / $total_score) * ($dawn_fes_point + $dawn_stone_point))*100)/100;
		$dawn_total = intval($dawn_total_pcnt * 10);
		$dusk_total = 1000 - $dawn_total;
		$dawn_total_pcnt = intval($dawn_total);
		$dusk_total_pcnt = 1000 - $dawn_total_pcnt;
		$dawn_total_pcnt = $dawn_total_pcnt / 10;
		$dusk_total_pcnt = $dusk_total_pcnt / 10;
	}
	else
	{
		$dawn_total = 0;
		$dusk_total = 0;
		$dawn_total_pcnt = 0;
		$dusk_total_pcnt = 0;
	}
	
	// Display the extended results table.
	echo "<p class=\"dropmain\">&nbsp;</p><center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\" width=\"100%\">\n
	<tr><td class=\"dropmain\">&nbsp;</td><td class=\"dropmain\" colspan=\"5\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$lang_stones</strong><table><tr><td><p class=\"dropmain\"><font color=$blue_code>$lang_dawn</font><br><font color=$red_code>$lang_dusk</font></p></td><td><p class=\"dropmain\"><font color=$blue_code>- $dawn_stone_point</font><br><font color=$red_code>- $dusk_stone_point</font></p></td><td><p class=\"dropmain\"><font color=$blue_code>- $dawn_stone_pcnt%</font><br><font color=$red_code>- $dusk_stone_pcnt%</font></p></p></td></tr></table></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td>\n
	<td class=\"dropmain\" colspan=\"5\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$lang_festival</strong><table><tr><td><p class=\"dropmain\"><font color=$blue_code>$lang_dawn</font><br><font color=$red_code>$lang_dusk</font></p></td><td><p class=\"dropmain\"><font color=$blue_code>- $dawn_fes_point</font><br><font color=$red_code>- $dusk_fes_point</font></p></td><td><p class=\"dropmain\"><font color=$blue_code>- $dawn_fes_pcnt%</font><br><font color=$red_code>- $dusk_fes_pcnt%</font></p></p></td></tr></table></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td>\n
	<td class=\"dropmain\" colspan=\"5\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$lang_total</strong><table><tr><td><p class=\"dropmain\"><font color=$blue_code>$lang_dawn</font><br><font color=$red_code>$lang_dusk</font></p></td><td><p class=\"dropmain\"><font color=$blue_code>- $dawn_total</font><br><font color=$red_code>- $dusk_total</font></p></td><td><p class=\"dropmain\"><font color=$blue_code>- $dawn_total_pcnt%</font><br><font color=$red_code>- $dusk_total_pcnt%</font></p></p></td></tr></table></center></td>\n
	<tr><td class=\"dropmain\">&nbsp;</td><td class=\"dropmain\" colspan=\"5\"><center><p class=\"dropmain\"><table><tr><td><p class=\"dropmain\"><center><font color=$blue_code>$lang_dawn<br>$dawn_a_per%</font></center></p></td><td><img src=\"" . $images_dir . "blank.gif\" width=\"30\"></td><td><p class=\"dropmain\"><center><font color=$red_code>$lang_dusk<br>$dusk_a_per%</font></center></p></td></tr></table><img src=\"" . $images_dir . "seal-a.gif\"><br><strong class=\"dropmain\">Avarice</strong></p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td><td class=\"dropmain\" colspan=\"5\"><center><p class=\"dropmain\"><table><tr><td><p class=\"dropmain\"><center><font color=$blue_code>$lang_dawn<br>$dawn_g_per%</font></center></p></td><td><img src=\"" . $images_dir . "blank.gif\" width=\"30\"></td><td><p class=\"dropmain\"><center><font color=$red_code>$lang_dusk<br>$dusk_g_per%</font></center></p></td></tr></table><img src=\"" . $images_dir . "seal-g.gif\"><br><strong class=\"dropmain\">Gnosis</strong></p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td><td class=\"dropmain\" colspan=\"5\"><center><p class=\"dropmain\"><table><tr><td><p class=\"dropmain\"><center><font color=$blue_code>$lang_dawn<br>$dawn_s_per%</font></center></p></td><td><img src=\"" . $images_dir . "blank.gif\" width=\"30\"></td><td><p class=\"dropmain\"><center><font color=$red_code>$lang_dusk<br>$dusk_s_per%</font></center></p></td></tr></table><img src=\"" . $images_dir . "seal-s.gif\"><br><strong class=\"dropmain\">Strife</strong></p></center></td></tr>\n
	<tr><td class=\"dropmain\">&nbsp;</td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6362.gif\"></center></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6361.gif\"></center></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6360.gif\"></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$lang_points</strong></p></center></td>\n
	<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6362.gif\"></center></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6361.gif\"></center></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6360.gif\"></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$lang_points</strong></p></center></td>\n
	<td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6362.gif\"></center></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6361.gif\"></center></td><td class=\"dropmain\"><center><img src=\"" . $images_dir . "items/6360.gif\"></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong>$lang_points</strong></p></center></td></tr>\n
	<tr><td class=\"dropmain\"><center><img src=\"" . $images_dir . "priest-dawn.jpg\"></center></td><td class=\"dropmain\"<center><p class=\"dropmain\">$dawn_1_r</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_1_g</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_1_b</p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$dawn_1_points</strong></p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td>\n
	<td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_2_r</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_2_g</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_2_b</p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$dawn_2_points</strong></p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\">\n
	</td><td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_3_r</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_3_g</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dawn_3_b</p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$dawn_3_points</p></strong></center></td></tr>\n
	<tr><td class=\"dropmain\"><center><img src=\"" . $images_dir . "priest-dusk.jpg\"></center></td><td class=\"dropmain\"<center><p class=\"dropmain\">$dusk_1_r</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_1_g</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_1_b</p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$dusk_1_points</strong></p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\"></td>\n
	<td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_2_r</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_2_g</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_2_b</p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$dusk_2_points</strong></p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"5\">\n
	</td><td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_3_r</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_3_g</p></center></td><td class=\"dropmain\"><center><p class=\"dropmain\">$dusk_3_b</p></center></td><td class=\"dropmain\"><img src=\"" . $images_dir . "blank.gif\" width=\"1\"></td><td class=\"dropmain\"><center><p class=\"dropmain\"><strong class=\"dropmain\">$dusk_3_points</p></strong></center></td></tr>\n
	</table></center>\n";
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
