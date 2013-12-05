<?php
// $Id: submit.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../mainfile.php';

function mw_post_form($edit = 0){
	global $xoopsConfig, $xoopsUser, $xoopsSecurity, $rmTpl, $rmEvents, $xoopsOption;
	
	if (!$xoopsUser){
		redirect_header(MWFunctions::get_url(), 1, __('You are not allowed to do this action!','mywords'));
		die();
	}
	
	// Check if user is a editor
	$author = new MWEditor();
	if (!$author->from_user($xoopsUser->uid()) && !$xoopsUser->isAdmin()){
		redirect_header(MWFunctions::get_url(), 1, __('You are not allowed to do this action!','mywords'));
		die();
	}
	
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.min.js');
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery-ui.min.js');
	
	if ($edit){
		$id = rmc_server_var($_GET, 'id', 0);
		if ($id<=0){
			redirect_header(MWFunctions::get_url(), __('Please, specify a valid post ID','mywords'), 1);
			die();
		}
		$post = new MWPost($id);
		if ($post->isNew()){
			redirect_header(MWFunctions::get_url(), __('Specified post does not exists!','mywords'), 1);
			die();
		}
		
		// Check if user is the admin or a editor of this this post
		if ($author->id()!=$post->getVar('author') && !$xoopsUser->isAdmin()){
			redirect_header($post->permalink(), 1, __('You are not allowed to do this action!','mywords'));
			die();
		}
		
	}
	
	// Read privileges
	$perms = @$author->getVar('privileges');
	$perms = is_array($perms) ? $perms : array();
	$allowed_tracks = in_array("tracks", $perms) || $xoopsUser->isAdmin() ? true : false;
	$allowed_tags = in_array("tags", $perms) || $xoopsUser->isAdmin() ? true : false;
	$allowed_cats = in_array("cats", $perms) || $xoopsUser->isAdmin() ? true : false;
	$allowed_comms = in_array("comms", $perms) || $xoopsUser->isAdmin() ? true : false;
	
	$xoopsOption['module_subpage'] = 'submit';
	include 'header.php';
	
	$form = new RMForm('','','');
	$editor = new RMFormEditor('','content','99%','300px', $edit ? $post->getVar('content') : '');
	$meta_names = MWFunctions::get()->get_metas();
	
	RMTemplate::get()->add_xoops_style('submit.css', 'mywords');
	RMTemplate::get()->add_script(XOOPS_URL.'/modules/mywords/include/js/scripts.php?file=posts.js&front=1');
	
	include RMTemplate::get()->get_template('mywords_submit_form.php', 'module', 'mywords');
	
	include 'footer.php';
	
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
	case 'edit':
		mw_post_form(true);
		break;
	default:
		mw_post_form();
		break;
}