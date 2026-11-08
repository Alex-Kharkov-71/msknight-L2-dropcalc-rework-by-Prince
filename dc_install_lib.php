<?php
/*
dc_install_lib.php
-------------------
Helpers for a fresh install of DropCalc (needs mysql_compat.php, loaded by config.php).

 - dc_read_settings(): reads knightsettings; if the table is missing or empty it returns the
   built-in defaults instead, so the login page opens on an empty database.
 - dc_ensure_login_tables(): creates knightdrop / knightipok in the LOGIN database when they
   are missing - without them nobody can log in.
 - dc_is_installer(): accounts listed in $install_admins (config.php) may run install.php even
   when nothing is installed yet and nobody has admin rights.
 - dc_present_tables() / dc_exec_sql_file() / dc_drop_tables(): used by install.php.
*/

if (!defined('DC_INSTALL_LIB'))
{
define('DC_INSTALL_LIB', 1);

function dc_sql_file($which)
{
	return __DIR__ . '/sql/' . ($which == 'login' ? 'install_login_db.sql' : 'install_game_db.sql');
}

function dc_settings_defaults()
{
	static $d = null;
	if ($d === null)
	{	$d = include __DIR__ . '/dc_settings_defaults.php';	}
	return $d;
}

// Same as "select * from <table>" for the one-row knightsettings table, but never fails:
// on a database without it the result is a one-row set built from the defaults.
function dc_read_settings($con, $table)
{
	$r = @mysql_query("select * from $table", $con);
	if ($r && mysql_num_rows($r) > 0)
	{	return $r;	}
	$GLOBALS['dc_not_installed'] = 1;
	$defaults = dc_settings_defaults();
	// Safe start: nobody is GM or admin, no registration, no guests. (The table default
	// 0 would make every player an admin.)
	$defaults['sec_inc_gmlevel'] = 256;
	$defaults['sec_inc_admin'] = 256;
	$defaults['register_allow'] = 0;
	$defaults['guest_allow'] = 0;
	$cols = array();
	foreach ($defaults as $c => $v)
	{
		$lit = (is_int($v) || is_float($v)) ? $v : "'" . str_replace("'", "''", (string) $v) . "'";
		$cols[] = "$lit AS `$c`";
	}
	return mysql_query("select " . implode(', ', $cols), $con);
}

function dc_is_installer($username)
{
	$list = isset($GLOBALS['install_admins']) && is_array($GLOBALS['install_admins']) ? $GLOBALS['install_admins'] : array();
	$username = trim((string) $username);
	if ($username === '' || $username == 'guest')
	{	return false;	}
	foreach ($list as $a)
	{
		if (strcasecmp(trim((string) $a), $username) === 0)
		{	return true;	}
	}
	return false;
}

// CREATE TABLE statements of an install file
function dc_create_statements($file)
{
	$sql = @file_get_contents($file);
	if ($sql === false)
	{	return array();	}
	preg_match_all('/^CREATE TABLE IF NOT EXISTS `knight\w+` \(.*?\n\) ENGINE=[^\n]*;/ms', $sql, $m);
	return $m[0];
}

// names of the knight* tables an install file creates
function dc_sql_file_tables($file)
{
	$sql = @file_get_contents($file);
	if ($sql === false)
	{	return array();	}
	preg_match_all('/^CREATE TABLE IF NOT EXISTS `(knight\w+)`/m', $sql, $m);
	return array_values(array_unique($m[1]));
}

// Which of $names exist in database $db (using connection $con)
function dc_present_tables($con, $db, $names)
{
	$have = array();
	$r = @mysql_query("show tables from `" . str_replace('`', '', $db) . "`", $con);
	while ($r && ($row = mysql_fetch_row($r)))
	{	$have[strtolower($row[0])] = 1;	}
	$present = array();
	foreach ($names as $n)
	{
		if (isset($have[strtolower($n)]))
		{	$present[] = $n;	}
	}
	return $present;
}

// Login database: knightdrop and knightipok must exist before anybody can log in.
function dc_ensure_login_tables($con)
{
	if (@mysql_query("select 1 from knightdrop limit 1", $con))
	{	return true;	}
	foreach (dc_create_statements(dc_sql_file('login')) as $stmt)
	{	@mysql_query($stmt, $con);	}
	return false;
}

// Runs a whole SQL file; returns '' on success or the MySQL error text.
function dc_exec_sql_file($con, $file)
{
	$sql = @file_get_contents($file);
	if ($sql === false)
	{	return "cannot read $file";	}
	if (!($con instanceof mysqli))
	{	return "no database connection";	}
	if (!mysqli_multi_query($con, $sql))
	{	return mysqli_error($con);	}
	do
	{
		$res = mysqli_store_result($con);
		if ($res)
		{	mysqli_free_result($res);	}
	}
	while (mysqli_more_results($con) && mysqli_next_result($con));
	if (mysqli_errno($con))
	{	return mysqli_error($con);	}
	return '';
}

// Only knight* tables, only names that come from the install file.
function dc_drop_tables($con, $names)
{
	foreach ($names as $n)
	{
		if (preg_match('/^knight\w+$/', $n))
		{	@mysql_query("drop table if exists `$n`", $con);	}
	}
}

// Menu entry "Install tables" for installers (next to Server tools). $style: 0/1 = side menu rows, 2 = top bar.
function dc_install_menu_row($username, $style)
{
	if (!dc_is_installer($username))
	{	return '';	}
	$g = $GLOBALS;
	$link = "install.php?username=" . urlencode($username) . "&token=" . urlencode($g['token'] ?? '') . "&langval=" . (int) ($g['langval'] ?? 0) . "&server_id=" . (int) ($g['server_id'] ?? 0) . "&skin_id=" . (int) ($g['skin_id'] ?? 0);
	$link = htmlspecialchars($link);
	$sd = $g['skin_dir'] ?? '';
	if ($style == 2)
	{	return "<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"$link\" class=\"whitetop\">Install tables</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$sd/bronzethin.jpg');\"><img src=\"$sd/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>";	}
	$bg = ($style == 1) ? "background: url('$sd/bg_sideback.jpg') no-repeat top left;" : "background:#000000 url('$sd/bg_sideback.jpg') repeat-x top right;";
	return "</tr><tr><td class=\"menuside\" style=\"$bg\"><a href=\"$link\" class=\"igotitleft\">&nbsp;Install tables</a></td>";
}

// Line under the page header: a link to install.php for installers, a notice for everybody
// while DropCalc is not installed.
function dc_installer_banner($username)
{
	$not_inst = !empty($GLOBALS['dc_not_installed']);
	$is_inst = dc_is_installer($username);
	if (!$not_inst)
	{	return;	}
	if ($is_inst)
	{
		$link = "install.php?username=" . urlencode($username) . "&token=" . urlencode($GLOBALS['token'] ?? '') . "&langval=" . (int) ($GLOBALS['langval'] ?? 0) . "&server_id=" . (int) ($GLOBALS['server_id'] ?? 0) . "&skin_id=" . (int) ($GLOBALS['skin_id'] ?? 0);
		echo "<p class=\"dropmain\"><a href=\"" . htmlspecialchars($link) . "\" class=\"dropmain\"><strong>[ Install DropCalc tables ]</strong></a>" . ($not_inst ? " &nbsp;DropCalc is not installed on this server yet." : "") . "</p>";
	}
	else
	{	echo "<p class=\"dropmain\">DropCalc is not set up yet. An administrator has to install its tables after logging in.</p>";	}
}

} // DC_INSTALL_LIB
