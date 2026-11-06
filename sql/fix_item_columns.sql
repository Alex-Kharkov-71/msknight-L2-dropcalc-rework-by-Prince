-- For databases created before this fix: widen columns that were too narrow (material 'scale_of_dragon' etc.).
-- Run on each GAME database, then re-run Import items in Server Utilities.
ALTER TABLE knightweapon  MODIFY material varchar(32) NOT NULL DEFAULT 'wood', MODIFY crystal_type varchar(8) NOT NULL DEFAULT 'none', MODIFY bodypart varchar(32) NOT NULL DEFAULT 'none';
ALTER TABLE knightarmour  MODIFY material varchar(32) NOT NULL DEFAULT 'wood', MODIFY crystal_type varchar(8) NOT NULL DEFAULT 'none', MODIFY bodypart varchar(32) NOT NULL DEFAULT 'none', MODIFY armor_type varchar(16) NOT NULL DEFAULT 'none';
ALTER TABLE knightetcitem MODIFY material varchar(32) NOT NULL DEFAULT 'wood', MODIFY crystal_type varchar(8) NOT NULL DEFAULT 'none', MODIFY consume_type varchar(16) NOT NULL DEFAULT 'normal';
