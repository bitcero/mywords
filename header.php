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

include XOOPS_ROOT_PATH."/header.php";

load_mod_locale('mywords', '');

$mc =& $xoopsModuleConfig;
$db = XoopsDatabaseFactory::getDatabaseConnection();
$myts = MyTextSanitizer::getInstance();

define('MW_PATH', XOOPS_ROOT_PATH.'/modules/mywords');
define('MW_URL', MWFunctions::get_url());

if (isset($no_includes) && $no_includes) {
    return;
}

$xoopsTpl->assign('mw_url', MW_URL);

$xmh = '';
if ($mc['use_css']) {
    RMTemplate::get()->add_style('mywords.min.css', 'mywords');
}

// Redes Sociales
$sql = "SELECT * FROM ".$db->prefix("mod_mywords_bookmarks")." WHERE `active`='1'";
$result = $db->query($sql);

$socials = array();
$i = 0;
while ($row = $db->fetchArray($result)) {
    $socials[$i] = new MWBookmark();
    $socials[$i]->assignVars($row);
    $i++;
}
$socials = RMEvents::get()->run_event('mywords.loding.socials', $socials);

$tpl = $rmTpl;

// Update scheduled posts
MWFunctions::go_scheduled();

$rmTpl->add_Script('main.min.js', 'mywords', array('directory' => 'include', 'footer' => 1));
