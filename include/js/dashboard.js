// $Id: dashboard.js 555 2010-11-15 16:42:05Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
	
	var url=encodeURIComponent("http://www.xoopsmexico.net/modules/vcontrol/?id=2");

	$.post('<?php echo XOOPS_URL; ?>/modules/rmcommon/include/proxy.php', {url: url}, function(data){
		if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
        $("#mw-recent-news").html(data);
	}, 'html');
	
});