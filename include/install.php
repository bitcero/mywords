<?php
// $Id: install.php 1067 2012-09-19 01:34:58Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_install_mywords(&$mod){
    
    xoops_setActiveModules();
    
    $mods = xoops_getActiveModules();
    
    if(!in_array("rmcommon", $mods)){
        $mod->setErrors('MyWords could not be instaled if <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a> has not be installed previously!<br />Please install <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>.');
        return false;
    }
    
    return true;
    
}

function xoops_module_update_mywords($mod, $pre){

    global $xoopsDB;

    // Update table names and engine
    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_bookmarks").'` TO  `'.$xoopsDB->prefix("mod_mywords_bookmarks").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_bookmarks").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_categories").'` TO  `'.$xoopsDB->prefix("mod_mywords_categories").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_categories").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_catpost").'` TO  `'.$xoopsDB->prefix("mod_mywords_catpost").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_catpost").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_editors").'` TO  `'.$xoopsDB->prefix("mod_mywords_editors").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_editors").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_meta").'` TO  `'.$xoopsDB->prefix("mod_mywords_meta").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_meta").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_posts").'` TO  `'.$xoopsDB->prefix("mod_mywords_posts").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_posts").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_tags").'` TO  `'.$xoopsDB->prefix("mod_mywords_tags").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_tags").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_tagspost").'` TO  `'.$xoopsDB->prefix("mod_mywords_tagspost").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_tagspost").'` ENGINE = INNODB;');

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("mw_trackbacks").'` TO  `'.$xoopsDB->prefix("mod_mywords_trackbacks").'` ;');
    $xoopsDB->queryF('ALTER TABLE  `'.$xoopsDB->prefix("mod_mywords_trackbacks").'` ENGINE = INNODB;');

    $xoopsDB->queryF("ALTER TABLE  `".$xoopsDB->prefix("mod_mywords_posts")."` ADD  `format` VARCHAR( 10 ) NOT NULL DEFAULT  'post', ADD INDEX (  `format` );");

    return true;
    
}