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
// Switch between different team tabs
function Teams()
{
	document.getElementById("Teams").style.display="inline";
	document.getElementById("Teams_menu").className="sub_menu_2";
	document.getElementById("Personer").style.display="none";
	document.getElementById("Personer_menu").className="sub_menu_1";
	document.getElementById("Evner").style.display="none";
	document.getElementById("Evner_menu").className="sub_menu_1";
}

function Personer()
{
	document.getElementById("Teams").style.display="none";
	document.getElementById("Teams_menu").className="sub_menu_1";
	document.getElementById("Personer").style.display="inline";
	document.getElementById("Personer_menu").className="sub_menu_2";
	document.getElementById("Evner").style.display="none";
	document.getElementById("Evner_menu").className="sub_menu_1";
}

function Evner()
{
	document.getElementById("Teams").style.display="none";
	document.getElementById("Teams_menu").className="sub_menu_1";
	document.getElementById("Personer").style.display="none";
	document.getElementById("Personer_menu").className="sub_menu_1";
	document.getElementById("Evner").style.display="inline";
	document.getElementById("Evner_menu").className="sub_menu_2";
}

function createXMLParser(text)
{
	// code for IE
	if (window.ActiveXObject) {
		var doc=new ActiveXObject("Microsoft.XMLDOM");
		doc.async="false";
		doc.loadXML(text);
	} else {
	// code for Mozilla, Firefox, Opera, etc.
		var parser=new DOMParser();
		var doc=parser.parseFromString(text,"text/xml");
	}
	return doc;
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

function parsePersonData(xmltext)
{
	document.personform.reset();

	// Parse the input XML and put the data into the correct input-element
	xmldoc = createXMLParser(xmltext);
	try {
		document.getElementById("personID").value = xmldoc.getElementsByTagName("id")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("fornavn").value = xmldoc.getElementsByTagName("firstName")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("efternavn").value = xmldoc.getElementsByTagName("lastName")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("adresse").value = xmldoc.getElementsByTagName("address1")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("adresse2").value = xmldoc.getElementsByTagName("address2")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("tlf").value = xmldoc.getElementsByTagName("phone")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("mobil").value = xmldoc.getElementsByTagName("mobile")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("email").value = xmldoc.getElementsByTagName("email")[0].childNodes[0].nodeValue;
	} catch(e) {
	}

	i = 0;
	while(true) {
		try {
			ability = xmldoc.getElementsByTagName("ability")[i].childNodes[0].nodeValue;
			index = "ability"+ability;

			if (index == "") continue;
			document.getElementById(index).checked = true;
		} catch(e) {
			break;
		}
		i++;
	}
	document.getElementById("edit_person").style.display="inline";
}

function editPerson(personID)
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
				parsePersonData(xhr.responseText);
				//alert(xhr.responseText);
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};
	
	// Send the data to the server
	xhr.open("POST", "getPerson.php", true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send("id="+personID);
}

function newPerson()
{
	document.getElementById("edit_person").style.display="inline";
	document.getElementById("personID").value = "-1";
	document.personform.reset();
}

function deletePerson()
{
	if (document.getElementById("personID").value == "-1") {
		alert('Personen er ikke gemt, og kan derfor ikke slettes');
		return false;
	}

	doit = confirm('Dette vil slette denne person, er du sikker?');
	if (doit == true) {
		document.personform.action = "main.php?page=teams&subpage=persons&action=delete";
		document.personform.submit();
		return true;
	} else {
		return false;
	}
}

function newAbility()
{
	ability=prompt("Indtast den nye evne.","");
	if (ability == "") return false;
	document.getElementById("newability").value = ability;
	document.newabilityform.submit();
	return true;
}

function deleteAbility(id)
{
	index = "abilityform"+id;
	document.getElementById(index).action += "&action=delete";
	return true;
}

function newTeam()
{
	document.getElementById("edit_team").style.display="inline";
	document.getElementById("teamID").value = "-1";
	document.teamform.reset();
}

function deleteTeam()
{
	if (document.getElementById("teamID").value == "-1") {
		alert('Teamet er ikke gemt, og kan derfor ikke slettes');
		return false;
	}

	doit = confirm('Dette vil slette dette team (ikke personerne), er du sikker?');
	if (doit == true) {
		document.teamform.action = "main.php?page=teams&subpage=teams&action=delete";
		document.teamform.submit();
		return true;
	} else {
		return false;
	}
	
}

function parseTeamData(xmltext)
{
	document.teamform.reset();

	// Parse the input XML and put the data into the correct input-element
	xmldoc = createXMLParser(xmltext);
	try {
		document.getElementById("teamID").value = xmldoc.getElementsByTagName("id")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("teamnavn").value = xmldoc.getElementsByTagName("teamName")[0].childNodes[0].nodeValue;
	} catch(e) {
	}
	try {
		document.getElementById("teamdescr").value = xmldoc.getElementsByTagName("description")[0].childNodes[0].nodeValue;
	} catch(e) {
	}

	i = 0;
	while(true) {
		try {
			ability = xmldoc.getElementsByTagName("member")[i].childNodes[0].nodeValue;
			index = "member"+ability;

			if (index == "") continue;
			document.getElementById(index).checked = true;
		} catch(e) {
			break;
		}
		i++;
	}
	document.getElementById("edit_team").style.display="inline";
}

function editTeam(teamID)
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
				parseTeamData(xhr.responseText);
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Send the data to the server
	xhr.open("POST", "getTeam.php", true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send("id="+teamID);

}