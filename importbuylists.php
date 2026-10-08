<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/*
importbuylists.php
Новый файл - в оригинальном DropCalc импорта магазинов не было (данные читались
напрямую из merchant_buylists/merchant_shopids на сервере). Сделан по образцу
остальных import*.php файлов. См. README миграции про price=NULL.
*/

include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];

$langfile = $language_array[$langval][1];
include($langfile);

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
		require_once(__DIR__ . '/xmlparse_common.php');
		set_time_limit(0);
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

		mysql_query("CREATE TABLE IF NOT EXISTS `knightbuylist` (
			`shop_id` INT, `item_id` INT, `price` BIGINT DEFAULT NULL,
			KEY `shop_id` (`shop_id`), KEY `item_id` (`item_id`))", $con);
		mysql_query("truncate table knightbuylist", $con);

		$dir = $server_dir . 'data' . $svr_dir_delimit . 'buylists' . $svr_dir_delimit;
		$files = xip_listXmlFiles($dir);
		$count = 0;

		foreach ($files as $path)
		{
			$xml = xip_loadXml($path);
			if (!$xml) continue;
			$npcIds = [];
			foreach ($xml->npcs->npc ?? [] as $npc)
			{	$npcIds[] = (int) $npc;	}
			if (empty($npcIds))
			{	$npcIds[] = (int) basename($path, '.xml');	}
			foreach ($xml->item as $item)
			{
				$itemId = (int) $item['id'];
				$price = isset($item['price']) ? (int) $item['price'] : null;
				foreach ($npcIds as $shopId)
				{
					mysql_query("insert into knightbuylist (shop_id,item_id,price) values ($shopId, $itemId, " . ($price===null?'NULL':$price) . ")", $con);
					$count++;
				}
			}
		}
		echo "<p>Импортировано строк магазинов: $count (файлов обработано: " . count($files) . ")</p>";
	}
}

echo "</center></body></html>";

?>
