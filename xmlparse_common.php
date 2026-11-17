<?php
/*
xmlparse_common.php
--------------------
Общие функции чтения нового (L2JMobius) XML-формата датапака.
Подключается из importitems.php / importnpc.php / importskill.php /
importrec.php / importbuylists.php / importspawns.php.

Старый парсер читал XML построчно и резал строки по кавычкам regex'ом -
для вложенного формата (<set>/<stats>/<dropLists>) это не работает вообще,
поэтому тут настоящий XML-парсер (SimpleXML).
*/

/** @return array<string,string> имя-set -> val, из <set name=".." val=".."/> */
function xip_readSets(SimpleXMLElement $node): array
{
    $out = [];
    foreach ($node->set ?? [] as $set) {
        $out[(string) $set['name']] = (string) $set['val'];
    }
    return $out;
}

/** @return array<string,string> тип-стата -> значение, из <stats><stat type=".."></stat></stats> */
function xip_readStats(SimpleXMLElement $node): array
{
    $out = [];
    if (isset($node->stats)) {
        foreach ($node->stats->stat as $stat) {
            $out[(string) $stat['type']] = (string) $stat;
        }
    }
    return $out;
}

function xip_loadXml(string $path): ?SimpleXMLElement
{
    if (!file_exists($path)) return null;
    $prev = libxml_use_internal_errors(true);
    $xml = simplexml_load_file($path);
    libxml_use_internal_errors($prev);
    return $xml === false ? null : $xml;
}

function xip_listXmlFiles(string $dir): array
{
    if (!is_dir($dir)) return [];
    $files = [];
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if ($f->isFile() && strtolower($f->getExtension()) === 'xml') {
            $files[] = $f->getPathname();
        }
    }
    sort($files);
    return $files;
}

/** Собрать "NNN00-NNN99" из номера диапазона $ia, как в оригинальном коде. */
function xip_rangeName($ia): string
{
	$ia = (int) $ia;
    if ($ia == 0) return "000";
    if ($ia < 10) return "00" . $ia;
    if ($ia < 100) return "0" . $ia;
    return "" . $ia;
}

function xip_escNum($v, $default = 0)
{
    if ($v === null || $v === '') return $default;
    return is_numeric($v) ? $v + 0 : $default;
}

function xip_bool2enum($sets, $key, $default)
{
    if (!isset($sets[$key])) return $default;
    return $sets[$key] === 'true' ? 'true' : 'false';
}

// ---- Import statistics (shared by all importers) -------------------------------------------
// xip_q() runs an insert/replace and counts successes/errors per table.
// xip_stat_flush() adds the counters to knightimportstat (so multi-page imports accumulate),
// xip_stat_report() prints the totals. xip_stat_reset() starts a new run.
function xip_q($sql, $con)
{
    global $xip_ok, $xip_err, $xip_msg, $xip_shown;
    if (!isset($xip_ok)) { $xip_ok = array(); $xip_err = array(); $xip_msg = array(); $xip_shown = 0; }
    $tbl = 'other';
    if (preg_match('/^\s*(?:insert|replace)\s+(?:ignore\s+)?into\s+`?(\w+)`?/i', $sql, $m)) { $tbl = $m[1]; }
    $r = mysql_query($sql, $con);
    if ($r) {
        $xip_ok[$tbl] = ($xip_ok[$tbl] ?? 0) + 1;
    } else {
        $xip_err[$tbl] = ($xip_err[$tbl] ?? 0) + 1;
        $text = (string) mysql_error() . ' | ' . substr(preg_replace('/\s+/', ' ', $sql), 0, 140);
        if (count($xip_msg) < 10) { $xip_msg[] = $text; }
        if ($xip_shown < 5) { $xip_shown++; echo '<p style="color:red">NOT imported: ' . htmlspecialchars($text) . '</p>'; }
    }
    return $r;
}

function xip_stat_table($con)
{
    mysql_query("CREATE TABLE IF NOT EXISTS `knightimportstat` (`kind` VARCHAR(20) NOT NULL, `tbl` VARCHAR(40) NOT NULL, `ok` INT NOT NULL DEFAULT 0, `err` INT NOT NULL DEFAULT 0, `msgs` TEXT, PRIMARY KEY (`kind`,`tbl`))", $con);
}

function xip_stat_reset($con, $kind)
{
    global $xip_ok, $xip_err, $xip_msg, $xip_shown;
    $xip_ok = array(); $xip_err = array(); $xip_msg = array(); $xip_shown = 0;
    xip_stat_table($con);
    mysql_query("delete from knightimportstat where kind = '" . mysql_real_escape_string($kind, $con) . "'", $con);
}

function xip_stat_flush($con, $kind)
{
    global $xip_ok, $xip_err, $xip_msg;
    xip_stat_table($con);
    $k = mysql_real_escape_string($kind, $con);
    $tbls = array_unique(array_merge(array_keys((array) $xip_ok), array_keys((array) $xip_err)));
    $msgs = mysql_real_escape_string(implode("\n", (array) $xip_msg), $con);
    foreach ($tbls as $t) {
        $ok = (int) ($xip_ok[$t] ?? 0); $er = (int) ($xip_err[$t] ?? 0);
        $tt = mysql_real_escape_string($t, $con);
        mysql_query("insert into knightimportstat (kind,tbl,ok,err,msgs) values ('$k','$tt',$ok,$er,'$msgs')
            on duplicate key update ok = ok + $ok, err = err + $er, msgs = if(char_length(coalesce(msgs,'')) < 1500, concat_ws('\n', nullif(msgs,''), nullif('$msgs','')), msgs)", $con);
    }
    $xip_ok = array(); $xip_err = array(); $xip_msg = array(); // already stored; avoid double counting on later flush
}

function xip_stat_report($con, $kind)
{
    xip_stat_table($con);
    $res = mysql_query("select tbl, ok, err, msgs from knightimportstat where kind = '" . mysql_real_escape_string($kind, $con) . "' order by tbl", $con);
    echo '<table border="1" cellpadding="4" cellspacing="0"><tr><th>Таблица</th><th>Импортировано</th><th>Ошибок</th></tr>';
    $tot_ok = 0; $tot_err = 0; $all = '';
    while ($res && ($row = mysql_fetch_assoc($res))) {
        $tot_ok += $row['ok']; $tot_err += $row['err'];
        echo '<tr><td>' . htmlspecialchars($row['tbl']) . '</td><td align="right">' . (int) $row['ok'] . '</td><td align="right"' . ($row['err'] ? ' style="color:red"' : '') . '>' . (int) $row['err'] . '</td></tr>';
        if ($row['msgs'] !== null && $row['msgs'] !== '') { $all .= $row['msgs'] . "\n"; }
    }
    echo '<tr><th>Итого</th><th align="right">' . $tot_ok . '</th><th align="right"' . ($tot_err ? ' style="color:red"' : '') . '>' . $tot_err . '</th></tr></table>';
    if ($tot_err && trim($all) !== '') {
        echo '<p style="color:red">Первые ошибки:</p><pre>' . htmlspecialchars(trim($all)) . '</pre>';
    } elseif (!$tot_err) {
        echo '<p style="color:green">Ошибок нет.</p>';
    }
}

// Item description = the XML comment right after <item ...> (Mobius datapack) plus the item's own
// skills (<skills><skill id level type_chance type/> <!-- name -->). Returns id => text.
function dc_item_comments($path)
{
    $txt = @file_get_contents($path);
    $out = array();
    if ($txt === false) { return $out; }
    $when = array('ON_MAGIC_SKILL' => 'on casting a magic skill', 'ON_CRIT' => 'on critical hit', 'ON_ATTACK' => 'on attack',
        'ON_HIT' => 'on hit', 'ON_ATTACK_SPECIAL' => 'on special attack', 'ON_MAGIC_FINISH' => 'on magic finish', 'ON_EXIT' => 'on unequip', 'ON_START' => 'on equip');
    if (!preg_match_all('/<item\s+id="(\d+)"[^>]*>(.*?)<\/item>/s', $txt, $blocks, PREG_SET_ORDER)) { return $out; }
    foreach ($blocks as $b) {
        $t = '';
        if (preg_match('/^\s*<!--(.*?)-->/s', $b[2], $c)) {
            $t = trim(preg_replace('/\s+/', ' ', $c[1]));
            $t = preg_replace('/<([^<>]*)>/', '[$1]', $t);   // pages print descriptions as raw HTML
        }
        if (preg_match_all('/<skill\s+([^>]*?)\/>\s*(?:<!--\s*(.*?)\s*-->)?/s', $b[2], $sk, PREG_SET_ORDER)) {
            foreach ($sk as $k) {
                if (!preg_match('/\bid="(\d+)"/', $k[1], $a) || !preg_match('/\blevel="(\d+)"/', $k[1], $l)) { continue; }
                if (strpos($b[2], '<skills>') === false) { continue; }
                // only skills inside <skills> ... </skills> (not the <set>/<stats> parts)
                if (!preg_match('/<skills>.*?<skill\s+[^>]*\bid="' . $a[1] . '"/s', $b[2])) { continue; }
                $name = isset($k[2]) && $k[2] !== '' ? $k[2] : 'Skill #' . $a[1];
                $line = $name . ' Lv.' . $l[1];
                $extra = array();
                if (preg_match('/type_chance="([\d.]+)"/', $k[1], $q)) { $extra[] = $q[1] . '% chance'; }
                if (preg_match('/\btype="(\w+)"/', $k[1], $q)) { $extra[] = isset($when[$q[1]]) ? $when[$q[1]] : strtolower(str_replace('_', ' ', $q[1])); }
                if (count($extra)) { $line .= ' (' . implode(', ', $extra) . ')'; }
                $t .= ($t === '' ? '' : ' ') . 'Skill: ' . $line . '.';
            }
        }
        if ($t !== '') { $out[(int) $b[1]] = $t; }
    }
    return $out;
}
