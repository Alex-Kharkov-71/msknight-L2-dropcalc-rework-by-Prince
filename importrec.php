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

		require_once(__DIR__ . '/xmlparse_common.php');

		mysql_query("CREATE TABLE IF NOT EXISTS `knightrecipe` (
			`rec_id` INT, `makes` INT DEFAULT 1, `item` INT, `qty` INT, KEY `rec_id` (`rec_id`))", $con);
		mysql_query("CREATE TABLE IF NOT EXISTS `knightrecch` (
			`rec_name` VARCHAR(100), `rec_id` INT PRIMARY KEY, `rec_item` INT, `level` INT,
			`makes` INT DEFAULT 1, `chance` FLOAT, `multiplier` INT DEFAULT 1, `xml_id` INT)", $con);
		mysql_query("truncate table knightrecipe", $con);
		mysql_query("truncate table knightrecch", $con);

		// Регистр имени важен на Linux: в L2JMobius файл называется Recipes.xml,
		// не recipes.xml, как было в старом датапаке.
		$file_loc = $server_dir . 'data' . $svr_dir_delimit . 'stats' . $svr_dir_delimit . 'Recipes.xml';
		$rec_count = 0;
		$ing_count = 0;
		$dup_count = 0;
		$seen_rec = array();

		$xml = xip_loadXml($file_loc);
		if ($xml)
		{
			foreach ($xml->item as $item)
			{
				$recId = (int) $item['id'];
				$realRecipeId = (int) ($item['recipeId'] ?? $recId);
				// V datapake odin recipeId inogda ob'yavlen dvazhdy (naprimer 5008 -
				// id=138 i id=436). Beryom pervoe opredelenie, vtoroe propuskaem
				// tselikom, inache ingredienty zadvoilis by.
				if (isset($seen_rec[$realRecipeId]))
				{	$dup_count++;	continue;	}
				$seen_rec[$realRecipeId] = 1;
				$rname = (string) $item['name'];
				$level = (int) ($item['craftLevel'] ?? 0);
				$successRate = (float) ($item['successRate'] ?? 100);

				$prodId = 0; $prodCount = 1;
				if (isset($item->production))
				{
					$prodId = (int) $item->production['id'];
					$prodCount = (int) ($item->production['count'] ?? 1);
				}

				mysql_query("insert into knightrecch (rec_name,rec_id,rec_item,level,makes,chance,multiplier,xml_id) values (
					'" . mysql_real_escape_string($rname,$con) . "', $realRecipeId, $prodId, $level, $prodCount, $successRate, 1, $recId)", $con);
				$rec_count++;

				foreach ($item->ingredient ?? [] as $ing)
				{
					$iid = (int) $ing['id'];
					$icount = (int) ($ing['count'] ?? 1);
					mysql_query("insert into knightrecipe (rec_id,makes,item,qty) values ($realRecipeId, $prodCount, $iid, $icount)", $con);
					$ing_count++;
				}
			}
		}
		else
		{
			echo "<p class=\"popup\">Файл не найден или не парсится: $file_loc</p>";
		}
		echo "<p>$rec_count recipes imported ($ing_count ingredients). Duplicate recipeId skipped: $dup_count.</p>";
	}
}

echo "</center></body></html>";

?>
