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

/**
* Categories widget
*/
function mywords_widget_categories($post = null)
{
    global $xoopsUser, $allowed_cats;
    
    $widget['title'] = __('Categories', 'admin_mywords');
    $widget['icon'] = '';
    
    $postcat = array();
    $edit = false;
    if (isset($post) && is_a($post, 'MWPost')) {
        if ($post->isNew()) {
            unset($post);
            $postcat = array();
        } else {
            $edit = true;
            $postcat = $post->get_categos(true);
        }
    }
    
    ob_start(); ?>
<div class="rmc_widget_content_reduced">
<form id="mw-post-categos-form">
<div class="w_categories" id="w-categos-container">
<?php
$categories = array();
    MWFunctions::categos_list($categories);
    foreach ($categories as $catego) {
        ?>
<label class="cat_label" style="padding-left: <?php echo $catego['indent']*10; ?>px;"><input type="checkbox" name="categories[]" id="categories[]" value="<?php echo $catego['id_cat']; ?>"<?php echo in_array($catego['id_cat'], $postcat) ? ' checked="checked"' : '' ?> /> <?php echo $catego['name']; ?></label>
<?php
    } ?>
</div>
<?php if ($xoopsUser->isAdmin() || $allowed_cats): ?>
<div class="w_catnew_container">
    <a href="javascript:;" id="a-show-new"><strong><?php _e('+ Add Categories', 'admin_mywords'); ?></strong></a>
    <div id="w-catnew-form">
    	<label class="error" style="display: none;" for="w-name"><?php _e('Please provide a name', 'admin_mywords'); ?></label>
    	<input type="text" name="name" id="w-name" value="" class="form-control" required>
    	<select name="parent" id="w-parent" class="form-control">
    		<option value="0"><?php _e('Parent category', 'admin_mywords'); ?></option>
    		<?php foreach ($categories as $catego): ?>
    		<option value="<?php _e($catego['id_cat']); ?>"><?php _e($catego['name']); ?></option>
    		<?php endforeach; ?>
    	</select>
    	<button type="button" id="create-new-cat" class="btn btn-warning"><?php _e('Add', 'admin_mywords'); ?></button>
    	<a href="#" class="btn btn-link"><?php _e('Cancel', 'admin_mywords'); ?></a>
    </div>
</div>
<?php endif; ?>
</form>
</div>
<?php
    $widget['content'] = ob_get_clean();
    return $widget;
}
