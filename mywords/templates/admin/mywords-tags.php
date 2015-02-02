<h1 class="cu-section-title mw_titles"><span style="background-position: -64px 0;">&nbsp;</span><?php _e('Tags','mywords'); ?></h1>

<?php if(isset($show_edit) && $show_edit): ?>
	
	<div class="edit_form">
	<form name="form_edit" id="form-edit" method="post" action="tags.php">
        <div class="form-group">
            <label for="edit-name"><?php _e('Name','mywords'); ?></label>
            <input type="text" name="name" id="edit-name" value="<?php echo $tag->getVar('tag'); ?>" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="edit-short"><?php _e('Short name','mywords'); ?></label>
            <input type="text" name="short" id="edit-short" value="<?php echo $tag->getVar('shortname'); ?>" class="form-control">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php _e('Save Changes','mywords'); ?></button>
            <button type="button" class="btn btn-default" onclick="window.location.href='tags.php';"><?php _e('Cancel','mywords'); ?></button>
        </div>

		<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
		<input type="hidden" name="action" value="saveedit" />
		<input type="hidden" name="id" value="<?php echo $tag->id(); ?>" />
	</form>
	</div>
	
<?php else: ?>
<div class="row">
<div id="tags-form" class="col-md-3 col-lg-3">
    <h3><?php _e('New Tag','mywords'); ?></h3>

	<form name="formTag" id="form-tags" method="post" action="tags.php">
        <div class="form-group">
            <label for="new-name"><?php _e('Tag name','mywords'); ?></label>
            <input type="text" name="name" id="new-name" class="form-control" />
        </div>

        <div class="form-group">
            <label for="new-short"><?php _e('Short name','mywords'); ?></label>
            <input type="text" name="short" id="new-short" class="form-control" />
        </div>

        <div class="form-group">
            <button type="button" id="submit-newtag" class="btn btn-primary"><?php _e('Add Tag','mywords'); ?></button>
            <a href="http://www.xoopsmexico.net/docs/mywords/etiquetas/#add-tags" class="btn btn-info rm_help_button">
                <span class="fa fa-question-circle"></span>
            </a>
        </div>

        <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
        <input type="hidden" name="action" value="new" />
        <input type="hidden" name="page" value="<?php echo $page; ?>" />
	</form>

    <h4><?php _e('More used tags','mywords'); ?></h4>
    <div class="more_used">
        <?php $f = 28/$size; ?>
        <?php foreach($mtags as $tag): ?>
            <a href="tags.php?action=edit&amp;id=<?php echo $tag['id_tag']; ?>&amp;page=<?php echo $page; ?>" title="<?php echo sprintf(__('%u Posts','mywords'), $tag['posts']); ?>" style="font-size: <?php echo $f*$tag['posts']<11 ? 11 : floor($f*$tag['posts']); ?>px"><?php echo $tag['tag']; ?></a>&nbsp;
        <?php endforeach; ?>
    </div>
</div>

<div id="tags-list" class="col-md-9 col-lg-9">
	<form name="form_list_tags" id="form-list-tags" method="post" action="tags.php">
	<div class="cu-bulk-actions">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <select name="action" id="action-list" class="form-control">
                    <option value=""><?php _e('Bulk actions','mywords'); ?></option>
                    <option value="update"><?php _e('Update posts number','mywords'); ?></option>
                    <option value="delete"><?php _e('Delete','mywords'); ?></option>
                </select>
                <button type="button" class="btn btn-default" onclick="submit();"><?php _e('Apply','mywords'); ?></button>
            </div>
            <div class="col-md-6 col-lg-6">
                <?php $nav->display(false); ?>
            </div>
        </div>

	</div>
	<table class="table table-bordered" cellspacing="0">
		<thead>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-tags").toggleCheckboxes(":not(#checkall)");' /></th>
			<th align="left"><?php _e('Name','mywords'); ?></th>
			<th><?php _e('Short name','mywords'); ?></th>
			<th><?php _e('Posts','mywords'); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th align="center"><input type="checkbox" id="checkall2" onclick='$("#form-list-tags").toggleCheckboxes(":not(#checkall2)");' /></th>
			<th align="left"><?php _e('Name','mywords'); ?></th>
			<th><?php _e('Short name','mywords'); ?></th>
			<th><?php _e('Posts','mywords'); ?></th>
		</tr>
		</tfoot>
		<tbody>
		<?php if(empty($tags)): ?>
		<tr class="even">
			<td colspan="4" class="error" align="center"><?php _e('There is not any tag yet!','mywords'); ?></td>
		</tr>
		<?php endif; ?>
		<?php foreach($tags as $tag): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
			<td align="center"><input type="checkbox" name="tags[<?php echo $tag['id_tag']; ?>]" id="tags-<?php echo $tag['id_tag']; ?>" value="<?php echo $tag['id_tag']; ?>" /></td>
			<td>
				<strong><a href="tags.php?action=edit&amp;id=<?php echo $tag['id_tag']; ?>&amp;page=<?php echo $page; ?>"><?php echo $tag['tag']; ?></a></strong>
				<span class="mw_options">
					<a href="tags.php?action=edit&amp;id=<?php echo $tag['id_tag']; ?>&amp;page=<?php echo $page; ?>"><?php _e('Edit','mywords'); ?></a> | 
					<a href="javascript:;" onclick="goto_update(<?php echo $tag['id_tag']; ?>);"><?php _e('Update','mywords'); ?></a> |
					<a class="delete" href="javascript:;" onclick="confirm_andgo(<?php echo $tag['id_tag']; ?>, <?php echo $page; ?>);"><?php _e('Delete','mywords'); ?></a>
				</span>
			</td>
			<td align="center"><?php echo $tag['shortname']; ?></td>
			<td align="center"><?php echo $tag['posts']; ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="cu-bulk-actions">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <select name="action-b" id="action-list-b" class="form-control">
                    <option value=""><?php _e('Bulk actions','mywords'); ?></option>
                    <option value="update"><?php _e('Update posts number','mywords'); ?></option>
                    <option value="delete"><?php _e('Delete','mywords'); ?></option>
                </select>
                <button type="button" class="btn btn-default" onclick="submit();"><?php _e('Apply','mywords'); ?></button>
            </div>
            <div class="col-md-6 col-lg-6">
                <?php $nav->display(false); ?>
            </div>
        </div>

	</div>
	<input type="hidden" name="XOOPS_TOKEN_REQUEST" id="xtoken" value="<?php echo $xoopsSecurity->createToken(); ?>" />
	<input type="hidden" name="page" value="<?php echo $page; ?>" />
	</form>
</div>
</div>
<?php endif; ?>
<div id="mw-dialog" title=''>

</div>