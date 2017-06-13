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
	
	$sql = "SELECT * FROM ".$db->prefix("mod_mywords_posts");
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
