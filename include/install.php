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
 * @param mixed $mod
 * @return bool
 */
function xoops_module_pre_install_mywords(&$mod)
{
    xoops_setActiveModules();

    $mods = xoops_getActiveModules();

    if (!in_array('rmcommon', $mods, true)) {
        $mod->setErrors('MyWords could not be instaled if <a href="http://rmcommon.com/" target="_blank">Common Utilities</a> has not be installed previously!<br>Please install <a href="http://rmcommon.com/" target="_blank">Common Utilities</a>.');

        return false;
    }

    return true;
}

function xoops_module_update_mywords($mod, $pre)
{
    global $xoopsDB;

    // Update table names and engine
    $sql = 'CREATE TABLE `' . $xoopsDB->prefix('mod_mywords_reports') . "` (
    `id_report` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `when` datetime NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'waiting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $xoopsDB->queryF($sql);

    $xoopsDB->queryF('ALTER TABLE `' . $xoopsDB->prefix('mod_mywords_reports') . '`
  ADD UNIQUE KEY `id_report` (`id_report`),
  ADD KEY `post` (`post`),
  ADD KEY `user` (`user`);');

    $xoopsDB->queryF('ALTER TABLE `' . $xoopsDB->prefix('mod_mywords_reports') . '`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT;');

    return true;
}
