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
if (!defined('XOOPS_MAINFILE_INCLUDED')) {
    header('Location: ../../backend.php');
    die();
}

load_mod_locale('mywords');
$show = rmc_server_var($_GET, 'show', 'all');

$xoopsModule = RMFunctions::load_module('mywords');
$config = RMSettings::module_settings('mywords');
require_once XOOPS_ROOT_PATH . '/modules/mywords/class/mwfunctions.php';

$rss_channel = [];

switch ($show) {
    case 'cat':
        require_once XOOPS_ROOT_PATH . '/modules/mywords/class/mwcategory.class.php';
        $id = rmc_server_var($_GET, 'cat', 0);
        if ($id <= 0) {
            redirect_header('backend.php', 1, __('Sorry, specified category was not foud!', 'mywords'));
            die();
        }

        $cat = new MWCategory($id);
        if ($cat->isNew()) {
            redirect_header('backend.php', 1, __('Sorry, specified category was not foud!', 'mywords'));
            die();
        }

        $rss_channel['title'] = sprintf(__('Posts in %s - %s', 'mywords'), $cat->name, $xoopsConfig['sitename']);
        $rss_channel['link'] = $cat->permalink();
        $rss_channel['description'] = htmlspecialchars($cat->getVar('description'), ENT_QUOTES);
        $rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
        $rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['category'] = $cat->getVar('name');
        $rss_channel['generator'] = 'Common Utilities';
        $rss_channel['language'] = RMCLANG;

        $posts = MWFunctions::get_posts_by_cat($id, 0, 10);
        $rss_items = [];
        foreach ($posts as $post) {
            $item = [];
            $item['title'] = $post->getVar('title');
            $item['link'] = $post->permalink();

            $img = new RMImage();
            $img->load_from_params($post->getVar('image', 'e'));
            if (!$img->isNew()) {
                $image = '<img src="' . $img->url() . '" alt="' . $post->getVar('title') . '"><br>';
            } else {
                $image = '';
            }

            $item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($image . $post->content(true), ENT_QUOTES));
            $item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
            $item['guid'] = $post->permalink();
            $rss_items[] = $item;
        }

        break;
    case 'tag':
        require_once XOOPS_ROOT_PATH . '/modules/mywords/class/mwtag.class.php';
        $id = rmc_server_var($_GET, 'tag', 0);
        if ($id <= 0) {
            redirect_header('backend.php', 1, __('Sorry, specified tag was not foud!', 'mywords'));
            die();
        }

        $tag = new MWTag($id);
        if ($tag->isNew()) {
            redirect_header('backend.php', 1, __('Sorry, specified tag was not foud!', 'mywords'));
            die();
        }

        $rss_channel['title'] = sprintf(__('Posts tagged %s in %s', 'mywords'), $tag->tag, $xoopsConfig['sitename']);
        $rss_channel['link'] = $tag->permalink();
        $rss_channel['description'] = sprintf(__('Posts tagged as %s', 'mywords'), $tag->getVar('tag'));
        $rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
        $rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['category'] = 'Blog';
        $rss_channel['generator'] = 'Common Utilities';
        $rss_channel['language'] = RMCLANG;

        $posts = MWFunctions::get_posts_by_tag($id, 0, 10);
        $rss_items = [];
        foreach ($posts as $post) {
            $item = [];
            $item['title'] = $post->getVar('title');
            $item['link'] = $post->permalink();
            $img = new RMImage();
            $img->load_from_params($post->getVar('image', 'e'));
            if (!$img->isNew()) {
                $image = '<img src="' . $img->url() . '" alt="' . $post->getVar('title') . '"><br>';
            } else {
                $image = '';
            }
            $item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($image . $post->content(true), ENT_QUOTES));
            $item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
            $item['guid'] = $post->permalink();
            $rss_items[] = $item;
        }

        break;
    case 'author':
        require_once XOOPS_ROOT_PATH . '/modules/mywords/class/mweditor.class.php';
        $id = RMHttpRequest::get('author', 'integer', 0);
        if ($id <= 0) {
            redirect_header('backend.php', 1, __('Sorry, specified author was not foud!', 'mywords'));
            die();
        }

        $ed = new MWEditor($id);
        if ($ed->isNew()) {
            redirect_header('backend.php', 1, __('Sorry, specified author was not foud!', 'mywords'));
            die();
        }

        $rss_channel['title'] = sprintf(__('Posts by %s in %s', 'mywords'), '' != $ed->name ? $ed->name : $ed->shortname, $xoopsConfig['sitename']);
        $rss_channel['link'] = $ed->permalink();
        $rss_channel['description'] = sprintf(__('Posts published by %s.', 'mywords'), $ed->getVar('name')) . ' ' . htmlspecialchars(strip_tags($ed->getVar('bio')), ENT_QUOTES);
        $rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
        $rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['category'] = 'Blog';
        $rss_channel['generator'] = 'Common Utilities';
        $rss_channel['language'] = RMCLANG;

        $posts = MWFunctions::get_filtered_posts('author=' . $ed->uid, 0, 10);
        $rss_items = [];
        foreach ($posts as $post) {
            $item = [];
            $item['title'] = $post->getVar('title');
            $item['link'] = $post->permalink();
            $img = new RMImage();
            $img->load_from_params($post->getVar('image', 'e'));
            if (!$img->isNew()) {
                $image = '<img src="' . $img->url() . '" alt="' . $post->getVar('title') . '"><br>';
            } else {
                $image = '';
            }
            $item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($image . $post->content(true), ENT_QUOTES));
            $item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
            $item['guid'] = $post->permalink();
            $rss_items[] = $item;
        }

        break;
    case 'all':
    default:
        $rss_channel['title'] = sprintf(__('Posts in %s', 'mywords'), $xoopsConfig['sitename']);
        $rss_channel['link'] = XOOPS_URL . ($config->permalinks ? $config->basepath : '/modules/mywords');
        $rss_channel['description'] = __('All recent published posts', 'mywords');
        $rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
        $rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['category'] = 'Blog';
        $rss_channel['generator'] = 'Common Utilities';
        $rss_channel['language'] = RMCLANG;

        // Get posts
        $posts = MWFunctions::get_posts(0, 10);
        $rss_items = [];
        foreach ($posts as $post) {
            $item = [];
            $item['title'] = $post->getVar('title');
            $item['link'] = $post->permalink();
            $img = new RMImage();
            $img->load_from_params($post->getVar('image', 'e'));
            if (!$img->isNew()) {
                $image = '<img src="' . $img->url() . '" alt="' . $post->getVar('title') . '"><br>';
            } else {
                $image = '';
            }
            $item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($image . $post->content(true), ENT_QUOTES));
            $item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
            $item['guid'] = $post->permalink();
            $rss_items[] = $item;
        }

        break;
}
