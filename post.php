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
$GLOBALS['xoopsOption']['template_main'] = 'mywords-post.tpl';
$xoopsOption['module_subpage'] = 'post';
require __DIR__ . '/header.php';

if ($post <= 0) {
    redirect_header(MWFunctions::get_url(), 2, '');
    die();
}

$post = new MWPost($post);
// Comprobamos que exista el post
if ($post->isNew()) {
    redirect_header(mw_get_url(), 2, __('Document not found', 'mywords'));
    die();
}
// Comprobamos permisos de acceso al post
if (!$post->user_allowed()) {
    redirect_header(MWFunctions::get_url(), 2, __('Sorry, you are not allowed to view this post', 'mywords'));
    die();
}

// Check if post belong to some category
if (count($post->get_categos()) <= 0) {
    $post->update();
}

# Generamos los vínculos
$day = date('d', $post->getVar('pubdate'));
$month = date('m', $post->getVar('pubdate'));
$year = date('Y', $post->getVar('pubdate'));

$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;

# Cargamos los datos del autor
$editor = new MWEditor($post->getVar('author'), 'user');
if ($editor->isNew()) {
    if ($xoopsUser && $xoopsUser->uid() == $post->author) {
        $user = $xoopsUser;
    } else {
        $user = new RMUser($post->author);
    }

    $editor->uid = $user->uid();
    $editor->name = $user->getVar('name');
    $editor->shortname = $user->getVar('uname');
    $editor->privileges = [ 'tags', 'tracks', 'comms' ];
    $editor->save();
}

# Texto de continuar leyendo

$xoopsTpl->assign('xoops_pagetitle', '' != $post->getVar('customtitle') ? $post->getVar('customtitle') : $post->getVar('title'));

# Cargamos los comentarios del Artículo
if ($page <= 0) {
    $path = explode('/', $request);
    $srh = array_search('page', $path, true);
    if (isset($path[$srh]) && 'page' == $path[$srh]) {
        if (!isset($path[$srh])) {
            $page = 1;
        } else {
            $page = $path[$srh + 1];
        }
    } else {
        $page = 1;
    }
}

$post->add_read();

// Navegación entre artículos
if ($xoopsModuleConfig['shownav']) {
    $sql = 'SELECT * FROM ' . $db->prefix('mod_mywords_posts') . ' WHERE id_post<' . $post->id() . " AND status='publish' ORDER BY id_post DESC LIMIT 0, 1";
    $result = $db->query($sql);
    $pn = new MWPost();
    // Anterior
    if ($db->getRowsNum($result) > 0) {
        $pn->assignVars($db->fetchArray($result));
        $xoopsTpl->assign('prev_post', ['link' => $pn->permalink(), 'title' => $pn->getVar('title')]);
    }

    // Siguiente
    $sql = 'SELECT * FROM ' . $db->prefix('mod_mywords_posts') . ' WHERE id_post>' . $post->id() . " AND status='publish' ORDER BY id_post ASC LIMIT 0, 1";
    $result = $db->query($sql);
    if ($db->getRowsNum($result) > 0) {
        $pn->assignVars($db->fetchArray($result));
        $xoopsTpl->assign('next_post', ['link' => $pn->permalink(), 'title' => $pn->getVar('title')]);
    }
}
$xoopsTpl->assign('shownav', $xoopsModuleConfig['shownav']);

if ($xoopsUser && ($xoopsUser->isAdmin() || $editor->getVar('uid') == $xoopsUser->uid())) {
    $editLink = XOOPS_URL . '/modules/mywords/admin/posts.php?op=edit&amp;id=' . $post->id();
    $xoopsTpl->assign('can_edit', true);
    $xoopsTpl->assign('edit_link', $editLink);
    unset($editLink);
}

$xoopsTpl->assign('lang_reads', sprintf(__('%u views', 'mywords'), $post->getVar('reads')));

// Post pages
$total_pages = $post->total_pages();
$nav = new RMPageNav($total_pages, 1, $page, 5);
$nav->target_url($post->permalink() . ($mc['permalinks'] > 1 ? 'page/{PAGE_NUM}/' : '&amp;page={PAGE_NUM}'));
$xoopsTpl->assign('post_navbar', $nav->render(true));

// Post data

$post_arr = [
    'id' => $post->id(),
    'title' => $post->getVar('title'),
    'published' => sprintf(__('%s by %s', 'mywords'), MWFunctions::format_time($post->getVar('pubdate')), '<a href="' . $editor->permalink() . '">' . (isset($editor) ? $editor->getVar('name') : __('Anonymous', 'mywords')) . '</a>'),
    'text' => $post->content(false, $page),
    'cats' => $post->get_categos('data'),
    'tags' => $post->tags(false),
    'trackback' => $post->getVar('pingstatus') ? MWFunctions::get_url(true) . $post->id() : '',
    'meta' => $post->get_meta('', false),
    'time' => $post->getVar('pubdate'),
    'image' => $post->image(),
    'video' => $post->video,
    'player' => '' != $post->video ? $post->video_player() : '',
    'author' => [
                            'name' => '' != $editor->getVar('name') ? $editor->name : $editor->shortname,
                            'id' => $editor->id(),
                            'link' => $editor->permalink(),
                            'bio' => $editor->getVar('bio'),
                            'email' => $editor->data('email'),
                            'uid' => $editor->uid,
                            'url' => $editor->data('url'),
                            'avatar' => $cuServices->avatar->getAvatarSrc($editor->data('email'), 100),// RMEvents::get()->run_event( 'rmcommon.get.avatar', $editor->data('email') )
                       ],
    'alink' => $editor->permalink(),
    'format' => $post->format,
    'comments' => $post->comments,
    'lang_comments' => sprintf(__('%u Comments', 'mywords'), $post->comments),
    'comments_enabled' => $post->comstatus,
];

// Enable reports
if ($xoopsModuleConfig['reports']) {
    $reportLink = MWFunctions::get_url();

    if ($xoopsModuleConfig['permalinks'] > 1) {
        $reportLink .= 'report/' . $post->id() . '/';
    } else {
        $reportLink .= '?report=' . $post->id();
    }

    if ($xoopsUser) {
        $xoopsTpl->assign('canReport', true);
        $xoopsTpl->assign('reportLink', $reportLink);
    } elseif ($xoopsModuleConfig['report_anonym']) {
        $xoopsTpl->assign('canReport', true);
        $xoopsTpl->assign('reportLink', $reportLink);
    }
}

$xoopsTpl->assign('full_post', 1);
$xoopsTpl->assign('lang_editpost', __('Edit Post', 'mywords'));
$xoopsTpl->assign('lang_postedin', __('Posted in:', 'mywords'));
$xoopsTpl->assign('lang_taggedas', __('Tagged as:', 'mywords'));
$xoopsTpl->assign('lang_report', __('Report', 'mywords'));
$xoopsTpl->assign('enable_images', $xoopsModuleConfig['list_post_imgs']);

// Plugins?
$post_arr = RMEvents::get()->run_event('mywords.view.post', $post_arr, $post);
$xoopsTpl->assign('post', $post_arr);

// Related posts
if ($xoopsModuleConfig['related']) {
    $rtags = $post->tags();
    $tt = [];
    foreach ($rtags as $tag) {
        $tt[] = $tag['id_tag'];
    }
    unset($rtags, $tag);
    $related = MWFunctions::get_posts_by_tag($tt, 0, $xoopsModuleConfig['related_num'], 'RAND()', '', 'publish', $post->id());
    unset($tt);

    if (!empty($related)) {
        $tf = new RMTimeFormatter(0, '%d% %T%, %Y%');
        foreach ($related as $rpost) {
            $xoopsTpl->append('relatedPosts', [
                'title' => $rpost->getVar('title'),
                'pubdate' => $tf->format($rpost->getVar('pubdate')),
                'link' => $rpost->permalink(),
                'image' => RMImage::get()->load_from_params($rpost->image),
            ]);
        }
    }
}

// Social sites
if ($xoopsModuleConfig['showbookmarks']) {
    foreach ($socials as $site) {
        $xoopsTpl->append('socials', [
            'title' => $site->getVar('title'),
            'icon' => $site->getVar('icon'),
            'url' => $site->link($post->getVar('title'), $post->permalink(), TextCleaner::truncate($post->content(true), 60)),
            'alt' => $site->getVar('alt'),
        ]);
    }
}

unset($tags_list);

// Comments
// When use the common utilities comments system you can choose between
// use of Common Utilities templates or use your own templates
// We will use MyWords included templates
if ($post->getVar('comstatus')) {
    //$comms = RMFunctions::get_comments('mywords','post='.$post->id(), 'module', 0, null, false);
    $comments = $common->comments()->load([
        'object' => 'mywords',
        'type' => 'module',
        'identifier' => 'post=' . $post->id(),
        'assign' => false,
        'url' => MW_URL . '/post.php',
    ]);
    if (count($comments) != $post->getVar('comments')) {
        //$post->setVar('comments', count($comments));
        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('mod_mywords_posts') . ' SET `comments`=' . count($comments) . ' WHERE id_post=' . $post->id());
    }
    $xoopsTpl->assign('comments', $comments);
    // Comments form
    $xoopsTpl->assign('comments_form', $common->comments()->form([
        'url' => MW_URL . '/post.php',
        'object' => 'mywords',
        'type' => 'module',
        'identifier' => 'post=' . $post->id(),
        'file' => MW_PATH . '/class/mywordscontroller.php',
    ]));
}

// Load trackbacks
$trackbacks = $post->trackbacks();
foreach ($trackbacks as $tb) {
    $xoopsTpl->append('trackbacks', [
        'id' => $tb->id(),
        'title' => $tb->getVar('title'),
        'blog' => $tb->getVar('blog_name'),
        'url' => $tb->getVar('url'),
        'text' => $tb->getVar('excerpt'),
        'date' => formatTimestamp($tb->getVar('date'), 'c'),
    ]);
}

// Language
$xoopsTpl->assign('lang_publish', __('Published in', 'mywords'));
$xoopsTpl->assign('lang_tagged', __('Tagged as', 'mywords'));
$xoopsTpl->assign('lang_numcoms', sprintf(__('%u Comments', 'mywords'), $post->getVar('comments')));
$xoopsTpl->assign('lang_numtracks', sprintf(__('%u trackbacks', 'mywords'), count($trackbacks)));
$xoopsTpl->assign('lang_trackback', __('Trackback', 'mywords'));
$xoopsTpl->assign('lang_homemw', __('Main Page', 'mywords'));
$xoopsTpl->assign('lang_related', __('Related Posts', 'mywords'));
$xoopsTpl->assign('enable_images', $xoopsModuleConfig['post_imgs']);

//Trackback
if ($post->getVar('pingstatus')) {
    $tb = new MWTrackback($xoopsConfig['sitename'], $editor->getVar('name'));
    RMTemplate::get()->add_head(
        $tb->rdf_autodiscover(date('r', $post->getVar('pubdate')), $post->getVar('title'), TextCleaner::getInstance()->truncate($post->content(true), 255), $post->permalink(), MWFunctions::get_url(true) . $post->id(), $editor->getVar('name'))
    );
}

$rmf = RMFunctions::get();
$description = $post->getVar('description', 'e');
$keywords = $post->getVar('keywords', 'e');
$rmf->add_keywords_description('' != $description ? $description : '', '' != $keywords ? $keywords : '');

// Send pings?
$pings = $post->getVar('toping');
$xoopsTpl->assign('pingnow', empty($pings));

require __DIR__ . '/footer.php';
