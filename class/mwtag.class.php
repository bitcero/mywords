<?php
// $Id: mwtag.class.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWTag extends RMObject
{
	public function __construct($id = null){
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("mod_mywords_tags");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) return;
    
        if ($this->loadValues($id)){
            $this->unsetNew();
            return true;
        }
        
        $this->primary = 'shortname';
        
        if ($this->loadValues($id)){
            $this->unsetNew();
            $this->primary = 'id_tag';
            return true;
        }
        
        $this->primary = 'id_tag';
               
    }
    
    public function id(){
		return $this->getVar('id_tag');
    }
    
    /**
    * This function 
    * 
    */
    public function update_posts(){
		$sql = "SELECT COUNT(*) FROM ".$this->db->prefix("mod_mywords_tagspost")." WHERE tag='".$this->id()."'";
		list($num) = $this->db->fetchRow($this->db->query($sql));
		$this->setVar('posts', $num);
		$this->updateTable();
    }
    
    function permalink(){
        $mc = RMSettings::module_settings( 'mywords' );
        $ret = MWFunctions::get_url();
        $ret .= $mc->permalinks == 1 ? '?tag='.$this->id() : ($mc->permalinks == 2 ? "tag/".$this->getVar('shortname','n')."/" : "tag/".$this->id());
        return $ret;
    }
    
    function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
    }
    
    function delete(){
		
		// Delete posts relations
		if (!$this->db->queryF("DELETE FROM ".$this->db->prefix("mod_mywords_tagspost")." WHERE tag=".$this->id())){
			$this->addError($this->db->error());
			return false;
		}
		
		return $this->deleteFromTable();
		
		
    }
    
}
