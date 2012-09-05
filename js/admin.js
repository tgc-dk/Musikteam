
// Delete user
function deleteUser(userId)
{
	doit = confirm("Dette vil slette denne bruger, er du sikker?");
	if (doit) {
		window.location = "main.php?page=admin&deleteUser=" + userId;
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

// New password for the selected user
function newPassword(userNum, userId)
{
	doit = confirm("Dette vil generere et nyt kodeord til brugeren, og sende det til vedkommende via email, ønsker du at fortsætte?");
	if (!doit) return;

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
					alert('Et nyt kodeord blev sendt til brugeren.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Gather the data
	var text = "action=newPassword&userId=" + userId;
	text += "&email=" + document.getElementById('email'+userNum).value;

	// Send the data to the server
	xhr.open("POST", "admin-action.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send(text);
	
}

// Save changes to this user
function saveUser(userNum, userId)
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
					alert('Ændringer gemt.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Gather the data
	var text = "action=saveChanges&userId=" + userId;
	text += "&admin=" + document.getElementById('admin'+userNum).checked;
	text += "&email=" + document.getElementById('email'+userNum).value;
	
	// Send the data to the server
	xhr.open("POST", "admin-action.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send(text);
	
}

// delete template
function deleteTemplate(name)
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
					alert('Ændringer gemt.');
				} else {
					alert('Der opstod en server fejl! Fejl meddelelse: ' + xhr.responseText);
				}
			} else {
				alert('Der opstod en forbindelses fejl! Fejl meddelelse: ' + xhr.status);
			}
		}
	};

	// Gather the data
	var text = "action=deleteTemplate&templateName=" + userId;
	
	// Send the data to the server
	xhr.open("POST", "admin-action.php",  true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
	xhr.send(text);

}

