<?php
/**
 * MyWords for XOOPS
 *
 * Copyright © 2017 Eduardo Cortés http://www.eduardocortes.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Eduardo Cortés (http://www.eduardocortes.mx)
 * @license      GNU GPL 2
 * @package      mywords
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.eduardocortes.mx
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';

$path = parse_url(str_replace(XOOPS_URL, '', RMUris::current_url()));
//$request = str_replace(XOOPS_URL, '', RMUris::current_url());
$request = rtrim($path['path'], '/') . (isset($path['query']) ? '/' . $path['query'] : '');
$request .= '' != isset($path['anchor']) ? '#' . $path['anchor'] : '';
$request = str_replace('/modules/mywords/', '', $request);

if ($xoopsModuleConfig['permalinks'] > 1 && '/' != $xoopsModuleConfig['basepath'] && 'index.php' != $request) {
    $request = str_replace(rtrim($xoopsModuleConfig['basepath'], '/') . '/', '', rtrim($request, '/') . '/');
}

$yesquery = false;

if ('?' == mb_substr($request, 0, 1)) {
    $request = mb_substr($request, 1);
    $yesquery = true;
}
if ('' == $request || 'index.php' == $request) {
    require __DIR__ . '/home.php';
    die();
}

$params = explode('/', $request);
if ('page' == $params[0]) {
    require __DIR__ . '/home.php';
    die();
}

$vars = [];
parse_str($request, $vars);

$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
if (isset($_REQUEST['trackback'])) {
    require __DIR__ . '/track.php';
    die();
}
if (isset($vars['post'])) {
    $post = $vars['post'];
    require __DIR__ . '/post.php';
    die();
}
if (isset($vars['report'])) {
    $id = $vars['report'];
    require __DIR__ . '/report.php';
    die();
}
if (isset($vars['cat'])) {
    $category = $vars['cat'];
    require __DIR__ . '/categories.php';
    die();
}
if (isset($vars['author'])) {
    $editor = $vars['author'];
    require __DIR__ . '/author.php';
    die();
}
if (isset($vars['tag'])) {
    $tag = $vars['tag'];
    require __DIR__ . '/tag.php';
    die();
}
if (isset($vars['edit'])) {
    $edit = $vars['edit'];
    require __DIR__ . '/submit.php';
    die();
}
if (isset($vars['trackback'])) {
    $id = $vars['trackback'];
    require __DIR__ . '/trackbacks.php';
    die();
}
if (isset($vars['date'])) {
    $vars = explode('/', $vars['date']);
    $time = mktime(0, 0, 0, $vars[1], $vars[0], $vars[2]);
    $time2 = mktime(23, 59, 59, $vars[1], $vars[0], $vars[2]);
    require __DIR__ . '/date.php';
    die();
}

$report = $common->httpRequest()->request('report', 'integer', 0);
if ($report > 0) {
    require __DIR__ . '/report.php';
    die();
}

$vars = explode('/', $request);

// Si los primeros tres valores son numéricos entonces se trata de un artículo
// Solicitado por fecha y por título
$db = XoopsDatabaseFactory::getDatabaseConnection();
if (is_numeric($vars[0]) && is_numeric($vars[1]) && is_numeric($vars[2])) {
    $time = mktime(0, 0, 0, $vars[1], $vars[0], $vars[2]);

    // Check if query is for a date range
    if (!isset($vars[3]) || 'page' == $vars[3] || '' == $vars[3]) {
        $time2 = mktime(23, 59, 59, $vars[1], $vars[0], $vars[2]);
        require __DIR__ . '/date.php';
        die();
    }

    $sql = 'SELECT id_post FROM ' . $db->prefix('mod_mywords_posts') . " WHERE shortname='$vars[3]' AND (pubdate>=$time AND pubdate<=" . ($time + 86400) . ')';
    $result = $db->query($sql);
    list($post) = $db->fetchRow($result);
    require __DIR__ . '/post.php';
    die();
}

/**
 * Si el primer valor es igual a post entonces se trata de una
 * artículo solicitado numéricamente
 */
if ('post' == $vars[0]) {
    $post = $vars[1];
    require __DIR__ . '/post.php';
    die();
}
/**
 * Si el primer valor es category entonces se realiza la búsqueda por
 * categoría
 */
if ('category' == $vars[0]) {
    $categotype = 1;
    require __DIR__ . '/categories.php';
    die();
}
/**
 * Si el primer valor es "author" entonce se
 * realiza la búsqueda por nombre de autor
 */
if ('author' == $vars[0]) {
    $editor = $vars[1];
    require __DIR__ . '/author.php';
    die();
}

if ('tag' == $vars[0]) {
    $tag = $vars[1];
    require __DIR__ . '/tag.php';
    die();
}

if ('trackback' == $vars[0]) {
    $id = $vars[1];
    require __DIR__ . '/trackbacks.php';
    die();
}

/**
 * Si el primer valor es submit
 * entonces se muestra el formulario
 * para enviar un artículo
 */
if ('submit' == $vars[0]) {
    require __DIR__ . '/submit.php';
    die();
}

if ('edit' == $vars[0]) {
    $edit = $vars[1];
    require __DIR__ . '/submit.php';
    die();
}

if ('report' == $vars[0]) {
    $id = $vars[1];
    require __DIR__ . '/report.php';
    die();
}

if ($yesquery || '' == $vars[0]) {
    require __DIR__ . '/home.php';
    die();
}

header('HTTP/1.0 404 Not Found');
if ('cgi' == mb_substr(php_sapi_name(), 0, 3)) {
    header('Status: 404 Not Found', true);
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}

echo '<h1>ERROR 404. Document not Found</h1>';
die();

decodeHeader();
