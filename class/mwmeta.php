<?php
// $Id: mwmeta.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWMeta extends RMObject
{
	public function __construct($id = null){
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("mod_mywords_meta");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) return;
    
        if ($this->loadValues($id)){
            $this->unsetNew();
            return true;
        } else {
            return;
        }        
    }
    
    public function id(){
        return $this->getVar('id_meta');
    }
    
    function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
}
