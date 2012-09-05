<?php

session_start();
setcookie("PHPSESSID",$_COOKIE['PHPSESSID'],time()+1800);

if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	openDB();

	$names = array();
	// Get the emails to send to
	$personcount = $_POST['personcount'];
	$to = "";
	for ($count = 0; $count < $personcount; $count++) {
		$index = 'Person'.$count;
		$personid = $_POST[$index];
		$query = "SELECT Mail,Fornavn,Efternavn FROM Person WHERE PersonID=" . $personid;
		$result = doSQLQuery($query);
		$line = db_fetch_array($result);
		$tmp = current($line);
		if (isset($tmp) && trim($tmp) != "") $to .= $tmp. ", ";
		$names[$count] = next($line). " ". next($line);
	}
	if ($to != "") $to = substr($to, 0, $to.Length - 2);

	// Create the content of the mail
	$body = "Hej!\n\nDu modtager denne mail fordi er sat på til at spille til ".$_POST['eventName']."\n\n".
			"Setliste:\n";

	// List the songs
	$songcount = $_POST['songcount'];
	for ($count = 0; $count < $songcount; $count++) {
		$songIndex = 'Song'.$count;
		$headIndex = 'Heading'.$count;
		$songname = $_POST[$songIndex];
		$heading = $_POST[$headIndex];
		$body .= $songname;
		if ($heading != "") $body .= "   (".$heading.")";
		$body .= "\n";
	}

	// List the band
	$body .= "\nBand:\n";
	for ($count = 0; $count < $personcount; $count++) {
		$index = 'Role'.$count;
		$role = $_POST[$index];
		$body .= $names[$count];
		if ($role != "") $body .= "  (".$role.")";
		$body .= "\n";
	}
	$body .= "\nDu kan se setlisten og udskrive sangblad samt sangene m akkorder på http://".$ROOT_URL."main.php?page=program&eventId=".$_POST['eventid']."\n";

	$body .= "\nMvh\n\t Københavnerkirkens Musikteam\n\n\nPS. Dette er en automatisk genereret e-mail.\n";

	// Get email-address of sender
	$username = $_POST['username'];
	$query = "SELECT Email FROM Bruger WHERE Brugernavn='" . $username. "'";
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$email = current($line);

	// Send an email with the sellist to the band to the band
	$subject = "Setliste til ".$_POST['eventName'];
	$headers = 'From: ' . $email . "\r\n" .
		'Reply-To: ' . $email . "\r\n" .
		'X-Mailer: PHP/' . phpversion().  "\r\n" .
		"MIME-Version: 1.0\r\n" .
		"Content-type: text/plain; charset=utf-8\r\n" .
		"Content-Transfer-Encoding: quoted-printable\r\n";

	if (mail($to, $subject, $body, $headers)) {
		echo "done";
	} else {
		echo("Der blev ikke sendt en mail til bandet pga. fejl. Prøv igen. emails: ".$to);
	}
}
?>
