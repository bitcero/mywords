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

$xoopsOption['template_main'] = 'mywords-author.tpl';
$xoopsOption['module_subpage'] = 'author';
include 'header.php';

if (!is_numeric($editor)){
	
	$sql = "SELECT id_editor FROM ".$db->prefix("mod_mywords_editors")." WHERE shortname='$editor'";
	list($editor) = $db->fetchRow($db->query($sql));
	if ($editor=='') $editor = 0;
	
}

$ed = new MWEditor($editor);

if ($ed->isNew()){
    $params = array(
        'page'  => 'author'
    );
    RMFunctions::error_404( __('Sorry, we don\'t know this editor', 'admin_mywords'), 'mywords', $params );
    die();
}

$xoopsTpl->assign('editor', array(
    'id'    => $ed->id(),
    'uid'   => $ed->uid,
    'name'  => $ed->name,
    'email' => $ed->data('email'),
    'uname' => $ed->uname
));

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
$sql = "SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")." WHERE author='".$ed->uid."' AND status='publish' AND
		((visibility='public' OR visibility='password') OR (visibility='private' AND
		author=".$ed->uid."))";
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

$sql = "SELECT * FROM ".$db->prefix("mod_mywords_posts")." WHERE author='".$ed->uid."' AND status='publish' AND
		((visibility='public' OR visibility='password') OR (visibility='private' AND
		author=".$ed->uid.")) ORDER BY pubdate DESC LIMIT $start,$limit";
$result = $db->query($sql);
require 'post_data.php';

include 'footer.php';
