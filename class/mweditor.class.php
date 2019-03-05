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
class MWEditor extends RMObject
{
    private $xuser;

    /**
     * Constructor of the class.
     * @param null $id <p>Editor ID</p>
     * @param string $from <p>Where this class will search the editor ID: user for User table and editor for Editors table</p>
     */
    public function __construct($id = null, $from = 'editor')
    {
        // Prevent to be translated
        $this->noTranslate = [
            'name', 'shortname', 'privileges',
        ];

        $this->ownerName = 'mywords';
        $this->ownerType = 'module';

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix('mod_mywords_editors');
        $this->setNew();
        $this->initVarsFromTable();
        $this->setVarType('privileges', XOBJ_DTYPE_ARRAY);

        $id = (int)$id;

        if (null === $id || $id <= 0) {
            return;
        }

        if ('user' == $from) {
            $this->primary = 'uid';
        }

        if (!$this->loadValues($id)) {
            $this->primary = 'id_editor';

            return;
        }

        $this->primary = 'id_editor';
        $this->unsetNew();
    }

    public function id()
    {
        return $this->getVar('id_editor');
    }

    public function posts()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('mod_mywords_posts') . ' WHERE author=' . $this->id();
        list($num) = $this->db->fetchRow($this->db->query($sql));

        return $num;
    }

    public function data($name)
    {
        if (!$this->xuser) {
            $this->xuser = new XoopsUser($this->getVar('uid'));
        }

        return $this->xuser->getVar($name);
    }

    public function permalink()
    {
        $mc = RMSettings::module_settings('mywords');
        $rtn = MWFunctions::get_url();
        $rtn .= 1 == $mc->permalinks ? '?author=' . $this->id() : (2 == $mc->permalinks ? 'author/' . $this->getVar('shortname', 'n') . '/' : 'author/' . RMUtilities::add_slash($this->id()));

        return $rtn;
    }

    public function from_user($uid)
    {
        $this->primary = 'uid';
        if ($this->loadValues($uid)) {
            $this->unsetNew();
            $this->primary = 'id_editor';

            return true;
        }

        $this->primary = 'id_editor';

        return false;
    }

    public function save()
    {
        if ($this->isNew()) {
            return $this->saveToTable();
        }

        return $this->updateTable();
    }
}
