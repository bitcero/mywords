<?php
// $Id: mwbookmark.class.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWBookmark extends RMObject
{
    function __construct($id=null){
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
