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

$common->location = 'posts';

$tpl->add_help(
    __('Posts help', 'mywords'),
    'http://www.xoopsmexico.net/docs/mywords/articulos/'
);

/**
 * Muestra los envíos existentes
 * @param mixed $aprovado
 */
function showPosts($aprovado = -1)
{
    global $db, $xoopsSecurity, $xoopsModule, $xoopsModuleConfig, $common;

    $mc = &$xoopsModuleConfig;

    $keyw = '';
    $op = '';
    $cat = 0;
    $status = '';
    foreach ($_REQUEST as $k => $v) {
        $$k = $v;
    }

    $tbl1 = $db->prefix('mod_mywords_posts');
    $tbl2 = $db->prefix('mod_mywords_catpost');
    $tbl3 = $db->prefix('mod_mywords_reports');
    $and = false;

    if ($cat > 0) {
        $sql = "SELECT COUNT(*) FROM $tbl1 a, $tbl2 b WHERE b.cat='$cat' AND a.id_post=b.post";
        $and = true;
    } else {
        $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_mywords_posts');
    }

    if (isset($status) && '' != $status) {
        define('RMCSUBLOCATION', $status);
        $sql .= $and ? " AND a.status='$status'" : " WHERE status='$status'";
        $and = true;
    }

    if (isset($author) && $author > 0) {
        $sql .= $and ? " AND a.author=$author" : " WHERE author=$author";
        $and = true;
    }

    if ('' != trim($keyw)) {
        $sql .= $and ? " AND title LIKE '%$keyw%'" : " WHERE title LIKE '%$keyw%'";
    }

    /**
     * Paginacion de Resultados
     */
    $page = rmc_server_var($_GET, 'page', 1);
    $limit = isset($limit) && $limit > 0 ? $limit : 15;
    list($num) = $db->fetchRow($db->query($sql));

    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num <= 0 ? 0 : ($page - 1) * $limit;

    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('posts.php?limit=' . $limit . '&page={PAGE_NUM}');

    $sql .= " ORDER BY id_post DESC LIMIT $start,$limit";
    $sql = str_replace('SELECT COUNT(*)', 'SELECT *', $sql);

    $result = $db->query($sql);
    $posts = [];
    while (false !== ($row = $db->fetchArray($result))) {
        $post = new MWPost();
        $post->assignVars($row);

        # Enlace para el artículo
        $day = date('d', $post->getVar('pubdate'));
        $month = date('m', $post->getVar('pubdate'));
        $year = date('Y', $post->getVar('pubdate'));
        $postlink = MWFunctions::get_url();
        $postlink .= 1 == $mc['permalinks'] ? '?post=' . $post->id() : (2 == $mc['permalinks'] ? "$day/$month/$year/" . $post->getVar('shortname') . '/' : 'post/' . $post->id());

        $posts[] = [
            'id' => $post->id(),
            'title' => $post->getVar('title'),
            'date' => $post->getVar('pubdate') > 0 ? formatTimestamp($post->getVar('pubdate')) : '<em>' . __('Not published', 'mywords') . '</em>',
            'created' => formatTimestamp($post->getVar('created')),
            'comments' => $post->getVar('comments'),
            'uid' => $post->getVar('author'),
            'uname' => $post->getVar('authorname'),
            'link' => $postlink,
            'status' => $post->getVar('status'),
            'categories' => $post->get_categories_names(true, ',', true, 'admin'),
            'tags' => $post->tags(false),
            'reads' => $post->getVar('reads'),
            'reports' => $post->reports(),
        ];
    }

    // Published count
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_mywords_posts') . " WHERE status='publish'";
    list($pub_count) = $db->fetchRow($db->query($sql));
    // Drafts count
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_mywords_posts') . " WHERE status='draft'";
    list($draft_count) = $db->fetchRow($db->query($sql));
    // Pending count
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_mywords_posts') . " WHERE status='pending'";
    list($pending_count) = $db->fetchRow($db->query($sql));

    // Confirm message
    RMTemplate::get()->add_head(
        '<script type="text/javascript">
        function post_del_confirm(post, id){
            var string = "' . __('Do you really want to delete \"%s\"', 'mywords') . '";
            string = string.replace("%s", post);
            var ret = confirm(string);

            if (ret){
                $("#form-posts input[type=checkbox]").removeAttr("checked");
                $("#post-"+id).attr("checked","checked");
                $("#posts-op").val("delete");
                $("#form-posts").submit();
            }
        }
     </script>'
    );

    RMTemplate::get()->add_script(RMCURL . '/include/js/jquery.checkboxes.js');
    MWFunctions::include_required_files();

    $title_by_status = '';
    switch ($status) {
        case 'publish':
            $title_by_status = __('Published Posts', 'mywords');
            break;
        case 'draft':
            $title_by_status = __('Drafts', 'mywords');
            break;
        case 'waiting':
            $title_by_status = __('Pending of Review', 'dtransport');
            break;
    }

    if ('' != $title_by_status) {
        RMBreadCrumb::get()->add_crumb(__('Posts management', 'mywords'), 'posts.php');
        RMBreadCrumb::get()->add_crumb($title_by_status);
        RMTemplate::get()->assign('xoops_pagetitle', sprintf(__('Posts Management: %s', 'mywords'), $title_by_status));
    } else {
        RMBreadCrumb::get()->add_crumb(__('Posts management', 'mywords'));
        RMTemplate::get()->assign('xoops_pagetitle', __('Posts Management', 'mywords'));
    }

    xoops_cp_header();
    require_once dirname(__DIR__) . '/templates/admin/mywords-posts.php';
    xoops_cp_footer();
}
/**
 * Muestra el formulario para la creación de un nuevo artículo
 * @param mixed $edit
 */
function newForm($edit = 0)
{
    global $db, $xoopsModule, $myts, $util, $xoopsConfig, $tpl, $xoopsSecurity, $cuSettings;

    define('RMCSUBLOCATION', 'new_post');

    if ($edit) {
        $id = rmc_server_var($_GET, 'id', 0);
        if ($id <= 0) {
            redirectMsg('posts.php', __('Please, specify a valid post ID', 'mywords'), 1);
            die();
        }
        $post = new MWPost($id);
        if ($post->isNew()) {
            redirectMsg('posts.php', __('Specified post does not exists!', 'mywords'), 1);
            die();
        }
    }

    // Context help
    $tpl->add_help(
        __('Publish articles', 'mywords'),
        'http://www.xoopsmexico.net/docs/mywords/publicar-entradas/'
    );

    MWFunctions::include_required_files(false);

    RMBreadCrumb::get()->add_crumb(__('Posts', 'mywords'), 'posts.php');
    RMBreadCrumb::get()->add_crumb(__('Write post', 'mywords'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Write post', 'mywords'));

    $head = '<script type="text/javascript" src="' . MW_URL . '/include/forms_post.js"></script>';
    xoops_cp_header($head);

    include RMCPATH . '/class/form.class.php';
    /*include RMCPATH.'/class/fields/formelement.class.php';
    include RMCPATH.'/class/fields/editor.class.php';*/
    TinyEditor::getInstance()->add_config('elements', 'content_editor');
    //TinyEditor::getInstance()->add_config('theme_advanced_buttons1', 'bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,spellchecker,fullscreen,|,exm_more,exm_adv', true);
    //TinyEditor::getInstance()->add_config('theme_advanced_buttons2','formatselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,media,charmap,|,outdent,indent,|,undo,redo,|,exm_img,exm_icons,exm_page', true);
    //echo $post->getVar('content'); die();
    $editor = new RMFormEditor('', 'content', '100%', '350px', $edit ? $post->getVar('content', 'tiny' == $cuSettings->editor_type ? 's' : 'e') : '');

    // Get current metas
    $meta_names = MWFunctions::get()->get_metas();
    //RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js');
    //RMTemplate::get()->add_script(RMCURL.'/include/js/forms.js');
    //RMTemplate::get()->add_head('<script type="text/javascript">$("form#mw-form-posts").validate();</script>');

    require  dirname(__DIR__) . '/templates/admin/mywords-formposts.php';

    xoops_cp_footer();
}

/**
 * Elimina un artículo de la base de datos
 */
function deletePost()
{
    global $xoopsSecurity;

    $posts = rmc_server_var($_POST, 'posts', []);

    if (empty($posts)) {
        redirectMsg('posts.php', __('Select one post at least!', 'mywords'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('posts.php', __('Session token expired!', 'mywords'), 1);
        die();
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'SELECT * FROM ' . $db->prefix('mod_mywords_posts') . ' WHERE id_post IN (' . implode(',', $posts) . ')';
    $result = $db->query($sql);

    while (false !== ($row = $db->fetchArray($result))) {
        $post = new MWPost();
        $post->assignVars($row);

        if (!$post->delete()) {
            showMessage(sprintf(__('Errors ocurred while deleting "%s"', 'mw_categories'), $post->getVar('title')), 1);
        }

        RMFunctions::delete_comments('mywords', urlencode('post=' . $post->id()));
    }

    redirectMsg('posts.php', __('Database updated!', 'mw_categories'), 0);
}

function set_posts_status($status)
{
    global $xoopsSecurity;

    $posts = rmc_server_var($_POST, 'posts', []);
    $limit = rmc_server_var($_POST, 'limit', 15);
    $keyw = rmc_server_var($_POST, 'keyw', '');
    $page = rmc_server_var($_POST, 'page', 1);

    $q = "limit=$limit&keyw=$keyw&page=$page";

    if (empty($posts)) {
        redirectMsg('posts.php?' . $q, __('Select one post at least!', 'mywords'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('posts.php?' . $q, __('Session token expired!', 'mywords'), 1);
        die();
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'UPDATE ' . $db->prefix('mod_mywords_posts') . " SET status='$status' WHERE id_post IN (" . implode(',', $posts) . ')';
    if (!$db->queryF($sql)) {
        redirectMsg('posts.php?' . $q, __('Posts could not be updated!', 'mw_categories'), 1);
        die();
    }

    redirectMsg('posts.php?' . $q, __('Posts updated successfully!', 'mywords'), 0);
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op) {
    case 'new':
        newForm();
        break;
    case 'edit':
        newForm(1);
        break;
    case 'saveretedit':
        saveEdited(0);
        break;
    case 'saveedit':
    case 'publishedit':
        saveEdited(1);
        break;
    case 'delete':
        deletePost();
        break;
    case 'trackbacks':
        require __DIR__ . '/trackbacks.php';
        break;
    case 'waiting':
        showPosts(0);
        break;
    case 'approved':
        showPosts(1);
        break;
    case 'status-waiting':
        set_posts_status('waiting');
        break;
    case 'status-draft':
        set_posts_status('draft');
        break;
    case 'status-published':
        set_posts_status('publish');
        break;
    default:
        showPosts();
        break;
}
