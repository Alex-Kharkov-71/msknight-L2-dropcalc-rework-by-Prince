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


$head_back = "igo-head-back.jpg";
$head_middle = "igo-head-middle.jpg";	
$back_cl = "c-l.jpg";
$back_ct = "c-t.jpg";
$back_cb = "c-b.jpg";
$back_cr = "c-r.jpg";
$back_tl = "c-tl.jpg";
$back_br = "c-br.jpg";
$back_tr = "c-tr.jpg";
$back_bl = "c-bl.jpg";
		
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
<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";

/* Your titles can go here -----
echo "
<center><p class=\"heading1\">Title</p>
<p class=\"heading2\">Title</p></center>
";
*/

echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"></tr><tr><td><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\">


		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
			<td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left\">
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
{	echo "<tr><td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
			<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_language&nbsp;: </td><td class=\"menusidetext\" valign=\"right\">$language_string</td>
				   </tr></table></td>
			</tr>";
}
if (($hide_server == 0) || ($g_array_count > 1))
{	echo "<tr><td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_server&nbsp;: </td><td class=\"menusidetext\" valign=\"right\">$server_string</td>
				   </tr></table></td>
			</tr>";
}
if ((($hide_skin == 0) || ($s_array_count > 1)) && (($access_lvl >= $sec_inc_gmlevel) || ($user_change_skin)))
{	echo "<tr><td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_skin&nbsp;: </td><td class=\"menusidetext\" valign=\"right\">$skin_string</td>
				   </tr></table></td>
			</tr>";
}

// Section for logon and gameservers on/offline
if ($show_online > 0)
{
	echo "<tr><td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
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
				<td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"register.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_reg_acc</a></td>
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
				<td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"w-online.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_guest_login</a></td>
			</tr>";
}
	echo "<tr>
				<td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"faq.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_faq</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"newbie.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_newbguide</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background:url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"connecting.php?username=guest&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_connecting</a></td>
			</tr>";


echo "</table>
</td></tr></table>
</td>
<td class=\"back1b\" style=\"background: url('$skin_dir/$head_back') no-repeat;\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"650\"></td>
<td valign=\"top\" width=\"100%\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"back3\">
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"background: url('$skin_dir/$head_middle')\"><img src=\"$skin_dir/$back_tl\" alt=\"\" width=\"23\" height=\"23\" border=\"0\"></td><td style=\"background: url('$skin_dir/$back_ct')\" width=\"100%\"></td><td style=\"background: url('$skin_dir/$head_middle')\"><img src=\"$skin_dir/$back_tr\" alt=\"\" width=\"23\" height=\"23\" border=\"0\"></td></tr>
<tr height=\"700\"><td style=\"background: url('$skin_dir/$back_cl')\"></td><td class=\"back3\" width=\"100%\" height=\"100%\" valign=\"top\">
<!-- Content -->";

?>
