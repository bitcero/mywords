<?php
// $Id: search.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * Función para realizar búsquedas
 */
function mywords_search($qa, $andor, $limit, $offset, $userid){
	global $xoopsUser;
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php';
	$util =& RMUtilities::get();
	$mc =& RMSettings::module_settings( 'mywords' );
	
	$sql = "SELECT * FROM ".$db->prefix("mw_posts");
	$adds = '';
	
	if ( is_array($qa) && $count = count($qa) ) {
		$adds = '';
		for($i=0;$i<$count;$i++){
			$adds .= $adds=='' ? "(title LIKE '%$qa[$i]%' OR content LIKE '%$qa[$i]%')" : " $andor (title LIKE '%$qa[$i]%' OR content LIKE '%$qa[$i]%')";
		}
	}
	
	$sql .= $adds!='' ? " WHERE ".$adds : '';
	if ($userid>0){
		$sql .= ($adds!='' ? " AND " : " WHERE ")."author='$userid'";
	}
	$sql .= " ORDER BY pubdate DESC";

	$i = 0;
	$result = $db->query($sql, $limit, $offset);
	$ret = array();
	while ($row = $db->fetchArray($result)){
		$post = new MWPost();
		$post->assignVars($row);
		$ret[$i]['image'] = "images/post.png";
		$ret[$i]['link'] = $post->permalink();
		$ret[$i]['title'] = $post->getVar('title');
		$ret[$i]['time'] = $post->getVar('pubdate');
		$ret[$i]['uid'] = $post->getVar('author');
		$ret[$i]['desc'] = substr(strip_tags($post->content()), 0, 150);
		$i++;
	}
	
	return $ret;
	
}
