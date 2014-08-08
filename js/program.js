
// Current number of songs
var numSongs = 0;
// Current number of persons in the band
var numPersons = 0;
// The window used for printing songs
var printWin;
// The window used for printing the setlist
var printSetWin;

// Print all songs
function printSongs()
{
	// open the print window
	var w=800;
	var h=screen.height-100;
//	var winl = (screen.width - w) / 2;
//	var wint = (screen.height - h) / 10;
	var winl = screen.width / 3;
	var wint = screen.height / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=yes,resizable';
	printWin = window.open("printSongs.html", "printWin", winprops);
}

// Called from the new print-window, and fills it with the current listed songs.
function fillPrintWindow()
{
	printWin.setupPrint(true, numSongs);

	// Extract song ids
	var tbl = document.getElementById('setliste_table');
	for (var count = 0; count < numSongs; count++) {
		var tmp = tbl.rows[2+count].cells[0].innerHTML;
		printWin.insertSong(count,tmp.substr(89, tmp.indexOf(">", 89)-90));
	}

	printWin.focus();
}

// Print setlist
function printSetlist()
{
	// open the print window
	var w=800;
	var h=screen.height-100;
//	var winl = (screen.width - w) / 2;
//	var wint = (screen.height - h) / 10;
	var winl = screen.width / 3;
	var wint = screen.height / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=yes,resizable';
	printSetWin = window.open("printSetlist.html", "printSetWin", winprops);
}
// Called from the new printsetlist-window, and fills it with the current listed songs and band.
function fillPrintSetWindow()
{
	// Extract event title
	var tbl = document.getElementById('setliste_table');
	var tmp = tbl.rows[0].cells[0].innerHTML;
	var eventTitle = tmp.substring(22,tmp.indexOf("<",22));
	
	printSetWin.setupPrint(numSongs, numPersons, eventTitle);

	// Extract song title and headings
	for (var count = 0; count < numSongs; count++) {
		song = tbl.rows[2+count].cells[0].getElementsByTagName("a")[0].innerHTML;
		heading = tbl.rows[2+count].cells[4].innerHTML;
		printSetWin.insertSong(song,heading);
	}

	// Extract names from band and selected role
	tbl = document.getElementById('band_table');
	for (var count = 0; count < numPersons; count++) {
		var person = tbl.rows[2+count].cells[0].getElementsByTagName("div")[0].innerHTML;
		var index1 = tbl.rows[2+count].cells[2].getElementsByTagName("select")[0].selectedIndex;
		var role = "";
		if (index1 != 0) role = tbl.rows[2+count].cells[2].getElementsByTagName("select")[0].options[index1].text;

		printSetWin.insertPerson(person,role);
	}

	printSetWin.focus();
}

// Delete the current event
function deleteEvent(eventid)
{
	doit = confirm('Dette vil slette det viste arrangement, er du sikker?');
	if (doit == true) {
		location.href='main.php?page=program&deleteEvent='+eventid;
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

// Save the changes to the event
function save(eventid)
{
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
					alert('Set liste og band er blevet gemt.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Gather the data
	var text = "";

	// Extract song ids and headings
	var tbl = document.getElementById('setliste_table');
	for (var count = 0; count < numSongs; count++) {
		text += "Song" + count + "=";

		var tmp = tbl.rows[2+count].cells[0].innerHTML;
		var start = tmp.indexOf("song=", 0)+5;
		var length = tmp.indexOf(">", start) - start - 1;
		text += tmp.substr(start, length) + "&";

		text += "Heading" + count + "=" + tbl.rows[2+count].cells[4].innerHTML + "&";
	}
	text += "songcount=" + count + "&";

	// Extract bandmember ids
	var tbl = document.getElementById('band_table');
	for (var count = 0; count < numPersons; count++) {
		text += "Person" + count + "=";
		text += tbl.rows[2+count].cells[0].getElementsByTagName("div")[0].id  + "&";

		text += "Rolle" + count + "=";
		text += tbl.rows[2+count].cells[2].getElementsByTagName("select")[0].value + "&";
	}
	text += "personcount=" + count + "&";

	text += "eventid=" + eventid;

	// Send the data to the server
	xhr.open("POST", "saveProgram.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	xhr.send(text);

}

// Makes the iframe with the teams visible or invisible
function togglePersonIframe()
{
	var itd = document.getElementById('personIframeTd');
	var simg = document.getElementById('addPersonImg');
	if (itd.innerHTML == "") { 
		itd.innerHTML = '<iframe src="addPerson.php" width="100%" height="400px" />';
		simg.src = 'img/minus.gif';
	} else {
		simg.src = 'img/list-add.gif';
		itd.innerHTML = '';
	}
}

// Add Person with given name, ebilities and id. This function is called from the addPerson iframe
function addPerson(name, abilities, abilitiesID, id)
{
	var tbl = document.getElementById('band_table');
	var rowIndex = numPersons + 2;
	try {
		var newRow = tbl.insertRow(rowIndex);

		if (numPersons % 2 == 1) {
			newRow.bgColor = '#F4F4F4';
		}

		var newCell = newRow.insertCell(0);
		//newCell.innerHTML = '<a onclick="NewWindow(this.href,\'person_win\',\'yes\');return false;" href="editPerson.php?person=' + id + '">' + name + '</a>';
		newCell.innerHTML = '<div id="' + id + '">' + name + '</div>';

		newCell = newRow.insertCell(1);
		newCell.innerHTML = '<a href="javascript:deletePerson(' + numPersons + ')"><img src="img/slet.gif" alt="Slet" width="16" height="16" border="0" /></a>';
		newCell.width = 17;

		newCell = newRow.insertCell(2);
		var abilitiesArr = abilities.split(", ");
		var abilitiesIDArr = abilitiesID.split(",");
		var selectBox = '<select name="selectAbility"><option value="-1">Vælg en rolle</option>';
		for (i=0; i<abilitiesArr.length; ++i) {
			selectBox += '<option value="'+abilitiesIDArr[i]+'">'+abilitiesArr[i]+'</option>';
		}
		selectBox +='</select>';
		newCell.innerHTML = selectBox;

		numPersons++
	} catch (ex) {
		//document.getElementById(txtError).value = ex;
	}
}

// Deletes the person on row+2 (argument is number of person in the list)
function deletePerson(row)
{
	// Delete a person/row
	var tbl = document.getElementById('band_table');
	try {
		tbl.deleteRow(row+2);
	} catch (ex) {
		//document.getElementById(txtError).value = ex;
	}

	numPersons--;

	// Update the remaining rows
	while (row < numPersons) {
		tbl.rows[2+row].cells[1].innerHTML = '<a href="javascript:deletePerson(' +row+ ')"><img src="img/slet.gif" alt="Slet" width="16" height="16" border="0" /></a>';

		if (row % 2 == 1) {
			tbl.rows[2+row].bgColor = '#F4F4F4';
		} else {
			tbl.rows[2+row].bgColor = '#FFFFFF';
		}
		row++
	}
}

// Makes the iframe with the song-search visible or invisible
function toggleSongIframe()
{
	var itd = document.getElementById('songIframeTd');
	var simg = document.getElementById('searchSongImg');
	if (itd.innerHTML == "") { 
		itd.innerHTML = '<iframe src="search-add.php" width="100%" height="300px" />';
		simg.src = 'img/minus.gif';
	} else {
		simg.src = 'img/list-add.gif';
		itd.innerHTML = '';
	}
}

// Deletes the song on row+2 (argument is number of song in the list)
function deleteSong(row)
{
	// Delete a song/row
	var tbl = document.getElementById('setliste_table');
	try {
		tbl.deleteRow(row+2);
	} catch (ex) {
		//document.getElementById(txtError).value = ex;
	}

	numSongs--;

	// Update the remaining rows
	while (row < numSongs) {
		tbl.rows[2+row].cells[1].innerHTML = '<a href="javascript:moveUp('+row+')"><img src="img/go-up.gif" alt="Flyt op" width="16" height="16" border="0" /></a>';
		tbl.rows[2+row].cells[2].innerHTML = '<a href="javascript:moveDown('+row+')"><img src="img/go-down.gif" alt="Flyt ned" width="16" height="16" border="0" /></a>';
		tbl.rows[2+row].cells[3].innerHTML = '<a href="javascript:deleteSong(' +row+ ')"><img src="img/slet.gif" alt="Slet" width="16" height="16" border="0" /></a>';
		tbl.rows[2+row].cells[4].rownum = row;

		// This is only needed for cells not create by javascript
		tbl.rows[2+row].cells[4].onclick = function(){editHeading(this.rownum);};

		if (row % 2 == 1) {
			tbl.rows[2+row].bgColor = '#F4F4F4';
		} else {
			tbl.rows[2+row].bgColor = '#FFFFFF';
		}
		row++
	}
}

// Add song with given title and id. This function is called from the search-add iframe
function addSong(name,id)
{
	var tbl = document.getElementById('setliste_table');
	var rowIndex = numSongs + 2;
	try {
		var newRow = tbl.insertRow(rowIndex);

		if (numSongs % 2 == 1) {
			newRow.bgColor = '#F4F4F4';
		}

		var newCell = newRow.insertCell(0);
		newCell.innerHTML = '<a onclick="NewWindow(this.href,\'song_win\',\'yes\');return false;" href="editSong.php?song=' + id + '">' + name + '</a>';

		newCell = newRow.insertCell(1);
		newCell.innerHTML = '<a href="javascript:moveUp('+numSongs+')"><img src="img/go-up.gif" alt="Flyt op" width="16" height="16" border="0" /></a>';
		newCell.width = 17;

		newCell = newRow.insertCell(2);
		newCell.innerHTML = '<a href="javascript:moveDown('+numSongs+')"><img src="img/go-down.gif" alt="Flyt ned" width="16" height="16" border="0" /></a>';
		newCell.width = 17;

		newCell = newRow.insertCell(3);
		newCell.innerHTML = '<a href="javascript:deleteSong(' + numSongs + ')"><img src="img/slet.gif" alt="Slet" width="16" height="16" border="0" /></a>';
		newCell.width = 17;

		newCell = newRow.insertCell(4);
		//newCell = '<td onclick="editHeading(' + numSongs + ');" />';
		newCell.innerHTML = '';
		newCell.rownum = numSongs;
		newCell.onclick = function(){editHeading(this.rownum);};

		numSongs++
	} catch (ex) {
		//document.getElementById(txtError).value = ex;
	}
}

// Move song at row+2 up if possible
function moveUp(row)
{
	// Already at the top, can get further up
	if (row == 0) return;

	var tbl = document.getElementById('setliste_table');
	// Move song title
	var tmpHTML = tbl.rows[2+row].cells[0].innerHTML;
	tbl.rows[2+row].cells[0].innerHTML = tbl.rows[1+row].cells[0].innerHTML;
	tbl.rows[1+row].cells[0].innerHTML = tmpHTML;

	// Move song heading
	var tmpHTML = tbl.rows[2+row].cells[4].innerHTML;
	tbl.rows[2+row].cells[4].innerHTML = tbl.rows[1+row].cells[4].innerHTML;
	tbl.rows[1+row].cells[4].innerHTML = tmpHTML;
}

// Move song at row+2 down if possible
function moveDown(row)
{
	// Already at the bottom, can get further down
	if (row == numSongs-1) return;

	var tbl = document.getElementById('setliste_table');
	// Move song title
	var tmpHTML = tbl.rows[2+row].cells[0].innerHTML;
	tbl.rows[2+row].cells[0].innerHTML = tbl.rows[3+row].cells[0].innerHTML;
	tbl.rows[3+row].cells[0].innerHTML = tmpHTML;

	// Move song heading
	var tmpHTML = tbl.rows[2+row].cells[4].innerHTML;
	tbl.rows[2+row].cells[4].innerHTML = tbl.rows[3+row].cells[4].innerHTML;
	tbl.rows[3+row].cells[4].innerHTML = tmpHTML;

}

// Edit the heading of the given song
function editHeading(row)
{
	var tbl = document.getElementById('setliste_table');
	var orgHeading = tbl.rows[2+row].cells[4].innerHTML;
	var newHeading = prompt("Indtast/rediger overskrift:", orgHeading);
	if (newHeading==null) return
	tbl.rows[2+row].cells[4].innerHTML = newHeading;
}

// Creates a PDF with the lyrics of the songs
function sangBlad(eventname)
{
	var text ="";
	// Extract song ids
	var tbl = document.getElementById('setliste_table');
	for (var count = 0; count < numSongs; count++) {
		text += "Song" + count + "=";
		var tmp = tbl.rows[2+count].cells[0].innerHTML;
		var start = tmp.indexOf("song=", 0)+5;
		var length = tmp.indexOf(">", start) - start - 1;
		text += tmp.substr(start, length) + "&";

		text += "Heading" + count + "=" + tbl.rows[2+count].cells[4].innerHTML + "&";
	}
	text += "songcount=" + count + "&eventName=" + eventname;
	window.location = "songs.php?" + text;
}

// Creates an OpenOffice Impress presentation with the song lyrics
function slides(eventname)
{
	var text ="";
	// Extract song ids
	var tbl = document.getElementById('setliste_table');
	for (var count = 0; count < numSongs; count++) {
		text += "Song" + count + "=";
		var tmp = tbl.rows[2+count].cells[0].innerHTML;
		var start = tmp.indexOf("song=", 0)+5;
		var length = tmp.indexOf(">", start) - start - 1;
		text += tmp.substr(start, length) + "&";
	}
	text += "songcount=" + count + "&eventName=" + eventname;
	window.location = "odp-handler/createSlides.php?" + text;
}

// Creates an OpenLP service file with the song lyrics
function openLP(eventname)
{
	var text ="";
	// Extract song ids
	var tbl = document.getElementById('setliste_table');
	for (var count = 0; count < numSongs; count++) {
		text += "Song" + count + "=";
		var tmp = tbl.rows[2+count].cells[0].innerHTML;
		var start = tmp.indexOf("song=", 0)+5;
		var length = tmp.indexOf(">", start) - start - 1;
		text += tmp.substr(start, length) + "&";
	}
	text += "songcount=" + count + "&eventName=" + eventname;
	window.location = "openlp/createServiceFile.php?" + text;
}

// Sends an email to the band with the setlist
function sendSetlist(eventid,eventname,username)
{
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
					alert('Setlisten er blevet sendt til bandet.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	var text ="eventName=" + eventname + "&eventid=" + eventid + "&username=" + username + "&";

	// Extract song
	var tbl = document.getElementById('setliste_table');
	for (var count = 0; count < numSongs; count++) {
		text += "Song" + count + "=";
		text += tbl.rows[2+count].cells[0].getElementsByTagName("a")[0].innerHTML + "&";
		text += "Heading" + count + "=" + tbl.rows[2+count].cells[4].innerHTML + "&";
	}
	text += "songcount=" + count + "&";

	// Extract bandmember ids
	var tbl = document.getElementById('band_table');
	for (var count = 0; count < numPersons; count++) {
		text += "Person" + count + "=";
		text += tbl.rows[2+count].cells[0].getElementsByTagName("div")[0].id  + "&";

		//text += tmp.substr(start, length) + "&";

		text += "Role" + count + "=";
		var index1 = tbl.rows[2+count].cells[2].getElementsByTagName("select")[0].selectedIndex;
		if (index1 != 0) text += tbl.rows[2+count].cells[2].getElementsByTagName("select")[0].options[index1].text;
		text += "&" + tbl.rows[2+count].cells[2].getElementsByTagName("select")[0].value + "&";
	}
	text += "personcount=" + count + "&";

	// Send the data to the server
	xhr.open("POST", "sendsetlist.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	xhr.send(text);

}

/**
 * DHTML date validation script for dd/mm/yyyy. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 */
// Declaring valid date character, minimum year and maximum year
var dtCh= "/";
var minYear=2000;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr) {
    if (dtStr === '') {
        alert("Indtast venligst en gyldig dato!");
        return false;
    }
    return true
}

function ValidateForm(value) {
    if (!isDate(value)) {
        return false
    }
    return true
 }

function EditDateTitle(id)
{
	var div;
	if(document.getElementById('divEditDateTitle'))
		div = document.getElementById('divEditDateTitle');
	else
	{
		try {
			div = document.createElement('<div id="divEditDateTitle"></div>');
		}
		catch (e) {
			div = document.createElement('div');
			div.setAttribute('id','divEditDateTitle');
		}

		document.body.appendChild(div);
		div.style.position = 'absolute';
		createBackground(div, 90);
		ajaxRequest(div.id,'program_ajax.php', 'mode=editDateTitle&programid='+id);
	}
	centerElm(div);
}

function retDatoTitle(id)
{
	createShadow(document.getElementById('divEditDateTitle'));
	ajaxRequest('divEditDateTitle','program_ajax.php', 'mode=saveDateTitle&programid='+id+'&arrangement='+document.getElementById('tbText').value+'&dato='+document.getElementById('tbDato').value);	
}



