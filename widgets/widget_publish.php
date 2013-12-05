<?php
// $Id: widget_publish.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Publish widget
* @return array
*/
function mw_widget_publish(){
	global $xoopsUser;
	
	RMTemplate::get()->add_style('publish_widget.css','mywords');
	RMTemplate::get()->add_style('forms.css','rmcommon');
	RMTemplate::get()->add_style('jquery.css','rmcommon');
	RMTemplate::get()->add_script(XOOPS_URL.'/modules/mywords/include/js/scripts.php?file=posts.js');
	RMTemplate::get()->add_script(XOOPS_URL.'/modules/mywords/include/js/mktime.js');
	RMTemplate::get()->add_script('forms.js', 'rmcommon');
	$widget['title'] = __('Publish','mywords');
	$widget['icon']	 = '';
    
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    $edit = false;
    if ($id>0){
        $post = new MWPost($id);
        if ($post->isNew()){
            unset($post);
        } else {
            $edit = true;
        }
    }
    
    if ($edit){
        
        switch($post->getVar('status')){
			case 'draft':
				$status = __('Draft','mywords');
				break;
			case 'pending':
				$status =  __('Pending review','mywords');
				break;
			case 'publish':
				$status =  __('Published','mywords');
				break;
			case 'scheduled':
				$status =  __('Scheduled','mywords');
				break;
        }
        $visibility = $post->getVar('visibility')=='public' ? 'Public' : ($post->getVar('visibility')=='password' ? 'Password Protected' : 'Private');
        
    } else {
        $status = 'Published';
        $visibility = 'Public';
    }
    
	ob_start();
?>
<div class="rmc_widget_content_reduced publish_container">
<form id="mw-post-publish-form">
<!-- Opciones de Publicación -->
<div class="publish_options">
<?php _e('Status:','mywords'); ?> <strong id="publish-status-legend"><?php _e($status,'mywords'); ?></strong> &nbsp; <a href="javascript:;" id="edit-publish"><?php _e('Edit','mywords'); ?></a>
	<div id="publish-options" style="display: none;">
		<select name="status" id="status">
            <option value="publish"<?php echo $edit && $post->getVar('status')=='publish' ? 'selected="selected"' : ($edit ? '' : 'selected="selected"'); ?>><?php _e('Published','mywords') ?></option>
			<option value="draft"<?php echo $edit && $post->getVar('status')=='draft' ? 'selected="selected"' : ''?>><?php _e('Draft','mywords') ?></option>
			<option value="pending"<?php echo $edit && $post->getVar('status')=='pending' ? 'selected="selected"' : ''?>><?php _e('Pending Review','mywords') ?></option>
		</select>
		<input type="button" name="publish-ok" id="publish-ok" class="button" value="<?php _e('Apply','mywords') ?>" /><br />
		<a href="javascript:;" onclick="$('#publish-options').slideUp('slow'); $('#edit-publish').show();"><?php _e('Cancel','mywords') ?></a>
	</div>
</div>
<!-- //Opciones de Publicación -->
<!-- Visibilidad -->
<div class="publish_options">
<?php _e('Visibility:','mywords'); ?> <strong id="visibility-caption"><?php _e($visibility,'mywords'); ?></strong> &nbsp; <a href="javascript:;" id="visibility-edit"><?php _e('Edit','mywords'); ?></a>
<?php
    if (!$edit){
        $visibility = 'public';
    } else {
        $visibility = $post->getVar('visibility');
    }
?>
    <div id="visibility-options">
        <input type="radio" name="visibility" value="public" id="visibility-public"<?php echo $visibility=='public' ? ' checked="checked"' : ''; ?> /> <label for="visibility-public"><?php _e('Public','mywords'); ?></label><br />
        <input type="radio" name="visibility" value="password" id="visibility-password"<?php echo $visibility=='password' ? ' checked="checked"' : ''; ?> /> <label for="visibility-password"><?php _e('Password protected','mywords'); ?></label><br />
        <span id="vis-password-text" style="<?php _e($visibility=='password' ? '' : 'display: none') ?>">
            <label>
            <?php _e('Password:','mywords') ?>
            <input type="text" name="vis_password" id="vis-password" value="<?php echo $edit ? $post->getVar('password') : ''; ?>" class="options_input" />
            </label>
        <br /></span>
        <input type="radio" name="visibility" value="private" id="visibility-private"<?php echo $visibility=='private' ? ' checked="checked"' : ''; ?> /> <label for="visibility-private"><?php _e('Private','mywords') ?></label><br /><br />
        <input type="button" name="vis-button" id="vis-button" value="<?php _e('Apply','mywords') ?>" class="button" />
        <a href="javascript:;" id="vis-cancel"><?php _e('Cancel','mywords') ?></a>
    </div>
</div>
<!-- /Visibilidad -->
<!-- Schedule -->
<div class="publish_options">
<?php _e('Publish','mywords'); ?> <strong id="schedule-caption"><?php echo $edit ? ($post->getVar('pubdate')>0?__('Inmediatly','mywords'):date("d, M Y \@ H:i", $post->getVar('schedule'))) : __('Inmediatly','mywords'); ?></strong> &nbsp; <a href="javascript:;" class="edit-schedule"><?php _e('Edit','mywords'); ?></a>
    <div class="schedule-options" style="display: none;">
        <?php
            // Determinamos la fecha correcta
            $time = $edit ? ($post->getVar('pubdate')>0?$post->getVar('pubdate'):$post->getVar('schedule')) : time();
            $day = date("d", $time);
            $month = date("n", $time);
            $year = date("Y", $time);
            $hour = date("H", $time);
            $minute = date("i", $time);
            $months = array(
            	__('Jan','mywords'),
            	__('Feb','mywords'),
            	__('Mar','mywords'),
            	__('Apr','mywords'),
            	__('May','mywords'),
            	__('Jun','mywords'),
            	__('Jul','mywords'),
            	__('Aug','mywords'),
            	__('Sep','mywords'),
            	__('Oct','mywords'),
            	__('Nov','mywords'),
            	__('Dec','mywords')
            );
        ?>
        <input type="text" name="schedule_day" id="schedule-day" size="2" maxlength="2" value="<?php _e($day) ?>" />
        <select name="schedule_month" id="schedule-month">
            <?php for($i=1;$i<=12;$i++){ ?>
                <option value="<?php echo $i; ?>" <?php if ($month==$i) echo('selected="selected"') ?>><?php echo $months[$i-1]; ?></option>
            <?php } ?>
        </select>
        <input type="text" name="schedule_year" id="schedule-year" size="2" maxlength="4" value="<?php echo $year;  ?>" /> @
        <input type="text" name="schedule_hour" id="schedule-hour" size="2" maxlength="2" value="<?php echo $hour; ?>" /> :
        <input type="text" name="schedule_minute" id="schedule-minute" size="2" maxlength="2" value="<?php echo $minute; ?>" /><br /><br />
        <input type="button" class="button" name="schedule-ok" id="schedule-ok" value="<?php _e('Apply','mywords') ?>" />
        <a href="javascript:;" class="schedule-cancel"><?php _e('Cancel','mywords') ?></a>
        <input type="hidden" name="schedule" id="schedule" value="<?php echo "$day-$month-$year-$hour-$minute"; ?>" />
    </div>
</div>
<!-- /Shedule -->
<div class="publish_options no_border">
<?php _e('Author:','mywords'); ?>
<?php 
	$user = new RMFormUser('', 'author', 0, $edit ? array($post->getVar('author')) : array($xoopsUser->uid()));
	if (!$xoopsUser->isAdmin()) $user->button(false);
	echo $user->render();
?>
</div>
<div class="widget_button">


<input type="submit" value="<?php _e($edit ? 'Update Post' : 'Publish','mywords'); ?>" class="button default btn
btn-primary" id="publish-submit" />
</div>


</form>
</div>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}
