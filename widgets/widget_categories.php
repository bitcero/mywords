<?php
// $Id: widget_categories.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Categories widget
*/
function mw_widget_categories(){
	global $xoopsUser, $allowed_cats;
	
	$widget['title'] = __('Categories','admin_mywords');
	$widget['icon'] = '';
    
    $id = rmc_server_var($_REQUEST,'id',0);
    $postcat = array();
    $edit = false;
    if ($id>0){
        $post = new MWPost($id);
        if ($post->isNew()){
            unset($post);
            $postcat = array();
        } else {
            $edit = true;
            $postcat = $post->get_categos(true);
        }
    }
    
	ob_start();
?>
<div class="rmc_widget_content_reduced">
<form id="mw-post-categos-form">
<div class="w_categories" id="w-categos-container">
<?php
$categories = array();
MWFunctions::categos_list($categories);
foreach ($categories as $catego){
?>
<label class="cat_label" style="padding-left: <?php echo $catego['indent']*10; ?>px;"><input type="checkbox" name="categories[]" id="categories[]" value="<?php echo $catego['id_cat']; ?>"<?php echo in_array($catego['id_cat'], $postcat) ? ' checked="checked"' : '' ?> /> <?php echo $catego['name']; ?></label>
<?php
}
?>
</div>
<?php if($xoopsUser->isAdmin() || $allowed_cats): ?>
<div class="w_catnew_container">
    <a href="javascript:;" id="a-show-new"><strong><?php _e('+ Add Categories','admin_mywords'); ?></strong></a>
    <div id="w-catnew-form">
    	<label class="error" style="display: none;" for="w-name"><?php _e('Please provide a name','admin_mywords'); ?></label>
    	<input type="text" name="name" id="w-name" value="" class="required" />
    	<select name="parent" id="w-parent">
    		<option value="0"><?php _e('Parent category','admin_mywords'); ?></option>
    		<?php foreach ($categories as $catego): ?>
    		<option value="<?php _e($catego['id_cat']); ?>"><?php _e($catego['name']); ?></option>
    		<?php endforeach; ?>
    	</select>
    	<input type="button" id="create-new-cat" value="<?php _e('Add','admin_mywords'); ?>" />
    	<a href="javascript:;"><?php _e('Cancel','admin_mywords'); ?></a>
    </div>
</div>
<?php endif; ?>
</form>
</div>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}