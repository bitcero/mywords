<?php
// $Id: block.tags.php 901 2012-01-03 07:08:22Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwtag.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';

function myWordsBlockTags($options){
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT * FROM ".$db->prefix("mw_tags")." ORDER BY RAND() LIMIT 0,$options[0]";
    $result = $db->query($sql);
    $block = array();
    $max = 0;
    $min = 0;
    while($row = $db->fetchArray($result)){
        $tag = new MWTag();
        $tag->assignVars($row);
        $block['tags'][] = array(
            'id'=>$tag->id(),
            'posts'=>$tag->getVar('posts'),
            'link'=>$tag->permalink(),
            'name'=>$tag->getVar('tag'),
            'size'=>($options[1] * $tag->getVar('posts') + 0.9)
        );
    }
    
    RMTemplate::get()->add_style('mwblocks.css', 'mywords');
    
    return $block;
}

function myWordsBlockTagsEdit($options){

    ob_start(); ?>

    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label"><?php _e('Number of tags','mywords'); ?></label>
            <div class="controls">
                <input type="text" size="5" name="options[0]" value="<?php echo $options[0]; ?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="label-control"><?php _e('Size increment per post','mywords'); ?></label>
            <div class="controls">
                <input type="text" size="5" name="options[1]" value="<?php echo $options[1]; ?>" />
            </div>
        </div>
    </div>

    <?php
    $form = ob_get_clean();
    
    return $form;
    
}
