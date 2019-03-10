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
load_mod_locale('mywords', '');

$adminmenu[] = [
    'title' => __('Dashboard', 'mywords'),
    'link' => 'admin/index.php',
    'icon' => 'svg-rmcommon-dashboard text-primary',
    'location' => 'dashboard',
];

$adminmenu[] = [
    'title' => __('Categories', 'mywords'),
    'link' => 'admin/categories.php',
    'icon' => 'svg-rmcommon-folder text-orange',
    'location' => 'categories',
];

$adminmenu[] = [
    'title' => __('Tags', 'mywords'),
    'link' => 'admin/tags.php',
    'icon' => 'svg-rmcommon-tags text-green',
    'location' => 'tags',
];

$options = [];
$options[] = [
    'title' => __('All Posts', 'mywords'),
    'link' => 'admin/posts.php',
    'selected' => 'posts_list',
    'icon' => 'svg-rmcommon-list text-midnight',
];
$options[] = [
    'title' => __('Add New', 'mywords'),
    'link' => 'admin/posts.php?op=new',
    'selected' => 'new_post',
    'icon' => 'svg-rmcommon-plus text-green',
];
$options[] = ['divider' => 1];
$options[] = [
    'title' => __('Published', 'mywords'),
    'link' => 'admin/posts.php?status=publish',
    'selected' => 'publish',
    'icon' => 'svg-rmcommon-world text-blue',
];
$options[] = [
    'title' => __('Drafts', 'mywords'),
    'link' => 'admin/posts.php?status=draft',
    'selected' => 'draft',
    'icon' => 'svg-rmcommon-pencil text-danger',
];
$options[] = [
    'title' => __('Pending of Review', 'mywords'),
    'link' => 'admin/posts.php?status=pending',
    'selected' => 'waiting',
    'icon' => 'svg-rmcommon-sand-clock text-brown',
];

$adminmenu[] = [
    'title' => __('Posts', 'mywords'),
    'link' => 'admin/posts.php',
    'icon' => 'svg-rmcommon-pin text-blue',
    'location' => 'posts',
    'options' => $options,
];

$adminmenu[] = [
    'title' => __('Editors', 'mywords'),
    'link' => 'admin/editors.php',
    'icon' => 'svg-rmcommon-users2 text-midnight',
    'location' => 'editors',
];

$adminmenu[] = [
    'title' => __('Social Sites', 'mywords'),
    'link' => 'admin/bookmarks.php',
    'icon' => 'svg-rmcommon-share text-pink',
    'location' => 'bookmarks',
];

$adminmenu[] = [
    'title' => __('Trackbacks', 'mywords'),
    'link' => 'admin/trackbacks.php',
    'icon' => 'svg-rmcommon-loop text-cyan',
    'location' => 'trackbacks',
];

$adminmenu[] = [
    'title' => __('Import Articles', 'mywords'),
    'link' => 'admin/importer.php',
    'icon' => 'svg-rmcommon-incoming text-deep-orange',
    'location' => 'importer',
];
