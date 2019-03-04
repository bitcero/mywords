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

/**
 * Clase para el manejo de categorías
 */
class MWCategory extends RMObject
{
    public function __construct($id = '')
    {
        // Prevent to be translated
        $this->noTranslate = [
            'shortname',
        ];

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix('mod_mywords_categories');
        $this->setNew();
        $this->initVarsFromTable();

        $this->ownerName = 'mywords';
        $this->ownerType = 'module';

        if ('' == $id) {
            return;
        }

        if (is_numeric($id)) {
            if ($this->loadValues($id)) {
                $this->unsetNew();
            }

            return;
        }

        $this->primary = 'shortcut';
        if ($this->loadValues($id)) {
            $this->unsetNew();
        }
        $this->primary = 'id_cat';
    }

    /**
     * Funciones para asignar valores a las variables
     */
    public function id()
    {
        return $this->getVar('id_cat');
    }

    public function loadPosts()
    {
        $result = $this->db->query('SELECT COUNT(*) FROM ' . $this->db->prefix('mod_mywords_catpost') . " WHERE cat='" . $this->id() . "'");
        list($num) = $this->db->fetchRow($result);
        $this->setVar('posts', $num);
    }

    /**
     * Obtiene la ruta completa de la categor?a basada en nombres
     */
    public function path()
    {
        if (0 == $this->getVar('parent')) {
            return $this->getVar('shortname', 'n') . '/';
        }
        $parent = new self($this->getVar('parent', 'n'));

        return $parent->path() . $this->getVar('shortname') . '/';
    }

    /**
     * Obtiene el enlace a la categor?a
     */
    public function permalink()
    {
        $mc = RMSettings::module_settings('mywords');
        $link = MWFunctions::get_url();
        $link .= (1 == $mc->permalinks ? '?cat=' . $this->id() : (2 == $mc->permalinks ? 'category/' . $this->path() : 'category/' . $this->id()));

        return $link;
    }

    /**
     * Guardamos los valores en la base de datos
     */
    public function save()
    {
        if ($this->isNew()) {
            return $this->saveToTable();
        }

        return $this->updateTable();
    }

    /**
     * Elimina de la base de datos la categor?a actual
     */
    public function delete()
    {
        $this->db->queryF('UPDATE ' . $this->db->prefix('mod_mywords_categories') . " SET parent='" . $this->getVar('parent', 'n') . "' WHERE parent='" . $this->id() . "'");
        $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_mywords_catpost') . " WHERE cat='" . $this->id() . "'");

        return $this->deleteFromTable();
    }
}
