<?php
// $Id: import_news.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------
// MyWords
// Manejo de Artículos
// CopyRight © 2007 - 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

/**
 * Este archivo permite importar art?culos desde
 * el m?dulo News.
 * ADVERTENCIA: Elimina este archivo despu?s de haber importado las noticias
 */

define('NP_LOCATION','import');
require 'header.php';

$module_handler =& xoops_gethandler('module');
$module =& $module_handler->getByDirname('news');

if (!$module){
	redirect_header('./', 2, sprintf(_AS_NP_NOINTALLED, 'News'));
	die();
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'do':
		
		include_once '../class/catego.class.php';
		include_once '../class/post.class.php';
		
		$result = $db->query("SELECT * FROM ".$db->prefix("topics")." ORDER by topic_pid");
		$cats = array();
		$cats[0] = 0;
		while ($row = $db->fetchArray($result)){
			$catego = new NPCategory();
			$catego->setName($row['topic_title']);
			$catego->setDescription($row['topic_description']);
			$catego->setParent($cats[$row['topic_pid']]);
			$catego->setPosts(0);
			$catego->setFriendName($util->sweetstring($row['topic_title']));
			$catego->save();
			$cats[$row['topic_id']] = $catego->getID();
		}
		
		// Guardamos los art?culos
		$result = $db->query("SELECT * FROm ".$db->prefix("stories")." ORDER BY topicid");
		$stories = array();
		
		while ($row = $db->fetchArray($result)){
			$post = new NPPost();
			$post->setTitle($row['title']);
			$post->setFriendTitle($util->sweetstring($row['title']));
			$post->setAuthor($row['uid']);
			$post->setDate($row['created']);
			$post->setModDate($row['published']);
			$post->setText($row['hometext'].'<br />'.$row['bodytext']);
			$post->setStatus(1);
			$post->setAllowComs(1);
			$post->setAdvance(0);
			$post->addToCatego($cats[$row['topicid']]);
			$post->save();
			$stories[$row['storyid']] = $post->getID();
		}
		
		// Guardamos los comentarios
		$result = $db->query("SELECT * FROM ".$db->prefix("xoopscomments")." WHERE com_modid='".$module->mid()."'");
		while ($row = $db->fetchArray($result)){
			$xu = new XoopsUser($row['com_uid']);
			$sql = "INSERT INTO ".$db->prefix("mod_mywords_comments")." (`post`,`nombre`,`email`,`texto`,`xu`,`fecha`,`aprovado`)
					VALUES ('".$stories[$row['com_itemid']]."','".$xu->uname()."','".$xu->email()."','$row[com_text]',
					'$row[com_uid]','$row[com_created]','".($row['com_status']==2 ? 1 : 0)."')";
			$db->queryF($sql);
			$post = new NPPost($stories[$row['com_itemid']]);
			$post->setComments($post->getComments()+1);
			$post->update();
		}
		
		redirect_header('posts.php', 2, _AS_NP_DBOK);
		die();
		
		break;
	default:
		xoops_cp_header();
		makeAdminNav();
		
		$hiddens['op'] = 'do';
		$buttons['sbt']['value'] = _SUBMIT;
		$buttons['sbt']['type'] = 'submit';
		$util->msgBox($hiddens, 'import_news.php', sprintf(_AS_NP_CONFIRMIMPORT, 'News'), '../images/question.png', $buttons, true, 400);
		
		xoops_cp_footer();
}

?>