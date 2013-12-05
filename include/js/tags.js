$(document).ready(function(){
    $("#addtag").validate({
        messages:{
            name: '<?php echo _AS_MW_NAMEREQ ?>'
        },
        invalidHandler: function(form, validator){
            $("#name").css("border-color","red")
        }
	});

});