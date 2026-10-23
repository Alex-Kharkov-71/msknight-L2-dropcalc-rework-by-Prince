-- DropCalc: installation of the LOGIN database (once). Usage: mysql -u root -p YOUR_LOGIN_DB < sql/install_login_db.sql
-- knightdrop holds DropCalc users (token, access level, e-mail); it is shared by all game servers of this login server.
-- The DropCalc admin rights follow accounts.accessLevel of the game account.

CREATE TABLE IF NOT EXISTS `knightdrop` (
  `name` varchar(45) NOT NULL DEFAULT '',
  `lastaction` int(11) DEFAULT NULL,
  `token` varchar(10) DEFAULT NULL,
  `mapaccess` int(20) DEFAULT 0,
  `recipeaccess` int(20) DEFAULT 0,
  `gdaccess` int(20) DEFAULT 0,
  `boxingok` int(1) DEFAULT NULL,
  `warnlevel` int(1) DEFAULT NULL,
  `characcess` int(20) DEFAULT 0,
  `lastheard` int(20) DEFAULT 0,
  `ipaddr` varchar(30) DEFAULT NULL,
  `access_level` int(11) DEFAULT 0,
  `email` varchar(50) DEFAULT '',
  `request_time` int(20) DEFAULT 0,
  `request_key` varchar(45) DEFAULT NULL,
  `emailcheck` int(1) NOT NULL DEFAULT 0,
  `password` varchar(45) DEFAULT NULL,
  `verified` int(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`name`),
  UNIQUE KEY `id` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `knightipok` (
  `ip_addr` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`ip_addr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
