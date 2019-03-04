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
$mwSettings = $common->settings()::module_settings('mywords');

if ($mwSettings->reports <= 0) {
    $common->uris()::redirect_with_message(
        __('Sorry, you are not allowed to perform this action.', 'mywords'),
        MW_URL,
        RMMSG_WARN
    );
}

if (!$xoopsUser && $mwSettings->report_anonym <= 0) {
    $common->uris()::redirect_with_message(
        __('Sorry, you are not allowed to perform this action.', 'mywords'),
        MW_URL,
        RMMSG_WARN
    );
}

if ($id <= 0) {
    $common->uris()::redirect_with_message(
        __('You must provide a valid post ID', 'mywords'),
        MW_URL,
        RMMSG_WARN
    );
}

$post = new MWPost($id);
if ($post->isNew()) {
    $common->uris()::redirect_with_message(
        __('Specified post does not exists!', 'mywords'),
        MW_URL,
        RMMSG_ERROR
    );
}

/**
 * Check if the report has been sent
 */
$action = $common->httpRequest()::post('action', 'string', '');

if ('submit' == $action) {
    $name = $common->httpRequest()::post('name', 'string', '');
    $email = $common->httpRequest()::post('email', 'string', '');
    $title = $common->httpRequest()::post('title', 'string', '');
    $content = $common->httpRequest()::post('content', 'string', '');
    $user = $xoopsUser ? $xoopsUser->getVar('uid') : 0;

    if ($user <= 0) {
        if ('' == trim($name) || '' == trim($email)) {
            $common->uris()::redirect_with_message(
                __('You must provide your name and your email!', 'mywords'),
                $post->permalink(),
                RMMSG_WARN
            );
        }

        if (false === checkEmail($mail)) {
            $common->uris()::redirect_with_message(
                __('You must provide a valid email!', 'mywords'),
                $post->permalink(),
                RMMSG_WARN
            );
        }
    }

    if ('' == trim($title) || '' == trim($content)) {
        $common->uris()::redirect_with_message(
            __('The main reason and details for report are required', 'mywords'),
            $post->permalink(),
            RMMSG_WARN
        );
    }

    // Check captcha if exists
    if ($common->services()->service('captcha')) {
        if (!$common->services()->captcha->verify()) {
            $common->uris()->redirect_with_message(
                __('CAPTCHA challenge failed! Please try again', 'rmcommon'),
                $post->permalink(),
                RMMSG_ERROR
            );
        }
    }

    if ($xoopsUser) {
        $where = "user = $user";
    } else {
        $where = "name = '$name'";
    }
    $sql = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('mod_mywords_reports') . " WHERE $where AND title = '$title'";

    list($total) = $xoopsDB->fetchRow($xoopsDB->query($sql));

    if ($total > 0) {
        $common->uris()->redirect_with_message(
            __('Another similar report from you already exists', 'rmcommon'),
            $post->permalink(),
            RMMSG_ERROR
        );
    }

    $report = new MWReport();
    $report->post = $post->id();
    $report->user = $user;
    $report->name = $name;
    $report->email = $email;
    $report->when = date('Y-m-d H:i:s');
    $report->title = $title;
    $report->content = $content;
    $report->status = $status;

    if ($report->save()) {
        $common->uris()->redirect_with_message(
            __('Your report has been sent. Thank you!', 'rmcommon'),
            $post->permalink(),
            RMMSG_SUCCESS
        );
    } else {
        $common->uris()->redirect_with_message(
            __('Your report could not been sent. Please try again.', 'rmcommon'),
            $post->permalink(),
            RMMSG_ERROR
        );
    }

    exit();
}

$GLOBALS['xoopsOption']['template_main'] = 'mywords-report.tpl';
$xoopsOption['module_subpage'] = 'report';
require __DIR__ . '/header.php';

$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Report post "%s"', 'mywords'), $post->title));
$xoopsTpl->assign('reportAnonym', !isset($xoopsUser));

$reportLink = $common->uris()::relative_url(MWFunctions::get_url());
if ($xoopsModuleConfig['permalinks'] > 1) {
    $reportLink .= 'report/' . $post->id() . '/';
} else {
    $reportLink .= '?report=' . $post->id();
}

$xoopsTpl->assign('reportLink', $reportLink);

$xoopsTpl->assign('post', [
    'id' => $post->id(),
]);

// Captcha
$xoopsTpl->assign('captcha', $common->services()->captcha->render());

$xoopsTpl->assign('lang', [
    'reportPost' => sprintf(__('Report post &laquo;%s&raquo;', 'mywords'), $post->title),
    'name' => __('Your name:', 'mywords'),
    'email' => __('Your email:', 'mywords'),
    'title' => __('Main reason:', 'mywords'),
    'reasonTitle' => __('Indicates, in max 60 chars, why you are reporting this post', 'mywords'),
    'content' => __('Details:', 'mywords'),
    'whyContent' => __('Provide details about why you are reporting this post. Remember be specific in order to help us (thank you) to investigate and take actions over this post.', 'mywords'),
    'send' => __('Submit Report', 'mywords'),
    'cancel' => __('Cancel', 'mywords'),
]);

require __DIR__ . '/footer.php';
