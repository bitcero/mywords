var defimg = 0;
var defurl = '';
function send_resize(params){

    $.get(defurl, {data: params, img: defimg, action: 'resize'}, function(data){

        if (data['error']){
            $("#resizer-bar span.message").html('<span>'+data['message']+'</span>');
            resize_image(params);
            return;
        }

        var img = '<img src="'+data['file']+'" alt="" title="'+data['title']+'" />';

        $("#mywd-default-text").fadeOut('fast', function(){
            $("#mywd-default-thumb a").before(img);
            $("#mywd-default-thumb").fadeIn('fast');
            $("#mw-image").val(defimg);
        });

    }, "json");

}

function resize_image(params){

    if(defimg<=0)
        return false;

    $("#mywd-default-thumb").fadeOut('fast', function(){
        $("#mywd-default-thumb img").remove();
        $("#mywd-default-text").fadeIn('fast', function(){
            send_resize(params);
        });
    });


}

function in_array(val,array){
	var a=false;
	for (var i=0;i<array.length;i++){
		if (val.toLowerCase()==array[i].toLowerCase()){
			return true;
			break;
		}
	}
	
	return false;
}

function array_slice(array, val){
	for (var i=0;i<array.length;i++){
		if (val==array[i]){
            array[i] = '';
			//return array.slice(i+1,1);
		}
	}
    
    return array;
}

<?php $front = rmc_server_var($_GET, 'front', 0); ?>

$(document).ready( function($) {

    var total_tags = 0;
    var tip_tag_visible = false;

    $(".del_def_img").click(function(){
        $("#mywd-default-thumb img").remove();
        $("#mywd-default-thumb").fadeOut('fast');
        $("#mw-image").val("");
    });

    $("#publish-submit").click(function() {
        if ($("#status option[value='publish']").val()==undefined){
            $("#status").html($("#status").html() + "<option value='publish'><?php _e('Published','mywords'); ?></option>");
        }
        //$("#status option:selected").removeAttr("selected");
        //$("#status option[value='publish']").attr("selected", true);
        
    });

    $("#edit-publish").click(function() {
    	$("#edit-publish").hide();
        $("#publish-options").slideDown("fast");
    });
    
    $("#publish-ok").click(function() {
    	if ($("#status").val()=='draft'){
			$("#publish-status-legend").text('<?php _e('Draft','mywords'); ?>');
			$("#publish-submit").val("<?php _e('Save as Draft','mywords'); ?>");
    	} else if($("#status").val()=='pending') {
			$("#publish-status-legend").text('<?php _e('Pending Review','mywords'); ?>');
			$("#publish-submit").val("<?php _e('Save as Pending','mywords'); ?>");
    	} else {
            $("#publish-status-legend").text('<?php _e('Published','mywords'); ?>');
            $("#publish-submit").val("<?php _e('Publish','mywords'); ?>");
        }
	
		$("#publish-options").slideUp("fast");
		$("#edit-publish").show();
    });

    $("#visibility-edit").click(function() {
        $("#visibility-edit").hide();
        $("#visibility-options").slideDown('fast');
    });

    $("input[name=visibility]").click(function() {
        if ($(this).val()=='password'){
            $("#vis-password-text").slideDown();
        } else {
            $("#vis-password-text").slideUp();
        }
    });

    $("#vis-button").click(function() {

        //Verificamos el password
        $("#vis-password").val($("#vis-password").val().replace(/[ ]/gi,""));
        if ($("#vis-password").val()=='' && $("#visibility-options input[type=radio]:checked").val()=='password'){
            $("#vis-password-text").addClass("span-error");
            $("#vis-password-text").html($("#vis-password-text").html() + "<strong><?php _e('Password:','mywords'); ?></strong>");
            return;
        }
		
		// Verificamos si es privado
		if ($("#visibility-options input[type=radio]:checked").val()=='private'){
			$("#edit-publish").hide();
            $("#publish-status-legend").text('<?php _e('Private','mywords'); ?>');
		} else {
            $("#edit-publish").show();
            if ($("#status").val()=='draft'){
    			$("#publish-status-legend").text('<?php _e('Draft','mywords'); ?>');
            } else if ($("#status").val()=='publish'){
                $("#publish-status-legend").text('<?php _e('Published','mywords'); ?>');
        	} else {
    			$("#publish-status-legend").text('<?php _e('Pending Review','mywords'); ?>');
        	}
		}
		
        $("#vis-password-text").removeClass("span-error");
        $("#vis-password-text strong").text('');

        text = $("label[for="+$("#visibility-options input[type=radio]:checked").attr('id')+"]").text();
        $("#visibility-caption").text(text);
        $("#visibility-options").slideUp('fast');
        $("#visibility-edit").show();
    });
	
	$("#vis-cancel").click(function() {
		$("#visibility-options").slideUp('fast');
		$("#visibility-edit").show();
		
	});

    $(".edit-schedule").click(function() {
        $(".edit-schedule").hide();
        $(".schedule-options").slideDown('fast');
    });

    $("a.schedule-cancel").click(function() {
        d = $("input#schedule").val().split("-");
        $("#schedule-day").val(d[0]);
        $("#schedule-month option:selected").removeAttr("selected");
        $("#schedule-month option[value="+d[1]+"]").attr("selected", true);
        $("#schedule-year").val(d[2]);
        $("#schedule-hour").val(d[3]);
        $("#schedule-minute").val(d[4]);
        $(".schedule-options").slideUp('fast');
        $(".edit-schedule").show();
    });
    
    $("#schedule-ok").click(function(){
    	current = <?php echo time() ?>;
        schedule = mktime($("#schedule-hour").val(), $("#schedule-minute").val(), 0, $("#schedule-month").val(), $("#schedule-day").val(), $("#schedule-year").val());
        // Check if scheduled date is minor than current date
        if (schedule<=current){
        
            schedule = current;
            day = <?php echo(date('d',time())) ?>;
            month = <?php echo(date('n',time())) ?>;
            year = <?php echo(date('Y',time())) ?>;
            hour = <?php echo(date('H',time())) ?>;
            minute = <?php echo(date('i',time())) ?>;
            $("input#schedule").val(day+'-'+month+'-'+year+'-'+hour+'-'+minute);
            $("#schedule-day").val(day);
            $("#schedule-year").val(year);
            $("#schedule-hour").val(hour);
            $("#schedule-minute").val(minute);
            $("#schedule-month option:selected").removeAttr("selected");
            $("#schedule-month option[value="+month+"]").attr("selected", true);
            $("strong#schedule-caption").text('<?php _e('Inmediatly','mywords'); ?>');
            
        } else {
            
            $("input#schedule").val(schedule);
            val = $("#schedule-day").val() + '-' + $("#schedule-month").val() + '-' + $("#schedule-year").val() + '-' + $("#schedule-hour").val() + '-' + $("#schedule-minute").val();
            d = val.split("-");
            $("input#schedule").val(val);
            $("strong#schedule-caption").text(d[0]+', '+$("#schedule-month option[value="+d[1]+"]").text()+' '+d[2]+' @ '+d[3]+':'+d[4]);
            
        }
        
        $(".schedule-options").slideUp('fast');
        $(".edit-schedule").show();
        
    });
    
    // Tags
    $("#tags-button").click(function(){
		tag = $("#tags-m").val();
		if (tag=='') return;
		tags = tag.split(',');
		
		// Sanitize tags
		for (var j=0;j<tags.length;j++){
			tags[j] = tags[j].replace(/^\s*|\s*$/g,'');
			tags[j] = tags[j].replace(/[ ]{2,}/gi," ");
		}
		
		j = 0;
		
		spans = $("#tags-container label");
		$(spans).each(function(i){
			text = $(this).text().replace(" ","");
			text = text.replace("&nbsp;");
			text = text.replace(/^\s*|\s*$/g,'');
			if (in_array(text,tags)){
				tags = array_slice(tags,text);
			}
		});
		
		for (j=0;j<tags.length;j++){
            if (tags[j]=='') continue;
            total_tags++;
			$("#tags-container").append("<label><input type='checkbox' name='tags[]' checked='checked' value='"+tags[j]+"' /> "+tags[j]+"</label>");
		}
		$("#tags-m").val('');
        
        if (total_tags>0 && !tip_tag_visible){
			$("#tags-container span.tip_legends").show();
			tip_tag_visible = true;
        }
		
    });
    
    $("input#tags").keydown(function(e) { if(e.which == 13){ $("input#tags-button").click(); return false; } });
    
    // Popular Tags
    $("a#show-used-tags").click(function() {
		$("div#popular-tags-container").slideDown('slow');
    });
    
    $("a.add_tag").click(function() {
		labels = $("div#tags-container label");
		tag = $(this).text();
		found = false;
		$(labels).each(function(i){
			text = $(this).text().replace(" ","");
			text = text.replace("&nbsp;");
			text = text.replace(/^\s*|\s*$/g,'');
			if (text==tag){
				found = true;
			}
		});
		
		if (!found)
			$("div#tags-container").append("<label><input type='checkbox' name='tags[]' checked='checked' value='"+tag+"' /> "+tag+"</label>");
		
		if (!tip_tag_visible)
			$("div#tags-container span.tip_legends").show();
		
    });
    
    $("a.mw_show_metaname").click(function(){
		$("select#meta-name-sel").hide();
		$("input#meta-name").show();
		$("input#meta-name").focus();
		$("a.mw_show_metaname").hide();
		$("a.mw_hide_metaname").show();
    });
    
    $("a.mw_hide_metaname").click(function(){
    	$("input#meta-name").hide();
		$("select#meta-name-sel").show();
		$("select#meta-name-sel").focus();
		$("a.mw_hide_metaname").hide();
		$("a.mw_show_metaname").show();
    });
    
    $("input#mw-addmeta").click(function(){
		if ($("select#meta-name-sel").is(":visible")){
			var name = $("select#meta-name-sel").val();
		} else {
			var name = $("input#meta-name").val()
		}
		
		if (name==''){
			$("label#error-metaname").slideDown('fast');
			return;
		}
		
		var value = $("textarea#meta-value").val();
		if (value==''){
			$("label#error-metavalue").slideDown('fast');
			return;
		}
		
		$("label#error-metaname").hide();
		$("label#error-metavalue").hide();
		
		var exit = false;
		if ($("table#metas-container input").length>0){
			$("table#metas-container input").each(function(){
				if ($(this).val()==name){
					alert('<?php _e('There is already a meta with same name','mywords'); ?>');
					exit = true;
					return;
				}
			});
		}
		
		if (exit) return;
		
        var count = 0;
        $("table#metas-container input").each(function(){
            id = $(this).attr("id").substring(0, 8);
            if (id=='meta-key'){
                num = $(this).attr("id").replace("meta-key-","");
                if (count <= num)
                    count = num;
            }
        });
        
        count++;
		
		$("table#metas-container").show();
		var html = '<tr class="even">';
		html += '<td valign="top"><input type="text" name="meta['+count+'][key]" id="meta-key-'+count+'" value="'+name+'" class="mw_large" style="width: 95%;" />';
		html += '<a href="javascript:;" onclick="remove_meta($(this));"><?php _e('Remove','mywords'); ?></td>';
		html += '<td><textarea name="meta['+count+'][value]" id="meta['+count+'][value]" class="mw_large">'+value+'</textarea></td></tr>';
		$("table#metas-container").append(html);
		
		$("select#meta-name-sel option[selected='selected']").removeAttr('selected');
		$("select#meta-name-sel option[value='']").attr("selected",'selected');
		$("textarea#meta-value").val('');
		$("input#meta-name").val('');
		
		$("tr#row-"+count).effect('highlight',{},'2000');
		
    });
    
    $("input#publish-submit").click(function(){
		
        $('div#mw-messages-post').slideUp('slow',function(){
            $('div#mw-messages-post').html('');
        });
        
        if($("input#post-title").val()==''){
            $("label[for='post-title']").slideDown();
            return false;
        }
        
        if(typeof tinyMCE!='undefined'){
            tinyMCE.activeEditor.save();
        }
        
        if ($("#content").val()==''){
            alert('<?php _e('Add content before to save this post','mywords'); ?>');
            return false;
        }
        
        // Serialize all data
        var params = $("form#mw-form-posts").serialize();
        params += "&"+$("form#mw-post-publish-form").serialize();
        params += "&"+$("form#mw-post-categos-form").serialize();
        params += "&"+$("form#mw-post-tags-form").serialize();
        params += "&"+$("form#frm-defimage").serialize();
        
        var blocker = '<div id="mw-blocker"></div><div id="mw-blocker-message"><img src="../images/wait.gif" alt="" /><br /><?php _e("Saving post...","mywords"); ?></div>';
        $("body").append(blocker);
        $("#mw-blocker, #mw-blocker-message").fadeIn('fast');
        // Send Post data
        $.post('<?php echo XOOPS_URL; ?>/modules/mywords/admin/ajax/ax-posts.php', params, function(data){
            
            if(data['error']!=undefined && data['error']!=''){
                $('div#mw-messages-post').addClass('messages_error');
                $('div#mw-messages-post').html(data['error']);
                $('div#mw-messages-post').slideDown();
                if(data['token'])
                    $('#XOOPS_TOKEN_REQUEST').val(data['token']);
                
                $("#mw-blocker, #mw-blocker-message").fadeOut('fast');
                return false;
            }
            
            $("#mw-blocker-message").html('<img src="../images/wait.gif" alt="" /><br /><?php _e("Loading post...","rmcommon"); ?>');
            window.location.href = '<?php if(!$front): echo "posts.php?op=edit"; else: echo "submit.php?action=edit"; endif; ?>&id='+data['post'];
            
        },'json');
        
        return false;
        
    });
    
    $("#a-show-new").click(function(){
		$("div#w-catnew-form").slideDown('slow');
	});
	
	$("#w-catnew-form a").click(function(){
		$("div#w-catnew-form").slideUp('slow');
	});
	
	$("#create-new-cat").click(function(){

		var name = $("#w-catnew-form input#w-name").val();
		if (name==''){
			$("label[for='w-name']").slideDown();
			return;
		}
	
		var parent = $("select#w-parent").val();
		
		var params = {
			'XOOPS_TOKEN_REQUEST': $("input#XOOPS_TOKEN_REQUEST").val(),
			'name':name,
			'parent':parent
		};
		
		$.post('<?php echo XOOPS_URL; ?>/modules/mywords/admin/ajax/ax-categories.php', params, function(data){
			
			if (data['error']!=undefined){
				alert(data['error']);
				if(data['token'])
					$("input#XOOPS_TOKEN_REQUEST").val(data['token']);
				
				return;
			}
			
			var html = '<label class="cat_label" id="label-'+data['id']+'"><input type="checkbox" name="categories[]" id="categories[]" value="'+data['id']+'" checked="checked" /> '+name+'</label>';
			$("#w-categos-container").prepend(html);
            $("#w-parent").prepend('<option value="'+data['id']+'">'+name+'</option>');
			$("#label-"+data['id']).focus();
			$("#label-"+data['id']).effect('highlight', {}, 1000);
			$("#XOOPS_TOKEN_REQUEST").val(data['token']);
			
			$("#w-catnew-form input#w-name").val('');
			$("#w-parent option[selected='selected']").removeAttr('selected');
			$("#w-parent option[value='0']").attr('selected','selected');
			
		},'json');
		
	});
	
	$("#post-shortname").click(function(){
		if ($("#shortname-editor").length>0) return;
		var html = '<input type="text" size="20" value="'+$(this).html()+'" name="shortname" id="shortname-editor" />';
		$(this).html(html);
		$("#shortname-editor").focus();
	});

 });
 
 function remove_meta(id){
	 
	 var parent = $(id).parent();
	 parent = $(parent).parent();
	 $(parent).remove();
	 
 }
