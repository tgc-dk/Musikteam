// Update the ModPro lyrics
function update() {
	var _mydiv = document.getElementById('tekst_m_akk');

	var _oldtext = _mydiv.childNodes;
	for (j=0; j < _oldtext.length; j++) {
		_mydiv.removeChild(_oldtext[j]);
	}
	var newnode = document.createElement("div");

	// Get the rendered protext.
	newnode.innerHTML = RenderSong(document.getElementById('editArea').value, 
				document.getElementById('Titel').value, document.getElementById('Forfatter').value, 
				document.getElementById('commentOver').value, document.getElementById('commentUnder').value);
	_mydiv.appendChild(newnode);
}

// Switch between different layout-edit tabs
function Afstande()
{
	document.getElementById("Afstande").style.display="inline";
	document.getElementById("Afstande_menu").className="sub_menu_2";
	document.getElementById("Tekst_2").style.display="none";
	document.getElementById("Tekst_2_menu").className="sub_menu_1";
	document.getElementById("Overskrifter").style.display="none";
	document.getElementById("Overskrifter_menu").className="sub_menu_1";
	document.getElementById("Kommentarer").style.display="none";
	document.getElementById("Kommentarer_menu").className="sub_menu_1";
	document.getElementById("Gem_layout").style.display="none";
	document.getElementById("Gem_layout_menu").className="sub_menu_1";
}

function Tekst_2()
{
	
	document.getElementById("Afstande").style.display="none";
	document.getElementById("Afstande_menu").className="sub_menu_1";
	document.getElementById("Tekst_2").style.display="inline";
	document.getElementById("Tekst_2_menu").className="sub_menu_2";
	document.getElementById("Overskrifter").style.display="none";
	document.getElementById("Overskrifter_menu").className="sub_menu_1";
	document.getElementById("Kommentarer").style.display="none";
	document.getElementById("Kommentarer_menu").className="sub_menu_1";
	document.getElementById("Gem_layout").style.display="none";
	document.getElementById("Gem_layout_menu").className="sub_menu_1";
}

function Overskrifter()
{
	document.getElementById("Afstande").style.display="none";
	document.getElementById("Afstande_menu").className="sub_menu_1";
	document.getElementById("Tekst_2").style.display="none";
	document.getElementById("Tekst_2_menu").className="sub_menu_1";
	document.getElementById("Overskrifter").style.display="inline";
	document.getElementById("Overskrifter_menu").className="sub_menu_2";
	document.getElementById("Kommentarer").style.display="none";
	document.getElementById("Kommentarer_menu").className="sub_menu_1";
	document.getElementById("Gem_layout").style.display="none";
	document.getElementById("Gem_layout_menu").className="sub_menu_1";
}

function Kommentarer()
{
	document.getElementById("Afstande").style.display="none";
	document.getElementById("Afstande_menu").className="sub_menu_1";
	document.getElementById("Tekst_2").style.display="none";
	document.getElementById("Tekst_2_menu").className="sub_menu_1";
	document.getElementById("Overskrifter").style.display="none";
	document.getElementById("Overskrifter_menu").className="sub_menu_1";
	document.getElementById("Kommentarer").style.display="inline";
	document.getElementById("Kommentarer_menu").className="sub_menu_2";
	document.getElementById("Gem_layout").style.display="none";
	document.getElementById("Gem_layout_menu").className="sub_menu_1";
}

function Gem_layout()
{
	document.getElementById("Afstande").style.display="none";
	document.getElementById("Afstande_menu").className="sub_menu_1";
	document.getElementById("Tekst_2").style.display="none";
	document.getElementById("Tekst_2_menu").className="sub_menu_1";
	document.getElementById("Overskrifter").style.display="none";
	document.getElementById("Overskrifter_menu").className="sub_menu_1";
	document.getElementById("Kommentarer").style.display="none";
	document.getElementById("Kommentarer_menu").className="sub_menu_1";
	document.getElementById("Gem_layout").style.display="inline";
	document.getElementById("Gem_layout_menu").className="sub_menu_2";
}

// Switch between text and layout edit tabs
function slides_edit()
{
	document.getElementById("slides_input").style.display="inline";
	document.getElementById("text_input").style.display="none";
	document.getElementById("layout_edit").style.display="none";
	document.getElementById("modpro_txt").style.display="none";

	document.getElementById("slides_img").src = "img/slides_down.gif";
	document.getElementById("text_img").src = "img/tekst_up.gif";
	//document.getElementById("layout_img").src = "img/layout_up.gif";
	
}
function text_input()
{
	document.getElementById("text_input").style.display="inline";
	document.getElementById("layout_edit").style.display="none";
	document.getElementById("slides_input").style.display="none";
	document.getElementById("modpro_txt").style.display="inline";

	document.getElementById("text_img").src = "img/tekst_down.gif";
	//document.getElementById("layout_img").src = "img/layout_up.gif";
	document.getElementById("slides_img").src = "img/slides_up.gif";
	
}
function layout_edit()
{
	document.getElementById("text_input").style.display="none";
	document.getElementById("slides_input").style.display="none";
	document.getElementById("layout_edit").style.display="inline";
	document.getElementById("modpro_txt").style.display="inline";

	document.getElementById("text_img").src = "img/tekst_up.gif";
	//document.getElementById("layout_img").src = "img/layout_down.gif";
	document.getElementById("slides_img").src = "img/slides_up.gif";

	Afstande();
}

// When page is loaded this makes sure the modpro lyrics is rendered and that only the modpro edit window is shown
function first_load()
{
	text_input();
	update();

	// setup the message that pops up on exit
	window.onbeforeunload = beforeunload;

	// setup shortcut keys
	// Vertical dash
	shortcut.add("Ctrl+Alt+D",function() {
		insertAtCursor(document.getElementById('editArea'),'|');
	});
	// Vertical square brackets
	shortcut.add("Ctrl+Alt+T",function() {
		insertAtCursor(document.getElementById('editArea'),'[]');
	});
}

function beforeunload()
{
	msg = "Hvis du lukker dette vindue vil alt som ikke er blevet gemt blive slettet!";
	return msg;
}

// Copies tekst from the slides to the modpro edit area
function copyFromSlides()
{
	doit = true;
	if (trim(document.getElementById('editArea').value) != "")
	{
		doit = confirm('Dette vil slette al eksisterende sangtekst, er du sikker?');
	}
	
	if (doit) {
		text = "";
		if (document.getElementById('slideA').value != "") text += document.getElementById('slideA').value + "\n\n";
		if (document.getElementById('slideB').value != "") text += document.getElementById('slideB').value + "\n\n";
		if (document.getElementById('slideC').value != "") text += document.getElementById('slideC').value + "\n\n";
		if (document.getElementById('slideD').value != "") text += document.getElementById('slideD').value + "\n\n";
		if (document.getElementById('slideE').value != "") text += document.getElementById('slideE').value + "\n\n";
		if (document.getElementById('slideF').value != "") text += document.getElementById('slideF').value + "\n\n";
		if (document.getElementById('slideG').value != "") text += document.getElementById('slideG').value + "\n\n";
		if (document.getElementById('slideH').value != "") text += document.getElementById('slideH').value + "\n\n";

		document.getElementById('editArea').value = text;
		update();
	}
	
	
}

function extractLyrics()
{
	$txt = document.getElementById('editArea').value;
	$newtxt = "";
	for ($i=0; $i < $txt.length; $i++) {
		if ($txt.charAt($i) == "|") {
			$newtxt +=  " ";
		} else if ($txt.charAt($i) == "[") {
			$i++;
			while ($txt.charAt($i) != "]" && $i < $txt.length) {
				$i++;
			}
		} else {
			$newtxt += $txt.charAt($i);
		}
	}
	// TODO: Remove duoble whitespaces
	return $newtxt;
}

// Copies tekst from the the modpro edit area to the first slide, the modpro stuff is stripped from the text
function copyToSlides()
{
	$doit = true;
	if ((trim(document.getElementById('slideA').value) != "") || (trim(document.getElementById('slideB').value) != "") 
		|| (trim(document.getElementById('slideC').value) != "") || (trim(document.getElementById('slideD').value) != "") 
		|| (trim(document.getElementById('slideE').value) != "") || (trim(document.getElementById('slideF').value) != "") 
		|| (trim(document.getElementById('slideG').value) != "") || (trim(document.getElementById('slideH').value) != ""))
	{
		$doit = confirm('Dette vil slette al eksisterende tekst i slidesne, er du sikker?');
	}

	if ($doit && trim(document.getElementById('editArea').value) != "") {
		document.getElementById('slideA').value = extractLyrics();
		document.getElementById('slideB').value = "";
		document.getElementById('slideC').value = "";
		document.getElementById('slideD').value = "";
		document.getElementById('slideE').value = "";
		document.getElementById('slideF').value = "";
		document.getElementById('slideG').value = "";
		document.getElementById('slideH').value = "";
	}
	
}

function createXmlHttpRequestObj()
{
	// Create the XMLHttpRequest object used for communication with the server
	var xhr = false;

	// try native XMLHttpRequest object
	if(window.XMLHttpRequest && !(window.ActiveXObject)) {
		try {
			xhr = new XMLHttpRequest();
		} catch(e) {
			xhr = false;
		}
	// IE/Windows ActiveX version
	} else if(window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e) {
				xhr = false;
			}
		}
	}
	return xhr;
}
function saveSlides()
{
	if (trim(document.getElementById('Titel').value) == "") {
		alert('Sangen skal have en titel for at kunne blive gemt!');
		return;
	}

	if (trim(document.getElementById('slides').value) == "") {
		alert('Der skal indtastes en afspilningsrækkefølge for slidesne, f.eks.: "ABCBC".');
	} else {

		// Create the XMLHttpRequest object used for communication with the server
		var xhr = createXmlHttpRequestObj();
		
		if (!xhr) {
			alert('Din browser understøtter ikke XmlHttpRequest!');
			return;
		}
		
		// Set the callback to handle the answer
		xhr.onreadystatechange  = function()
		{ 
			if (xhr.readyState  == 4) {
				if (xhr.status  == 200) {
					if (xhr.responseText == "done") {
						alert('De ændrede slides er blevet gemt.');
					} else if (pos = xhr.responseText.indexOf('done:') >= 0) {
						songID = parseInt(xhr.responseText.substr(5));
						alert('Slidesne er blevet gemt.');
					} else {
						alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
					}
				} else {
					alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
				}
			}
		};

		// Gather the data
		var text = "type=slides&";
		text += "id=" + songID + "&";
		text += "playlist=" + trim(document.getElementById('slides').value) + "&";
		text += "slideA=" + document.getElementById('slideA').value + "&";
		text += "slideB=" + document.getElementById('slideB').value + "&";
		text += "slideC=" + document.getElementById('slideC').value + "&";
		text += "slideD=" + document.getElementById('slideD').value + "&";
		text += "slideE=" + document.getElementById('slideE').value + "&";
		text += "slideF=" + document.getElementById('slideF').value + "&";
		text += "slideG=" + document.getElementById('slideG').value + "&";
		text += "slideH=" + document.getElementById('slideH').value;
		
		if (songID == -1) {
			text += "&title=" + trim(document.getElementById('Titel').value);
		}

		// Send the data to the server
		xhr.open("POST", "saveSongData.php",  true);
		xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
		xhr.send(text);

	}
}

function saveLyrics()
{
	if (trim(document.getElementById('Titel').value) == "") {
		alert('Sangen skal have en titel for at kunne blive gemt!');
		return;
	}

	// Create the XMLHttpRequest object used for communication with the server
	var xhr = createXmlHttpRequestObj();
	
	if (!xhr) {
		alert('Din browser understøtter ikke XmlHttpRequest!');
		return;
	}
	
	// Set the callback to handle the answer
	xhr.onreadystatechange  = function()
	{ 
		if (xhr.readyState  == 4) {
			if (xhr.status  == 200) {
				if (xhr.responseText == "done") { 
					alert('Den ændrede sangtekst er blevet gemt.');
				} else if (pos = xhr.responseText.indexOf('done:') >= 0) {
					songID = parseInt(xhr.responseText.substr(5));
					alert('Sangteksten er blevet gemt.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Gather the data
	var text = "type=lyrics&";
	text += "id=" + songID + "&";
	text += "lyrics=" + document.getElementById('editArea').value + "&";
	text += "commentOver=" + document.getElementById('commentOver').value + "&";
	text += "commentUnder=" + document.getElementById('commentUnder').value;

	if (songID == -1) {
		text += "&title=" + trim(document.getElementById('Titel').value);
	}

	// Send the data to the server
	xhr.open("POST", "saveSongData.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send(text);

}

function saveMeta()
{
	if (trim(document.getElementById('Titel').value) =="") {
		alert('Du skal som minimum indtaste en titel!');
		return;
	}

	// Create the XMLHttpRequest object used for communication with the server
	var xhr = createXmlHttpRequestObj();
	
	if (!xhr) {
		alert('Din browser understøtter ikke XmlHttpRequest!');
		return;
	}
	
	// Set the callback to handle the answer
	xhr.onreadystatechange  = function()
	{ 
		if (xhr.readyState  == 4) {
			if (xhr.status  == 200) {
				if (xhr.responseText == "done") { 
					alert('De ændrede stamdata er blevet gemt.');
				} else if (pos = xhr.responseText.indexOf('done:') >= 0) {
					songID = parseInt(xhr.responseText.substr(5));
					alert('Stamdataen er blevet gemt.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Gather the data
	var text = "type=meta&";
	text += "id=" + songID + "&";
	text += "title=" + trim(document.getElementById('Titel').value) + "&";
	text += "author=" + trim(document.getElementById('Forfatter').value) + "&";
	text += "edition=" + trim(document.getElementById('Udgave').value) + "&";
	text += "source=" + trim(document.getElementById('Kilde').value) + "&";
	text += "file=" + trim(document.getElementById('file2').value);


	// Send the data to the server
	xhr.open("POST", "saveSongData.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send(text);

}

function fixAmp(text)
{
	text = text.replace(" & "," og ");
	text = text.replace(" &"," og ");
	text = text.replace("& "," og ");
	text = text.replace("&"," og ");
	return text;
}

function saveAll()
{
	if (trim(document.getElementById('Titel').value) =="") {
		alert('Du skal som minimum indtaste en titel!');
		return;
	}

	if (songID != -1) {
		doit = confirm('Dette vil overskrive tidligere tekst m. akkorder og slides, er du sikker på at du vil fortsætte?');
		if (!doit) return;
	}

	// Create the XMLHttpRequest object used for communication with the server
	var xhr = createXmlHttpRequestObj();
	
	if (!xhr) {
		alert('Din browser understøtter ikke XmlHttpRequest!');
		return;
	}
	
	// Set the callback to handle the answer
	xhr.onreadystatechange  = function()
	{ 
		if (xhr.readyState  == 4) {
			if (xhr.status  == 200) {
				if (xhr.responseText == "done") { 
					alert('De ændrede data er blevet gemt.');
				} else if (pos = xhr.responseText.indexOf('done:') >= 0) {
					songID = parseInt(xhr.responseText.substr(5));
					alert('Dataen er blevet gemt.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Gather the data
	var text = "type=all&";
	text += "id=" + songID + "&";
	text += "title=" + fixAmp(trim(document.getElementById('Titel').value)) + "&";
	text += "author=" + fixAmp(trim(document.getElementById('Forfatter').value)) + "&";
	text += "edition=" + fixAmp(trim(document.getElementById('Udgave').value)) + "&";
	text += "source=" + fixAmp(trim(document.getElementById('Kilde').value)) + "&";
	var file = "";
	try {
		file = document.getElementById('audiofile').getElementsByTagName("a")[0].href;
	} catch (e) {
	}
	text += "file=" + file + "&";
	text += "lyrics=" + fixAmp(document.getElementById('editArea').value) + "&";
	text += "commentOver=" + fixAmp(document.getElementById('commentOver').value) + "&";
	text += "commentUnder=" + fixAmp(document.getElementById('commentUnder').value) + "&";
	text += "playlist=" + trim(document.getElementById('slides').value) + "&";
	text += "slideA=" + fixAmp(document.getElementById('slideA').value) + "&";
	text += "slideB=" + fixAmp(document.getElementById('slideB').value) + "&";
	text += "slideC=" + fixAmp(document.getElementById('slideC').value) + "&";
	text += "slideD=" + fixAmp(document.getElementById('slideD').value) + "&";
	text += "slideE=" + fixAmp(document.getElementById('slideE').value) + "&";
	text += "slideF=" + fixAmp(document.getElementById('slideF').value) + "&";
	text += "slideG=" + fixAmp(document.getElementById('slideG').value) + "&";
	text += "slideH=" + fixAmp(document.getElementById('slideH').value);
	

	// Send the data to the server
	xhr.open("POST", "saveSongData.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send(text);

}

function copyToNew()
{
	songID = -1;
	alert('Kopiering gennemført, brug evt. "Udgave" feltet til at kende forskel på den nye og den gamle version.');
}

function deleteSong()
{
	if (songID == -1) {
		alert('Sangen er ikke blevet gemt endnu, og kan derfor ikke slettes');
		return;
	}

	doit = confirm('Dette vil slette denne sang og lukke vinduet. Hvis sangen er brugt i nogen playlister kan det give problemer.');
	if (doit == true) {
		// Create the XMLHttpRequest object used for communication with the server
		var xhr = createXmlHttpRequestObj();
		
		if (!xhr) {
			alert('Din browser understøtter ikke XmlHttpRequest!');
			return;
		}
		
		// Set the callback to handle the answer
		xhr.onreadystatechange  = function()
		{ 
			if (xhr.readyState  == 4) {
				if (xhr.status  == 200) {
					if (xhr.responseText != "done") { 
						alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
					} else {
						window.onbeforeunload = "";
						window.focus();
						window.close();
					}
				} else {
					alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
				}
			}
		};

		// Gather the data
		var text = "type=delete&id=" + songID;

		// Send the data to the server
		xhr.open("POST", "saveSongData.php",  true);
		xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
		xhr.send(text);
	}

}

var printWin;

function printSong()
{
	// open the print window
	var w=800;
	var h=screen.height-100;
//	var winl = (screen.width - w) / 2;
//	var wint = (screen.height - h) / 10;
	var winl = screen.width / 3;
	var wint = screen.height / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=yes,resizable';
	printWin = window.open("printSong.html", "printWin", winprops);
}

// Called from the new print-window, and fills it with the current song.
function fillPrintWindow()
{
	printWin.insertSong(document.getElementById('tekst_m_akk').innerHTML);

	printWin.focus();
}

// Returns the index where the "raw" tone and the tone extension meet.
// -1 is returned if no extensions is found.
function getToneExtensionSplit(tone)
{
	if (tone.length < 2) return -1;

	if (tone.charAt(1) != '#' && tone.charAt(1) != 'b') return 1;
	
	if (tone.length > 2) return 2;
	else return -1;
}

// Returns a new tone, half a tone higher than the given one
function getHigherTone(tone)
{
	// Check for empty tone
	if (tone == '') return '';

	// Extract the extensions on this tone, if it exists
	var toneEx = '';
	toneSplit = getToneExtensionSplit(tone);
	if (toneSplit > -1 ) {
		toneEx = tone.substr(toneSplit);
		tone = tone.substr(0, toneSplit);
	}

	var newtone = "";

	switch(tone) {
		case 'C':
			newtone = 'C#';
			break;
		case 'C#':
		case 'Db':
			newtone = 'D';
			break;
		case 'D':
			newtone = 'Eb';
			break;
		case 'D#':
		case 'Eb':
			newtone = 'E';
			break;
		case 'E':
			newtone = 'F';
			break;
		case 'F':
			newtone = 'F#';
			break;
		case 'F#':
		case 'Gb':
			newtone = 'G';
			break;
		case 'G':
			newtone = 'G#';
			break;
		case 'G#':
		case 'Ab':
			newtone = 'A';
			break;
		case 'A':
			newtone = 'Bb';
			break;
		case 'A#':
		case 'Bb':
			newtone = 'H';
			break;
		case 'H':
			newtone = 'C';
			break;
		case '-':
			newtone = '-';
			break;
		default:
			return 'ERROR';
	}

	return (newtone + toneEx);
}

// Returns a new tone, half a tone lower than the given one
function getLowerTone(tone)
{
	// Check for empty tone
	if (tone == '') return '';

	// Extract the extensions on this tone, if it exists
	var toneEx = '';
	toneSplit = getToneExtensionSplit(tone);
	if (toneSplit > -1 ) {
		toneEx = tone.substr(toneSplit);
		tone = tone.substr(0, toneSplit);
	}

	var newtone = '';

	switch(tone) {
		case 'H':
			newtone = 'Bb';
			break;
		case 'A#':
		case 'Bb':
			newtone = 'A';
			break;
		case 'A':
			newtone = 'G#';
			break;
		case 'G#':
		case 'Ab':
			newtone = 'G';
			break;
		case 'G':
			newtone = 'F#';
			break;
		case 'F#':
		case 'Gb':
			newtone = 'F';
			break;
		case 'F':
			newtone = 'E';
			break;
		case 'E':
			newtone = 'Eb';
			break;
		case 'D#':
		case 'Eb':
			newtone = 'D';
			break;
		case 'D':
			newtone = 'C#';
			break;
		case 'C#':
		case 'Db':
			newtone = 'C';
			break;
		case 'C':
			newtone = 'H';
			break;
		case '-':
			newtone = '-';
			break;
		default:
			return 'ERROR';
	}

	return (newtone + toneEx);
}

// Used to raise or lower the tones in the editArea
function changeTone(mode)
{
	// This is where we put the lyrics with the changed tones
	var newlyrics = '';

	var oldlyrics = document.getElementById('editArea').value;

	lastpos = 0;
	while (true) {
		// Find a tone like this: [C]
		toneBegin = oldlyrics.indexOf('[', lastpos);
		if (toneBegin < 0) {
			newlyrics += oldlyrics.substr(lastpos);
			break;
		}
		toneEnd = oldlyrics.indexOf(']', toneBegin);
		if (toneEnd < 0) {
			newlyrics += oldlyrics.substr(lastpos);
			break;
		}
		tone = oldlyrics.substr(toneBegin+1, toneEnd-toneBegin - 1);

		orgTone = tone;

		// Check if the tone contains a bass tone
		bassTone = '';
		bassSep = tone.indexOf('/');
		newBassTone = '';
		if (bassSep >= 0) {
			bassTone = tone.substr(bassSep+1);
			tone = orgTone.substr(0, bassSep);

			// Get the new higher or lower bass tone
			if (mode == 'higher') newBassTone = getHigherTone(trim(bassTone));
			else newBassTone = getLowerTone(trim(bassTone));

			if (newBassTone == 'ERROR') {
				alert('Der opstod en fejl omkring tonen: ' + orgTone + '! Er du sikker på at du har skrevet tonerne korrekt?');
				return;
			}
		}

		// Get the new higher or lower tone
		newTone = '';
		if (mode == 'higher') newTone = getHigherTone(trim(tone));
		else newTone = getLowerTone(trim(tone));

		if (newTone == 'ERROR') {
			alert('Der opstod en fejl omkring tonen: ' + orgTone + '! Er du sikker på at du har skrevet tonerne korrekt?');
			return;
		}

		newlyrics += oldlyrics.substr(lastpos, toneBegin - lastpos) + '[' + newTone;
		if (bassSep >= 0) newlyrics += '/' + newBassTone;
		newlyrics += ']';

		lastpos = toneEnd+1;
	}

	document.getElementById('editArea').value = newlyrics;
	update();
}

// Insert text at cursors position in the given field
function insertAtCursor(myField, myValue) {
	//IE support
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);

		myField.selectionStart = startPos+1;
		myField.selectionEnd = startPos+1;
	} else {
		myField.value += myValue;
	}
}

// Hides or show the popup-help window
function ShowPop(id)
{
   document.getElementById(id).style.visibility = "visible";
   document.getElementById("Btn1").style.display="none";
}
function HidePop(id)
{
   document.getElementById(id).style.visibility = "hidden";
   document.getElementById("Btn1").style.display="inline";
}

// Opens a window where the user can upload a audiofile
function uploadAudio()
{
	if (songID==-1) {
		alert("Sangen skal gemmes før du kan uploade en lydfil");
		return;
	}
	if (document.getElementById('audiofile').innerHTML.indexOf('/musik/') != -1) {
		doit = confirm('Dette vil slette den eksisterende fil, er du sikker?');
		if (!doit) return;
	}
	// open the upload window
	var w=300;
	var h=100;
	var winl = screen.width / 3;
	var wint = screen.height / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=no,resizable';
	audioWin = window.open("audiofile.php?mode=upload&songid="+songID, "audioWin", winprops);
}

// Opens a window where the user enter an URL to an audiofile
function giveAudioURL()
{
	var oldurl = "";
	if (document.getElementById('audiofile').innerHTML.indexOf('/musik/') != -1) {
		doit = confirm('Dette vil slette den eksisterende fil, er du sikker?');
		if (!doit) return;
	} else {
		try {
			orlurl = document.getElementById('audiofile').getElementsByTagName("a")[0].href;
		} catch (e) {
		}
	}
	// open the upload window
	var w=300;
	var h=100;
	var winl = screen.width / 3;
	var wint = screen.height / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=no,resizable';
	audioWin = window.open("audiofile.php?mode=url&songid="+songID+"&oldurl="+oldurl, "audioWin", winprops);
}

// Called from the new-audiofile-window
function updateAudioFile(url)
{
	document.getElementById('audiofile').innerHTML = '<a href="'+url+'" target="_new" style="text-decoration:none">Lyt:&nbsp; <img src="img/mp3.gif" alt="Lyt" border="0"></a>'; 
}

// Open the chords-converter-window
var chordsWin;
function chordConverter()
{
	// open the chord window
	var w=screen.width-50;
	var h=screen.height-100;
	winprops = 'height='+h+',width='+w+',scrollbars=yes,resizable';
	chordsWin = window.open("chordConverter.html", "chordsWin", winprops);
}

function insertNewChords(chords)
{
	doit = confirm('Dette vil overskrive de eksisterende, er du sikker?');
	if (!doit) return -1;

	//alert(chords);
	
	document.getElementById('editArea').value = chords;
	return 0;
}

// Called from the new chords-window, and fills it with the current chords.
function getChords()
{
	chordsWin.insertChords(document.getElementById('editArea').value);
	chordsWin.focus();
}
