// $Id: trackbacks.js 208 2010-02-12 06:00:27Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
	$("#form-list-tracks").submit(function(){
		if ($("#action-list").val()==''){
			alert('<?php _e('Select an option','admin_mywords'); ?>');
			return false;
		}
		
		var eles = $("#form-list-tracks input[type='checkbox']");
		var ok = false;
		
		for(i=0;i<=eles.length;i++){
			if($(eles[i]).attr("name")!='tbs[]') continue;
			if ($(eles[i]).is(":checked")){
				ok = true;
				break;
			}
		}
		
		if (!ok){
			alert("<?php _e('Select at least one trackback!','admin_mywords'); ?>");
			return false;
		}
		
		if ($("#action-list").val()=='delete'){
			return  confirm("<?php _e('Do you really want to delete selected trackbacks?', 'admin_mywords'); ?>");
		}
		
	});
	
	$("#action-list").change(function(){
		$("#action-listb").val($("#action-list").val());
	});
	
	$("#action-listb").change(function(){
		$("#action-list").val($("#action-listb").val());
	});
});

function delete_trackback(id){
    
    $("input[name='tbs[]']").removeAttr("checked")
    $("#tb-"+id).attr('checked','checked');
    $("#action-list").val('delete');
    $("#form-list-tracks").submit();
    
}