<?php
// $Id: ping.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file execute the pings for a given post
*/

require '../../mainfile.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

$id = rmc_server_var($_GET, 'post', 0);

if ($id<=0) die();

$post = new MWPost($id);

if ($post->isNew()) die();

$editor = new MWEditor($post->getVar('author'));
if ($editor->isNew()) $user = new XoopsUser($post->getVar('author'));
$tracks = $post->getVar('toping');

if(empty($tracks)) die();

$pinged = $post->getVar('pinged');
$toping = $post->getVar('toping');
$tp = array();

$tback = new MWTrackback($xoopsModuleConfig['blogname'], $editor->isNew() ? $user->getVar('uname') : $editor->getVar('name'));
foreach ($tracks as $t){
	if (!empty($pinged) && in_array($t, $pinged)) continue;
	$ret = $tback->ping($t, $post->permalink(), $post->getVar('title'), TextCleaner::getInstance()->truncate($post->content(true), 240));
	if ($ret){
		$pinged[] = $t;
	} else {
		$tp[] = $t;
	}
}

$post->setVar('toping', empty($tp) ? '' : $tp);
$post->setVar('pinged', $pinged);
$post->update(true);

die();
