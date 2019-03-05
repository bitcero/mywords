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
require __DIR__ . '/header.php';

class MyWordsSectionReports
{
    public function __construct()
    {
        global $common;

        $common->location = 'posts';
    }

    public function default()
    {
        global $common, $xoopsDB;

        $common->uris()->redirect_with_message(
            __('Reports must be accessed directly from posts management', 'mywords'),
            'posts.php',
            RMMSG_WARN
        );
    }

    public function viewReports()
    {
        global $common, $xoopsDB;

        $id = $common->httpRequest()->get('id', 'integer', 0);

        if ($id <= 0) {
            $common->uris()->redirect_with_message(
                __('You must provide a valid post ID to view its reports', 'mywords'),
                'posts.php',
                RMMSG_ERROR
            );
        }

        $post = new MWPost($id);
        if ($post->isNew()) {
            $common->uris()->redirect_with_message(
                __('You must specify an existing post before to see reports', 'mywords'),
                'posts.php',
                RMMSG_ERROR
            );
        }

        // Get reports
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('mod_mywords_reports') . " WHERE post = $id ORDER BY `when` DESC";
        $result = $xoopsDB->query($sql);

        if ($xoopsDB->getRowsNum($result) <= 0) {
            $common->uris()->redirect_with_message(
                __('Specified post does not have any report', 'mywords'),
                'posts.php',
                RMMSG_ERROR
            );
        }

        $reports = [];
        $report = new MWReport();

        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $report->assignVars($row);
            $values = $report->getValues();

            if ($values['user'] > 0) {
                $user = new XoopsUser($values['user']);
                if (false === $user->isNew()) {
                    $values['user'] = (object) [
                        'id' => $report->user,
                        'name' => $user->getVar('name'),
                        'uname' => $user->getVar('uname'),
                        'email' => $user->getVar('uname'),
                    ];
                } else {
                    $values['user'] = (object) [
                        'id' => $report->user,
                        'name' => $report->name,
                        'uname' => $report->name,
                        'email' => $report->email,
                    ];
                }
            } else {
                $values['user'] = (object) [
                    'id' => $report->user,
                    'name' => $report->name,
                    'uname' => $report->name,
                    'email' => $report->email,
                ];
            }

            $reports[] = (object) $values;
            $values = null;
        }

        $common->template()->assign('reports', $reports);
        $common->template()->assign('post', $post);

        $common->template()->add_script('reports.min.js', 'mywords', ['id' => 'reports-js', 'footer' => 1, 'directory' => 'include']);
        $common->template()->add_style('admin.min.css', 'mywords', ['id' => 'mywords-css']);

        require  dirname(__DIR__) . '/include/mw-lang.php';

        $common->breadcrumb()->add_crumb(sprintf(__('Reports for %s', 'mywords'), $post->title));
        $common->template()->assign('xoops_pagetitle', sprintf(__('Reports for %s', 'mywords'), $post->title));

        $common->template()->header();

        $common->template()->display('admin/mywords-reports.php', 'module', 'mywords');

        $common->template()->footer();
    }

    public function details()
    {
        global $common;

        $common->ajax()->prepare();

        $common->checkToken();

        $id = $common->httpRequest()->post('id', 'integer', 0);
        if ($id <= 0) {
            $common->ajax()->notifyError(__('You must provide a valid report ID', 'mywords'));
        }

        $report = new MWReport($id);
        if ($report->isNew()) {
            $common->ajax()->notifyError(__('Specified report does not exists or it is not valid!', 'mywords'));
        }

        // Get user
        if ($report->user > 0) {
            $user = new XoopsUser($report->user);
            $common->template()->assign('user', [
                'id' => $user->getVar('uid'),
                'name' => '' == $user->getVar('name') ? $user->getVar('uname') : $user->getVar('name'),
                'email' => $user->getVar('email'),
            ]);
        } else {
            $common->template()->assign('user', [
                'id' => 0,
                'name' => $report->name,
                'email' => $report->email,
            ]);
        }

        $common->template()->assign('report', $report);

        $common->ajax()->response(
            sprintf(__('Report No. %s', 'mywords'), str_pad($report->id(), 5, '0', STR_PAD_LEFT)),
            0,
            1,
            [
                'openDialog' => true,
                'content' => $common->template()->render('admin/mywords-report-details.php', 'module', 'mywords'),
                'windowId' => 'mywords-report-details',
                'width' => 'small',
                'color' => 'primary',
                'icon' => 'svg-rmcommon-report',
            ]
        );
    }

    public function status($status)
    {
        global $common, $xoopsDB;

        $common->ajax()->prepare();

        $common->checkToken();

        $ids = $common->httpRequest()->post('ids', 'array', []);
        if (empty($ids)) {
            $common->ajax()->notifyError(__('You must provide a valid report ID', 'mywords'));
        }

        $sql = 'UPDATE ' . $xoopsDB->prefix('mod_mywords_reports') . " SET status='$status' WHERE id_report IN (" . implode(',', $ids) . ')';

        if ($xoopsDB->queryF($sql)) {
            $statusText = 'accepted' == $status ? 'accepted' : 'waiting';

            $common->ajax()->response(
                sprintf(__('Reports status updated to "%s" successfully', 'mywords'), $statusText),
                0,
                1,
                [
                    'notify' => [
                        'type' => 'accepted' == $status ? 'alert-success' : 'alert-purple',
                        'icon' => 'svg-rmcommon-ok-circle',
                    ],
                    'status' => $status,
                    'ids' => $ids,
                ]
            );
        } else {
            $common->ajax()->notifyError(
                __('Reports status could not be updated:', 'mywords') . $xoopsDB->error()
            );
        }
    }

    public function delete()
    {
        global $common, $xoopsDB;

        $common->ajax()->prepare();

        $common->checkToken();

        $ids = $common->httpRequest()->post('ids', 'array', []);
        if (empty($ids)) {
            $common->ajax()->notifyError(__('You must provide a valid report ID', 'mywords'));
        }

        $sql = 'DELETE FROM ' . $xoopsDB->prefix('mod_mywords_reports') . ' WHERE id_report IN (' . implode(',', $ids) . ')';

        if ($xoopsDB->queryF($sql)) {
            $common->ajax()->response(
                __('Sepecified reports has been deleted successfully', 'mywords'),
                0,
                1,
                [
                    'notify' => [
                        'type' => 'alert-success',
                        'icon' => 'svg-rmcommon-ok-circle',
                    ],
                    'ids' => $ids,
                ]
            );
        } else {
            $common->ajax()->notifyError(
                __('Reports status could not be deleted', 'mywords') . $xoopsDB->error()
            );
        }
    }
}

$mywordsSection = new MyWordsSectionReports();

/**
 * Receive 'action' parameter and take action
 */
$action = $common->httpRequest()->request('action', 'string');

switch ($action) {
    case 'view':
        $mywordsSection->viewReports();
        break;
    case 'details':
        $mywordsSection->details();
        break;
    case 'accept':
        $mywordsSection->status('accepted');
        break;
    case 'waiting':
        $mywordsSection->status('waiting');
        break;
    case 'delete':
        $mywordsSection->delete();
        break;
    default:
        $mywordsSection->default();
        break;
}
