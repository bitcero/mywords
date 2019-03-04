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
$GLOBALS['xoopsOption']['template_main'] = 'mywords-cats.tpl';
$xoopsOption['module_subpage'] = 'catego';
require __DIR__ . '/header.php';

$tbl1 = $db->prefix('mod_mywords_categories');
$tbl2 = $db->prefix('mod_mywords_catpost');
$tbl3 = $db->prefix('mod_mywords_posts');

$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;

if ($page <= 0) {
    $path = explode('/', $request);
    $srh = array_search('page', $path, true);
    if (isset($path[$srh]) && 'page' == $path[$srh]) {
        if (!isset($path[$srh])) {
            $page = 0;
        } else {
            $page = $path[$srh + 1];
        }
    }
}

/**
 * Antes que nada debemos buscar la categoría
 * si esta ha sido pasada como una ruta
 */
if (@$categotype) {
    array_shift($path);
    /**
     * Comprobamos si el primer indice corresponde a la id de la categoría
     */
    if (is_numeric($path[0])) {
        $category = $path[0];
    } else {
        $idp = 0; # ID de la categoria padre
        foreach ($path as $k) {
            if ('' == $k) {
                continue;
            }
            $sql = "SELECT id_cat FROM $tbl1 WHERE shortname='$k' AND parent='$idp'";
            $result = $db->query($sql);
            if ($db->getRowsNum($result) > 0) {
                list($idp) = $db->fetchRow($result);
            }
        }
        $category = $idp;
    }
}

$catego = new MWCategory($category);
if ($catego->isNew()) {
    redirect_header(MWFunctions::get_url(), 2, __('Specified category could not be found', 'mywords'));
    die();
}

// Datos de la Categoría
$xoopsTpl->assign('category', ['id' => $catego->id(), 'name' => $catego->getVar('name')]);
$xoopsTpl->assign('lang_postsincat', sprintf(__('Posts in &#8216;%s&#8217; Category', 'mywords'), $catego->getVar('name')));

$request = mb_substr($request, 0, mb_strpos($request, 'page') > 0 ? mb_strpos($request, 'page') - 1 : mb_strlen($request));
//$request =

// Select all posts from relations table
//$sql = "SELECT post FROM ".$db->prefix("mod_mywords_catpost")." WHERE cat='$category'";
//$result = $db->query($sql);

/**
 * Paginacion de Resultados
 */
$limit = $mc['posts_limit'];
list($num) = $db->fetchRow($db->query("SELECT COUNT($tbl2.post) FROM $tbl2, $tbl3 WHERE $tbl2.cat='$category' 
		AND $tbl3.id_post=$tbl2.post AND $tbl3.status='publish' AND 
		(($tbl3.visibility='public' OR $tbl3.visibility='password') OR ($tbl3.visibility='private' AND 
		$tbl3.author=" . ($xoopsUser ? $xoopsUser->uid() : -1) . '))'));

$page = isset($page) && $page > 0 ? $page : 1;

$limit = $xoopsModuleConfig['posts_limit'];
$tpages = ceil($num / $limit);
$page = $page > $tpages ? $tpages : $page;
$p = $page > 0 ? $page - 1 : $page;
$start = $num <= 0 ? 0 : $p * $limit;

$xoopsTpl->assign('page', $page);

$nav = new RMPageNav($num, $limit, $page, 5);
$nav->target_url($catego->permalink() . (1 == $xoopsModuleConfig['permalinks'] ? '&page={PAGE_NUM}' : 'page/{PAGE_NUM}/'));
$xoopsTpl->assign('pagenav', $nav->render(false));

$xoopsTpl->assign('lang_permalink', __('Permalink to this post', 'mywords'));

$result = $db->query("SELECT $tbl3.* FROM $tbl2, $tbl3 WHERE $tbl2.cat='$category' 
		AND $tbl3.id_post=$tbl2.post AND $tbl3.status='publish' AND 
		(($tbl3.visibility='public' OR $tbl3.visibility='password') OR ($tbl3.visibility='private' AND 
		$tbl3.author=" . ($xoopsUser ? $xoopsUser->uid() : -1) . ")) ORDER BY $tbl3.pubdate DESC LIMIT $start,$limit");

require __DIR__ . '/post_data.php';

$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Posts published under "%s"', 'mywords'), $catego->getVar('name')));

require __DIR__ . '/footer.php';
