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

class MWBookmark extends RMObject
{
    function __construct($id=null){

        // Prevent to be translated
        $this->noTranslate = [
            'url',
            'icon'
        ];

        $this->ownerName = 'mywords';
        $this->ownerType = 'module';

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("mod_mywords_bookmarks");
        $this->setNew();
        $this->initVarsFromTable();
        if ($id==''){
            return;
        }
        
        if (is_numeric($id)){
            if ($this->loadValues($id)){
                $this->unsetNew();
            }
            return;
        }
        
    }
    
    public function id(){
        return $this->getVar('id_book');
    }
        
    /**
    * @desc Crea el enlace para agregar el elemento a la red social
    */
    public function link($title, $url, $desc=''){
        $link = str_replace('{TITLE}', urlencode($title), $this->getVar('url'));
        $link = str_replace('{URL}', urlencode($url), $link);
        $link = str_replace('{DESC}', urlencode($desc), $link);
        return $link;
    }
    
    function save(){
        if($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
    function delete(){
        
        // eliminamos el archivo de icono
        @unlink(XOOPS_UPLOAD_PATH.'/mywords/icons/'.$this->icon());
        
        return $this->deleteFromTable();
        
    }
    
}
