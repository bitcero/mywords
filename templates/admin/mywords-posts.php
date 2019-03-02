<h1 class="cu-section-title"><?php _e('Posts Management', 'mywords'); ?></h1>
<form name="frmSearch" method="get" action="posts.php" style="margin: 0;">
    <div class="row">
        <div class="col-md-2 col-lg-2">
            <input type="text" name="keyw" value="<?php echo $keyw!='' ? $keyw : ''; ?>" class="form-control" placeholder="<?php _e('Search', 'mywords'); ?>">
        </div>
        <div class="col-md-2 col-lg-2">
            <div class="input-group">
                <span class="input-group-addon"><?php _e('Results:', 'mywords'); ?></span>
                <input type="text" size="5" name="limit" value="<?php echo $limit; ?>" class="form-control" />
                <span class="input-group-btn">
                    <button class="btn btn-info" type="submit"><span class="fa fa-search"></span></button>
                </span>
            </div>
        </div>
        <div class="col-md-8 col-lg-8">
            
            <ul class="nav nav-pills">
                <li><a href="posts.php?op=new"><?php _e('Add New', 'mywords'); ?></a></li>
                <li<?php echo $status=='' ? ' class="active"' : ''; ?>><a href="posts.php?limit=<?php echo $limit ?>"><?php _e('Show all', 'mywords'); ?> <strong>(<?php echo($pub_count+$draft_count+$pending_count); ?>)</strong></a></li>
                <li<?php echo $status=='publish' ? ' class="active"' : ''; ?>><a href="posts.php?status=publish&amp;limit=<?php echo $limit ?>"><?php _e('Published', 'admin_mywords'); ?> <strong>(<?php echo $pub_count; ?>)</strong></a></li>
                <li<?php echo $status=='draft' ? ' class="active"' : ''; ?>><a href="posts.php?status=draft&amp;limit=<?php echo $limit ?>"><?php _e('Drafts', 'admin_mywords'); ?> <strong>(<?php echo $draft_count; ?>)</strong></a></li>
                <li<?php echo $status=='pending' ? ' class="active"' : ''; ?>><a href="posts.php?status=pending&amp;limit=<?php echo $limit ?>"><?php _e('Pending of Review', 'admin_mywords'); ?> <strong>(<?php echo $pending_count; ?>)</strong></a></li>
            </ul>
            
        </div>
    </div>
</form>
<br />
<form name="modPosts" id="form-posts" method="post" action="posts.php">
<div class="cu-bulk-actions">
    <div class="row">
        <div class="col-md-4 col-lg-4">
            <select name="op" id="posts-op" class="form-control">
                <option value=""><?php _e('Bulk Actions', 'mywords'); ?></option>
                <option value="delete"><?php _e('Delete Posts', 'mywords'); ?></option>
                <option value="status-waiting"><?php _e('Set status as Pending review', 'mywords'); ?></option>
                <option value="status-draft"><?php _e('Set status as Draft', 'mywords'); ?></option>
                <option value="status-published"><?php _e('Set status as published', 'mywords'); ?></option>
            </select>
            <button type="button" onclick="submit();" class="btn btn-default"><?php _e('Apply', 'mywords'); ?></button>
        </div>
        <div class="col-md-8 col-lg-8">
            <?php echo isset($nav) ? $nav->render(false) : ''; ?>
        </div>
    </div>

</div>

    <div class="panel panel-default">

            <div class="table-responsive">
                <table border="0" cellspacing="1" cellpadding="0" class="table">
                    <thead>
                    <tr class="head" align="center">
                        <th class="text-center" width="30">
                            <input type="checkbox" name="checkall" id="checkall" value="1" data-checkbox="chk-posts">
                        </th>
                        <th></th>
                        <th><?php _e('Post', 'mywords'); ?></th>
                        <th class="text-center"><?php _e('Author', 'mywords'); ?></th>
                        <th><?php _e('Categories', 'mywords'); ?></th>
                        <th><?php _e('Tags', 'mywords'); ?></th>
                        <th class="text-center"><span class="fa fa-comment"></span></th>
                        <th class="text-center"><span class="fa fa-eye"></span></th>
                        <th class="text-center"><?php _e('Date', 'mywords'); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr class="head" align="center">
                        <th class="text-center" width="30">
                            <input type="checkbox" name="checkall" id="checkall" value="1" data-checkbox="chk-posts">
                        </th>
                        <th style="width: 20px;"></th>
                        <th style="white-space: nowrap;"><?php _e('Post', 'mywords'); ?></th>
                        <th class="text-center"><?php _e('Author', 'mywords'); ?></th>
                        <th><?php _e('Categories', 'mywords'); ?></th>
                        <th><?php _e('Tags', 'mywords'); ?></th>
                        <th class="text-center"><span class="fa fa-comment"></span></th>
                        <th class="text-center"><span class="fa fa-eye"></span></th>
                        <th class="text-center"><?php _e('Date', 'mywords'); ?></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php if (empty($posts)): ?>
                        <tr class="even">
                            <td colspan="8" align="center" class="error"><?php _e('No posts where found', 'mywords'); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($posts as $post): ?>
                        <tr class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
                            <td align="center" valign="top">
                                <input type="checkbox" name="posts[]" id="post-<?php echo $post['id']; ?>" value="<?php echo $post['id']; ?>" data-oncheck="chk-posts">
                            </td>
                            <td class="text-center">
                                <?php if ($post['reports'] > 0): ?>
                                    <?php echo $common->icons()->getIcon('svg-rmcommon-report', ['class' => 'text-red', 'title' => __('This post has reports', 'mywords')]); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong>
                                    <a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a>
                                    <?php switch ($post['status']) {
                                        case 'draft':
                                            echo "<span class=\"draft\">- ".__('Draft', 'mywords')."</span> ";
                                            break;
                                        case 'scheduled':
                                            echo "<span class=\"sheduled\">- ".__('Scheduled', 'mywords')."</span> ";
                                            break;
                                        case 'pending':
                                            echo "<span class=\"pending\">- ".__('Pending', 'mywords')."</span> ";
                                            break;
                                    } ?>
                                </strong>
                            </td>
                            <td align="center">
                                <a href="posts.php?author=<?php echo $post['uid'] ?>"><?php echo $post['uname'] ?></a>
                            </td>
                            <td class="mw_postcats"><?php echo $post['categories']; ?></td>
                            <td class="mw_postcats">
                                <?php
                                $count = 1;
                                $ct = count($post['tags']);
                                foreach ($post['tags'] as $tag): ?>
                                    <?php echo $tag['tag']; ?><?php echo $count<$ct ? ',' : ''; ?>
                                    <?php $count++; endforeach; ?>
                            </td>
                            <td align="center">
                                <?php echo $post['comments']; ?>
                            </td>
                            <td align="center"><?php echo $post['reads']; ?></td>
                            <td align="center"><?php echo $post['date']; ?></td>
                            <td style="white-space: nowrap;">
                                <div class="cu-options">
    		                        <a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>" title="<?php _e('Edit', 'mywords'); ?>" class="warning">
                                        <?php echo $common->icons()->getIcon('svg-rmcommon-pencil'); ?>
                                        <span class="sr-only"><?php _e('Edit', 'mywords'); ?></span>
                                    </a>
    		                        <a href="#" class="danger" onclick="return post_del_confirm('<?php echo $post['title']; ?>', <?php echo $post['id']; ?>);" title="<?php _e('Delete', 'mywords'); ?>">
                                        <?php echo $common->icons()->getIcon('svg-rmcommon-cross'); ?>
                                        <span class="sr-only"><?php _e('Delete', 'mywords'); ?></span>
                                    </a>
                                    <?php if ($post['status']!='publish'): ?>
                                        <a href="<?php echo MW_URL.'?p='.$post['id']; ?>" title="<?php _e('Preview', 'mywords'); ?>" class="grey">
                                            <?php echo $common->icons()->getIcon('svg-rmcommon-eye'); ?>
                                            <span class="sr-only"><?php _e('Preview', 'mywords'); ?></span>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo $post['link']; ?>" title="<?php _e('View', 'mywords'); ?>" class="success">
                                            <?php echo $common->icons()->getIcon('svg-rmcommon-eye'); ?>
                                            <span class="sr-only"><?php _e('View', 'mywords'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($post['reports'] > 0): ?>
                                        <a href="reports.php?action=view&amp;id=<?php echo $post['id']; ?>" class="red" title="<?php _e('Reports', 'mywords'); ?>">
                                            <?php echo $common->icons()->getIcon('svg-rmcommon-report'); ?>
                                            <span class="sr-only"><?php _e('View Reports', 'mywords'); ?></span>
                                        </a>
                                    <?php endif; ?>
    	                        </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

    </div>

<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="keyw" value="<?php echo $keyw; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
</form>
