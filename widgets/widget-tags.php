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
 * @param null|mixed $post
 */

/**
 * Widget that show tags selection in a post form
 * @param null $post
 * @return mixed
 */
function mywords_widget_addtags($post = null)
{
    global $xoopsModuleConfig, $xoopsUser, $allowed_tags;

    $widget['title'] = __('Add Tags', 'admin_mywords');
    RMTemplate::get()->add_script(XOOPS_URL . '/modules/mywords/include/js/scripts.php?file=tags.js');
    $widget['icon'] = '';

    $edit = false;
    if (isset($post) && is_a($post, 'MWPost')) {
        if ($post->isNew()) {
            unset($post);
        } else {
            $edit = true;
        }
    }

    ob_start(); ?>
<div class="rmc_widget_content_reduced">
<form id="mw-post-tags-form">
<?php if ($allowed_tags || $xoopsUser->isAdmin()): ?>
<div class="tags_box">
    <div class="input-group">
        <input type="text" name="tagsm" id="tags-m" class="form-control">
        <div class="input-group-btn">
            <button type="button" name="tags-button" id="tags-button" class="btn btn-info"><?php _e('+ Add', 'admin_mywords'); ?></button>
        </div>
    </div>
    <span class="help-block"><em><?php _e('Separate multiple tags with commas', 'admin_mywords'); ?></em></span>
</div>
<?php endif; ?>
<?php $tags = $edit ? $post->tags() : []; ?>
<div id="tags-container">
    <span class="tip_legends" style="<?php echo empty($tags) ? 'display: none;' : ''; ?>">
        <?php _e('Used Tags', 'admin_mywords'); ?>
    </span>
    <?php
    foreach ($tags as $tag): ?>
    <label><input type='checkbox' name='tags[]' checked value='<?php echo $tag['tag']; ?>'><?php echo $tag['tag']; ?></label>
    <?php
    endforeach;
    unset($tags); ?>
</div>
<a href="javascript:;" id="show-used-tags"><?php _e('Choose between most populars tags', 'admin_mywords'); ?></a>
<div id="popular-tags-container" style="display: none;">
    <?php
        $tags = MWFunctions::get_tags('*', '', 'posts DESC', "0,$xoopsModuleConfig[tags_widget_limit]");
    foreach ($tags as $tag):
    ?>
        <a href="javascript:;" id="tag-<?php echo $tag['id_tag']; ?>" class="add_tag" style="font-size: <?php echo MWFunctions::get()->tag_font_size($tag['posts'], 2) ?>em;"><?php echo $tag['tag']; ?></a>
    <?php endforeach; ?>
</div>
</form>
</div>
<?php
    $widget['content'] = ob_get_clean();

    return $widget;
}
