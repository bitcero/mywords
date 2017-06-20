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

$amod = xoops_getActiveModules();
if(!in_array("rmcommon",$amod)){
    $error = "<strong>WARNING:</strong> MyWords requires %s to be installed!<br />Please install %s before trying to use MyWords";
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    xoops_error($error);
    $error = '%s is not installed! This might cause problems with functioning of MyWords and entire system. To solve, install %s or uninstall MyWords and then delete module folder.';
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    trigger_error($error, E_USER_WARNING);
    echo "<br />";
} else {
    $mc = RMSettings::module_settings('mywords');
}

if (!function_exists("__")){
    function __($text, $d){
        return $text;
    }
}

if(function_exists("load_mod_locale")) load_mod_locale('mywords');

$modversion['name'] = "MyWords";
$modversion['description'] = _MI_MW_DESC;
$modversion['version'] = '2.2';
$modversion['help'] = "docs/readme.html";
$modversion['license'] = "GPL v2";
$modversion['official'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "mywords";
$modversion['onInstall'] = 'include/install.php';
$modversion['onUpdate'] = 'include/install.php';

/**
 * Common Utilities Setup
 */
// Behaviour
$modversion['rmnative'] = 1;
$modversion['url'] = 'https://github.com/bitcero/mywords';
$modversion['rmversion'] = array('major'=>2,'minor'=>2,'revision'=>63, 'stage'=>0,'name'=>'MyWords');
$modversion['rewrite'] = 0;
$modversion['permissions'] = 'include/permissions.php';
$modversion['updateurl'] = "http://www.xoopsmexico.net/modules/vcontrol/";
// Icons
$modversion['icon']   = "svg-rmcommon-comment text-warning";
// Credits
$modversion['author'] = "Eduardo Cortés";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Eduardo Cortés";
$modversion['authorurl'] = "http://eduardocortes.mx";
$modversion['credits'] = "Eduardo Cortés";

// Social links
$modversion['social'][0] = array('title' => __('Twitter', 'mywords'),'type' => 'twitter','url' => 'http://www.twitter.com/bitcero/');
$modversion['social'][1] = array('title' => __('Facebook', 'mywords'),'type' => 'facebook-square','url' => 'http://www.facebook.com/eduardo.cortes.hervis/');
$modversion['social'][2] = array('title' => __('Instagram', 'mywords'),'type' => 'instagram','url' => 'http://www.instagram.com/eduardocortesh/');
$modversion['social'][3] = array('title' => __('LinkedIn', 'mywords'),'type' => 'linkedin-square','url' => 'http://www.linkedin.com/in/bitcero/');
$modversion['social'][4] = array('title' => __('GitHub', 'mywords'),'type' => 'github','url' => 'http://www.github.com/bitcero/');
$modversion['social'][5] = array('title' => __('My Blog', 'mywords'),'type' => 'quote-left','url' => 'http://eduardocortes.mx');

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['hasMain'] = 1;
$modversion['sub'][0]['name'] = __('Submit Article','mywords');
$modversion['sub'][0]['url'] = $mc->permalinks > 1 ? "submit/" : 'submit.php';

// Archivo SQL
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "mywords_search";

// Tablas
$modversion['tables'] = array(
    "mod_mywords_categories",
    "mod_mywords_posts",
    "mod_mywords_catpost",
    "mod_mywords_trackbacks",
    "mod_mywords_editors",
    "mod_mywords_bookmarks",
    "mod_mywords_meta",
    "mod_mywords_tags",
    "mod_mywords_tagspost",
    "mod_mywords_reports"
);

// Plantillas
$modversion['templates'] = array(
    array(
        'file' =>'mywords-index.tpl',
        'description' => __('Homepage of module', 'mywords')
    ),
    array(
        'file' => 'mywords-post.tpl',
        'description' => __('Full post template', 'mywords')
    ),
    array(
        'file' => 'mywords-post-header.tpl',
        'description' => __('Common header for posts types', 'mywords')
    ),
    array(
        'file' => 'mywords-cats.tpl',
        'description' => __('Content of a category', 'mywords')
    ),
    array(
        'file' => 'mywords-author.tpl',
        'description' => 'Post list from a specific author'
    ),
    array(
        'file' => 'mywords-single-post.tpl',
        'description' => __('The specific post content')
    ),
    array(
        'file' => 'mywords-password.tpl',
        'description' => __('Form for protected posts', 'mywords')
    ),
    array(
        'file' => 'mywords-tag.tpl',
        'description' => __('Posts list for a specific tag', 'mywords')
    ),
    array(
        'file' => 'mywords-date.tpl',
        'description' => __('Posts from a specific date', 'mywords')
    ),
    [
        'file' => 'mywords-report.tpl',
        'description' => __('Form to report posts', 'mywords')
    ],
    array(
        'file' => 'formats/post.tpl',
        'description' => __('Template for normal post', 'mywords')
    ),
    array(
        'file' => 'formats/video.tpl',
        'description' => __('Template for video post', 'mywords')
    ),
    array(
        'file' => 'formats/gallery.tpl',
        'description' => __('Template for gallery post', 'mywords')
    ),
    array(
        'file' => 'formats/image.tpl',
        'description' => __('Template for image post', 'mywords')
    ),
    array(
        'file' => 'formats/mini.tpl',
        'description' => __('Template for mini post', 'mywords')
    ),
    array(
        'file' => 'formats/quote.tpl',
        'description' => __('Template for quote post', 'mywords')
    ),
    array(
        'file' => 'formats/video-player.tpl',
        'description' => __('Template for video player', 'mywords')
    )
);


// Blog name
$modversion['config'][0]['name'] = 'blogname';
$modversion['config'][0]['title'] = '_MI_MW_BLOGNAME';
$modversion['config'][0]['description'] = '_MI_MW_BLOGNAMED';
$modversion['config'][0]['formtype'] = 'textbox';
$modversion['config'][0]['valuetype'] = 'text';
$modversion['config'][0]['default'] = $xoopsConfig['sitename'];

// Formato de los enlaces
$modversion['config'][1]['name'] = 'permalinks';
$modversion['config'][1]['title'] = '_MI_MW_PERMAFORMAT';
$modversion['config'][1]['description'] = '_MI_MW_PERMA_DESC';
$modversion['config'][1]['formtype'] = 'select';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 1;
$modversion['config'][1]['options'] = array(__('Default','mywords')=>1, __('Based on date and name','mywords')=>2, __('Numeric format','mywords')=>3);

$modversion['config'][2]['name'] = 'basepath';
$modversion['config'][2]['title'] = '_MI_MW_BASEPATH';
$modversion['config'][2]['description'] = '_MI_MW_BASEPATHD';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['size'] = '50';
$modversion['config'][2]['default'] = '/modules/mywords';
$modversion['config'][2]['order'] = 0;

// Tags limit
$modversion['config'][3]['name'] = 'tags_widget_limit';
$modversion['config'][3]['title'] = '_MI_MW_WIDGETTAGS';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 10;

// Posts list limit number
$modversion['config'][4]['name'] = 'posts_limit';
$modversion['config'][4]['title'] = '_MI_MW_PPP';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 10;

// CSS File
$modversion['config'][5]['name'] = 'use_css';
$modversion['config'][5]['title'] = '_MI_MW_CSS';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 1;

// Navigation bar
$modversion['config'][6]['name'] = 'shownav';
$modversion['config'][6]['title'] = '_MI_MW_SHOWNAV';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype'] = 'yesno';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = 1;

// Social bookmarks
$modversion['config'][7]['name'] = 'showbookmarks';
$modversion['config'][7]['title'] = '_MI_MW_SHOWBOOKMARKS';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'yesno';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 1;

// Enable images in posts
$modversion['config'][8]['name'] = 'list_post_imgs';
$modversion['config'][8]['title'] = '_MI_MW_ENABLELISTIMAGES';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype'] = 'yesno';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = 1;

// Image size for posts list
$modversion['config'][9]['name'] = 'list_post_imgs_size';
$modversion['config'][9]['title'] = '_MI_MW_LISTIMAGESSIZE';
$modversion['config'][9]['description'] = '_MI_MW_LISTIMAGESSIZED';
$modversion['config'][9]['formtype'] = 'textbox';
$modversion['config'][9]['valuetype'] = 'text';
$modversion['config'][9]['default'] = 'thumbnail';

// Enable images in posts
$modversion['config'][10]['name'] = 'post_imgs';
$modversion['config'][10]['title'] = '_MI_MW_ENABLEPOSTIMAGES';
$modversion['config'][10]['description'] = '';
$modversion['config'][10]['formtype'] = 'yesno';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = 1;

// Image size for posts list
$modversion['config'][11]['name'] = 'post_imgs_size';
$modversion['config'][11]['title'] = '_MI_MW_POSTIMAGESSIZE';
$modversion['config'][11]['description'] = '_MI_MW_LISTIMAGESSIZED';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'text';
$modversion['config'][11]['default'] = 'large';

// Related posts
$modversion['config'][12]['name'] = 'related';
$modversion['config'][12]['title'] = '_MI_MW_RELATED';
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['formtype'] = 'yesno';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 1;

// Related posts num
$modversion['config'][13]['name'] = 'related_num';
$modversion['config'][13]['title'] = __('Number of related posts', 'mywords');
$modversion['config'][13]['description'] = __('Specify the limit of posts that will be shown in posts content', 'mywords');
$modversion['config'][13]['formtype'] = 'textbox';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = 5;

// Related posts num
$modversion['config'][14]['name'] = 'submit';
$modversion['config'][14]['title'] = __('Enable posts submission', 'mywords');
$modversion['config'][14]['description'] = __('By enabling this option, registered users can submit their own articles to MyWords', 'mywords');
$modversion['config'][14]['formtype'] = 'yesno';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] = 1;

// Related posts num
$modversion['config'][15]['name'] = 'approve';
$modversion['config'][15]['title'] = __('Auto approve posts submitted by editors', 'mywords');
$modversion['config'][15]['description'] = __('By enabling this option, the posts submitted by editors will automatically approve', 'mywords');
$modversion['config'][15]['formtype'] = 'yesno';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] = 0;

// Reports
$modversion['config'][16]['name'] = 'reports';
$modversion['config'][16]['title'] = __('Enable posts reports', 'mywords');
$modversion['config'][16]['description'] = __('Allow to users send reports for posts', 'mywords');
$modversion['config'][16]['formtype'] = 'yesno';
$modversion['config'][16]['valuetype'] = 'int';
$modversion['config'][16]['default'] = 1;

// Anonymous reports
$modversion['config'][17]['name'] = 'report_anonym';
$modversion['config'][17]['title'] = __('Allow reports for anonoymous users', 'mywords');
$modversion['config'][17]['description'] = __('This option allows to anonymous users to send reports', 'mywords');
$modversion['config'][17]['formtype'] = 'yesno';
$modversion['config'][17]['valuetype'] = 'int';
$modversion['config'][17]['default'] = 0;

// Bloque Categorias
$modversion['blocks'][1]['file'] = "block.cats.php";
$modversion['blocks'][1]['name'] = __('Categories','mywords');
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "mywordsBlockCats";
$modversion['blocks'][1]['edit_func'] = "mywordsBlockCatsEdit";
$modversion['blocks'][1]['template'] = 'bk-mywords-categos.tpl';
$modversion['blocks'][1]['options'] = "1";

// Bloque Recientes
$modversion['blocks'][2]['file'] = "block.recent.php";
$modversion['blocks'][2]['name'] = __('Recent Posts','mywords');
$modversion['blocks'][2]['description'] = "";
$modversion['blocks'][2]['show_func'] = "mywordsBlockRecent";
$modversion['blocks'][2]['edit_func'] = "mywordsBlockRecentEdit";
$modversion['blocks'][2]['template'] = 'bk-mywords-recent.tpl';
$modversion['blocks'][2]['options'] = "10|recent|1|50|1|0";

// Tags
$modversion['blocks'][3]['file'] = "block.tags.php";
$modversion['blocks'][3]['name'] = __('Tags','mywords');
$modversion['blocks'][3]['description'] = "";
$modversion['blocks'][3]['show_func'] = "mywordsBlockTags";
$modversion['blocks'][3]['edit_func'] = "mywordsBlockTagsEdit";
$modversion['blocks'][3]['template'] = 'bk-mywords-tags.tpl';
$modversion['blocks'][3]['options'] = "50|.05";

// Subpáginas
$modversion['subpages'] = array('index'=>_MI_MW_SPINDEX,
                                'post'=>_MI_MW_SPPOST,
                                'catego'=>_MI_MW_SPCATEGO,
                                'author'=>_MI_MW_SPAUTHOR,
                                'submit'=>_MI_MW_SPSUBMIT);
