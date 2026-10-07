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
$action = input_check($_REQUEST['action'],0);
$shopid = input_check($_REQUEST['shopid'],0);
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

		if ($action == "delete")
		{
			$sql = "delete from merchant_buylists where shop_id = '$shopid' and item_id = '$itemid'";
			$result = mysql_query($sql,$con);
			$sql = "select `order` from merchant_buylists where shop_id = '$shopid' order by `order`";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result); 	// We start the shop add by tidying up the actual shop order numbers.
			$i=0;
			while ($i < $count)
			{
				$i_order = mysql_result($result,$i,"order");
				$i++;
				if ($i_order != $i)
				{
					$sql = "update merchant_buylists set `order` = '$i' where shop_id = '$shopid' and `order` = '$i_order'";
					$result2 = mysql_query($sql,$con);
				}
			}
		}

		if ($action == "change")
		{
			$sql = "update merchant_buylists set price = '$price' where shop_id = '$shopid' and item_id = '$itemid'";
			$result = mysql_query($sql,$con);
		}

		if ($action == "up")
		{
			$up_num = $itemid - 1;
			$sql = "update merchant_buylists set `order` = '9999' where shop_id = '$shopid' and `order` = '$up_num'";
			$result = mysql_query($sql,$con);
			$sql = "update merchant_buylists set `order` = '$up_num' where shop_id = '$shopid' and `order` = '$itemid'";
			$result = mysql_query($sql,$con);
			$sql = "update merchant_buylists set `order` = '$itemid' where shop_id = '$shopid' and `order` = '9999'";
			$result = mysql_query($sql,$con);
		}

		if ($action == "down")
		{
			$up_num = $itemid + 1;
			$sql = "update merchant_buylists set `order` = '9999' where shop_id = '$shopid' and `order` = '$up_num'";
			$result = mysql_query($sql,$con);
			$sql = "update merchant_buylists set `order` = '$up_num' where shop_id = '$shopid' and `order` = '$itemid'";
			$result = mysql_query($sql,$con);
			$sql = "update merchant_buylists set `order` = '$itemid' where shop_id = '$shopid' and `order` = '9999'";
			$result = mysql_query($sql,$con);
		}

		if ($action == "add")
		{
			$sql = "select `order` from merchant_buylists where shop_id = '$shopid' order by `order`";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result); 	// We start the shop add by tidying up the actual shop order numbers.
			$i=0;
			while ($i < $count)
			{
				$i_order = mysql_result($result,$i,"order");
				$i++;
				if ($i_order != $i)
				{
					$sql = "update merchant_buylists set `order` = '$i' where shop_id = '$shopid' and `order` = '$i_order'";
					$result2 = mysql_query($sql,$con);
				}
			}
			$i++;
			$next_item = $i;
			$sql = "select `order` from merchant_buylists where shop_id = '$shopid' and item_id = '$itemid'";
			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			if ($count)
			{	echo "<h2 class=\"dropmain\">Item $itemid already in shop.</h2>";	}
			else
			{
				$found_item = 0;
				$sql = "select name, crystal_type from armor where item_id = '$itemid'";
				$result2 = mysql_query($sql,$con);
				$count4 = mysql_num_rows($result2);
				if ($count4)
				{	$found_item = 1;	}
				$sql = "select name, crystal_type from etcitem where item_id = '$itemid'";
				$result2 = mysql_query($sql,$con);
				$count4 = mysql_num_rows($result2);
				if ($count4)
				{	$found_item = 1;	}
				$sql = "select name, crystal_type from weapon where item_id = '$itemid'";
				$result2 = mysql_query($sql,$con);
				$count4 = mysql_num_rows($result2);
				if ($count4)
				{	$found_item = 1;	}
				if (!$found_item)
				{	echo "<h2 class=\"dropmain\">Item $itemid doesn't exist in game.</h2>";	}
				else
				{
					$price = intval($price);
					if ($price < 0)
					{	$price = 0;	}
					$sql = "insert into merchant_buylists (item_id, price, shop_id, `order`) values ('$itemid', '$price', '$shopid', '$next_item')";
					$result2 = mysql_query($sql,$con);
				}
			}
		}

		$sql = "select shop_id, npc_id from merchant_shopids order by shop_id";
		$result = mysql_query($sql,$con);
		$count = mysql_num_rows($result);

		if (!$shopid)
		{
			echo "<table class=\"blanktab\" width=\"100%\"><tr><td class=\"noborderback\" valign=\"top\"><center><table border=\"0\" cellpadding=\"5\" cellspacing = \"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">ID</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Location</strong></p></td></tr>";
			$i=0;
			$target = intval($count / 4) + 1;
			$count3 = 0;
			while ($i < $count)
			{
				$shop_id = mysql_result($result,$i,"shop_id");
				$shop_owner = mysql_result($result,$i,"npc_id");
				$shop_loc = intval($shop_owner);
				$sql = "select name from npc where id = '$shop_owner' union select name from custom_npc where id = '$shop_owner'";
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
			$sql = "select shop_id, npc_id from merchant_shopids where shop_id = '$shopid'";
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
			}
			$sql = "select name from npc where id = '$shop_owner' union select name from custom_npc where id = '$shop_owner'";
			$result2 = mysql_query($sql,$con);
			$count2 = mysql_num_rows($result2);
			if ($count2)
			{
				$shop_owner = mysql_result($result2,0,"name");
			}
			echo "<h2 class=\"dropmain\">ID $shop_id - $shop_owner - $shop_location</h2>";

			echo "<center><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\" colspan=\"5\"><p class=\"dropmain\"><strong class=\"dropmain\">Add Item - </strong></p></td></tr><tr>";
			echo "<form method=\"post\" action=\"shops.php\">
			<input name=\"username\" type=\"hidden\" value=\"$username\">
			<input name=\"token\" type=\"hidden\" value=\"$token\">
			<input name=\"shopid\" type=\"hidden\" value=\"$shop_id\">
			<input name=\"action\" type=\"hidden\" value=\"add\">
			<td class=\"blanktab\"><p class=\"dropmain\">&nbsp;&nbsp;&nbsp;Item&nbsp;-&nbsp;</p></td><td class=\"blanktab\">
			<input name=\"itemid\" type=\"text\" value=\"0\" maxlength=\"6\" size=\"6\"></td><td class=\"blanktab\"><p class=\"dropmain\">&nbsp;&nbsp;&nbsp;Price&nbsp;-&nbsp;</p></td><td class=\"blanktab\">
			<input name=\"price\" type=\"text\" value=\"0\" maxlength=\"10\" size=\"10\"></td><td class=\"blanktab\"><input value=\"<- Add\" type=\"submit\" class=\"bigbut2\"></td></form>";
			echo "</tr></table></center>";
			echo "<p>&nbsp</p>";

			$sql = "select item_id, price, `order` from merchant_buylists where shop_id = '$shop_id' order by `order`";

			$result = mysql_query($sql,$con);
			$count = mysql_num_rows($result);
			echo "<table class=\"blanktab\" width=\"100%\"><tr><td class=\"noborderback\" valign=\"top\"><center><table border=\"0\" cellpadding=\"5\" cellspacing = \"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">ID</strong></p></td><td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Grade</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Price</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Delete?</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Move</strong></p></td></tr>";
			$i = 0;
			$target = intval($count / 2);
			if (($target * 2) < $count)
			{	$target++;	}
			$count2 = 0;
			while ($i < $count)
			{
				$item_id = mysql_result($result,$i,"item_id");
				$item_price = mysql_result($result,$i,"price");
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
				echo "<form><form action=\"shops.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<input name=\"action\" type=\"hidden\" value=\"change\">
					<input name=\"shopid\" type=\"hidden\" value=\"$shop_id\">
					<input name=\"itemid\" type=\"hidden\" value=\"$item_id\">
					<table class=\"blanktab\" border=\"0\" cellpadding=\"0\" cellspacing = \"0\"><tr><td class=\"noborderback\" valign=\"top\">
					<input name=\"price\" type=\"text\" value=\"$item_price\" maxlength=\"10\" size=\"10\">
					</td><td class=\"noborderback\" valign=\"top\">
					<input value=\"<-\" onclick=\"submit\" height=\"9\" type=\"submit\"></form></td>
					</td></td></table>";
				echo "<td class=\"dropmain\"><form action=\"shops.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<input name=\"action\" type=\"hidden\" value=\"delete\">
					<input name=\"shopid\" type=\"hidden\" value=\"$shop_id\">
					<input name=\"itemid\" type=\"hidden\" value=\"$item_id\">
					<input value=\"D\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"></form></td><td class=\"dropmain\"><table class=\"blanktab\" border=\"0\" cellpadding=\"0\" cellspacing = \"0\"><tr><td class=\"noborderback\">";
				$order_num = $i+1;
				if ($i > 0)
				{	echo "<a href=\"shops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=up&shopid=$shop_id&itemid=$order_num\" border=\"0\"><img src=\"" . $images_dir . "sortbutup.gif\"></a>";	}
				if ($i < ($count-1))
				{	echo "<a href=\"shops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=down&shopid=$shop_id&itemid=$order_num\" border=\"0\"><img src=\"" . $images_dir . "sortbut.gif\"></a>";	}
				echo "</td></tr></table></td></tr>";
				$i++;
				$count2++;
				if (($count2 >= $target) && ($i < $count))
				{
					echo "</tr></table></center></td><td class=\"noborderback\" valign=\"top\"><center><table border=\"0\" cellpadding=\"5\" cellspacing = \"0\" class=\"dropmain\"><tr><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">ID</strong></p></td><td class=\"drophead\">&nbsp;</td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">$lang_name</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Grade</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Price</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Delete?</strong></p></td><td class=\"drophead\"><p class=\"dropmain\"><strong class=\"dropmain\">Move</strong></p></td></tr>";
					$count2 = 0;
				}
			}
			echo "</table></center></td></tr></table>";
		}
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
