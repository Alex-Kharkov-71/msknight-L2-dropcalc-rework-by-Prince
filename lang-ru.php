
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
$lang_welcome = "Добро пожаловать";
$lang_online = "Онлайн";
$lang_language = "Язык";
$lang_logout = "Выйти";
$lang_items = "Вещи";
$lang_chars = "Персонажи";
$lang_clans = "Кланы";
$lang_mobs = "Мобы";
$lang_account = "Аккаунт";
$lang_locations = "Локации";
$lang_recipes = "Рецепты";
$lang_skills = "Скилы";
$lang_usern = "Логин";
$lang_passwd = "Пароль";
$lang_reg_acc = "Зарегистрировать&nbsp;аккаунт";
$lang_guest_login = "Гостевой вход";
$lang_faq = "F.A.Q.";
$lang_newbguide = "Newbie Guide";
$lang_connecting = "Подключение к L2J";
$lang_whosonline = "Кто онлайн";
$lang_mobsbylvl = "Мобы по уровням";
$lang_itemsbytype = "Вещи по типам";
$lang_trustedp = "Доверенные игроки";
$lang_classtree = "Дерево классов";
$lang_caststat = "Статус замков";
$lang_sevens = "Статус 7 печатей";
$lang_topten = "Топ игроков";
$lang_changep = "Изменить пароль";
$lang_gmref = "Информация для ГМ";
$lang_servertools = "Утилиты&nbsp;сервера";
$lang_serverconsole = "Консоль&nbsp;сервера";
$lang_serverstats = "Статистика&nbsp;сервера";
$lang_chatlog = "Лог&nbsp;чата";
$lang_shops = "Магазины";
$lang_pets = "Питомцы";
$lang_databaseu = "Утилиты&nbsp;БД";
$lang_announcements = "Объявления";
$lang_loginc = "Консоль&nbsp;логин&nbsp;сервера";
$lang_loginevent = "События&nbsp;логин&nbsp;сервера";
$lang_itemlog = "Лог&nbsp;вещей";
$lang_gmaudit = "Лог&nbsp;ГМ-аудита";

//Races
$lang_human = "Человек";
$lang_elf = "Эльф";
$lang_delf = "Тёмный&nbsp;Эльф";
$lang_orc = "Орк";
$lang_dwarf = "Дварф";
$lang_kamael = "Kamael";

$lang_monday = "Понедельник";
$lang_tuesday = "Вторник";
$lang_wednesday = "Среда";
$lang_thursday = "Четверг";
$lang_friday = "Пятница";
$lang_saturday = "Суббота";
$lang_sunday = "Воскресенье";

$lang_unknown = "Неизвестно";
$lang_none = "Нет";
$lang_dawn = "Рассвет";
$lang_dusk = "Закат";
$lang_stones = "Камни";
$lang_festival = "Фестиваль";
$lang_total = "Всего";
$lang_points = "Очки";
$lang_name = "Имя";
$lang_undead = "Нежить";
$lang_type = "Тип";
$lang_spawn = "Спавн";
$lang_sct = "Выберите тип НПЦ";
$lang_armour = "Броня";
$lang_weapon = "Оружие";
$lang_other = "Другое";
$lang_accessories = "Бижутерия";
$lang_warehouse = "Склад";
$lang_freight = "Фрахт";
$lang_inventory = "Инвентарь";
$lang_wearing = "Надето";
$lang_sarmour = "Выберите броню";
$lang_sweapon = "Выберите оружие";
$lang_sother = "Выберите вещь";
$lang_saccessories = "Выберите бижутерию";
$lang_tdwarves = "Доверенные гномы";
$lang_tothers = "Другие доверенные персонажи";
$lang_castle = "Замок";
$lang_owner = "Владелец";
$lang_stime = "Время осады";
$lang_sday = "День осады";
$lang_karma = "Карма";
$lang_level = "Уровень";
$lang_character = "Персонаж";
$lang_clan = "Клан";
$lang_name = "Имя";
$lang_password = "Пароль";
$lang_confirm = "Подтвердить";
$lang_createacc = "Создать аккаунт";
$lang_signin = "Войти";
$lang_day = "День";
$lang_night = "Ночь";
$lang_always = "Всегда";
$lang_mapkey = "Легенда карты";
$lang_tax = "Налог";
$lang_itemview = "Просмотр вещей";
$lang_skillview = "Просмотр скилов";
$lang_recipeview = "Просмотр рецептов";
$lang_pguandp = "Please give a user name and password";
$lang_passillchar = "Неверные символы в пароле.<br>Используйте только буквы и цифры.";
$lang_passilluchar = "Неверные символы в логине.<br>Используйте только буквы, цифры и подчёркивания.";
$lang_pass_noguest = "Нельзя использовать Guest как имя пользователя.";
$lang_pass_minthree = "Пароль должен быть не короче трёх символов.";
$lang_pass_nomatch = "Пароли не совпадают.";
$lang_pass_length = "Длина имени аккаунта превышает максимальную допустимую";
$lang_pass_userexist = "Имя пользователя уже существует.";
$lang_pass_suceed = "Смена пароля прошла успешно!";

$lang_itemtype = "Тип&nbsp;вещи";
$lang_arm_and_a = "Броня и бижутерия";
$lang_clanwareh = "Склад клана";
$lang_clanwarehemp = "Склад клана пуст.";
$lang_equipped = "Надето";
$lang_invendissable = "Администратор отключил просмотр инвентаря других персонажей.";
$lang_clanifinddis = "Администратор отключил поиск вещей в клане.";
$lang_clancalc = "Клановый подсчёт";
$lang_class = "Класс";

$lang_settings = "Установки&nbsp;программы";
$lang_leader = "Лидер";
$lang_makeleader = "Сделать лидером";
$lang_hideout = "Клан-холл";
$lang_ally = "Альянс";
$lang_lastlogon = "Последний вход";
$lang_erasename = "Удалить имя";

$lang_server = "Сервер";
$lang_skin = "Скин";
$lang_fame = "Fame";
$lang_shops_spawn = "Магазины со спавном";
$lang_shops_nospawn = "Без спавна";
$lang_shops_all = "Все магазины";
$lang_shops_nonpc = "Список без NPC";
$lang_close = "Закрыть";
$lang_imp = array(
	'item_routine' => 'Импорт предметов',
	'npc_routine' => 'Импорт NPC',
	'processing' => 'Обработка',
	'items_imported' => 'Импортировано предметов: %1$d',
	'npc_imported' => 'Импортировано NPC: %1$d, строк дропа/спойла: %2$d',
	'import_complete' => 'Импорт завершён.',
	'npc_complete' => 'Импорт NPC завершён.',
	'skills_imported' => 'Импортировано скиллов: %1$d',
	'custom_files' => 'Файлов custom: %1$d',
	'recipes' => 'Импортировано рецептов: %1$d (ингредиентов: %2$d). Пропущено повторов recipeId: %3$d.',
	'file_bad' => 'Файл не найден или не разбирается: %1$s',
	'shops_html' => 'NPC найдено через диалоги для магазинов без &lt;npcs&gt;: %1$d',
	'shops' => 'Импортировано строк магазинов: %1$d (файлов обработано: %2$d)',
	'spawns' => 'Импортировано строк спавна: %1$d (файлов обработано: %2$d)',
	'locs' => 'Импортировано локаций (TownZone): %1$d (файлов прочитано: %2$d)',
	'quests_end' => 'Импорт завершён. Квестов без найденных предметов: %1$d',
	'quests_nodir' => 'Папки квестов не найдены: %1$s',
	'st_table' => 'Таблица',
	'st_imported' => 'Импортировано',
	'st_errors' => 'Ошибок',
	'st_total' => 'Итого',
	'st_first' => 'Первые ошибки:',
	'st_none' => 'Ошибок нет.',
	'st_notimp' => 'НЕ импортировано:',
	'no_access' => 'У вас недостаточно прав доступа.',
	'no_connect' => 'Не удалось подключиться',
	'acc_about' => 'Сейчас будут импортированы аккаунты логин-сервера',
	'acc_fail' => 'Не удалось импортировать аккаунты: %1$s',
	'acc_ok' => 'Аккаунты импортированы: добавлено %1$d, уже было %2$d, всего %3$d.',
	'acc_button' => 'Нажмите, чтобы импортировать аккаунты',
);
$lang_imp = $lang_imp + array(
	'Skills Changer' => 'Смена скиллов',
	'Regular Database Checks' => 'Регулярные проверки базы',
	'Other DB Tools' => 'Прочие инструменты БД',
	'Logon Server' => 'Логин-сервер',
	'Item History System' => 'История предметов',
	'Import Tools' => 'Инструменты импорта',
	'Gameserver Operations' => 'Операции с игровым сервером',
	'Enchanted Items Check' => 'Проверка заточенных предметов',
	'Dormant Accounts' => 'Неактивные аккаунты',
	'Take Snapshot' => 'Сделать снимок',
	'Shutdown Logon' => 'Остановить логин',
	'Restart Logon' => 'Перезапустить логин',
	'Reboot 3 minutes' => 'Перезагрузка через 3 минуты',
	'Reboot 10 minutes' => 'Перезагрузка через 10 минут',
	'Reboot 1 minute' => 'Перезагрузка через 1 минуту',
	'Purge Thread Pools' => 'Очистить пулы потоков',
	'Process Item History Log' => 'Обработать журнал предметов',
	'Logon Status' => 'Состояние логина',
	'List Banned Accs.' => 'Заблокированные акк.',
	'Import Spawns' => 'Импорт спавнов',
	'Import Skills.' => 'Импорт скиллов',
	'Import Recipes' => 'Импорт рецептов',
	'Import Quests' => 'Импорт квестов',
	'Import NPCs' => 'Импорт NPC',
	'Import Logon Accounts' => 'Импорт аккаунтов логина',
	'Import Locations' => 'Импорт локаций',
	'Import Items' => 'Импорт предметов',
	'Import File' => 'Импорт файла',
	'Import Buylists' => 'Импорт магазинов',
	'Freeform Telnet' => 'Свободный telnet',
	'Extunload all' => 'Выгрузить все (extunload)',
	'Extreload all' => 'Перезагрузить все (extreload)',
	'Extinit all' => 'Инициализировать все (extinit)',
	'Enchant 5+' => 'Заточка 5+',
	'Enchant 10+' => 'Заточка 10+',
	'Enchant 50+' => 'Заточка 50+',
	'Enchant 100+' => 'Заточка 100+',
	'Enchant 1,000+' => 'Заточка 1 000+',
	'Enchant 60,000+' => 'Заточка 60 000+',
	'Create Test Character' => 'Создать тестового персонажа',
	'Clone NPC' => 'Клонировать NPC',
	'Clean Up Database' => 'Очистить базу',
	'Check Database' => 'Проверить базу',
	'Changes List' => 'Список изменений',
	'Box OK IPs' => 'Разрешённые IP',
	'Apply Changes' => 'Применить изменения',
	'Abort Reboot' => 'Отменить перезагрузку',
	'3 Month Dormant Delete' => 'Удалить неактивных 3 мес.',
	'6 Month Dormant Delete' => 'Удалить неактивных 6 мес.',
	'1 Year Dormant Delete' => 'Удалить неактивных 1 год',
	'Couldn\'t connect to telnet' => 'Не удалось подключиться к telnet',
	'Install DropCalc tables' => 'Установка таблиц DropCalc',
	'DropCalc is not installed on the first server\'s database yet, built-in default settings are in use.' => 'DropCalc ещё не установлен в базе первого сервера, используются встроенные настройки по умолчанию.',
	'Install the <strong>login database</strong> and then each server. The first server\'s database also holds the interface settings shared by all servers, so install it first.<br>Only the DropCalc tables (<strong>knight*</strong>) are touched, never the game\'s own tables.' => 'Установите <strong>базу логина</strong>, затем каждый сервер. В базе первого сервера хранятся и общие настройки интерфейса, поэтому ставьте его первым.<br>Затрагиваются только таблицы DropCalc (<strong>knight*</strong>), таблицы самой игры не трогаются.',
	'Database' => 'База данных',
	'State' => 'Состояние',
	'Action' => 'Действие',
	'cannot connect to %1$s - %2$s' => 'нет подключения к %1$s - %2$s',
	'database %1$s not found - %2$s' => 'база %1$s не найдена - %2$s',
	'not installed' => 'не установлено',
	'partly installed' => 'установлено частично',
	'installed' => 'установлено',
	'%1$d of %2$d tables' => '%1$d из %2$d таблиц',
	'settings row missing' => 'нет строки настроек',
	'%1$d tables' => '%1$d таблиц',
	'Install tables' => 'Установить таблицы',
	'Install missing tables' => 'Доустановить недостающие таблицы',
	'Recreate from scratch' => 'Создать заново',
	'yes, delete and recreate' => 'да, удалить и создать заново',
	'This DELETES all DropCalc data in %1$s (%2$s) and creates the tables from scratch. Continue?' => 'Это УДАЛИТ все данные DropCalc в %1$s (%2$s) и создаст таблицы заново. Продолжить?',
	'users, access times, e-mail addresses' => 'пользователи, время доступа, адреса почты',
	'settings, imported game data, trusted list, statistics' => 'настройки, импортированные данные игры, доверенный список, статистика',
	'%1$s: cannot open database %2$s - %3$s' => '%1$s: не удалось открыть базу %2$s - %3$s',
	'%1$s: nothing was done - tick the confirmation box to recreate the tables from scratch.' => '%1$s: ничего не сделано - отметьте галочку подтверждения, чтобы создать таблицы заново.',
	'%1$s: the install stopped with an error - %2$s (the database user needs CREATE and, for a recreate, DROP rights).' => '%1$s: установка остановилась с ошибкой - %2$s (пользователю БД нужны права CREATE, а для пересоздания ещё и DROP).',
	'%1$s: tables recreated from scratch.' => '%1$s: таблицы созданы заново.',
	'%1$s: tables installed.' => '%1$s: таблицы установлены.',
	' All DropCalc users were recreated from the login server accounts, so you have to log in again.' => ' Все пользователи DropCalc созданы заново из аккаунтов логин-сервера, поэтому нужно войти снова.',
	'DropCalc is not installed on this server yet.' => 'DropCalc на этом сервере ещё не установлен.',
	'DropCalc is not set up yet. An administrator has to install its tables after logging in.' => 'DropCalc ещё не настроен. Администратор должен установить его таблицы после входа.',
);
?>