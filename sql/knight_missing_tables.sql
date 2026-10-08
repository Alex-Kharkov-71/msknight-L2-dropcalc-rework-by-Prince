-- DropCalc own tables that the original install script created.
-- That script is not part of the source archive, so the schemas below are
-- RECONSTRUCTED from how the PHP code uses these tables (columns read /
-- written, comparison values). If you already have the originals, keep
-- yours - everything here is CREATE TABLE IF NOT EXISTS.
--
-- Run this in the GAME database (the one in $gameservers[n][2]).
-- Not included here, because they already exist in a standard install or are
-- created by Server Utilities -> Import: knightweapon, knightarmour,
-- knightetcitem, knightquests, knightquestrun, knightsettings, knightnpc,
-- knightnpcskills, knightdroplist, knightskills, knightrecipe, knightrecch,
-- knightbuylist, knightspawnlist, knightspawnzone.
-- knightdrop and knightipok live in the LOGIN database and already exist.

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
