# Drop Calc for L2JMobius (High Five)

**English** · [Русский](README.ru.md)

A web info site and GM panel for Lineage 2 servers: drop / spoil calculator, item, NPC, skill, recipe, shop and spawn
reference, maps, player / clan / castle pages and GM tools. This is a port of
Michelle Knight's Drop Calc (originally
PHP 5 + l2jserver) to **PHP 8** and **L2JMobius CT 2.6 High Five**.

> Developed and tested on **L2JMobius CT_2.6_HighFive only**. Other L2JMobius branches and other server packs
> (l2jserver, aCis, ...) are untested and will most likely need changes.
> Try it on a **test server first**: many GM tools write straight to the game database.

## Features

- Drop / spoil / quest-item calculator with the real two-step chances of the XML datapack
- Reference pages: items, NPCs (drops, skills, spawns on the map), skills, recipes, shops, locations, quests
- Player, clan, castle, clan hall, hero, pet, raid boss pages; online list; statistics; top lists
- GM tools: accounts, characters, items, jail / ban, notes, logs, announcements (those that need a telnet console
  are switched off: L2JMobius has none)
- Several game servers with one shared login server; 9 interface languages; 3 skins
- Built-in installer and **verified importers** that read the datapack XML and report "imported / errors"
- Optional public read-only mirror ([docs/PUBLIC_MIRROR_RU.md](docs/PUBLIC_MIRROR_RU.md), Russian)

## Requirements

- PHP 8.1+ with `mysqli`, `xml` (SimpleXML), `gd`, `mbstring`
- MariaDB / MySQL: the game database(s) and the login database of your server
- The L2JMobius `data` folder readable by the web server (same machine or a mounted copy)
- nginx or Apache

## Installation

1. Copy the files to the web root.
2. `cp config.php.example config.php` (it is git-ignored) and fill in `$gameservers` and `$dblog_*`.
   Position 6 of a server entry is the folder that **contains `data/`**, with a trailing slash
   (for Mobius `.../dist/game/`). Leave the telnet fields (9-12) empty.
3. Put your game account into `$install_admins`, e.g. `array("youraccount")`. Open the site, log in with that
   account (password from the login server's `accounts`) and use **Install tables** in the menu
   (next to *Server tools*), or open `install.php`.
   Install the login database first, then each game server (the first server also holds the shared interface settings).
   The same scripts can be run by hand: `sql/install_login_db.sql`, `sql/install_game_db.sql`.
4. Log in as admin → **Server tools → Import** and run, one after another: items, NPCs (with drops and NPC
   skills), skills, recipes, shops, spawns, locations, quests, accounts. Every importer ends with a table of
   imported / failed rows. Do not close the window while a long import is running.

### Access levels

There is no separate "DropCalc admin" role: the level is the game account's `accessLevel` on the **login server**
and only counts for a **verified** account (`knightdrop.verified = 1`). Defaults of a fresh install:

| Setting | Default | Meaning |
|---|---|---|
| `sec_inc_gmlevel` | 99 | minimum level for GM pages |
| `sec_inc_admin` | 100 | minimum level for admin pages (100 = full access in the stock `AccessLevels.xml`) |
| all GM tool levels | 100 | give / take items, skills, kick, announce, shops, ... |

Change them on the Settings page if your levels differ. GM level must stay below admin level. Accounts listed in
`$install_admins` always get full access; empty the list once real admins exist (see `sql/make_admin.sql`).
For a database that is already installed:

```sql
UPDATE knightsettings SET sec_inc_gmlevel = 99, sec_inc_admin = 100, sec_giveandtake = 100, sec_takeskill = 100,
  sec_chatto = 100, sec_enchant = 100, adjust_anounce = 100, kick_player = 100, adjust_trust = 100,
  reboot_server = 100, adjust_shop = 100, adjust_drops = 100, sec_adj_notes = 100;
```

### Other settings worth knowing

- `db_tokenexp`: session timeout in minutes (30-600, default 60)
- `show_online`: server status lamps in the menu (Off / Front only / Always after login)
- `$status_timeout`, `$logsvr_status_host`: the lamps are a plain TCP connect to the game / login port
- `$telnet_enabled = false`: L2JMobius has no telnet console; telnet actions fail at once with a clear message

## What changed compared with the original

| Old (l2jserver) | Now (L2JMobius) |
|---|---|
| `mysql_*` | compatibility layer `mysql_compat.php` over `mysqli` |
| `npc`, `droplist`, `armor`, `weapon`, `etcitem` in the game DB | datapack XML imported into `knightnpc`, `knightdroplist`, `knightweapon`, `knightarmour`, `knightetcitem` |
| `merchant_buylists` | `knightbuylist` (shops are XML files, view only) |
| `spawnlist` | `knightspawnlist` / `knightspawnzone` (zones are polygons, single points are supported too) |
| quests in Jython | quests parsed from `data/scripts/quests/Q*/Q*.java` |

Characters, clans, castles, items, pets, heroes, raid bosses etc. are still read live from the game database.

Not available on Mobius (and therefore removed or read-only): day / night spawns, boss minions table, shop and drop
editing (they live in XML), NPC creation, telnet-based actions. Spawns are drawn as zones, not exact points.

## Documentation

- [docs/PUBLIC_MIRROR_RU.md](docs/PUBLIC_MIRROR_RU.md): hourly read-only copy of the databases to a public server (Russian)
- [docs/original-readme.txt](docs/original-readme.txt): the original readme of the PHP 5 version
- `scripts/sync-to-public.sh`, `scripts/sync.conf.example`: the copy script

## Credits and license

Original Drop Calc by **Michelle Knight** (2006), distributed under the GNU General Public License: use and
distribute freely, keep the headers intact, make no charge, no warranty. PHP 8 / L2JMobius port by
[Alex-Kharkov-71](https://github.com/Alex-Kharkov-71). Use at your own risk.
