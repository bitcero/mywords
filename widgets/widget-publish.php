<?php
/**
 * MyWords for XOOPS
 *
 * Copyright © 2017 Eduardo Cortés http://www.eduardocortes.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Eduardo Cortés (http://www.eduardocortes.mx)
 * @license      GNU GPL 2
 * @package      mywords
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.eduardocortes.mx
 * @param null|mixed $post
 * @param mixed $frontend
 */

/**
 * Publish widget
 * @return array
 */
function mywords_widget_publish($post = null, $frontend = false)
{
    global $xoopsUser;

    RMTemplate::getInstance()->add_style('widget-publish.min.css', 'mywords');
    RMTemplate::get()->add_style('forms.min.css', 'rmcommon');
    RMTemplate::get()->add_style('jquery.css', 'rmcommon');
    RMTemplate::get()->add_script('scripts.php?file=posts.js', 'mywords', ['directory' => 'include', 'footer' => 1]);
    RMTemplate::get()->add_script(XOOPS_URL . '/modules/mywords/include/js/mktime.js');
    RMTemplate::get()->add_script('forms.js', 'rmcommon');
    $widget['title'] = __('Publish', 'mywords');
    $widget['icon'] = '';

    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    $edit = false;

    if (isset($post) && is_a($post, 'MWPost') && !$post->isNew()) {
        $edit = true;
        switch ($post->getVar('status')) {
            case 'draft':
                $status = __('Draft', 'mywords');
                break;
            case 'pending':
                $status = __('Pending review', 'mywords');
                break;
            case 'publish':
                $status = __('Published', 'mywords');
                break;
            case 'scheduled':
                $status = __('Scheduled', 'mywords');
                break;
        }
        $visibility = 'public' == $post->getVar('visibility') ? 'Public' : ('password' == $post->getVar('visibility') ? 'Password Protected' : 'Private');
    } else {
        $status = 'Published';
        $visibility = 'Public';
    }

    ob_start(); ?>
<div class="rmc_widget_content_reduced publish_container">
<form id="mw-post-publish-form">
<!-- Opciones de Publicación -->
<div class="publish_options">
<?php _e('Status:', 'mywords'); ?> <strong id="publish-status-legend"><?php _e($status, 'mywords'); ?></strong> &nbsp; <a href="javascript:;" id="edit-publish"><?php _e('Edit', 'mywords'); ?></a>
	<div id="publish-options" style="display: none;">
		<select name="status" id="status">
            <option value="publish"<?php echo $edit && 'publish' == $post->getVar('status') ? 'selected="selected"' : ($edit ? '' : 'selected="selected"'); ?>><?php _e('Published', 'mywords') ?></option>
			<option value="draft"<?php echo $edit && 'draft' == $post->getVar('status') ? 'selected="selected"' : ''?>><?php _e('Draft', 'mywords') ?></option>
			<option value="pending"<?php echo $edit && 'pending' == $post->getVar('status') ? 'selected="selected"' : ''?>><?php _e('Pending Review', 'mywords') ?></option>
		</select>
		<input type="button" name="publish-ok" id="publish-ok" class="button" value="<?php _e('Apply', 'mywords') ?>"><br>
		<a href="javascript:;" onclick="$('#publish-options').slideUp('slow'); $('#edit-publish').show();"><?php _e('Cancel', 'mywords') ?></a>
	</div>
</div>
<!-- //Opciones de Publicación -->
<!-- Visibilidad -->
<div class="publish_options">
<?php _e('Visibility:', 'mywords'); ?> <strong id="visibility-caption"><?php _e($visibility, 'mywords'); ?></strong> &nbsp; <a href="javascript:;" id="visibility-edit"><?php _e('Edit', 'mywords'); ?></a>
<?php
    if (!$edit) {
        $visibility = 'public';
    } else {
        $visibility = $post->getVar('visibility');
    } ?>
    <div id="visibility-options">
        <input type="radio" name="visibility" value="public" id="visibility-public"<?php echo 'public' == $visibility ? ' checked' : ''; ?>> <label for="visibility-public"><?php _e('Public', 'mywords'); ?></label><br>
        <input type="radio" name="visibility" value="password" id="visibility-password"<?php echo 'password' == $visibility ? ' checked' : ''; ?>> <label for="visibility-password"><?php _e('Password protected', 'mywords'); ?></label><br>
        <span id="vis-password-text" style="<?php _e('password' == $visibility ? '' : 'display: none') ?>">
            <label>
            <?php _e('Password:', 'mywords') ?>
            <input type="text" name="vis_password" id="vis-password" value="<?php echo $edit ? $post->getVar('password') : ''; ?>" class="options_input">
            </label>
        <br></span>
        <input type="radio" name="visibility" value="private" id="visibility-private"<?php echo 'private' == $visibility ? ' checked' : ''; ?>> <label for="visibility-private"><?php _e('Private', 'mywords') ?></label><br><br>
        <input type="button" name="vis-button" id="vis-button" value="<?php _e('Apply', 'mywords') ?>" class="button">
        <a href="javascript:;" id="vis-cancel"><?php _e('Cancel', 'mywords') ?></a>
    </div>
</div>
<!-- /Visibilidad -->
<!-- Schedule -->
<div class="publish_options">
<?php _e('Publish', 'mywords'); ?> <strong id="schedule-caption"><?php echo $edit ? ($post->getVar('pubdate') > 0 ? __('Inmediatly', 'mywords') : date("d, M Y \@ H:i", $post->getVar('schedule'))) : __('Inmediatly', 'mywords'); ?></strong> &nbsp; <a href="javascript:;" class="edit-schedule"><?php _e('Edit', 'mywords'); ?></a>
    <div class="schedule-options" style="display: none;">
        <?php
            // Determinamos la fecha correcta
            $time = $edit ? ($post->getVar('pubdate') > 0 ? $post->getVar('pubdate') : $post->getVar('schedule')) : time();
    $day = date('d', $time);
    $month = date('n', $time);
    $year = date('Y', $time);
    $hour = date('H', $time);
    $minute = date('i', $time);
    $months = [
                __('Jan', 'mywords'),
                __('Feb', 'mywords'),
                __('Mar', 'mywords'),
                __('Apr', 'mywords'),
                __('May', 'mywords'),
                __('Jun', 'mywords'),
                __('Jul', 'mywords'),
                __('Aug', 'mywords'),
                __('Sep', 'mywords'),
                __('Oct', 'mywords'),
                __('Nov', 'mywords'),
                __('Dec', 'mywords'),
            ]; ?>
        <input type="text" name="schedule_day" id="schedule-day" size="2" maxlength="2" value="<?php _e($day) ?>">
        <select name="schedule_month" id="schedule-month">
            <?php for ($i = 1; $i <= 12; $i++) {
                ?>
                <option value="<?php echo $i; ?>" <?php if ($month == $i) {
                    echo('selected="selected"');
                } ?>><?php echo $months[$i - 1]; ?></option>
            <?php
            } ?>
        </select>
        <input type="text" name="schedule_year" id="schedule-year" size="2" maxlength="4" value="<?php echo $year; ?>"> @
        <input type="text" name="schedule_hour" id="schedule-hour" size="2" maxlength="2" value="<?php echo $hour; ?>"> :
        <input type="text" name="schedule_minute" id="schedule-minute" size="2" maxlength="2" value="<?php echo $minute; ?>"><br><br>
        <input type="button" class="button" name="schedule-ok" id="schedule-ok" value="<?php _e('Apply', 'mywords') ?>">
        <a href="javascript:;" class="schedule-cancel"><?php _e('Cancel', 'mywords') ?></a>
        <input type="hidden" name="schedule" id="schedule" value="<?php echo "$day-$month-$year-$hour-$minute"; ?>">
    </div>
</div>
<!-- /Shedule -->
    <?php if (!$frontend): ?>
        <div class="publish_options no_border">
        <?php _e('Author:', 'mywords'); ?>
        <?php
            $user = new RMFormUser('', 'author', 0, $edit ? [$post->getVar('author')] : [$xoopsUser->uid()]);
    if (!$xoopsUser->isAdmin()) {
        $user->button(false);
    }
    echo $user->render(); ?>
        </div>
    <?php else: ?>
        <input type="hidden" name="author" value="<?php echo $xoopsUser->uid(); ?>">
    <?php endif; ?>
<div class="widget_button">

<button type="button" class="button default btn btn-primary" id="publish-submit"><?php $edit ? _e('Update Post', 'mywords') : _e('Publish', 'mywords'); ?></button>
</div>


</form>
</div>
<?php
    $widget['content'] = ob_get_clean();

    return $widget;
}
