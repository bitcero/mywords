<?php
// $Id: mywordscontroller.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains the object MywordsController that
* will be uses by Common Utilities to do some actions
* like update comments
*/

class MywordsController implements iCommentsController
{
    public function increment_comments_number($comment){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($post) || $post<=0) return;
        
        $sql = "UPDATE ".$db->prefix("mw_posts")." SET comments=comments+1 WHERE id_post=$post";
        $db->queryF($sql);
        
    }
    
    public function reduce_comments_number($comment){
		
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($post) || $post<=0) return;
        
        $sql = "UPDATE ".$db->prefix("mw_posts")." SET comments=comments-1 WHERE id_post=$post AND comments>0";
        $db->queryF($sql);
		
    }
    
    public function get_item($params, $com){
        static $posts;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($post) || $post<=0) return __('Not found','mywords');;
        
        if(isset($posts[$post])){
        	return $posts[$post]->getVar('title');
        }
        
        include_once (XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php');
        include_once (XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php');
        $item = new MWPost($post);
        if($item->isNew()){
			return __('Not found','mywords');
        }
        
        $posts[$post] = $item;
        return $item->getVar('title');
        
    }
	
	public function get_item_url($params, $com){
		static $posts;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($post) || $post<=0) return '';
        
        if(isset($posts[$post])){
        	$ret = $posts[$post]->permalink();
			return $ret;
        }
        
        include_once (XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php');
        include_once (XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php');
        $item = new MWPost($post);
        if($item->isNew()){
			return '';
        }
		
		$posts[$post] = $item;
        
        return $item->permalink();
        
	}
    
    public function get_main_link(){
		
		$mc = RMSettings::module_settings('mywords');
		
		if ($mc->permalinks > 1){
			return XOOPS_URL.$mc->basepath;
		} else {
			return XOOPS_URL.'/modules/mywords';
		}
		
    }
    
}
