<?php
// $Id: ax-posts.php 971 2012-05-31 04:21:08Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
set_time_limit(0);
/**
* This function show an error message
*/
function return_error($msg, $token=true, $redirect=''){
    global $xoopsSecurity;
    
    $ret['error'] = $msg;
    if ($token) $ret['token'] = $xoopsSecurity->createToken();
    if ($redirect!='') $ret['redirect'] = $redirect;
    
    echo json_encode($ret);
    die();

}

include '../header.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

extract($_POST);

/*if(!$xoopsSecurity->check() || !$xoopsSecurity->checkReferer()){
    $ret = array(
        'error'=>__('You are not allowed to do this operation!','mywords')
    );
    echo json_encode($ret);
    die();
}*/

$editor = new MWEditor();
$editor->from_user($author);

if ($editor->isNew() && !$xoopsUser->isAdmin()){
	return_error(__('You are not allowed to do this action!','mywords'), false, MW_URL);
}

if ($op=='saveedit'){
    if(!isset($id) || $id<=0){
        return_error(__('You must provide a valid post ID','mywords'), 0, 'posts.php');
        die();
    }
        
    $post = new MWPost($id);
    if($post->isNew()){
        return_error(__('You must provide an existing post ID','mywords'), 0, 'posts.php');
        die();
    }
    
    if (!$editor->id()==$post->getVar('author') && !$xoopsUser->isAdmin()){
		return_error(__('You are not allowed to do this action!','mywords'), false, MW_URL);
    }
        
    $query = 'op=edit&id='.$id;
    $edit = true;
        
} else {
    $query = 'op=new';
    $post = new MWPost();
    $edit = false;
}

/**
* @todo Insert code to verify token
*/
    
// Verify title
if ($title==''){
    return_error(__('You must provide a title for this post','mywords'), true);
    die();
}

if (!isset($shortname) || $shortname==''){
    $shortname = TextCleaner::getInstance()->sweetstring($title);
} else {
    $shortname = TextCleaner::getInstance()->sweetstring($shortname);
}

// Check content
if ($content=='' && $format != 'image'){
    return_error(__('Content for this post has not been provided!','mywords'), true);
    die();
}
    
// Categories
if (!isset($categories) || empty($categories)){
    $categories = array(MWFunctions::get()->default_category_id());
}
    
// Check publish options
if ($visibility=='password' && $vis_password==''){
    return_error(__('You must provide a password for this post or select another visibility option','mywords'), true);
    die();
}
    
$time = explode("-", $schedule);
$schedule = mktime($time[3], $time[4], 0, $time[1], $time[0], $time[2]);
if ($schedule<=time())
    $schedule = 0;

$author = !isset($author) || $author<=0 ? $xoopsUser->uid() : $author;
$authorname = !isset($author) || $author<=0 ? $xoopsUser->uname() : MWFunctions::author_name($author);

// Add Data
$post->setVar('title', $title);
$post->setVar('shortname', $shortname);
$post->setVar('content', $content);
$post->setVar('status', $schedule>time() && $status!='draft' ? 'scheduled' : $status);
$post->setVar('visibility', $visibility);
$post->setVar('schedule', $schedule);
$post->setVar('password', $vis_password);
$post->setVar('author', $author);
$post->setVar('comstatus', isset($comstatus) ? $comstatus : 0);
$post->setVar('pingstatus', isset($pingstatus) ? $pingstatus : 0);
$post->setVar('authorname', $authorname);
$post->setVar('image', $image);
$post->setVar('video', $video);
$post->setVar('format', $format);

if($post->isNew()){
    $post->setVar('comments', 0);
    $post->setVar('reads', 0);
}

// SEO
$post->setVar('description', $description);
$post->setVar('keywords', $keywords);
$post->setVar('customtitle', $seotitle);

if($edit) $post->setVar('modified', time());

if($post->isNew())
    $post->setVar('created', time());

if($status!='draft'){
    if ($schedule<=time() && !$edit){
        $post->setVar('pubdate', time());
    }elseif ($schedule<=time() && $edit){
        $post->setVar('pubdate', $post->getVar('pubdate')==0 ? time() : $post->getVar('pubdate'));
    } else {
        $post->setVar('pubdate', 0);
    }
}

if (MWFunctions::post_exists($post)){
    return_error(__('There is already another post with same title for same date','mywords'), $xoopsSecurity->createToken());
    die();
}

// Add categories
$post->add_categories($categories, true);

// Add tags
$post->add_tags($tags);

$post->clear_metas();

foreach($meta as $data){
    $post->add_meta($data['key'], $data['value']);
}

// before to save post
RMEvents::get()->run_event('mywords.saving.post', $post);

// Add trackbacks uris
$toping = array();
$pinged = $edit ? $post->getVar('pinged') : array();
if ($trackbacks!='' && $post->getVar('pingstatus')){
	
	$trackbacks = explode(" ", $trackbacks);
	
} elseif($trackbacks=='' && $post->getVar('pingstatus')){
	
	$tb = new MWTrackback('','');
	$trackbacks = $tb->auto_discovery($content);
	
}

if (!empty($trackbacks)){
	foreach ($trackbacks as $t){
		if (!empty($pinged) && in_array($t, $pinged)) continue;
		$toping[] = $t;
	}
}

$post->setVar('toping', !empty($toping) ? $toping : '');

$return = $edit ? $post->update() : $post->save();

if ($return){
    if (!$edit) $xoopsUser->incrementPost();
    
    showMessage($edit ? __('Post updated successfully','mywords') : __('Post saved successfully','mywords'), 0);

    $url .= 'posts.php?op=edit&id=' . $post->id();

    $rtn = array(
        'message'   => $edit ? __('Post updated successfully','mywords') : __('Post saved successfully','mywords'),
        'token'     => $xoopsSecurity->createToken(),
        'link'      => '<strong>'.__('Permalink:','mywords').'</strong> '.$post->permalink(),
        'post'      => $post->id(),
        'url'       => $url
    );
    echo json_encode($rtn);
    die();
} else {
    return_error(__('Errors ocurred while trying to save this post.','mywords').'<br />'.$post->errors(), true);
    die();
}
