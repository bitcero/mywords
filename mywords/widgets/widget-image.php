<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Provides a widget to specify the default image for posts
 */
function mywords_widget_image( $post = null ){
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $rm_config;
    
    $type   = RMHttpRequest::request( 'type', 'string', '' );

    $widget = array();
    $widget['title'] = __('Default Image','mywords');
    $util = new RMUtilities();

    if ( isset($post) && is_a( $post, 'MWPost' ) ){

        if ($post->isNew())
            $params = '';
        else
            $params = $post->getVar('image','e');

    } else {
        $params = '';
    }

    $widget['content'] = '<form name="frmDefimage" id="frm-defimage" method="post">';
    $widget['content'] .= $util->image_manager('image', 'image', $params, array('accept' => 'thumbnail', 'multiple' => 'no'));
    $widget['content'] .= '</form>';
    return $widget;

}