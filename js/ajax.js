function ajaxRequest(replaceid, url, param)
{
	var replaceId = replaceid;
	var xmlHttp = GetXmlHttpObject();

	stateChanged = function() { 
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{
			var text = xmlHttp.responseText;
			while(text.match("<script>") != null)
			{
				var scr = text.slice(text.search("<script>")+8);
				scr = scr.slice(0,scr.search("</script>"));
				eval(scr);
				text = text.replace("<script>"+scr+"</script>","");
			}
			var postScriptArr = new Array();
			var arrayIndex = 0;
			while(text.match("<postscript>") != null)
			{
				var scr = text.slice(text.search("<postscript>")+12);
				scr = scr.slice(0,scr.search("</postscript>"));
				postScriptArr[arrayIndex++] = scr;
				text = text.replace("<postscript>"+scr+"</postscript>","");
			}
			
			if(replaceId != '')
				try { document.getElementById(replaceId).innerHTML = text; }
				catch (e) { alert(e); }
				
			for(i = 0; i < arrayIndex; i++)
				eval(postScriptArr[i]);
		}
	}

	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("POST",url, true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.send(param);
}

function GetXmlHttpObject(){ 
	var objXMLHttp=null
	if (window.XMLHttpRequest){
		objXMLHttp=new XMLHttpRequest()
	}
	else if (window.ActiveXObject){
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
	}
	return objXMLHttp
}