<?php
// $Id: mwfunctions.php 940 2012-04-08 16:28:59Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains general functions used in MyWords
* @author BitC3R0 <i.bitcero@gmail.com>
* @since 2.0
*/
class MWFunctions
{
	private $max_popularity = 0;
    
	public function get(){
		static $instance;
		
		if (isset($instance))
			return $instance;
		
		$instance = new MWFunctions();
		return $instance;
	}
	
	/**
	* Retrieve metas from database
	* @return array
	*/
	function get_metas(){
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$result = $db->query("SELECT name FROM ".$db->prefix("mod_mywords_meta")." GROUP BY name");
		$ret = array();
		while($row = $db->fetchArray($result)){
			$ret[] = $row['name'];
		}
		return $ret;
	}
    
    /**
    * Get all categories from database arranged by parents
    * 
    * @param mixed $categories
    * @param mixed $parent
    * @param mixed $indent
    * @param mixed $include_subs
    * @param mixed $exclude
    * @param mixed $order
    */
    public function categos_list(&$categories, $parent = 0, $indent = 0, $include_subs = true, $exclude=0, $order="id_cat DESC"){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        
        $sql = "SELECT * FROM ".$db->prefix("mod_mywords_categories")." WHERE parent='$parent' ORDER BY $order";
        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            if ($row['id_cat']==$exclude) continue;
            $row['indent'] = $indent;
            $categories[] = $row;
            if ($include_subs) MWFunctions::categos_list($categories, $row['id_cat'], $indent+1, $include_subs, $exclude);
        }
        
    }
    
    /**
    * Show admin menu and include the javascript files
    */
    static function include_required_files( $toolbar = true ){
		RMTemplate::get()->add_style('admin.css','mywords');
    }
    
    /**
    * Check if a category exists already
    * @param object MWCategory object
    * @return bool
    */
    public function category_exists(MWCategory $cat){
		
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_categories")." WHERE name='".$cat->getVar('name','n')."' OR
				shortname='".$cat->getVar('shortname','n')."'";
		
		if (!$cat->isNew()){
			$sql .= " AND id_cat<>".$cat->id();
		}
		
		list($num) = $db->fetchRow($db->query($sql));
		
		if ($num>0) return true;
		
		return false;
		
    }
    
    /**
    * Check if given post already exists
    * @param object MWPost object
    * @return bool
    */
    public function post_exists(MWPost &$post){
        
        if ($post->getVar('title','n')=='') return false;
        
        // the pubdate
        if ($post->getVar('pubdate')<=0){
        	
			$day = date('j', $post->getVar('schedule'));
			$month = date('n', $post->getVar('schedule'));
			$year = $day = date('Y', $post->getVar('schedule'));
			
			$bdate = mktime(0, 0, 0, $month, $day, $year);
			$tdate = mktime(23, 59, 59, $month, $day, $year);
			
        } else {
			
			$day = date('j', $post->getVar('pubdate'));
			$month = date('n', $post->getVar('pubdate'));
			$year = date('Y', $post->getVar('pubdate'));
			
			$bdate = mktime(0, 0, 0, $month, $day, $year);
			$tdate = $bdate + 86400;
	
        }
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")." WHERE (pubdate>=$bdate AND pubdate<=$tdate) AND
        		(title='".$post->getVar('title','n')."' OR shortname='".$post->getVar('shortname','n')."')";
        

        if (!$post->isNew()){
			$sql .= " AND id_post<>".$post->id();
        }

        list($num) = $db->fetchRow($db->query($sql));
        
        if ($num>0){
			return true;
        }
        
        return false;
        
    }
    
    /**
    * Get the tags list based on given parameters
    * @param string SQL Select
    * @param string SQL Where
    * @param string SQL Order
    * @param string SQL Limit
    * @return array
    */
    public function get_tags($select = '*', $where='',$order='',$limit=''){
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT $select FROM ".$db->prefix("mod_mywords_tags").($where!='' ? " WHERE $where" : '').($order!='' ? " ORDER BY $order" : '' ).($limit!='' ? " LIMIT $limit" : '');
        $result = $db->query($sql);
        $tags = array();
        while ($row = $db->fetchArray($result)){
            $tags[] = $row;
        }
        asort($tags);
        return $tags;
    }
    
    /**
    * Get the font size for tags names based on their popularity
    * @param int Number of posts for this tag
    * @param int Max font size for tag name. This value is expressend in 'ems' (2em)
    * @return float Size of tag expressed as em value
    */
    public function tag_font_size($posts, $max_size = 3){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        if ($this->max_popularity<=0){
            $sql = "SELECT MAX(posts) FROM ".$db->prefix("mod_mywords_tags");
            list($this->max_popularity) = $db->fetchRow($db->query($sql));
        }
        
        if ($this->max_popularity<=0) return 0.85;
        
        $base_size = $max_size / $this->max_popularity;
        
        $ret = $posts * $base_size;
        
        if ($ret<0.85) return 0.85;
        
        return number_format($ret, 2);
        
    }
    
    /**
    * @desc Devuelve la categoría "uncategorized"
    * @return array
    */
    public function default_category_id(){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $result = $db->query("SELECT id_cat FROM ".$db->prefix("mod_mywords_categories")." WHERE id_cat='1'");
        if ($db->getRowsNum($result)<=0) return false;
        
        list($id) = $db->fetchRow($result);
        return $id;
        
    }
    
    /**
    * Get author name
    * @param int Author (XoopsUser) ID
    * @return string
    */
    public function author_name($uid){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $result = $db->query("SELECT name FROM ".$db->prefix("mod_mywords_editors")." WHERE uid='$uid'");
        if ($db->getRowsNum($result)>0){
            $row = $db->fetchArray($result);
            return $row['name'];
        }
        
        $result = $db->query("SELECT uname FROM ".$db->prefix("users")." WHERE uid='$uid'");
        if ($db->getRowsNum($result)<=0) return;
        
        $row = $db->fetchArray($result);
        return $row['uname'];
        
    }
    
    /**
    * Add tags to database
    * @param string|array Tags names
    * @return array Tags saved ID
    */
    public function add_tags($tags){
        
        if (!is_array($tags))
            $tags = array($tags);
        
        if(empty($tags)) return;
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        
        $sql = "SELECT id_tag, shortname FROM ".$db->prefix('mod_mywords_tags')." WHERE ";
        $sa = '';
        foreach($tags as $tag){
            $sa .= $sa=='' ? "shortname='".TextCleaner::sweetstring($tag)."'" : " OR shortname='".TextCleaner::sweetstring($tag)."'";
        }

        $result = $db->query($sql.$sa);
        $existing = array();
        $ids = array();
        
        while($row = $db->fetchArray($result)){
            $existing[$row['shortname']] = $row['id_tag'];
            $ids[] = $row['id_tag'];
        }

        $sa = '';
        
        foreach ($tags as $tag){
            if ($tag=='') continue;
            $short = TextCleaner::sweetstring($tag);
            
            if (isset($existing[$short])) continue;
            $sql = "INSERT INTO ".$db->prefix("mod_mywords_tags")." (`tag`,`shortname`,`posts`) VALUES ('$tag','$short','0')";
            if ($db->queryF($sql)){
                $ids[] = $db->getInsertId();
            }
            
        }

        return empty($ids) ? array() : $ids;
        
    }
    
    /**
    * Get correct base url for links
    */
    public static function get_url($track = false){
        global $xoopsModule, $xoopsModuleConfig;
        $mc = RMSettings::module_settings( 'mywords' );
        
        if ($mc->permalinks > 1){
            
            $ret = XOOPS_URL.rtrim($mc->basepath, "/").'/';
            if ($track) $ret .= 'trackback/';
            
        } else {
            $ret = XOOPS_URL.'/modules/mywords/';
            if ($track) $ret .= 'trackbacks.php?trackback=';
        }
        
        return $ret;
    }
    
    public function format_time($time){
		
		$day = date('d', $time);
    	$month = date('m', $time);
    	$year = date('Y', $time);
		$format = __("Published on %s at %s",'mywords');
		
        $config = RMSettings::module_settings('mywords');
        if ($config->permalinks>1){
            $url = MWFunctions::get_url()."$day/$month/$year/";
        } else {
            $url = MWFunctions::get_url()."?date=$day/$month/$year/";
        }
        
		$date = '<a href="'.$url.'">'.date(__('D d M, Y','mywords'), $time).'</a>';
		$hour = date(__('H:i','mywords'), $time);
		
		$rtn = sprintf($format, $date, $hour);
		return $rtn;
		
    }
    
    public function go_scheduled(){
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$sql = "UPDATE ".$db->prefix("mod_mywords_posts")." SET pubdate=schedule, schedule=0, status='publish' WHERE status<>'draft' AND pubdate<schedule AND schedule<=".time();
		return $db->queryF($sql);
    }
    
    public function show_password($post){
        
        global $xoopsTpl;
        
        $xoopsTpl->assign('post', array(
            'id' => $post->id(),
            'permalink' => $post->permalink()
        ));
        
        $xoopsTpl->assign('lang_thispost', __('This post has been protected by a password. To read it you must provide the correct password.','mywords'));
        $xoopsTpl->assign('lang_password', __('Password:','mywords'));
        $xoopsTpl->assign('lang_submit', __('Show Post','mywords'));
        
        return $xoopsTpl->fetch("db:mywords_password.html");
        
    }
    
    /**
    * Get posts by category
    */
    static public function get_posts_by_cat($cat, $start=0, $limit=1, $orderby='pubdate', $order='DESC', $status='publish'){
		
		$path = XOOPS_ROOT_PATH.'/modules/mywords';
		include_once $path.'/class/mwpost.class.php';
		
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		if ($cat>0){
			$sql = "SELECT a.* FROM ".$db->prefix("mod_mywords_posts")." as a, ".$db->prefix("mod_mywords_catpost")." as b WHERE
				b.cat='$cat' AND a.id_post=b.post AND a.status='$status' ORDER BY a.$orderby $order LIMIT $start,$limit";
        } else {
			$sql = "SELECT a.* FROM ".$db->prefix("mod_mywords_posts")." as a WHERE
				a.status='$status' ORDER BY a.$orderby $order LIMIT $start,$limit";
		}

		$result = $db->query($sql);
		$ret = array();
		while($row = $db->fetchArray($result)){
			$post = new MWPost();
			$post->assignVars($row);
			$ret[] = $post;
		}
		
		return $ret;
		
    }
    
    /**
    * Get posts by tag
    * 
    * @param array|int Tag id
    * @param int Start
    * @param int Max results
    * @param string Column to sort
    * @param string Sort direction ASC or DESC, etc
    * @param string Posts status, published, draft, etc.
    * @return array
    */
    public function get_posts_by_tag($tags, $start=0, $limit=1, $orderby='pubdate', $order='DESC', $status='publish', $exclude = 0){
    	$path = XOOPS_ROOT_PATH.'/modules/mywords';
		include_once $path.'/class/mwpost.class.php';

        if(empty($tags) || $tags<=0)
            return false;

		$tags = !is_array($tags) ? array($tags) : $tags;
		$db = XoopsDatabaseFactory::getDatabaseConnection();

		$sql = "SELECT a.* FROM ".$db->prefix("mod_mywords_posts")." as a, ".$db->prefix("mod_mywords_tagspost")." as b WHERE
				b.tag IN (".implode(",",$tags).") AND a.id_post=b.post AND a.status='$status' AND a.id_post != $exclude GROUP BY a.id_post ORDER BY ".( $orderby != 'RAND()' ? "a.$orderby" : $orderby )." $order LIMIT $start,$limit";

		$result = $db->query($sql);
		$ret = array();
		while($row = $db->fetchArray($result)){
			$post = new MWPost();
			$post->assignVars($row);
			$ret[] = $post;
		}
		
		return $ret;
	}
    
    static public function get_posts($start=0, $limit=1, $orderby='pubdate', $order='DESC', $status='publish'){
		
		return self::get_posts_by_cat(0, $start, $limit, $orderby, $order, $status);
		
    }
    
    static public function get_filtered_posts($where = '', $start = 0, $limit = 1, $orderby='pubdate',$sort='desc',$status='publish'){
		
		$path = XOOPS_ROOT_PATH.'/modules/mywords';
		include_once $path.'/class/mwpost.class.php';
		
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		
		$sql = "SELECT * FROM ".$db->prefix("mod_mywords_posts");
		if ($where!=''){
			$sql .= " WHERE $where";
		}
		if($status!=''){
			$sql .= $where!='' ? " AND status='$status'" : " WHERE status='$status'";
		}
		if($orderby!=''){
			$sql .= " ORDER BY $orderby";
		}
		if($sort!=''){
			$sql .= " $sort";
		}
		$sql .= " LIMIT $start,$limit";

		$result = $db->query($sql);
		$ret = array();
		while($row = $db->fetchArray($result)){
			$post = new MWPost();
			$post->assignVars($row);
			$ret[] = $post;
		}
		
		return $ret;
		
    }
    
    /**
    * Verify if a user is a registered editor
    */
    public function is_editor($uid=0){
        if ($uid<=0) return false;
        
        $editor = new MWEditor();
        $editor->from_user($uid);
        return !$editor->isNew();
        
    }

    public static function get_editors( $start, $limit, $where = '', $sort = 'name', $order = 'ASC' ){

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM " . $db->prefix("mod_mywords_editors");
        if ( $where != '' )
            $sql .= " WHERE $where";

        if ( $sort != '' )
            $sql .= " ORDER BY $sort $order";

        $sql .= " LIMIT $start, $limit";

        $editors = array();
        $result = $db->query( $sql );
        while( $row = $db->fetchArray ( $result ) ) {
            $editor = new MWEditor();
            $editor->assignVars( $row );
            $editors[] = $editor;
        }

        return $editors;

    }
	
}
