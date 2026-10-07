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

		$file_loc_a = $server_dir . 'data' . $svr_dir_delimit . 'stats' . $svr_dir_delimit . 'skills' . $svr_dir_delimit;

		$sql = "truncate table knightskills";	
		$result = mysql_query($sql,$con);
		$skill_count = 0;
		for($ia==0; $ia<1000; $ia++)
		{
			if ($ia == 0)
			{	$name = "000";	}
			elseif ($ia < 10)
			{	$name = "00" . $ia;	}
			elseif ($ia < 100)
			{	$name = "0" . $ia;	}
			else
			{	$name = "" . $ia;	}
			$file_loc = $file_loc_a . $name . "00-" . $name . "99.xml";

			if (file_exists($file_loc))
			{
				$lines = file($file_loc);
				$line_nums = count($lines);
				foreach ($lines as $line_num => $line) 
				{
					$skill_id = strchr($line,"skill id=");
					if ($skill_id)
					{	
						$single_del = strpos($line,"'");
						$double_del = strpos($line,'"');
						$delimeter = '"';
						if (($single_del > 0) && ($single_del < $double_del))
						{	$delimeter = "'";	}
						if (($single_del > 0) && ($double_del == 0))
						{	$delimeter = "'";	}
						$id = substr($skill_id,10);
						$pos = strpos($id,$delimeter);
						$id = substr($id,0,$pos);;
						$name = strchr($skill_id,"name=");
						$name = substr($name,6);
						$pos = strpos($name,$delimeter);
						$name = substr($name,0,$pos);
						$skill_count++;
						$name = preg_replace('/\'/','`',$name);
						$sql = "insert into knightskills (`skill_id`, `name`) values ('$id', '$name')";
						$result = mysql_query($sql,$con);
					}
				}
			}  
		}
		echo "<p>$skill_count skills imported.</p>";
	}
}

echo "</center></body></html>";

?>
