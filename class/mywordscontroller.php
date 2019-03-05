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
 * This file contains the object MywordsController that
 * will be uses by Common Utilities to do some actions
 * like update comments
 */
class mywordscontroller implements iCommentsController
{
    public function increment_comments_number($comment)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);

        if (!isset($post) || $post <= 0) {
            return;
        }

        $sql = 'UPDATE ' . $db->prefix('mod_mywords_posts') . " SET comments=comments+1 WHERE id_post=$post";
        $db->queryF($sql);
    }

    public function reduce_comments_number($comment)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);

        if (!isset($post) || $post <= 0) {
            return;
        }

        $sql = 'UPDATE ' . $db->prefix('mod_mywords_posts') . " SET comments=comments-1 WHERE id_post=$post AND comments>0";
        $db->queryF($sql);
    }

    public function get_item($params, $com)
    {
        static $posts;

        $params = urldecode($params);
        $output = parse_str($params, $output);
        if (!isset($post) || $post <= 0) {
            return __('Not found', 'mywords');
        }

        if (isset($posts[$post])) {
            return $posts[$post]->getVar('title');
        }

        include_once(XOOPS_ROOT_PATH . '/modules/mywords/class/mwpost.class.php');
        include_once(XOOPS_ROOT_PATH . '/modules/mywords/class/mwfunctions.php');
        $item = new MWPost($post);
        if ($item->isNew()) {
            return __('Not found', 'mywords');
        }

        $posts[$post] = $item;

        return $item->getVar('title');
    }

    public function get_item_url($params, $com)
    {
        static $posts;

        $params = urldecode($params);
        $output = parse_str($params, $output);
        if (!isset($post) || $post <= 0) {
            return '';
        }

        if (isset($posts[$post])) {
            $ret = $posts[$post]->permalink();

            return $ret;
        }

        include_once(XOOPS_ROOT_PATH . '/modules/mywords/class/mwpost.class.php');
        include_once(XOOPS_ROOT_PATH . '/modules/mywords/class/mwfunctions.php');
        $item = new MWPost($post);
        if ($item->isNew()) {
            return '';
        }

        $posts[$post] = $item;

        return $item->permalink();
    }

    public function get_main_link()
    {
        $mc = RMSettings::module_settings('mywords');

        if ($mc->permalinks > 1) {
            return XOOPS_URL . $mc->basepath;
        }

        return XOOPS_URL . '/modules/mywords';
    }

    public static function getInstance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
