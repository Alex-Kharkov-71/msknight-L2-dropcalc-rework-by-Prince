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
			<h2 class=\"dropmainblack\">Welcome to the dropcalc FAQ.</h2>
			<p class=\"dropmain\">&nbsp;</p>
			<p class=\"left\"><strong class=\"dropmain\">Q.</strong> What is this?</p>
			<p class=\"left\"><strong class=\"dropmain\">A.</strong> This is what is known as a drop calc system.  It is used in conjunction with the game Lineage II. (The open source, Java based version rather than the retail one.)  As the virtual world of Lineage II is a large one, systems like this are used to help the player locate various creatures or objects that are needed to progress in the game.</p>
			<p class=\"dropmain\">&nbsp;</p>
			<p class=\"left\"><strong class=\"dropmain\">Q.</strong> Is there a charge for using it?</p>
			<p class=\"left\"><strong class=\"dropmain\">A.</strong> That depends on the administrators of the particular server that this program is being used on.</p>
			<p class=\"dropmain\">&nbsp;</p>
			<p class=\"left\"><strong class=\"dropmain\">Q.</strong> Will I see monsters that have no spawns?</p>
			<p class=\"left\"><strong class=\"dropmain\">A.</strong> No.  The drop calc will only show you monsters that actually have locations that are live in the game.  The drop calc can be used by administrators to find monsters that are not active, to help with adding more monsters in to the live playing field, but everyday users of the drop calc will not see these.</p>
			<p class=\"dropmain\">&nbsp;</p>
			<p class=\"left\"><strong class=\"dropmain\">Q.</strong> Spawns show as two numbers.  Why?</p>
			<p class=\"left\"><strong class=\"dropmain\">A.</strong> Later versions of Lineage II Java game enabled a system whereby a monster can have a variety of spawn points in the world, but would only spawn in a restricted number of them.  For example, the Varikan Brigand Leader has five possible places to spawn in, but will only be at one of them.  Thus he is shown as 1/5.  Some monsters, however, only spawn in the day or the night, so there are separate figures for them.</p>
			<p class=\"dropmain\">&nbsp;</p>
			<p class=\"left\"><strong class=\"dropmain\">Q.</strong> Are there any oddities?</p>
			<p class=\"left\"><strong class=\"dropmain\">A.</strong> Yes, there are.  There are some monsters that show up on a particular place on a map, but when teleporting in to that area, the pointer on the players map can show a completely different location.  Things like this are not only rare, but the areas concerned are also only reachable by transporters, so there isn't really much to worry over.</p>
			<p class=\"dropmain\">&nbsp;</p>
			<p class=\"dropmain\"><a href=\"index.php\">Return to the logon screen</a></p>
			<p class=\"dropmain\">&nbsp;</p>
		
	</td>
	</tr>
	</table></center>
	";

wrap_end_dummy($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
