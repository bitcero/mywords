<h1 class="cu-section-title mw_titles"><span style="background-position: -64px -32px;">&nbsp;</span><?php _e('Trackbacks','mywords'); ?></h1>
<div class="descriptions">
    <?php _e('A trackback is one of three types of linkbacks, methods for Web authors to request notification when somebody links to one of their documents.','mywords'); ?>
    <a href="http://en.wikipedia.org/wiki/Trackback" target="_blank"><?php _e('Learn more', 'amin_mywords'); ?></a>
</div>

<form name="formTracks" id="form-list-tracks" method="post" action="trackbacks.php">
<div class="options">
	<?php $nav->display(false); ?>
    <select name="action" id="action-list">
    	<option value=""><?php _e('Bulk actions','mywords'); ?></option>
        <option value="delete"><?php _e('Delete','mywords'); ?></option>
    </select>
	<input type="button" id="apply-button" value="<?php _e('Apply','mywords'); ?>" onclick="$('#form-list-tracks').submit();"/>
</div>
<table class="outer" cellspacing="0">
	<thead>
	<tr align="left">
		<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-tracks").toggleCheckboxes(":not(#checkall)");' /></th>
		<th><?php _e('Title','mywords'); ?></th>
		<th nowrap="nowrap" align="center"><?php _e('Blog name', 'admin_mywords'); ?></th>
		<th><?php _e('Excerpt', 'admin_mywords'); ?></th>
		<th align="center"><?php _e('Date', 'admin_mywords'); ?></th>
		<th align="center"><?php _e('Post', 'admin_mywords'); ?></th>
	</tr>
	</thead>
	<tfoot>
	<tr align="left">
		<th width="20" align="center"><input type="checkbox" id="checkall2" onclick='$("#form-list-tracks").toggleCheckboxes(":not(#checkall2)");' /></th>
		<th><?php _e('Title','mywords'); ?></th>
		<th align="center"><?php _e('Blog name', 'admin_mywords'); ?></th>
		<th><?php _e('Excerpt', 'admin_mywords'); ?></th>
		<th align="center"><?php _e('Date', 'admin_mywords'); ?></th>
		<th align="center"><?php _e('Post', 'admin_mywords'); ?></th>
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
			<span class="rmc_options">
				<a href="javascript:;" onclick="delete_trackback(<?php echo $tb->id(); ?>);"><?php _e('Delete', 'admin_mywords'); ?></a>
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
<div class="options">
	<?php $nav->display(false); ?>
    <select name="actionb" id="action-listb">
    	<option value=""><?php _e('Bulk actions','mywords'); ?></option>
        <option value="delete"><?php _e('Delete','mywords'); ?></option>
    </select>
	<input type="button" id="apply-button" value="<?php _e('Apply','mywords'); ?>" onclick="$('#form-list-tracks').submit();"/>
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>