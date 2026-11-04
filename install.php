<?php 
error_reporting(E_ERROR | E_PARSE);

/*
install.php - creates the DropCalc tables, per database.
Who may use it: accounts listed in $install_admins (config.php), or - once DropCalc is
installed - admins (accessLevel >= sec_inc_admin, verified).
Run it for the LOGIN database and for every game server database in $gameservers.
*/

include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');
require_once(__DIR__ . '/dc_install_lib.php');

$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$action = input_check($_REQUEST['action'],0);
$target = input_check($_REQUEST['target'],0);
$confirm = input_check($_REQUEST['confirm'],0);

$langfile = $language_array[$langval][1];
include($langfile);

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

function h($s)
{	return htmlspecialchars((string) $s);	}

if ($evaluser)
{
	$is_installer = dc_is_installer($username) || (empty($dc_not_installed) && ($user_access_lvl >= $sec_inc_admin));
	if (!$is_installer)
	{
		writewarn("You don't have sufficient access to install the DropCalc tables.");
	}
	else
	{
		// The installer opens its own database connections; keep the page's "current" one.
		$saved_link = $GLOBALS['__mysql_compat_last_link'];

		// ---- what can be installed: the login DB and every game server DB ----
		$targets = array();
		$targets[] = array('key' => 'login', 'title' => "Login database", 'host' => $dblog_location, 'user' => $dblog_user, 'pass' => $dblog_psswd, 'db' => $dblog_l2jdb, 'file' => dc_sql_file('login'));
		foreach ($gameservers as $i => $g)
		{
			$targets[] = array('key' => (string) $i, 'title' => $g[0], 'host' => $g[1], 'user' => $g[3], 'pass' => $g[4], 'db' => $g[2], 'file' => dc_sql_file('game'));
		}

		// ---- run a requested action ----
		$messages = array();
		if (($action == "install" || $action == "recreate") && $_SERVER['REQUEST_METHOD'] == 'POST')
		{
			foreach ($targets as $t)
			{
				if ($t['key'] !== (string) $target)
				{	continue;	}
				$names = dc_sql_file_tables($t['file']);
				$c = @mysql_connect($t['host'], $t['user'], $t['pass']);
				if (!$c || !@mysql_select_db($t['db'], $c))
				{
					$messages[] = array('bad', $t['title'] . ": cannot open database " . h($t['db']) . " - " . h(mysql_error($c ? $c : null)));
					break;
				}
				@mysql_query("SET NAMES 'utf8'", $c);
				if ($action == "recreate")
				{
					if ($confirm != "1")
					{
						$messages[] = array('bad', $t['title'] . ": nothing was done - tick the confirmation box to recreate the tables from scratch.");
						break;
					}
					dc_drop_tables($c, $names);
				}
				$err = dc_exec_sql_file($c, $t['file']);
				if ($err != '')
				{	$messages[] = array('bad', $t['title'] . ": the install stopped with an error - " . h($err) . " (the database user needs CREATE and, for a recreate, DROP rights).");	}
				else
				{	$messages[] = array('good', $t['title'] . ": tables " . ($action == "recreate" ? "recreated from scratch." : "installed.") . ($t['key'] === 'login' && $action == "recreate" ? " All DropCalc users were recreated from the login server accounts, so you have to log in again." : ""));	}
				break;
			}
		}

		echo "<center><h2 class=\"dropmain\">Install DropCalc tables</h2>";
		if (!empty($dc_not_installed))
		{	echo "<p class=\"dropmain\">DropCalc is not installed on the first server's database yet, built-in default settings are in use.</p>";	}
		echo "<p class=\"dropmain\">Install the <strong>login database</strong> and then each server. The first server's database also holds the interface settings shared by all servers, so install it first.<br>Only the DropCalc tables (<strong>knight*</strong>) are touched, never the game's own tables.</p>";
		foreach ($messages as $m)
		{	echo "<p class=\"dropmain\"><strong><font color=\"" . ($m[0] == 'good' ? '#00aa00' : '#cc0000') . "\">" . $m[1] . "</font></strong></p>";	}

		echo "<table class=\"dropmain\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\">";
		echo "<tr><td class=\"drophead\"><strong class=\"dropmain\">Database</strong></td><td class=\"drophead\"><strong class=\"dropmain\">State</strong></td><td class=\"drophead\"><strong class=\"dropmain\">Action</strong></td></tr>";
		foreach ($targets as $t)
		{
			$names = dc_sql_file_tables($t['file']);
			$total = count($names);
			$state = ""; $present = array(); $row_ok = true; $can_connect = false;
			$c = @mysql_connect($t['host'], $t['user'], $t['pass']);
			if (!$c)
			{	$state = "<font color=\"#cc0000\">cannot connect to " . h($t['host']) . " - " . h(mysql_error()) . "</font>";	}
			elseif (!@mysql_select_db($t['db'], $c))
			{	$state = "<font color=\"#cc0000\">database " . h($t['db']) . " not found - " . h(mysql_error($c)) . "</font>";	}
			else
			{
				$can_connect = true;
				$present = dc_present_tables($c, $t['db'], $names);
				if ($t['key'] !== 'login' && in_array('knightsettings', $present))
				{
					$rs = @mysql_query("select count(*) as n from knightsettings", $c);
					$row_ok = ($rs && mysql_result($rs, 0, "n") > 0);
				}
				if (count($present) == 0)
				{	$state = "<font color=\"#cc0000\">not installed</font> (0 of $total tables)";	}
				elseif (count($present) < $total || !$row_ok)
				{	$state = "<font color=\"#cc8800\">partly installed</font> (" . count($present) . " of $total tables" . ($row_ok ? "" : ", settings row missing") . ")";	}
				else
				{	$state = "<font color=\"#00aa00\">installed</font> ($total tables)";	}
			}
			$complete = $can_connect && count($present) == $total && $row_ok;
			echo "<tr><td class=\"dropmain\"><strong class=\"dropmain\">" . h($t['title']) . "</strong><br>" . h($t['db']) . " @ " . h($t['host']) . "</td><td class=\"dropmain\">$state</td><td class=\"dropmain\">";
			if ($can_connect)
			{
				$hidden = "<input type=\"hidden\" name=\"username\" value=\"" . h($username) . "\"><input type=\"hidden\" name=\"token\" value=\"" . h($token) . "\"><input type=\"hidden\" name=\"langval\" value=\"" . h($langval) . "\"><input type=\"hidden\" name=\"server_id\" value=\"" . h($server_id) . "\"><input type=\"hidden\" name=\"skin_id\" value=\"" . h($skin_id) . "\"><input type=\"hidden\" name=\"target\" value=\"" . h($t['key']) . "\">";
				if ($complete)
				{	echo "<input type=\"button\" value=\"Install tables\" disabled class=\"bigbut2\"> ";	}
				else
				{	echo "<form method=\"post\" action=\"install.php\" style=\"display:inline\">$hidden<input type=\"hidden\" name=\"action\" value=\"install\"><input type=\"submit\" value=\"" . (count($present) ? "Install missing tables" : "Install tables") . "\" class=\"bigbut2\"></form> ";	}
				if (count($present) > 0)
				{
					$warn = "This DELETES all DropCalc data in " . addslashes($t['db']) . " (" . ($t['key'] === 'login' ? "users, access times, e-mail addresses" : "settings, imported game data, trusted list, statistics") . ") and creates the tables from scratch. Continue?";
					echo "<form method=\"post\" action=\"install.php\" style=\"display:inline\" onsubmit=\"return confirm('" . h($warn) . "');\">$hidden<input type=\"hidden\" name=\"action\" value=\"recreate\"><label><input type=\"checkbox\" name=\"confirm\" value=\"1\"> yes, delete and recreate</label> <input type=\"submit\" value=\"Recreate from scratch\" class=\"bigbut2\"></form>";
				}
			}
			echo "</td></tr>";
		}
		echo "</table></center>";
		$GLOBALS['__mysql_compat_last_link'] = $saved_link;
	}
}

$refresh_timer = 0;
$refresh_string = "";
$quality_level = 0;
wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
