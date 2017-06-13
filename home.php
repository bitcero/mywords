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

$xoopsOption['template_main'] = 'mywords-index.tpl';
$xoopsOption['module_subpage'] = 'index';
include 'header.php';

/**
 * Paginación de Resultados
 */
$sql = "SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")." WHERE status='publish' AND ((visibility='public' OR visibility='password') OR (visibility='private' AND author=".($xoopsUser ? $xoopsUser->uid() : -1)."))";
list($num) = $db->fetchRow($db->query($sql));

$page = rmc_server_var($_GET, 'page', 0);

if ($page<=0){
	$path = explode("/", $request);
	$srh = array_search('page', $path);
	if (isset($path[$srh]) && $path[$srh]=='page')	if (!isset($path[$srh])){ $page = 0; } else { $page = $path[$srh +1]; }
}

$limit = $xoopsModuleConfig['posts_limit'];
$tpages = ceil($num / $limit);
$page = $page > $tpages ? $tpages : $page;
$p = $page>0 ? $page-1 : $page;
$start = $num<=0 ? 0 : $p * $limit;

$nav = new RMPageNav($num, $limit, $page, 5);
$nav->target_url(MW_URL.($mc['permalinks']>1 ? 'page/{PAGE_NUM}/' : '?page={PAGE_NUM}'));
$xoopsTpl->assign('pagenav', $nav->render(false));

$sql = "SELECT * FROM ".$db->prefix("mod_mywords_posts")." WHERE status='publish' AND ((visibility='public' OR visibility='password') OR (visibility='private' AND author=".($xoopsUser ? $xoopsUser->uid() : -1).")) ORDER BY pubdate DESC LIMIT $start,$limit";
$result = $db->query($sql);

include 'post_data.php';

$xoopsTpl->assign('xoops_pagetitle', __('Recent Posts','mywords'));

include 'footer.php';
