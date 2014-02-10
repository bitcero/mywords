<?php
// $Id: index.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include("../../mainfile.php");

$request = str_replace(XOOPS_URL, '', RMUris::current_url());
$request = str_replace("/modules/mywords/", '', $request);

if ($xoopsModuleConfig['permalinks']>1 && $xoopsModuleConfig['basepath']!='/'){
    $request = str_replace(rtrim($xoopsModuleConfig['basepath'],'/').'/', '', rtrim($request,'/').'/');
}

$yesquery = false;

if (substr($request, 0, 1)=='?'){ $request = substr($request, 1); $yesquery=true; }
if ($request=='' || $request=='index.php'){
	require 'home.php';
	die();
}

$params = explode("/", $request);
if ($params[0]=='page'){
	require 'home.php';
	die();
}

$vars = array();
parse_str($request, $vars);

$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
if (isset($_REQUEST['trackback'])){ require 'track.php'; die(); }
if (isset($vars['post'])){ $post = $vars['post']; require 'post.php'; die(); }
if (isset($vars['cat'])){ $category = $vars['cat']; require 'categories.php'; die(); }
if (isset($vars['author'])){ $editor = $vars['author']; require 'author.php'; die(); }
if (isset($vars['tag'])){ $tag = $vars['tag']; require 'tag.php'; die(); }
if (isset($vars['edit'])){ require 'submit.php'; die(); }
if (isset($vars['trackback'])){ $id = $vars['trackback']; require 'trackbacks.php'; die(); }
if (isset($vars['date'])){ 
    $vars = explode("/", $vars['date']);
    $time = mktime(0,0,0,$vars[1],$vars[0],$vars[2]);
    $time2 = mktime(23,59,59,$vars[1],$vars[0],$vars[2]);
    require 'date.php'; die();
}

$vars = explode('/', $request);

// Si los primeros tres valores son numéricos entonces se trata de un artículo
// Solicitado por fecha y por título
$db = XoopsDatabaseFactory::getDatabaseConnection();
if (is_numeric($vars[0]) && is_numeric($vars[1]) && is_numeric($vars[2])){
	
    $time = mktime(0,0,0,$vars[1],$vars[0],$vars[2]);
    
    // Check if query is for a date range
    if (!isset($vars[3]) || $vars[3]=='page' || $vars[3]==''){
        $time2 = mktime(23,59,59,$vars[1],$vars[0],$vars[2]);
        require 'date.php';
        die();
    }

	$sql = "SELECT id_post FROM ".$db->prefix("mw_posts")." WHERE shortname='$vars[3]' AND (pubdate>=$time AND pubdate<=".($time + 86400).")";
	$result = $db->query($sql);
	list($post) = $db->fetchRow($result);
	require 'post.php';
	die();
}

/**
 * Si el primer valor es igual a post entonces se trata de una
 * artículo solicitado numéricamente
 */
if ($vars[0]=='post'){
	$post = $vars[1];
	require 'post.php';
	die();
}
/**
 * Si el primer valor es category entonces se realiza la búsqueda por
 * categoría
 */
if ($vars[0]=='category'){
	$categotype = 1;
	require 'categories.php';
	die();
}
/**
 * Si el primer valor es "author" entonce se
 * realiza la búsqueda por nombre de autor
 */
if ($vars[0]=='author'){
	$editor = $vars[1];
	require 'author.php';
	die();
}

if ($vars[0]=='tag'){
    $tag = $vars[1];
    require 'tag.php';
    die();
}

if ($vars[0]=='trackback'){
    $id = $vars[1];
    require 'trackbacks.php';
    die();
}

/**
 * Si el primer valor es submit
 * entonces se muestra el formulario
 * para enviar un artículo
 */
if ($vars[0]=='submit'){
	require 'submit.php';
	die();
}

if ($vars[0]=='edit'){
	$vars['edit'] = $vars[1];
	require 'submit.php';
	die();
}

if ($yesquery || $vars[0]==''){
	require 'home.php';
	die();
}


header("HTTP/1.0 404 Not Found");
if (substr(php_sapi_name(), 0, 3) == 'cgi')
      header('Status: 404 Not Found', TRUE);
  	else
      header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

echo "<h1>ERROR 404. Document not Found</h1>";
die();

decodeHeader();
