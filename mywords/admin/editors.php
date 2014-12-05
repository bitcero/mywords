<?php
// $Id: editors.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','editors');
require('header.php');

/**
 * Mostramos la lista de editores junto con
 * el formulario para crear nuevos editores
 */
function show_editors(){
	global $tpl, $xoopsUser, $xoopsSecurity, $xoopsModule;
	
	MWFunctions::include_required_files();

    RMTemplate::get()->assign('xoops_pagetitle', __('Editors Management','mywords'));
	include_once RMCPATH.'/class/form.class.php';
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$db = XoopsDatabaseFactory::getDatabaseConnection();	
    list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_editors")));
    $page = rmc_server_var($_GET, 'page', 1);
    $limit = isset($limit) && $limit>0 ? $limit : 15;
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('editors.php?page={PAGE_NUM}');
	$result = $db->query("SELECT * FROM ".$db->prefix("mod_mywords_editors")." ORDER BY name LIMIT $start,$limit");
    $editores = array();			
    
	while($row = $db->fetchArray($result)){
        $ed = new MWEditor();
        $ed->assignVars($row);
		$tpl->append('editors', $ed);
	}

    RMBreadCrumb::get()->add_crumb(__('Editors Management','mywords'));
	
	xoops_cp_header();
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('../include/js/scripts.php?file=editors.js');
	include RMTemplate::get()->get_template('admin/mywords-editors.php','module','mywords');
	
	xoops_cp_footer();
	
}

function edit_editor(){
    global $xoopsModule, $xoopsSecurity;
    
    $id = rmc_server_var($_GET,'id',0);
    $page = rmc_server_var($_GET,'page',1);
    
    if($id<=0){
        redirectMsg('editors.php?page='.$page, __('Editor ID not provided!.','mywords'), 1);
        die();
    }
    
    $editor = new MWEditor($id);
    if($editor->isNew()){
        redirectMsg('editors.php?page='.$page, __('Editor does not exists!','mywords'), 1);
        die();
    }
    
    include_once RMCPATH.'/class/form.class.php';
    
    MWFunctions::include_required_files();

    RMTemplate::get()->assign('xoops_pagetitle', __('Editing Editor','mywords'));

    RMBreadCrumb::get()->add_crumb(__('Editors Management', 'mywords'), 'editors.php');
    RMBreadCrumb::get()->add_crumb(__('Edit Editor','mywords'));

    xoops_cp_header();   
    $show_edit = true;
    include RMTemplate::get()->get_template('admin/mywords-editors.php','module','mywords');
    xoops_cp_footer();
    
}

/**
 * Agregamos nuevos editores a la base de datos
 */
function save_editor($edit = false){
	global $xoopsConfig, $xoopsSecurity;
	
    $page = rmc_server_var($_POST, 'page', 1);
    
    if (!$xoopsSecurity->check()){
        redirectMsg('editors.php?page='.$page, __('Operation not allowed!','mywords'), 1);
        die();
    }
    
    if ($edit){
        
        $id = rmc_server_var($_POST, 'id', 0);
        if ($id<=0){
            redirectMsg('editors.php?page='.$page, __('Editor ID has not been provided!','mywords'), 1);
            die();
        }
        
        $editor = new MWEditor($id);
        if ($editor->isNew()){
            redirectMsg('editors.php?page='.$page, __('Editor has not been found!','mywords'), 1);
            die();
        }
        
    } else {
        
        $editor = new MWEditor();
        
    }
    
    $name = rmc_server_var($_POST, 'name', '');
    $bio = rmc_server_var($_POST, 'bio', '');
    $uid = rmc_server_var($_POST, 'new_user', 0);
    $perms = rmc_server_var($_POST, 'perms', array());
    $short = rmc_server_var($_POST, 'short', '');
    
    if (trim($name)==''){
        redirectMsg('editors.php?page='.$page, __('You must provide a display name for this editor!','mywords'), 1);
        die();
    }
    
    if ($uid<=0){
        redirectMsg('editors.php?page='.$page, __('You must specify a registered user ID for this editor!','mywords'), 1);
        die();
    }
    
    // Check if XoopsUser is already register
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_editors")." WHERE uid=$uid";
    if ($edit) $sql .= " AND id_editor<>".$editor->id();
    list($num) = $db->fetchRow($db->query($sql));
    
    if ($num>0){
        redirectMsg('editors.php?page='.$page, __('This user has been registered as editor before.','mywords'), 1);
        die();
    }
    
    $editor->setVar('name', $name);
    $editor->setVar('shortname', TextCleaner::sweetstring($short!='' ? $short : $name));
    $editor->setVar('bio', $bio);
    $editor->setVar('uid', $uid);
    $editor->setVar('privileges', $perms);
    
    if(!$editor->save()){
        redirectMsg('editors.php?page='.$page, __('Errors occurs while trying to save editor data','mywords').'<br />'.$editor->errors(), 1);
        die();
    } else {
        redirectMsg('editors.php?page='.$page, __('Database updated succesfully!','mywords'), 0);
        die();
    }
    
	
}

function activate_editors($a){
    global $xoopsSecurity;
    
    $page = rmc_server_var($_POST, 'page', 1);
    $editors = rmc_server_var($_POST, 'editors', array());
    
    if (!$xoopsSecurity->check()){
        redirectMsg('editors.php?page='.$page, __('Sorry, operation not allowed!','mywords'), 1);
        die();
    }
    
    if (!is_array($editors) || empty($editors)){
        redirectMsg('editors.php?page='.$page, __('Please, specify a valid editor ID!','mywords'), 1);
        die();
    }
    
    // Delete all relations
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "UPDATE ".$db->prefix("mod_mywords_editors")." SET active='".($a?'1':'0')."' WHERE id_editor IN(".implode(',',$editors).")";
    if (!$db->queryF($sql)){
        redirectMsg('editors.php?page='.$page, __('Errors ocurred while trying to update database!','mywords')."\n".$db->error(), 1);
        die();
    }
    
    redirectMsg('editors.php?page='.$page, __('Database updated successfully!','mywords'), 0);
    
}

function delete_editors(){
    global $xoopsSecurity;
    
    $page = rmc_server_var($_POST, 'page', 1);
    $editors = rmc_server_var($_POST, 'editors', array());
    
    if (!$xoopsSecurity->check()){
        redirectMsg('editors.php?page='.$page, __('Sorry, operation not allowed!','mywords'), 1);
        die();
    }
    
    if (!is_array($editors) || empty($editors)){
        redirectMsg('editors.php?page='.$page, __('Please, specify a valid editor ID!','mywords'), 1);
        die();
    }
    
    // Delete all relations
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "UPDATE ".$db->prefix("mod_mywords_posts")." SET author='0' WHERE author IN(".implode(',',$editors).")";
    if (!$db->queryF($sql)){
        redirectMsg('editors.php?page='.$page, __('Errors ocurred while trying to delete editors!','mywords').'<br />'.$db->error(), 1);
        die();
    }
    
    $sql = "DELETE FROM ".$db->prefix("mod_mywords_editors")." WHERE id_editor IN(".implode(",",$editors).")";
    if (!$db->queryF($sql)){
        redirectMsg('editors.php?page='.$page, __('Errors ocurred while trying to delete editors!','mywords').'<br />'.$db->error(), 1);
        die();
    }
    
    redirectMsg('editors.php?page='.$page, __('Database updated succesfully!','mywords'), 0);
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
	case 'new':
		save_editor(false);
		break;
    case 'saveedit':
        save_editor(true);
        break;
    case 'edit':
        edit_editor();
        break;
	case 'delete':
		delete_editors();
		break;
    case 'deactivate':
        activate_editors(0);
        break;
    case 'activate':
        activate_editors(1);
        break;
	default:
		show_editors();
		break;
}
