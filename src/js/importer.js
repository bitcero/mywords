(function($){

    var data, categories, articles, categoriesDone = false;
    var $progress, $legend, $logger, increments, currentIncrement = 0;

    $(document).ready(function(){

        $("#start-publisher").click(function(){
            $(this).cuSpinner()
                .removeClass('btn-success')
                .addClass('btn-default')
                .disable();
            $("#importer-logger").fadeIn(250);
            $('#importer-indicators').fadeIn(250, function(){
                importingStart();
            });
        });

    });

    function addToLogger(msg, type){

        $logger.append('<li class="' + (type=='success' ? 'text-light-green' : 'text-danger') + '">' + msg + '</li>');

    }

    function importingStart(){
        $progress = $("#importer-indicators .progress-bar");
        $legend = $("#importer-indicators .progress-legend");
        $logger = $("#importer-logger");

        $legend.html(mwLang.collecting);

        var params = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: 'collect'
        };

        $.post('importer.php', params, function(response){

            if(!cuHandler.retrieveAjax(response)){
                $legend.addClass('text-danger').html(response.message);
                $progress.removeClass('progress-bar-info').removeClass('active').addClass('progress-bar-danger');
                return false;
            }

            if(response.categories.length <= 0 && response.articles.length <= 0){
                $legend.addClass('text-warning').html(mwLang.noData);
                $progress.removeClass('progress-bar-info').removeClass('active').addClass('progress-bar-warning');
                return false;
            }

            articles = response.articles;
            categories = response.categories;

            // Calculate progress bar increments
            var total = articles.length + categories.length;
            increments = 100 / total;

            var count = response.categories.length;

            if(count > 0){
                $progress.removeClass('progress-bar-info').addClass('progress-bar-success');
                importCategory(0);
                return;
            }

            categoriesDone = true;

            var count = response.articles.length;

            if(count <= 0){
                $legend.addClass('text-warning').html(mwLang.noArticles);
                $progress.removeClass('progress-bar-info').removeClass('active').addClass('progress-bar-warning');
                return false;
            }

            importArticle(0);

        }, 'json');

    }

    /**
     * Import categories one by one
     */
    function importCategory(index){

        var total = categories.length;

        if(index > total-1){
            categoriesDone = true;
            $legend.html(mwLang.categoriesDone);
            $progress.css('width', (increments * total) + '%')
            importArticle(0);
            return false;
        }

        var current = categories[index];

        $legend.html(mwLang.importingCategory.replace('%s', current.name));
        currentIncrement += increments;
        $progress.css('width', currentIncrement + '%');

        var params = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: 'import-category',
            id: current.id
        };

        $.post('importer.php', params, function(response){

            if(!cuHandler.retrieveAjax(response)){
                $legend.addClass('text-danger').html(response.message);
                $progress.removeClass('progress-bar-info').removeClass('active').addClass('progress-bar-danger');
                return false;
            }

            if(undefined != response.result){
                addToLogger(response.message, response.result);
            }
            importCategory(index+1);

        }, 'json');

    }

    /**
     * Import articles one by one
     */
    function importArticle(index){

        var total = articles.length;

        if(index > total-1){
            $legend.html(mwLang.articlesDone);
            $progress.css('width', '100%');
            closeImporter();
            return false;
        }

        var current = articles[index];

        $legend.html(mwLang.importingArticle.replace('%s', current.title));
        currentIncrement += increments;
        $progress.css('width', currentIncrement + '%');

        var params = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: 'import-article',
            id: current.id
        };

        $.post('importer.php', params, function(response){

            if(!cuHandler.retrieveAjax(response)){
                $legend.addClass('text-danger').html(response.message);
                $progress.removeClass('progress-bar-info').removeClass('active').addClass('progress-bar-danger');
                return false;
            }

            if(undefined != response.result){
                addToLogger(response.message, response.result);
            }
            importArticle(index+1);

        }, 'json');
    }

    function closeImporter(){
        var params = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: 'close'
        };

        $.post('importer.php', params, function(response){

            $logger.fadeOut(250);
            $("#importer").fadeOut(250, function(){
                $("#importer-finished").fadeIn(250);
            });

        }, 'json');
    }

}(jQuery));