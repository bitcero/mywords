<?php
// $Id: rmcommon.php 971 2012-05-31 04:21:08Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MywordsRmcommonPreload
{
	
    public function eventRmcommonLoadRightWidgets($widgets){
		global $xoopsModule;
        
		if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='mywords'))
			return $widgets;
		
	    if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='new_post'){
			include_once '../widgets/widget_publish.php';
			$widgets[] = mw_widget_publish();

            include_once '../widgets/widget_image.php';
            $widgets[] = mw_widget_image();
			
			include_once '../widgets/widget_categories.php';
			$widgets[] = mw_widget_categories();
	        
	        include_once '../widgets/widget_tags.php';
	        $widgets[] = mw_widget_addtags();
	        
	    }
        
		return $widgets;
	}
    
    public function eventRmcommonGetSystemTools($tools){
        
        load_mod_locale('mywords', 'admin_');
        
        $rtn = array(
            'link'  => '../mywords/admin/',
            'icon'  => '../mywords/images/icon16.png',
            'caption' => __('MyWords Administration', 'admin_mywords')
        );
        
        $tools[] = $rtn;
        
        return $tools;
        
    }
    
    public function eventRmcommonLoadingSingleEditorimgs($items, $url){
    	
    	if (FALSE === strpos($url, 'modules/mywords/admin/posts.php')) return $items;
    	
		parse_str($url);
		if (!isset($id) || $id<=0) return $items;
		
		xoops_load('mwpost.class','mywords');
		xoops_load('mwfunctions','mywords');
		
		$post = new MWPost($id);
		if ($post->isNew()) return $items;
		
		$items['links']['post'] = array('caption'=>__('Link to post','mywords'),'value'=>$post->permalink());
		return $items;
		
    }
    
    /**
    * Return the feed options to show in RSS Center
    */
	public function eventRmcommonGetFeedsList($feeds){
		
        include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';
		load_mod_locale('mywords');
		
		$module = RMFunctions::load_module('mywords');
		$config = RMSettings::module_settings('mywords');

		$data = array(
				'title'	=> $module->name(),
				'url'	=> XOOPS_URL.$config->basepath,
				'module' => 'mywords'
		);
		
		$options[] = array(
			'title'	=> __('All Recent Posts', 'mywords'),
			'params' => 'show=all',
			'description' => __('Show all recent posts','mywords')
		);
		
		$categories = array();
		MWFunctions::categos_list($categories);
		
		$table = '<table cellpadding="2" cellspacing="2" width="100%"><tr class="even">';
		$count = 0;
		foreach($categories as $cat){
			if ($count>=3){
				$count = 0;
				$table .= '</tr><tr class="'.tpl_cycle("odd,even").'">';
			}
			$table .= '<td width="33%"><a href="'.XOOPS_URL.'/backend.php?action=showfeed&amp;mod=mywords&amp;show=cat&amp;cat='.$cat['id_cat'].'">'.$cat['name'].'</a></td>';
			$count++;
		}
		$table .= '</tr></table>';
		
		$options[] = array(
			'title' => __('Posts by category','mywords'),
			'description' => __('Select a category to see the posts published recently.','mywords').' <a href="javascript:;" onclick="$(\'#categories-feed\').slideToggle(\'slow\');">Show Categories</a>
						    <div id="categories-feed" style="padding: 10px; display: none;">'.$table.'</div>'
		);
		
		unset($categories);
		
		$tags = MWFunctions::get_tags("*",'','',99);
		$table = '<table cellpadding="2" cellspacing="2" width="100%"><tr class="even">';
		$count = 0;
		foreach($tags as $tag){
			if ($count>=3){
				$count = 0;
				$table .= '</tr><tr class="'.tpl_cycle("odd,even").'">';
			}
			$table .= '<td width="33%"><a href="'.XOOPS_URL.'/backend.php?action=showfeed&amp;mod=mywords&amp;show=tag&amp;tag='.$tag['id_tag'].'">'.$tag['tag'].'</a></td>';
			$count++;
		}
		$table .= '</tr></table>';
		
		$options[] = array(
			'title' => __('Show posts by tag','mywords'),
			'description' => __('Select a tag to see the posts published recently.','mywords').' <a href="javascript:;" onclick="$(\'#tags-feed\').slideToggle(\'slow\');">Show Tags</a>
						    <div id="tags-feed" style="padding: 10px; display: none;">'.$table.'</div>'
		);
        
        unset($tags);
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM ".$db->prefix("mw_editors")." ORDER BY name";
        $result = $db->query($sql);
        $editors = array();
        while ($row = $db->fetchArray($result)){
            $editors[] = $row;
        }
        asort($editors);
        
        $table = '<table cellpadding="2" cellspacing="2" width="100%"><tr class="even">';
        $count = 0;
        foreach($editors as $ed){
            if ($count>=3){
                $count = 0;
                $table .= '</tr><tr class="'.tpl_cycle("odd,even").'">';
            }
            $table .= '<td width="33%"><a href="'.XOOPS_URL.'/backend.php?action=showfeed&amp;mod=mywords&amp;show=author&amp;author='.$ed['id_editor'].'">'.$ed['name'].'</a></td>';
            $count++;
        }
        $table .= '</tr></table>';
		
		$options[] = array(
			'title' => __('Show posts by author','mywords'),
			'description' => __('Select an author to see the posts published recently.','mywords').' <a href="javascript:;" onclick="$(\'#editor-feed\').slideToggle(\'slow\');">Show Authors</a>
						    <div id="editor-feed" style="padding: 10px; display: none;">'.$table.'</div>'
		);
        
        unset($editors);
		unset($table);
        
        RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.min.js');
        RMTemplate::get()->add_script(RMCURL.'/include/js/jquery-ui.min.js');
		
		$feed = array('data'=>$data,'options'=>$options);
		$feeds[] = $feed;
		return $feeds;
		
	}
	
}
