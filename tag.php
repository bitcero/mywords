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
$GLOBALS['xoopsOption']['template_main'] = 'mywords-tag.tpl';
$xoopsOption['module_subpage'] = 'author';
require __DIR__ . '/header.php';

$tag = new MWTag($tag);

if ($tag->isNew()) {
    redirect_header(MWFunctions::get_url(), 2, __('Sorry, this tag does not exists!', 'admin_mywords'));
    die();
}

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

$request = mb_substr($request, 0, mb_strpos($request, 'page') > 0 ? mb_strpos($request, 'page') - 1 : mb_strlen($request));

/**
 * Paginamos los resultados
 */
$limit = $mc['posts_limit'];
$table_tags = $db->prefix('mod_mywords_tagspost');
$table_posts = $db->prefix('mod_mywords_posts');

$sql = "SELECT COUNT(*) FROM $table_posts as a, $table_tags as b WHERE b.tag='" . $tag->id() . "' AND 
        a.id_post=b.post AND status='publish' AND 
		((visibility='public' OR visibility='password') OR (visibility='private' AND
		author=" . ($xoopsUser ? $xoopsUser->uid() : -1) . '))';
list($num) = $db->fetchRow($db->query($sql));

if ($page > 0) {
    $page -= 1;
}

$start = $page * $mc['posts_limit'];
$tpages = (int)($num / $mc['posts_limit']);
if ($num % $mc['posts_limit'] > 0) {
    $tpages++;
}
$pactual = $page + 1;
if ($pactual > $tpages) {
    $rest = $pactual - $tpages;
    $pactual = $pactual - $rest + 1;
    $start = ($pactual - 1) * $limit;
}

$nav = new RMPageNav($num, $limit, $pactual, 6);
$nav->target_url($tag->permalink() . ($mc['permalinks'] > 1 ? 'page/{PAGE_NUM}/' : '&page={PAGE_NUM}'));
$xoopsTpl->assign('nav_pages', $nav->render(false, 0));

$xoopsTpl->assign('pactual', $pactual);

$xoopsTpl->assign('lang_taggedtitle', sprintf(__('Posts tagged as "%s"', 'mywords'), $tag->getVar('tag')));

$sql = "SELECT a.* FROM $table_posts as a, $table_tags as b WHERE b.tag='" . $tag->id() . "' AND
        a.id_post=b.post AND status='publish' AND 
		((visibility='public' OR visibility='password') OR (visibility='private' AND
		author=" . ($xoopsUser ? $xoopsUser->uid() : -1) . ")) ORDER BY pubdate DESC LIMIT $start,$limit";
$result = $db->query($sql);
require __DIR__ . '/post_data.php';

$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Posts tagged as "%s"', 'mywords'), $tag->getVar('tag')));

require __DIR__ . '/footer.php';
