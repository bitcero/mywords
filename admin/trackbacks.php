<?php
/**
 * MyWords for XOOPS
 *
 * Copyright © 2017 Eduardo Cortés http://www.eduardocortes.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Eduardo Cortés (http://www.eduardocortes.mx)
 * @license      GNU GPL 2
 * @package      mywords
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.eduardocortes.mx
 */

require('header.php');
$common->location = 'trackbacks';

RMTemplate::get()->assign('xoops_pagetitle', __('Trackbacks management', 'mw_categories'));

function show_mw_trackbacks()
{
    global $xoopsModule, $xoopsSecurity;
    
    $id = rmc_server_var($_GET, 'id', 0);
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("mod_mywords_trackbacks");
    if ($id>0) {
        $sql .= "WHERE post=$id";
    }
    list($num) = $db->fetchRow($db->query($sql));
    $page = rmc_server_var($_GET, 'page', 1);
    $limit = isset($limit) && $limit>0 ? $limit : 15;
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('trackbacks.php?page={PAGE_NUM}'.($id>0 ? '&amp;id='.$id : ''));
    
    $sql = str_replace("COUNT(*)", '*', $sql);
    $sql .= " ORDER BY `date` LIMIT $start,$limit";
    $result = $db->query($sql);
    
    $posts = array(); // posts cache
    $trackbacks = array();
    
    // Get trackbacks data
    while ($row = $db->fetchArray($result)) {
        $trac = new MWTrackbackObject();
        $trac->assignVars($row);
        $posts[$trac->getVar('post')] = isset($posts[$trac->getVar('post')]) ? $posts[$trac->getVar('post')] : new MWPost($trac->getVar('post'));
        $trackbacks[] = array(
            'tb'    => $trac,
            'post'  => array(
                'title'=>$posts[$trac->getVar('post')]->getVar('title'),
                'link'=>$posts[$trac->getVar('post')]->isNew() ? '' : $posts[$trac->getVar('post')]->permalink()
            )
        );
    }
    
    // Event
    $trackbacks = RMEvents::get()->run_event("mywords.trackbacks.list", $trackbacks);
    
    MWFunctions::include_required_files();
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script(XOOPS_URL.'/modules/mywords/include/js/scripts.php?file=trackbacks.js');

    RMBreadCrumb::get()->add_crumb(__('Trackbacks', 'mywords'));

    xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/mywords-trackbacks.php', 'module', 'mywords');
    
    xoops_cp_footer();
}

function delete_mw_trackbacks()
{
    global $xoopsSecurity;
    
    $tbs = rmc_server_var($_POST, 'tbs', array());
    
    if (empty($tbs) || !is_array($tbs)) {
        redirectMsg('trackbacks.php', __('Select one trackback at least!', 'mw_categories'), 1);
        die();
    }
    
    if (!$xoopsSecurity->check()) {
        redirectMsg('trackbacks.php', __('Session token expired!', 'mywords'), 1);
        die();
    }
    
    // Event
    RMEvents::get()->run_event('mywords.before.delete.trackback', $tbs);
    
    foreach ($tbs as $id) {
        $trac = new MWTrackbackObject($id);
        if ($trac->isNew()) {
            continue;
        }
        
        $trac->delete();
    }
    
    redirectMsg('trackbacks.php', __('Trackbacks deleted successfully', 'mywords'), 0);
}


$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {
    case 'delete':
        delete_mw_trackbacks();
        break;
    default:
        show_mw_trackbacks(0);
        break;
}
