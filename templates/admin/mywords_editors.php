<h1 class="cu-section-title mw_titles"><span style="background-position: -128px 0;">&nbsp;</span><?php _e('Editors','mywords'); ?></h1>
<?php if(isset($show_edit) && $show_edit): ?>

    <div class="edit_form">
    <form name="form_edit" id="form-edit" method="post" action="editors.php">
        <label for="name"><?php _e('Name','mywords'); ?></label>
        <input type="text" name="name" id="name" value="<?php echo $editor->getVar('name'); ?>" />
        <br clear="all" />
        <label for="short"><?php _e('Short name','mywords'); ?></label>
        <input type="text" name="short" id="short" value="<?php echo $editor->getVar('shortname'); ?>" />
        <br clear="all" />
        <label for="bio"><?php _e('Biography:','mywords'); ?></label>
        <textarea name="bio" id="bio" style="height: 120px;"><?php echo $editor->getVar('bio','e'); ?></textarea>
        <br clear="all" />
        <label for="new_user"><?php _e('Registered user:','mywords'); ?></label>
        <?php 
        $ele = new RMFormUser('', 'new_user', false, array($editor->getVar('uid')));
        echo $ele->render();
        ?>
        <br clear="all" />
        <label for="perms"><?php _e('Permissions:','mywords'); ?></label>
        <div class="permissions" style="margin-left: 160px;">
            <label><input type="checkbox" name="perms[]" value="tags"<?php echo in_array("tags", $editor->getVar('privileges')) ? ' checked="checked"' : ''; ?> /> <?php _e('Create tags','mywords'); ?></label>
            <label><input type="checkbox" name="perms[]" value="cats"<?php echo in_array("cats", $editor->getVar('privileges')) ? ' checked="checked"' : ''; ?> /> <?php _e('Create categories','mywords'); ?></label>
            <label><input type="checkbox" name="perms[]" value="tracks"<?php echo in_array("tracks", $editor->getVar('privileges')) ? ' checked="checked"' : ''; ?> /> <?php _e('Send trackbacks','mywords'); ?></label>
            <label><input type="checkbox" name="perms[]" value="comms"<?php echo in_array("comms", $editor->getVar('privileges')) ? ' checked="checked"' : ''; ?> /> <?php _e('Manage discussions','mywords'); ?></label>
        </div>
        <br clear="all" />
        <div style="padding-left: 160px;">
        <input type="submit" value="<?php _e('Save Changes','mywords'); ?>" />
        <input type="button" value="<?php _e('Cancel','mywords'); ?>" onclick="history.go(-1);" />
        </div>
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
        <input type="hidden" name="action" value="saveedit" />
        <input type="hidden" name="id" value="<?php echo $editor->id(); ?>" />
    </form>
    </div>

<?php else: ?>

<div class="descriptions">
	<?php _e('Editors are people that can send and publish content without admin approval. You can create new editors and assign individuals permissions for them.','mywords'); ?>
	<em><?php _e('All webmasters are allowed as editors with all privileges.','mywords'); ?></em>
</div>
<div class="form_options">
	<form name="form_new" id="form-new-editor" method="post" action="editors.php">
	<h3 class="form_titles"><?php _e('Register Editor','mywords'); ?></h3>
	<label for="new-name"><?php _e('Display name:','mywords'); ?></label>
	<input type="text" name="name" id="new-name" value="" class="required" />
	<label for="new-bio"><?php _e('Biography:','mywords'); ?></label>
	<textarea name="bio" id="new-bio" style="height: 120px;"></textarea>
	<label for="new-user"><?php _e('Registered user:','mywords'); ?></label>
	<?php 
	$ele = new RMFormUser('', 'new_user');
	echo $ele->render();
	?>
	<label for="new-perm"><?php _e('Privilieges:','mywords'); ?></label>
	<div class="permissions">
		<label><input type="checkbox" name="perms[]" value="tags" checked="checked" /> <?php _e('Create tags','mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="cats" checked="checked" /> <?php _e('Create categories','mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="tracks" checked="checked" /> <?php _e('Send trackbacks','mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="comms" checked="checked" /> <?php _e('Manage discussions','mywords'); ?></label>
	</div>
    <br clear="all" />
    <input type="submit" value="<?php _e('Create Editor','mywords'); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="action" value="new" />
	</form>
</div>

<div id="editors-list">
	<form id="form-list-editors" name="from_editors" method="post" action="editors.php">
    <div class="options">
        <?php $nav->display(false); ?>
        <select name="action" id="action-list">
            <option value=""><?php _e('Bulk actions','mywords'); ?></option>
            <option value="activate"><?php _e('Activate','mywords'); ?></option>
            <option value="deactivate"><?php _e('Deactivate','mywords'); ?></option>
            <option value="delete"><?php _e('Delete','mywords'); ?></option>
        </select>
        <input type="button" id="apply-button" value="<?php _e('Apply','mywords'); ?>" onclick="submit();"/>
    </div>
	<table class="outer" cellspacing="0">
		<thead>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-editors").toggleCheckboxes(":not(#checkall)");' /></th>
			<th align="left"><?php _e('Display name','mywords'); ?></th>
			<th><?php _e('User','mywords'); ?></th>
			<th><?php _e('Permissions','mywords'); ?></th>
			<th><?php _e('Posts','mywords'); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall2" onclick='$("#form-list-editors").toggleCheckboxes(":not(#checkall2)");' /></th>
			<th align="left"><?php _e('Display name','mywords'); ?></th>
			<th><?php _e('User','mywords'); ?></th>
			<th><?php _e('Permissions','mywords'); ?></th>
			<th><?php _e('Posts','mywords'); ?></th>
		</tr>
		</tfoot>
		<tbody>
		<?php if(!$tpl->get_var('editors')): ?>
		<tr class="even">
			<td colspan="5" align="center"><?php _e('There are not editors registered yet.','mywords'); ?></td>
		</tr>
		<?php endif; ?>
		<?php foreach($tpl->get_var('editors') as $editor): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
			<td><input type="checkbox" name="editors[]" id="editor-<?php echo $editor->id(); ?>" value="<?php echo $editor->id(); ?>" /></td>
			<td>
                <strong><?php echo $editor->getVar('name'); ?></strong><?php echo $editor->getVar('active')?'':' ['.__('Inactive','mywords').']'; ?>
                <span class="mw_options">
                    <a href="editors.php?id=<?php echo $editor->id(); ?>&amp;action=edit&amp;page=<?php echo $page; ?>"><?php _e('Edit','mywords'); ?></a> |
                    <?php if($editor->getVar('active')): ?>
                    <a href="javascript:;" onclick="goto_activate(<?php echo $editor->id(); ?>,<?php echo $page; ?>,false);"><?php _e('Desactivar','mywords'); ?></a> |
                    <?php else: ?>
                    <a href="javascript:;" onclick="goto_activate(<?php echo $editor->id(); ?>,<?php echo $page; ?>,true);"><?php _e('Activar','mywords'); ?></a> |
                    <?php endif; ?>
                    <a href="javascript:;" onclick="goto_delete(<?php echo $editor->id(); ?>,<?php echo $page; ?>);"><?php _e('Delete','mywords'); ?></a>
                </span>
            </td>
            <td align="center"><a href="<?php echo XOOPS_URL; ?>/modules/system/admin.php?fct=users&amp;op=modifyUser&amp;uid=<?php echo $editor->getVar('uid'); ?>" title="<?php _e('Edit user','mywords'); ?>"><?php echo $editor->data('uname'); ?></a></td>
            <td align="center">
            <?php 
                foreach($editor->getVar('privileges') as $perm): 
                
                switch($perm){
                    case 'tags':?>
                        <img src="../images/tag16.png" title="<?php _e('Create tags','mywords'); ?>" alt="" />
            <?php
                        break;
                    case 'cats': ?>
                        <img src="../images/categos.png" title="<?php _e('Create categories','mywords'); ?>" alt="" />
            <?php
                        break;
                    case 'tracks': ?>
                        <img src="../images/traks.png" title="<?php _e('Send trackbacks and pings','mywords'); ?>" alt="" />
            <?php
                        break;
                    case 'comms': ?>
                        <img src="../images/comment.png" title="<?php _e('Enable/disable discussions','mywords'); ?>" alt="" />
            <?php
                        break;
                }
                
                endforeach; ?>
            </td>
            <td align="center"><?php echo $editor->posts(); ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
    <div class="options">
        <?php $nav->display(false); ?>
        <select name="action" id="action-list">
            <option value=""><?php _e('Bulk actions','mywords'); ?></option>
            <option value="activate"><?php _e('Activate','mywords'); ?></option>
            <option value="deactivate"><?php _e('Deactivate','mywords'); ?></option>
            <option value="delete"><?php _e('Delete','mywords'); ?></option>
        </select>
        <input type="button" value="<?php _e('Apply','mywords'); ?>" onclick="submit();"/>
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>

<?php endif; ?>