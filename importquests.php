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
$next = input_check($_REQUEST['next'],0);
$next++;

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

echo "<html class=\"popup\">
<head>
<title>Michelle's Generic Drop Calc</title>";

$evaluser = evalUser($username, $token, $ipaddr, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $sec_inc_admin, $sec_inc_gmlevel, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
			</head>
			<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
			<center><p class=\"popup\">You don't have sufficient access.</p>";
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
		$result = mysql_query("CREATE TABLE IF NOT EXISTS `knightquestrun` (`quest_id` int(4) NOT NULL default '0',`type` int(1) NOT NULL default '0',`target_id` int(11) NOT NULL default '0', KEY `qiest_id` (`quest_id`), UNIQUE quest_id (quest_id,target_id))",$con);
		$result = mysql_query("CREATE TABLE IF NOT EXISTS `knightquests` (`quest_id` int(4) NOT NULL default '0', `name` varchar(50) default NULL, KEY `quest_id` (`quest_id`))",$con);
		$file_loc = $server_dir . 'data' . $svr_dir_delimit;
		if ($next == 1)
		{
			$sql = "truncate table knightquests";	
			$result = mysql_query($sql,$con);
			$sql = "truncate table knightquestrun";	
			$result = mysql_query($sql,$con);
		}
		$in_file = $file_loc . 'scripts.cfg';
		$lines = file($in_file);
		$line_nums = count($lines);
		$found = 0;
		$run_with = "";
		foreach ($lines as $line_num => $line) 
		{
			if(substr($line,0,7) == "quests/")
			{
				$found++;
				if ($found == $next)
				{	
					$run_with = substr($line,7);
					$run_with = substr($run_with,0,strpos($run_with,"/"));
				}
			}
		}
		if (strlen($run_with) > 0)
		{
			echo "<META content=\"2;url=importquests.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=$next\" http-equiv=refresh >\n";
			echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
				</head>
				<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
				<center>";
			echo "<p>Importing quest $run_with</p>";
			$quest_name = substr($run_with,strpos($run_with,'_')+1);
			$quest_number = substr($run_with,0,strpos($run_with,'_'));
			$result = mysql_query("insert into knightquests (quest_id, name) values ('$quest_number', '$quest_name')",$con);
			$file_name = $file_loc . 'scripts' . $svr_dir_delimit . 'quests' . $svr_dir_delimit . $run_with . $svr_dir_delimit . '__init__.py';
			if (file_exists($file_name))
			{
 				$lines = file($file_name);
				$line_nums = count($lines);
				$stage = 0;
				$itm_array = ARRAY();
				foreach ($lines as $line_num => $line) 
				{
					$line = trim($line);
					if (substr($line,0,6) == "class ")
					{	$stage = 2;	}
					if (($stage == 1) && (strpos($line,'=') > 0))
					{
						$left = trim(substr($line,0,strpos($line,'=')-1));
						$right = trim(substr($line,strpos($line,'=')+2));
						$itm_array["$left"] = $right;
					}
					if (substr($line,0,5) == "qn = ")
					{	$stage = 1;	}
					if (substr($line,0,12) == 'st.giveItems')
					{
						$middle = substr($line,strpos($line,'(')+1);
						$middle = substr($middle,0,strpos($middle,','));
						$value = $itm_array["$middle"]; 
						if (intval($value) > 0)
						{	$result = mysql_query("insert ignore into knightquestrun (quest_id, type, target_id) values ('$quest_number', 1, '$value')",$con);	}
					}
					if (strpos($line,"npcId == ") > 0)
					{
						$middle = trim(substr($line,strpos($line,'npcId ==')+8));
						$middle = substr($middle,0,strpos($middle,' '));
						if (intval($middle) > 0)
						{	$value = $middle;	}
						else
						{	$value = $itm_array["$middle"]; }
						if (intval($value) > 0)
						{	$result = mysql_query("insert ignore into knightquestrun (quest_id, type, target_id) values ('$quest_number', 2, '$value')",$con);	}
					}
				}
			}
			else
			{	echo "<p>No ini.py found for this quest.</p>";	}
		}
		else
		{	echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\">
				</head>
				<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" class=\"popup\">
				<center><p>Importing ended. $next quests imported.</p>";
		}
	}
}

echo "</center></body></html>";

?>
