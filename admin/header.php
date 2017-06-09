<?php
// $Id: header.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xpath = str_replace("\\", "/", dirname(__FILE__));
$xpath = str_replace("/modules/mywords/admin", "", $xpath);

require $xpath.'/include/cp_header.php';
//require $xpath.'/modules/rmcommon/admin_loader.php';

$tpl = $GLOBALS['rmTpl'];

load_mod_locale('mywords', '');

$db =& $xoopsDB;

define('MW_PATH',XOOPS_ROOT_PATH.'/modules/mywords');
define('MW_URL', MWFunctions::get_url());

# Asignamos las variables básicas a SMARTY
$tpl->assign('MW_URL',MW_URL);
$tpl->assign('MW_PATH',MW_PATH);

$mc =& $xoopsModuleConfig;

// Activate scheduled posts
MWFunctions::go_scheduled();
