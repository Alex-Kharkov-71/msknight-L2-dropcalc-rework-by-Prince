-- DropCalc: installation of the GAME database (run once per game server database, e.g. l2s1 and l2s2).
-- Usage:  mysql -u root -p YOUR_GAME_DB < sql/install_game_db.sql
-- Safe to run again: every statement is CREATE TABLE IF NOT EXISTS / guarded INSERT.
-- The original DropCalc install script is not in the source archive, so this file is built from:
--  * the real structure of knightsettings/knightweapon/knightarmour/knightetcitem/knightquests/knightquestrun
--    (taken from a working L2JMobius database),
--  * the tables of this port (filled later by Server Utilities -> Import),
--  * tables reconstructed from the way the code uses them (see knight_missing_tables.sql).
-- The knightdrop / knightipok tables belong to the LOGIN database: use install_login_db.sql for them.

-- ===== tables that exist in a standard DropCalc install (real structure) =====
CREATE TABLE IF NOT EXISTS `knightsettings` (
  `register_allow` int(1) NOT NULL DEFAULT 0,
  `guest_allow` int(1) NOT NULL DEFAULT 0,
  `guest_user_maps` int(1) NOT NULL DEFAULT 0,
  `low_graphic_allow` int(1) NOT NULL DEFAULT 0,
  `db_tokenexp` int(3) NOT NULL DEFAULT 0,
  `sec_inc_gmlevel` int(3) NOT NULL DEFAULT 0,
  `sec_inc_admin` int(3) NOT NULL DEFAULT 0,
  `sec_giveandtake` int(3) NOT NULL DEFAULT 0,
  `sec_takeskill` int(3) NOT NULL DEFAULT 0,
  `sec_chatto` int(3) NOT NULL DEFAULT 0,
  `sec_enchant` int(3) NOT NULL DEFAULT 0,
  `adjust_anounce` int(3) NOT NULL DEFAULT 0,
  `kick_player` int(3) NOT NULL DEFAULT 0,
  `adjust_trust` int(3) NOT NULL DEFAULT 0,
  `reboot_server` int(3) NOT NULL DEFAULT 0,
  `adjust_shop` int(3) NOT NULL DEFAULT 0,
  `adjust_drops` int(3) NOT NULL DEFAULT 0,
  `sec_adj_notes` int(3) NOT NULL DEFAULT 0,
  `account_safe` int(1) NOT NULL DEFAULT 0,
  `guest_dropthru` int(1) NOT NULL DEFAULT 0,
  `gm_play_show` int(1) NOT NULL DEFAULT 0,
  `max_acc_length` int(2) NOT NULL DEFAULT 0,
  `max_pass_length` int(2) NOT NULL DEFAULT 0,
  `all_users_maps` int(1) NOT NULL DEFAULT 0,
  `all_users_recipe` int(1) NOT NULL DEFAULT 0,
  `all_newusers_maps` int(1) NOT NULL DEFAULT 0,
  `all_newusers_recipe` int(1) NOT NULL DEFAULT 0,
  `recipe_depth` int(1) NOT NULL DEFAULT 0,
  `all_newusers_character` int(1) NOT NULL DEFAULT 0,
  `all_users_character` int(1) NOT NULL DEFAULT 0,
  `l2version` int(2) NOT NULL DEFAULT 0,
  `drop_chance_adena` decimal(8,2) NOT NULL DEFAULT 1.00,
  `drop_chance_item` decimal(8,2) NOT NULL DEFAULT 1.00,
  `drop_chance_spoil` decimal(8,2) NOT NULL DEFAULT 1.00,
  `drop_chance_green` int(2) NOT NULL DEFAULT 0,
  `drop_chance_blue` int(2) NOT NULL DEFAULT 0,
  `sevensignall` int(1) NOT NULL DEFAULT 0,
  `top_ten` int(1) NOT NULL DEFAULT 0,
  `tten_number` int(1) NOT NULL DEFAULT 0,
  `tten_level` int(1) NOT NULL DEFAULT 0,
  `tten_pk` int(1) NOT NULL DEFAULT 0,
  `tten_pvp` int(1) NOT NULL DEFAULT 0,
  `tten_karma` int(1) NOT NULL DEFAULT 0,
  `tten_fame` int(1) NOT NULL DEFAULT 0,
  `tten_time` int(1) NOT NULL DEFAULT 0,
  `max_chars_per_acc` int(2) NOT NULL DEFAULT 0,
  `game_paranoia` int(1) NOT NULL DEFAULT 0,
  `guest_nosee_clanchars` int(1) NOT NULL DEFAULT 0,
  `check_boxing` int(1) NOT NULL DEFAULT 0,
  `emergency_teleport` int(1) NOT NULL DEFAULT 0,
  `prevent_cross_clan` int(1) NOT NULL DEFAULT 0,
  `bot_scan_ban` int(1) NOT NULL DEFAULT 0,
  `auto_prune` int(1) NOT NULL DEFAULT 0,
  `display_country` int(1) NOT NULL DEFAULT 0,
  `stopbanIPreg` int(1) NOT NULL DEFAULT 0,
  `map_item_status` int(1) NOT NULL DEFAULT 0,
  `map_item_id` int(6) NOT NULL DEFAULT 0,
  `map_item_when` int(1) NOT NULL DEFAULT 0,
  `map_item_online` int(1) NOT NULL DEFAULT 0,
  `rec_item_status` int(1) NOT NULL DEFAULT 0,
  `rec_item_id` int(6) NOT NULL DEFAULT 0,
  `rec_item_when` int(1) NOT NULL DEFAULT 0,
  `rec_item_online` int(1) NOT NULL DEFAULT 0,
  `char_item_status` int(1) NOT NULL DEFAULT 0,
  `char_item_id` int(6) NOT NULL DEFAULT 0,
  `char_item_when` int(1) NOT NULL DEFAULT 0,
  `char_item_online` int(1) NOT NULL DEFAULT 0,
  `use_duplicate` int(1) NOT NULL DEFAULT 0,
  `hide_language` int(1) NOT NULL DEFAULT 1,
  `hide_server` int(1) NOT NULL DEFAULT 1,
  `hide_skin` int(1) NOT NULL DEFAULT 1,
  `user_change_server` int(1) NOT NULL DEFAULT 0,
  `user_change_skin` int(1) NOT NULL DEFAULT 0,
  `force_default_skin` int(1) NOT NULL DEFAULT 1,
  `show_online` int(1) NOT NULL DEFAULT 1,
  `default_lang` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'lang-eng.php',
  `whosonlinegmlow` int(1) NOT NULL DEFAULT 1,
  `show_char_time` int(1) NOT NULL DEFAULT 1,
  `show_detail_char_time` int(1) NOT NULL DEFAULT 0,
  `delay_whosonline` int(2) NOT NULL DEFAULT 30,
  `delay_chat` int(2) NOT NULL DEFAULT 10,
  `delay_logs` int(2) NOT NULL DEFAULT 10,
  `gmintopten` int(1) NOT NULL DEFAULT 0,
  `php_type` int(1) NOT NULL DEFAULT 0,
  `show_map` int(1) NOT NULL DEFAULT 0,
  `map_nudge` int(1) NOT NULL DEFAULT 0,
  `showmobpict` int(1) NOT NULL DEFAULT 0,
  `allowpassreset` int(1) NOT NULL DEFAULT 0,
  `phpsmtp` int(1) NOT NULL DEFAULT 0,
  `emailfrom` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `smtpserver` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `smtpport` int(5) NOT NULL DEFAULT 25,
  `smtptimeout` int(2) NOT NULL DEFAULT 30,
  `smtpusername` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `smtppassword` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `smtplocalhost` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `smtpdebug` int(1) NOT NULL DEFAULT 0,
  `emailcheck` int(1) NOT NULL DEFAULT 0,
  `enchntgmaccallow` int(1) NOT NULL DEFAULT 0,
  `minlenitem` int(1) NOT NULL DEFAULT 3,
  `minlenchar` int(1) NOT NULL DEFAULT 0,
  `minlenclan` int(1) NOT NULL DEFAULT 0,
  `minlenmobs` int(1) NOT NULL DEFAULT 3,
  `minlenacc` int(1) NOT NULL DEFAULT 0,
  `minlenloc` int(1) NOT NULL DEFAULT 0,
  `minlenrec` int(1) NOT NULL DEFAULT 0,
  `gd_on` int(1) NOT NULL DEFAULT 0,
  `gd_srvon` int(1) NOT NULL DEFAULT 0,
  `gd_compress` int(1) NOT NULL DEFAULT 0,
  `gd_style` int(1) NOT NULL DEFAULT 0,
  `levelbracket` int(1) NOT NULL DEFAULT 0,
  `menushowchars` int(1) NOT NULL DEFAULT 0,
  `log_actions` int(1) NOT NULL DEFAULT 0,
  `log_duration` int(1) NOT NULL DEFAULT 1,
  `usecustomtables` int(1) NOT NULL DEFAULT 0,
  `chat_style` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `knightweapon` (
  `item_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `name` varchar(120) NOT NULL DEFAULT '0',
  `icon` varchar(50) NOT NULL DEFAULT '0',
  `bodypart` varchar(15) NOT NULL DEFAULT 'none',
  `crystallizable` enum('true','false') NOT NULL DEFAULT 'false',
  `weight` smallint(4) NOT NULL DEFAULT 0,
  `soulshots` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `spiritshots` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `material` varchar(11) NOT NULL DEFAULT 'wood',
  `crystal_type` varchar(4) NOT NULL DEFAULT 'none',
  `p_dam` smallint(4) NOT NULL DEFAULT 0,
  `rnd_dam` smallint(3) NOT NULL DEFAULT 0,
  `weaponType` varchar(20) NOT NULL DEFAULT 'none',
  `critical` smallint(3) NOT NULL DEFAULT 0,
  `hit_modify` tinyint(2) NOT NULL DEFAULT 0,
  `avoid_modify` tinyint(2) NOT NULL DEFAULT 0,
  `shield_def` smallint(4) NOT NULL DEFAULT 0,
  `shield_def_rate` smallint(3) NOT NULL DEFAULT 0,
  `atk_speed` smallint(4) NOT NULL DEFAULT 0,
  `mp_consume` tinyint(1) NOT NULL DEFAULT 0,
  `m_dam` smallint(4) NOT NULL DEFAULT 0,
  `duration` mediumint(5) NOT NULL DEFAULT -1,
  `time` mediumint(5) NOT NULL DEFAULT -1,
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `crystal_count` smallint(4) unsigned NOT NULL DEFAULT 0,
  `sellable` enum('true','false') NOT NULL DEFAULT 'false',
  `dropable` enum('true','false') NOT NULL DEFAULT 'false',
  `destroyable` enum('true','false') NOT NULL DEFAULT 'false',
  `tradeable` enum('true','false') NOT NULL DEFAULT 'false',
  `depositable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_stackable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_questitem` enum('true','false') NOT NULL DEFAULT 'false',
  `change_weaponId` smallint(5) unsigned NOT NULL DEFAULT 0,
  `skill` varchar(70) NOT NULL DEFAULT '0-0;',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `knightarmour` (
  `item_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `name` varchar(120) NOT NULL DEFAULT '',
  `icon` varchar(50) NOT NULL DEFAULT '0',
  `bodypart` varchar(15) NOT NULL DEFAULT 'none',
  `crystallizable` enum('true','false') NOT NULL DEFAULT 'false',
  `armor_type` varchar(5) NOT NULL DEFAULT 'none',
  `weight` smallint(4) NOT NULL DEFAULT 0,
  `material` varchar(15) NOT NULL DEFAULT 'wood',
  `crystal_type` varchar(4) NOT NULL DEFAULT 'none',
  `avoid_modify` tinyint(2) NOT NULL DEFAULT 0,
  `duration` mediumint(5) NOT NULL DEFAULT -1,
  `time` mediumint(5) NOT NULL DEFAULT -1,
  `p_def` smallint(3) NOT NULL DEFAULT 0,
  `m_def` smallint(3) NOT NULL DEFAULT 0,
  `mp_bonus` smallint(3) NOT NULL DEFAULT 0,
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `crystal_count` smallint(4) unsigned NOT NULL DEFAULT 0,
  `sellable` enum('true','false') NOT NULL DEFAULT 'false',
  `dropable` enum('true','false') NOT NULL DEFAULT 'false',
  `destroyable` enum('true','false') NOT NULL DEFAULT 'false',
  `tradeable` enum('true','false') NOT NULL DEFAULT 'false',
  `depositable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_stackable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_questitem` enum('true','false') NOT NULL DEFAULT 'false',
  `skill` varchar(70) DEFAULT '0-0;',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `knightetcitem` (
  `item_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL DEFAULT '',
  `icon` varchar(50) NOT NULL DEFAULT '0',
  `crystallizable` enum('true','false') NOT NULL DEFAULT 'false',
  `weight` smallint(4) NOT NULL DEFAULT 0,
  `consume_type` varchar(9) NOT NULL DEFAULT 'normal',
  `material` varchar(11) NOT NULL DEFAULT 'wood',
  `crystal_type` varchar(4) NOT NULL DEFAULT 'none',
  `duration` mediumint(5) NOT NULL DEFAULT -1,
  `time` mediumint(5) NOT NULL DEFAULT -1,
  `price` int(10) unsigned NOT NULL DEFAULT 0,
  `crystal_count` smallint(4) unsigned NOT NULL DEFAULT 0,
  `sellable` enum('true','false') NOT NULL DEFAULT 'false',
  `dropable` enum('true','false') NOT NULL DEFAULT 'false',
  `destroyable` enum('true','false') NOT NULL DEFAULT 'false',
  `tradeable` enum('true','false') NOT NULL DEFAULT 'false',
  `depositable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_stackable` enum('true','false') NOT NULL DEFAULT 'false',
  `is_questitem` enum('true','false') NOT NULL DEFAULT 'false',
  `skill` varchar(70) NOT NULL DEFAULT '0-0;',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `knightquests` (
  `quest_id` int(4) NOT NULL DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  KEY `quest_id` (`quest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `knightquestrun` (
  `quest_id` int(4) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `target_id` int(11) NOT NULL DEFAULT 0,
  UNIQUE KEY `quest_id` (`quest_id`,`target_id`),
  KEY `qiest_id` (`quest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- one settings row is required (all columns have defaults, drop rates 1.00)
INSERT INTO `knightsettings` (`drop_chance_adena`) SELECT 1.00 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `knightsettings`);

-- ===== tables of this port, filled by Server Utilities -> Import =====
CREATE TABLE IF NOT EXISTS `knightnpc` (
  `id` INT PRIMARY KEY, `name` VARCHAR(100), `title` VARCHAR(100), `type` VARCHAR(30), `level` INT DEFAULT 1,
  `race` VARCHAR(30), `sex` VARCHAR(10), `exp` FLOAT DEFAULT 0, `sp` FLOAT DEFAULT 0,
  `hp` FLOAT DEFAULT 0, `mp` FLOAT DEFAULT 0, `patk` FLOAT DEFAULT 0, `matk` FLOAT DEFAULT 0,
  `pdef` FLOAT DEFAULT 0, `mdef` FLOAT DEFAULT 0, `atkspd` INT DEFAULT 0, `accuracy` FLOAT DEFAULT 0,
  `attackrange` INT DEFAULT 0, `walkspd` FLOAT DEFAULT 0, `runspd` FLOAT DEFAULT 0,
  `aggro` INT DEFAULT 0, `is_aggressive` INT DEFAULT 0, `rhand` INT DEFAULT 0,
  `lhand` INT DEFAULT 0, `matkspd` INT DEFAULT 0, `isundead` INT DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightnpcskills` (
  `npcid` INT, `skillid` INT, `level` INT, KEY `npcid` (`npcid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightdroplist` (
  `mobid` INT, `itemid` INT, `min` INT, `max` INT, `chance` FLOAT, `group_chance` FLOAT DEFAULT NULL,
  `sweep` INT DEFAULT 0, `category` INT DEFAULT NULL,
  KEY `mobid` (`mobid`), KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightskills` (
  `skill_id` INT PRIMARY KEY, `name` VARCHAR(150), `levels` INT DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightrecipe` (
  `rec_id` INT, `makes` INT DEFAULT 1, `item` INT, `qty` INT, KEY `rec_id` (`rec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightrecch` (
  `rec_name` VARCHAR(100), `rec_id` INT PRIMARY KEY, `rec_item` INT, `level` INT,
  `makes` INT DEFAULT 1, `chance` FLOAT, `multiplier` INT DEFAULT 1, `xml_id` INT
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightbuylist` (
  `shop_id` INT, `item_id` INT, `price` BIGINT DEFAULT NULL,
  KEY `shop_id` (`shop_id`), KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightspawnlist` (
  `npc_templateid` INT, `locx` INT, `locy` INT, `locz` INT, `count` INT DEFAULT 1,
  `loc_id` VARCHAR(100), `is_approximate` INT DEFAULT 1,
  KEY `npc_templateid` (`npc_templateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightspawnzone` (
  `zone` VARCHAR(100) PRIMARY KEY, `minZ` INT, `maxZ` INT, `points` TEXT
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- ===== DropCalc own tables (reconstructed) =====
CREATE TABLE IF NOT EXISTS `knightloc` (          -- towns / named locations for the maps
  `name` varchar(100) NOT NULL DEFAULT '',
  `x` int(11) NOT NULL DEFAULT 0,
  `y` int(11) NOT NULL DEFAULT 0,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knighttrust` (        -- trusted characters list
  `account_name` varchar(45) NOT NULL DEFAULT '',
  `char_name` varchar(35) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT 0,
  `race` varchar(20) NOT NULL DEFAULT '',
  `class` varchar(60) NOT NULL DEFAULT '',
  KEY `char_name` (`char_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightstats` (        -- online statistics; date=2147483647 holds the max-players record
  `date` int(11) NOT NULL DEFAULT 0,
  `hour` int(11) NOT NULL DEFAULT 0,
  `period` int(11) NOT NULL DEFAULT 0,
  `count` int(11) NOT NULL DEFAULT 0,
  `maxplayers` int(11) NOT NULL DEFAULT 0,
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightdungeon` (      -- dungeon maps (admin-maintained bounding boxes)
  `dungeon_name` varchar(100) NOT NULL DEFAULT '',
  `sub_name` varchar(100) NOT NULL DEFAULT '',
  `mapname` varchar(100) NOT NULL DEFAULT '',
  `mapx` int(11) NOT NULL DEFAULT 0,
  `mapy` int(11) NOT NULL DEFAULT 0,
  `xmin` int(11) NOT NULL DEFAULT 0,
  `xmax` int(11) NOT NULL DEFAULT 0,
  `ymin` int(11) NOT NULL DEFAULT 0,
  `ymax` int(11) NOT NULL DEFAULT 0,
  `zmin` int(11) NOT NULL DEFAULT 0,
  `zmax` int(11) NOT NULL DEFAULT 0,
  KEY `dungeon_name` (`dungeon_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightduplicate` (    -- maps duplicate item ids to the original id
  `item` int(11) NOT NULL DEFAULT 0,              -- 0 / 1: which item list the id belongs to
  `dupnum` int(11) NOT NULL DEFAULT 0,
  `orig` int(11) NOT NULL DEFAULT 0,
  KEY `dupnum` (`dupnum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightitemdesc` (     -- item descriptions per language
  `id` int(11) NOT NULL DEFAULT 0,
  `language` int(11) NOT NULL DEFAULT 1,
  `description` text,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightskillmod` (     -- skill description text substitutions
  `id` int(11) NOT NULL DEFAULT 0,
  `textident` varchar(100) NOT NULL DEFAULT '',
  `textfrom` varchar(255) NOT NULL DEFAULT '',
  `textto` varchar(255) NOT NULL DEFAULT '',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `knightsubclassmap` (  -- which classes may take which subclass
  `class` int(11) NOT NULL DEFAULT 0,
  `subclass` int(11) NOT NULL DEFAULT 0,
  KEY `class` (`class`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- ===== class names (race-prefixed, like the old class_list) =====
CREATE TABLE IF NOT EXISTS `knightclass` (
  `id` int(11) NOT NULL,
  `class_name` varchar(60) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT -1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
REPLACE INTO `knightclass` (`id`,`class_name`,`parent_id`) VALUES
(0,'H_Human Fighter',-1),
(1,'H_Warrior',0),
(2,'H_Gladiator',1),
(3,'H_Warlord',1),
(4,'H_Knight',0),
(5,'H_Paladin',4),
(6,'H_Dark Avenger',4),
(7,'H_Rogue',0),
(8,'H_Treasure Hunter',7),
(9,'H_Hawkeye',7),
(10,'H_Human Mystic',-1),
(11,'H_Human Wizard',10),
(12,'H_Sorcerer',11),
(13,'H_Necromancer',11),
(14,'H_Warlock',11),
(15,'H_Cleric',10),
(16,'H_Bishop',15),
(17,'H_Prophet',15),
(18,'E_Elven Fighter',-1),
(19,'E_Elven Knight',18),
(20,'E_Temple Knight',19),
(21,'E_Swordsinger',19),
(22,'E_Elven Scout',18),
(23,'E_Plainswalker',22),
(24,'E_Silver Ranger',22),
(25,'E_Elven Mystic',-1),
(26,'E_Elven Wizard',25),
(27,'E_Spellsinger',26),
(28,'E_Elemental Summoner',26),
(29,'E_Elven Oracle',25),
(30,'E_Elven Elder',29),
(31,'DE_Dark Fighter',-1),
(32,'DE_Palus Knight',31),
(33,'DE_Shillien Knight',32),
(34,'DE_Bladedancer',32),
(35,'DE_Assassin',31),
(36,'DE_Abyss Walker',35),
(37,'DE_Phantom Ranger',35),
(38,'DE_Dark Mystic',-1),
(39,'DE_Dark Wizard',38),
(40,'DE_Spellhowler',39),
(41,'DE_Phantom Summoner',39),
(42,'DE_Shillien Oracle',38),
(43,'DE_Shillien Elder',42),
(44,'O_Orc Fighter',-1),
(45,'O_Orc Raider',44),
(46,'O_Destroyer',45),
(47,'O_Orc Monk',44),
(48,'O_Tyrant',47),
(49,'O_Orc Mystic',-1),
(50,'O_Orc Shaman',49),
(51,'O_Overlord',50),
(52,'O_Warcryer',50),
(53,'D_Dwarven Fighter',-1),
(54,'D_Scavenger',53),
(55,'D_Bounty Hunter',54),
(56,'D_Artisan',53),
(57,'D_Warsmith',56),
(88,'H_Duelist',2),
(89,'H_Dreadnought',3),
(90,'H_Phoenix Knight',5),
(91,'H_Hell Knight',6),
(92,'H_Sagittarius',9),
(93,'H_Adventurer',8),
(94,'H_Archmage',12),
(95,'H_Soultaker',13),
(96,'H_Arcana Lord',14),
(97,'H_Cardinal',16),
(98,'H_Hierophant',17),
(99,'E_Eva''s Templar',20),
(100,'E_Sword Muse',21),
(101,'E_Wind Rider',23),
(102,'E_Moonlight Sentinel',24),
(103,'E_Mystic Muse',27),
(104,'E_Elemental Master',28),
(105,'E_Eva''s Saint',30),
(106,'DE_Shillien Templar',33),
(107,'DE_Spectral Dancer',34),
(108,'DE_Ghost Hunter',36),
(109,'DE_Ghost Sentinel',37),
(110,'DE_Storm Screamer',40),
(111,'DE_Spectral Master',41),
(112,'DE_Shillien Saint',43),
(113,'O_Titan',46),
(114,'O_Grand Khavatari',48),
(115,'O_Dominator',51),
(116,'O_Doomcryer',52),
(117,'D_Fortune Seeker',55),
(118,'D_Maestro',57),
(123,'K_Male Soldier',-1),
(124,'K_Female Soldier',-1),
(125,'K_Trooper',123),
(126,'K_Warder',124),
(127,'K_Berserker',125),
(128,'K_Male Soul Breaker',125),
(129,'K_Female Soul Breaker',126),
(130,'K_Arbalester',126),
(131,'K_Doombringer',127),
(132,'K_Male Soul Hound',128),
(133,'K_Female Soul Hound',129),
(134,'K_Trickster',130),
(135,'K_Inspector',125),
(136,'K_Judicator',135);
