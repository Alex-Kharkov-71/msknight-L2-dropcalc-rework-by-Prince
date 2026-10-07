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
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$skin_dir/style.css\" title=\"$skin_dir/style.css\" media=\"screen\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
if (!$quality_level)
{	$flyback = "flyback.jpg";	}
else
{	$flyback = "l-flyback.jpg";	}

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
<SCRIPT LANGUAGE=\"JavaScript\">
function submitForm(){
	if(document.pressed == 'items')
	{
		document.searchform.action = \"i-search.php\";
	}
	if(document.pressed == 'location')
	{
		document.searchform.action = \"l-search.php\";
	}
	if(document.pressed == 'mobs')
	{
		document.searchform.action = \"m-search.php\";
	}
	if(document.pressed == 'chars')
	{
		document.searchform.action = \"c-search.php\";
	}
	if(document.pressed == 'clans')
	{
		document.searchform.action = \"cl-search.php\";
	}
	if(document.pressed == 'recipe')
	{
		document.searchform.action = \"r-search.php\";
	}
	if(document.pressed == 'account')
	{
		document.searchform.action = \"a-search.php\";
	}
	if(document.pressed == 'skill')
	{
		document.searchform.action = \"s-search.php\";
	}
  document.searchform.submit();
 return true;
}
</SCRIPT>
</head>
<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
<table width=\"100%\" height=\"100%\" border=\"0\" style=\"background: url('$skin_dir/$flyback') repeat-y top right;\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\" colspan=\"3\">
	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>
		<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tr>";
if (($hide_language == 0) || ($l_array_count > 1))
{	echo "<td valign=\"top\"><p class=\"brasstop\">$lang_language : </p></td><td valign=\"top\">$language_string</td>";	}
else
{	echo "<td valign=\"top\"><p class=\"brasstop\">&nbsp;</p></td><td valign=\"top\">&nbsp;</td>";	}
echo "</tr></table>
	</td><td width=\"100%\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tr>";
if ((($hide_server == 0) || ($g_array_count > 1)) && (($access_lvl >= $sec_inc_gmlevel) || ($user_change_server)))
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
		<form name=\"searchform\" onSubmit=\"return submitForm();\" action=\"\">
		<input name=\"username\" type=\"hidden\" value=\"$username\">
		<input name=\"token\" type=\"hidden\" value=\"$token\">
		<input name=\"langval\" value=\"$langval\" type=\"hidden\">
		<input name=\"server_id\" value=\"$server_id\" type=\"hidden\">
		<input name=\"skin_id\" value=\"$skin_id\" type=\"hidden\">
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><input onClick=\"document.pressed=this.value\" type=\"image\" value=\"chars\" src=\"$skin_dir/but1-off.jpg\"width=\"74\" height=\"56\" border=\"0\" onmouseover=\"this.src='$skin_dir/but1-on.jpg'\" onmouseout=\"this.src='$skin_dir/but1-off.jpg'\"></td></tr>
			<tr><td><input onClick=\"document.pressed=this.value\" type=\"image\" value=\"clans\" src=\"$skin_dir/but2-off.jpg\" width=\"74\" height=\"58\" border=\"0\" onmouseover=\"this.src='$skin_dir/but2-on.jpg'\" onmouseout=\"this.src='$skin_dir/but2-off.jpg'\"></td></tr></table>
		</td><td>
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><input onClick=\"document.pressed=this.value\" type=\"image\" value=\"account\" src=\"$skin_dir/but5-off.jpg\"width=\"106\" height=\"30\" border=\"0\" onmouseover=\"this.src='$skin_dir/but5-on.jpg'\" onmouseout=\"this.src='$skin_dir/but5-off.jpg'\"></td>
				<td>";
				if ($access_lvl >= $sec_inc_gmlevel)
				{	echo "<input onClick=\"document.pressed=this.value\" type=\"image\" value=\"skill\" src=\"$skin_dir/but6-off.jpg\"width=\"100\" height=\"30\" border=\"0\" onmouseover=\"this.src='$skin_dir/but6-on.jpg'\" onmouseout=\"this.src='$skin_dir/but6-off.jpg'\">";	}
				else
				{	echo "<img src=\"$skin_dir/but6-dummy.jpg\" alt=\"\" width=\"100\" height=\"30\" border=\"0\">";	}
				echo "</td></tr>
			<tr><td colspan=\"2\" style=\"background: url('$skin_dir/but-cback.jpg');\">
				<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"52\" border=\"0\"></td>
				<td width=\"100%\"><center><input name=\"itemname\" maxlength=\"50\" size=\"20\" type=\"text\"></td></center>
				</tr></table></td></tr>
			<tr><td><input onClick=\"document.pressed=this.value\" type=\"image\" value=\"location\" src=\"$skin_dir/but7-off.jpg\"width=\"106\" height=\"32\" border=\"0\" onmouseover=\"this.src='$skin_dir/but7-on.jpg'\" onmouseout=\"this.src='$skin_dir/but7-off.jpg'\"></td>
				<td><input onClick=\"document.pressed=this.value\" type=\"image\" value=\"recipe\" src=\"$skin_dir/but8-off.jpg\"width=\"100\" height=\"32\" border=\"0\" onmouseover=\"this.src='$skin_dir/but8-on.jpg'\" onmouseout=\"this.src='$skin_dir/but8-off.jpg'\"></td></tr></table>
		</td><td>
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><input onClick=\"document.pressed=this.value\" type=\"image\" value=\"items\" src=\"$skin_dir/but3-off.jpg\"width=\"74\" height=\"56\" border=\"0\" onmouseover=\"this.src='$skin_dir/but3-on.jpg'\" onmouseout=\"this.src='$skin_dir/but3-off.jpg'\"></td></tr>
			<tr><td><input onClick=\"document.pressed=this.value\" type=\"image\" value=\"mobs\" src=\"$skin_dir/but4-off.jpg\"width=\"74\" height=\"58\" border=\"0\" onmouseover=\"this.src='$skin_dir/but4-on.jpg'\" onmouseout=\"this.src='$skin_dir/but4-off.jpg'\"></td></tr></table>
		</td></tr></table>
		</form>
	</td><td>
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td valign=\"bottom\">
			<img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"45\" border=\"0\"></td><td>
			<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tr>
			<td valign=\"center\"><p class=\"brasstop\"><a href=\"w-online.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_whosonline</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			<td valign=\"center\"><p class=\"brasstop\"><a href=\"m-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id&detreq=99999&detshow=2\" class=\"brasstop\">$lang_mobsbylvl</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			<td valign=\"center\"><p class=\"brasstop\"><a href=\"it-search.php?itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&itemid=$item_id\" class=\"brasstop\">$lang_itemsbytype</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>
			<td valign=\"center\"><p class=\"brasstop\"><a href=\"index.php?logoffname=$username&action=logoff&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_logout</a></p></td>
			</tr></table>
			
		</td></tr><tr><td style=\"background: url('$skin_dir/bronzethin.jpg');\" colspan=\"2\">
			<center><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"25\" border=\"0\"></td><td><p class=\"whitetop\">$lang_welcome $u_name &nbsp;-&nbsp;$lang_online : $count_accs</p></td></tr></table></center>
		</td></tr><tr><td valign=\"top\">
			<img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"44\" border=\"0\"></td><td>
			<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tr>
			<td valign=\"center\"><p class=\"brasstop\"><a href=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">Status Pages...</a></p></td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>";
			if ($username != "guest")
			{
				echo "<td valign=\"center\"><p class=\"brasstop\"><a href=\"javascript:popit('cpasswd.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id','470','300');\" class=\"brasstop\">$lang_changep</a></p></td>";
				if ($result)
				{
					if ($access_lvl >= $sec_inc_gmlevel)
					{	echo "<td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td><td valign=\"center\"><p class=\"brasstop\"><a href=\"reference2.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"brasstop\">$lang_gmref</a></p></td>";	}
				}
			}
			echo "</tr></table>
			
		</td></tr></table>
	</td></tr></table>
	
</td></tr>";

// Section for logon and gameservers on/offline
if ($show_online > 1)
{
	echo "<tr><td width=\"100%\" colspan=\"3\"><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"8\" border=\"0\"></td></tr>
			<tr><td width=\"100%\" colspan=\"3\" style=\"background: url('$skin_dir/bronzethin.jpg');\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"25\" border=\"0\"></td>";
	echo "<td><p class=\"whitetop\">&nbsp;Login&nbsp;Server&nbsp;-&nbsp;</p></td><td>".(checkport($log_telnet_host, $dblog_port, $log_telnet_timeout) ? "<img src=\"" . $images_dir . "online.gif\">" : "<img src=\"" . $images_dir . "offline.gif\">")."&nbsp;</td><td><img src=\"$skin_dir/brassbar.gif\" alt=\"\" width=\"6\" height=\"24\" border=\"0\"></td>\n";
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
// Section to show the accounts characters and provide direct links to them.
if (($menushowchars) && (strlen($chars_list) > 0))
{	$chars_list =  preg_replace('/<a /','<a class="whitetop" ',$chars_list);
	echo "\n<tr><td width=\"100%\" colspan=\"3\"><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"8\" border=\"0\"></td></tr>
			<tr><td width=\"100%\" colspan=\"3\" style=\"background: url('$skin_dir/bronzethin.jpg');\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"25\" border=\"0\"></td><td><p class=\"whitetop\">" . $chars_list . "</p></td></tr></table></td></tr>\n";	}
if ($username != "guest")
{
	if ($result)
	{
		if ($access_lvl >= $sec_inc_gmlevel)
		{	echo "<tr><td width=\"100%\" colspan=\"3\"><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"8\" border=\"0\"></td></tr>
			<tr><td width=\"100%\" colspan=\"3\" style=\"background: url('$skin_dir/bronzethin.jpg');\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"25\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"servertools.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"whitetop\">$lang_servertools</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"sconsole.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"whitetop\">$lang_serverconsole</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"statistics.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&action=\" class=\"whitetop\">$lang_serverstats</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"clog.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"whitetop\">$lang_chatlog</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>";
			if ($access_lvl >= $adjust_shop)
			{	echo "<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"shops.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"whitetop\">$lang_shops</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>";	}
			echo "<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"pets.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"whitetop\">$lang_pets</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>";
			echo "<td width=\"100%\"></td></tr></table></td></tr>";
			echo "<tr><td width=\"100%\" colspan=\"3\"><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"8\" border=\"0\"></td></tr>";
			echo "<tr><td width=\"100%\" colspan=\"3\" style=\"background: url('$skin_dir/bronzethin.jpg');\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><img src=\"$skin_dir/blank.gif\" alt=\"\" width=\"1\" height=\"25\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"announcements.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\" class=\"whitetop\">$lang_announcements</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"lconsole.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"whitetop\">$lang_loginc</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>
			
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"lconsole2.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"whitetop\">$lang_loginevent</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"ilog.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"whitetop\">$lang_itemlog</a>&nbsp;&nbsp;&nbsp;</p></td><td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td>
			<td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"gmlog.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"whitetop\">$lang_gmaudit</a>&nbsp;&nbsp;&nbsp;</p></td>";
			if ($access_lvl >= $sec_inc_admin)
			{	echo "<td style=\"background: url('$skin_dir/bronzethin.jpg');\"><img src=\"$skin_dir/whitebar.jpg\" alt=\"\" width=\"8\" height=\"20\" border=\"0\"></td><td><p class=\"whitetop\">&nbsp;&nbsp;&nbsp;<a href=\"settings.php?$itemname=$item_id&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id&lastlines=1#bottomline\" class=\"whitetop\">$lang_settings</a>&nbsp;&nbsp;&nbsp;</p></td>";	}
			echo "<td width=\"100%\"></td></tr></table></td></tr>";
		}
	}
}
echo "<tr>
<td><img src=\"$skin_dir/blank.gif\" height=\"1\" width=\"20\"></td><td valign=\"top\" height=\"100%\" width=\"100%\">";

echo "<!-- Content -->";
?>
