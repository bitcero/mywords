<div class="row">
    <div class="col-md-8 col-lg-9">

        <div id="mw-messages-post" class="mw_messages_post">

        </div>
        <h2 class="mw_submit_title"><?php $edit ? _e('Edit Post','mywords') : _e('Create Post','mywords'); ?></h2>
        <form name="mwposts" id="mw-form-posts" action="posts.php" method="post">
            <div class="form-group">
                <label class="error" for ="post-title" style="display: none;"><?php _e('You must specify the title for this post!','mywords'); ?></label>
                <input type="text" name="title" id="post-title" placeholder="<?php _e('Post title here...','mywords'); ?>" class="form-control input-lg" value="<?php echo $edit ? $post->getVar('title','e') : ''; ?>" required>
            </div>

            <div class="mw_permacont <?php if(!$edit): ?>mw_permainfo<?php endif; ?>" id="mw-perma-link">
                <?php if($edit): ?>
                    <strong><?php _e('Permalink:','mywords'); ?></strong> <?php echo str_replace($post->getVar('shortname'), '<span id="shortname">'.$post->getVar('shortname').'</span>', $post->permalink()); ?>
                <?php else: ?>
                    <?php _e('This post has not been saved. Remember to save it before leave this page.','mywords'); ?>
                <?php endif; ?>
            </div>

            <?php echo $editor->render(); ?>


            <?php if($allowed_tracks): ?>
                <div class="form-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php _e('Send Trackbacks','mywords'); ?></h3>
                        </div>
                        <div class="panel-body">
                            <label for="post-trackbacks"><?php _e('Send trackbacks to:','mywords'); ?></label>
                            <input type="text" name="trackbacks" id="post-trackbacks" class="form-control" value="<?php echo $edit && $post->getVar('toping') ? implode(' ', $post->getVar('toping')) : ''; ?>">
                            <small class="help-block">(<?php _e('Separate multiple URLs with spaces','mywords'); ?>)</small>
                            <?php if($edit): ?>
                                <strong><?php _e('Pinged:','mywords'); ?></strong><br />
                                <small><?php $pinged = $post->getVar('pinged'); echo !empty( $pinged ) ? implode("<br />", $pinged) : ''; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php _e('Custom Fields','mywords'); ?></h3>
                    </div>
                    <div class="panel-body">
                        <table id="metas-container" class="table<?php echo !$edit || (!isset($post) && !$post->fields()) ? ' mw_hidden' : ''; ?>" cellspacing="0" width="100%">
                            <tr class="head">
                                <td width="30%"><?php _e('Name','mywords'); ?></td>
                                <td><?php _e('Value','mywords'); ?></td>
                            </tr>
                            <?php if($edit || isset($post)): ?>
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
                        <table class="table" cellspacing="0">
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
                                    <input type="button" id="mw-addmeta" class="btn btn-info" value="<?php _e('Add custom field','mywords'); ?>" />
                                </td>
                            </tr>
                        </table>
                        <small class="help-block"><?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','mywords'); ?></small>
                    </div>
                </div>
            </div>

            <?php if($allowed_tracks): ?>
                <div class="form-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php _e('Comments and Trackbacks','mywords'); ?></h3>
                        </div>
                        <div class="panel-body">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="comstatus" value="1" checked="checked" /> <?php _e('Enable comments for this post','mywords'); ?>
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="pingstatus" value="1" checked="checked" /> <?php _e('Allow trackbacks for this post','mywords'); ?>
                            </label>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php RMEvents::get()->run_event('mywords.posts.form.center.widgets', isset($post) ? $post : null); ?>

            <input type="hidden" name="XOOPS_TOKEN_REQUEST" id="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
            <input type="hidden" name="op" id="mw-op" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
            <input type="hidden" name="frontend" id="mw-frontend" value="1" />
            <?php if($edit): ?>
                <input type="hidden" name="id" id="mw-id" value="<?php echo $post->id(); ?>" />
            <?php endif; ?>
        </form>

    </div>

    <div class="col-md-4 col-lg-3">
        <!-- Publish -->
        <?php
        include 'widgets/widget-publish.php';
        $w = mywords_widget_publish( $post, true );
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $w['title']; ?></h3>
            </div>
            <div class="panel-body">
                <?php echo $w['content']; ?>
            </div>
        </div>

        <!-- Categories -->
        <?php
        include 'widgets/widget-categories.php';
        $w = mywords_widget_categories( $post );
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $w['title']; ?></h3>
            </div>
            <div class="panel-body">
                <?php echo $w['content']; ?>
            </div>
        </div>

        <!-- Add tags -->
        <?php
        include 'widgets/widget-tags.php';
        $w = mywords_widget_addtags( $post );
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $w['title']; ?></h3>
            </div>
            <div class="panel-body">
                <?php echo $w['content']; ?>
            </div>
        </div>

        <!-- Add tags -->
        <?php
        include 'widgets/widget-image.php';
        $w = mywords_widget_image( $post );
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $w['title']; ?></h3>
            </div>
            <div class="panel-body">
                <?php echo $w['content']; ?>
            </div>
        </div>

        <?php RMEvents::get()->run_event('mywords.posts.form.front.widgets', isset($post) ? $post : null); ?>

    </div>
</div>

<?php if($edit && $post->getVar('toping')): ?>
<iframe src="<?php echo XOOPS_URL; ?>/modules/mywords/ping.php?post=<?php echo $post->id(); ?>" style="display: none; visibility: hidden; width: 0; height: 0;"></iframe>
<?php endif; ?>