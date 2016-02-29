<?php
// $Id: mwpost.class.php 1067 2012-09-19 01:34:58Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: bitc3r0@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwtag.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwmeta.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';

class MWPost extends RMObject
{
	private $myts = '';
	/**
	 * Categorias a las que pertenece el artículo
	 */
	private $categos = array();
	private $lcats = array();
	/**
	* Meta data container
	*/
	private $metas = array();
    /**
    * Tags container
    */
    private $tags = array();
    /**
    * Indicate when a post has more text
    */
    private $hasmore = false;
    /**
    * Number of pages
    */
    private $pages = 1;
    /**
    * Trackbacks
    */
    private $trackbacks = array();
	/**
	 * Constructor de la clase
	 * Carga los valores de un post específico o prepara
	 * las variables para la creación de uno nuevo
	 * Se puede establecer el id del post o bien la fecha y
	 * título amigable del post.
	 * @param int $id Identificador numérico del post
	 * @param date $date Fecha del post
	 * @param string $titulo Titulo amigable del post
	 */
	function __construct($id=null){

        // Prevent to be translated
        $this->noTranslate = [
            'image', 'shortname', 'status', 'visibility', 'password', 'authorname',
            'toping', 'pinged', 'video', 'format', 'pingstatus'
        ];

		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->myts =& MyTextSanitizer::getInstance();
		$this->_dbtable = $this->db->prefix("mod_mywords_posts");
		$this->setNew();
		$this->initVarsFromTable();
		$this->setVarType('toping', XOBJ_DTYPE_ARRAY);
		$this->setVarType('pinged', XOBJ_DTYPE_ARRAY);
		$this->setVarType('image', XOBJ_DTYPE_SOURCE);
		$this->setVarType('video', XOBJ_DTYPE_SOURCE);

        $this->ownerName = 'mywords';
        $this->ownerType = 'module';

		if ($id==null) return;
	
		if ($this->loadValues($id)){
            $this->unsetNew();
            $this->load_meta();
            $this->get_tags();
            $this->get_categos();
			return true;
		} else {
			return;
		}		
		
	}
	/**
	 * Funciones para manipular los datos del envío
	 */
	public function id(){
		return $this->getVar('id_post');
	}
	
    /**
    * Get content for current post according to given options
    * 
    * @param bool Indicates if only text before <!--more--> tag is returned
    * @param bool Indicates wich page will be returned. If there are only a page then return all
    * @return string
    */
	public function content($advance=true, $page=0){
		
        $content = $this->getVar('content', 'n');
        $content = explode("<!--nextpage-->", $content);
        
        $pages = count($content);
        
        if ($advance){
            $advance = explode('<!--more-->', $content[0]);
            $advance = str_replace("<!--nextpage-->","", $advance[0]);
            return TextCleaner::getInstance()->to_display($advance);
        }

        if ($page>0){
            $page--;
            if ($pages<=1) return TextCleaner::getInstance()->to_display(str_replace("<!--more-->","",$content[0]));
            
            if (($pages-1)<=$page) return TextCleaner::getInstance()->to_display(str_replace("<!--more-->","",$content[$pages-1]));
            
            return TextCleaner::getInstance()->to_display(str_replace("<!--more-->","",$content[$page]));
        }
        
        $content = str_replace("<!--more-->","",$this->getVar('content'));
        return $content;
        
	}
    
    public function total_pages(){
        
        $content = explode("<!--nextpage-->", $this->getVar('content','n'));
        $this->pages = count($content);
        return $this->pages;
        
    }
	
	public function hasmore_text(){
        
        $text = explode("<!--more-->", $this->getVar('content'));
        
		return count($text)>1;
	}
	
	/**
	 * Incrementa en uno el numero de comentarios
	 */
	public function add_comment(){
		$this->setVar('comments', $this->getVar('comments') + 1);
        $this->db->queryF("UPDATE ".$this->db->prefix("mod_mywords_posts")." SET comments='".($this->getVar('comments'))."'
                WHERE id_post='".$this->id()."'");
	}
	
	
	/**
	 * Funciones para el control de lecturas
	 */
	public function add_read(){
		global $xoopsUser;
		
		$editor = new MWEditor($this->getVar('author'));
		if ($xoopsUser && $editor->id()==$xoopsUser->uid()) return;
		
		$this->setVar('reads', $this->getVar('reads') + 1);
		$this->db->queryF("UPDATE ".$this->db->prefix("mod_mywords_posts")." SET `reads`='".($this->getVar('reads'))."'
				WHERE id_post='".$this->id()."'");
	}

	/**
	 * Obtiene las catgorías a las que pertenece el artículo
	 * @param string Indicates the returned data (ids, data, objects)
	 * @return string, array
	 */
	public function get_categos($w='ids'){
		global $mc;
		
		$tbl1 = $this->db->prefix("mod_mywords_categories");
		$tbl2 = $this->db->prefix("mod_mywords_catpost");
		
		$objs = array();
		if (empty($this->categos)){	
			$result = $this->db->query("SELECT a.* FROM $tbl1 a,$tbl2 b WHERE b.post='".$this->id()."' AND a.id_cat=b.cat GROUP BY b.cat");
			$rtn = array();
			while ($row = $this->db->fetchArray($result)){
				$cat = new MWCategory();
				$this->lcats[] = $row;
				$this->categos[] = $row['id_cat'];
				$cat->assignVars($row);
				$objs[] = $cat;
				$this->lcats[count($this->lcats)-1]['permalink'] = $cat->permalink();
			}
		}
        
        if ($w=='ids')
            return $this->categos;
        elseif ($w=='data')
            return $this->lcats;
        
        // Return objects
        $rtn = array();
        if (empty($objs)){
	        foreach($this->lcats as $row){
	            $cat = new MWCategory();
	            $cat->assignVars($row);
	            $rtn[] = $cat;
	        }
		}
        
        return $rtn;
		
	}
	/**
	 * Assign this post to a new category.
     * If Replace parameter is true, delete previos categories assignments and replace
     * with new given cats
	 * @param int|array Category ID or array with categories ID
     * @param bool Replace or add
	 */
	public function add_categories($cat, $replace = false){
		if (empty($this->categos) && !$replace) $this->get_categos();
        
        if (!is_array($cat)) $cat = array($cat);
        
        if($replace) $this->categos = array();
        
        foreach($cat as $id){
            if (in_array($id, $this->categos)) continue;
            $this->categos[] = $id;
        }
	}

	/**
	 * Devuelve los nombres de las categorías a las que pertenece
	 * el post actual
	 * @param bool $asList Detemina si se muestra en forma de lista o de array
	 * @param string $delimiter Delimitador para la lista
	 * @param bool Get names with links. Only works when $asList equal true
	 * @param string Section for link. It can be front or admin. Only works when $asList equal true
	 * @return string or array
	 */
	public function get_categories_names($asList=true, $delimiter=',', $links = true, $section='front'){
		
		if (empty($this->lcats)) $this->get_categos('data');
		
        $rtn = $asList ? '' : array();
        $url = MWFunctions::get_url();
        
		foreach ($this->lcats as $cat){
			if ($asList){
				if ($links){
					$category = new MWCategory();
					$category->assignVars($cat);
					$rtn .= $rtn == '' ? '' : "$delimiter";
					$rtn .= '<a href="'.($section=='front' ? $category->permalink() : 'posts.php?cat='.$cat['id_cat']).'">'.$cat['name'].'</a>';
				} else {
					$rtn .= $rtn == '' ? $cat['name'] : "$delimiter $cat[name]";
				}
			} else {
				$rtn[] = $row['nombre'];
			}
		}
        
		return $rtn;
	}
    
    /**
    * Add Tags
    * @param array|string Tags to add
    */
    public function add_tags($tags){
        $tags = MWFunctions::add_tags($tags);
		if (empty($tags)) return;
        $this->tags = $tags;
    }
    
    /**
    * Get Tags
    */
    private function get_tags(){
		
		$db = $this->db;
		
		$sql = "SELECT t.* FROM ".$db->prefix("mod_mywords_tags")." as t, ".$db->prefix("mod_mywords_tagspost")." as r WHERE r.post='".$this->id()."' AND t.id_tag=r.tag";
		
		$result = $db->query($sql);
		$this->tags = array();
		while($row = $db->fetchArray($result)){
			$tag = new MWTag();
			$tag->assignVars($row);
			$this->tags[] = $row;
			$this->tags[count($this->tags)-1]['permalink'] = $tag->permalink();
		}
		
    }
    
    /**
    * Get all tags according to given options
    * return array
    * @todo Enable objects return
    */
    public function tags($objects = false){
		
		if (empty($this->tags)) $this->get_tags();
		
		if (!$objects) return $this->tags;
		$tags = array();
		foreach ($this->tags as $data){
			$tag = new MWTag();
			$tag->assignVars($data);
			$tags[] = $tag;
		}
		
		return $tags;
    }
    
	/**
	 * Obtiene el enlace permanente al artículo
	 */
	public function permalink(){
		$mc = RMSettings::module_settings( 'mywords' );
		$day = date('d', $this->getVar('pubdate'));
		$month = date('m', $this->getVar('pubdate'));
		$year = date('Y', $this->getVar('pubdate'));
		$rtn = MWFunctions::get_url();
		$rtn .= $mc->permalinks == 1 ? '?post='.$this->id() : ($mc->permalinks == 2 ? "$day/$month/$year/".$this->getVar('shortname','n')."/" : "post/".$this->id());
		return $rtn;
		
	}
	
	/**
	* Meta data
	*/
	private function load_meta(){
		if (!empty($this->metas)) return;

		$result = $this->db->query("SELECT * FROM ".$this->db->prefix("mod_mywords_meta")." WHERE post='".$this->id()."'");
		while($row = $this->db->fetchArray($result)){
			$this->metas[$row['name']] = $row;
		}
	}
	
	/**
	* Get metas from post.
	* If a meta name has not been provided then return all metas
	* @param string Meta name
	* @return string|array
	*/
	public function get_meta($name='', $object = true){
		$this->load_meta();
		
		if (trim($name)!=''){
            
            if (!isset($this->metas[$name])) return false;
            
            if (!$object) return $this->metas[$name]['value'];
            
            $meta = new MWMeta();
            $meta->assignVars($this->metas[$name]);
            return $meta;
            
        }
		
        $metas = array();
        
		foreach ($this->metas as $data){
            $meta = new MWMeta();
            $meta->assignVars($data);
            if (!$object){
				$metas[$data['name']] = $data['value'];
            } else {
            	$metas[] = $meta;
			}
        }
		
		return $metas;
		
	}
	
	/**
	* Add or modify a field
	* @param string Meta name
	* @param mixed Meta value
	* @return none
	*/
	public function add_meta($name, $value){
		if (trim($name)=='' || trim($value)=='') return;
		
		$this->metas[$name] = $value;
	}
    
    /**
    * Clean metas array
    */
    public function clear_metas(){
        $this->metas = array();
    }
	
	/**
	* Determines if current or given user can read this post
	* @param int $id User ID
	* @return bool
	*/
	public function user_allowed($uid=null){
		global $xoopsUser;
		
        if(!$xoopsUser){
            $owner = false;
        } else {
            $user = $uid!=null ? $uid : $xoopsUser->uid();
            $editor = new MWEditor($this->getVar('author'));
            $owner = $user==$editor->getVar('uid');
        }
        
        if ($owner) return true;
        
		if ($this->getVar('status')!='publish') return false;
		if ($this->getVar('visibility')=='public') return true;
        
        if ($this->getVar('visibility')=='password'){
            
            $pass = rmc_server_var($_POST, 'password', '');
            $pass = $pass=='' && isset($_SESSION['password-'.$this->id()]) ? $_SESSION['password-'.$this->id()] : $pass;

            if ($pass=='') return false;
            
            if ($pass!=$this->getVar('password')) return false;
            
            $_SESSION['password-'.$this->id()] = $pass;
            
            return true;
            
        }
		
		return false;
		
	}
    
    /**
    * Trackbacks
    * @return array
    */
    public function trackbacks(){
        
        if (!empty($this->trackbacks)) return $this->trackbacks;
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM ".$db->prefix("mod_mywords_trackbacks")." WHERE post='".$this->id()."'";
        
        $result = $db->query($sql);
        $rtn = array();
        
        while($row = $db->fetchArray($result)){
            $tb = new MWTrackbackObject();
            $tb->assignVars($row);
            $this->trackbacks[] = $tb;
        }
        
        return $this->trackbacks;
        
    }

    /**
     * Get the default image according to specified size
     * @param string Size name to get
     * @return string
     */
    public function image(){

        if($this->getVar('image', 'e') == '') return '';
        
        return RMImage::get()->load_from_params( $this->getVar('image', 'e') );
    }

    /**
     * Get the video iframe when post type is 'video'
     */
    public function video_player(){

        if ( 'video' != $this->format )
            return null;

        $player = MWFunctions::construct_video_player( $this->video );
        return $player;

    }
	
	/**
	 * Actualizamos los valores en la base de datos
	 */
	public function update($ping = false){
		
		if (!$this->updateTable()) return false;
		if($ping) return true;
		$this->save_categories();
		$this->save_metas();		
		$this->save_tags();

		if ($this->errors()!='')
			return false;
		
		return true;
		
	}
	/**
	 * Guardamos los datos en la base de datos
	 */
	public function save(){	
		
		if (!$this->saveToTable()) return false;
		$this->setVar('id_post', $this->db->getInsertId());
		$this->save_categories();
		$this->save_metas();
        $this->save_tags();
        
        // Increment tags post number
        $sql = "UPDATE ".$this->db->prefix("mod_mywords_tags")." SET posts=posts+1 WHERE id_tag IN(".implode(',',$this->tags).")";
        $this->db->queryF($sql);
        
		return true;
	}
	
	/**
	* Save existing meta
	*/
	private function save_metas(){
		$this->db->queryF("DELETE FROM ".$this->db->prefix("mod_mywords_meta")." WHERE post='".$this->id()."'");
		if (empty($this->metas)) return true;
		$sql = "INSERT INTO ".$this->db->prefix("mod_mywords_meta")." (`name`,`value`,`post`) VALUES ";
		$values = '';
		foreach ($this->metas as $name => $value){
            if (is_array($value)) $value = $value['value'];
			$values .= ($values=='' ? '' : ',')."('".MyTextSanitizer::addSlashes($name)."','".MyTextSanitizer::addSlashes($value)."','".$this->id()."')";
		}
		
		if ($this->db->queryF($sql.$values)){
			return true;
		} else {
			$this->addError($this->db->error());
			return false;
		}
	}
    
	/**
	 * Almacena las categorías a las que pertenece el artículo
	 */
	public function save_categories(){
		if (empty($this->categos)){
			$this->add_categories(MWFunctions::default_category_id());
		}
		$this->db->queryF("DELETE FROM ".$this->db->prefix("mod_mywords_catpost")." WHERE post='".$this->id()."'");
		$sql = "INSERT INTO ".$this->db->prefix("mod_mywords_catpost")." (`post`,`cat`) VALUES ";
		foreach ($this->categos as $k){
			$sql .= "('".$this->id()."','$k'), ";
		}
		$sql = substr($sql, 0, strlen($sql) - 2);
		if ($this->db->queryF($sql)){
			return true;
		} else {
			$this->addError($this->db->error());
			return false;
		}
	}
    
    /**
    * Save tags
    * @return bool
    */
    public function save_tags(){
        if (!$this->isNew()){
            $this->db->queryF("DELETE FROM ".$this->db->prefix("mod_mywords_tagspost")." WHERE post='".$this->id()."'");
        }
        
        if (empty($this->tags)) return true;
        
        $sql = "INSERT INTO ".$this->db->prefix("mod_mywords_tagspost")." (`post`,`tag`) VALUES ";
        $sa = '';
        foreach ($this->tags as $tag){
            $sa .= $sa=='' ? "('".$this->id()."','$tag')" : ",('".$this->id()."','$tag')";
        }

        if (!$this->db->queryF($sql.$sa)){
            $this->addError($this->db->error());
            return false;
        }
        
        $this->tags = array();
        $tags = $this->tags(true);
        foreach($tags as $tag){
			$tag->update_posts();
        }
        return true;
    
    }
	/**
	 * Elimina un artículo y todos sus comentarios de
	 * la base de datos.
	 */
	public function delete(){
		global $xoopsModule;
        
        // Event
        RMEvents::get()->run_event('mywords.delete.post', $this);
        
		$sql = "DELETE FROM ".$this->db->prefix("mod_mywords_catpost")." WHERE post='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
		}
        
        $sql = "DELETE FROM ".$this->db->prefix("mod_mywords_meta")." WHERE post='".$this->id()."'";
        if (!$this->db->queryF($sql)){
            $this->addError($this->db->error());
        }
        
        // Deleting trackbacks
        $sql = "DELETE FROM ".$this->db->prefix("mod_mywords_trackbacks")." WHERE post='".$this->id()."'";
        if (!$this->db->queryF($sql)){
            $this->addError($this->db->error());
        }
        
        $this->db->queryF("DELETE FROM ".$this->db->prefix("mod_mywords_tagspost")." WHERE post='".$this->id()."'");
        foreach($this->tags(false) as $tag){
            $tags[] = $tag['id_tag'];
        }
        
        $sql = "UPDATE ".$this->db->prefix("mod_mywords_tags")." SET posts=posts-1 WHERE id_tag IN(".implode(',',$this->tags).")";
        $this->db->queryF($sql);
		
		$this->deleteFromTable();
		if ($this->errors()!=''){ return false; } else { return true; }
		
	}

}
