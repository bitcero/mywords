<?php
// $Id: modinfo.php 1058 2012-09-14 03:18:00Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (function_exists('load_mod_locale')) {
    load_mod_locale('mywords');
}

if (!function_exists('__')) {
    function __($text, $d)
    {
        return $text;
    }
}

define('_MI_MW_DESC', __('Module for publishing and management of news and blogs', 'mywords'));

define('_MI_MW_AMENU1', __('Module Status', 'mywords'));
define('_MI_MW_AMENU2', __('Categories', 'mywords'));
define('_MI_MW_AMENU3', __('Articles', 'mywords'));
define('_MI_MW_AMENU4', __('Editors', 'mywords'));
define('_MI_MW_AMENU5', __('Bookmarks', 'mywords'));

// Menu principal
define('_MI_MW_SEND', __('Submit Article', 'mywords'));

# Permalinks
define('_MI_MW_PERMAFORMAT', __('Links Format', 'mywords'));
define('_MI_MW_PERMA_DESC', __('Determines the way the links in the module will be shown and processed.', 'mywords'));
define('_MI_MW_PERMA_DEF', __('Default', 'mywords'));
define('_MI_MW_PERMA_DATE', __('Based on name and date', 'mywords'));
define('_MI_MW_PERMA_NUMS', __('Numeric', 'mywords'));
// Base path for permalinks
define('_MI_MW_BASEPATH', __('Base Path', 'mywords'));
define('_MI_MW_BASEPATHD', __('This path is used when the links format has been set to use dates or numbers.', 'mywords'));
// Widget tags
define('_MI_MW_WIDGETTAGS', __('Number of tags on admin widget', 'mywords'));
// Posts number per page
define('_MI_MW_PPP', __('Posts per page', 'mywords'));
define('_MI_MW_CSS', __('Use CSS file', 'mywords'));
define('_MI_MW_CSSFILE', __('CSS file to use', 'mywords'));
define('_MI_MW_SHOWNAV', __('Show navigation bar between posts', 'mywords'));
// Blog name
define('_MI_MW_BLOGNAME', __('Blog name (section name)', 'mywords'));
define('_MI_MW_BLOGNAMED', __('This name will be used in trackbacks.', 'mywords'));

define('_MI_MW_RELATED', __('Enable related posts', 'mywords'));

# Imágenes para los bloques
define('_MI_MW_BIMGSIZE', __('Image Size for the Block', 'mywords'));
define('_MI_MW_BIMGSIZE_DESC', __('The specified image in the article will be resized with this sizes. Format: "width|height"', 'mywords'));
define('_MI_MW_DEFIMG', __('Default Image for the articles in blocks', 'mywords'));
define('_MI_MW_DEFIMG_DESC', __('When the "graphic" mode is enabled in the "Recent Articles" blocks, this image will be used when there is not a specified one for the article', 'mywords'));

// Images
define('_MI_MW_ENABLELISTIMAGES', __('Enable images in posts list', 'mywords'));
define('_MI_MW_LISTIMAGESSIZE', __('Size of images to use in posts list', 'mywords'));
define('_MI_MW_LISTIMAGESSIZED', sprintf(__('You must specify a custom size name created for a category in %s of %s.', 'mywords'), '<a href="' . RMCURL . '/images.php">' . __('Images Manager', 'mywords') . '</a>', '<a href="' . RMCURL . '">' . __('Common Utilities', 'mywords') . '</a>'));
define('_MI_MW_ENABLEPOSTIMAGES', __('Enable images in post', 'mywords'));
define('_MI_MW_POSTIMAGESSIZE', __('Size of images of post', 'mywords'));

define('_MI_MW_FILESIZE', __('Maximum file size', 'mywords'));

define('_MI_MW_SHOWBOOKMARKS', __('Show social bookmarks', 'mywords'));

// BLOQUES
define('_MI_MW_BKCATEGOS', __('Categories', 'mywords'));
define('_MI_MW_BKRECENT', __('Recent Articles', 'mywords'));
define('_MI_MW_BKCOMMENTS', __('Recent Comments', 'mywords'));

define('_MI_MW_RSSNAME', __('Posts Syndication', 'mywords'));
define('_MI_MW_RSSNAMECAT', __('Articles Syndication in %s', 'mywords'));
define('_MI_MW_RSSDESC', __('Syndication Description', 'mywords'));
define('_MI_MW_RSSALL', __('All Recent Posts', 'mywords'));
define('_MI_MW_RSSALLDESC', __('Show all recent posts', 'mywords'));

// Subpáginas
define('_MI_MW_SPINDEX', __('Home Page', 'mywords'));
define('_MI_MW_SPPOST', __('Post', 'mywords'));
define('_MI_MW_SPCATEGO', __('Category', 'mywords'));
define('_MI_MW_SPAUTHOR', __('Author', 'mywords'));
define('_MI_MW_SPSUBMIT', __('Post Article', 'mywords'));
