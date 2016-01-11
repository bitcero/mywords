<?php
/**
 * MyWords for Xoops
 *
 * Copyright © 2015 Red Mexico http://www.redmexico.com.mx
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
 * @copyright    Red Mexico (http://www.redmexico.com.mx)
 * @license      GNU GPL 2
 * @package      mywords
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @link         http://www.redmexico.com.mx
 * @link         http://www.eduardocortes.mx
 */

define('RMCLOCATION', 'importer');
require 'header.php';

$importer = new MWImporter();

$action = RMHttpRequest::request('action', 'string', '');

switch ($action) {
    case 'collect':
        $importer->collect();
        break;
    case 'import-category':
        $importer->category();
        break;
    case 'import-article':
        $importer->article();
        break;
    case 'close':
        $importer->close();
        break;
    default:
        $importer->panel();
        break;
}
