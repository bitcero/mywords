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

/**
 * This file execute the pings for a given post
 */
require dirname(__DIR__) . '/../mainfile.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

$id = rmc_server_var($_GET, 'post', 0);

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
$tracks = $post->getVar('toping');

if (empty($tracks)) {
    die();
}

$pinged = $post->getVar('pinged');
$toping = $post->getVar('toping');
$tp = [];

$tback = new MWTrackback($xoopsModuleConfig['blogname'], $editor->isNew() ? $user->getVar('uname') : $editor->getVar('name'));
foreach ($tracks as $t) {
    if (!empty($pinged) && in_array($t, $pinged, true)) {
        continue;
    }
    $ret = $tback->ping($t, $post->permalink(), $post->getVar('title'), TextCleaner::getInstance()->truncate($post->content(true), 240));
    if ($ret) {
        $pinged[] = $t;
    } else {
        $tp[] = $t;
    }
}

$post->setVar('toping', empty($tp) ? '' : $tp);
$post->setVar('pinged', $pinged);
$post->update(true);

die();
