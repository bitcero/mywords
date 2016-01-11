<div class="alert alert-danger alert-icon">
    <?php echo $cuIcons->getIcon('svg-rmcommon-alert'); ?>
    <?php _e('Please be careful from import articles from other sources multiple times. In the best of cases, the import must be done once time and no more, due to run this script multiple times can cause duplicity or errors in synchronization.', 'mywords'); ?>
</div>

<div class="panel panel-pink">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Import from Publisher', 'mywords'); ?></h4>
    </div>
    <div class="panel-body">
        <p class="text-primary">
            <?php _e('Importing articles from publisher may take some time depending of amount of data to be imported. Please be patient and do not close or reload window while operation is on course.', 'mywords'); ?>
        </p>

        <div class="row" id="importer">
            <div class="col-sm-3">
                <button type="button" class="btn btn-success btn-lg btn-block" id="start-publisher">
                    <?php echo $cuIcons->getIcon('svg-rmcommon-play'); ?>
                    <?php _e('Start Import', 'mywords'); ?>
                </button>
            </div>
            <div class="col-sm-9">
                <div id="importer-indicators">
                    <div class="progress-legend text-midnight">
                        &nbsp;
                    </div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul id="importer-logger">
        </ul>

        <div id="importer-finished">
            <?php _e('Importing articles done!', 'mywords'); ?>
        </div>
    </div>
</div>
