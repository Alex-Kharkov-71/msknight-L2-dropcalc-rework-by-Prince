
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
$lang_welcome = "V&iacute;tej";
$lang_online = "Online";
$lang_language = "Jazyk";
$lang_logout = "Odhl&aacute;&scaron;en&iacute;";
$lang_items = "P&#345;edm&#283;ty";
$lang_chars = "Postavy";
$lang_clans = "Klany";
$lang_mobs = "Mobov&eacute;";
$lang_account = "&#218;&#269;et";
$lang_locations = "Lokace";
$lang_recipes = "Recepty";
$lang_skills = "Skily";
$lang_usern = "&#218;&#269;et";
$lang_passwd = "Heslo";
$lang_reg_acc = "Registrace &uacute;&#269;tu";
$lang_guest_login = "P&#345;ihl&aacute;sit se jako host";
$lang_faq = "F.A.Q.";
$lang_newbguide = "N&aacute;vod";
$lang_connecting = "P&#345;ihl&aacute;&scaron;en&iacute; do L2J";
$lang_whosonline = "Kdo je Online";
$lang_mobsbylvl = "Mobov&eacute; podle &uacute;rovn&#283;";
$lang_itemsbytype = "P&#345;edm&#283;ty podle typu";
$lang_trustedp = "D&#367;v&#283;ryhodn&iacute; hr&aacute;&#269;i";
$lang_classtree = "Class Tree";
$lang_caststat = "Stav hrad&#367;";
$lang_sevens = "Stav Sedmi znamen&iacute;";
$lang_topten = "Nejlep&scaron;&iacute; hr&aacute;&#269;i";
$lang_changep = "Zm&#283;na hesla";
$lang_gmref = "GM Reference";
$lang_servertools = "Server&nbsp;Tools";
$lang_serverconsole = "Server&nbsp;Console";
$lang_serverstats = "Server&nbsp;Statistics";
$lang_chatlog = "Chat&nbsp;Log";
$lang_shops = "Obchody";
$lang_pets = "Peti";
$lang_databaseu = "Utility&nbsp;Datab&aacute;ze";
$lang_announcements = "Ozn&aacute;men&iacute;";
$lang_loginc = "Login&nbsp;Console";
$lang_loginevent = "Login&nbsp;Events";
$lang_itemlog = "Item&nbsp;Log";
$lang_gmaudit = "GM&nbsp;Audit&nbsp;Log";

//Races
$lang_human = "&#269;lov&#283;k";
$lang_elf = "Elf";
$lang_delf = "Temn&yacute;&nbsp;Elf";
$lang_orc = "Ork";
$lang_dwarf = "Trpasl&iacute;k";
$lang_kamael = "Kamael";

$lang_monday = "Pond&#283;l&iacute;";
$lang_tuesday = "&#218;ter&yacute;";
$lang_wednesday = "St&#345;eda";
$lang_thursday = "&#269;tvrtek";
$lang_friday = "P&aacute;tek";
$lang_saturday = "Sobota";
$lang_sunday = "Ned&#283;le";

$lang_unknown = "Nezn&aacute;m&yacute;";
$lang_none = "&#382;&aacute;dn&yacute;";
$lang_dawn = "&#218;svit";
$lang_dusk = "Soumrak";
$lang_stones = "Kameny";
$lang_festival = "Festival";
$lang_total = "Celkem";
$lang_points = "Body";
$lang_name = "Jm&eacute;no";
$lang_undead = "Nemrtv&yacute;";
$lang_type = "Typ";
$lang_spawn = "Spawn";
$lang_sct = "Vyber typ postavy";
$lang_armour = "Zbroj";
$lang_weapon = "Zbra&#328;";
$lang_other = "Jin&eacute;";
$lang_accessories = "P&#345;&iacute;slu&scaron;enstv&iacute;";
$lang_warehouse = "Skladi&scaron;t&#283;";
$lang_freight = "Doprava";
$lang_inventory = "Invent&aacute;&#345;";
$lang_wearing = "Oble&#269;en&iacute;";
$lang_sarmour = "Vyber zbroj";
$lang_sweapon = "Vyber zbra&#328;";
$lang_sother = "Vyber p&#345;edm&#283;t";
$lang_saccessories = "Vyber p&#345;&iacute;slu&scaron;enstv&iacute;";
$lang_tdwarves = "D&#367;v&#283;ryhodn&iacute; trpasl&iacute;ci";
$lang_tothers = "Ostatn&iacute; d&#367;v&#283;ryhodn&iacute;";
$lang_castle = "Hrad";
$lang_owner = "Vlastn&iacute;k";
$lang_stime = "&#269;as obl&eacute;h&aacute;n&iacute;";
$lang_sday = "Den obl&eacute;h&aacute;n&iacute;";
$lang_karma = "Karma";
$lang_level = "&#218;rove&#328;";
$lang_character = "Postava";
$lang_clan = "Klan";
$lang_name = "Jm&eacute;no";
$lang_password = "Heslo";
$lang_confirm = "Potvrzen&iacute;";
$lang_createacc = "Vytvo&#345;en&iacute; &uacute;&#269;tu";
$lang_signin = "P&#345;ihl&aacute;&scaron;en&iacute;";
$lang_day = "Den";
$lang_night = "Noc";
$lang_always = "V&#382;dy";
$lang_mapkey = "Legenda k map&#283;";
$lang_tax = "Taxa";
$lang_itemview = "P&#345;edm&#283;ty";
$lang_skillview = "Schopnosti";
$lang_recipeview = "Recepty";
$lang_pguandp = "Pros&iacute;m zadej jm&eacute;no a heslo";
$lang_passillchar = "Neplatn&eacute; znaky v hesle.<br>Dr&#382; se alfanumeriky.";
$lang_passilluchar = "Neplatn&eacute; znaky v u&#382;ivatelsk&eacute;m jm&eacute;n&#283;.<br>Dr&#382; se alfanumeriky a podtr&#382;&iacute;tek.";
$lang_pass_noguest = "Nem&#367;&#382;e&scaron; pou&#382;&iacute;t \'Guest\' jako u&#382;ivatelsk&eacute; jm&eacute;no.";
$lang_pass_minthree = "Heslo mus&iacute; b&yacute;t minim&aacute;ln&#283; t&#345;i znaky dlouh&eacute;.";
$lang_pass_nomatch = "Heslo nesouhlas&iacute;.";
$lang_pass_length = "D&eacute;lka u&#382;ivatelsk&eacute;ho jm&eacute;na p&#345;esahuje mnaxim&aacute;ln&iacute; d&eacute;lku";
$lang_pass_userexist = "U&#382;ivatelsk&eacute; jm&eacute;no ji&#382; existuje.";
$lang_pass_suceed = "Zm&#283;na hesla byla &uacute;sp&#283;&scaron;n&aacute;.";

$lang_itemtype = "Typ&nbsp;p&#345;edm&#283;tu";
$lang_arm_and_a = "Zbroj a p&#345;&iacute;slu&scaron;enstv&iacute;";
$lang_clanwareh = "Skladi&scaron;t&#283; klanu";
$lang_clanwarehemp = "Skladi&scaron;t&#283; klanu je pr&aacute;zdn&eacute;.";
$lang_equipped = "Vybaveno";
$lang_invendissable = "Admin zak&aacute;zal prohl&iacute;&#382;en&iacute; invent&aacute;&#345;e jin&yacute;ch hr&aacute;&#269;&#367;.";
$lang_clanifinddis = "Administr&aacute;tor zak&aacute;zal vyhled&aacute;v&aacute;n&iacute; klanov&yacute;ch p&#345;edm&#283;t&#367;.";
$lang_clancalc = "Kalkulace pro klan";
$lang_class = "Class";

$lang_settings = "Nastaven&iacute;&nbsp;Drop&nbsp;kalkul&aacute;toru";
$lang_leader = "V&#367;dce";
$lang_makeleader = "Prohla&scaron; v&#367;dcem";
$lang_hideout = "&#218;kryt";
$lang_ally = "Spojenec";
$lang_lastlogon = "Posledn&iacute; p&#345;ihl&aacute;&scaron;en&iacute;";
$lang_erasename = "Smazat jm&eacute;no";

$lang_server = "Server";
$lang_skin = "Skin";
$lang_fame = "Fame";
$lang_shops_spawn = "Obchody se spawnem";
$lang_shops_nospawn = "Bez spawnu";
$lang_shops_all = "V&scaron;echny obchody";
$lang_shops_nonpc = "Seznam obchodu bez NPC";
$lang_close = "Zav&#345;&iacute;t";
$lang_imp = array(
	'item_routine' => 'Import předmětů',
	'npc_routine' => 'Import NPC',
	'processing' => 'Zpracovávání',
	'items_imported' => 'Importováno předmětů: %1$d',
	'npc_imported' => 'Importováno NPC: %1$d, řádků dropu/spoilu: %2$d',
	'import_complete' => 'Import dokončen.',
	'npc_complete' => 'Import NPC dokončen.',
	'skills_imported' => 'Importováno skillů: %1$d',
	'custom_files' => 'Souborů custom: %1$d',
	'recipes' => 'Importováno receptů: %1$d (surovin: %2$d). Přeskočeno duplicitních recipeId: %3$d.',
	'file_bad' => 'Soubor nenalezen nebo jej nelze zpracovat: %1$s',
	'shops_html' => 'NPC nalezeno přes dialogy u obchodů bez &lt;npcs&gt;: %1$d',
	'shops' => 'Importováno řádků obchodů: %1$d (zpracováno souborů: %2$d)',
	'spawns' => 'Importováno řádků spawnů: %1$d (zpracováno souborů: %2$d)',
	'locs' => 'Importováno lokací (TownZone): %1$d (přečteno souborů: %2$d)',
	'quests_end' => 'Import dokončen. Questů bez nalezených předmětů: %1$d',
	'quests_nodir' => 'Složky questů nenalezeny: %1$s',
	'st_table' => 'Tabulka',
	'st_imported' => 'Importováno',
	'st_errors' => 'Chyb',
	'st_total' => 'Celkem',
	'st_first' => 'První chyby:',
	'st_none' => 'Žádné chyby.',
	'st_notimp' => 'NEIMPORTOVÁNO:',
	'no_access' => 'Nemáte dostatečná oprávnění.',
	'no_connect' => 'Nelze se připojit',
	'acc_about' => 'Chystá se import účtů přihlašovacího serveru',
	'acc_fail' => 'Účty se nepodařilo importovat: %1$s',
	'acc_ok' => 'Účty importovány: přidáno %1$d, již existovalo %2$d, celkem %3$d.',
	'acc_button' => 'Klikněte pro import účtů',
);
$lang_imp = $lang_imp + array(
	'Skills Changer' => 'Změna skillů',
	'Regular Database Checks' => 'Pravidelné kontroly databáze',
	'Other DB Tools' => 'Ostatní nástroje DB',
	'Logon Server' => 'Přihlašovací server',
	'Item History System' => 'Historie předmětů',
	'Import Tools' => 'Nástroje importu',
	'Gameserver Operations' => 'Operace herního serveru',
	'Enchanted Items Check' => 'Kontrola vylepšených předmětů',
	'Dormant Accounts' => 'Neaktivní účty',
	'Take Snapshot' => 'Pořídit snímek',
	'Shutdown Logon' => 'Vypnout login',
	'Restart Logon' => 'Restartovat login',
	'Reboot 3 minutes' => 'Restart za 3 minuty',
	'Reboot 10 minutes' => 'Restart za 10 minut',
	'Reboot 1 minute' => 'Restart za 1 minutu',
	'Purge Thread Pools' => 'Vyčistit fondy vláken',
	'Process Item History Log' => 'Zpracovat log historie předmětů',
	'Logon Status' => 'Stav loginu',
	'List Banned Accs.' => 'Zabanované účty',
	'Import Spawns' => 'Import spawnů',
	'Import Skills.' => 'Import skillů',
	'Import Recipes' => 'Import receptů',
	'Import Quests' => 'Import questů',
	'Import NPCs' => 'Import NPC',
	'Import Logon Accounts' => 'Import účtů loginu',
	'Import Locations' => 'Import lokací',
	'Import Items' => 'Import předmětů',
	'Import File' => 'Import souboru',
	'Import Buylists' => 'Import obchodů',
	'Freeform Telnet' => 'Volný telnet',
	'Extunload all' => 'Extunload všech',
	'Extreload all' => 'Extreload všech',
	'Extinit all' => 'Extinit všech',
	'Enchant 5+' => 'Vylepšení 5+',
	'Enchant 10+' => 'Vylepšení 10+',
	'Enchant 50+' => 'Vylepšení 50+',
	'Enchant 100+' => 'Vylepšení 100+',
	'Enchant 1,000+' => 'Vylepšení 1 000+',
	'Enchant 60,000+' => 'Vylepšení 60 000+',
	'Create Test Character' => 'Vytvořit testovací postavu',
	'Clone NPC' => 'Klonovat NPC',
	'Clean Up Database' => 'Vyčistit databázi',
	'Check Database' => 'Zkontrolovat databázi',
	'Changes List' => 'Seznam změn',
	'Box OK IPs' => 'Povolené IP',
	'Apply Changes' => 'Použít změny',
	'Abort Reboot' => 'Zrušit restart',
	'3 Month Dormant Delete' => 'Smazat neaktivní 3 měs.',
	'6 Month Dormant Delete' => 'Smazat neaktivní 6 měs.',
	'1 Year Dormant Delete' => 'Smazat neaktivní 1 rok',
	'Couldn\'t connect to telnet' => 'Nelze se připojit k telnetu',
	'Install DropCalc tables' => 'Instalace tabulek DropCalc',
	'DropCalc is not installed on the first server\'s database yet, built-in default settings are in use.' => 'DropCalc zatím není nainstalován v databázi prvního serveru, používá se vestavěné výchozí nastavení.',
	'Install the <strong>login database</strong> and then each server. The first server\'s database also holds the interface settings shared by all servers, so install it first.<br>Only the DropCalc tables (<strong>knight*</strong>) are touched, never the game\'s own tables.' => 'Nainstalujte <strong>databázi loginu</strong> a poté každý server. Databáze prvního serveru obsahuje také nastavení rozhraní společné všem serverům, proto ji nainstalujte první.<br>Dotčeny jsou jen tabulky DropCalc (<strong>knight*</strong>), tabulky samotné hry se nikdy nemění.',
	'Database' => 'Databáze',
	'State' => 'Stav',
	'Action' => 'Akce',
	'cannot connect to %1$s - %2$s' => 'nelze se připojit k %1$s - %2$s',
	'database %1$s not found - %2$s' => 'databáze %1$s nenalezena - %2$s',
	'not installed' => 'nenainstalováno',
	'partly installed' => 'částečně nainstalováno',
	'installed' => 'nainstalováno',
	'%1$d of %2$d tables' => '%1$d z %2$d tabulek',
	'settings row missing' => 'chybí řádek nastavení',
	'%1$d tables' => '%1$d tabulek',
	'Install tables' => 'Nainstalovat tabulky',
	'Install missing tables' => 'Doinstalovat chybějící tabulky',
	'Recreate from scratch' => 'Vytvořit znovu',
	'yes, delete and recreate' => 'ano, smazat a vytvořit znovu',
	'This DELETES all DropCalc data in %1$s (%2$s) and creates the tables from scratch. Continue?' => 'Tím se SMAŽOU všechna data DropCalc v %1$s (%2$s) a tabulky se vytvoří znovu. Pokračovat?',
	'users, access times, e-mail addresses' => 'uživatelé, časy přístupu, e-mailové adresy',
	'settings, imported game data, trusted list, statistics' => 'nastavení, importovaná data hry, důvěryhodný seznam, statistiky',
	'%1$s: cannot open database %2$s - %3$s' => '%1$s: databázi %2$s nelze otevřít - %3$s',
	'%1$s: nothing was done - tick the confirmation box to recreate the tables from scratch.' => '%1$s: nic se neudělalo - zaškrtněte potvrzení pro nové vytvoření tabulek.',
	'%1$s: the install stopped with an error - %2$s (the database user needs CREATE and, for a recreate, DROP rights).' => '%1$s: instalace skončila chybou - %2$s (uživatel databáze potřebuje práva CREATE a pro nové vytvoření i DROP).',
	'%1$s: tables recreated from scratch.' => '%1$s: tabulky vytvořeny znovu.',
	'%1$s: tables installed.' => '%1$s: tabulky nainstalovány.',
	' All DropCalc users were recreated from the login server accounts, so you have to log in again.' => ' Všichni uživatelé DropCalc byli znovu vytvořeni z účtů přihlašovacího serveru, takže se musíte znovu přihlásit.',
	'DropCalc is not installed on this server yet.' => 'DropCalc na tomto serveru zatím není nainstalován.',
	'DropCalc is not set up yet. An administrator has to install its tables after logging in.' => 'DropCalc ještě není nastaven. Administrátor musí po přihlášení nainstalovat jeho tabulky.',
);
?>
