<?php
// $Id: scripts.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file allows to mywords to load js files when language is needed in this files
*/

$wfile = isset($_GET['file']) ? $_GET['file'] : '';
if ($wfile=='') {
    exit();
}

$path = dirname(__FILE__);
if (!file_exists($path.'/'.$wfile)) {
    exit();
}

$path .= '/'.$wfile;

include_once '../../../../mainfile.php';
include_once '../../../rmcommon/loader.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

header('Content-Type: application/javascript');
switch ($wfile) {
    
    default:
        include $path;
        break;
    
}
