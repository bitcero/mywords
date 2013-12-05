$(document).ready(function(){
	
	$("a#add_field_name").click(function(){
		if ($(this).text()=='Add New'){
			$("#dmeta_sel").hide();
			$("#dmeta").show();
			$(this).text("Cancel");
		} else {
			$("#dmeta_sel").show();
			$("#dmeta").hide();
			$(this).text("Add New");
		}
	});
	
	$("input#add_field").click(function(){
		if ($("#dvalue").val()=='') return;
		
		if ($("#dmeta_sel").is(":visible")){
			name = $("#dmeta_sel").val();
		} else {
			name = $("#dmeta").val();
			$("#dmeta_sel").show();
			$("#dmeta").hide();
			$("#dmeta").val('');
			$(this).text("Add New");
		}
		
		var field = '<tr valign="top"><td width="100"><input type="text" name="meta_name[]" value="'+name+'" /></td>';
		field += '<td><textarea name="meta_value[]" style="width: 99%; height: 70px;">'+$("#dvalue").val()+'</textarea></td></tr>';
		$("#dvalue").val('');
		$("#existing_meta").append(field);
		
	});
	
});