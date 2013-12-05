$(document).ready(function(){
    
    $("#action-list").change(function(){
		$("#action-list-b").val($("#action-list").val());
	});
    
    $("#form-new-editor").submit(function(){
        
        if($("#new-name").val()==''){
            $("#form-new-editor label[for='new-name']").css("color",'#f00');
            $("#form-new-editor #new-name").css("border-color",'#f00');
            $("#form-new-editor #new-name").focus();
            return false;
        }
        
        if($("#new_user-users-list li").length<=0){
            $("#form-new-editor label[for='new-user']").css("color",'#f00');
            return false;
        }
        
    });
    
});

function goto_activate(id,page,act){
    
    if(!act){
    	var rtn = confirm('<?php _e('Do you really want to deactivate this editor?','admin_mywords'); ?>');
    
    	if (!rtn) return false;
	}
    
    $("#form-list-editors input[type=checkbox]").removeAttr("checked");
    $("#form-list-editors #editor-"+id).attr('checked','checked');
    $("#form-list-editors #action-list").val(act?'activate':'deactivate');
    $("#apply-button").click();
    
}

function goto_delete(id,page){
    
    var rtn = confirm('<?php _e('Do you really want to delete this editor?','admin_mywords'); ?>');
    
    if (!rtn) return false;
    
    $("#form-list-editors input[type=checkbox]").removeAttr("checked");
    $("#form-list-editors #editor-"+id).attr('checked','checked');
    $("#form-list-editors #action-list").val('delete');
    $("#apply-button").click();
    
}
