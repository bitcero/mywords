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

$xoopsOption['template_main'] = 'mywords-date.tpl';
$xoopsOption['module_subpage'] = 'date';
include 'header.php';

$sql = "SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")." WHERE pubdate BETWEEN $time and $time2 AND ((visibility='public' OR visibility='password') OR (visibility='private' AND author=".($xoopsUser ? $xoopsUser->uid() : -1)."))";
list($num) = $db->fetchRow($db->query($sql));

// Check if there are posts for this date
if ($num<=0){
    redirect_header(MWFunctions::get_url(), 1, __("There are not posts published on this date", 'mywords'));
    die();
}

$page = rmc_server_var($_GET, 'page', 0);

if ($page<=0){
    $path = explode("/", $request);
    $srh = array_search('page', $path);
    if (isset($path[$srh]) && $path[$srh]=='page')    if (!isset($path[$srh])){ $page = 0; } else { $page = $path[$srh +1]; }
}

$limit = $xoopsModuleConfig['posts_limit'];
$tpages = ceil($num / $limit);
$page = $page > $tpages ? $tpages : $page;
$p = $page>0 ? $page-1 : $page;
$start = $num<=0 ? 0 : $p * $limit;

$nav = new RMPageNav($num, $limit, $page, 5);
$date_prefix = date("d/m/Y", $time);
$nav->target_url(MW_URL.($mc['permalinks']>1 ? $date_prefix.'/page/{PAGE_NUM}/' : '?date='.$date_prefix.'&amp;page={PAGE_NUM}'));
$xoopsTpl->assign('pagenav', $nav->render(false));

$sql = "SELECT * FROM ".$db->prefix("mod_mywords_posts")." WHERE pubdate BETWEEN $time and $time2 AND ((visibility='public' OR visibility='password') OR (visibility='private' AND author=".($xoopsUser ? $xoopsUser->uid() : -1).")) ORDER BY pubdate DESC LIMIT $start,$limit";
$result = $db->query($sql);

include 'post_data.php';

$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Posts published on %s','mywords'), formatTimestamp($time, 's')));

include 'footer.php';

