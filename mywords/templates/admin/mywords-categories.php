<?php RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js'); ?>
<h1 class="cu-section-title mw_titles"><span style="background-position: left 0;">&nbsp;</span><?php _e('Categories','mywords'); ?></h1>
<div class="row">

    <!-- Create form -->
    <div class="col-md-4 col-lg-4">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php _e('Add Category','mywords'); ?></h3>
            </div>

            <div class="panel-body">
                <form name="addcat" id="addcat" method="post" action="categories.php" class="validate form">
                    <div class="form-group">
                        <label for="name"><?php _e('Category Name','mywords'); ?></label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="shortcut"><?php _e('Category Slug','mywords'); ?></label>
                        <input type="text" name="shortcut" id="shortcut" class="form-control" value="<?php echo $shortcut; ?>">
                        <span class="help-block"><em><?php _e('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.','mywords'); ?></em></span>
                    </div>
                    <div class="form-group">
                        <label for="parent"><?php _e('Category Parent','mywords'); ?></label>
                        <select name="parent" id="parent" class="form-control">
                            <option value=""<?php if($parent==''): ?> selected="selected"<?php endif; ?>><?php _e('None','mywords'); ?></option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id_cat']; ?>"<?php echo $parent==$cat['id_cat'] ? 'selected="selected"' : ''; ?>><?php echo preg_replace('!^!m',str_repeat("&#8212;",$cat['indent']),' '.$cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"><em><?php _e('Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.','mywords'); ?></em></span>
                    </div>
                    <div class="form-group">
                        <label for="desc"><?php _e('Category description','mywords'); ?></label>
                        <textarea name="desc" id="desc" class="form-control"><?php echo $desc ?></textarea>
                    </div>

                    <?php
                    // Event to allow plugins to add new options
                    RMEvents::get()->run_event('mywords.newcategory_form', null);
                    ?>

                    <p class="submit">
                        <button type="submit" class="btn btn-primary"><?php _e('Add Category','mywords'); ?></button>
                        <a href="http://www.xoopsmexico.net/docs/bitcero/mywords/categorias/#add-categories" class="btn btn-info" data-action="help"><span class="fa fa-question-circle"></span></a>
                    </p>
                    <input type="hidden" name="op" id="op" value="save" />
                    <?php echo $xoopsSecurity->getTokenHTML(); ?>
                </form>
            </div>
        </div>

    </div>


    <div class="col-md-8 col-lg-8">
        <form name="tblCats" id="tblCats" method="post" action="categories.php">
            <div class="cu-bulk-actions">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <select name="op" id="cat-op" class="form-control">
                            <option value="" selected="selected"><?php _e('Bulk actions','mywords'); ?></option>
                            <option value="delete"><?php _e('Delete','mywords'); ?></option>
                        </select>
                        <button type="submit" class="btn btn-default"><?php _e('Apply','mywords'); ?></button>
                    </div>
                    <div class="col-md-6 col-lg-6 text-right">
                        <?php echo isset($nav) ? $nav->render(false) : ''; ?>
                    </div>
                </div>

            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php _e('Existing Categories', 'mywords'); ?></h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" cellspacing="0">
                        <thead>
                        <tr align="center">
                            <th scope="col" width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall)");' /></th>
                            <th><?php _e('Name','mywords'); ?></th>
                            <th><?php _e('Description','mywords'); ?></th>
                            <th><?php _e('Slug','mywords'); ?></th>
                            <th><?php _e('Posts','mywords'); ?></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr align="center">
                            <th scope="col"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall2)");' /></th>
                            <th><?php _e('Name','mywords'); ?></th>
                            <th><?php _e('Description','mywords'); ?></th>
                            <th><?php _e('Slug','mywords'); ?></th>
                            <th><?php _e('Posts','mywords'); ?></th>
                        </tr>
                        </tfoot>
                        <?php if(empty($categories)): ?>
                            <tr class="even">
                                <td colspan="5" class="text-center"><span class="label label-info"><?php _e('There are not categories registered yet!','mywords'); ?></span></td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach($categories as $cat): ?>
                            <tr id="item-<?php echo $cat['id_cat']; ?>" class="<?php echo tpl_cycle("even,odd"); ?> iedit"<?php if($new==$cat['id_cat']): ?> id="thenew"<?php endif; ?>>
                                <td valign="top"><?php if($cat['id_cat']): ?><input type="checkbox" name="cats[]" id="cat-<?php echo $cat['id_cat']; ?>" value="<?php echo $cat['id_cat'] ?>" /><?php endif; ?></td>
                                <td nowrap="nowrap">
                                    <strong><a href="categories.php?op=edit&amp;id=<?php echo $cat['id_cat']?>"><?php echo str_repeat("&#8212;",$cat['indent']).' '.$cat['name']; ?></a></strong>
                            <span class="mw_options">
                            <a href="categories.php?op=edit&amp;id=<?php echo $cat['id_cat']?>"><?php _e('Edit','mywords'); ?></a>
                                <?php if($cat['id_cat']!=1): ?> |
                                    <a href="javascript:;" onclick="return cat_del_confirm('<?php echo $cat['name']; ?>',<?php echo $cat['id_cat']; ?>);"><?php _e('Delete','mywords'); ?></a><?php endif; ?>
                            </span>
                                </td>
                                <td valign="top" class="mw_cat_description"><?php if ($cat['description']!=''): ?><?php echo $cat['description']; ?><?php else: ?>&nbsp;<?php endif; ?></td>
                                <td align="center" valign="top"><?php echo $cat['shortname']?></td>
                                <td align="center" valign="top"><?php echo $cat['posts']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <div class="cu-bulk-actions">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <select name="op2" id="cat-op" class="form-control">
                            <option value="" selected="selected"><?php _e('Bulk actions','mywords'); ?></option>
                            <option value="delete"><?php _e('Delete','mywords'); ?></option>
                        </select>
                        <button type="submit" class="btn btn-default"><?php _e('Apply','mywords'); ?></button>
                    </div>
                    <div class="col-md-6 col-lg-6 text-right">
                        <?php echo isset($nav) ? $nav->render(false) : ''; ?>
                    </div>
                </div>

            </div>

            <?php echo $xoopsSecurity->getTokenHTML(); ?>
        </form>
    </div>
    
</div>
