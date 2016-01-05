<h1 class="cu-section-title mw_titles"><span style="background-position: -64px -32px;">&nbsp;</span><?php _e('Trackbacks','mywords'); ?></h1>
<div class="help-block">
    <?php _e('A trackback is one of three types of linkbacks, methods for Web authors to request notification when somebody links to one of their documents.','mywords'); ?>
    <strong><a href="http://en.wikipedia.org/wiki/Trackback" target="_blank"><?php _e('Learn more', 'mywords'); ?></a></strong>.
</div>

<form name="formTracks" id="form-list-tracks" method="post" action="trackbacks.php">
<div class="cu-bulk-actions">
	<?php $nav->display(false); ?>
    <select name="action" id="action-list" class="form-control">
    	<option value=""><?php _e('Bulk actions','mywords'); ?></option>
        <option value="delete"><?php _e('Delete','mywords'); ?></option>
    </select>
	<button type="button" id="apply-button" class="btn btn-default" onclick="$('#form-list-tracks').submit();"><?php _e('Apply','mywords'); ?></button>
</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php _e('Received trackbacks', 'mywords'); ?></h3>
        </div>
        <div class="table-responsive">
            <table class="outer" cellspacing="0">
                <thead>
                <tr align="left">
                    <th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-tracks").toggleCheckboxes(":not(#checkall)");' /></th>
                    <th><?php _e('Title','mywords'); ?></th>
                    <th nowrap="nowrap" align="center"><?php _e('Blog name', 'mywords'); ?></th>
                    <th><?php _e('Excerpt', 'mywords'); ?></th>
                    <th align="center"><?php _e('Date', 'mywords'); ?></th>
                    <th align="center"><?php _e('Post', 'mywords'); ?></th>
                </tr>
                </thead>
                <tfoot>
                <tr align="left">
                    <th width="20" align="center"><input type="checkbox" id="checkall2" onclick='$("#form-list-tracks").toggleCheckboxes(":not(#checkall2)");' /></th>
                    <th><?php _e('Title','mywords'); ?></th>
                    <th align="center"><?php _e('Blog name', 'mywords'); ?></th>
                    <th><?php _e('Excerpt', 'mywords'); ?></th>
                    <th align="center"><?php _e('Date', 'mywords'); ?></th>
                    <th align="center"><?php _e('Post', 'mywords'); ?></th>
                </tr>
                </tfoot>
                <tbody>
                <?php if(empty($trackbacks)): ?>
                    <tr class="even error" align="center">
                        <td colspan="6"><?php _e('There are not trackbacks yet','mywords'); ?></td>
                    </tr>
                <?php endif; ?>
                <?php foreach($trackbacks as $trac): ?>
                    <tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
                        <?php extract($trac); ?>
                        <td align="center"><input type="checkbox" name="tbs[]" id="tb-<?php echo $tb->id(); ?>" value="<?php echo $tb->id(); ?>" /></td>
                        <td nowrap="nowrap">
                            <strong><?php echo $tb->getVar('title'); ?></strong>
			<span class="cu-item-options">
				<a href="javascript:;" onclick="delete_trackback(<?php echo $tb->id(); ?>);"><?php _e('Delete', 'mywords'); ?></a>
			</span>
                        </td>
                        <td align="center" nowrap="nowrap"><a href="<?php echo $tb->getVar('url'); ?>" target="_blank"><?php echo $tb->getVar('blog_name'); ?></a></td>
                        <td><?php echo $tb->getVar('excerpt'); ?></td>
                        <td align="center"><?php echo formatTimestamp($tb->getVar('date'), 'l'); ?></td>
                        <td align="center"><?php if($post['title']==''): ?><?php _e('Unknow','mywords'); ?><?php else: ?><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a><?php endif; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<div class="cu-bulk-actions">
	<?php $nav->display(false); ?>
    <select name="actionb" id="action-listb" class="form-control">
    	<option value=""><?php _e('Bulk actions','mywords'); ?></option>
        <option value="delete"><?php _e('Delete','mywords'); ?></option>
    </select>
	<button type="button" id="apply-button" class="btn btn-default" onclick="$('#form-list-tracks').submit();"><?php _e('Apply','mywords'); ?></button>
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>