<?php
// $Id: home.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'mywords_index.html';
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
