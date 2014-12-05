<h1 class="cu-section-title mw_titles"><?php $edit ? _e('Edit Post','mywords') : _e('Create Post','mywords'); ?></h1>
<div id="mw-messages-post" class="alert alert-danger mw_messages_post">

</div>
<form name="mwposts" id="mw-form-posts" action="posts.php" method="post">
<label class="error" for ="post-title" style="display: none;"><?php _e('You must specify the title for this post!','mywords'); ?></label>
<input type="text" name="title" id="post-title" class="form-control input-lg" value="<?php echo $edit ? $post->getVar('title','e') : ''; ?>" placeholder="<?php _e('Post Title...', 'mywords'); ?>" />
<div class="mw_permacont <?php if(!$edit): ?>mw_permainfo<?php endif; ?>" id="mw-perma-link">
	<?php if($edit): ?>
		<strong><?php _e('Permalink:','mywords'); ?></strong> <?php echo str_replace($post->getVar('shortname'), '<span id="post-shortname">'.$post->getVar('shortname').'</span>', $post->permalink()); ?>
		<a href="#" onclick="window.open('<?php echo $post->permalink(); ?>', 'preview');" class="btn btn-default btn-mini">
			<?php if($post->getVar('status')=='publish') _e('View Post','mywords'); else _e('Preview Post','mywords'); ?>
		</a>
	<?php else: ?>
		<?php _e('This post has not been saved. Remember to save it before leave this page.','mywords'); ?>
	<?php endif; ?>
</div>
<?php echo $editor->render(); ?>
<br />

    <!-- Basic SEO -->
    <div class="cu-box">
        <div class="box-header">
            <span class="fa fa-caret-up box-handler"></span>
            <h3><?php _e('Post SEO', 'mywords'); ?></h3>
        </div>
        <div class="box-content">

            <div class="form-group">
                <label for="seo-title">
                    <?php _e('Custom Title','mywords'); ?><br>
                </label>
                <small class="help-block"><?php _e('Insert here your own custom title for SEO purposes. This custom title will be used in title tag.','mywords'); ?></small>
                <input type="text" class="form-control" name="seotitle" id="seo-title" value="<?php echo $edit ? $post->getVar('customtitle') : ''; ?>">
            </div>

            <div class="form-group">
                <label for="seo-description"><?php _e('Meta Description','mywords'); ?></label>
                <textarea class="form-control" name="description" id="seo-description" rows="4" cols="45"><?php echo $edit ? $post->getVar('description','e') : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="seo-keywords"><?php _e('Meta Keywords','mywords'); ?></label>
                <input type="text" class="form-control" name="keywords" id="seo-keywords" value="<?php echo $edit ? $post->getVar('keywords','e') : ''; ?>">
            </div>

        </div>
    </div>
    <!-- // End Basic SEO -->


<div class="cu-box">
	<div class="box-header">
        <span class="fa fa-caret-up box-handler"></span>
        <h3><?php _e('Send Trackbacks','mywords'); ?></h3>
	</div>
	<div class="mw_bcontent box-content">
		<div class="form-group">
            <label><?php _e('Send trackbacks to:','mywords'); ?></label>

            <input type="text" name="trackbacks" id="post-trackbacks" class="form-control" value="<?php echo $edit && $post->getVar('toping') ? implode(' ', $post->getVar('toping')) : ''; ?>" />
            <span class="help-block">(<?php _e('Separate multiple URLs with spaces','mywords'); ?>)</span>
            <?php if($edit && $post->getVar('pinged')!=''): ?>
                <br /><br />
                <strong><?php _e('Pinged:','mywords'); ?></strong><br />
                <small><?php $pinged = $post->getVar('pinged'); echo implode("<br />", $pinged); ?></small>
            <?php endif; ?>
		</div>

	</div>
</div>

<div class="cu-box">
	<div class="box-header">
        <span class="fa fa-caret-up box-handler"></span>
        <h3><?php _e('Custom Fields','mywords'); ?></h3>
    </div>
	<div class="mw_bcontent box-content">
		<table id="metas-container" class="outer<?php echo !$edit || (!isset($post) && !$post->fields()) ? ' mw_hidden' : ''; ?>" cellspacing="0" width="100%" />
			<tr class="head">
				<td width="30%"><?php _e('Name','mywords'); ?></td>
				<td><?php _e('Value','mywords'); ?></td>
			</tr>
			<?php if($edit || (isset($post) && $post->fields())): ?>
			<?php foreach($post->get_meta('',true) as $field): ?>
				<tr class="<?php echo tpl_cycle("even,odd"); ?>">
					<td valign="top"><input type="text" name="meta[<?php echo $field->id(); ?>][key]" id="meta-key-<?php echo $field->id(); ?>" value="<?php echo $field->getVar('name'); ?>" class="form-control" />
                    <a href="#" onclick="remove_meta($(this));"><?php _e('Remove','mywords'); ?></a></td>
					<td><textarea name="meta[<?php echo $field->id(); ?>][value]" id="meta[<?php echo $field->id(); ?>][value]" class="form-control"><?php echo $field->getVar('value','e'); ?></textarea></td>
				</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		</table><br />
		<label><strong><?php _e('Add new field:','mywords'); ?></strong></label>
		<table class="outer" cellspacing="0" />
			<tr class="head" align="center">
				<td width="30%"><?php _e('Name','mywords'); ?></td>
				<td><?php _e('Value','mywords'); ?></td>
			</tr>
			<tr class="even">
				<td valign="top">
					<label class="error" style="display: none;" id="error-metaname">Please, select or specify a new meta name</label>
					<?php if(!empty($meta_names)): ?>
					<select name="meta_name_sel" class="form-control" id="meta-name-sel">
						<option value="" selected="selected"><?php _e('- Select -','mywords'); ?></option>
						<?php foreach ($meta_names as $name): ?>
						<option value="<?php echo $name; ?>"><?php echo $name; ?></option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="meta_name" id="meta-name" value="" class="form-control" style="display: none; width: 95%;" />
					<a href="javascript:;" class="mw_show_metaname"><?php _e('Enter New','mywords'); ?></a>
					<a href="javascript:;" class="mw_hide_metaname" style="display: none;"><?php _e('Cancel','mywords'); ?></a>
					<?php else: ?>
					<input type="text" name="meta_name" id="meta-name" value="" class="form-control" style="width: 95%;" />
					<?php endif; ?>
				</td>
				<td valign="top">
					<label class="error" style="display: none;" id="error-metavalue">Please provide a value for this meta</label>
					<textarea name="meta_value" id="meta-value" class="form-control"></textarea>
				</td>
			</tr>
			<tr class="odd">
				<td colspan="2">
					<input type="button" id="mw-addmeta" value="<?php _e('Add custom field','mywords'); ?>" />
				</td>
			</tr>
		</table>
		<label><?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','mywords'); ?></label>
	</div>
</div>

<div class="cu-box">
    <div class="box-header">
        <span class="fa fa-caret-up box-handler"></span>
        <h3><?php _e('Comments and Trackbacks','mywords'); ?></h3>
    </div>
    <div class="mw_bcontent box-content">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr class="outer">
                <td width="50%">
                    <label><input type="checkbox" name="comstatus" value="1" checked="checked" /> <?php _e('Enable comments for this post','mywords'); ?></label>
                </td>
                <td>
                    <label><input type="checkbox" name="pingstatus" value="1" checked="checked" /> <?php _e('Allow trackbacks for this post','mywords'); ?></label>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php RMEvents::get()->run_event('mywords.posts.form.center.widgets', isset($post) ? $post : null); ?>
<input type="hidden" name="XOOPS_TOKEN_REQUEST" id="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
<input type="hidden" name="op" id="mw-op" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
<?php if($edit): ?>
<input type="hidden" name="id" id="mw-id" value="<?php echo $post->id(); ?>" />
<input type="hidden" name="image" id="mw-image" value="<?php echo $post->getVar('image'); ?>" />
<?php endif; ?>
</form>
<?php if($edit && $post->getVar('toping')): ?>
<iframe src="<?php echo XOOPS_URL; ?>/modules/mywords/ping.php?post=<?php echo $post->id(); ?>" style="display: none; visibility: hidden; width: 0; height: 0;"></iframe>
<?php endif; ?>