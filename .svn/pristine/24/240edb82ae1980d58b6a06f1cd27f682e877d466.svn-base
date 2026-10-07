<?php

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
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>
<td colspan=\"3\" class=\"topback\">
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"left\"><img src=\"$skin_dir/t3.gif\" alt=\"\" width=\"211\" height=\"185\" border=\"0\"></td>
<td style=\"background:url('$skin_dir/t1.gif') center no-repeat;\" valign=\"center\"><h2 class=\"topbar\">Michelle's&nbsp;L2J&nbsp;Dropcalc</h2><h3 class=\"topbar\">Generic&nbsp;server&nbsp;side&nbsp;PHP&nbsp;for&nbsp;the&nbsp;Lineage&nbsp;2&nbsp;Java&nbsp;game&nbsp;system</h3></td>
<td align=\"right\"><img src=\"$skin_dir/t2.gif\" alt=\"\" width=\"211\" height=\"185\" border=\"0\"></td></tr>
</table>
</td></tr><tr>
<td colspan=\"3\" style=\"background:url('$skin_dir/top-bar.jpg');\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"9\" class=\"back1\"></td></tr><tr>
<td valign=\"top\"><table width=\"200\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td  class=\"back1\"></td></tr><tr><td>

		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
			<td class=\"menu_top\"><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"15\" alt=\"Menu Sword\" title=\"Menu Sword\" /></td>
		</tr>
		<tr>
			<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
			<table>
			<tr>
				<td class=\"menusidetext\">&nbsp;$lang_usern:</td>
				<td><form method=\"post\" name=\"login\" action=\"index.php\"><input name=\"username\" maxlength=\"50\" size=\"12\" type=\"text\"><input name=\"langval\" value=\"$langval\" type=\"hidden\"><input name=\"server_id\" value=\"$server_id\" type=\"hidden\"><input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"></td>
			</tr>
			<tr>
				<td class=\"menusidetext\">&nbsp;$lang_passwd:</td>
				<td><input name=\"password\" maxlength=\"50\" size=\"12\" type=\"password\"></td>
			</tr>
			<tr>
				<td>&nbsp</td>
				<td><input type=\"submit\" value=\" Sign In \"></a></td></form>
			</tr>
			</table>
			</td>
		</tr>";
if (($hide_language == 0) || ($l_array_count > 1))
{	echo "<tr><td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
			<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_language&nbsp;: </td><td class=\"menusidetext\" valign=\"right\">$language_string</td>
				   </tr></table></td>
			</tr>";
}
if (($hide_server == 0) || ($g_array_count > 1))
{	echo "<tr><td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_server&nbsp;: </td><td class=\"menusidetext\" valign=\"right\">$server_string</td>
				   </tr></table></td>
			</tr>";
}
if ((($hide_skin == 0) || ($s_array_count > 1)) && (($access_lvl >= $sec_inc_gmlevel) || ($user_change_skin)))
{	echo "<tr><td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_skin&nbsp;: </td><td class=\"menusidetext\" valign=\"right\">$skin_string</td>
				   </tr></table></td>
			</tr>";
}

/// Section for logon and gameservers on/offline
if ($show_online > 0)
{
	echo "<tr><td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";
	echo "<tr><td class=\"menusidetext\" width=\"100%\">&nbsp;Login Server&nbsp;-&nbsp;</td><td>".(checkport($log_telnet_host, $dblog_port, $log_telnet_timeout) ? "<img src=\"" . $images_dir . "online.gif\">" : "<img src=\"" . $images_dir . "offline.gif\">")."</td></tr>\n";
	$i = 0;
	while ($i < $g_array_count)
	{
		$g_name = $gameservers[$i][0];
		$g_ip = $gameservers[$i][9];
		$g_port = $gameservers[$i][7];
		echo "<tr><td class=\"menusidetext\" width=\"100%\">&nbsp;$g_name&nbsp;-&nbsp;</td><td>".(checkport($g_ip, $g_port, $log_telnet_timeout) ? "<img src=\"" . $images_dir . "online.gif\">" : "<img src=\"" . $images_dir . "offline.gif\">")."</td></tr>\n";
		$i++;
	}
	echo "</table></td></tr>\n";
}

if ($register_allow)
	{
		echo "		<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"register.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_reg_acc</a></td>
			</tr>";
	}
if ($allowpassreset)
	{
		echo "		<tr>
				<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\"><a href=\"registerreset.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;Reset Password</a></td>
			</tr>";
	}
if ($guest_allow)
{
	echo "<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"w-online.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_guest_login</a></td>
			</tr>";
}

	echo "<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"faq.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_faq</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"newbie.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_newbguide</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"connecting.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_connecting</a></td>
			</tr>";


echo "			<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"5\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			<tr>
				<td class=\"menu_bot\"><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"16\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			</table>
</td></tr></table>
</td>
<td class=\"back1b\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"650\"></td>
<td valign=\"top\" width=\"100%\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td colspan=\"2\" class=\"back3\">
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/keybd-corner.jpg\" alt=\"\" width=\"58\" height=\"58\" border=\"0\"></td><td style=\"background:url('$skin_dir/keybd-top.jpg');\" width=\"100%\"></td><td><img src=\"$skin_dir/keybd-corner.jpg\" alt=\"\" width=\"58\" height=\"58\" border=\"0\"></td></td></tr>
<tr height=\"950\"><td style=\"background:url('$skin_dir/keybd-left.jpg');\"></td><td class=\"back3\" width=\"100%\" height=\"100%\" valign=\"top\">
<!-- Content -->";
?>
