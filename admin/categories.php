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
$common->location = 'categories';

RMTemplate::get()->add_help(
    'Uso de las categorías',
    'http://www.xoopsmexico.net/docs/mywords/categorias/'
);

/**
 * Muestra una lista de las categorías existentes
 */
function showCategos()
{
    global $tpl, $xoopsSecurity, $xoopsModule, $xoopsSecurity;

    $row = [];
    MWFunctions::categos_list($row);
    $categories = [];

    foreach ($row as $k) {
        $catego = new MWCategory();
        $catego->assignVars($k);
        $catego->loadPosts();
        $categories[] = [
            'id_cat' => $catego->id(),
            'description' => $catego->getVar('description', 'n'),
            'posts' => $catego->getVar('posts', 'n'),
            'name' => $catego->getVar('name', 'n'),
            'indent' => $k['indent'],
            'shortname' => $catego->getVar('shortname', 'n'),
        ];
    }

    // Categories pagination
    // Paginamos
    $limit = 15;
    if (count($categories) > $limit) {
        $page = rmc_server_var($_GET, 'page', 1);
        $page = $page < 1 ? 1 : $page;
        $limit_c = $page * ($limit - 1);

        $limit_c = $limit_c > count($categories) - 1 ? count($categories) - 1 : $limit_c;
        $tpages = ceil(count($categories) / 3);

        if ($page > $tpages) {
            $page = $tpages;
        }

        $nav = new RMPageNav(count($categories), $limit, $page, 5);
        $nav->target_url('?page={PAGE_NUM}');

        $i = ($page - 1) * $limit;

        while ($i <= $limit_c) {
            $pcategories[] = $categories[$i];
            $i++;
        }

        $categories = $pcategories;
    }

    MWFunctions::include_required_files();

    RMTemplate::get()->add_head(
        '<script type="text/javascript">
			function cat_del_confirm(cat, id){
	  
			  var string = "' . __('Do you really want to delete \"%s\"', 'mywords') . '";
	 				string = string.replace("%s", cat);
	 				var ret = confirm(string);
	 				
	 				if (ret){
	 					$("#tblCats input[type=checkbox]").removeAttr("checked");
	 					$("#cat-"+id).attr("checked","checked");
	 					$("#cat-op").val("delete");
	 					$("#tblCats").submit();
					}
			  
		  }
	  </script>'
    );

    RMBreadCrumb::get()->add_crumb(__('Categories', 'mywords'));

    xoops_cp_header();

    $desc = '';
    $posts = '';
    $parent = '';
    $selcat = '';
    $new = '';
    $name = '';
    $shortcut = '';
    extract($_GET);
    include RMTemplate::get()->get_template('admin/mywords-categories.php', 'module', 'mywords');
    RMTemplate::get()->assign('xoops_pagetitle', __('Categories Management', 'mywords'));
    RMTemplate::get()->add_script('jquery.checkboxes.js', 'rmcommon');
    RMTemplate::get()->add_script('../include/js/categories.js');

    xoops_cp_footer();
}

/**
 * Presenta un formulario para la creación de una nueva
 * categoría para los artículos
 */
function newForm()
{
    global $xoopsModule;

    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    if ($id <= 0) {
        redirectMsg('categories.php', __('You must specify a valid category', 'mywords'), 1);
        die();
    }
    // Cargamos la categoría
    $catego = new MWCategory($id);
    // Si no existe entonces devolvemos un error
    if ($catego->isNew()) {
        redirectMsg('cats.php', __('Specified category not exists!', 'mywords'), 1);
        die();
    }

    MWFunctions::include_required_files();

    RMBreadCrumb::get()->add_crumb(__('Categories', 'mywords'), 'categories.php');
    RMBreadCrumb::get()->add_crumb(__('Edit category', 'mywords'));

    xoops_cp_header();

    $cats = [];
    MWFunctions::categos_list($cats, 0, 0, true, $id);

    $form = new RMForm([
        'title' => __('Edit Category', 'mywords'),
        'name' => 'frmNew',
        'action' => 'categories.php',
        'data-translate' => 'true',
        'method' => 'post',
    ]);
    //$form = new RMForm($edit ? __('Edit Category','mywords') : __('Edit Category','mywords'), 'frmNew', 'categories.php');

    $form->addElement(new RMFormText(__('Category name', 'mywords'), 'name', 50, 150, $catego->getVar('name')), true);
    $form->addElement(new RMFormText(__('Category slug', 'mywords'), 'shortname', '', '150', $catego->getVar('shortname', 'n')));
    $form->addElement(new RMFormTextArea(__('Category description', 'mywords'), 'desc', 5, 45, $catego->getVar('description', 'e')));
    $ele = new RMFormSelect(__('Category Parent', 'mywords'), 'parent');
    $ele->addOption(0, _SELECT, 0 == $catego->getVar('parent') ? 1 : 0);
    foreach ($cats as $k) {
        $ele->addOption($k['id_cat'], str_repeat('-', $k['indent']) . ' ' . $k['name'], $catego->getVar('parent') == $k['id_cat'] ? 1 : 0);
    }
    $form->addElement($ele);

    $form->addElement(new RMFormHidden('op', 'saveedit'));
    $form->addElement(new RMFormHidden('id', $id));

    $ele = new RMFormButtonGroup('', ' ');
    $ele->addButton('sbt', __('Update Category', 'mywords'), 'submit');
    $ele->addButton('cancel', __('Cancel', 'mywords'), 'button');
    $ele->setExtra('cancel', "onclick='history.go(-1);'");
    $form->addElement($ele);
    $form->display();

    xoops_cp_footer();
}

/**
 * Almacenamos la categoría en la base de datos
 * @param mixed $edit
 */
function saveCatego($edit = 0)
{
    global $xoopsSecurity, $db;

    if (!$xoopsSecurity->check()) {
        redirectMsg('categories.php', __('Sorry, session token expired!', 'mywords'), 1);
        die();
    }

    $query = '';
    foreach ($_POST as $k => $v) {
        $$k = $v;
        if ('op' == $k || 'XOOPS_TOKEN_REQUEST' == $k) {
            continue;
        }
        $query .= '' == $query ? "$k=" . urlencode($v) : '&' . $k . '=' . urlencode($v);
    }

    $query = $edit ? '&op=edit' : '';

    if ($edit) {
        if ($id <= 0) {
            redirectMsg('categories.php', __('You must specify a valid category', 'mywords'), 1);
            die();
        }

        $catego = new MWCategory($id);
        if ($catego->isNew()) {
            redirectMsg('categories.php', __('Specified category not exists!', 'mywords'), 1);
            die();
        }
    } else {
        $catego = new MWCategory();
    }

    if ('' == $name) {
        redirectMsg('categories.php?' . $query, __('Please specify a name for this category!', 'mywords'), 1);
        die();
    }

    $shortname = '' == $shortname ? TextCleaner::sweetstring($name) : $shortname;

    # Verificamos que no exista la categoría
    $result = $db->query('SELECT COUNT(*) FROM ' . $db->prefix('mod_mywords_categories') . " WHERE parent='$parent'" . ($edit ? " AND id_cat<>$id" : '') . " AND (name='$name' OR shortname='$shortname')");
    list($num) = $db->fetchRow($result);

    if ($num > 0) {
        redirectMsg('categories.php?' . $query, __('There is already a category with the same name!', 'mywords'), 1);
        die();
    }

    # Si todo esta bien guardamos la categoría
    $catego->setVar('name', $name);
    $catego->setVar('shortname', $shortname);
    $catego->setVar('description', $desc);
    $catego->setVar('parent', $parent);
    if (!$edit) {
        $catego->setVar('posts', 0);
    }

    $result = $catego->save();

    if ($result) {
        redirectMsg('categories.php', __('Category created succesfully!', 'mywords'), 0);
    } else {
        redirectMsg('categories.php?' . $query, __('There was an error!', 'mywords') . '<br>' . $catego->errors(), 1);
    }
}
/**
 * Elimina una categoría de la base de datos.
 * Las subcategorías pertenecientes a esta categoría no son eliminadas,
 * sino que son asignadas a la categoría superior.
 */
function deleteCatego()
{
    global $xoopsSecurity, $xoopsModule;

    $cats = rmc_server_var($_POST, 'cats', []);

    if (empty($cats)) {
        redirectMsg('categories.php', __('You must select one category at least!', 'mywords'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('categories.php', __('Session token expired!', 'mw_categories'), 1);
        die();
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'SELECT * FROM ' . $db->prefix('mod_mywords_categories') . ' WHERE id_cat IN (' . implode(',', $cats) . ')';
    $result = $db->query($sql);

    while (false !== ($row = $db->fetchArray($result))) {
        $cat = new MWCategory();
        $cat->assignVars($row);
        if (!$cat->delete()) {
            showMessage(__('Category "%s" could not be deleted', 'mywords'), 1);
        }
    }

    redirectMsg('categories.php', __('Database updated!', 'mw_categories'), 0);
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op) {
    case 'save':
        saveCatego();
        break;
    case 'saveedit':
        saveCatego(1);
        break;
    case 'edit':
        newForm();
        break;
    case 'delete':
        deleteCatego();
        break;
    default:
        showCategos();
        break;
}
