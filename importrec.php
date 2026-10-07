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
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		echo "<p class=\"popup\">You don't have sufficient access.</p>";
		echo "</center></body></html>";
		return 0;
	}
	else
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

		$file_loc = $server_dir . 'data' . $svr_dir_delimit . 'recipes.xml';

		$lines = file($file_loc);
		$line_nums = count($lines);
		$recipe_count = 0;
		$recipe_items = 0;
		$ex_recipe_count = 0;
		$ex_recipe_items = 0;

		$sql = "truncate table knightrecch";	
		$result = mysql_query($sql,$con);
		$sql = "truncate table knightrecipe";	
		$result = mysql_query($sql,$con);
		$rec_run = 0;
		foreach ($lines as $line_num => $line) 
		{
		
			if (strpos($line,"<item") > 0)
			{
				$rec_run = 0;
				if (strpos($line,"item id=") > 0)
				{
					$parts = preg_split('/"/', $line, -1, PREG_SPLIT_NO_EMPTY);
					$r_xmlid = $parts[1];
					$rec_run++;
				}
				if (strpos($line,"recipeId=") > 0)
				{
					$parts = preg_split('/"/', $line, -1, PREG_SPLIT_NO_EMPTY);
					$r_recid = $parts[3];
					$rec_run++;
				}				
				if (strpos($line," successRate=") > 0)
				{
					$parts = preg_split('/"/', $line, -1, PREG_SPLIT_NO_EMPTY);
					$r_chance = $parts[11];
					$rec_run++;
				}
				if (strpos($line," name=") > 0)
				{
					$parts = preg_split('/"/', $line, -1, PREG_SPLIT_NO_EMPTY);
					$r_name = $parts[5];
					$r_name = preg_replace('/\'/','',$r_name);
					$r_name = preg_replace('/mk_/','', $r_name);
					$r_name = preg_replace('/_/',' ', $r_name);
					$rec_run++;
				}
			}
			if ($rec_run >= 3)
			{
				if (strpos($line,"<production") > 0)
				{
					$parts = preg_split('/"/', $line, -1, PREG_SPLIT_NO_EMPTY);
					$r_item = $parts[1];
					$r_created = $parts[3];
					$rec_run++;
					$sql2 = "select name from knightarmour where item_id = $r_item";
					$result2 = mysql_query($sql2,$con);
					$count = mysql_num_rows($result2);
					if ($count)
					{	$r_name = mysql_result($result2,0,"name");	}
					$sql2 = "select name from knightetcitem where item_id = $r_item";
					$result2 = mysql_query($sql2,$con);
					$count = mysql_num_rows($result2);
					if ($count)
					{	$r_name = mysql_result($result2,0,"name");	}
					$sql2 = "select name from knightweapon where item_id = $r_item";
					$result2 = mysql_query($sql2,$con);
					$count = mysql_num_rows($result2);
					if ($count)
					{	$r_name = mysql_result($result2,0,"name");	}
					$grade = "";
					$sql = "select crystal_type from knightarmour where item_id = '$r_item'";
					$result = mysql_query($sql,$con);
					$count = mysql_num_rows($result);
					if ($count)
					{	$grade = mysql_result($result,0,"crystal_type");	}
					$sql = "select crystal_type from knightetcitem where item_id = '$r_item'";
					$result = mysql_query($sql,$con);
					$count = mysql_num_rows($result);
					if ($count)
					{	$grade = mysql_result($result,0,"crystal_type");	}
					$sql = "select crystal_type from knightweapon where item_id = '$r_item'";
					$result = mysql_query($sql,$con);
					$count = mysql_num_rows($result);
					if ($count)
					{	$grade = mysql_result($result,0,"crystal_type");	}
					if ($grade == "none")
					{	$r_level = 1;	}
					if ($grade == "d")
					{	$r_level = 2;	}
					if ($grade == "c")
					{	$r_level = 3;	}
					if ($grade == "b")
					{	$r_level = 4;	}
					if ($grade == "a")
					{	$r_level = 5;	}
					if ($grade == "s")
					{	$r_level = 6;	}
					if ($parts[0] == "common")
					{	$r_level = 0;	}
				}
				if ($rec_run > 3)
				{
					if (strpos($line,"<ingredient") > 0)
					{
						$parts = preg_split('/"/', $line, -1, PREG_SPLIT_NO_EMPTY);
						$rec_itm_num = $parts[1];
						$rec_itm_qty = $parts[3];
						$sql = "insert into knightrecipe (`rec_id`, `makes`, `item`, `qty`) values ('$r_recid', \"$r_item\", '$rec_itm_num', '$rec_itm_qty')";	
						$ex_recipe_items++;
						$result = mysql_query($sql,$con);
						$recipe_items++;
					}
				}
				if ($rec_run == 5)
				{
					$sql = "insert into knightrecch (`rec_name`, `rec_id`, `rec_item`, `level`, `makes`, `chance`, `multiplier`, `xml_id`) values (\"$r_name\", '$r_recid', '$r_recitem', '$r_level', '$r_item', '$r_chance', '$r_created', '$r_xmlid')";	
					$result = mysql_query($sql,$con);
					$ex_recipe_count++;
					$rec_run++;
					if ($result <> 1)
					{	
						echo "Failed - $sql<br>";	
						$ex_recipe_count--;
					}
				}
			}

			if (strpos($line,"</item") > 0)
			{
				$rec_run = 0;
				$parts = preg_split('/"/', $line, -1, PREG_SPLIT_NO_EMPTY);
				$r_recid = $parts[1];
				$r_recitem = 0;
				$r_item = 0;
				$r_created = 0;
				$r_chance = 0;

				$recipe_count++;
			}
			
		}
		echo "<p class=\"popup\">$recipe_count recipes, made of $recipe_items items found.</p>";
		echo "<p class=\"popup\">$ex_recipe_count recipes, made of $ex_recipe_items items imported.</p>";
	}
}

echo "</center></body></html>";

?>
