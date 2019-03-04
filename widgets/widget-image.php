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
 * @param null|mixed $post
 */

/**
 * Provides a widget to specify the default image for posts
 */
function mywords_widget_image($post = null)
{
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $rm_config;

    $type = RMHttpRequest::request('type', 'string', '');

    $widget = [];
    $widget['title'] = __('Default Image', 'mywords');
    $util = new RMUtilities();

    if (isset($post) && is_a($post, 'MWPost')) {
        if ($post->isNew()) {
            $params = '';
        } else {
            $params = $post->getVar('image', 'e');
        }
    } else {
        $params = '';
    }

    $widget['content'] = '<form name="frmDefimage" id="frm-defimage" method="post">';
    $widget['content'] .= $util->image_manager('image', 'image', $params, ['accept' => 'thumbnail', 'multiple' => 'no']);
    $widget['content'] .= '</form>';

    return $widget;
}
