// $Id: bookmarks.js 107 2010-01-10 03:32:17Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
	
	$("#action-list").change(function(){
		$("#action-list-b").val($("#action-list").val());
	});
	
	$(".icons_sel img").click(function(){
		$(".icons_sel img").removeClass('selected');
		$(this).addClass('selected');
		$("#new-icon-h").val($(this).attr('alt'));
	});
	
	$("#form-new-bookmark").submit(function(){
		
		if($("#new-title").val()==''){
			$("label[for='new-title']").css("color",'#f00');
            $("#new-title").css("border-color",'#f00');
            $("#new-title").focus();
            return false;
		}
		
		if($("#new-alt").val()==''){
			$("label[for='new-alt']").css("color",'#f00');
            $("#new-alt").css("border-color",'#f00');
            $("#new-alt").focus();
            return false;
		}
		
		if($("#new-url").val()==''){
			$("label[for='new-url']").css("color",'#f00');
            $("#new-url").css("border-color",'#f00');
            $("#new-url").focus();
            return false;
		}
		
	});
});

function goto_activate(id,act){
    
    if(!act){
    	var rtn = confirm('<?php _e('Do you really want to deactivate this site?','admin_mywords'); ?>');
    
    	if (!rtn) return false;
	}
    
    $("#form-list-book #book-"+id).attr('checked','checked');
    $("#form-list-book #book-list").val(act?'activate':'deactivate');
    $("#form-list-book").submit();
    
}

function goto_delete(id){
    
    var rtn = confirm('<?php _e('Do you really want to delete this site?','admin_mywords'); ?>');
    
    if (!rtn) return false;
    
    $("#form-list-book #book-"+id).attr('checked','checked');
    $("#form-list-book #action-list").val('delete');
    $("#form-list-book").submit();
    
}
