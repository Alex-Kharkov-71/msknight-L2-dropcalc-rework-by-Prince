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

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.

$evaluser = wrap_start($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, 0, "", 0, $ipaddr, $db_tokenexp, $guest_allow, $all_users_maps, $all_users_recipe, $guest_user_maps);
if ($evaluser == 2)
{	$username = "guest";	}
if ($evaluser == 0)
{	$username = "";	}

echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"blanktab\"><tr>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"7sign.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_sevens\" type=\"submit\" class=\"bigbut2\"></form></td>";
	if ($top_ten)
	{	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"topten.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_topten\" type=\"submit\" class=\"bigbut2\"></form></td>";	}
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"trusted.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_trustedp\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"reference.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_classtree\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"castles.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"$lang_caststat\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "<td class=\"dropmain\" valign=\"top\"><form method=\"post\" action=\"clanhall.php?username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id\"><input value=\"Clan Halls\" type=\"submit\" class=\"bigbut2\"></form></td>";
	echo "</tr></table>";
// Print top of page
echo "
			<p class=\"dropmain\">&nbsp;</p>
			<center>
	<table width=\"80%\" cellpadding=\"20\" class=\"dropmain\">
	<tr>
	<td class=\"dropmain\">
			<p class=\"dropmain\">&nbsp;</p>
			<h2 class=\"dropmainblack\">Character Progressions</h2>

			<center><table cellpadding=\"20\" class=\"nodropback\" width=\"100%\"><tr>
			<td class=\"blanktab\">

			<center><p class=\"dropmain\"><strong class=\"dropmain\">Human</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"6\"><p class=\"dropmain\">Fighter</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Knight</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Dark Avenger<br><small>The&nbsp;tank!&nbsp;Reflect&nbsp;damage Dark&nbsp;Panther&nbsp;pet&nbsp;fights.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Hell Knight</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Paladin<br><small>Support&nbsp;Tank<br>Doesn't&nbsp;do&nbsp;much&nbsp;damage.<br>Hybrid&nbsp;class.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Phoenix Knight</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Rogue</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Hawkeye<br><small>Damage&nbsp;output&nbsp;archer&nbsp;in all&nbsp;three&nbsp;classes.<br>Snipe&nbsp;buff.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Sagittarius</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Treasure Hunter<br><small>Very powerful&nbsp;close&nbsp;combat&nbsp;fighter<br>but&nbsp;low&nbsp;HP.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Adventurer</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Warrior</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Gladiator<br><small>Powerhouse&nbsp;for&nbsp;damage<br>but&nbsp;medium&nbsp;HP<br>Ideal&nbsp;for&nbsp;PVP&nbsp;and&nbsp;solo</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Duelist</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Warlord<br><small>One&nbsp;against&nbsp;many.<br>Polearm&nbsp;specialist.<br>Good&nbsp;area&nbsp;of&nbsp;effect.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Dreadnought</p></td></tr><tr>
			<td class=\"lefthead\" rowspan=\"5\"><p class=\"dropmain\">Mage</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Cleric</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Bishop<br><small>Highest&nbsp;power&nbsp;heal&nbsp;spells&nbsp;for&nbsp;single&nbsp;target&nbsp;and&nbsp;party.&nbsp;<br>Can ressurects,&nbsp;cleans&nbsp;debuffs&nbsp;en&nbsp;renders&nbsp;<br>you&nbsp;invincible&nbsp;a&nbsp;short&nbsp;time.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Cardinal</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Prophet<br><small>Most&nbsp;specialized&nbsp;buffer&nbsp;class&nbsp;mainly&nbsp;for&nbsp;fighter&nbsp;character.&nbsp;<br>Can&nbsp;support&nbsp;with&nbsp;low&nbsp;power&nbsp;heal&nbsp;and&nbsp;Root,&nbsp;debuff&nbsp;seplls.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Hierophant</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"3\"><p class=\"dropmain\">Wizard</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Sorcerer<br><small>A&nbsp;fire&nbsp;based&nbsp;nuker&nbsp;class,&nbsp;cancel&nbsp;buffs&nbsp;from&nbsp;an&nbsp;enemy&nbsp;and&nbsp;<br>also&nbsp;use&nbsp;AoE&nbsp;fire&nbsp;attacks.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Archmage</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Necromancer<br><small>Summons&nbsp;Undead&nbsp;monster&nbsp;from&nbsp;a&nbsp;Corpse.&nbsp;Mainly&nbsp;use&nbsp;<br>debuff&nbsp;spells&nbsp;and&nbsp;dark&nbsp;magic/life&nbsp;drain&nbsp;skill.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Soul Taker</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Warlock<br><small>A&nbsp;support&nbsp;class&nbsp;that&nbsp;summons&nbsp;Cats.&nbsp;Use&nbsp;cubics&nbsp;and&nbsp;can&nbsp;<br>buff&nbsp;with&nbsp;his&nbsp;Feline&nbsp;Queen.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Arcana Lord</p></td></tr><tr>
			</tr></table></center>

			</td><td class=\"blanktab\">

			<center><p class=\"dropmain\"><strong class=\"dropmain\">Elf</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"4\"><p class=\"dropmain\">Fighter</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Knight</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Temple Knight<br><small>Support&nbsp;Tank<br>Doesn't&nbsp;do&nbsp;much&nbsp;damage.<br>Has&nbsp;cubics&nbsp;but&nbsp;weak.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Evas Templar</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Sword Singer<br><small>Support&nbsp;character&nbsp;with&nbsp;songs.<br>Low&nbsp;damage.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Sword Muse</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Scout</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Plains Walker<br><small>Dagger&nbsp;using.&nbsp;Low&nbsp;HP.<br>Powerful&nbsp;close&nbsp;up&nbsp;attacker.<br>Quite&nbsp;fast.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Wind Rider</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Silver Ranger<br><small>Highest&nbsp;walking&nbsp;speed.<br>Interrupts&nbsp;spellcasters.&nbsp;Good&nbsp;kiter.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Moonlight Sentinel</p></td></tr><tr>
			<td class=\"lefthead\" rowspan=\"5\"><p class=\"dropmain\">Mage</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Wizard</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Elemental Summoner<br><small>Area&nbsp;of&nbsp;effect&nbsp;nuker&nbsp;by&nbsp;summons.<br>One&nbsp;on&nbsp;many&nbsp;damage.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Elemental Master</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Spell Singer<br><small>Strong&nbsp;water&nbsp;spell&nbsp;nuker.<br>Low&nbsp;HP&nbsp;high&nbsp;damage.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Mystic Muse</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"1\"><p class=\"dropmain\">Elven Oracle</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Elven Elder<br><small>An&nbsp;single&nbsp;target&nbsp;healer&nbsp;class&nbsp;with&nbsp;highest&nbsp;<br>cast&nbsp;spd&nbsp;and&nbsp;some&nbsp;exclusive&nbsp;buffs.&nbsp;<br>They&nbsp;have&nbsp;ability&nbsp;to&nbsp;heal&nbsp;MP.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Evas Saint</p></td></tr><tr>
			</tr></table>

			<p class=\"dropmain\"><strong class=\"dropmain\">Dwarf</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Fighter</p></td>
				<td class=\"lefthead\" rowspan=\"1\"><p class=\"dropmain\">Artisan</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Warsmith<br><small>Creates&nbsp;weapons&nbsp;and&nbsp;siege&nbsp;weapons.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Maestro</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"1\"><p class=\"dropmain\">Scavenger</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Bounty Hunter<br><small>Spoiler&nbsp;class&nbsp;to&nbsp;get&nbsp;other&nbsp;items.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Fortune Seeker</p></td></tr><tr>
	
			</tr></table>

			</td></tr></table></center>


			<center><table cellpadding=\"20\" class=\"nodropback\" width=\"100%\"><tr>
			<td class=\"blanktab\">

			<center><p class=\"dropmain\"><strong class=\"dropmain\">Dark Elf</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"4\"><p class=\"dropmain\">Fighter</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Palus Knight</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Shillen Knight<br><small>Good&nbsp;tanker.<br>Lightning&nbsp;spell.<br>Has&nbsp;cubics.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Shillen Templar</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Bladedancer<br><small>Supports&nbsp;party&nbsp;with&nbsp;dances.<br>Low&nbsp;damage.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Spectral Dancer</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Assassin</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Abyss Walker<br><small>Dagger&nbsp;user.<br>Highest&nbsp;damage&nbsp;of&nbsp;dagger&nbsp;users.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Ghost Hunter</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Phantom Ranger<br><small>Distance&fighter.<br>When&nbsp;low&nbsp;on&nbsp;HP,&nbsp;does&nbsp;more<br>damage&nbsp;through&nbsp;fatal&nbsp;counter.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Ghost Sentinel</p></td></tr><tr>
			<td class=\"lefthead\" rowspan=\"5\"><p class=\"dropmain\">Mage</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Wizard</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Phantom Summoner<br><small>Summons&nbsp;demonic&nbsp;monsters&nbsp;and&nbsp;uses&nbsp;Dark&nbsp;nuke,&nbsp;Poisons&nbsp;<br>and&nbsp;debuff&nbsp;spells.&nbsp;Summon&nbsp;Nightshade&nbsp;can&nbsp;<br>be&nbsp;used&nbsp;as&nbsp;healer/buffer.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Spectral Master</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Spell Howler<br><small>Strong&nbsp;wind&nbsp;spell&nbsp;nuker.<br>Low&nbsp;HP&nbsp;high&nbsp;damage.<br>Strongest&nbsp;nuker.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Storm Screamer</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"1\"><p class=\"dropmain\">Shillien Oracle</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Shillien Elder<br><small>Buffer&nbsp;needed&nbsp;for&nbsp;Empower&nbsp;&&nbsp;Vampiric&nbsp;rage&nbsp;spells&nbsp;<br>also&nbsp;have&nbsp;middle-power&nbsp;heal&nbsp;and&nbsp;recharge&nbsp;MP.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Shillien Saint</p></td></tr><tr>
			</tr></table>

			<p class=\"dropmain\"><strong class=\"dropmain\">Orc</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Fighter(marauder)</p></td>
				<td class=\"lefthead\" rowspan=\"1\"><p class=\"dropmain\">Raider</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Destroyer<br><small>One&nbsp;man&nbsp;army.Awesome&nbsp;XP,&nbsp;<br>damage&nbsp;and&nbsp;crit.<br>Slow&nbsp;character.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Titan</p></td></tr><tr>
				<td class=\"lefthead\" rowspan=\"1\"><p class=\"dropmain\">Monk</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Tyrant<br><small>Powerhouse for damage.<br>Highest&nbsp;attack&nbsp;speed&nbsp;in&nbsp;game,Skills&nbsp;that&nbsp;<br>suport&nbsp;fist&nbsp;weapons.</strong><br>Low&nbsp;defnece.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Grand Khauatari</p></td></tr><tr>
			<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Mage</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Shaman</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Over Lord<br><small>Mass&nbsp;debuffer.<br>Sleeps&nbsp;and&nbsp;lowers&nbsp;defence.<br>Damage&nbsp;over&nbsp;time.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Dominator</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Warcryer<br><small>Party&nbsp;buffer.&nbsp;Highest&nbsp;power&nbsp;buffs.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Doomcryer</p></td></tr><tr>
			</tr></table></center>

			</td><td class=\"blanktab\">

			<center>
			
			<p class=\"dropmain\"><strong class=\"dropmain\">Male Kamael</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Male Soldier</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Trooper</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Soul Breaker<br><small>An&nbsp;hybrid&nbsp;class&nbsp;using&nbsp;Nukes,&nbsp;debuff&nbsp;spells&nbsp;<br>and&nbsp;physical&nbsp;attacks.&nbsp;<br>Use&nbsp;Rapier&nbsp;and&nbsp;light&nbsp;armor.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Soul Hound</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Berseker<br><small>Use&nbsp;rush&nbsp;and&nbsp;high&nbsp;power&nbsp;physical&nbsp;skills,&nbsp;<br>can&nbsp;Disarm&nbsp;a&nbsp;target,&nbsp;stuns&nbsp;and&nbsp;bleed&nbsp;<br>enemies&nbsp;around&nbsp;him.<small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">DoomBringer</p></td></tr><tr>
			</tr></table>

			<br>
			<br>
			<br>
			<br>
			<br>
			<br>

			<p class=\"dropmain\"><strong class=\"dropmain\">Female Kamael</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Female Soldier</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">Warder</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Soul Breaker<br></p><small>An&nbsp;hybrid&nbsp;class&nbsp;using&nbsp;Nukes,&nbsp;debuff&nbsp;spells&nbsp;<br>and&nbsp;physical&nbsp;attacks.&nbsp;<br>Use&nbsp;Rapier&nbsp;and&nbsp;light&nbsp;armor.</small></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Soul Hound</p></td></tr><tr>
					<td class=\"lefthead\"><p class=\"dropmain\">Arbalester<br><small>A&nbsp;Crossbow&nbsp;user&nbsp;for&nbsp;long-range&nbsp;attacks,&nbsp;<br>Can&nbsp;use&nbsp;traps&nbsp;against&nbsp;their&nbsp;enemies.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">Trikster</p></td></tr><tr>
			</tr></table>

			<br>
			<br>
			<br>
			<br>
			<br>
			<br>

			<p class=\"dropmain\"><strong class=\"dropmain\">Secret Class Kamael</strong></p><table cellpadding=\"5\" class=\"dropmain\"><tr>
			<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">--------------</p></td>
				<td class=\"lefthead\" rowspan=\"2\"><p class=\"dropmain\">--------------</p></td>
					<td class=\"lefthead\"><p class=\"dropmain\">Inspector<br><small>A&nbsp;Class&nbsp;based&nbsp;on&nbsp;debuffs&nbsp;and&nbsp;short&nbsp;lived&nbsp;<br>but&nbsp;high&nbsp;power&nbsp;buffs.&nbsp;Can&nbsp;also&nbsp;heal&nbsp;<br>(over&nbsp;time)&nbsp;or&nbsp;regenerating&nbsp;party's&nbsp;mps.</small></p></td>
						<td class=\"lefthead\"><p class=\"dropmain\">judicator</p></td></tr><tr>

			</td></tr></table></center>

			
			<p class=\"dropmain\">&nbsp;</p>
		
	</td>
	</tr>
	</table></center>
	";

wrap_end($username, $token, $_GET, $_POST, $langval, $language_array, $top_ten, $db_location, $db_user, $db_psswd, $db_l2jdb, $dblog_location, $dblog_user, $dblog_psswd, $dblog_l2jdb, $sec_inc_gmlevel, $sec_inc_admin, $refresh_timer, $refresh_string, $quality_level);

?>
