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
