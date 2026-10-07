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

		if (($action == "extreload") && ($user_access_lvl >= $sec_inc_admin))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'extreload';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}

		if (($action == "extinit") && ($user_access_lvl >= $sec_inc_admin))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'extinit';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}

		if (($action == "extunload") && ($user_access_lvl >= $sec_inc_admin))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'extunload';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}

		if (($action == "restart1") && ($user_access_lvl >= $reboot_server))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'restart 60';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
			$action = "";
		}

		if (($action == "restart3") && ($user_access_lvl >= $reboot_server))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'restart 180';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
			$action = "";
		}

		if (($action == "restart10") && ($user_access_lvl >= $reboot_server))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'restart 600';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
			$action = "";
		}

		if (($action == "abort") && ($user_access_lvl >= $reboot_server))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'abort';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}

		if (($action == "purge") && ($user_access_lvl >= $sec_inc_admin))	
		{
			$usetelnet = fsockopen($telnet_host, $telnet_port, $errno, $errstr, $telnet_timeout);
			if($usetelnet)
			{
				$give_string = 'purge';
				fputs($usetelnet, $telnet_password);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, $give_string);
				fputs($usetelnet, "\r\n");
				fputs($usetelnet, "exit\r\n");
				fclose($usetelnet);
			}
			else
			{	echo "<p class=\"popup\">Couldn't connect to telnet</p>";	}
		}

		echo "<p>&nbsp;</p><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Gameserver Operations</strong></p>";
		echo "<table width=\"100%\" cellpadding=\"20\" class=\"blanktab\"><tr>";

		if ($user_access_lvl >= $reboot_server)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"restart1\"><input value=\"Reboot 1 minute\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $reboot_server)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"restart3\"><input value=\"Reboot 3 minutes\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $reboot_server)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"restart10\"><input value=\"Reboot 10 minutes\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr><tr>";

		if ($user_access_lvl >= $reboot_server)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"abort\"><input value=\"Abort Reboot\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"purge\"><input value=\"Purge Thread Pools\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('freetelnet.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&touser=$character','470','130');\"><input value=\"Freeform Telnet\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr><tr>";

		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"extreload\"><input value=\"Extreload all\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"extinit\"><input value=\"Extinit all\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"servertools.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"extunload\"><input value=\"Extunload all\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }


		echo "</tr><tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Item History System</strong></p></td></tr><tr>";
		
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('itemsnap.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&file=0','400','150');\"><input value=\"Take Snapshot\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('itemsnap2.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&file=0','400','150');\"><input value=\"Process Item History Log\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_giveandtake)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popot('itemhist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&fromuser=$c_num&itemid=-4&itemqty=$i_count&usern=$c_name&location=$i_loc&binloc=$i_binloc','600','500');\"><input value=\" Deleted Items \" type=\"submit\" class=\"bigbut2\"></form></td>";	}
		else
		{	echo "<td class=\"noborderback\">&nbsp;</td>";	}

		echo "</tr><tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Regular Database Checks</strong></p></td></tr><tr>";
		
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"checkdb.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Check Database\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"checkdb.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=go\"><input value=\"Clean Up Database\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"bannedacc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"List Banned Accs.\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		
		echo "</tr><tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Enchanted Items Check</strong></p></td></tr><tr>";

		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"ei-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&enchant=5\"><input value=\"Enchant 5+\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"ei-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&enchant=10\"><input value=\"Enchant 10+\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"ei-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&enchant=50\"><input value=\"Enchant 50+\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr><tr>";

		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"ei-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&enchant=100\"><input value=\"Enchant 100+\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"ei-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&enchant=1000\"><input value=\"Enchant 1,000+\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_gmlevel)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"ei-search.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&enchant=60000\"><input value=\"Enchant 60,000+\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		
		echo "</tr><tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Import Tools</strong></p></td></tr><tr>";

		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('importitems.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=0','400','150');\"><input value=\"Import Items\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('importnpc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=0','400','150');\"><input value=\"Import NPCs\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr><tr>";
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('importacc.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','470','180');\"><input value=\"Import Logon Accounts\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('importskill.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','500','200');\"><input value=\"Import Skills.\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('importrec.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','500','200');\"><input value=\"Import Recipes\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr>";

		if ($user_access_lvl >= $sec_inc_admin)
		{	
			echo "<tr><td class=\"noborderback\" colspan=\"2\"><center><p class=\"dropmain\"><form method=\"post\" enctype=\"multipart/form-data\" action=\"import.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"500000\">Account Import: <input name=\"file\" type=\"file\"><input value=\"Import File\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></p></center></td>";
			echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('importquests.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&next=0','400','150');\"><input value=\"Import Quests\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";
			echo "</tr>";	}
	
		echo "<tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Dormant Accounts</strong></p></td></tr><tr>";

		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('delete.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&dormant=year','500','200');\"><input value=\"1 Year Dormant Delete\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('delete.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&dormant=six','500','200');\"><input value=\"6 Month Dormant Delete\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('delete.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&dormant=three','500','200');\"><input value=\"3 Month Dormant Delete\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr><tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Other DB Tools</strong></p></td></tr><tr>";

		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('autotest.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','500','200');\"><input value=\"Create Test Character\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"npccreate.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Clone NPC\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"ip-table.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Box OK IPs\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr><tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Skills Changer</strong></p></td></tr><tr>";

		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"skillclist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Changes List\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('skillalist.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&file=0','400','150');\"><input value=\"Apply Changes\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\">&nbsp;</td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr><tr>";
		echo "<td class=\"noborderback\" colspan=\"3\"><hr><p class=\"dropmainwhite\"><strong class=\"dropmainst\">Logon Server</strong></p></td></tr><tr>";

		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('logonstat.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&dormant=three','500','600');\"><input value=\"Logon Status\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('logonr.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&dormant=three','500','200');\"><input value=\"Restart Logon\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }
		if ($user_access_lvl >= $sec_inc_admin)
		{	echo "<td class=\"noborderback\"><center><form method=\"post\" action=\"javascript:popit('logons.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&dormant=three','500','200');\"><input value=\"Shutdown Logon\" onclick=\"submit\" height=\"9\" type=\"submit\" class=\"bigbut2\"></form></center></td>";	}
		else	{	echo "<td class=\"noborderback\">&nbsp;</td>"; }

		echo "</tr></table>";
		
	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
