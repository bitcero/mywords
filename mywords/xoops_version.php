<?php
// $Id: xoops_version.php 1058 2012-09-14 03:18:00Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

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
$modversion['rmversion'] = array('major'=>2,'minor'=>2,'revision'=>18, 'stage'=>-2,'name'=>'MyWords');
$modversion['rewrite'] = 0;
$modversion['permissions'] = 'include/permissions.php';
$modversion['updateurl'] = "http://www.xoopsmexico.net/modules/vcontrol/";
// Icons
$modversion['icon16'] = "images/icon16.png";
$modversion['icon24'] = 'images/icon24.png';
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon48'] = "images/icon48.png";
// Credits
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.bitcero.info";
$modversion['credits'] = "Red México";

// Social links
$modversion['social'][0] = array('title' => __('Twitter', 'rmcommon'),'type' => 'twitter','url' => 'http://www.twitter.com/xoopsmexico/');
$modversion['social'][1] = array('title' => __('Facebook', 'rmcommon'),'type' => 'linkedin','url' => 'http://www.facebook.com/XoopsMexico/');
$modversion['social'][2] = array('title' => __('bitcero', 'rmcommon'),'type' => 'linkedin','url' => 'http://www.linkedin.com/bitcero/');

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
$modversion['tables'][0] = "mw_categories";
$modversion['tables'][1] = "mw_posts";
$modversion['tables'][2] = "mw_catpost";
$modversion['tables'][3] = "mw_trackbacks";
$modversion['tables'][4] = "mw_editors";
$modversion['tables'][5] = "mw_bookmarks";
$modversion['tables'][6] = "mw_meta";
$modversion['tables'][7] = "mw_tags";
$modversion['tables'][8] = "mw_tagspost";

// Plantillas
$modversion['templates'][1]['file'] = 'mywords_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'mywords_post.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'mywords_cats.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'mywords_author.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'mywords_trackback.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'mywords_single_post.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'mywords_password.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'mywords_tag.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'mywords_date.html';
$modversion['templates'][9]['description'] = '';

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

// Bloque Categorias
$modversion['blocks'][1]['file'] = "block.cats.php";
$modversion['blocks'][1]['name'] = __('Categories','mywords');
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "mywordsBlockCats";
$modversion['blocks'][1]['edit_func'] = "mywordsBlockCatsEdit";
$modversion['blocks'][1]['template'] = 'bk_mywords_categos.html';
$modversion['blocks'][1]['options'] = "1";

// Bloque Recientes
$modversion['blocks'][2]['file'] = "block.recent.php";
$modversion['blocks'][2]['name'] = __('Recent Posts','mywords');
$modversion['blocks'][2]['description'] = "";
$modversion['blocks'][2]['show_func'] = "mywordsBlockRecent";
$modversion['blocks'][2]['edit_func'] = "mywordsBlockRecentEdit";
$modversion['blocks'][2]['template'] = 'bk_mywords_recent.html';
$modversion['blocks'][2]['options'] = "10|recent|1|50|1|0";

// Tags
$modversion['blocks'][3]['file'] = "block.tags.php";
$modversion['blocks'][3]['name'] = __('Tags','mywords');
$modversion['blocks'][3]['description'] = "";
$modversion['blocks'][3]['show_func'] = "mywordsBlockTags";
$modversion['blocks'][3]['edit_func'] = "mywordsBlockTagsEdit";
$modversion['blocks'][3]['template'] = 'bk_mywords_tags.html';
$modversion['blocks'][3]['options'] = "50|.05";

// Subpáginas
$modversion['subpages'] = array('index'=>_MI_MW_SPINDEX,
                                'post'=>_MI_MW_SPPOST,
                                'catego'=>_MI_MW_SPCATEGO,
                                'author'=>_MI_MW_SPAUTHOR,
                                'submit'=>_MI_MW_SPSUBMIT);
