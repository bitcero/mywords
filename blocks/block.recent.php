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

include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';

function mywordsBlockRecent($options){
	global $xoopsModuleConfig, $xoopsModule, $xoopsUser;
    
	$mc = RMSettings::module_settings('mywords');
	$db = XoopsDatabaseFactory::getDatabaseConnection();
    $by = '';
    
    switch($options[1]){
        case 'recent':
            $by = 'pubdate';
            break;
        case 'popular':
            $by = "`reads`";
            break;
        case 'comm':
            $by = "`comments`";
            break;
    }
    
    $posts = MWFunctions::get_posts_by_cat($options[5], 0, $options[0], $by, 'DESC');
    
	$block = array();
	foreach ($posts as $post){
		$ret = array();
		$ret['id'] = $post->id();
		$ret['title'] = $post->getVar('title');
		$ret['link'] = $post->permalink();
        // Content
        if ($options[2]){
            $ret['content'] = TextCleaner::getInstance()->truncate($post->content(true), $options[3]);
        }
        // Pubdate
        if ($options[4]){
            $ret['date'] = formatTimestamp($post->getVar('pubdate'), 'c');
        }
        // Show reads
        if ($options[1]=='popular'){
            $ret['hits'] = sprintf(__('%u Reads','mywords'), $post->getVar('reads'));
        } elseif($options[1]=='comm'){
            $ret['comments'] = sprintf(__('%u Comments','mywords'), $post->getVar('comments'));
        }
        $ret['time'] = $post->getVar('pubdate');
        $ret['image'] = RMIMage::get()->load_from_params( $post->image );
		$block['posts'][] = $ret;
        
	}
    
    RMTemplate::get()->add_style('mwblocks.css', 'mywords');
    
	return $block;
}

function mywordsBlockRecentEdit($options){
    
    $options[5] = isset($options[5]) ? $options[5] : 0;

    ob_start();

    $cats = array();
    MWFunctions::categos_list($cats);
    ?>

    <div class="form-horizontal">
        <div class="control-group">

            <label class="control-label"><strong><?php _e('Posts Number:','mywords'); ?></strong></label>
            <div class="controls">
                <input type="text" size="10" value="<?php echo $options[0]; ?>" name="options[0]" />
            </div>

        </div>

        <div class="control-group">

            <label class="control-label"><strong><?php _e('Block type:','mywords'); ?></strong></label>
            <div class="controls">
                <label class="radio">
                    <input type="radio" name="options[1]"<?php echo !isset($options[1]) || $options[1]=='recent' ? ' checked="checked"' : ''; ?> value="recent" />
                    <?php _e('Recent Posts','mywords'); ?>
                </label>
                <label class="radio">
                    <input type="radio" name="options[1]"<?php echo !isset($options[1]) || $options[1]=='popular' ? ' checked="checked"' : ''; ?> value="popular" />
                    <?php _e('Popular Posts','mywords'); ?>
                </label>
                <label class="radio">
                    <input type="radio" name="options[1]"<?php echo !isset($options[1]) || $options[1]=='comm' ? ' checked="checked"' : ''; ?> value="comm" />
                    <?php _e('Most Commented','mywords'); ?>
                </label>
            </div>

        </div>

        <div class="control-group">

            <label class="control-label"><strong><?php _e('Show text:','mywords'); ?></strong></label>
            <div class="controls">
                <label class="radio inline"><input type="radio" name="options[2]" value="1"<?php echo isset($options[2]) && $options[2]==1 ? ' checked="checked"' : ''; ?> /> <?php _e('Yes','mywords'); ?></label>
                <label class="radio inline"><input type="radio" name="options[2]" value="0"<?php echo !isset($options[2]) || $options[2]==0 ? ' checked="checked"' : ''; ?> /> <?php _e('No','mywords'); ?></label>
            </div>

        </div>

        <div class="control-group">

            <label class="control-label"><strong><?php _e('Text length:','mywords'); ?></strong></label>
            <div class="controls">
                <input type="text" size="10" value="<?php echo isset($options[3]) ? $options[3] : 50; ?>" name="options[3]" />
            </div>

        </div>

        <div class="control-group">

            <label class="control-label"><strong><?php _e('Show date:','mywords'); ?></strong></label>
            <div class="controls">
                <label class="radio inline"><input type="radio" name="options[4]" value="1"<?php echo isset($options[4]) && $options[4]==1 ? ' checked="checked"' : ''; ?> /> <?php _e('Yes','mywords'); ?></label>
                <label class="radio inline"><input type="radio" name="options[4]" value="0"<?php echo !isset($options[4]) || $options[4]==0 ? ' checked="checked"' : ''; ?> /> <?php _e('No','mywords'); ?></label>
            </div>

        </div>

        <div class="control-group">

            <label class="control-label"><strong><?php _e('Posts from category:','mywords'); ?></strong></label>
            <div class="controls">
                <select name="options[5]">
                    <option value="0"<?php echo $options[5]==0 ? ' selected="selected"' : ''; ?>><?php _e('Select category...','mywords'); ?></option>';
                    <?php foreach($cats as $cat): ?>
                    <option value="<?php echo $cat['id_cat']; ?>"<?php echo $options[5]==$cat['id_cat'] ? ' selected="selected"' : ''; ?>><?php echo str_repeat("&#8212;", $cat['indent']).$cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>
    </div>

    <?php

    $form = ob_get_clean();
    
    return $form;
    
}
