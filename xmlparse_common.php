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
function xip_rangeName(int $ia): string
{
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
