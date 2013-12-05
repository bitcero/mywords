<div id="mw-messages-post" class="mw_messages_post">

</div>
<h1 class="mw_submit_title"><?php $edit ? _e('Edit Post','mywords') : _e('Create Post','mywords'); ?></h1>
<form name="mwposts" id="mw-form-posts" action="posts.php" method="post">
<label class="error" for ="post-title" style="display: none;"><?php _e('You must specify the title for this post!','mywords'); ?></label>
<input type="text" name="title" id="post-title" placeholder="<?php _e('Post title here...','mywords'); ?>" class="mw_biginput required" value="<?php echo $edit ? $post->getVar('title','e') : ''; ?>" />
<div class="mw_permacont <?php if(!$edit): ?>mw_permainfo<?php endif; ?>" id="mw-perma-link">
	<?php if($edit): ?>
		<strong><?php _e('Permalink:','mywords'); ?></strong> <?php echo str_replace($post->getVar('shortname'), '<span id="shortname">'.$post->getVar('shortname').'</span>', $post->permalink()); ?>
	<?php else: ?>
		<?php _e('This post has not been saved. Remember to save it before leave this page.','mywords'); ?>
	<?php endif; ?>
</div>
<?php echo $editor->render(); ?>
<br />
<?php if($allowed_tracks): ?>
<table class="outer" cellspacing="0">
	<tr><th><?php _e('Send Trackbacks','mywords'); ?></th></tr>
	<tr><td class="mw_bcontent">
		<?php _e('Send trackbacks to:','mywords'); ?>
		<input type="text" name="trackbacks" id="post-trackbacks" class="mw_large" value="<?php echo $edit && $post->getVar('toping') ? implode(' ', $post->getVar('toping')) : ''; ?>" />
		(<?php _e('Separate multiple URLs with spaces','mywords'); ?>)
        <?php if($edit): ?>
        <br /><br />
		<strong><?php _e('Pinged:','mywords'); ?></strong><br />
		<small><?php $pinged = $post->getVar('pinged'); echo implode("<br />", $pinged); ?></small>
        <?php endif; ?>
	</td></tr>
</table>
<br />
<?php endif; ?>
<table cellsapcing="0">
	<tr><th><?php _e('Custom Fields','mywords'); ?></th></tr>
	<tr><td class="mw_bcontent">
		<table id="metas-container" class="outer<?php echo !$edit || (!isset($post) && !$post->fields()) ? ' mw_hidden' : ''; ?>" cellspacing="0" width="100%" />
			<tr class="head">
				<td width="30%"><?php _e('Name','mywords'); ?></td>
				<td><?php _e('Value','mywords'); ?></td>
			</tr>
			<?php if($edit || (isset($post) && $post->fields())): ?>
			<?php foreach($post->get_meta('',true) as $field): ?>
				<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
					<td valign="top"><input type="text" name="meta[<?php echo $field->id(); ?>][key]" id="meta-key-<?php echo $field->id(); ?>" value="<?php echo $field->getVar('name'); ?>" class="mw_large" /></td>
					<td><textarea name="meta[<?php echo $field->id(); ?>][value]" id="meta[<?php echo $field->id(); ?>][value]" class="mw_large"><?php echo $field->getVar('value','e'); ?></textarea></td>
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
					<select name="meta_name_sel" class="mw_large" id="meta-name-sel">
						<option value="" selected="selected"><?php _e('- Select -','mywords'); ?></option>
						<?php foreach ($meta_names as $name): ?>
						<option value="<?php echo $name; ?>"><?php echo $name; ?></option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="meta_name" id="meta-name" value="" class="mw_large" style="display: none; width: 95%;" />
					<a href="javascript:;" class="mw_show_metaname"><?php _e('Enter New','mywords'); ?></a>
					<a href="javascript:;" class="mw_hide_metaname" style="display: none;"><?php _e('Cancel','mywords'); ?></a>
					<?php else: ?>
					<input type="text" name="meta_name" id="meta-name" value="" class="mw_large" style="width: 95%;" />
					<?php endif; ?>
				</td>
				<td valign="top">
					<label class="error" style="display: none;" id="error-metavalue">Please provide a value for this meta</label>
					<textarea name="meta_value" id="meta-value" class="mw_large"></textarea>
				</td>
			</tr>
			<tr class="odd">
				<td colspan="2">
					<input type="button" id="mw-addmeta" value="<?php _e('Add custom field','mywords'); ?>" />
				</td>
			</tr>
		</table>
		<label><?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','mywords'); ?></label>
	</td></tr>
</table>
<br />
<?php if($allowed_tracks): ?>
<table class="outer" cellspacing="0">
    <tr><th><?php _e('Comments and Trackbacks','mywords'); ?></th></tr>
    <tr><td class="mw_bcontent">
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
    </td></tr>
</table>
<br />
<?php endif; ?>
<?php RMEvents::get()->run_event('mywords.posts.form.center.widgets', isset($post) ? $post : null); ?>
<input type="hidden" name="XOOPS_TOKEN_REQUEST" id="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
<input type="hidden" name="op" id="mw-op" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
<?php if($edit): ?>
<input type="hidden" name="id" id="mw-id" value="<?php echo $post->id(); ?>" />
<?php endif; ?>
</form>
<table class="outer" cellspacing="0">
<?php 
include 'widgets/widget_tags.php'; 
$w = mw_widget_addtags();
?>
	<tr><th><?php echo $w['title']; ?></th></tr>
	<tr class="even"><td><?php echo $w['content']; ?></td></tr>
</table>
			<br />
<table cellpadding="2" cellspacing="4" width="100%">
	<tr valign="top">
		<td style="padding: 0 4px 0 0;">
			<table class="outer" cellspacing="0">
			<?php 
			include 'widgets/widget_publish.php'; 
			$w = mw_widget_publish();
			?>
			<tr><th><?php echo $w['title']; ?></th></tr>
			<tr><td><?php echo $w['content']; ?></td></tr>
			</table>
		</td>
		<td style="padding: 0 0 0 4px;">
			<table class="outer" cellspacing="0">
			<?php 
			include 'widgets/widget_categories.php'; 
			$w = mw_widget_categories();
			?>
			<tr><th><?php echo $w['title']; ?></th></tr>
			<tr><td><?php echo $w['content']; ?></td></tr>
			</table>
		</td>
	</tr>
</table>
<?php if($edit && $post->getVar('toping')): ?>
<iframe src="<?php echo XOOPS_URL; ?>/modules/mywords/ping.php?post=<?php echo $post->id(); ?>" style="display: none; visibility: hidden; width: 0; height: 0;"></iframe>
<?php endif; ?>