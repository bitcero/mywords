<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System for XOOPS
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Provides a widget to specify the default image for posts
 */
function mywords_widget_post_type( $post = null ){
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $rm_config;

    $widget = array();
    $widget['title'] = __('Post type','mywords');

    ob_start(); ?>
    <form name="frmformat" id="frm-post-type" method="post">
        <div class="form-group mywords-post-types">
            <div class="radio">
                <label>
                    <input type="radio" value="post" name="format"<?php echo !isset($post) || $post->getVar('format')=='post' ? ' checked' : ''; ?>>
                    <span class="fa fa-thumb-tack"></span>
                    <?php _e('Standard', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="video" name="format"<?php echo isset($post) && $post->getVar('format')=='video' ? ' checked' : ''; ?>>
                    <span class="fa fa-video-camera"></span>
                    <?php _e('Video', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="gallery" name="format"<?php echo isset($post) && $post->getVar('format')=='gallery' ? ' checked' : ''; ?>>
                    <span class="fa fa-camera"></span>
                    <?php _e('Gallery', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="quote" name="format"<?php echo isset($post) && $post->getVar('format')=='quote' ? ' checked' : ''; ?>>
                    <span class="fa fa-quote-left"></span>
                    <?php _e('Quote', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="mini" name="format"<?php echo isset($post) && $post->getVar('format')=='mini' ? ' checked' : ''; ?>>
                    <span class="fa fa-file-text-o"></span>
                    <?php _e('Mini post', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="image" name="format"<?php echo isset($post) && $post->getVar('format')=='image' ? ' checked' : ''; ?>>
                    <span class="fa fa-picture-o"></span>
                    <?php _e('Image', 'mywords'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" value="link" name="format"<?php echo isset($post) && $post->getVar('format')=='link' ? ' checked' : ''; ?>>
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
            $types = array();
            $types = RMEvents::get()->run_event( 'mywords.post.types', $types, $post );
            foreach ( $types as $type ):
            ?>
                <div class="radio">
                    <label>
                        <input type="radio" value="<?php echo $type->id; ?>" name="format"<?php echo isset($post) && $post->getVar('format')==$type->id ? ' checked' : ''; ?>>
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