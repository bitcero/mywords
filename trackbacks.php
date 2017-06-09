<?php
// $Id: trackbacks.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
header('Content-Type: text/xml');
require '../../mainfile.php';

load_mod_locale('mywords');

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

$id = rmc_server_var($_REQUEST, 'trackback', 0);
if ($id<=0) die();

$post = new MWPost($id);

if ($post->isNew()) die();

$editor = new MWEditor($post->getVar('author'));
if ($editor->isNew()) $user = new XoopsUser($post->getVar('author'));
$track = new MWTrackback($xoopsConfig['sitename'], $editor->getVar('name'));

$id = $track->post_id; // The id of the item being trackbacked
$url = $track->url; // The URL from which we got the trackback
$title = $track->title; // Subject/title send by trackback
$excerpt = $track->excerpt; // Short text send by trackback
$blog_name = rmc_server_var($_POST, 'blog_name', '');

if ($url=='' || $title=='' || $excerpt==''){
	echo $track->recieve(false, __('Sorry, your trackback seems to be invalid!', 'mywords'));
	die();
}

$params = array(
	'blogurl'=>MWFunctions::get_url(),
	'name'	=> 'Trackback',
	'email'	=> '',
	'url'	=> $url,
	'text'	=> $excerpt,
	'permalink'	=> $post->permalink()
);

$ret = RMEvents::get()->run_event('rmcommon.check.post.spam', $params);

if (!$ret){
	echo $track->recieve(false, __('Sorry, your trackback seems to be SPAM','mywords'));
} else {
	
	$to = new MWTrackbackObject();
	$to->setVar('date', time());
	$to->setVar('title', $title);
	$to->setVar('blog_name', $blog_name);
	$to->setVar('excerpt', $excerpt);
	$to->setVar('url', $url);
	$to->setVar('post', $post->id());
	if ($to->save()){
		echo $track->recieve(true);
	} else {
		echo $track->recieve(false, __('We are unable to store your trackback. Please try again later!', 'mywords'));
	}
}

die();