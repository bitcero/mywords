// JavaScript Document
function npressShowComments()
{
	if	(document.layers) {
		document.layers['comentarios'].visibility="visible";
		document.layers['trackbacks'].visibility="hidden";
	} else if (document.getElementById) {
		document.getElementById('comentarios').style.display="";
		document.getElementById('comentarios').style.visibility="visible";
		document.getElementById('trackbacks').style.display="none";
		document.getElementById('trackbacks').style.visibility="hidden";
	} else if (document.all) {
		document.all('comentarios').style.display="";
		document.all('comentarios').style.visibility="visible";	
		document.all('trackbacks').style.display="none";
		document.all('trackbacks').style.visibility="hidden";	
	}
}

function npressShowTracks(){
	if	(document.layers) {
		document.layers['comentarios'].visibility="hidden";
		document.layers['trackbacks'].visibility="visible";
	} else if (document.getElementById) {
		document.getElementById('comentarios').style.display="none";
		document.getElementById('comentarios').style.visibility="hidden";
		document.getElementById('trackbacks').style.display="";
		document.getElementById('trackbacks').style.visibility="visible";
	} else if (document.all) {
		document.all('comentarios').style.display="none";
		document.all('comentarios').style.visibility="hidden";	
		document.all('trackbacks').style.display="";
		document.all('trackbacks').style.visibility="visible";	
	}
}

function npressInsertImageTiny(img, titulo, width, height, desc, href){
	var str = '';

	if (href!='')
		str += '<a href="' + href + '" target="_blank">';	
		
	str += '<img src="' + img + '" alt="' + titulo + '" title="' + titulo + '" width="'+width+'" height="'+height+'"';
	if (desc!='')
		str += ' longdesc="' + desc + '"';
		
	str += ' />';
	
	if (href!='')
		str += '</a>';
	
	tinyMCE.execCommand("mceInsertContent", false, str);
}

function npressInsertImage(img, titulo, width, height, desc, href){
	var campo = xoopsGetElementById('texto');
	var  str = '';
	if (href!='')
		str += '<a href="' + href + '">';	
		
	str += '<img src="' + img + '" alt="' + titulo + '" title="' + titulo + '" width="'+width+'" height="'+height+'"';
	if (desc!='')
		str += ' longdesc="' + desc + '"';
		
	str += ' />';
	
	if (href!='')
		str += '</a>';
	xoopsInsertText(campo, str);	
}