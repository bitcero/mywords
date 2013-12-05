<?php
// $Id: author.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'mywords_author.html';
$xoopsOption['module_subpage'] = 'author';
include 'header.php';

if (!is_numeric($editor)){
	
	$sql = "SELECT id_editor FROM ".$db->prefix("mw_editors")." WHERE shortname='$editor'";
	list($editor) = $db->fetchRow($db->query($sql));
	if ($editor=='') $editor = 0;
	
}

$ed = new MWEditor($editor);

if ($ed->isNew()){
    redirect_header(MWFunctions::get_url(), 2, __('Sorry, We don\'t know to this editor', 'admin_mywords'));
    die();
}

$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 0;	
if ($page<=0){
	$path = explode("/", $request);
	$srh = array_search('page', $path);
	if (isset($path[$srh]) && $path[$srh]=='page')	if (!isset($path[$srh])){ $page = 0; } else { $page = $path[$srh +1]; }
}

$request = substr($request, 0, strpos($request, 'page')>0 ? strpos($request, 'page') - 1 : strlen($request));

/**
 * Paginamos los resultados
 */
$limit = $mc['posts_limit'];
$sql = "SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE author='$editor' AND status='publish' AND 
		((visibility='public' OR visibility='password') OR (visibility='private' AND
		author=".($xoopsUser ? $xoopsUser->uid() : -1)."))";
list($num) = $db->fetchRow($db->query($sql));

if ($page > 0){ $page -= 1; }

$start = $page * $mc['posts_limit'];
$tpages = (int)($num / $mc['posts_limit']);
if($num % $mc['posts_limit'] > 0) $tpages++;
$pactual = $page + 1;
if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * $limit;
}

$nav = new RMPageNav($num, $limit, $pactual, 6);
$nav->target_url($ed->permalink().($mc['permalinks']>1 ? 'page/{PAGE_NUM}/' : '&page={PAGE_NUM}'));
$xoopsTpl->assign("nav_pages", $nav->render(false, 0));

$xoopsTpl->assign('pactual', $pactual);

$xoopsTpl->assign('lang_fromauthor', sprintf(__('Posts by "%s"','mywords'), $ed->getVar('name')));

$sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE author='$editor' AND status='publish' AND 
		((visibility='public' OR visibility='password') OR (visibility='private' AND
		author=".($xoopsUser ? $xoopsUser->uid() : -1).")) ORDER BY pubdate DESC LIMIT $start,$limit";
$result = $db->query($sql);
require 'post_data.php';

include 'footer.php';
