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
$adminshow = input_check($_REQUEST['adminshow'],0);
$i_style = input_check($_REQUEST['itemstyle'],0);
$i_searchid = input_check($_REQUEST['itemsrch'],1);
$i_sort = input_check($_REQUEST['sort'],0);
$shoponly = input_check($_REQUEST['shoponly'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($shoponly > 0)
	{	echo "<center><form method=\"post\" action=\"it-search.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemname\" type=\"hidden\" value=\"$itemname\"><input name=\"itemid\" type=\"hidden\" value=\"$itemid\"><input name=\"itemsort\" type=\"hidden\" value=\"$itemsort\"><input name=\"adminshow\" type=\"hidden\" value=\"$adminshow\"><input name=\"itemstyle\" type=\"hidden\" value=\"$i_style\"><input name=\"itemsrch\" type=\"hidden\" value=\"$i_searchid\"><input name=\"sort\" type=\"hidden\" value=\"$i_sort\"><input name=\"shoponly\" type=\"hidden\" value=\"0\"><input value=\" <- View All Items -> \" type=\"submit\" class=\"bigbut\"></form></center>";	}
	else
	{	echo "<center><form method=\"post\" action=\"it-search.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"itemname\" type=\"hidden\" value=\"$itemname\"><input name=\"itemid\" type=\"hidden\" value=\"$itemid\"><input name=\"itemsort\" type=\"hidden\" value=\"$itemsort\"><input name=\"adminshow\" type=\"hidden\" value=\"$adminshow\"><input name=\"itemstyle\" type=\"hidden\" value=\"$i_style\"><input name=\"itemsrch\" type=\"hidden\" value=\"$i_searchid\"><input name=\"sort\" type=\"hidden\" value=\"$i_sort\"><input name=\"shoponly\" type=\"hidden\" value=\"1\"><input value=\" <- View Sold Items -> \" type=\"submit\" class=\"bigbut\"></form></center>";	}
	// Connect to DB
	$con = mysql_connect($db_location,$db_user,$db_psswd);
	mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET character_set_results='utf8'", $con);
	if (!$con)
	{
		echo "Could Not Connect";
		die('Could not connect: ' . mysql_error());
	}		
	if (!mysql_select_db("$db_l2jdb",$con))
	{
		die('Could not change to L2J database: ' . mysql_error());
	}

	$order_by = "order by name";
	if ($i_sort == "2")
	{	$order_by = "order by name";	}
	elseif ($i_sort == "3")
	{	$order_by = "order by name desc";	}
	elseif ($i_sort == "4")
	{	$order_by = "order by weight, name";	}
	elseif ($i_sort == "5")
	{	$order_by = "order by weight desc, name desc";	}
	elseif ($i_sort == "6")
	{	$order_by = "order by price, name";	}
	elseif ($i_sort == "7")
	{	$order_by = "order by price desc, name desc";	}
	$order_by2 = $order_by;
	$order_by3 = $order_by;
	if ($i_sort == "8")
	{	$order_by = "order by p_def, name";	}
	elseif ($i_sort == "9")
	{	$order_by = "order by p_def desc, name desc";	}
	elseif ($i_sort == "10")
	{	$order_by = "order by m_def, name";	}
	elseif ($i_sort == "11")
	{	$order_by = "order by m_def desc, name desc";	}
	if ($i_sort == "12")
	{
		$order_by2 = "order by p_dam, name";	
		if ($i_searchid == 10)
		{	$order_by2 = "order by shield_def, name";		}
	}
	elseif ($i_sort == "13")
	{	
		$order_by2 = "order by p_dam desc, name desc";	
		if ($i_searchid == 10)
		{	$order_by2 = "order by shield_def desc, name desc";		}
	}
	elseif ($i_sort == "14")
	{	
		$order_by2 = "order by m_dam, name";	
		if ($i_searchid == 10)
		{	$order_by2 = "order by shield_def_rate, name";		}
	}
	elseif ($i_sort == "15")
	{	
		$order_by2 = "order by m_dam desc, name desc";	
		if ($i_searchid == 10)
		{	$order_by2 = "order by shield_def_rate desc, name desc";		}
	}
	elseif ($i_sort == "16")
	{	$order_by2 = "order by soulshots, name";	}
	elseif ($i_sort == "17")
	{	$order_by2 = "order by soulshots desc, name desc";	}
	elseif ($i_sort == "18")
	{	$order_by2 = "order by spiritshots, name";	}
	elseif ($i_sort == "19")
	{	$order_by2 = "order by spiritshots desc, name desc";	}
	elseif ($i_sort == "20")
	{	$order_by2 = "order by mp_consume, name";	}
	elseif ($i_sort == "21")
	{	$order_by2 = "order by mp_consume desc, name desc";	}
	elseif ($i_sort == "22")
	{	
		$order_by2 = "order by atk_speed, name";
		if ($i_searchid == 10)
		{	$order_by2 = "order by avoid_modify, name desc";		}	
	
	}
	elseif ($i_sort == "23")
	{	
		$order_by2 = "order by atk_speed desc, name desc";
		if ($i_searchid == 10)
		{	$order_by2 = "order by avoid_modify desc, name desc";		}	
	}
	elseif ($i_sort == "24")
	{	$order_by3 = "order by material, name";	}
	elseif ($i_sort == "25")
	{	$order_by3 = "order by material desc, name desc";	}
	elseif ($i_sort == "26")
	{	$order_by3 = "order by item_type, name";	}
	elseif ($i_sort == "27")
	{	$order_by3 = "order by item_type desc, name desc";	}

	echo "<center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr>
			<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">$lang_armour</strong></p></td>
			<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">$lang_weapon</strong></p></td>
			<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">$lang_other</strong></p></td>
			<td class=\"dropmain\"><p class=\"center\"><strong class=\"dropmain\">$lang_accessories</strong></p></td>";
	echo "</tr><tr>";
	echo "<td class=\"dropmain\"><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\">
			<option value=\"\">- $lang_sarmour -</option>";
$sql2 = "select distinct bodypart from knightarmour where bodypart <> \"neck\" and bodypart <> \"lbracelet\" and bodypart <> \"rbracelet\" and  bodypart <> \"deco1\" and  bodypart <> \"hair\" and  bodypart <> \"hair2\" and  bodypart <> \"hairall\" and  bodypart <> \"rear;lear\" and  bodypart <> \"rfinger;lfinger\" order by bodypart";
$result2 = mysql_query($sql2,$con);
			if (!$result2)
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			// If return array empty, then nothing found in armour.
			
			$count_r = mysql_num_rows($result2);
			$total_match = 0;
			if (mysql_fetch_array($result2))
			{
				$i=0;
				while ($i < $count_r) 
				{
					$i_wtype = mysql_result($result2,$i,"bodypart");
					$i_title = part_name($i_wtype);
					$i++;
					echo "<option value=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=1&itemsrch=$i_wtype&sort=$i_sort\">$i_title</option>";
				}
			}
			
	echo "			</select></p></td>
<td class=\"dropmain\"><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\">
			<option value=\"\">- $lang_sweapon -</option>";
$sql2 = "select distinct weaponType from knightweapon order by weaponType";
$result2 = mysql_query($sql2,$con);
			if (!$result2)
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			// If return array empty, then nothing found in armour.
			
			$count_r = mysql_num_rows($result2);
			$total_match = 0;
			if (mysql_fetch_array($result2))
			{
				$i=0;
				while ($i < $count_r) 
				{
					$i_wtype = mysql_result($result2,$i,"weaponType");
					$i_title = part_name($i_wtype);
					$i++;
					echo "<option value=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=2&itemsrch=$i_wtype&sort=$i_sort\">$i_title</option>";
				}
			}
echo "			</select></p></td>";
	echo "<td class=\"dropmain\"><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\">
			<option value=\"\">- $lang_sother -</option>";
$sql2 = "select distinct material from knightetcitem order by material";
$result2 = mysql_query($sql2,$con);
			if (!$result2)
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			// If return array empty, then nothing found in armour.
			
			$count_r = mysql_num_rows($result2);
			$total_match = 0;
			if (mysql_fetch_array($result2))
			{
				$i=0;
				while ($i < $count_r) 
				{
					$i_wtype = mysql_result($result2,$i,"material");
					$i_title = part_name($i_wtype);
					$i++;
					echo "<option value=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=3&itemsrch=$i_wtype&sort=$i_sort\">$i_title</option>";
				}
			}
			

	echo "</select></p></td><td class=\"dropmain\"><p class=\"dropmain\"><select onChange=\"document.location=options[selectedIndex].value;\">
			<option value=\"\">- $lang_saccessories -</option>";
$sql2 = "select distinct bodypart from knightarmour where bodypart = \"neck\" or bodypart = \"lbracelet\" or bodypart = \"rbracelet\" or  bodypart = \"deco1\" or  bodypart = \"hair\" or bodypart = \"hair2\" or  bodypart = \"hairall\" or  bodypart = \"rear;lear\" or  bodypart = \"rfinger;lfinger\" order by bodypart";
$result2 = mysql_query($sql2,$con);
			if (!$result2)
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			// If return array empty, then nothing found in armour.
			
			$count_r = mysql_num_rows($result2);
			$total_match = 0;
			if (mysql_fetch_array($result2))
			{
				$i=0;
				while ($i < $count_r) 
				{
					$i_wtype = mysql_result($result2,$i,"bodypart");
					$i_title = part_name($i_wtype);
					$i++;
					echo "<option value=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=1&itemsrch=$i_wtype&sort=$i_sort\">$i_title</option>";
				}
			}
	echo "</select></p></td></tr></table></center><p class=\"dropmain\">&nbsp;</p>";
		
	if ($i_style == 1)
	{
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
		if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
			echo "<td class=\"drophead\">&nbsp;</td>";
			echo "<td width=\"250\" class=\"lefthead\"><p class=\"dropmain\"><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=3\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_name</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=2\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=1\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Grade</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=0\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=9\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">P.Def</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=8\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=11\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">M.Def</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=10\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=5\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Weight</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=4\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shoponly=$shoponly&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=7\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Price</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=6\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
			echo "</tr><tr>";
		
		$sql = "select item_id, name, m_def, weight, price, p_def, crystal_type from knightarmour where bodypart = \"$i_searchid\" ";	
		$grade = 0;
		while ($grade < 6)
		{
			if ($i_sort < 2)
			{
				$grade_s = $grade;
				if ($i_sort == 1)
				{	$grade_s = 5 - $grade;	}
				if ($grade_s == 0)
				{	$sql_q = $sql . "and crystal_type = 'none' " . $order_by;	}
				elseif ($grade_s == 1)
				{	$sql_q = $sql . "and crystal_type = 'd' " . $order_by;	}
				elseif ($grade_s == 2)
				{	$sql_q = $sql . "and crystal_type = 'c' " . $order_by;	}
				elseif ($grade_s == 3)
				{	$sql_q = $sql . "and crystal_type = 'b' " . $order_by;	}
				elseif ($grade_s == 4)
				{	$sql_q = $sql . "and crystal_type = 'a' " . $order_by;	}
				elseif ($grade_s == 5)
				{	$sql_q = $sql . "and crystal_type = 's' " . $order_by;	}
			}
			else
			{
				$sql_q = $sql . $order_by;
				$grade = 5;
			}
			$result = mysql_query($sql_q,$con);
			if (!$result)
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			// If return array empty, then nothing found in armour.
			
			$count_r = mysql_num_rows($result);
			$total_match = 0;
			if (mysql_fetch_array($result))
			{
				$i=0;
				while ($i < $count_r) 
				{
					$i_id = mysql_result($result,$i,"item_id");
					$i_name = mysql_result($result,$i,"name");
					$i_bonus = mysql_result($result,$i,"m_def");
					$i_weight = mysql_result($result,$i,"weight");
					$i_price = comaise(mysql_result($result,$i,"price"));
					$i_pdef = mysql_result($result,$i,"p_def");
					$i_grade = mysql_result($result,$i,"crystal_type");
					$found = 1;
					if ($shoponly > 0)
					{	
						$result2 = mysql_query("select COUNT(*) from merchant_buylists where item_id = '$i_id' and (shop_id < 1001 or shop_id > 301000)",$con);
						$found = mysql_result($result2,0,"COUNT(*)");
					}
					if ($found > 0)
					{
						if ($user_access_lvl >= $sec_inc_gmlevel)
						{ echo "<td class=\"dropmain\">$i_id</td>"; }
						$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemname=$i_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($i_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($i_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($i_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($i_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($i_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						else
						{ echo "&nbsp;"; }
						echo "</td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_pdef</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_bonus</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						echo "</tr>";
					}
				$i++;
				}
			
			}
			$grade++;
		}
		echo "</table></center><p class=\"dropmain\">&nbsp;</p>";
	}

	if ($i_style == 2)
	{
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
		if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }

			echo "<td class=\"drophead\">&nbsp;</td>";
			echo "<td width=\"250\" class=\"lefthead\"><p class=\"dropmain\"><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=3\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_name</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=2\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=1\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Grade</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=0\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=13\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=15\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">P/M.atk</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=12\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=14\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=17\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=19\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=21\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br>";
			echo "<strong class=\"dropmain\">SS/SpS/MP</strong>";
			echo "<br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=16\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=18\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=20\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=23\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Speed</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=22\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=5\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Weight</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=4\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=7\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Price</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=6\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
			echo "</tr><tr>";
		$sql = "select item_id, name, crystal_type, price, weight, atk_speed, p_dam, m_dam, mp_consume, soulshots, spiritshots, avoid_modify, shield_def, shield_def_rate from knightweapon where weapontype = \"$i_searchid\" ";
		$grade = 0;
		while ($grade < 6)
		{
			if ($i_sort < 2)
			{
				$grade_s = $grade;
				if ($i_sort == 1)
				{	$grade_s = 5 - $grade;	}
				if ($grade_s == 0)
				{	$sql_q = $sql . "and crystal_type = 'none' " . $order_by2;	}
				elseif ($grade_s == 1)
				{	$sql_q = $sql . "and crystal_type = 'd' " . $order_by2;	}
				elseif ($grade_s == 2)
				{	$sql_q = $sql . "and crystal_type = 'c' " . $order_by2;	}
				elseif ($grade_s == 3)
				{	$sql_q = $sql . "and crystal_type = 'b' " . $order_by2;	}
				elseif ($grade_s == 4)
				{	$sql_q = $sql . "and crystal_type = 'a' " . $order_by2;	}
				elseif ($grade_s == 5)
				{	$sql_q = $sql . "and crystal_type = 's' " . $order_by2;	}
			}
			else
			{
				$sql_q = $sql . $order_by2;
				$grade = 5;
			}
			$result = mysql_query($sql_q,$con);
			if (!$result)
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}
			
			$count_r = mysql_num_rows($result);
			if (mysql_fetch_array($result))
			{
				$i=0;
				$total_match = 1;
				while ($i < $count_r) 
				{
					$i_id = mysql_result($result,$i,"item_id");
					$i_name = mysql_result($result,$i,"name");
					$i_grade = mysql_result($result,$i,"crystal_type");
					$i_price = comaise(mysql_result($result,$i,"price"));
					$i_weight = mysql_result($result,$i,"weight");
					$i_atkspd = mysql_result($result,$i,"atk_speed");
					$i_pdam = mysql_result($result,$i,"p_dam");
					$i_mdam = mysql_result($result,$i,"m_dam");
					$i_mpc = mysql_result($result,$i,"mp_consume");
					$i_ss = mysql_result($result,$i,"soulshots");
					$i_sps = mysql_result($result,$i,"spiritshots");
					$i_amod = mysql_result($result,$i,"avoid_modify");
					$i_sdef = mysql_result($result,$i,"shield_def");
					$i_sdefr = mysql_result($result,$i,"shield_def_rate");
					$found = 1;
					if ($shoponly > 0)
					{	
						$result2 = mysql_query("select COUNT(*) from merchant_buylists where item_id = '$i_id' and (shop_id < 1001 or shop_id > 301000)",$con);
						$found = mysql_result($result2,0,"COUNT(*)");
					}
					if ($found > 0)
					{
						if ($i_sdef)
						{
							$i_pdam = $i_sdef;
							$i_mdam = $i_sdefr;
							$i_atkspd = $i_amod;
						}
						if ($user_access_lvl >= $sec_inc_gmlevel)
						{	echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_id</p></td>"; }
						$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemname=$i_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($i_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($i_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($i_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($i_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($i_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						else
						{ echo "&nbsp;"; }
						echo "</td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_pdam&nbsp;/&nbsp;$i_mdam</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">x<font color=$green_code>$i_ss</font>&nbsp;/&nbsp;x<font color=#6B5D10>$i_sps</font>&nbsp;/&nbsp;<font color=$blue_code>$i_mpc</font></p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_atkspd</p></td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						echo "</tr>";
					}
				$i++;
				}
			}
			$grade++;
		}
		echo "</table></center><p class=\"dropmain\">&nbsp;</p>";
	}	
	
	if ($i_style == 3)
	{
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
		if ($user_access_lvl >= $sec_inc_gmlevel)
				{ echo "<td class=\"drophead\"><p class=\"dropmain\">ID</p></td>"; }
			echo "<td class=\"drophead\">&nbsp;</td>";
			echo "<td width=\"250\" class=\"lefthead\"><p class=\"dropmain\"><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=3\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">$lang_name</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=2\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=1\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Grade</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=0\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=25\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Material</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=24\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
		
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=5\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Weight</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=4\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\"><center><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=7\" class=\"dropmain\"><img src=\"" . $images_dir . "sortbutup.gif\" width=\"21\" height=\"12\" border=\"0\"></a><br><strong class=\"dropmain\">Price</strong><br><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&itemstyle=$i_style&itemsrch=$i_searchid&sort=6\"><img src=\"" . $images_dir . "sortbut.gif\" width=\"21\" height=\"12\" border=\"0\"></a></center></p></td>";
			echo "<td class=\"drophead\"><p class=\"dropmain\">Cln.Itm.F</p></td>";
			echo "</tr><tr>";			
		
		$sql = "select item_id, name, crystal_type, weight, material, price from knightetcitem where material = \"$i_searchid\" ";
		$grade = 0;
		while ($grade < 6)
		{
			if ($i_sort < 2)
			{
				$grade_s = $grade;
				if ($i_sort == 1)
				{	$grade_s = 5 - $grade;	}
				if ($grade_s == 0)
				{	$sql_q = $sql . "and crystal_type = 'none' " . $order_by3;	}
				elseif ($grade_s == 1)
				{	$sql_q = $sql . "and crystal_type = 'd' " . $order_by3;	}
				elseif ($grade_s == 2)
				{	$sql_q = $sql . "and crystal_type = 'c' " . $order_by3;	}
				elseif ($grade_s == 3)
				{	$sql_q = $sql . "and crystal_type = 'b' " . $order_by3;	}
				elseif ($grade_s == 4)
				{	$sql_q = $sql . "and crystal_type = 'a' " . $order_by3;	}
				elseif ($grade_s == 5)
				{	$sql_q = $sql . "and crystal_type = 's' " . $order_by3;	}
			}
			else
			{
				$sql_q = $sql . $order_by3;
				$grade = 5;
			}
			$result = mysql_query($sql_q,$con);
			if (!$result)
			{
				die('Could not retrieve from knightdrop database: ' . mysql_error());
			}

			
			$count_r = mysql_num_rows($result);
			if (mysql_fetch_array($result))
			{
				$i=0;
				$total_match = 1;
				
				while ($i < $count_r) 
				{
					$i_id = mysql_result($result,$i,"item_id");
					$i_name = mysql_result($result,$i,"name");
					$i_weight = mysql_result($result,$i,"weight");
					$i_price = comaise(mysql_result($result,$i,"price"));
					$i_grade = mysql_result($result,$i,"crystal_type");
					$i_mat = mysql_result($result,$i,"material");
					$found = 1;
					if ($shoponly > 0)
					{	
						$result2 = mysql_query("select COUNT(*) from merchant_buylists where item_id = '$i_id' and (shop_id < 1001 or shop_id > 301000)",$con);
						$found = mysql_result($result2,0,"COUNT(*)");
					}
					if ($found > 0)
					{
						if ($user_access_lvl >= $sec_inc_gmlevel)
							{ echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_id</p></td>"; }
						$i_id2 = item_check(0, $i_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
						echo "<td class=\"dropmain\"><img src=\"" . $images_dir . "items/$i_id2.gif\"></td>";
						echo "<td class=\"left\"><p class=\"dropmain\"><a href=\"i-search.php?itemname=$i_name&itemid=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\">$i_name</a>";
						check_item($i_id, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $langval, $language_array);
						echo "</p></td>";
						echo "<td class=\"dropmain\">";
						if ($i_grade == "s")
						{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
						elseif  ($i_grade == "a")
						{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
						elseif  ($i_grade == "b")
						{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
						elseif  ($i_grade == "c")
						{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
						elseif  ($i_grade == "d")
						{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
						else
						{ echo "&nbsp;"; }
						echo "</td>";
						echo "<td class=\"dropmain\"><p class=\"dropmain\">";
						if ($i_mat == "adamantaite")
						{ echo "<img src=\"" . $images_dir . "items/1024.gif\" title=\"adamantaite\">"; }
						elseif ($i_mat == "liquid")
						{ echo "<img src=\"" . $images_dir . "items/1764.gif\" title=\"liquid\">"; }
						elseif ($i_mat == "paper")
						{ echo "<img src=\"" . $images_dir . "items/1695.gif\" title=\"paper\">"; }
						elseif ($i_mat == "crystal")
						{ echo "<img src=\"" . $images_dir . "items/3365.gif\" title=\"crystal\">"; }
						elseif ($i_mat == "steel")
						{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"steel\">"; }
						elseif ($i_mat == "fine_steel")
						{ echo "<img src=\"" . $images_dir . "items/1880.gif\" title=\"fine_steel\">"; }
						elseif ($i_mat == "bone")
						{ echo "<img src=\"" . $images_dir . "items/1872.gif\" title=\"bone\">"; }
						elseif ($i_mat == "bronze")
						{ echo "<img src=\"" . $images_dir . "items/626.gif\" title=\"bronze\">"; }
						elseif ($i_mat == "cloth")
						{ echo "<img src=\"" . $images_dir . "items/1729.gif\" title=\"cloth\">"; }
						elseif ($i_mat == "gold")
						{ echo "<img src=\"" . $images_dir . "items/1289.gif\" title=\"gold\">"; }
						elseif ($i_mat == "leather")
						{ echo "<img src=\"" . $images_dir . "items/1689.gif\" title=\"leather\">"; }
						elseif ($i_mat == "mithril")
						{ echo "<img src=\"" . $images_dir . "items/1876.gif\" title=\"mithril\">"; }
						elseif ($i_mat == "silver")
						{ echo "<img src=\"" . $images_dir . "items/1873.gif\" title=\"silver\">"; }
						elseif ($i_mat == "wood")
						{ echo "<img src=\"" . $images_dir . "items/2109.gif\" title=\"wood\">"; }
						else
						{ echo "$i_mat"; }
						echo "</p></td>";
						
						echo "<td class=\"dropmain\"><p class=\"dropmain\">$i_weight</p></td>";
						echo "<td class=\"dropmain\"><p class=\"right\">$i_price</p></td>";
						echo "<td class=\"dropmain\"><center><a href=\"ci-search.php?itemname=$itemname&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$i_id\" class=\"dropmain\"><img src=\"" . $images_dir . "butright.jpg\" width=\"25\" height=\"23\" border=\"0\"></a></center></td>";
						echo "</tr>";
					}
				$i++;
				}
			}
			$grade++;
		}
		echo "</table></center><p class=\"dropmain\">&nbsp;</p>";
	}

}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
