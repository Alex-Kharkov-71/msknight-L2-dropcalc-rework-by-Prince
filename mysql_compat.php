<?php
/*
mysql_compat.php
-----------------
Совместимость со старым расширением ext/mysql (удалено в PHP7+) поверх mysqli.

Зачем:
Код Drop Calc написан для древнего mysql_* API и вызывает mysql_connect(),
mysql_query(), mysql_result() и т.д. по всему проекту (~3600 обращений).
Переписывать каждый вызов вручную на mysqli_* — слишком большой риск для
такого объёма кода. Вместо этого здесь воссоздан привычный API поверх mysqli,
так что сам код почти не меняется.

Важные особенности поведения, которые сохранены специально:
1. Старое расширение хранило "последнее соединение" и использовало его,
   если вызов был без явного $link (например mysql_error() без аргументов
   встречается в проекте 491 раз). Это поведение воспроизведено через
   $GLOBALS['__mysql_compat_last_link'].
2. По умолчанию с PHP 8.1 mysqli при ошибках выбрасывает исключения
   (mysqli_sql_exception), а не просто возвращает false. Старый код этого
   не ожидает и обрабатывает ошибки через if (!$result) + mysql_error().
   Поэтому здесь принудительно включен старый режим (MYSQLI_REPORT_OFF).
3. mysql_result() в оригинале не требовал предварительного mysql_fetch_*,
   а сразу прыгал на нужную строку — это воспроизведено через
   mysqli_data_seek().

Подключается один раз из config.php (require_once), т.к. config.php
подключается практически из каждого файла проекта (иногда даже дважды в
одной цепочке include, поэтому здесь тоже стоит защита от повторного
объявления функций).
*/

if (!function_exists('mysql_connect')) {

// Больше не бросаем исключения на ошибках соединения/запроса — старый код
// ожидает false + mysql_error(), а не try/catch.
mysqli_report(MYSQLI_REPORT_OFF);

if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', 1);
if (!defined('MYSQL_NUM'))   define('MYSQL_NUM', 2);
if (!defined('MYSQL_BOTH'))  define('MYSQL_BOTH', 3);

$GLOBALS['__mysql_compat_last_link'] = null;
$GLOBALS['__mysql_compat_last_connect_error'] = '';

function __mysql_compat_resolve_link($link = null)
{
    if ($link instanceof mysqli) {
        return $link;
    }
    return $GLOBALS['__mysql_compat_last_link'];
}

function mysql_connect($host = null, $user = null, $password = null)
{
    $link = @mysqli_connect($host, $user, $password);
    if ($link === false || $link === null) {
        $GLOBALS['__mysql_compat_last_connect_error'] = mysqli_connect_error();
        return false;
    }
    $GLOBALS['__mysql_compat_last_link'] = $link;
    $GLOBALS['__mysql_compat_last_connect_error'] = '';
    return $link;
}

function mysql_pconnect($host = null, $user = null, $password = null)
{
    // Постоянные соединения в mysqli делаются через префикс "p:" у хоста.
    if ($host !== null && strpos($host, 'p:') !== 0) {
        $host = 'p:' . $host;
    }
    return mysql_connect($host, $user, $password);
}

function mysql_select_db($dbname, $link = null)
{
    $link = __mysql_compat_resolve_link($link);
    if (!$link) return false;
    return @mysqli_select_db($link, $dbname);
}

function mysql_query($query, $link = null)
{
    $link = __mysql_compat_resolve_link($link);
    if (!$link) return false;
    return @mysqli_query($link, $query);
}

function mysql_error($link = null)
{
    $resolved = __mysql_compat_resolve_link($link);
    if ($resolved instanceof mysqli) {
        return mysqli_error($resolved);
    }
    return $GLOBALS['__mysql_compat_last_connect_error'];
}

function mysql_errno($link = null)
{
    $resolved = __mysql_compat_resolve_link($link);
    if ($resolved instanceof mysqli) {
        return mysqli_errno($resolved);
    }
    return mysqli_connect_errno();
}

function mysql_num_rows($result)
{
    if (!($result instanceof mysqli_result)) return false;
    return mysqli_num_rows($result);
}

function mysql_num_fields($result)
{
    if (!($result instanceof mysqli_result)) return false;
    return mysqli_num_fields($result);
}

function mysql_fetch_assoc($result)
{
    if (!($result instanceof mysqli_result)) return false;
    return mysqli_fetch_assoc($result);
}

function mysql_fetch_array($result, $type = MYSQL_BOTH)
{
    if (!($result instanceof mysqli_result)) return false;
    $mode = MYSQLI_BOTH;
    if ($type === MYSQL_ASSOC) $mode = MYSQLI_ASSOC;
    elseif ($type === MYSQL_NUM) $mode = MYSQLI_NUM;
    return mysqli_fetch_array($result, $mode);
}

function mysql_fetch_row($result)
{
    if (!($result instanceof mysqli_result)) return false;
    return mysqli_fetch_row($result);
}

function mysql_fetch_object($result)
{
    if (!($result instanceof mysqli_result)) return false;
    return mysqli_fetch_object($result);
}

function mysql_data_seek($result, $row)
{
    if (!($result instanceof mysqli_result)) return false;
    return mysqli_data_seek($result, $row);
}

function mysql_result($result, $row, $field = 0)
{
    if (!($result instanceof mysqli_result)) return false;
    if (!mysqli_data_seek($result, $row)) return false;
    $r = mysqli_fetch_array($result, MYSQLI_BOTH);
    if ($r === null || $r === false) return false;
    return array_key_exists($field, $r) ? $r[$field] : null;
}

function mysql_close($link = null)
{
    $link = __mysql_compat_resolve_link($link);
    if (!$link) return false;
    return mysqli_close($link);
}

function mysql_real_escape_string($str, $link = null)
{
    $resolved = __mysql_compat_resolve_link($link);
    if ($resolved instanceof mysqli) {
        return mysqli_real_escape_string($resolved, (string) $str);
    }
    // До установления соединения безопасного экранирования через mysqli
    // не сделать — используем addslashes как разумный запасной вариант
    // (проект всегда работает в utf8, поэтому классический GBK-обход
    // addslashes здесь не применим).
    return addslashes((string) $str);
}

function mysql_insert_id($link = null)
{
    $link = __mysql_compat_resolve_link($link);
    if (!$link) return false;
    return mysqli_insert_id($link);
}

function mysql_affected_rows($link = null)
{
    $link = __mysql_compat_resolve_link($link);
    if (!$link) return false;
    return mysqli_affected_rows($link);
}

function mysql_free_result($result)
{
    if (!($result instanceof mysqli_result)) return false;
    mysqli_free_result($result);
    return true;
}

} // function_exists('mysql_connect')
