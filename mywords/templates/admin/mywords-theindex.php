<h1 class="cu-section-title mw_titles"><em class="fa fa-home"></em> <?php _e('Dashboard','mywords'); ?></h1>

<div id="row"  data-news="load" data-boxes="load" data-module="mywords" data-target="#mywords-news">
    
    <div class="col-md-4 col-lg-4" data-box="mywords-box-left">
        <!-- Quick overview -->
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('Quick Overview','mywords'); ?></h3>
            </div>
            <div class="box-content">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr class="mw_qrdata">
                        <td align="right" width="20"><a href="posts.php"><span><?php echo $numposts; ?></span></td>
                        <td><a href="posts.php"><?php _e('Posts', 'admin_mywords'); ?></a></td>
                        <td align="right" width="20"><a href="<?php echo RMCURL; ?>/comments.php?module=mywords"><span><?php echo $numcoms; ?></span></a></td>
                        <td><a href="<?php echo XOOPS_URL; ?>/modules/rmcommon/comments.php?module=mywords"><?php _e('Comments','mywords'); ?></a></td>
                    </tr>
                    <tr class="mw_qrdata">
                        <td align="right"><a href="posts.php?status=draft"><span><?php echo $numdrafts; ?></span></a></td>
                        <td><a href="posts.php?status=draft"><?php _e('Drafts','mywords'); ?></a></td>
                        <td align="right" width="20"><a href="<?php echo RMCURL; ?>/editors.php"><span><?php echo $numeditors; ?></span></a></td>
                        <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/editors.php"><?php _e('Editors','mywords'); ?></a></td>
                    </tr>
                    <tr class="mw_qrdata">
                        <td align="right"><a href="posts.php?status=pending"><span><?php echo $numpending; ?></span></a></td>
                        <td><a href="posts.php?status=pending"><?php _e('Pending of Review','mywords'); ?></a></td>
                        <td align="right" width="20"><a href="<?php echo RMCURL; ?>/bookmarks.php"><span><?php echo $numsocials; ?></span></a></td>
                        <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/bookmarks.php"><?php _e('Social sites','mywords'); ?></a></td>
                    </tr>
                    <tr class="mw_qrdata">
                        <td align="right"><a href="categories.php"><span><?php echo $numcats; ?></span></a></td>
                        <td><a href="categories.php"><?php _e('Categories','mywords'); ?></a></td>
                        <td align="right" width="20"><a href="<?php echo RMCURL; ?>/tags.php"><span><?php echo $numtags; ?></span></a></td>
                        <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/tags.php"><?php _e('Tags','mywords'); ?></a></td>
                    </tr>
                </table><br />
                <span class="descriptions"><?php _e('Current version:','mywords'); ?> <strong><?php echo RMModules::format_module_version($xoopsModule->getInfo('rmversion')); ?></strong></span>
            </div>
        </div>
        <!-- / End quick overview -->
        
        <!-- Drafts -->
        <div class="cu-box">
        	<div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('Recent Drafts', 'admin_mywords'); ?></h3>
            </div>
        	<?php foreach($drafts as $post): ?>
        	<div class="box-content mw_tools">
        		<a href="posts.php?op=edit&amp;id=<?php echo $post->id(); ?>" class="item">
        			<?php echo $post->getVar('title'); ?><br />
        			<span><?php echo substr(strip_tags($post->content(true)), 0, 150).'...'; ?></span>
        		</a>
        		
        	</div>
        	<?php endforeach; ?>
        </div>
        <!-- / End Drafts -->
        
        <!-- Pending of review -->
        <div class="cu-box">
        	<div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('Posts pending for review', 'admin_mywords'); ?></h3>
            </div>
        	<?php foreach($pendings as $post): ?>
        	<div class="box-content">
        		<a href="posts.php?op=edit&amp;id=<?php echo $post->id(); ?>" class="item">
        			<?php echo $post->getVar('title'); ?><br />
        			<span><?php echo substr(strip_tags($post->content(true)), 0, 150).'...'; ?></span>
        		</a>
        		
        	</div>
        	<?php endforeach; ?>
        </div>
        <!-- / End Pending of Review -->
        
        <!-- Other blocks -->
        <?php RMEvents::get()->run_event('mywords.dashboard.left.widgets'); ?>
        <!-- /End other blocks -->
        
    </div>


    <div class="col-md-4 col-lg-4" data-box="mywords-box-center">

        <?php if(isset($htResult) && $htResult!==true): ?>
        <div class="alert alert-block">
            <h4><?php _e('Important!','mywords'); ?></h4>
            <p><?php _e('MyWords tried to write your htaccess file in order to enable friendly urls but has been impossible. Please copy and paste next code into your htaccess file.','mywords'); ?></p><br />
            <pre><?php echo $htResult; ?></pre>
        </div>
        <?php endif; ?>

        <!-- Editors -->
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('Editors Activity','mywords'); ?></h3>
            </div>
            <div class="box-content">
            <?php if(empty($editors)): ?>
            <?php _e('There are not editors registered yet!','mywords'); ?>

            <?php else: ?>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th><?php _e('Name','mywords'); ?></th>
                        <th align="center"><?php _e('Posts','mywords'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($editors as $ed): ?>
                <tr class="even">
                    <td>
                        <a href="<?php echo $ed['link']; ?>"><?php echo $ed['name']; ?></a>
                    </td>
                    <td align="center">
                        <?php echo $ed['total']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php endif; ?>
            </div>
        </div>
        <!-- End editors -->

        <!-- Resources -->
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('MyWords Resources','mywords'); ?></h3>
            </div>
            <div class="box-content">
                <a href="http://redmexico.com.mx/docs/mywords" target="_blank" class="item">
                    <span class="fa fa-caret-right"></span>
                    <?php _e('MyWords documentation','mywords'); ?>
            		<span class="help-block"><?php _e('Learn more about MyWords. Installation, configuration and all information to improve this module.','mywords'); ?></span>
                </a>
                <a href="http://redmexico.com.mx/" target="_blank" class="item">
                    <span class="fa fa-caret-right"></span>
                    <?php _e('Red México','mywords'); ?>
                    <span class="help-block"><?php _e('New modules, themes and awesome resources for XOOPS.','mywords'); ?></span>
                </a>
                <?php
                // Print new resources
                RMEvents::get()->run_event('mywords.get.resources.list');
                ?>
            </div>
        </div>
        <!--// End resources -->

    </div>


    <div class="col-md-4 col-lg-4" data-box="mywords-box-right">

        <!-- Recent News -->
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('Recent News','mywords'); ?></h3>
            </div>
            <div class="box-content" id="mywords-news">

            </div>
        </div>
        <!-- /End recent news -->

        <!-- Other blocks -->
        <?php RMEvents::get()->run_event('mywords.dashboard.right.widgets'); ?>
        <!-- /End other blocks -->

    </div>
    
</div>
