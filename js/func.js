
function centerElm(elm)
{
	var xOffset = document.documentElement.scrollLeft;
	var yOffset = document.documentElement.scrollTop;

	yOffset = yOffset + (screen.availHeight - elm.offsetHeight) / 2;
	xOffset = xOffset + (screen.availWidth - elm.offsetWidth) / 2;
	elm.style.left = xOffset + 'px';
	elm.style.top = yOffset + 'px';
	elm.style.position = 'absolute';
}

function createShadow(elm)
{
	var shadow;
	try {
		shadow = document.createElement('<div class="shadow"></div>');
	}
	catch (e) {
		shadow = document.createElement('div');
		shadow.className = 'shadow';
	}
	shadow.style.left = 0+'px';
	shadow.style.top = 0+'px';
	shadow.style.width = elm.offsetWidth+'px';
	shadow.style.height = elm.offsetHeight+'px';
	shadow.style.position = 'absolute';
	elm.appendChild(shadow);
}

function createBackground(elm, op)
{
	var opacity;
	try {
		opacity = document.createElement('<div class="opacity"></div>');
		if(op == null || !IsNumeric(op))
			op = 75;
		if(op < 1)
			op = op * 100;
	}
	catch (e) {
		opacity = document.createElement('div');
		opacity.className = 'opacity';
		if(op == '' || !IsNumeric(op))
			op = 0.75;
		while(op > 1)
			op = op / 10;
	}
	opacity.style.left = 0+'px';
	opacity.style.top = 0+'px';
	opacity.style.width = elm.offsetWidth+'px';
	opacity.style.height = elm.offsetHeight+'px';
	opacity.style.position = 'absolute';
	opacity.style.opacity = op;
	opacity.style.filter = "alpha(opacity="+op+")";
	if(elm.style.zIndex == '')
		elm.style.zIndex = 1;
	
	elm.appendChild(opacity);
}

function IsNumeric(sText)
{
	if(sText == null)
		return false;
	
	var ValidChars = "0123456789.";
	var IsNumber=true;
	var Char;

	for (i = 0; i < sText.length && IsNumber == true; i++) 
	{ 
		Char = sText.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) 
			IsNumber = false;
	}
	return IsNumber;  
}

function destroyElement(elm)
{
	elm.parentNode.removeChild(elm);
	elm = null;
}