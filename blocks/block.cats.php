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

function mywordsBlockCats($options)
{
    global $xoopsModuleConfig, $xoopsModule;
    
    $categos = array();
    MWFunctions::categos_list($categos, 0, 0, $options[0]);
    $block = array();
    $mc = $xoopsModule && $xoopsModule->getVar('dirname')=='mywords' ? $xoopsModuleConfig : RMSettings::module_settings('mywords');
    foreach ($categos as $k) {
        $ret = array();
        $cat = new MWCategory();
        $cat->assignVars($k);
        $cat->loadPosts();
        $ret['id'] = $cat->id();
        $ret['name'] = $cat->getVar('name');
        if (isset($options[1]) && $options[1]) {
            $ret['posts'] = $cat->getVar('posts');
        }
        $ret['indent'] = $k['indent'] * 2;
        $ret['link'] = $cat->permalink();
        $block['categos'][] = $ret;
    }
    
    RMTemplate::get()->add_style('mwblocks.css', 'mywords');
    
    return $block;
}

function mywordsBlockCatsEdit($options)
{
    ob_start(); ?>

    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label">
                <?php echo __('Show subcategories:', 'mywords'); ?>
            </label>
            <div class="controls">
                <label class="radio inline">
                    <input type="radio" name="options[0]" value="1"'<?php echo $options[0] ? ' checked="checked"' : ''; ?>>
                    <?php _e('Yes', 'mywords'); ?>
                </label>
                <label class="radio inline">
                    <input type="radio" name="options[0]" value="0"'<?php echo $options[0]<=0 ? ' checked="checked"' : ''; ?>>
                    <?php _e('No', 'mywords'); ?>
                </label>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">
                <?php echo __('Show posts number:', 'mywords'); ?>
            </label>
            <div class="controls">
                <label class="radio inline">
                    <input type="radio" name="options[1]" value="1"'<?php echo $options[1] ? ' checked="checked"' : ''; ?>>
                    <?php _e('Yes', 'mywords'); ?>
                </label>
                <label class="radio inline">
                    <input type="radio" name="options[1]" value="0"'<?php echo $options[1]<=0 ? ' checked="checked"' : ''; ?>>
                    <?php _e('No', 'mywords'); ?>
                </label>
            </div>
        </div>
    </div>

    <?php
    $form = ob_get_clean();

    return $form;
}
