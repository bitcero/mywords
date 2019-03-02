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

class MWTag extends RMObject
{
    public function __construct($id = null)
    {

        // Prevent to be translated
        $this->noTranslate = [
            'shortname'
        ];

        $this->ownerType = 'module';
        $this->ownerName = 'mywords';

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("mod_mywords_tags");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) {
            return;
        }
    
        if ($this->loadValues($id)) {
            $this->unsetNew();
            return true;
        }
        
        $this->primary = 'shortname';
        
        if ($this->loadValues($id)) {
            $this->unsetNew();
            $this->primary = 'id_tag';
            return true;
        }
        
        $this->primary = 'id_tag';
    }
    
    public function id()
    {
        return $this->getVar('id_tag');
    }
    
    /**
    * This function
    *
    */
    public function update_posts()
    {
        $sql = "SELECT COUNT(*) FROM ".$this->db->prefix("mod_mywords_tagspost")." WHERE tag='".$this->id()."'";
        list($num) = $this->db->fetchRow($this->db->query($sql));
        $this->setVar('posts', $num);
        $this->updateTable();
    }
    
    public function permalink()
    {
        $mc = RMSettings::module_settings('mywords');
        $ret = MWFunctions::get_url();
        $ret .= $mc->permalinks == 1 ? '?tag='.$this->id() : ($mc->permalinks == 2 ? "tag/".$this->getVar('shortname', 'n')."/" : "tag/".$this->id());
        return $ret;
    }
    
    public function save()
    {
        if ($this->isNew()) {
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
    public function delete()
    {
        
        // Delete posts relations
        if (!$this->db->queryF("DELETE FROM ".$this->db->prefix("mod_mywords_tagspost")." WHERE tag=".$this->id())) {
            $this->addError($this->db->error());
            return false;
        }
        
        return $this->deleteFromTable();
    }
}
