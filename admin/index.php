<?php
// $Id: index.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','dashboard');
require 'header.php';

	
list($numcats) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_categories")));
list($numposts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")));
list($numdrafts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")." WHERE status='draft'"));
list($numpending) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")." WHERE status='waiting'"));
list($numeditors) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_editors")));
list($numsocials) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_bookmarks")));
list($numcoms) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_rmcommon_comments")." WHERE id_obj='mywords'"));
list($numtags) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_tags")));
	
/**
* @desc Caragmaos los artículos recientemente enviados
*/
$drafts = array();
$result = $db->query("SELECT * FROM ".$db->prefix("mod_mywords_posts")." WHERE status='draft' ORDER BY id_post DESC LIMIT 0,5");
while ($row = $db->fetchArray($result)){
    $post = new MWPost();
    $post->assignVars($row);
    $drafts[] = $post;
}

$pendings = array();
$result = $db->query("SELECT * FROM ".$db->prefix("mod_mywords_posts")." WHERE status='waiting' ORDER BY id_post DESC LIMIT 0,8");
while ($row = $db->fetchArray($result)){
    $post = new MWPost();
    $post->assignVars($row);
    $pendings[] = $post;
}

// Editors
$sql = "SELECT *, (SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_posts")." WHERE author=id_editor) as counter FROM ".$db->prefix("mod_mywords_editors")." ORDER BY counter DESC LIMIT 0, 5";
$result = $db->query($sql);
$editors = array();
while($row = $db->fetchArray($result)){
    $editor = new MWEditor();
    $editor->assignVars($row);
    $editors[] = array(
        'id'    => $editor->id(),
        'name'  => $editor->getVar('name'),
        'link'  => $editor->permalink(),
        'total' => $row['counter']
    );
}
unset($editor, $result, $sql);

// URL rewriting
$rule = "RewriteRule ^".trim($xoopsModuleConfig['basepath'],'/')."/?(.*)$ modules/mywords/index.php [L]";
if($xoopsModuleConfig['permalinks']>1){

    $ht = new RMHtaccess('mywords');
    $htResult = $ht->write($rule);
    if($htResult!==true){
        showMessage(__('An error ocurred while trying to write .htaccess file!','mywords'), RMMSG_ERROR);
    }

} else {
    $ht = new RMHtaccess( 'mywords' );
    $ht->removeRule();
    $ht->write();
}

RMBreadCrumb::get()->add_crumb(__('Dashboard','mywords'));
	
include 'menu.php';
MWFunctions::include_required_files();
RMTemplate::get()->add_script('../include/js/scripts.php?file=dashboard.js');
RMTemplate::get()->add_help(__('MyWords Documentation','mywords'), 'http://www.xoopsmexico.net/docs/mywords/introduccion/');

// Other panels for dashboard
$dashboardPanels = [];
$dashboardPanels = RMEvents::get()->trigger('mywords.dashboard.panels', $dashboardPanels);

xoops_cp_header();
	
// Show Templates
RMTemplate::get()->add_body_class('dashboard');

//$tpl->header();
include RMtemplate::get()->get_template('admin/mywords-theindex.php', 'module', 'mywords');
//$tpl->footer();
xoops_cp_footer();