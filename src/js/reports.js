(function($){

    function sendRequest(params, ele, callback){

        params.CUTOKEN_REQUEST = $("#cu-token").val();
        var url = 'reports.php';

        $.post(url, params, function(response){
            $(ele).cuSpinner();

            var result = cuHandler.retrieveAjax(response);

            if(undefined != callback){
                callback(result, response);
            }

        }, 'json');
    }

    function updateStatusIcons(result, response){

        if(result){

            var icon = response.status == 'accepted' ? 'svg-rmcommon-ok-circle' : 'svg-rmcommon-sand-clock';
            var iconButton = response.status == 'accepted' ? 'svg-rmcommon-sand-clock' : 'svg-rmcommon-ok';

            for(var i = 0;i<response.ids.length;i++){
                if($("[data-status='"+response.ids[i]+"']").length > 0){

                    // Set status icon and color
                    $("[data-status='"+response.ids[i]+"']")
                        .html('')
                        .removeClass(response.status == 'accepted' ? 'text-grey' : 'text-success')
                        .addClass(response.status == 'accepted' ? 'text-success' : 'text-grey');
                    cuHandler.loadIcon(icon, $("[data-status='"+response.ids[i]+"']"));

                    // Set option icon and color
                    $("[data-options='"+response.ids[i]+"'] .status")
                        .html('')
                        .removeClass(response.status == 'accepted' ? 'green' : 'purple')
                        .addClass(response.status == 'accepted' ? 'purple' : 'green')
                        .data('action', response.status == 'accepted' ? 'waiting' : 'accept');
                    cuHandler.loadIcon(iconButton, $("[data-options='"+response.ids[i]+"'] .status"));
                }
            }

        }

    }

    function deleteRow(result, response){

        for(var i = 0;i<response.ids.length;i++){
            if($("tr[data-id='"+response.ids[i]+"']").length > 0){

                // Set status icon and color
                $("tr[data-id='"+response.ids[i]+"']").remove();

            }
        }

    }

    /**
     * View details command
     */
    $("body").on('click', "[data-do='view']", function(){

        var params = {
            action: 'details',
            id: $(this).data("id")
        };

        var ele = $(this);
        $(this).cuSpinner({icon: 'svg-rmcommon-spinner-02'});

        sendRequest(params, ele);

        return false;
    });

    /**
     * Accept report command
     */
    $("body").on('click', '[data-do="status"]', function(){

        var params = {
            action: $(this).data('action'),
            ids: [$(this).data("id")]
        };

        var ele = $(this);
        $(this).cuSpinner({icon: 'svg-rmcommon-spinner-02'});
        sendRequest(params, ele, updateStatusIcons);
    });

    /**
     * Delete report command
     */
    $("body").on('click', '[data-do="delete"]', function(){

        var params = {
            action: 'delete',
            ids: [$(this).data("id")]
        };

        if(false == confirm(mwLang.confirmReportDeletion)){
            return false;
        }

        var ele = $(this);
        $(this).cuSpinner({icon: 'svg-rmcommon-spinner-02'});
        sendRequest(params, ele, deleteRow);
        return false;
    });

    /**
     * Bulk actions
     */
    $("#reports-bulk-apply").click(function(){

        var action = $("#reports-actions").val();

        if('' == action){
            return false;
        }

        var selected = $("#table-reports :checkbox[data-oncheck]:checked");

        if(selected.length <= 0){
            cuHandler.notify({
                type: 'alert-warning',
                icon: 'svg-rmcommon-question',
                text: mwLang.selectReport
            });
            return false;
        }

        if('delete' == action){
            if(false == confirm(mwLang.confirmReportDeletion)){
                return false;
            }
        }

        $(this).cuSpinner({icon: 'svg-rmcommon-spinner-02'});

        var ids = [];
        $(selected).each(function(){
            ids.push($(this).val());
        });

        var params  = {
            action: action,
            ids: ids
        };

        switch(action){
            case 'waiting':
            case 'accept':
                var callback = updateStatusIcons;
                break;
            case 'delete':
                var callback = deleteRow;
                break;
        }

        sendRequest(params, $(this), callback);
        return false;

    });

})(jQuery);