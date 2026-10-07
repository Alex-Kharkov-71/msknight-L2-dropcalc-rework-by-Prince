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
include('map.php');
include('playermap.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$action = input_check($_REQUEST['action'],0);
$password = input_check($_REQUEST['password'],0);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_gmlevel)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{

	// Connect to DB
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
	$sql = "create database if not exists $knight_db";
	if (!mysql_query($sql,$con))
		{
		die('Could not create $knight_db database: ' . mysql_error());
		}

// -- Action for Reference
		if ($action == "reference")
		{
			$max_runtime = ini_get('max_execution_time');
			echo "<h2 class=\"dropmain\">Server PHP timeout set to $max_runtime seconds</h2>";
			echo "<center><p class=\"dropmainwhite\">Some scripts can take more than ten minutes to run fully.</p></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			if ($password == $conf_reference)
			{
				echo "<td class=\"drophead\" colspan=\"2\"><p class=\"center\"><strong class=\"dropmain\">Reference Results</strong></p></td>";
				$sql = "USE $knight_db";
				if (!mysql_query($sql,$con))
					{
					die('Could not change to L2J database: ' . mysql_error());
				}
				$i=0;
				$count = count($tables_merge);
				while ($i < $count)
				{
					set_time_limit(0);
					$table = $tables_merge[$i];
					$sql = "drop table if exists ref_$table";
					if (!mysql_query($sql,$con))
						{
						die('Could not drop table ref_$table from $knight_db: ' . mysql_error());
						}
					if (($table <> "accounts") && ($table <> "gameservers") && ($table <> "knightdrop"))
					{	$sql = "create table $knight_db.ref_$table select * from $db_l2jdb.$table";	}
					else
					{	$sql = "create table $knight_db.ref_$table select * from $dblog_l2jdb.$table";	}
					$result = mysql_query($sql,$con);
					$error =  mysql_error();
					if ($result)
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$green_code>O.K.</font></p></td></tr>";	}
					else
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code>Error</font></p></td></tr>";	}
					$i++;
				}
			}
			else
			{	echo "<td class=\"dropmain\"><h2 class=\"dropmainblack\">Reference password doesn't match.<br>No action taken.</h2></td>";	}
			echo "</tr></table></center>";
		}

// -- Action for checks
		if ($action == "checks")
		{
			$max_runtime = ini_get('max_execution_time');
			echo "<h2 class=\"dropmain\">Server PHP timeout set to $max_runtime seconds</h2>";
			echo "<center><p class=\"dropmainwhite\">Some scripts can take more than ten minutes to run fully.</p></center>";
			$sql = "USE $knight_db";
			if (!mysql_query($sql,$con))
				{
				die('Could not change to L2J database: ' . mysql_error());
			}
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td><h2 class=\"dropmainblack\">Start of report ...</h2>";
			$i=0;
			$count = count($tables_merge);
			while ($i < $count)
			{
				$table = $tables_merge[$i];


				// Retrieve list of fields from table.
				$field_count = 0;
				$sql = "show fields from ref_$table";
				$result = mysql_query($sql,$con);
				if ($result)
				{	$field_count = mysql_num_rows($result);	}
				$field_list = "";
				$i2=0;
				while ($i2 < $field_count)
				{
					$field_name = mysql_result($result,$i2,"field");
					if ($i2 == 0)
					{	$field_list = array($field_name);	}
					else
					{	array_push($field_list, $field_name);	}
					
					$i2++;
				}

				// Retrieve list of fields from table.
				$field_count2 = 0;
				if (($table <> "accounts") && ($table <> "gameservers") && ($table <> "knightdrop"))
				{	$sql = "show fields from $db_l2jdb.$table";	}
				else
				{	$sql = "show fields from $dblog_l2jdb.$table";	}
				$result = mysql_query($sql,$con2);
				if ($result)
				{	$field_count2 = mysql_num_rows($result);	}
				$field_list2 = "";
				$i2=0;
				while ($i2 < $field_count2)
				{
					$field_name = mysql_result($result,$i2,"field");
					if ($i2 == 0)
					{	$field_list2 = array($field_name);	}
					else
					{	array_push($field_list2, $field_name);	}
					
					$i2++;
				}


				$i2 = 0;
				while ($i2 < $field_count)
				{
					$check_in = 0;
					$i3 = 0;
					while ($i3 < $field_count2)
					{				
						if ($field_list[$i2] == $field_list2[$i3])
						{	$check_in = 1;	}
						$i3++;
					}
					if (!$check_in)
					echo "<p class=\"main\">Column $field_list[$i2] has been deleted from table $table !!!</p>";
					$i2++;
				}
				$i2 = 0;
				while ($i2 < $field_count2)
				{
					$check_in = 0;
					$i3 = 0;
					while ($i3 < $field_count)
					{				
						if ($field_list2[$i2] == $field_list[$i3])
						{	$check_in = 1;	}
						$i3++;
					}
					if (!$check_in)
					echo "<p class=\"main\">Column $field_list[$i2] has been inserted in table $table - Check for index field problems.</p>";
					$i2++;
				}

				$i++;
			}

			$i=0;
			$count = count($tables_backup);
			while ($i < $count)
			{
				$table = $tables_backup[$i];


				// Retrieve list of fields from table.
				$field_count = 0;
				$sql = "show fields from bk_$table";
				$result = mysql_query($sql,$con);
				if ($result)
				{	$field_count = mysql_num_rows($result);	}
				$field_list = "";
				$i2=0;
				while ($i2 < $field_count)
				{
					$field_name = mysql_result($result,$i2,"field");
					if ($i2 == 0)
					{	$field_list = array($field_name);	}
					else
					{	array_push($field_list, $field_name);	}
					
					$i2++;
				}

				// Retrieve list of fields from table.
				$field_count2 = 0;
				if (($table <> "accounts") && ($table <> "gameservers") && ($table <> "knightdrop"))
				{	$sql = "show fields from $db_l2jdb.$table";	}
				else
				{	$sql = "show fields from $dblog_l2jdb.$table";	}
				$result = mysql_query($sql,$con2);
				if ($result)
				{	$field_count2 = mysql_num_rows($result);	}
				$field_list2 = "";
				$i2=0;
				while ($i2 < $field_count2)
				{
					$field_name = mysql_result($result,$i2,"field");
					if ($i2 == 0)
					{	$field_list2 = array($field_name);	}
					else
					{	array_push($field_list2, $field_name);	}
					
					$i2++;
				}


				$i2 = 0;
				while ($i2 < $field_count)
				{
					$check_in = 0;
					$i3 = 0;
					while ($i3 < $field_count2)
					{				
						if ($field_list[$i2] == $field_list2[$i3])
						{	$check_in = 1;	}
						$i3++;
					}
					if (!$check_in)
					echo "<p class=\"main\">Column $field_list[$i2] has been deleted from table $table !!!</p>";
					$i2++;
				}
				$i2 = 0;
				while ($i2 < $field_count2)
				{
					$check_in = 0;
					$i3 = 0;
					while ($i3 < $field_count)
					{				
						if ($field_list2[$i2] == $field_list[$i3])
						{	$check_in = 1;	}
						$i3++;
					}
					if (!$check_in)
					echo "<p class=\"main\">Column $field_list[$i2] has been inserted in table $table - Check for index field problems.</p>";
					$i2++;
				}

				$i++;
			}
			echo "<h2 class=\"dropmainblack\">... end of report.</h2></td></tr></table></center>";
		}


// -- Action for Replay
		if ($action == "replay")
		{
			$max_runtime = ini_get('max_execution_time');
			echo "<h2 class=\"dropmain\">Server PHP timeout set to $max_runtime seconds</h2>";
			echo "<center><p class=\"dropmainwhite\">Some scripts can take more than ten minutes to run fully.</p></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			if ($password == $conf_replay)
			{
				$now_t = date("g:i a");
				echo "<td class=\"drophead\" colspan=\"4\"><p class=\"center\"><strong class=\"dropmain\">Replay Results started - $now_t</strong></p></td>";
				$sql = "USE $knight_db";
				if (!mysql_query($sql,$con))
					{
					die('Could not change to L2J database: ' . mysql_error());
				}
				$sql = "drop table if exists errors";
				if (!mysql_query($sql,$con))
					{
					die('Could not remove old error database: ' . mysql_error());
				}
				$sql = "create table errors( error_text text, reason text) ";
				if (!mysql_query($sql,$con))
					{
					die('Could not create errors database: ' . mysql_error());
				}
				$i=0;
				$count = count($tables_merge);
				while ($i < $count)
				{
					$table = $tables_merge[$i];
					set_time_limit(0);

					// Retrieve list of fields from table.
					$field_count = 0;
					$sql = "show fields from del_$table";
					$result = mysql_query($sql,$con);
					if ($result)
					{	$field_count = mysql_num_rows($result);	}
					$field_list = "";
					$ttl_count = $field_count;
					$first_field = "";
					$i2=0;
					while ($i2 < $ttl_count)
					{
						$field_name = mysql_result($result,$i2,"field");
						if ($i2 == 0)
						{	
							$field_list = array($field_name);	
							$first_field = $field_name;
						}
						else
						{	array_push($field_list, $field_name);	}
						
						$i2++;
					}


					$rec_count = 0;
					$sql = "SELECT $first_field FROM del_$table";
					$result = mysql_query($sql,$con);
					if ($result)
					{	$rec_count = mysql_num_rows($result);	}
					$result_mess = "<font color=$green_code>Delete O.K.</font>";
					$sql="handler del_$table open";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	$result_mess = "<font color=$red_code>Error on open</font>";	}
					$sql="handler del_$table read first";
					$result = mysql_query($sql,$con);
					if ((!$result) && ($rec_count > 0))
					{	$result_mess = "<font color=$red_code>Error on first read</font>";	}

					$i2=0;

					while ($i2 < $rec_count)
					{
						set_time_limit(0);
						$sqls = "delete from $db_l2jdb.$table where ";
						$i3=0;
						$and_on = 0;
						while ($i3 < $field_count)
						{	
							$skip_and = false;
							$field_name = $field_list[$i3];
							$field_data = mysql_result($result,0,$field_name);
							if ($field_data == NULL)
							{	$skip_and = true;	}
							else
							{	
								if ($and_on)
								{	$sqls = $sqls . "AND ";	}
								if  (($field_name == "int") || ($field_name == "exp") || ($field_name == "order"))
								{	$sqls = $sqls . "`$field_name` = \"$field_data\" ";	}
								else
								{	$sqls = $sqls . "$field_name = \"$field_data\" ";	}
							$and_on = true;
							}
							$i3++;

						}
						$sqls = $sqls . " limit 1";

						$result = mysql_query($sqls,$con);  // Try and delete the record from the add databa	
		
						$i2++;
						if ($i2 < $rec_count)
						{
							$sql="handler del_$table read next";
							$result = mysql_query($sql,$con);
							if (!$result)
							{	$result_mess = "<font color=$red_code>Error subsequent read</font>";	}
						}
					}
					echo "<tr><td><p class=\"main\">$table</p></td><td>$result_mess</td>";
					$rec_count = 0;
					$sql = "SELECT $first_field FROM add_$table";
					$result = mysql_query($sql,$con);
					if ($result)
					{	$rec_count = mysql_num_rows($result);	}
					$result_mess = "<font color=$green_code>Add O.K.</font>";
					$sql="handler add_$table open";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	$result_mess = "<font color=$red_code>Error on open</font>";	}
					$sql="handler add_$table read first";
					$result = mysql_query($sql,$con);
					if ((!$result) && ($rec_count > 0))
					{	$result_mess = "<font color=$red_code>Error on first read</font>";	}
					$i2=0;
					$num_ok = 0;
					$num_bad = 0;
					while ($i2 < $rec_count)
					{
						set_time_limit(0);
						$sqls = "insert into $db_l2jdb.$table (";
						$sqlb = ") values(";
						$i3=0;
						$and_on = 0;
						while ($i3 < $field_count)
						{	
							$skip_and = false;
							$field_name = $field_list[$i3];
							$field_data = mysql_result($result,0,$field_name);
							if ($field_data == NULL)
							{	$skip_and = true;	}
							else
							{	
								if ($and_on)
								{	
									$sqls = $sqls . ", ";	
									$sqlb = $sqlb . ", ";	
								}
								if  (($field_name == "int") || ($field_name == "exp") || ($field_name == "order"))
								{	$sqls = $sqls . "`$field_name`";	}
								else
								{	$sqls = $sqls . "$field_name";	}
								$sqlb = $sqlb . "\"" . $field_data . "\"";
							$and_on = true;
							}
							$i3++;

						}
						$sqls = $sqls . $sqlb . ")";

						$result = mysql_query($sqls,$con);  // Try and delete the record from the add databa	
						if ($result)
						{	$num_ok++;	}
						else
						{	
							$num_bad++;	
							$error_report = mysql_error();
							$error_call = preg_replace('/"/','\\\"',$sqls);
							$sql = "insert into errors (error_text, reason) values(\"" . $error_call . "\", \"". $error_report . "\")";
							$result = mysql_query($sql,$con);
						}
						$i2++;
						if ($i2 < $rec_count)
						{
							$sql="handler add_$table read next";
							$result = mysql_query($sql,$con);
							if (!$result)
							{	$result_mess = "<font color=$red_code>Error subsequent read</font>";	}
						}
					}

					$sql="handler add_$table close";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	$result_mess = "<font color=$red_code>Error on close</font>";	}
					echo "<td><p class=\"main\"><font color=$green_code>$num_ok</font>/<font color=$red_code>$num_bad</font>/$rec_count</p></td></tr>";

					$i++;
				}
			}
			else
			{	echo "<td class=\"dropmain\"><h2 class=\"dropmainblack\">Replay password doesn't match.<br>No action taken.</h2></td>";	}
			echo "</tr></table></center>";
		}


// -- Action for Makedif
		if ($action == "makedif")
		{
			$max_runtime = ini_get('max_execution_time');
			echo "<h2 class=\"dropmain\">Server PHP timeout set to $max_runtime seconds</h2>";
			echo "<center><p class=\"dropmainwhite\">Some scripts can take more than ten minutes to run fully.</p></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			if ($password == $conf_diff)
			{
				$now_t = date("g:i a");
				echo "<td class=\"drophead\" colspan=\"4\"><p class=\"center\"><strong class=\"dropmain\">Difference Results started - $now_t</strong></p></td>";
				$sql = "USE $knight_db";
				if (!mysql_query($sql,$con))
					{
					die('Could not change to L2J database: ' . mysql_error());
				}

				$i=0;
				$count = count($tables_merge);
				while ($i < $count)
				{
					set_time_limit(0);
					$table = $tables_merge[$i];
					$sql = "drop table if exists add_$table";
					if (!mysql_query($sql,$con))
						{
						die('Could not drop table add_$table from $knight_db: ' . mysql_error());
						}
					$sql = "create table add_$table select * from $db_l2jdb.$table";
					$result = mysql_query($sql,$con);
					if ($result)
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$green_code>Copy O.K.</font></p></td>";	}
					else
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code>Copy Error</font></p></td>";	}
					$sql = "ALTER TABLE  add_$table ADD INDEX(overtime_rate);";

					// Retrieve list of fields from table.
					$field_count = 0;
					$sql = "show fields from ref_$table";
					$result = mysql_query($sql,$con);
					if ($result)
					{	$field_count = mysql_num_rows($result);	}
					$field_list = "";
					$first_field = "";
					$ttl_count = $field_count;
					$i2=0;
					while ($i2 < $ttl_count)
					{
						$field_name = mysql_result($result,$i2,"field");
						if ($i2 == 0)
						{	
							$field_list = array($field_name);	
							$first_field = $field_name;
						}
						else
						{	array_push($field_list, $field_name);	}
						
						$i2++;
					}

					// Drop and recreate the deletion table.
					$sql = "drop table if exists del_$table";
					if (!mysql_query($sql,$con))
						{
						die('Could not drop table del_$table from $knight_db: ' . mysql_error());
						}
					$sql = "create table del_$table select * from ref_$table";
					if (!mysql_query($sql,$con))
						{
						die('Could not create del_$table from $knight_db: ' . mysql_error());
						}

					if ($field_count > 0)
					{
						$index_field = $field_list[0];
						$sql = "ALTER TABLE  add_$table ADD INDEX($index_field);";
						$result = mysql_query($sql,$con);
						$sql = "ALTER TABLE  del_$table ADD INDEX($index_field);";
						$result = mysql_query($sql,$con);
					}

					$rec_count = 0;
					$sql = "SELECT $first_field FROM add_$table";
					$result = mysql_query($sql,$con);
					if ($result)
					{	$rec_count = mysql_num_rows($result);	}
					$result_mess = "<font color=$green_code>Merge O.K.</font>";
					$sql="handler add_$table open";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	$result_mess = "<font color=$red_code>Error on open</font>";	}
					$sql="handler add_$table read first";
					$result = mysql_query($sql,$con);
					if ((!$result) && ($rec_count > 0))
					{	$result_mess = "<font color=$red_code>Error on first read</font>";	}

					$i2=0;

					while ($i2 < $rec_count)
					{
						set_time_limit(0);
						$sqls = "delete from del_$table where ";
						$i3=0;
						$and_on = 0;
						while ($i3 < $field_count)
						{	
							$skip_and = false;
							$field_name = $field_list[$i3];
							$field_data = mysql_result($result,0,$field_name);
							if ($field_data == NULL)
							{	$skip_and = true;	}
							else
							{	
								if ($and_on)
								{	$sqls = $sqls . "AND ";	}
								if  (($field_name == "int") || ($field_name == "exp") || ($field_name == "order"))
								{	$sqls = $sqls . "`$field_name` = \"$field_data\" ";	}
								else
								{	$sqls = $sqls . "$field_name = \"$field_data\" ";	}
							$and_on = true;
							}
							$i3++;

						}
						$sqls = $sqls . " limit 1";

						$sql = "insert into dels (dels) values(\"$sqls\")";
						$result = mysql_query($sqls,$con);  // Try and delete the record from the add databa	
		
						$i2++;
						if ($i2 < $rec_count)
						{
							$sql="handler add_$table read next";
							$result = mysql_query($sql,$con);
							if (!$result)
							{	$result_mess = "<font color=$red_code>Error subsequent read</font>";	}
						}
					}

					$sql="handler add_$table close";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	$result_mess = "<font color=$red_code>Error on close</font>";	}

					$rec_count = 0;
					$sql = "SELECT $first_field FROM ref_$table";
					$result = mysql_query($sql,$con);
					if ($result)
					{	$rec_count = mysql_num_rows($result);	}
					$result_mess2 = "<font color=$green_code>Merge O.K.</font>";
					$sql="handler ref_$table open";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	$result_mess2 = "<font color=$red_code>Error on open</font>";	}
					$sql="handler ref_$table read first";
					$result = mysql_query($sql,$con);
					if ((!$result) && ($rec_count > 0))
					{	$result_mess2 = "<font color=$red_code>Error on first read</font>";	}

					$i2=0;

					while ($i2 < $rec_count)
					{
						set_time_limit(0);
						$sqls = "delete from add_$table where ";
						$i3=0;
						$and_on = 0;
						while ($i3 < $field_count)
						{
							$skip_and = false;
							$field_name = $field_list[$i3];
							$field_data = mysql_result($result,0,$field_name);
							if ($field_data == null)
							{	$skip_and = true;	}
							else
							{	
								if ($and_on)
								{	$sqls = $sqls . "AND ";	}
								if  (($field_name == "int") || ($field_name == "exp") || ($field_name == "order"))
								{	$sqls = $sqls . "`$field_name` = \"$field_data\" ";	}
								else
								{	$sqls = $sqls . "$field_name = \"$field_data\" ";	}
								$and_on = true;		
							}
							$i3++;

						}	
						$sqls = $sqls . " limit 1";
						$sql = "insert into dels (dels) values(\"$sqls\")";
						$result = mysql_query($sqls,$con);  // Try and delete the record from the add database
						$i2++;
						if ($i2 < $rec_count)
						{
							$sql="handler ref_$table read next";
							$result = mysql_query($sql,$con);
							if (!$result)
							{	$result_mess2 = "<font color=$red_code>Error subsequent read</font>";	}
						}
					
					}

					$sql="handler ref_$table close";
					$result = mysql_query($sql,$con);
					if (!$result)
					{	$result_mess2 = "<font color=$red_code>Error on close</font>";	}
					$now_t = date("g:i a");
					echo "<td class=\"dropmain\"><p class=\"dropmain\">$result_mess</p></td><td class=\"dropmain\"><p class=\"dropmain\">$now_t</p></td>";
					echo "</tr>";
					$i++;
				}
			}
			else
			{	echo "<td class=\"dropmain\"><h2 class=\"dropmainblack\">Difference password doesn't match.<br>No action taken.</h2></td>";	}
			echo "</tr></table></center>";
		}

// -- Action for Restore
		if ($action == "restore")
		{
			$max_runtime = ini_get('max_execution_time');
			echo "<h2 class=\"dropmain\">Server PHP timeout set to $max_runtime seconds</h2>";
			echo "<center><p class=\"dropmainwhite\">Some scripts can take more than ten minutes to run fully.</p></center>";
			$title = 0;
			if ($password == $conf_restore)
			{				
				if (!mysql_select_db("$db_l2jdb",$con))
					{
					die('Could not change to L2J database: ' . mysql_error());
				}
				$i=0;
				$count = count($tables_backup);
				while ($i < $count)
				{
					$table = $tables_backup[$i];
					if (($table <> "accounts") && ($table <> "gameservers") && ($table <> "knightdrop"))
					{	$sql = "truncate table $table";	}
					else
					{	$sql = "truncate table $dblog_l2jdb.$table";	}
					$result = mysql_query($sql,$con2);
					if (!$result)
						{	echo "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"dropmain\"><p class=\"dropmain\"><strong  class=\"dropmainst\">Warning : $table in $db_l2jdb had been deleted. Check Indexes!!!</strong></p></td></tr></table>";	}
					$i++;
				}
				$i=0;
				while ($i < $count)
				{
					if (!$title)
					{
						echo "<center><table border=\"2\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
						echo "<td class=\"drophead\" colspan=\"2\"><p class=\"center\"><strong class=\"dropmain\">Restore Results</strong></p></td>";
						$title = 1;
					}
					$table = $tables_backup[$i];
					if (($table <> "accounts") && ($table <> "gameservers") && ($table <> "knightdrop"))
					{	$sql = "create table if not exists $table select * from $knight_db.bk_$table";	}
					else
					{	$sql = "create table if not exists $dblog_l2jdb.$table select * from $knight_db.bk_$table";	}
					
					$result = mysql_query($sql,$con);
					if (!$result)
						{
						die('Could not create $table in $db_l2jdb: ' . mysql_error());
						}
					if ($result)
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$green_code>O.K.</font></p></td></tr>";	}
					else
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code>Error</font></p></td></tr>";	}
					$i++;
				}
			}
			else
			{	echo "<td class=\"dropmain\"><h2 class=\"dropmainblack\">Restore password doesn't match.<br>No action taken.</h2></td>";	}
			if ($title)
			{	echo "</tr></table></center>";	}
		}

// -- Action for Backup
		if ($action == "backup")
		{
			$max_runtime = ini_get('max_execution_time');
			echo "<h2 class=\"dropmain\">Server PHP timeout set to $max_runtime seconds</h2>";
			echo "<center><p class=\"dropmainwhite\">Some scripts can take more than ten minutes to run fully.</p></center>";
			echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr>";
			if ($password == $conf_backup)
			{
				echo "<td class=\"drophead\" colspan=\"2\"><p class=\"center\"><strong class=\"dropmain\">Backup Results</strong></p></td>";
				$sql = "USE $knight_db";
				if (!mysql_query($sql,$con))
					{
					die('Could not change to L2J database: ' . mysql_error());
				}
				$i=0;
				$count = count($tables_backup);
				while ($i < $count)
				{
					$table = $tables_backup[$i];
					$sql = "drop table if exists bk_$table";
					if (!mysql_query($sql,$con))
						{
						die('Could not drop table bk_$table from $knight_db: ' . mysql_error());
						}
					if (($table <> "accounts") && ($table <> "gameservers") && ($table <> "knightdrop"))
					{	$sql = "create table bk_$table select * from $db_l2jdb.$table";	}
					else
					{	$sql = "create table bk_$table select * from $dblog_l2jdb.$table";	}
					$result = mysql_query($sql,$con);

					if ($result)
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$green_code>O.K.</font></p></td></tr>";	}
					else
					{	echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\"><font color=$red_code>Error</font></p></td></tr>";	}
					$i++;
				}
			}
			else
			{	echo "<td class=\"dropmain\"><h2 class=\"dropmainblack\">Backup password doesn't match.<br>No action taken.</h2></td>";	}
			echo "</tr></table></center>";
		}
		
// --- Generate Reference Table Output

		echo "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr><td rowspan=\"9\" width=\"50%\" class=\"noborderback\" valign=\"top\"><center><table width=\"50%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td rowspan=\"53\" width=\"50%\" class=\"dropmain\">";
		echo "<h2 class=\"dropmainblack\">Merged Tables</h2>";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">Table</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Live DB</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Reference DB</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Add DB</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Remove DB</strong></p></td></tr>";
		$i=0;
		$count = count($tables_merge);
		while ($i < $count)
		{
			$table = $tables_merge[$i];
			$db_lcount = 0;
			$db_bcount = 0;
			$db_acount = 0;
			$db_dcount = 0;
			if (!mysql_select_db("$db_l2jdb",$con))
				{
				die('Could not change to L2J database: ' . mysql_error());
				}
			$sql = "show fields from $table";
			$result = mysql_query($sql,$con);
			if ($result)
			{	$first_field = mysql_result($result,0,"field");	}
			$sql = "SELECT `$first_field` FROM $table";
			$result = mysql_query($sql,$con);
			if ($result)
			{	$db_lcount = mysql_num_rows($result);	}
			$sql = "USE $knight_db";
			if (!mysql_query($sql,$con))
				{
				die('Could not change to $knight_db database: ' . mysql_error());
				}
			$sql = "SELECT `$first_field` FROM ref_$table";
			$result = mysql_query($sql,$con);
			if ($result)
			{	$db_bcount = mysql_num_rows($result);	}
			$sql = "SELECT `$first_field` FROM add_$table";
			$result = mysql_query($sql,$con);
			if ($result)
			{	$db_acount = mysql_num_rows($result);	}
			$sql = "SELECT `$first_field` FROM del_$table";
			$result = mysql_query($sql,$con);
			if ($result)
			{	$db_dcount = mysql_num_rows($result);	}
			echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\">$db_lcount</p></td><td class=\"dropmain\"><p class=\"dropmain\">$db_bcount</p></td><td class=\"dropmain\"><p class=\"dropmain\">$db_acount</p></td><td class=\"dropmain\"><p class=\"dropmain\">$db_dcount</p></td></tr>";
			$i++;
		}
		echo "</table></center></td></tr></table></center>";
		echo "</td><td rowspan=\"53\" width=\"50%\" class=\"noborderback\" valign=\"top\"><center>";
		echo "<table width=\"50%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td rowspan=\"9\" width=\"50%\">";
		echo "<h2 class=\"dropmainblack\">Backup Tables</h2>";
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"dropmain\"><tr><td class=\"lefthead\"><p class=\"center\"><strong class=\"dropmain\">Table</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Live DB</strong></p></td><td class=\"drophead\"><p class=\"center\"><strong class=\"dropmain\">Backup DB</strong></p></td></tr>";
		$i=0;
		$count = count($tables_backup);
		while ($i < $count)
		{
			$table = $tables_backup[$i];
			$db_lcount = 0;
			$db_bcount = 0;
			if (!mysql_select_db("$db_l2jdb",$con))
				{
				die('Could not change to L2J database: ' . mysql_error());
				}
	
			if (($table <> "accounts") && ($table <> "gameservers") && ($table <> "knightdrop"))
			{	$sql = "show fields from $table";
				$result = mysql_query($sql,$con);
				if ($result)
				{	$first_field = mysql_result($result,0,"field");	}
				$sql = "SELECT $first_field FROM $table";	
			}
			else
			{	$sql = "show fields from $dblog_l2jdb.$table";
				$result = mysql_query($sql,$con2);
				if ($result)
				{	$first_field = mysql_result($result,0,"field");	}
				$sql = "SELECT $first_field FROM $dblog_l2jdb.$table";	}
			
			$result = mysql_query($sql,$con2);
			if ($result)
			{	$db_lcount = mysql_num_rows($result);	}
			$sql = "USE $knight_db";
			if (!mysql_query($sql,$con))
				{
				die('Could not change to $knight_db database: ' . mysql_error());
				}
			$sql = "SELECT $first_field FROM bk_$table";
			$result = mysql_query($sql,$con);
			if ($result)
			{	$db_bcount = mysql_num_rows($result);	}
			echo "<tr><td class=\"dropmain\"><p class=\"left\">$table</p></td><td class=\"dropmain\"><p class=\"dropmain\">$db_lcount</p></td><td class=\"dropmain\"><p class=\"dropmain\">$db_bcount</p></td></tr>";
			$i++;
		}
		echo "</table></center></td></tr></table></center></td>";
		echo "<td class=\"noborderback\"><p class=\"dropmainwhite\">Step 1. Load the data pack.</p><hr></td></tr>
		<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 2. Check the table structures for differences...</p><form action=\"dbutils.php\">
		<input value=\"Check Structures\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"><hr></td>
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"action\" type=\"hidden\" value=\"checks\">
		</form></td></tr>";
		echo "<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 3. Take a reference point...</p><form action=\"dbutils.php\">
		<input class=\"dropmain\" height=\"20\" name=\"password\" maxlength=\"93\" size=\"20\" type=\"text\" value=\"\" class=\"popup\">
		<input value=\"Take Reference Point\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"><hr></td>
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"action\" type=\"hidden\" value=\"reference\">
		</form></td></tr>";		
		echo "<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 4. Replay your differences...<form action=\"dbutils.php\">
		<input class=\"dropmain\" height=\"20\" name=\"password\" maxlength=\"93\" size=\"20\" type=\"text\" value=\"\" class=\"popup\">
		<input value=\"Replay Differences\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"><hr></td>
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"action\" type=\"hidden\" value=\"replay\">
		</form></td></tr>";		
		echo "<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 5. Restore your game tables...</p><form action=\"dbutils.php\">
		<input class=\"dropmain\" height=\"20\" name=\"password\" maxlength=\"93\" size=\"20\" type=\"text\" value=\"\" class=\"popup\">
		<input value=\"Restore Tables\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"><hr><hr></td>
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"action\" type=\"hidden\" value=\"restore\">
		</form></td></tr>";	
		echo "<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 6. Play the game!</p><hr><hr></td></tr>";
		echo "<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 7. When ready to upgrade again, extract your current differences...<form action=\"dbutils.php\">
		<input class=\"dropmain\" height=\"20\" name=\"password\" maxlength=\"93\" size=\"20\" type=\"text\" value=\"\" class=\"popup\">
		<input value=\"Make Difference File\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"><hr></td>
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"action\" type=\"hidden\" value=\"makedif\">
		</form></td></tr>";
		echo "<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 8. Backup your game tables...</p><form action=\"dbutils.php\">
		<input class=\"dropmain\" height=\"20\" name=\"password\" maxlength=\"93\" size=\"20\" type=\"text\" value=\"\" class=\"popup\">
		<input value=\"Backup Tables\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"popupcentre\"><hr></td>
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<input name=\"action\" type=\"hidden\" value=\"backup\">
		</form></td></tr>";
		echo "<tr><td class=\"noborderback\"><p class=\"dropmainwhite\">Step 9. Go to step 1...</p><hr></td></tr>";


		echo "</table>";	
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>