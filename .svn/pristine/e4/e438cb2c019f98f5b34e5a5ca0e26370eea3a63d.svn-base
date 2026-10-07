<?php
/*
Incoming Varialbe List
----------------------
$quality_level - 0 high res : 1 - low res
$username - User name
$access_lvl - Users access level
$sec_inc_gmlevel - Basic GM access level
$sec_inc_admin - Admin access level
$count_accs - Number of people online
$language_string - HTML containing dropdown for language
*/

echo "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\" title=\"style01.css\" media=\"screen\" />
<meta http-equiv=\"pragma\" content=\"no-cache\" />
<meta http-equiv=\"cache-control\" content=\"no-cache\" />";
$server = $g_name = $gameservers[$server_id][0];
echo "<title>$server - $count_accs</title>
<SCRIPT language=\"JavaScript1.2\" type=\"text/javascript\" src=\"script.js\"></SCRIPT>
</head>
<body>
<table width=\"100%\" height=\"100%\" border=\"0\" style=\"background: url('$skin_dir/flyback.jpg') repeat-y top right;\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\" colspan=\"3\">
	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>
		<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tr>";
if (($hide_language == 0) || ($l_array_count > 1))
{	echo "<td valign=\"top\"><p class=\"brasstop\">$lang_language : </p></td><td valign=\"top\">$language_string</td>";	}
else
{	echo "<td valign=\"top\"><p class=\"brasstop\">&nbsp;</p></td><td valign=\"top\">&nbsp;</td>";	}
echo "</tr></table>
	</td><td width=\"100%\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tr>";
if (($hide_server == 0) || ($g_array_count > 1))
{	echo "<td valign=\"top\"><p class=\"brasstop\">$lang_server&nbsp;: </p></td><td valign=\"top\">$server_string</td>";	}
else
{	echo "<td valign=\"top\"><p class=\"brasstop\">&nbsp;</p></td><td valign=\"top\">&nbsp;</td>";	}
echo "<td width=\"100%\"><h2 class=\"brasstop\">Michelle's L2J Dropcalc</h2>
		</td>";
if ((($hide_skin == 0) || ($s_array_count > 1)) && (($access_lvl >= $sec_inc_gmlevel) || ($user_change_skin)))
{	echo "<td valign=\"top\"><p class=\"brasstop\">$lang_skin&nbsp;: </p></td><td valign=\"top\">$skin_string</td>";	}
else
{	echo "<td valign=\"top\"><p class=\"brasstop\">&nbsp;</p></td><td valign=\"top\">&nbsp;</td>";	}
echo "</tr></table>
	</td></tr><tr><td>
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/but1-off.jpg\"width=\"74\" height=\"56\" border=\"0\"></td></tr>
			<tr><td><img src=\"$skin_dir/but2-off.jpg\" width=\"74\" height=\"58\" border=\"0\"></td></tr></table>
		</td><td>
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/but5-off.jpg\"width=\"106\" height=\"30\" border=\"0\"></td>
				<td><img src=\"$skin_dir/but6-dummy.jpg\" alt=\"\" width=\"100\" height=\"30\" border=\"0\"></td></tr>
			<tr><td colspan=\"2\" style=\"background: url('$skin_dir/but-cback.jpg');\">
				<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"52\" border=\"0\"></td>
				</tr></table></td></tr>
			<tr><td><img src=\"$skin_dir/but7-off.jpg\"width=\"106\" height=\"32\" border=\"0\"></td>
				<td><img src=\"$skin_dir/but8-off.jpg\"width=\"100\" height=\"32\" border=\"0\"></td></tr></table>
		</td><td>
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/but3-off.jpg\"width=\"74\" height=\"56\" border=\"0\"></td></tr>
			<tr><td><img src=\"$skin_dir/but4-off.jpg\"width=\"74\" height=\"58\" border=\"0\"></td></tr></table>
		</td></tr></table>
		</form>
	</td><td>
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td valign=\"bottom\">
			<img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"45\" border=\"0\"></td><td>
			<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tr>
			<form method=\"post\" name=\"logon\" action=\"index.php\"><input name=\"langval\" value=\"$langval\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"><td valign=\"center\"><p class=\"brasstop\">$lang_usern:</p></td>
			<td valign=\"center\"><p class=\"brasstop\"><input name=\"username\" maxlength=\"50\" size=\"12\" type=\"text\">&nbsp;&nbsp;&nbsp;</p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			<td valign=\"center\"><p class=\"brasstop\">&nbsp;&nbsp;&nbsp;$lang_passwd:</p></td>
			<td valign=\"center\"><p class=\"brasstop\"><input name=\"password\" maxlength=\"50\" size=\"12\" type=\"password\">&nbsp;&nbsp;&nbsp;</p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			<td valign=\"center\"><p class=\"brasstop\">&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\" $lang_signin \"></p></td>
			</tr></table></form>
			
		</td></tr><tr><td style=\"background: url('$skin_dir/bronzethin.jpg');\" colspan=\"2\">
			<center><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"25\" border=\"0\"></td><td><p class=\"whitetop\">$lang_online : $count_accs</p></td></tr></table></center>
		</td></tr><tr><td valign=\"top\">
			<img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"44\" border=\"0\"></td><td>
			<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tr>";
			if ($register_allow)
			{	echo "<td valign=\"center\"><p class=\"brasstop\"><a href=\"register.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_reg_acc</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>";	}
			if ($allowpassreset)
			{	echo "<td valign=\"center\"><p class=\"brasstop\"><a href=\"registerreset.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">Reset Password</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>";	}
			if ($guest_allow)
			{	echo "<td valign=\"center\"><p class=\"brasstop\"><a href=\"w-online.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_guest_login</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>";	}
			echo "<td valign=\"center\"><p class=\"brasstop\"><a href=\"faq.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_faq</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			<td valign=\"center\"><p class=\"brasstop\"><a href=\"newbie.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_newbguide</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			<td valign=\"center\"><p class=\"brasstop\"><a href=\"connecting.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_connecting</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			</tr></table>
			
		</td></tr></table>
	</td></tr></table>
	
</td></tr>";
// Section for logon and gameservers on/offline
if ($show_online > 0)
{
	echo "<tr><td width=\"100%\" colspan=\"3\"><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"8\" border=\"0\"></td></tr>
			<tr><td width=\"100%\" colspan=\"3\" style=\"background: url('$skin_dir/bronzethin.jpg');\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"25\" border=\"0\"></td>";
	echo "<td><p class=\"whitetop\">&nbsp;Login&nbsp;Server&nbsp;-</p></td><td>&nbsp;".(checkport($log_telnet_host, $dblog_port, $log_telnet_timeout) ? "<img src=\"" . $images_dir . "online.gif\">" : "<img src=\"" . $images_dir . "offline.gif\">")."&nbsp;</td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>\n";
	$i = 0;
	while ($i < $g_array_count)
	{
		$g_name = $gameservers[$i][0];
		$g_ip = $gameservers[$i][9];
		$g_port = $gameservers[$i][7];
		echo "<td><p class=\"whitetop\">&nbsp;$g_name&nbsp;-</p></td><td>&nbsp;".(checkport($g_ip, $g_port, $log_telnet_timeout) ? "<img src=\"" . $images_dir . "online.gif\">" : "<img src=\"" . $images_dir . "offline.gif\">")."&nbsp;</td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>\n";
		$i++;
	}
	echo "<td width=\"100%\"></td></tr></table></td></tr>\n";
}
echo "<tr>
<td><img src=\"$skin_dir/blank.gif\" height=\"1\" width=\"20\"></td><td valign=\"top\" height=\"100%\" width=\"100%\">

<!-- Content -->";
?>
