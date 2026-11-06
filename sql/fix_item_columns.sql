-- For databases created before this fix: widen columns that were too narrow (material 'scale_of_dragon' etc.).
-- Run on each GAME database, then re-run Import items in Server Utilities.
ALTER TABLE knightweapon  MODIFY material varchar(32) NOT NULL DEFAULT 'wood', MODIFY crystal_type varchar(8) NOT NULL DEFAULT 'none', MODIFY bodypart varchar(32) NOT NULL DEFAULT 'none';
ALTER TABLE knightarmour  MODIFY material varchar(32) NOT NULL DEFAULT 'wood', MODIFY crystal_type varchar(8) NOT NULL DEFAULT 'none', MODIFY bodypart varchar(32) NOT NULL DEFAULT 'none', MODIFY armor_type varchar(16) NOT NULL DEFAULT 'none';
ALTER TABLE knightetcitem MODIFY material varchar(32) NOT NULL DEFAULT 'wood', MODIFY crystal_type varchar(8) NOT NULL DEFAULT 'none', MODIFY consume_type varchar(16) NOT NULL DEFAULT 'normal';
ALTER TABLE knightweapon  MODIFY name varchar(255) NOT NULL DEFAULT '0', MODIFY icon varchar(100) NOT NULL DEFAULT '0', MODIFY skill varchar(255) NOT NULL DEFAULT '0-0;', MODIFY weaponType varchar(32) NOT NULL DEFAULT 'none';
ALTER TABLE knightarmour  MODIFY name varchar(255) NOT NULL DEFAULT '', MODIFY icon varchar(100) NOT NULL DEFAULT '0', MODIFY skill varchar(255) DEFAULT '0-0;';
ALTER TABLE knightetcitem MODIFY name varchar(255) NOT NULL DEFAULT '', MODIFY icon varchar(100) NOT NULL DEFAULT '0', MODIFY skill varchar(255) NOT NULL DEFAULT '0-0;';
-- NPC tables
ALTER TABLE knightnpc MODIFY name varchar(255), MODIFY title varchar(255), MODIFY type varchar(64), MODIFY race varchar(64), MODIFY sex varchar(16);

-- Skin selection: allow users to choose a skin, do not force the server default (run on each GAME database)
UPDATE knightsettings SET user_change_skin = 1, force_default_skin = 0;
