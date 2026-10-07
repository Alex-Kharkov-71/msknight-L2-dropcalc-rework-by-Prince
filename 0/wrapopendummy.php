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

echo "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\" title=\"style01.css\" media=\"screen\" />
<meta http-equiv=\"pragma\" content=\"no-cache\" />
<meta http-equiv=\"cache-control\" content=\"no-cache\" />";
$server = $g_name = $gameservers[$server_id][0];
echo "<title>$server - $count_accs</title>

<SCRIPT language=\"JavaScript1.2\" type=\"text/javascript\" src=\"script.js\"></SCRIPT>
</head>
<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" style=\"background:#000000 url('$skin_dir/bg_body.jpg') repeat-x top left;\">
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:url('$skin_dir/bg_wrapper.jpg') no-repeat top left;\"><tr><td><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: url('$skin_dir/bg_main.gif') no-repeat top right;\"><tr>
<td valign=\"top\"><table width=\"220\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"160\"></td></tr><tr><td>

		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
			<td class=\"menu_top\"><img src=\"$skin_dir/menu_sword_top.png\" width=\"180\" height=\"15\" alt=\"Menu Sword\" title=\"Menu Sword\" /></td>
		</tr>
		<tr>
			<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">
			<table>
			<tr>
				<td class=\"menusidetext\">&nbsp;$lang_usern:</td>
				<td><form method=\"post\" name=\"logon\" action=\"index.php\"><input name=\"username\" maxlength=\"50\" size=\"12\" type=\"text\"><input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\"></td>
			</tr>
			<tr>
				<td class=\"menusidetext\">&nbsp;$lang_passwd:</td>
				<td><input name=\"password\" maxlength=\"50\" size=\"12\" type=\"password\"></td>
			</tr>
			<tr>
				<td>&nbsp</td>
				<td><input type=\"submit\" value=\" $lang_signin \"></a></td></form>
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

// Section for logon and gameservers on/offline
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
				<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\"><a href=\"register.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_reg_acc</a></td>
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
				<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\"><a href=\"w-online.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_guest_login</a></td>
			</tr>";
}
	echo "<tr>
				<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\"><a href=\"faq.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_faq</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\"><a href=\"newbie.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_newbguide</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\"><a href=\"connecting.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_connecting</a></td>
			</tr>";


echo "			<tr>
				<td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"5\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			<tr>
				<td class=\"menu_bot\"><img src=\"$skin_dir/menu_sword_bot.png\" width=\"180\" height=\"16\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			</table>
</td></tr></table>
</td>
<td><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"650\"></td>
<td valign=\"top\" width=\"100%\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"100\"></td><td><p class=\"heading1\">Michelle's L2J Dropcalc</p><p class=\"heading2\">Generic server side PHP for the Lineage 2 Java game system</p></td></tr><tr><td colspan=\"2\">

<!-- Content -->";

?>
