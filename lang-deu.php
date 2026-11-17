
<?php
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

/*
	IF CONTRIBUTING ANOTHER LANGUAGE ...
	Please use the html & symbol code wherever possible to ensure that your language code stays intact
	... they can be found here ... http://www.ascii.cl/htmlcodes.htm 
*/

//Skin Menu
$lang_welcome = "Willkommen";
$lang_online = "Sind Online";
$lang_language = "Sprache";
$lang_logout = "Abmelden";
$lang_items = "Gegenst&auml;nde";
$lang_chars = "Charaktere";
$lang_clans = "Clans";
$lang_mobs = "Kreaturen";
$lang_account = "Konto";
$lang_locations = "Position";
$lang_recipes = "Rezepte";
$lang_skills = "Fertigkeiten";
$lang_usern = "Benutzername";
$lang_passwd = "Passwort";
$lang_reg_acc = "Konto erstellen";
$lang_guest_login = "Gast Anmeldung";
$lang_faq = "Fragen und Antworten";
$lang_newbguide = "Anleitung f&uuml;r Neulinge";
$lang_connecting = "Verbindung zu L2J";
$lang_whosonline = "Wer ist online?";
$lang_mobsbylvl = "Kreaturen nach Lvl";
$lang_itemsbytype = "Gegenst&auml;nde nach Typen";
$lang_trustedp = "Vertrauensw&uuml;rdige Spieler";
$lang_classtree = "Klassen Baum";
$lang_caststat = "Status der Festungen";
$lang_sevens = "Status der 7 Siegel";
$lang_topten = "Beste Spieler";
$lang_changep = "Passwort &auml;ndern";
$lang_gmref = "GM Hinweise";
$lang_servertools = "Server&nbsp;Werkzeuge";
$lang_serverconsole = "Server&nbsp;Konsole";
$lang_serverstats = "Server&nbsp;Statistiken";
$lang_chatlog = "Chat&nbsp;Protokoll";
$lang_shops = "L&auml;den";
$lang_pets = "Haustiere";
$lang_databaseu = "Datenbank&nbsp;Werkzeuge";
$lang_announcements = "Ank&uuml;ndigungen";
$lang_loginc = "Anmeldungs&nbsp;Konsole";
$lang_loginevent = "Anmeldungs&nbsp;Ereignisse";
$lang_itemlog = "Gegenstands&nbsp;Protokoll";
$lang_gmaudit = "GM-Kontroll&nbsp;Protokoll";

//Races
$lang_human = "Mensch";
$lang_elf = "Elf";
$lang_delf = "Dunkel Elf";
$lang_orc = "Ork";
$lang_dwarf = "Zwerg";
$lang_kamael = "Kamael";

$lang_monday = "Montag";
$lang_tuesday = "Dienstag";
$lang_wednesday = "Mittwoch";
$lang_thursday = "Donnerstag";
$lang_friday = "Freitag";
$lang_saturday = "Samstag";
$lang_sunday = "Sonntag";

$lang_unknown = "Unbekannt";
$lang_none = "Kein(e)";
$lang_dawn = "Dawn";
$lang_dusk = "Dusk";
$lang_stones = "Steine";
$lang_festival = "Festival";
$lang_total = "Total";
$lang_points = "Punkte";
$lang_name = "Name";
$lang_undead = "Untod";
$lang_type = "Typ";
$lang_spawn = "Spawn";
$lang_sct = "Charakter Typ w&auml;hlen";
$lang_armour = "R&uuml;stung";
$lang_weapon = "Waffen";
$lang_other = "Anderes";
$lang_accessories = "Accessoires";
$lang_warehouse = "Lager";
$lang_freight = "Fracht";
$lang_inventory = "Inventar";
$lang_wearing = "Bei sich tragend";
$lang_sarmour = "R&uuml;stung w&auml;hlen";
$lang_sweapon = "Waffe w&auml;hlen";
$lang_sother = "Item w&auml;hlen";
$lang_saccessories = "Accessoires w&auml;hlen";
$lang_tdwarves = "Vertrauensw&uuml;rdige Zwerge";
$lang_tothers = "Vertrauensw&uuml;rdige andere Klassen";
$lang_castle = "Burg";
$lang_owner = "Eigner";
$lang_stime = "Belagerungszeit";
$lang_sday = "Belagerungstag";
$lang_karma = "Karma";
$lang_level = "Level";
$lang_character = "Charakter";
$lang_clan = "Clan";
$lang_name = "Name";
$lang_password = "Passwort";
$lang_confirm = "Best&auml;tigen";
$lang_createacc = "Konto Anlegen";
$lang_signin = "anmelden";
$lang_day = "Tag";
$lang_night = "Nacht";
$lang_always = "Immer";
$lang_mapkey = "Map Key";
$lang_tax = "Steuer";
$lang_itemview = "Item Ansicht";
$lang_skillview = "Fertigkeitenansicht";
$lang_recipeview = "Rezeptansicht";
$lang_pguandp = "Bitte einen Nutzernamen und ein Passwort eingeben";
$lang_passillchar = "Ung&uml;ltiges Zeichen im Paswort.<br>Bitte nur alphanumerische Zeichen verwenden.";
$lang_passilluchar = "Ung&uml;ltiges Zeichen im Nutzernamen.<br>Bitte nur alphanumerische Zeichen oder Unterstriche verwenden.";
$lang_pass_noguest = "Guest kann nicht Nutzername sein.";
$lang_pass_minthree = "Passwort muss mindestens 3 Zeichen lang sein.";
$lang_pass_nomatch = "Passw&ouml;rter stimmen nicht &uumlberein.";
$lang_pass_length = "Nutzernamen zu lang, maximal Zeichen: ";
$lang_pass_userexist = "Nutzername bereits vorhanden.";
$lang_pass_suceed = "Passwort&auml;nderung durchgef&uuml;hrt!";

$lang_itemtype = "Item&nbsp;Typ";
$lang_arm_and_a = "R&uuml;stung &amp; Accessoires";
$lang_clanwareh = "Clan Lager";
$lang_clanwarehemp = "Clan Lager ist leer.";
$lang_equipped = "Ausger&uuml;stet";
$lang_invendissable = "Der Admin l&auml:sst nicht zu, das Inventar eines anderen Charakters einzusehen.";
$lang_clanifinddis = "Der Admin hat die Clansuchfunktion abgeschaltet.";
$lang_clancalc = "Clan Berechnung";
$lang_class = "Klasse";

$lang_settings = "Dropcalc&nbsp;Einstellungen";
$lang_leader = "Leader";
$lang_makeleader = "Make Leader";
$lang_hideout = "Hideout";
$lang_ally = "Ally";
$lang_lastlogon = "Letzter Logon";
$lang_erasename = "Name l&ouml;schen";

$lang_server = "Server";
$lang_skin = "Skin";
$lang_fame = "Ruhm";
$lang_shops_spawn = "Shops mit Spawn";
$lang_shops_nospawn = "Ohne Spawn";
$lang_shops_all = "Alle Shops";
$lang_shops_nonpc = "Shopliste ohne NPC";
$lang_close = "Schlie&szlig;en";
$lang_imp = array(
	'item_routine' => 'Import der Gegenstände',
	'npc_routine' => 'NPC-Import',
	'processing' => 'Verarbeitung',
	'items_imported' => 'Gegenstände importiert: %1$d',
	'npc_imported' => 'NPCs importiert: %1$d, Drop-/Spoil-Zeilen: %2$d',
	'import_complete' => 'Import abgeschlossen.',
	'npc_complete' => 'NPC-Import abgeschlossen.',
	'skills_imported' => 'Skills importiert: %1$d',
	'custom_files' => 'Custom-Dateien: %1$d',
	'recipes' => 'Rezepte importiert: %1$d (Zutaten: %2$d). Doppelte recipeId übersprungen: %3$d.',
	'file_bad' => 'Datei nicht gefunden oder nicht lesbar: %1$s',
	'shops_html' => 'NPCs über Dialoge gefunden für Shops ohne &lt;npcs&gt;: %1$d',
	'shops' => 'Shop-Zeilen importiert: %1$d (Dateien verarbeitet: %2$d)',
	'spawns' => 'Spawn-Zeilen importiert: %1$d (Dateien verarbeitet: %2$d)',
	'locs' => 'Orte importiert (TownZone): %1$d (Dateien gelesen: %2$d)',
	'quests_end' => 'Import beendet. Quests ohne erkannte Gegenstände: %1$d',
	'quests_nodir' => 'Quest-Ordner nicht gefunden: %1$s',
	'st_table' => 'Tabelle',
	'st_imported' => 'Importiert',
	'st_errors' => 'Fehler',
	'st_total' => 'Gesamt',
	'st_first' => 'Erste Fehler:',
	'st_none' => 'Keine Fehler.',
	'st_notimp' => 'NICHT importiert:',
	'no_access' => 'Sie haben keine ausreichenden Rechte.',
	'no_connect' => 'Verbindung nicht möglich',
	'acc_about' => 'Konten des Login-Servers werden importiert',
	'acc_fail' => 'Konten konnten nicht importiert werden: %1$s',
	'acc_ok' => 'Konten importiert: %1$d hinzugefügt, %2$d bereits vorhanden, %3$d insgesamt.',
	'acc_button' => 'Klicken, um die Konten zu importieren',
);
$lang_imp = $lang_imp + array(
	'Skills Changer' => 'Skill-Änderung',
	'Regular Database Checks' => 'Regelmäßige Datenbankprüfungen',
	'Other DB Tools' => 'Weitere DB-Werkzeuge',
	'Logon Server' => 'Login-Server',
	'Item History System' => 'Gegenstandsverlauf',
	'Import Tools' => 'Import-Werkzeuge',
	'Gameserver Operations' => 'Gameserver-Aktionen',
	'Enchanted Items Check' => 'Prüfung verzauberter Gegenstände',
	'Dormant Accounts' => 'Inaktive Konten',
	'Take Snapshot' => 'Snapshot erstellen',
	'Shutdown Logon' => 'Login beenden',
	'Restart Logon' => 'Login neu starten',
	'Reboot 3 minutes' => 'Neustart in 3 Minuten',
	'Reboot 10 minutes' => 'Neustart in 10 Minuten',
	'Reboot 1 minute' => 'Neustart in 1 Minute',
	'Purge Thread Pools' => 'Thread-Pools leeren',
	'Process Item History Log' => 'Gegenstandsverlauf verarbeiten',
	'Logon Status' => 'Login-Status',
	'List Banned Accs.' => 'Gesperrte Konten',
	'Import Spawns' => 'Spawns importieren',
	'Import Skills.' => 'Skills importieren',
	'Import Recipes' => 'Rezepte importieren',
	'Import Quests' => 'Quests importieren',
	'Import NPCs' => 'NPCs importieren',
	'Import Logon Accounts' => 'Login-Konten importieren',
	'Import Locations' => 'Orte importieren',
	'Import Items' => 'Gegenstände importieren',
	'Import File' => 'Datei importieren',
	'Import Buylists' => 'Shops importieren',
	'Freeform Telnet' => 'Freies Telnet',
	'Extunload all' => 'Alle Extunload',
	'Extreload all' => 'Alle Extreload',
	'Extinit all' => 'Alle Extinit',
	'Enchant 5+' => 'Verzauberung 5+',
	'Enchant 10+' => 'Verzauberung 10+',
	'Enchant 50+' => 'Verzauberung 50+',
	'Enchant 100+' => 'Verzauberung 100+',
	'Enchant 1,000+' => 'Verzauberung 1.000+',
	'Enchant 60,000+' => 'Verzauberung 60.000+',
	'Create Test Character' => 'Testcharakter erstellen',
	'Clone NPC' => 'NPC klonen',
	'Clean Up Database' => 'Datenbank bereinigen',
	'Check Database' => 'Datenbank prüfen',
	'Changes List' => 'Änderungsliste',
	'Box OK IPs' => 'Erlaubte IPs',
	'Apply Changes' => 'Änderungen anwenden',
	'Abort Reboot' => 'Neustart abbrechen',
	'3 Month Dormant Delete' => 'Inaktive löschen (3 Monate)',
	'6 Month Dormant Delete' => 'Inaktive löschen (6 Monate)',
	'1 Year Dormant Delete' => 'Inaktive löschen (1 Jahr)',
	'Couldn\'t connect to telnet' => 'Telnet-Verbindung nicht möglich',
	'Install DropCalc tables' => 'DropCalc-Tabellen installieren',
	'DropCalc is not installed on the first server\'s database yet, built-in default settings are in use.' => 'DropCalc ist in der Datenbank des ersten Servers noch nicht installiert, die eingebauten Standardeinstellungen werden verwendet.',
	'Install the <strong>login database</strong> and then each server. The first server\'s database also holds the interface settings shared by all servers, so install it first.<br>Only the DropCalc tables (<strong>knight*</strong>) are touched, never the game\'s own tables.' => 'Installieren Sie die <strong>Login-Datenbank</strong> und danach jeden Server. Die Datenbank des ersten Servers enthält auch die gemeinsamen Oberflächeneinstellungen, daher zuerst installieren.<br>Es werden nur die DropCalc-Tabellen (<strong>knight*</strong>) verändert, nie die Tabellen des Spiels.',
	'Database' => 'Datenbank',
	'State' => 'Status',
	'Action' => 'Aktion',
	'cannot connect to %1$s - %2$s' => 'keine Verbindung zu %1$s - %2$s',
	'database %1$s not found - %2$s' => 'Datenbank %1$s nicht gefunden - %2$s',
	'not installed' => 'nicht installiert',
	'partly installed' => 'teilweise installiert',
	'installed' => 'installiert',
	'%1$d of %2$d tables' => '%1$d von %2$d Tabellen',
	'settings row missing' => 'Einstellungszeile fehlt',
	'%1$d tables' => '%1$d Tabellen',
	'Install tables' => 'Tabellen installieren',
	'Install missing tables' => 'Fehlende Tabellen installieren',
	'Recreate from scratch' => 'Neu erstellen',
	'yes, delete and recreate' => 'ja, löschen und neu erstellen',
	'This DELETES all DropCalc data in %1$s (%2$s) and creates the tables from scratch. Continue?' => 'Dies LÖSCHT alle DropCalc-Daten in %1$s (%2$s) und erstellt die Tabellen neu. Fortfahren?',
	'users, access times, e-mail addresses' => 'Benutzer, Zugriffszeiten, E-Mail-Adressen',
	'settings, imported game data, trusted list, statistics' => 'Einstellungen, importierte Spieldaten, Vertrauensliste, Statistik',
	'%1$s: cannot open database %2$s - %3$s' => '%1$s: Datenbank %2$s kann nicht geöffnet werden - %3$s',
	'%1$s: nothing was done - tick the confirmation box to recreate the tables from scratch.' => '%1$s: nichts getan - Bestätigungsfeld ankreuzen, um die Tabellen neu zu erstellen.',
	'%1$s: the install stopped with an error - %2$s (the database user needs CREATE and, for a recreate, DROP rights).' => '%1$s: Installation mit Fehler abgebrochen - %2$s (der DB-Benutzer braucht CREATE und für Neuerstellung auch DROP).',
	'%1$s: tables recreated from scratch.' => '%1$s: Tabellen neu erstellt.',
	'%1$s: tables installed.' => '%1$s: Tabellen installiert.',
	' All DropCalc users were recreated from the login server accounts, so you have to log in again.' => ' Alle DropCalc-Benutzer wurden aus den Konten des Login-Servers neu erstellt, Sie müssen sich neu anmelden.',
	'DropCalc is not installed on this server yet.' => 'DropCalc ist auf diesem Server noch nicht installiert.',
	'DropCalc is not set up yet. An administrator has to install its tables after logging in.' => 'DropCalc ist noch nicht eingerichtet. Ein Administrator muss nach der Anmeldung die Tabellen installieren.',
);
?>