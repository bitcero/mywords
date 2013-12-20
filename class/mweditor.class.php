<?php
// $Id: mweditor.class.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWEditor extends RMObject
{
    private $xuser;
    
	public function __construct($id = null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("mw_editors");
        $this->setNew();
        $this->initVarsFromTable();
        $this->setVarType('privileges', XOBJ_DTYPE_ARRAY);
        
        $id = intval($id);
        
        if ($id==null || $id<=0) return;
        
        if (!$this->loadValues($id)) return;
        
        $this->unsetNew();
                
	}
    
    public function id(){
        return $this->getVar('id_editor');
    }
    
    public function posts(){
        
        $sql = "SELECT COUNT(*) FROM ".$this->db->prefix("mw_posts")." WHERE author=".$this->id();
        list($num) = $this->db->fetchRow($this->db->query($sql));
        return $num;
        
    }
    
    public function data($name){
        
        if (!$this->xuser){
            $this->xuser = new XoopsUser($this->getVar('uid'));
        }
        
        return $this->xuser->getVar($name);
        
    }
    
    public function permalink(){
		$mc = RMSettings::module_settings( 'mywords' );
		$rtn = MWFunctions::get_url();
		$rtn .= $mc->permalinks == 1 ? '?author='.$this->id() : ($mc->permalinks==2 ? "author/".$this->getVar('shortname','n')."/" : "author/".RMUtilities::add_slash($this->id()));
		return $rtn;
    }
    
	public function from_user($uid){
		$this->primary = 'uid';
		if ($this->loadValues($uid)){
			$this->unsetNew();
			$this->primary = 'id_editor';
			return true;
		}
		
		$this->primary = 'id_editor';
		return false;
	}
    
    public function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
}
