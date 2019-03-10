<div class="report-details">
    <div class="table-responsive">
        <table class="table table-no-bordered table-details">
            <tr>
                <td><?php _e('Title:', 'mywords'); ?></td>
                <td><strong><?php echo $report->title; ?></strong></td>
            </tr>
            <tr>
                <td><?php _e('Sent by:', 'mywords'); ?></td>
                <td>
                    <?php if ($report->user > 0): ?>
                        <a target="_blank" href="<?php echo XOOPS_URL; ?>/userinfo.php?uid=<?php echo $report->user; ?>"><?php echo $user['name']; ?></a>
                        <em>(<?php echo $user['email']; ?>)</em>
                    <?php else: ?>
                        <strong><?php echo $user['name']; ?></strong>
                        <em>(<?php echo $user['email']; ?>)</em>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><?php _e('Sent on:', 'mywords'); ?></td>
                <td>
                    <strong><?php echo $common->timeFormat('%T% %d%, %Y% @ %h%:%i%:%s%')->format($report->when); ?></strong>
                </td>
            </tr>
        </table>
    </div>
    <div class="report-content">
        <?php echo $report->content; ?>
    </div>
</div>

<div class="cu-content-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php _e('Close', 'mywords'); ?></button>
</div>