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

if (!defined('XOOPS_ROOT_PATH')){
    header('location: ./');
    die();
}

// Authors cache
$editors = array();

while ($row = $db->fetchArray($result)){
	
    $post = new MWPost();
    $post->assignVars($row);

    # Generamos los vínculos
    $day = date('d', $post->getVar('pubdate'));
    $month = date('m', $post->getVar('pubdate'));
    $year = date('Y', $post->getVar('pubdate'));
    $link = $post->permalink();
    # Generamos el vínculo para el autor
    if ($post->getVar('author')>0){

    	if( !isset( $editors[$post->getVar('author')] ) )
            $editors[$post->getVar('author')] = new MWEditor( $post->getVar('author'), 'user' );

        if ( $editors[$post->getVar('author')]->isNew() ){

            if ( $xoopsUser && $xoopsUser->uid() == $post->author )
                $user = $xoopsUser;
            else
                $user = new RMUser( $post->author );

            $editors[$post->getVar('author')]->uid = $user->uid();
            $editors[$post->getVar('author')]->name = $user->getVar('name');
            $editors[$post->getVar('author')]->shortname = $user->getVar('uname');
            $editors[$post->getVar('author')]->privileges = array( 'tags', 'tracks', 'comms' );
            $editors[$post->getVar('author')]->save();

        }

        $editor = $editors[$post->getVar('author')];
        $alink = $editor->permalink();

    } else {

		$alink = '';

    }

    # Información de Publicación
    $published = sprintf(__('%s by %s', 'mywords'), MWFunctions::format_time($post->getVar('pubdate'),'string'), '<a href="'.$alink.'">'.(isset($editor) ? $editor->name : __('Anonymous','mywords'))."</a>");
    # Texto de continuar leyendo
    if ($post->getVar('visibility')=='password'){
        $text = isset($_SESSION['password-'.$post->id()]) && $_SESSION['password-'.$post->id()]==$post->getVar('password') ? $post->content(true) : MWFunctions::show_password($post);
    } else {
        $text = $post->content(true);
    }
    
    // Redes Sociales
    if($xoopsModuleConfig['showbookmarks']){
        $bms = array();
        foreach ($socials as $bm){
            $bms[] = array('icon'=>$bm->getVar('icon'),'alt'=>$bm->getVar('alt'),'link'=>str_replace(array('{URL}','{TITLE}','{DESC}'), array($post->permalink(),$post->getVar('title'),TextCleaner::getInstance()->truncate($text, 50)),$bm->getVar('url')));
        }
    }
    
    $xoopsTpl->append('posts', array(
        'id'                =>$post->id(),
        'title'            	=>$post->getVar('title'),
        'format'            =>$post->getVar('format'),
        'text'              =>$text,
        'description'       => $post->getVar('description'),
        'keywords'          => $post->getVar('keywords'),
        'cats'           	=> $post->get_categos('data'),
        'link'              =>$link,
        'published'         =>$published,
        'comments'          =>$post->getVar('comments'),
        'lang_comments'		=>sprintf(__('%u Comments','mywords'), $post->getVar('comments')),
        'continue'          =>$post->hasmore_text(),
        'lang_continue'		=> $post->hasmore_text() ? sprintf(__('Read more about "%s"','mywords'), $post->getVar('title')) : '',
        'bookmarks'         =>$bms,
        'time'              => $post->getVar('pubdate'),
        'author'            =>array(
                                'name'  => $editor->name != '' ? $editor->name : $editor->shortname,
                                'id'    => $editor->id(),
                                'link'  => $editor->permalink(),
                                'bio'   => $editor->getVar('bio'),
                                'email' => $editor->data('email'),
                                'avatar'=> $cuServices->avatar->getAvatarSrc($editor->data('email'), 100)// RMEvents::get()->run_event( 'rmcommon.get.avatar', $editor->data('email') )
                            ),
        'alink'				=>$alink,
        'edit'              => $xoopsUser && ($xoopsUser->isAdmin() || $editor->getVar('uid')==$xoopsUser->uid()),
        'tags'              => $post->tags(false),
        'meta'              => $post->get_meta('', false),
        'image'             => $post->image(),
        'video'             => $post->video,
        'player'            => $post->format == 'video' ? $post->video_player() : '',
    ));

}
$xoopsTpl->assign('lang_editpost', __('Edit Post','mywords'));
$xoopsTpl->assign('lang_postedin', __('Posted in:','mywords'));
$xoopsTpl->assign('lang_taggedas', __('Tagged as:','mywords'));
$xoopsTpl->assign('enable_images', $xoopsModuleConfig['list_post_imgs']);
