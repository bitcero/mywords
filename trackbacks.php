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
header('Content-Type: text/xml');
require dirname(__DIR__) . '/../mainfile.php';

load_mod_locale('mywords');

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

$id = rmc_server_var($_REQUEST, 'trackback', 0);
if ($id <= 0) {
    die();
}

$post = new MWPost($id);

if ($post->isNew()) {
    die();
}

$editor = new MWEditor($post->getVar('author'));
if ($editor->isNew()) {
    $user = new XoopsUser($post->getVar('author'));
}
$track = new MWTrackback($xoopsConfig['sitename'], $editor->getVar('name'));

$id = $track->post_id; // The id of the item being trackbacked
$url = $track->url; // The URL from which we got the trackback
$title = $track->title; // Subject/title send by trackback
$excerpt = $track->excerpt; // Short text send by trackback
$blog_name = rmc_server_var($_POST, 'blog_name', '');

if ('' == $url || '' == $title || '' == $excerpt) {
    echo $track->recieve(false, __('Sorry, your trackback seems to be invalid!', 'mywords'));
    die();
}

$params = [
    'blogurl' => MWFunctions::get_url(),
    'name' => 'Trackback',
    'email' => '',
    'url' => $url,
    'text' => $excerpt,
    'permalink' => $post->permalink(),
];

$ret = RMEvents::get()->run_event('rmcommon.check.post.spam', $params);

if (!$ret) {
    echo $track->recieve(false, __('Sorry, your trackback seems to be SPAM', 'mywords'));
} else {
    $to = new MWTrackbackObject();
    $to->setVar('date', time());
    $to->setVar('title', $title);
    $to->setVar('blog_name', $blog_name);
    $to->setVar('excerpt', $excerpt);
    $to->setVar('url', $url);
    $to->setVar('post', $post->id());
    if ($to->save()) {
        echo $track->recieve(true);
    } else {
        echo $track->recieve(false, __('We are unable to store your trackback. Please try again later!', 'mywords'));
    }
}

die();
