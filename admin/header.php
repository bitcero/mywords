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
$xpath = str_replace('\\', '/', __DIR__);
$xpath = str_replace('/modules/mywords/admin', '', $xpath);

require $xpath . '/include/cp_header.php';
//require $xpath.'/modules/rmcommon/admin_loader.php';

$tpl = $GLOBALS['rmTpl'];

load_mod_locale('mywords', '');

$db = &$xoopsDB;

define('MW_PATH', XOOPS_ROOT_PATH . '/modules/mywords');
define('MW_URL', MWFunctions::get_url());

# Asignamos las variables básicas a SMARTY
$tpl->assign('MW_URL', MW_URL);
$tpl->assign('MW_PATH', MW_PATH);

$mc = &$xoopsModuleConfig;

// Activate scheduled posts
MWFunctions::go_scheduled();
