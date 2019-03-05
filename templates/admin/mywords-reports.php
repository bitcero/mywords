<div class="cu-bulk-actions">
    <select name="action" class="form-control" id="reports-actions">
        <option value=""><?php _e('Select option...', 'mywords'); ?></option>
        <option value="accept"><?php _e('Mark as accepted', 'mywords'); ?></option>
        <option value="waiting"><?php _e('Mark as waiting', 'mywords'); ?></option>
        <option value="delete"><?php _e('Delete', 'mywords'); ?></option>
    </select>
    <button type="button" class="btn btn-primary" id="reports-bulk-apply"><?php _e('Apply', 'mywords'); ?></button>

    <div class="pull-right">
        <a href="<?php echo $post->permalink(); ?>" target="_blank" class="btn btn-orange">
            <?php echo $common->icons()->getIcon('svg-rmcommon-eye'); ?>
            <?php _e('View Post', 'mywords'); ?>
        </a>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php echo $common->icons()->getIcon('svg-rmcommon-report', ['class' => 'text-red']); ?>
            <?php echo sprintf(__('Received reports for "%s"', 'mywords'), $post->title); ?>
        </h3>
    </div>
    <div class="table-responsive">
        <table class="table" id="table-reports">
            <thead>
            <tr>
                <th class="text-center">
                    <input type="checkbox" data-checkbox="check-reports">
                </th>
                <th class="text-center">
                    <?php _e('ID', 'mywords'); ?>
                </th>
                <th>
                    <?php _e('Title', 'mywords'); ?>
                </th>
                <th class="text-center">
                    <?php _e('User', 'mywords'); ?>
                </th>
                <th class="text-center">
                    <?php echo $common->icons()->getIcon('svg-rmcommon-user'); ?>
                </th>
                <th class="text-center">
                    <?php _e('Date', 'mywords'); ?>
                </th>
                <th class="text-center">
                    <?php echo $common->icons()->getIcon('svg-rmcommon-eye'); ?>
                </th>
                <th class="text-center">
                    <?php _E('Options', 'mywords'); ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($reports)): ?>
            <tr class="text-center">
                <td colspan="5" class="text-info">
                    <?php _e('There are not reports received for this post', 'mywords'); ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php foreach ($reports as $report): ?>
            <tr data-id="<?php echo $report->id_report; ?>">
                <td class="text-center">
                    <input type="checkbox" name="ids[]" value="<?php echo $report->id_report; ?>" data-oncheck="check-reports">
                </td>
                <td class="text-center">
                    <strong><?php echo $report->id_report; ?></strong>
                </td>
                <td>
                    <?php echo $report->title; ?>
                </td>
                <td class="text-center">
                    <?php if ($report->user->id > 0): ?>
                        <a href="<?php echo XOOPS_URL; ?>/userinfo.php?uid=<?php echo $report->user->id; ?>" target="_blank"><?php echo $report->user->name; ?></a>
                    <?php else: ?>
                        <?php echo $report->user->name; ?>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if ($report->user->id > 0): ?>
                        <?php echo $common->icons()->getIcon('svg-rmcommon-ok', ['class' => 'text-success']); ?>
                    <?php else: ?>
                        <?php echo $common->icons()->getIcon('svg-rmcommon-cross', ['class' => 'text-grey']); ?>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php echo $common->timeFormat()->ago($report->when); ?>
                </td>
                <td class="text-center <?php echo 'accepted' === $report->status ? 'text-success' : 'text-grey'; ?>" data-status="<?php echo $report->id_report; ?>">
                    <?php if ('accepted' === $report->status): ?>
                        <?php echo $common->icons()->getIcon('svg-rmcommon-ok-circle'); ?>
                    <?php else: ?>
                        <?php echo $common->icons()->getIcon('svg-rmcommon-sand-clock'); ?>
                    <?php endif; ?>
                </td>
                <td class="text-center" data-options="<?php echo $report->id_report; ?>">
                    <div class="cu-options">
                        <a href="#" data-do="view" data-id="<?php echo $report->id_report; ?>" class="info" title="<?php _e('View report', 'mywords'); ?>">
                            <?php echo $common->icons()->getIcon('svg-rmcommon-eye'); ?>
                            <span class="sr-only"><?php _e('View report', 'mywords'); ?></span>
                        </a>
                        <?php if ('accepted' === $report->status): ?>
                            <a href="#" data-do="status" data-action="waiting" data-id="<?php echo $report->id_report; ?>" class="purple status" title="<?php _e('Mark as not read', 'mywords'); ?>">
                                <?php echo $common->icons()->getIcon('svg-rmcommon-sand-clock'); ?>
                            </a>
                        <?php else: ?>
                            <a href="#" data-do="status" data-action="accept" data-id="<?php echo $report->id_report; ?>" class="green status" title="<?php _e('Mark as accepted', 'mywords'); ?>">
                                <?php echo $common->icons()->getIcon('svg-rmcommon-ok'); ?>
                            </a>
                        <?php endif; ?>
                        <a href="#" data-do="delete" data-id="<?php echo $report->id_report; ?>" class="danger" title="<?php _e('Delete report', 'mywords'); ?>">
                            <?php echo $common->icons()->getIcon('svg-rmcommon-cross'); ?>
                            <span class="sr-only"><?php _e('Delete report', 'mywords'); ?></span>
                        </a>
                        <?php if ($report->user->id > 0): ?>
                        <a href="<?php echo $common->url('users.php?action=mailer&uid=' . $report->user->id); ?>" class="teal" title="<?php _e('Reply to user', 'mywords'); ?>">
                            <?php echo $common->icons()->getIcon('svg-rmcommon-envelope'); ?>
                            <span class="sr-only"><?php _e('Reply to user', 'mywords'); ?></span>
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
