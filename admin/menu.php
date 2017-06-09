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
    'icon'=>"svg-rmcommon-dashboard text-primary",
    'location'=>"dashboard"
);

$adminmenu[] = array(
    'title'=>__('Categories','mywords'),
    'link'=>"admin/categories.php",
    'icon'=>"svg-rmcommon-folder text-orange",
    'location'=>"categories"
);

$adminmenu[] = array(
    'title'=>__('Tags','mywords'),
    'link'=>"admin/tags.php",
    'icon'=>"svg-rmcommon-tags text-green",
    'location'=>"tags"
);

$options = array();
$options[] = array(
    'title'     => __('All Posts','mywords'),
    'link'      => 'admin/posts.php',
    'selected'  => 'posts_list',
    'icon'      => 'svg-rmcommon-list text-midnight'
);
$options[] = array(
    'title'     => __('Add New','mywords'),
    'link'      => 'admin/posts.php?op=new',
    'selected'  => 'new_post',
    'icon'      => 'svg-rmcommon-plus text-green',
);
$options[] = array('divider' => 1);
$options[] = array(
    'title'     => __('Published','mywords'),
    'link'      => 'admin/posts.php?status=publish',
    'selected'  => 'publish',
    'icon'      => 'svg-rmcommon-world text-blue',
);
$options[] = array(
    'title'     => __('Drafts','mywords'),
    'link'      => 'admin/posts.php?status=draft',
    'selected'  => 'draft',
    'icon'      => 'svg-rmcommon-pencil text-danger',
);
$options[] = array(
    'title'     => __('Pending of Review','mywords'),
    'link'      => 'admin/posts.php?status=pending',
    'selected'  => 'waiting',
    'icon'      => 'svg-rmcommon-sand-clock text-brown',
);

$adminmenu[] = array(
    'title'=>__('Posts','mywords'),
    'link'=>"admin/posts.php",
    'icon'=>"svg-rmcommon-pin text-blue",
    'location'=>"posts",
    'options'=>$options
);

$adminmenu[] = array(
    'title'=>__('Editors','mywords'),
    'link'=>"admin/editors.php",
    'icon'=>"svg-rmcommon-users2 text-midnight",
    'location'=>"editors"
);

$adminmenu[] = array(
    'title'=>__('Social Sites','mywords'),
    'link'=>"admin/bookmarks.php",
    'icon'=>"svg-rmcommon-share text-pink",
    'location'=>"bookmarks",
);

$adminmenu[] = array(
    'title'=>__('Trackbacks','mywords'),
    'link'=>"admin/trackbacks.php",
    'icon'=>"svg-rmcommon-loop text-cyan",
    'location'=>"trackbacks",
);

$adminmenu[] = array(
    'title'=>__('Import Articles','mywords'),
    'link'=>"admin/importer.php",
    'icon'=>"svg-rmcommon-incoming text-deep-orange",
    'location'=>"trackbacks",
);
