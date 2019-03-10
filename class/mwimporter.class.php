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
class MWImporter
{
    use RMModuleAjax;

    /**
     * Shows the initial panel with options to import
     */
    public function panel()
    {
        require XOOPS_ROOT_PATH . '/modules/mywords/include/mw-lang.php';

        RMTemplate::getInstance()->add_style('importer.min.css', 'mywords', ['id' => 'importer-css']);
        RMTemplate::getInstance()->add_script('importer.min.js', 'mywords', ['id' => 'importer-js', 'directory' => 'include', 'footer' => 1]);

        RMTemplate::getInstance()->header();
        RMTemplate::getInstance()->display('admin/mywords-importer.php');
        RMTemplate::getInstance()->footer();
    }

    private function loadCache()
    {
        $file = XOOPS_CACHE_PATH . '/mwimporter.json';

        if (!file_exists($file)) {
            return [];
        }

        $cache = json_decode(file_get_contents($file), true);

        return $cache;
    }

    private function writeCache($cache)
    {
        $file = XOOPS_CACHE_PATH . '/mwimporter.json';

        return file_put_contents($file, json_encode($cache));
    }

    public function close()
    {
        $this->prepare_ajax_response();
        $file = XOOPS_CACHE_PATH . '/mwimporter.json';
        @unlink($file);
        $this->ajax_response('Done', 0, 1);
    }

    /**
     * Get and return de data from publisher before to start the
     * importing.
     */
    public function collect()
    {
        global $xoopsDB, $xoopsSecurity;

        $this->prepare_ajax_response();

        if (!$xoopsSecurity->check(true, false, 'CUTOKEN')) {
            $this->ajax_response(__('Session token not valid!', 'mywords'), 1, 0);
        }

        // Get categories
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('publisher_categories') . ' ORDER BY parentid ASC';
        $result = $xoopsDB->query($sql);
        $categories = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $categories[] = [
                'name' => $row['name'],
                'id' => $row['categoryid'],
            ];
        }

        // Get articles
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('publisher_items') . ' ORDER BY datesub ASC';
        $result = $xoopsDB->query($sql);
        $articles = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $articles[] = [
                'title' => $row['title'],
                'id' => $row['itemid'],
            ];
        }

        $this->ajax_response(__('Data collected', 'mywords'), 0, 1, [
            'categories' => $categories,
            'articles' => $articles,
        ]);
    }

    /**
     * Imports a single category from publisher to MyWords
     */
    public function category()
    {
        global $xoopsSecurity, $xoopsDB;

        $this->prepare_ajax_response();

        $functions = MWFunctions::get();

        if (!$xoopsSecurity->check(true, false, 'CUTOKEN')) {
            $this->ajax_response(__('Session token not valid!', 'mywords'), 1, 0);
        }

        $id = RMHttpRequest::post('id', 'integer', 0);

        if ($id <= 0) {
            $this->ajax_response(
                sprintf(__('Category ID %u is not valid!', 'mywords'), $id),
                0,
                1,
                [
                    'result' => 'error',
                ]
            );
        }

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('publisher_categories') . " WHERE categoryid = $id";
        $result = $xoopsDB->query($sql);

        if ($xoopsDB->getRowsNum($result)) {
            if ($id <= 0) {
                $this->ajax_response(
                    sprintf(__('Category with ID %u was not found!', 'mywords'), $id),
                    0,
                    1,
                    [
                        'result' => 'error',
                    ]
                );
            }
        }

        $row = $xoopsDB->fetchArray($result);
        $cache = $this->loadCache();

        $category = new MWCategory();
        $category->setVar('name', $row['name']);
        $category->setVar('description', $row['description']);
        $category->setVar('shortname', TextCleaner::getInstance()->sweetstring($row['name']));

        // Search for parent
        if (isset($cache['categories'][$row['parentid']])) {
            $category->setVar('parent', $cache['categories'][$row['parentid']]);
        }

        unset($row);

        if ($functions->category_exists($category)) {
            $this->ajax_response(
                sprintf(__('Category %s already exists', 'mywords'), $category->name),
                0,
                1,
                ['result' => 'success']
            );
        }

        if (!$category->save()) {
            $this->ajax_response(
                sprintf(__('Category %s could not be saved!', 'mywords'), $category->name),
                0,
                1,
                ['result' => 'error']
            );
        }

        $cache['categories'][$id] = $category->id();
        $this->writeCache($cache);
        $this->ajax_response(
            sprintf(__('Category %s imported successfully!', 'mywords'), '<strong>' . $category->name . '</strong>'),
            0,
            1,
            ['result' => 'success']
        );
    }

    /**
     * Imports a single article from Publisher
     */
    public function article()
    {
        global $xoopsSecurity, $xoopsDB;

        $this->prepare_ajax_response();

        $functions = MWFunctions::get();

        if (!$xoopsSecurity->check(true, false, 'CUTOKEN')) {
            $this->ajax_response(__('Session token not valid!', 'mywords'), 1, 0);
        }

        $id = RMHttpRequest::post('id', 'integer', 0);

        if ($id <= 0) {
            $this->ajax_response(
                sprintf(__('Article ID %u is not valid!', 'mywords'), $id),
                0,
                1,
                [
                    'result' => 'error',
                ]
            );
        }

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('publisher_items') . " WHERE itemid = $id";
        $result = $xoopsDB->query($sql);

        if ($xoopsDB->getRowsNum($result)) {
            if ($id <= 0) {
                $this->ajax_response(
                    sprintf(__('Article with ID %u was not found!', 'mywords'), $id),
                    0,
                    1,
                    [
                        'result' => 'error',
                    ]
                );
            }
        }

        $row = $xoopsDB->fetchArray($result);
        $cache = $this->loadCache();

        $post = new MWPost();
        $post->setVar('title', $row['title']);
        $post->setVar('shortname', TextCleaner::getInstance()->sweetstring($row['title']));
        $post->setVar('content', $row['body']);

        switch ($row['status']) {
            case 1:
            case 4:
                $status = 'pending';
                break;
            case 2:
                $status = 'publish';
                break;
            case 3:
                $status = 'draft';
                break;
        }

        $post->setVar('status', $status);
        $post->setVar('visibility', 'public');
        $post->setVar('author', $row['uid']);
        $post->setVar('comstatus', 1);
        $post->setVar('pubdate', $row['datesub']);
        $post->setVar('created', $row['datesub']);
        $post->setVar('reads', $row['counter']);
        $post->setVar('description', $row['summary']);
        $post->setVar('keywords', $row['meta_keywords']);
        $post->setVar('format', 'post');

        if (isset($cache['categories'][$row['categoryid']])) {
            $post->add_categories($cache['categories'][$row['categoryid']]);
        }

        unset($row);

        if (!$post->save()) {
            $this->ajax_response(
                sprintf(__('Article %s could not be saved!', 'mywords'), $post->title),
                0,
                1,
                ['result' => 'error']
            );
        }

        $this->ajax_response(
            sprintf(__('Article %s imported successfully!', 'mywords'), '<strong>' . $post->title . '</strong>'),
            0,
            1,
            ['result' => 'success']
        );
    }
}
