-- Make a DropCalc admin. Two short steps, in two different databases.
-- DropCalc has no separate admin role: the level is the game account's accessLevel on the LOGIN
-- server, and it only counts when the account is "verified". So an admin is a game GM account
-- that you verify here and whose level reaches sec_inc_admin.

-- ===== STEP 1: LOGIN database =====
SET @admin = 'YOUR_ACCOUNT_LOGIN';                 -- <-- put the login name here

SELECT login, accessLevel FROM accounts WHERE login = @admin;
-- accessLevel must be > 0. If it is 0 the account is not a game GM: raising it here makes that
-- person a GM in the game as well (UPDATE accounts SET accessLevel = 100 WHERE login = @admin;).

-- The account normally has a knightdrop row from the install; create it if it does not.
INSERT INTO knightdrop (name, access_level)
SELECT login, accessLevel FROM accounts WHERE login = @admin
AND NOT EXISTS (SELECT 1 FROM knightdrop WHERE name = @admin);

UPDATE knightdrop
SET verified = 1,
    access_level = (SELECT accessLevel FROM accounts WHERE login = @admin)
WHERE name = @admin;

-- ===== STEP 2: GAME database (once per game server database) =====
-- Thresholds: GM level and admin level, in accessLevel units. Use the level printed above for
-- sec_inc_admin (every verified account at or above it becomes a DropCalc admin).
UPDATE knightsettings SET sec_inc_gmlevel = 1, sec_inc_admin = 100;   -- <-- adjust 100
