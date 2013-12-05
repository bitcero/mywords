<?php
// $Id: toolbar.php 976 2012-06-01 03:52:18Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$no_toolbar = array(
    'categories',
    'tags'
);
if (in_array(RMCLOCATION, $no_toolbar))
    return;

/**
* This file shows the toolbar and allows to other objects to create
* menus and buttons
*/
if(RMCLOCATION=='posts'){
    RMTemplate::get()->add_tool(__('Posts','mywords'), './posts.php', '../images/post.png', 'dashboard');
    RMTemplate::get()->add_tool(__('New Post','mywords'), './posts.php?op=new', '../images/newpost.png', 'new_post');
    RMTemplate::get()->add_tool(__('Published','mywords'), './posts.php?status=publish', '../images/published.png', 'publish');
    RMTemplate::get()->add_tool(__('Drafts','mywords'), './posts.php?status=draft', '../images/draft.gif', 'draft');
    RMTemplate::get()->add_tool(__('Pending of Review','mywords'), './posts.php?status=waiting', '../images/wait.png', 'waiting');
} else {
    RMTemplate::get()->add_tool(__('Dashboard','mywords'), './index.php', '../images/dashboard.png', 'dashboard');
    RMTemplate::get()->add_tool(__('Categories','mywords'), './categories.php', '../images/categos.png', 'categories');
    RMTemplate::get()->add_tool(__('Tags','mywords'), './tags.php', '../images/tag.png', 'tags');
    RMTemplate::get()->add_tool(__('Posts','mywords'), './posts.php', '../images/post.png', 'posts');
    RMTemplate::get()->add_tool(__('Editors','mywords'), './editors.php', '../images/editor.png', 'editors');
    RMTemplate::get()->add_tool(__('Social Sites','mywords'), './bookmarks.php', '../images/bookmark.png', 'bookmarks');
    RMTemplate::get()->add_tool(__('Trackbacks','mywords'), './trackbacks.php', '../images/trackbacks.png', 'trackbacks');
}

// New toolbar buttons
RMEvents::get()->run_event('mywords.get_toolbar', RMTemplate::get()->get_toolbar());

// New menus
global $xoopsModule;
RMEvents::get()->run_event('mywords.get_menu', $xoopsModule->getAdminMenu());