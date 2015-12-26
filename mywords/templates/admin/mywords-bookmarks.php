<h1 class="cu-section-title mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Social Sites','mywords'); ?></h1>

<?php if(isset($show_edit) && $show_edit): ?>

	<div class="edit_form">
	<form name="form_edit" id="form-new-bookmark" method="post" action="bookmarks.php">
        <div class="form-group">
            <label for="new-title"><?php _e('Title','mywords'); ?></label>
            <input type="text" name="title" id="new-title" value="<?php echo $book->getVar('title'); ?>" class="form-control">
        </div>

        <div class="form-group">
            <label for="new-alt">*<?php _e('Short description:','mywords'); ?></label>
            <input type="text" name="alt" id="new-alt" value="<?php echo $book->getVar('alt'); ?>" class="form-control">
        </div>

		<div class="form-group">
            <label for="new-url">*<?php _e('Formated URL:','mywords'); ?></label>
            <input type="text" name="url" id="new-url" value="<?php echo $book->getVar('url'); ?>" class="form-control">
		</div>

        <div class="form-group">
            <label for="edit-icon"><?php _e('Icon:','mywords'); ?></label>
            <div class="icons_sel" id="edit-icon">
                <?php foreach($icons as $id => $icon): ?>
                    <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['name']; ?>" id="icon-<?php echo $id; ?>" title="<?php echo $icon['name']; ?>"<?php echo $book->getVar('icon')==$icon['name']?' class="selected"' : ''; ?> />
                <?php endforeach; ?>
                <input type="hidden" name="icon" id="new-icon-h" value="<?php echo $book->getVAr('icon'); ?>" />
            </div>
            <span class="help-block"><?php echo sprintf(__('You can create new icons by uploading files to %s folder.','mywords'), '<code>'.XOOPS_ROOT_PATH.'/modules/mywords/images</code>'); ?>
        </div>

		<div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg"><?php _e('Save Changes','mywords'); ?></button>
            <button type="button" class="btn btn-default btn-lg" onclick="history.go(-1);"><?php _e('Cancel','mywords'); ?></button>
        </div>
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
        <input type="hidden" name="action" value="saveedit" />
        <input type="hidden" name="id" value="<?php echo $book->id(); ?>" />
	</form>
	</div>

<?php else: ?>

	<div class="alert alert-info fade in">
        <span class="close" data-dismiss="alert">&times;</span>
		<?php _e('Social Sites allows to publish directly, on these sites, links and content from MyWords posts.','mywords'); ?>
		<?php _e('You can add new sites easily by configuring parameters for each site (eg. Twitter, Facebook, etc.), then your visitors can recommend posts to other users from these social networks.','armin_mywords'); ?></em>
	</div>
	<div class="row">
        <div class="col-md-4 col-lg-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php _e('Create Site','mywords'); ?></h3>
                </div>
                <div class="panel-body">
                    <form name="form_new" id="form-new-bookmark" method="post" action="bookmarks.php">
                        <div class="form-group">
                            <label for="new-title">*<?php _e('Site title:','mywords'); ?></label>
                            <input type="text" name="title" id="new-title" value="" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="new-alt">*<?php _e('Short description:','mywords'); ?></label>
                            <input type="text" name="alt" id="new-alt" value="" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="new-url">*<?php _e('Formated URL:','mywords'); ?></label>
                            <input type="text" name="url" id="new-url" value="" class="form-control" required>
                            <small class="help-block"><?php _e('Please, note that the URL can contain parameters {TITLE}, {URL} and {DESC} that will be replaced with their respective values.','mywords'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="new-icon"><?php _e('Icon:','mywords'); ?></label>
                            <div class="icons_sel" id="new-icon">
                                <?php foreach($icons as $id => $icon): ?>
                                    <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['name']; ?>" id="icon-<?php echo $id; ?>" title="<?php echo $icon['name']; ?>" />
                                <?php endforeach; ?>
                                <input type="hidden" name="icon" id="new-icon-h" value="" />
                                <small class="help-block"><?php echo sprintf(__('You can create new icons by uploading files to %s folder.','mywords'), XOOPS_ROOT_PATH.'/modules/mywords/images'); ?></small>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"><?php _e('Create Site','mywords'); ?></button>
                        </div>

                        <?php echo $xoopsSecurity->getTokenHTML(); ?>
                        <input type="hidden" name="action" value="new" />
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-8">
            <form name="frmListB" id="form-list-book" method="post" action="bookmarks.php">
                <div class="cu-bulk-actions">
                    <select name="action" id="action-list" class="form-control">
                        <option value=""><?php _e('Bulk actions','mywords'); ?></option>
                        <option value="activate"><?php _e('Activate','mywords'); ?></option>
                        <option value="deactivate"><?php _e('Deactivate','mywords'); ?></option>
                        <option value="delete"><?php _e('Delete','mywords'); ?></option>
                    </select>
                    <button type="button" class="btn btn-default" onclick="submit();"><?php _e('Apply','mywords'); ?></button>
                </div>

                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php _e('Existing sites', 'mywords'); ?></h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" cellspacing="0">
                            <thead>
                            <tr>
                                <th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-book").toggleCheckboxes(":not(#checkall)");' /></th>
                                <th>&nbsp;</th>
                                <th align="left"><?php _e('Title','mywords'); ?></th>
                                <th align="left"><?php _e('Description','mywords'); ?></th>
                                <th><?php _e('URL','mywords'); ?></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-book").toggleCheckboxes(":not(#checkall)");' /></th>
                                <th>&nbsp;</th>
                                <th align="left"><?php _e('Title','mywords'); ?></th>
                                <th align="left"><?php _e('Description','mywords'); ?></th>
                                <th><?php _e('URL','mywords'); ?></th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php if(count($bookmarks)<=0): ?>
                                <tr class="even">
                                    <td colspan="5"><?php _e('There are not social sites registered yet!','mywords'); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach($bookmarks as $book): ?>
                                <tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
                                    <td align="center"><input type="checkbox" name="books[]" id="book-<?php echo $book['id']; ?>" value="<?php echo $book['id']; ?>" /></td>
                                    <td align="center"><img src="../images/icons/<?php echo $book['icon']; ?>" alt="<?php echo $book['icon']; ?>" title="<?php echo $book['icon']; ?>" /></td>
                                    <td>
                                        <strong><?php echo $book['name']; ?></strong>
                                        <?php echo $book['active']?'':'['.__('Inactive','mywords').']'; ?>
                                        <span class="mw_options">
                            <a href="bookmarks.php?id=<?php echo $book['id']; ?>&amp;action=edit"><?php _e('Edit','mywords'); ?></a> |
                                            <?php if($book['active']): ?>
                                                <a href="javascript:;" onclick="goto_activate(<?php echo $book['id']; ?>,false);"><?php _e('Desactivar','mywords'); ?></a> |
                                            <?php else: ?>
                                                <a href="javascript:;" onclick="goto_activate(<?php echo $book['id']; ?>,true);"><?php _e('Activar','mywords'); ?></a> |
                                            <?php endif; ?>
                                            <a href="javascript:;" onclick="goto_delete(<?php echo $book['id']; ?>);"><?php _e('Delete','mywords'); ?></a>
                        </span>
                                    </td>
                                    <td><?php echo $book['desc']; ?></td>
                                    <td class="burl"><small><?php echo $book['url']; ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="cu-bulk-actions">
                    <select name="actionb" id="action-list-b" class="form-control">
                        <option value=""><?php _e('Bulk actions','mywords'); ?></option>
                        <option value="activate"><?php _e('Activate','mywords'); ?></option>
                        <option value="deactivate"><?php _e('Deactivate','mywords'); ?></option>
                        <option value="delete"><?php _e('Delete','mywords'); ?></option>
                    </select>
                    <button type="button" class="btn btn-default" onclick="submit();"><?php _e('Apply','mywords'); ?></button>
                </div>
                <?php echo $xoopsSecurity->getTokenHTML(); ?>
            </form>
        </div>
	</div>
<?php endif; ?>