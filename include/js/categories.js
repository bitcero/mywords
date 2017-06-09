$(document).ready(function(){

    $("#addcat").validate({
        messages:{
            name: '<?php echo _AS_MW_NAMEREQ ?>'
        },
        invalidHandler: function(form, validator){
            $("#name").css("border-color","red")
        }
    });
	
	// Parent
	$("#parent").bind("change", function() {
		if ($("#parent").val()>0){
			$("#type").val('');
			$("#type").attr("disabled", true);
		} else {
			$("#type").attr("disabled", false);
		}
	});
	
  });
