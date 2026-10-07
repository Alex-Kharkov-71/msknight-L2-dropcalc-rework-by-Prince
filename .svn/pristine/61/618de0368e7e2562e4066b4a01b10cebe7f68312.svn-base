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

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$action = input_check($_REQUEST['action'],0);
$number = preg_replace('/[&%$\\\|<>#£]/','',$_REQUEST['number']);

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

if ($evaluser)
{
	if ($user_access_lvl < $sec_inc_admin)
	{
		writewarn("You don't have sufficient access.");
		wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);
		return 0;
	}
	else
	{
	
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


		echo "<p class=\"dropmain\">&nbsp;</p><h2 class=\"dropmain\">Cross Gameserver Settings</h2>";

		
		$con3 = mysql_connect($core_db_location, $core_db_user, $core_db_psswd);
		mysql_query("SET NAMES 'utf8'", $con3);
		if (!$con)
		{
			echo "Could Not Connect";
			die('Could not connect: ' . mysql_error());
		}		
		if (!mysql_select_db("$db_l2jdb",$con))
		{
			die('Could not change to L2J database: ' . mysql_error());
		}

		if ($action == "hidelanguage")
		{
			$sql = "update $core_db_l2jdb.knightsettings set hide_language = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "hideservers")
		{
			$sql = "update $core_db_l2jdb.knightsettings set hide_server = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "hideskins")
		{
			$sql = "update $core_db_l2jdb.knightsettings set hide_skin = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "changeserver")
		{
			$sql = "update $core_db_l2jdb.knightsettings set user_change_server = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "changeskin")
		{
			$sql = "update $core_db_l2jdb.knightsettings set user_change_skin = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "forceskin")
		{
			$sql = "update $core_db_l2jdb.knightsettings set force_default_skin = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "showonline")
		{
			$sql = "update $core_db_l2jdb.knightsettings set show_online = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "phptype")
		{
			$sql = "update $core_db_l2jdb.knightsettings set php_type = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "emailfrom")
		{
			$sql = "update $core_db_l2jdb.knightsettings set emailfrom   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "phpsmtp")
		{
			$sql = "update $core_db_l2jdb.knightsettings set phpsmtp   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "smtpserver")
		{
			$sql = "update $core_db_l2jdb.knightsettings set smtpserver   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "smtpport")
		{
			if ($number < 10)
			{	$number = 10;	}
			$sql = "update $core_db_l2jdb.knightsettings set smtpport   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "smtptimeout")
		{
			if ($number < 10)
			{	$number = 10;	}
			$sql = "update $core_db_l2jdb.knightsettings set smtptimeout   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "smtpusername")
		{
			$sql = "update $core_db_l2jdb.knightsettings set smtpusername   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "smtppassword")
		{
			$sql = "update $core_db_l2jdb.knightsettings set smtppassword   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "smtplocalhost")
		{
			$sql = "update $core_db_l2jdb.knightsettings set smtplocalhost   = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "smtpdebug")
		{
			$sql = "update $core_db_l2jdb.knightsettings set smtpdebug = '$number'";
			$result = mysql_query($sql,$con3);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "allowreminder")
		{
			$sql = "update $core_db_l2jdb.knightsettings set allowpassreset = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "emailcheck")
		{
			$sql = "update $core_db_l2jdb.knightsettings set emailcheck = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "gdon")
		{
			$sql = "update $core_db_l2jdb.knightsettings set gd_on = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "gdstyle")
		{
			$sql = "update $core_db_l2jdb.knightsettings set gd_style = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "gdsrvon")
		{
			$sql = "update $core_db_l2jdb.knightsettings set gd_srvon = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "gdcompress")
		{
			$sql = "update $core_db_l2jdb.knightsettings set gd_compress = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		if ($action == "menushowchars")
		{
			$sql = "update $core_db_l2jdb.knightsettings set menushowchars = '$number'";
			$result = mysql_query($sql,$con);
			if (!$result)
			{
				die('Could not update settings table: ' . mysql_error());
			}
		}
		
		include('config-read.php');
		
		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Dropcalc Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings2.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Technical Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings3.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Cross Gameserver Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"settings4.php\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input value=\"Character Permission Settings\" type=\"submit\" class=\"bigbut2\"></form></td>";
		echo "</tr></table>";
		echo "<p>&nbsp;</p>";


		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// Hide the language option
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"hidelanguage\">Hide language if only one</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($hide_language)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, then if the server only has one language defined, then the language selection will be hidden.</p></td></tr>";
		
		// Hide the servers option
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"hideservers\">Hide servers if only one</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($hide_server)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, then if the server only has one server defined, then the server selection will be hidden.</p></td></tr>";
		
		// Hide the skins option
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"hideskins\">Hide skins if only one</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($hide_skin)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, then if the server only has one skin defined, then the skin selection will be hidden.</p></td></tr>";
		
		// Allow change server after logon
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"changeserver\">Allow change server after logon</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($user_change_server)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Determines whether a logged on user can change the server once they have logged on to the dropcalc.</p></td></tr>";
		
		// Allow change skin after logon
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"changeskin\">Allow change skin after logon</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($user_change_skin)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Determines whether a logged on user can change the skin once they have logged on to the dropcalc.</p></td></tr>";
		
		// Force default skin
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"forceskin\">Force users to have default skin</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($force_default_skin)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, forces the user to have the default skin applied that has been specified in the server settings.  The setting to allow skins is overridden if this is set to yes.  This does not apply to GM's.</p></td></tr>";
		
		// Force default skin
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"showonline\">Whether to show server online status</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($show_online == 1)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>Front Only</option><option value=\"2\">GM Always</option>";	}
		elseif ($show_online == 2)
		{	echo "<option value=\"0\">Off</option><option value=\"1\">Front Only</option><option value=\"2\" selected>GM Always</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">Front Only</option><option value=\"2\">GM Always</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The server online status can either be hidden, shown on the front page for everyone, or shown on the front page for everyone and continuously on the side for GM's.</p></td></tr>";
		
		// Show characters names in menu
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"menushowchars\">Show chars in menu</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($menushowchars == 1)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Shows the character names above the \"Items\" in the menu structure.</p></td></tr>";
		
		// PHP type
		echo "<tr><form method=\"post\" action=\"settings3.php#block8\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"phptype\">Defines the PHP age</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($php_type)
		{	echo "<option value=\"0\">&lt;5</option><option value=\"1\" selected>&gt;=5</option>";	}
		else
		{	echo "<option value=\"0\" selected>&lt;5</option><option value=\"1\">&gt;=5</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Newer PHP systems can user the stream_get_line function, and this speeds up the log watching functions.  PHP 5 and above only.  Windows users who can support the newer PHP functions can use this to watch the system and chat logs, but not the individual player chat.</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block4\"></a>";
	
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// GD On
		echo "<tr><form method=\"post\" action=\"settings3.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"gdon\">Allow GD</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($gdon ==1)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option><option value=\"2\">Subscribe</option>";	}
		elseif ($gdon ==2)
		{	echo "<option value=\"0\">Off</option><option value=\"1\">On</option><option value=\"2\" selected>Subscribe</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option><option value=\"2\">Subscribe</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If On, then people can access GD images of their character and account status. NOTE - You need the GD WITH JPEG SUPPORT loaded in to your PHP.<br>The subscribe option does not automatically give display rights to GM's or Admins; each account has to be manually changed.  This allows you tighter control over your bandwidth.</p></td></tr>";
		
		// GD Style
		echo "<tr><form method=\"post\" action=\"settings3.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"gdstyle\">GD Style</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($gdstyle == 1)
		{	echo "<option value=\"0\">Detailed</option><option value=\"1\" selected>Basic</option>";	}
		else
		{	echo "<option value=\"0\" selected>Detailed</option><option value=\"1\">Basic</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Selects from different styles of information shown to the user in the character GD.</p></td></tr>";
		
		// GD SRV On
		echo "<tr><form method=\"post\" action=\"settings3.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"gdsrvon\">Allow Server GD</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($gdsrvon)
		{	echo "<option value=\"0\">Off</option><option value=\"1\" selected>On</option>";	}
		else
		{	echo "<option value=\"0\" selected>Off</option><option value=\"1\">On</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If On, then people can access GD images of the server status. NOTE - You need the GD WITH JPEG SUPPORT loaded in to your PHP.</p></td></tr>";
		
		// GD Compress
		echo "<tr><form method=\"post\" action=\"settings3.php#block4\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"gdcompress\">GD Compress</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($gdcompress == 1)
		{	echo "<option value=\"0\">None</option><option value=\"1\" selected>90%</option><option value=\"2\">75%</option><option value=\"3\">60%</option>";	}
		elseif ($gdcompress == 2)
		{	echo "<option value=\"0\">None</option><option value=\"1\">90%</option><option value=\"2\" selected>75%</option><option value=\"3\">60%</option>";	}
		elseif ($gdcompress == 3)
		{	echo "<option value=\"0\">None</option><option value=\"1\">90%</option><option value=\"2\">75%</option><option value=\"3\" selected>60%</option>";	}
		else
		{	echo "<option value=\"0\" selected>None</option><option value=\"1\">90%</option><option value=\"2\">75%</option><option value=\"3\">60%</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Controls the rate of compression used in the GD image. None=45k, 90%=20k, 75%=11k</p></td></tr>";
		
		echo "</table></center>";
		
		echo "<p>&nbsp;</p><a name=\"block9\"></a>";
	
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\">";
		
		// E-Mail From Address
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"emailfrom\">Source E-Mail address for sending password reminders</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"30\" size=\"20\" value=\"$e_mail_from\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">This e-mail address is the source e-mail address applied to the FROM and REPLY TO headers for e-mail.  This is required to be properly set in order to allow e-mail through most gateways.</p></td></tr>";

		// Password reminder
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"allowreminder\">Allow password reminder</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($allowpassreset)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, people can register game accounts via the dropcalc.  If no, then you will need to provide an alternative registration method.</p></td></tr>";
		
		// Email Authentication Check
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"emailcheck\">Force e-mail authentication of new accounts</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($emailcheck)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">This option sends an e-mail to new accounts registrations to force them to authenticate their e-mail addresses for new accounts.  This setting requires that password reminding is also set to on, otherwise this option will not be enforced.</p></td></tr>";

		// PHP or SMTP
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"phpsmtp\">Use PHP mail() or SMTP mail</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($phpsmtp)
		{	echo "<option value=\"0\">PHP</option><option value=\"1\" selected>SMTP</option>";	}
		else
		{	echo "<option value=\"0\" selected>PHP</option><option value=\"1\">SMTP</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">If yes, people can register game accounts via the dropcalc.  If no, then you will need to provide an alternative registration method.</p></td></tr>";

		// SMTP Host
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"smtpserver\">SMTP server address or IP.</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"30\" size=\"20\" value=\"$smtpserver\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">This is either the IP address or the host name of the SMTP server to communicate with.  Rarely, the server name might need to be preceded by \"mail.\" to make it work.</p></td></tr>";

		// port
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"smtpport\">Port to communicate with the SMTP server</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"5\" size=\"5\" value=\"$smtpport\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The port number to communicate with the SMTP server on.  Usually 25 unless it has been changed by an admin for security purposes.</p></td></tr>";
		
		// timeout
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"smtptimeout\">Timeout value for SMTP communication</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"2\" size=\"2\" value=\"$smtptimeout\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">The timeout for most mail systems can be left at 30 seconds, but can be increased if necessary.</p></td></tr>";

	
		// Username
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"smtpusername\">Uername for SMTP communication, if required</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"30\" size=\"20\" value=\"$smtpuser\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Username to pass to the SMTP server if authentication is required.</p></td></tr>";

		// Password
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"smtppassword\">Password for the SMTP server if required</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"password\" name=\"number\" maxlength=\"30\" size=\"20\" value=\"$smtppassword\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Password to pass to the SMTP server if authentication is required.</p></td></tr>";

		// Localhost
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"smtplocalhost\">Localhost setting for SMTP</td><td class=\"dropmain\" valign=\"top\">";
		echo "<input type=\"text\" name=\"number\" maxlength=\"30\" size=\"20\" value=\"$smtplocalhost\">";
		echo "</td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">Some SMTP servers require the domain of the sending system, such as \"yourdomain.com\" for example.</p></td></tr>";

		// SMTP Debug
		echo "<tr><form method=\"post\" action=\"settings3.php#block9\"><td class=\"dropmain\" valign=\"top\"><p class=\"left\"><input name=\"username\" type=\"hidden\" value=\"$username\"><input name=\"token\" type=\"hidden\" value=\"$token\"><input name=\"langval\" type=\"hidden\" value=\"$langval\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><input name=\"action\" type=\"hidden\" value=\"smtpdebug\">Allows the e-mail system to display debug code when it sends mail.</td><td class=\"dropmain\" valign=\"top\"><select name=\"number\">";
		if ($smtp_debug)
		{	echo "<option value=\"0\">No</option><option value=\"1\" selected>Yes</option>";	}
		else
		{	echo "<option value=\"0\" selected>No</option><option value=\"1\">Yes</option>";	}
		echo "</select></td><td class=\"dropmain\" valign=\"top\"><input value=\"<- Change\" type=\"submit\" class=\"bigbut2\"></p></td></form><td class=\"noborderback\" valign=\"top\"><p class=\"dropmainwhite\">This option sends an e-mail to new accounts registrations to force them to authenticate their e-mail addresses for new accounts.  This setting requires that password reminding is also set to on, otherwise this option will not be enforced.</p></td></tr>";


		echo "</table></center>";
		
		echo "<p>&nbsp;</p>";

	}
}

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>