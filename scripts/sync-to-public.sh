#!/bin/bash
# Copies the home databases (login + game servers) to the public MariaDB, started from the HOME server.
# Loads into a staging database, anonymises/re-hashes, then swaps tables atomically (RENAME TABLE).
# Per-site DropCalc tables (knightdrop, knightipok, knightsettings, knightimportstat) are NOT copied.
# Usage: sync-to-public.sh [/etc/dropcalc-sync.conf]   (cron: 0 * * * *)
set -u
CONF="${1:-/etc/dropcalc-sync.conf}"
# shellcheck disable=SC1090
. "$CONF"
# conf must define: LOCAL_CNF REMOTE_CNF REMOTE_HOST REMOTE_PORT PEPPER LOGIN_PAIR GAME_PAIRS (array) [EXCLUDE (array of db.table)]
exec 9>/var/lock/dropcalc-sync.lock
flock -n 9 || { echo "sync already running"; exit 1; }

L=(mysql --defaults-extra-file="$LOCAL_CNF" -N -B)
R=(mysql --defaults-extra-file="$REMOTE_CNF" -h "$REMOTE_HOST" -P "${REMOTE_PORT:-3306}" --max_allowed_packet=256M)
SKIP="knightdrop knightipok knightsettings knightimportstat"

sync_db() {            # $1 = home db, $2 = public db, $3 = "login" for the accounts db
  local src="$1" pub="$2" kind="${3:-game}" stage old t ign=()
  stage="${pub}__new"; old="${pub}__old"
  echo "[$(date +%T)] $src -> $pub"
  for t in $SKIP; do ign+=(--ignore-table="$src.$t"); done
  for t in "${EXCLUDE[@]:-}"; do [ -n "$t" ] && ign+=(--ignore-table="$t"); done
  "${R[@]}" -e "DROP DATABASE IF EXISTS \`$stage\`; DROP DATABASE IF EXISTS \`$old\`; CREATE DATABASE \`$stage\` CHARACTER SET utf8mb4; CREATE DATABASE IF NOT EXISTS \`$pub\` CHARACTER SET utf8mb4;" || return 1
  mysqldump --defaults-extra-file="$LOCAL_CNF" --single-transaction --quick --skip-lock-tables \
      --no-tablespaces --skip-add-locks "${ign[@]}" "$src" | "${R[@]}" "$stage" || { echo "dump/load FAILED for $src"; return 1; }
  if [ "$kind" = "login" ]; then
    # re-hash passwords with the secret, drop personal data (e-mail, IPs)
    "${R[@]}" --force "$stage" -e "
      UPDATE accounts SET password = TO_BASE64(UNHEX(SHA2(CONCAT('$PEPPER', password), 256))),
             email = NULL, lastIP = NULL, userIP = NULL, pcIp = NULL, hop1 = NULL, hop2 = NULL, hop3 = NULL, hop4 = NULL;
      UPDATE account_login_history SET ip = '';
      TRUNCATE TABLE account_data;" 2>/dev/null
  fi
  # atomic swap of all tables in one statement
  local stmt="" live
  live=$("${R[@]}" -N -B -e "SELECT table_name FROM information_schema.tables WHERE table_schema='$pub'")
  for t in $("${R[@]}" -N -B -e "SELECT table_name FROM information_schema.tables WHERE table_schema='$stage'"); do
    if grep -qx "$t" <<<"$live"; then stmt+="\`$pub\`.\`$t\` TO \`$old\`.\`$t\`, "; fi
    stmt+="\`$stage\`.\`$t\` TO \`$pub\`.\`$t\`, "
  done
  [ -z "$stmt" ] && { echo "nothing loaded for $src"; return 1; }
  "${R[@]}" -e "CREATE DATABASE IF NOT EXISTS \`$old\`; RENAME TABLE ${stmt%, }; DROP DATABASE \`$stage\`; DROP DATABASE \`$old\`;" || return 1
  echo "[$(date +%T)] $pub updated"
}

rc=0
IFS=: read -r s p <<<"$LOGIN_PAIR";  sync_db "$s" "$p" login || rc=1
for pair in "${GAME_PAIRS[@]}"; do IFS=: read -r s p <<<"$pair"; sync_db "$s" "$p" game || rc=1; done
exit $rc
