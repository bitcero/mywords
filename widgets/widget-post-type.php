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
 * Provides a widget to specify the default image for posts
 * @param null $post
 * @return array
 */
function mywords_widget_post_type($post = null)
{
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $rm_config;

    $widget = [];
    $widget['title'] = __('Post type', 'mywords');

    ob_start(); ?>
    <form name="frmformat" id="frm-post-type" method="post">
        <div class="form-group mywords-post-types">
            <div class="radio">
                <label>
                    <input type="radio" value="post" name="format"<?php echo !isset($post) || 'post' === $post->getVar('format') ? ' checked' : ''; ?>>
                    <span class="fa fa-thumb-tack"></span>
                    <?php _e('Standard', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="video" name="format"<?php echo isset($post) && 'video' === $post->getVar('format') ? ' checked' : ''; ?>>
                    <span class="fa fa-video-camera"></span>
                    <?php _e('Video', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="gallery" name="format"<?php echo isset($post) && 'gallery' === $post->getVar('format') ? ' checked' : ''; ?>>
                    <span class="fa fa-camera"></span>
                    <?php _e('Gallery', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="quote" name="format"<?php echo isset($post) && 'quote' === $post->getVar('format') ? ' checked' : ''; ?>>
                    <span class="fa fa-quote-left"></span>
                    <?php _e('Quote', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="mini" name="format"<?php echo isset($post) && 'mini' === $post->getVar('format') ? ' checked' : ''; ?>>
                    <span class="fa fa-file-text-o"></span>
                    <?php _e('Mini post', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="image" name="format"<?php echo isset($post) && 'image' === $post->getVar('format') ? ' checked' : ''; ?>>
                    <span class="fa fa-picture-o"></span>
                    <?php _e('Image', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="link" name="format"<?php echo isset($post) && 'link' === $post->getVar('format') ? ' checked' : ''; ?>>
                    <span class="fa fa-link"></span>
                    <?php _e('Link', 'mywords'); ?>
                </label>
            </div>
            <?php
            /**
             * Themes and plugins can extend the list of post types.
             * The post types identifiers must have a max length of 10 chars.
             * Example fo returned value:
             * <code>
             * [
             *      'id'        => 'format',
             *      'caption'   => 'Post type',
             *      'icon'      => 'check-square'
             * ]
             * </code>
             * The returned value must be a stdClass with three previous values.
             * Note that "icon" value must correspond to a FontAwesome class icon.
             * In the example this icon must be converted to fa-check-square.
             */
            $types = [];
    $types = RMEvents::get()->run_event('mywords.post.types', $types, $post);
    foreach ($types as $type):
            ?>
                <div class="radio">
                    <label>
                        <input type="radio" value="<?php echo $type->id; ?>" name="format"<?php echo isset($post) && $post->getVar('format') == $type->id ? ' checked' : ''; ?>>
                        <span class="fa fa-<?php echo $type->icon; ?>"></span>
                        <?php echo $type->caption; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
    <?php
    $widget['content'] = ob_get_clean();

    return $widget;
}
