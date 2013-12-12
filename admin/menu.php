<?php
// $Id: menu.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('mywords','');

$adminmenu[] = array(
    'title'=>__('Dashboard','mywords'),
    'link'=>"admin/index.php",
    'icon'=>"../images/dashboard.png",
    'location'=>"dashboard"
);

$adminmenu[] = array(
    'title'=>__('Categories','mywords'),
    'link'=>"admin/categories.php",
    'icon'=>"../images/categos.png",
    'location'=>"categories"
);

$adminmenu[] = array(
    'title'=>__('Tags','mywords'),
    'link'=>"admin/tags.php",
    'icon'=>"../images/tag.png",
    'location'=>"tags"
);

$options = array();
$options[] = array(
    'title'     => __('All Posts','mywords'),
    'link'      => 'admin/posts.php',
    'selected'  => 'posts_list',
    'icon'      => '../images/icon16.png'
);
$options[] = array(
    'title'     => __('Add New','mywords'),
    'link'      => 'admin/posts.php?op=new',
    'selected'  => 'new_post',
    'icon'      => '../images/newpost.png',
);
$options[] = array('divider' => 1);
$options[] = array(
    'title'     => __('Published','mywords'),
    'link'      => 'admin/posts.php?op=publish',
    'selected'  => 'publish',
    'icon'      => '../images/published.png',
);
$options[] = array(
    'title'     => __('Drafts','mywords'),
    'link'      => 'admin/posts.php?op=draft',
    'selected'  => 'draft',
    'icon'      => '../images/draft.gif',
);
$options[] = array(
    'title'     => __('Pending of Review','mywords'),
    'link'      => 'admin/posts.php?op=waiting',
    'selected'  => 'waiting',
    'icon'      => '../images/wait.png',
);

$adminmenu[] = array(
    'title'=>__('Posts','mywords'),
    'link'=>"admin/posts.php",
    'icon'=>"../images/post.png",
    'location'=>"posts",
    'options'=>$options
);

$adminmenu[] = array(
    'title'=>__('Editors','mywords'),
    'link'=>"admin/editors.php",
    'icon'=>"../images/editor.png",
    'location'=>"editors"
);

$adminmenu[] = array(
    'title'=>__('Social Sites','mywords'),
    'link'=>"admin/bookmarks.php",
    'icon'=>"../images/bookmark.png",
    'location'=>"bookmarks",
);

$adminmenu[] = array(
    'title'=>__('Trackbacks','mywords'),
    'link'=>"admin/trackbacks.php",
    'icon'=>"../images/trackbacks.png",
    'location'=>"trackbacks",
);

