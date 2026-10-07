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

$langval = input_check($_REQUEST['langval'],2);

// Retrieve environment variables
wrap_start_dummy($username, $token, $_GET, $_POST, $langval, $default_lang, $language_array, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $guest_allow, $all_users_maps, $all_users_recipe, $register_allow);

// Print top of page
echo "
			<p class=\"dropmain\">&nbsp;</p>
			<center>
	<table width=\"80%\" cellpadding=\"20\" class=\"dropmain\">
	<tr>
	<td class=\"dropmain\">
			<p class=\"dropmain\">&nbsp;</p>
			<h2 class=\"dropmainblack\">Connecting to L2J servers.</h2>
			<p class=\"dropmain\">&nbsp;</p>
			<p class=\"left\">Connecting to an L2J server is quite simple.  It merely requires you to make a change to a file on your computer.</p>
	<p class=\"left\"><img src=\"" . $images_dir . "hostsloc.jpg\" alt=\"\" align=\"left\" border=\"0\" height=\"274\" width=\"173\">Open your \"My Computer\" and open the drive that contains your operating system (usually C:) and then look for where your operating system lives (usually a folder called WINNT or WINDOWS)  Open this folder.  You might get a warning about viewing system files.  Accept this warning and continue.</p>
	<p class=\"left\">Now you are looking for a folder called SYSTEM32.  In here is another folder called DRIVERS.  Open this and then look for, and open (last one, I promise!) a folder called ETC.</p>
	<p class=\"left\">In the ETC folder is a file called HOSTS.  It isn't assigned to anything, so you are going to have to open it with Notepad.  You can right click on it and select \"Open With\".  If Notepad is listed, then select it, otherwise select \"Choose Program..\" and then it will open a list that contains notepad.  Select Notepad from the list and click O.K.</p>
	<p class=\"left\">If all is well and good, you are now looking at the Hosts file of your PC.</p>
	<p class=\"left\">It works like this ...</p>
	<p class=\"left\">When you type www.google.com in to your browser, it has to find out the IP address of the web site before it can talk with it.  This is usually done behind the scenes and you don't see this happening.  It is sort of like a big yellow pages looking up the phone number of someone you want to talk to.</p>
<p class=\"left\">However, the hosts file is a way of telling the system the IP addresses of machines on the Internet that it doesn't know about.</p>
<p class=\"left\">What we are going to do here, is put an entry in this file, which gives the computer a different phone number to dial (IP address) for the Lineage 2 servers.  Instead of your client talking with the Lineage 2 servers, it will talk with the L2J server instead.  Clever, eh?</p>
<p class=\"left\">The computer normally reads this list all the time, so once you have made the change and saved the file, it should take immediate effect.  You shouldn't have to reboot. </p>
<p class=\"left\">At the bottom of the file, you need to put the line ...</p>
<pre class=\"dropmaintable\">999.999.999.999		l2authd.lineage2.com </pre>

<p class=\"left\">... where 999.999.999.999 is the IP address of the L2J server you want to play on.  Note that there can not be any spaces or tabs, etc. at the begining of the line.  It must be the IP address.</p>
<p class=\"left\">Now, with the file saved and closed, you can start your client and play the game!  Obviously, you need a game account on their server, and if they are running this dropcalc, then simply use the register option on the logon menu.</p>
<p class=\"left\">If you want to change the server you are playing on, then simply change the IP address in the hosts file.  You can \"remark\", (rem out) a line you don't want the computer to listen to, by putting a # in front of the line.  Thus, when you want to change servers, you just change the lines that it listens to, and save the hosts file again.
<pre class=\"dropmaintable\">#999.999.999.999		l2authd.lineage2.com 
888.888.888.888			l2authd.lineage2.com 
#777.777.777.777		l2authd.lineage2.com </pre>
<p class=\"left\">There can be connection problems with some servers, as your client has to match the server.</p>
<p class=\"left\">Some sites get around this by issuing a \"patch\" that puts different versions of files on your machine, and it also hard-codes the IP address in the client, so you don't have to mess around with the hosts file.  This has a benefit in that it saves you hassle, but the downside is that you need a separate copy of the game for each server you want to join, and that means a good few gig of hard disk space for each instance.</p>
<p class=\"left\">Certainly while you are experimenting, you are going to have to go through quite a bit of hassle as you connect and disconnect to different servers, but once you find a server that you are happy on, then your troubles are over .... or are they?</p>
<p class=\"left\">Actually, how smooth things go depends on the L2J server that is being run. NC Soft update their client on a regular basis, and the L2J server isn't too far behind.  If the versions are too far different between your client and the server they are running, this usually manifests itself in having problems logging on.  Enjoying a free game isn't an easy ride!</p>
			<p class=\"dropmain\">&nbsp;</p>
	</td>
	</tr>
	</table></center>
	";

wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
