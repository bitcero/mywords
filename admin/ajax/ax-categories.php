<?php
// $Id: ax-categories.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Handle ajax requests for categories
 */
require  dirname(__DIR__) . '/header.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

extract($_POST);

if (!$xoopsSecurity->check() || !$xoopsSecurity->checkReferer()) {
    $ret = [
        'error' => __('You are not allowed to do this operation!', 'mywords'),
    ];
    echo json_encode($ret);
    die();
}

if (!isset($name) || '' == $name) {
    $ret = [
        'error' => __('A name is neccesary to create a new category!', 'mywords'),
        'token' => $xoopsSecurity->createToken(),
    ];
    echo json_encode($ret);
    die();
}

$catego = new MWCategory();
$catego->setVar('name', $name);
$catego->setVar('shortname', TextCleaner::sweetstring($name));
$catego->setVar('parent', $parent);

if (MWFunctions::category_exists($catego)) {
    $ret = [
        'error' => __('There is already a category with same name!', 'mywords'),
        'token' => $xoopsSecurity->createToken(),
    ];
    echo json_encode($ret);
    die();
}

if (!$catego->save()) {
    $ret = [
        'error' => __('Category could not inserted!', 'mywords') . "\n" . $catego->errors(),
        'token' => $xoopsSecurity->createToken(),
    ];
    echo json_encode($ret);
    die();
}

$ret = [
    'message' => __('Category created successfully!', 'mywords'),
    'token' => $xoopsSecurity->createToken(),
    'id' => $catego->id(),
];
echo json_encode($ret);
