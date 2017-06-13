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

include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwtag.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';

function myWordsBlockTags($options){
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT * FROM ".$db->prefix("mod_mywords_tags")." ORDER BY RAND() LIMIT 0,$options[0]";
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
