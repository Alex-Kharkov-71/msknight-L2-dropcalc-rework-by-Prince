<?php 
error_reporting(E_ERROR | E_PARSE);

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
$action = input_check($_REQUEST['action'],0);
$shopid = input_check($_REQUEST['shopid'],0);
$view = input_check($_REQUEST['view'] ?? '',0);
if (($view != 'nospawn') && ($view != 'all'))
{	$view = '';	}
$itemid = input_check($_REQUEST['itemid'],0);
$price = input_check($_REQUEST['price'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $adjust_shop)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
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
			echo "Could Not Connect";
			die('Could not connect: ' . mysql_error());
		}		
		if (!mysql_select_db("$db_l2jdb",$con))
		{
			die('Could not change to L2J database: ' . mysql_error());
		}
		echo "<h2 class=\"dropmain\">Shops</h2>";

		// PHP8/L2JMobius fix: redaktirovanie magazinov (delete/change/up/down/add)
		// pravilo tablitsu merchant_buylists igrovogo servera. V L2JMobius magaziny
		// zadany v XML datapaka (data/buylists), tablitsy net - redaktirovat nechego.
		// Stranitsa ostayotsya tolko dlya prosmotra (knightbuylist).
		if ($action != "")
		{	echo "<h2 class=\"dropmain\">Editing shops from here is not possible: L2JMobius keeps shops in the XML datapack (data/buylists). Edit the XML and re-run Server Utilities - Import Buylists.</h2>";	}

		$view_where = "";
		if ($view == '')
		{	$view_where = "where shop_id in (select npc_templateid from knightspawnlist)";	}
		elseif ($view == 'nospawn')
		{	$view_where = "where shop_id not in (select npc_templateid from knightspawnlist)";	}
		$sql = "select distinct shop_id, shop_id as npc_id from knightbuylist $view_where order by shop_id";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);

		if (!$shopid)
		{
			$vl = array('' => 'Shops with a spawn (normal)', 'nospawn' => 'Without a spawn', 'all' => 'All shops');
			echo "<p class=\"dropmain\">";
			foreach ($vl as $vk => $vt)
			{
				if ($vk == $view)
				{	echo "<strong class=\"dropmain\">[ $vt ]</strong>&nbsp;&nbsp;";	}
				else
				{	echo "<a class=\"dropmain\" href=\"shops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&view=$vk\">$vt</a>&nbsp;&nbsp;";	}
			}
			echo " &nbsp; ($count)</p>";
			echo "<table class=\"blanktab\" width=\"100%\"><tr><td class=\"noborderback\" valign=\"top\"><center><table border=\"0\" cellpadding=\"5\" cellspacing = \"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">ID</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Location</strong></p></td></tr>";
			$i=0;
			$target = intval($count / 4) + 1;
			$count3 = 0;
			while ($i < $count)
			{
				$shop_id = mysql_result($result,$i,"shop_id");
				$shop_owner = mysql_result($result,$i,"npc_id");
				$shop_loc = intval($shop_owner);
				$sql = "select name from knightnpc where id = '$shop_owner'";
				$result2 = mysql_query($sql,$con);
				$count2 = mysql_num_rows($result2);
				if ($count2)
				{
					$shop_owner = mysql_result($result2,0,"name");
				}
		
				$shop_location = "$lang_unknown";
				if ($shop_loc)
				{
					if ($shop_loc < 10000)
					{	$shop_loc = 1000000 + $shop_loc;	}
					$shop_location = shop_loc($shop_loc, $db_location, $db_user, $db_psswd, $db_l2jdb, $lang_unknown);
					$chk_npc = mysql_query("select 1 from knightnpc where id = '" . (($shop_loc >= 1000000) ? ($shop_loc - 1000000) : intval($shop_loc)) . "'", $con);
					if ($chk_npc && !mysql_num_rows($chk_npc))
					{	$shop_location = "Buylist without NPC";	}
							
				}
				echo "<tr><td class=\"dropmain\">$shop_id</td><td class=\"dropmain\"><a href=\"shops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&shopid=$shop_id\" class=\"dropmain\">$shop_owner</a></td><td class=\"dropmain\">$shop_location</td></tr>";
				$i++;
				$count3++;
				if (($count3 >= $target) && ($i < $count))
				{
					echo "</tr></table></center></td><td class=\"noborderback\" valign=\"top\"><center><table border=\"0\" cellpadding=\"5\" cellspacing = \"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">ID</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Location</strong></p></td></tr>";
					$count3 = 0;
				}
			}
			echo "</table></center></td></tr></table>";
		}
		else
		{
			$sql = "select distinct shop_id, shop_id as npc_id from knightbuylist where shop_id = '$shopid'";
			$result = mysql_query($sql,$con);
			$shop_id = mysql_result($result,0,"shop_id");
			$shop_owner = mysql_result($result,0,"npc_id");
			$shop_loc = intval($shop_owner);
			$shop_location = "$lang_unknown";
			if ($shop_loc)
			{
				if ($shop_loc < 10000)
				{	$shop_loc = 1000000 + $shop_loc;	}
				$shop_location = shop_loc($shop_loc, $db_location, $db_user, $db_psswd, $db_l2jdb, $lang_unknown);
				$chk_npc = mysql_query("select 1 from knightnpc where id = '" . (($shop_loc >= 1000000) ? ($shop_loc - 1000000) : intval($shop_loc)) . "'", $con);
				if ($chk_npc && !mysql_num_rows($chk_npc))
				{	$shop_location = "Buylist without NPC";	}
			}
			$sql = "select name from knightnpc where id = '$shop_owner'";
			$result2 = mysql_query($sql,$con);
			$count2 = mysql_num_rows($result2);
			if ($count2)
			{
				$shop_owner = mysql_result($result2,0,"name");
			}
			echo "<h2 class=\"dropmain\">ID $shop_id - $shop_owner - $shop_location</h2>";


			$sql = "select item_id, price, 0 as `order` from knightbuylist where shop_id = '$shop_id'";

			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			echo "<table class=\"blanktab\" width=\"100%\"><tr><td class=\"noborderback\" valign=\"top\"><center><table border=\"0\" cellpadding=\"5\" cellspacing = \"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">ID</strong></p></td><td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Grade</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Price</strong></p></td></tr>";
			$i = 0;
			$target = intval($count / 2);
			if (($target * 2) < $count)
			{	$target++;	}
			$count2 = 0;
			while ($i < $count)
			{
				$item_id = mysql_result($result,$i,"item_id");
				$item_price = mysql_result($result,$i,"price");
				if ($item_price === null || $item_price === "")
				{	// price v XML ne zadan - znachit bazovaya tsena predmeta
					$rp = mysql_query("select price from knightarmour where item_id = '$item_id' union select price from knightweapon where item_id = '$item_id' union select price from knightetcitem where item_id = '$item_id'",$con);
					$item_price = ($rp && mysql_num_rows($rp)) ? mysql_result($rp,0,"price") : 0;
				}
				$itm_grade = "?";
				$itm_name = "$lang_unknown";
				$sql = "select name, crystal_type from knightarmour where item_id = '$item_id'";
				$result2 = mysql_query($sql,$con);
				$count4 = mysql_num_rows($result2);
				if ($count4)
				{	
					$itm_name = mysql_result($result2,0,"name");	
					$itm_grade = mysql_result($result2,0,"crystal_type");	
				}
				$sql = "select name, crystal_type from knightetcitem where item_id = '$item_id'";
				$result2 = mysql_query($sql,$con);
				$count4 = mysql_num_rows($result2);
				if ($count4)
				{	
					$itm_name = mysql_result($result2,0,"name");	
					$itm_grade = mysql_result($result2,0,"crystal_type");	
				}
				$sql = "select name, crystal_type from knightweapon where item_id = '$item_id'";
				$result2 = mysql_query($sql,$con);
				$count4 = mysql_num_rows($result2);
				if ($count4)
				{	
					$itm_name = mysql_result($result2,0,"name");	
					$itm_grade = mysql_result($result2,0,"crystal_type");	
				}
				$item_id2 = item_check(0, $item_id, $use_duplicate, $db_location, $db_user, $db_psswd, $db_l2jdb);
				echo "<tr><td class=\"dropmain\">$item_id</td><td class=\"dropmain\"><img src=\"" . $images_dir . "items/$item_id2.gif\"></td><td class=\"dropmain\">$itm_name</td><td class=\"dropmain\">";
				if ($itm_grade == "s")
				{ echo "<img src=\"" . $images_dir . "l_grade_5.gif\">"; }
				elseif  ($itm_grade == "a")
				{ echo "<img src=\"" . $images_dir . "l_grade_4.gif\">"; }
				elseif  ($itm_grade == "b")
				{ echo "<img src=\"" . $images_dir . "l_grade_3.gif\">"; }
				elseif  ($itm_grade == "c")
				{ echo "<img src=\"" . $images_dir . "l_grade_2.gif\">"; }
				elseif  ($itm_grade == "d")
				{ echo "<img src=\"" . $images_dir . "l_grade_1.gif\">"; }
				else
				{ echo "&nbsp;"; }
				echo "</td><td class=\"dropmain\">";
				echo $item_price . "</td></tr>";
				$i++;
				$count2++;
				if (($count2 >= $target) && ($i < $count))
				{
					echo "</tr></table></center></td><td class=\"noborderback\" valign=\"top\"><center><table border=\"0\" cellpadding=\"5\" cellspacing = \"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">ID</strong></p></td><td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Grade</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Price</strong></p></td></tr>";
					$count2 = 0;
				}
			}
			echo "</table></center></td></tr></table>";
		}
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
