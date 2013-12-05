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

	
list($numcats) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_categories")));
list($numposts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")));
list($numdrafts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE status='draft'"));
list($numpending) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE status='waiting'"));
list($numeditors) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_editors")));
list($numsocials) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_bookmarks")));
list($numcoms) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("rmc_comments")." WHERE id_obj='mywords'"));
list($numtags) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_tags")));
	
/**
* @desc Caragmaos los artículos recientemente enviados
*/
$drafts = array();
$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." WHERE status='draft' ORDER BY id_post DESC LIMIT 0,5");
while ($row = $db->fetchArray($result)){
    $post = new MWPost();
    $post->assignVars($row);
    $drafts[] = $post;
}

$pendings = array();
$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." WHERE status='waiting' ORDER BY id_post DESC LIMIT 0,8");
while ($row = $db->fetchArray($result)){
    $post = new MWPost();
    $post->assignVars($row);
    $pendings[] = $post;
}

// Editors
$sql = "SELECT *, (SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE author=id_editor) as counter FROM ".$db->prefix("mw_editors")." ORDER BY counter DESC LIMIT 0, 5";
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
if($xoopsModuleConfig['permalinks']>1){

    $rule = "RewriteRule ^".trim($xoopsModuleConfig['basepath'],'/')."/?(.*)$ modules/mywords/index.php [L]";
    $ht = new RMHtaccess('mywords');
    $htResult = $ht->write($rule);
    if($htResult!==true){
        showMessage(__('An error ocurred while trying to write .htaccess file!','mywords'), RMMSG_ERROR);
    }

}

$donateButton = '<form id="paypal-form" name="_xclick" action="https://www.paypal.com/fr/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="ohervis@redmexico.com.mx">
                    <input type="hidden" name="item_name" value="MyWords Support">
                    <input type="hidden" name="amount" value=0>
                    <input type="hidden" name="currency_code" value="USD">
                    <img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" onclick="$(\'#paypal-form\').submit()" alt="PayPal - The safer, easier way to pay online!" />
    </form>';
$myEmail = 'a888698732624c0a1d4da48f1e5c6bb4';
	
$url = "http://www.redmexico.com.mx/modules/vcontrol/?id=5&limit=4";

$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
$cHead .= "<link href=\"".XOOPS_URL."/modules/mywords/styles/admin.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />";

RMBreadCrumb::get()->add_crumb(__('Dashboard','mywords'));
	
include 'menu.php';
MWFunctions::include_required_files();
RMTemplate::get()->add_script('../include/js/scripts.php?file=dashboard.js');
RMTemplate::get()->add_help(__('MyWords Documentation','mywords'), 'http://www.xoopsmexico.net/docs/mywords/standalone/1/');
RMTemplate::get()->add_help(__('Dashboard Help','mywords'), 'http://www.xoopsmexico.net/docs/mywords/descripcion-del-modulo/standalone/1/#administracion');

xoops_cp_header();
	
// Show Templates
RMTemplate::get()->add_style('dashboard.css', 'mywords');
RMTemplate::get()->add_style('admin.css', 'mywords');
//$tpl->header();
include RMtemplate::get()->get_template('admin/mywords_theindex.php', 'module', 'mywords');
//$tpl->footer();
xoops_cp_footer();