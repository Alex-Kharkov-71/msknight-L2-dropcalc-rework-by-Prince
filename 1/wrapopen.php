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
<head>";
if ($refresh_timer)
{	echo "<META content=\"$refresh_timer;url=$refresh_string\" http-equiv=refresh >";	}
echo "

<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
$side_1 = "t2.gif";
$side_2 = "t3.gif";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\" title=\"style.css\" media=\"screen\" />";
if (!$quality_level)
{	$keyboard_center = "keybd-center.jpg";
	$keyboard_top = "keybd-top.jpg";
	$keyboard_left = "keybd-left.jpg";
	$keyboard_corner = "keybd-corner.jpg";
}
else
{	$keyboard_center = "l-keybd-center.jpg";
	$keyboard_top = "l-keybd-top.jpg";
	$keyboard_left = "l-keybd-left.jpg";
	$keyboard_corner = "l-keybd-corner.jpg";	
}
if ($username != "guest")
{
	$sql = "select accessLevel from $dblog_l2jdb.accounts where login = '$username'";
	$result = mysql_query($sql,$con2);
	if ($result)
	{	$access_lvl = mysql_result($result,0,"accessLevel");	}
}
$server = $g_name = $gameservers[$server_id][0];
echo "<title>$server - $count_accs</title>
<SCRIPT language=\"JavaScript1.2\" type=\"text/javascript\" src=\"script.js\"></SCRIPT>
</head>
<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>
<td colspan=\"3\" class=\"topback\">
<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"left\"><img src=\"$skin_dir/$side_2\" alt=\"\" width=\"211\" height=\"185\" border=\"0\"></td>
<td style=\"background:url('$skin_dir/t1.gif') center no-repeat;\" valign=\"center\"><h2 class=\"topbar\">Michelle's&nbsp;L2J&nbsp;Dropcalc</h2><h3 class=\"topbar\">Generic&nbsp;server&nbsp;side&nbsp;PHP&nbsp;for&nbsp;the&nbsp;Lineage&nbsp;2&nbsp;Java&nbsp;game&nbsp;system</h3></td>
<td align=\"right\"><img src=\"$skin_dir/$side_1\" alt=\"\" width=\"211\" height=\"185\" border=\"0\"></td></tr>
</table>
</td></tr><tr>
<td colspan=\"3\" style=\"background:url('$skin_dir/top-bar.jpg');\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"9\" class=\"back1\"></td></tr><tr>
<td valign=\"top\"><table width=\"200\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td  class=\"back1\"></td></tr><tr><td>
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"180\">
		<tr>
			<td><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"15\" alt=\"Menu Sword\" title=\"Menu Sword\" /></td>
			</tr>
			<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">&nbsp;$lang_welcome $u_name</td>
			</tr>
			<tr><td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_online : $count_accs</td>
				   <td valign=\"right\" width=\"100%\" class=\"menusidetext\">
				<a href=\"index.php?logoffname=$username&action=logoff&langval=$langval&server_id=$server_id&skin_id=$skin_id\">Logout&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>	
				</td></tr></table></td>
			</tr>";
if (($hide_language == 0) || ($l_array_count > 1))
{	echo "<tr><td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
			<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_language&nbsp;: </td><td class=\"menusidetext\" valign=\"right\">$language_string</td>
				   </tr></table></td>
			</tr>";
}
if ((($hide_server == 0) || ($g_array_count > 1)) && (($access_lvl >= $sec_inc_gmlevel) || ($user_change_server)))
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
if ($show_online > 1)
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
echo "<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"5\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			
			<tr>
				<td><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"16\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			<tr>
			<td><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"15\" alt=\"Menu Sword\" title=\"Menu Sword\" /></td>
			</tr>";
if (($menushowchars) && (strlen($chars_list) > 0))
{	echo "<tr><td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">" . $chars_list . "</td></tr>";	}
echo "			<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_items</td>
				   <td>
					<form action=\"i-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_chars</td>
				   <td>
					<form action=\"c-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_clans</td>
				   <td>
					<form action=\"cl-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_mobs</td>
				   <td>
					<form action=\"m-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_account</td>
				   <td>
					<form action=\"a-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_locations</td>
				   <td>
					<form action=\"l-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_recipes</td>
				   <td>
					<form action=\"r-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>				
			</tr><tr>";
if ($access_lvl >= $sec_inc_gmlevel)
{		echo "			<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;$lang_skills</td>
				   <td>
					<form action=\"s-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>				
			</tr><tr><td class=\"menuside\" style=\"background:#000000 url('$skin_dir/bg_sideback.jpg') repeat-x top right;\">
				<table class=\"link2b\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
				<tr><td valign=\"left\" width=\"100%\" class=\"menusidetext\">&nbsp;E-mail</td>
				   <td>
					<form action=\"a-search.php\">
					<input name=\"username\" type=\"hidden\" value=\"$username\">
					<input name=\"token\" type=\"hidden\" value=\"$token\">
					<input name=\"langval\" value=\"$langval\" type=\"hidden\">
					<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
					<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
					<input name=\"esearch\" value=\"1\" type=\"hidden\">
					<table align=\"right\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"igotab2back2\">
					<tr>
						<td align=\"right\" valign=\"center\" class=\"noborderback\">
						<input name=\"itemname\" maxlength=\"50\" size=\"8\" type=\"text\" class=\"field2\">
						</td>
						<td valign=\"center\" width=\"20\" class=\"noborderback\">
						<input src=\"$skin_dir/select.gif\" onclick=\"submit\" height=\"20\" type=\"image\" width=\"23\" class=\"field3\">
	    					</td>
						</tr></table>    
				</form></td></tr></table>
				</td>				
			</tr><tr>";			
}
echo "				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"5\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			<tr>
				<td ><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"16\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr><tr>
			<td><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"15\" alt=\"Menu Sword\" title=\"Menu Sword\" /></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"w-online.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" >&nbsp;$lang_whosonline </a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"m-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=99999&detshow=2\">&nbsp;$lang_mobsbylvl</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" >&nbsp;$lang_itemsbytype</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;Status Pages...</a></td>
			</tr>";
if ($username != "guest")
{
	echo "<tr>
			<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"javascript:popit('cpasswd.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','470','300');\">&nbsp;$lang_changep</a></td>
			</tr>";

	if ($result)
	{
		if ($access_lvl >= $sec_inc_gmlevel)
		{	echo "<tr>
					<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"reference2.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_gmref</a></td>
				</tr>";
		}
	}
}
echo "			<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"5\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
			<tr>
				<td ><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"16\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>
";
	
if ($username != "guest")
{

	if ($result)
	{
		if ($access_lvl >= $sec_inc_gmlevel)
		{	echo "<tr>
			<td ><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"15\" alt=\"Menu Sword\" title=\"Menu Sword\" /></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"servertools.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_servertools</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"sconsole.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"igotitleft\">&nbsp;$lang_serverconsole</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"statistics.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=\">&nbsp;$lang_serverstats</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"clog.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"igotitleft\">&nbsp;$lang_chatlog</a></td>
			</tr>";
			if ($access_lvl >= $adjust_shop)
			{	echo "<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"shops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_shops</a></td>
			</tr>";
			}
			echo "<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"pets.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_pets </a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"announcements.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"igotitleft\">&nbsp;$lang_announcements</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"lconsole.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"igotitleft\">&nbsp;$lang_loginc</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"lconsole2.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"igotitleft\">&nbsp;$lang_loginevent</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"ilog.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"igotitleft\">&nbsp;$lang_itemlog</a></td>
			</tr><tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"gmlog.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"igotitleft\">&nbsp;$lang_gmaudit</a></td>
			</tr>";
			if ($access_lvl >= $sec_inc_admin)
			{
				echo "<tr>
					<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><a href=\"settings.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"igotitleft\">&nbsp;$lang_settings</a></td>
					</tr>";
			}
			echo "<tr>
				<td class=\"menuside\" style=\"background: url('$skin_dir/bg_sideback.jpg') no-repeat top left;\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"5\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr><tr>
				<td ><img src=\"$skin_dir/blank.gif\" width=\"180\" height=\"16\" alt=\"Menu Sword\" title=\"Menu Sword\" /></form></td>
			</tr>";	

		}
	}
} 
echo "		</table>
</td></tr></table>
</td>
<td class=\"back1b\"><img src=\"$skin_dir/blank.gif\" width=\"1\" height=\"650\"></td>
<td valign=\"top\" width=\"100%\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td colspan=\"2\" class=\"back3\">
<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/$keyboard_corner\" alt=\"\" width=\"58\" height=\"58\" border=\"0\"></td><td style=\"background:url('$skin_dir/$keyboard_top');\" width=\"100%\"></td><td class=\"back2\"><img src=\"$skin_dir/$keyboard_corner\" alt=\"\" width=\"58\" height=\"58\" border=\"0\"></td></tr>
<tr height=\"950\"><td style=\"background:url('$skin_dir/$keyboard_left');\"></td><td style=\"background:url('$skin_dir/$keyboard_center') repeat-y top center;\" width=\"100%\" height=\"100%\" valign=\"top\">
<!-- Content -->";

?>
