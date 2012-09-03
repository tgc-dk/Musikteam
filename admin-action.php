<?php
function generatePassword($length=9, $strength=0) {
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    if ($strength & 1) {
        $consonants .= 'BDGHJLMNPQRSTVWXZ';
    }
    if ($strength & 2) {
        $vowels .= "AEUY";
    }
    if ($strength & 4) {
        $consonants .= '23456789';
    }
    if ($strength & 8) {
        $consonants .= '@#$%';
    }

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}

session_start();


if (isset($_SESSION['logget_ind']) && isset($_SESSION['admin'])) {

	include("db.php");
	openDB();

	// Generate a new password for the user
	if ($_POST['action'] == "newPassword") {
		$newPassword = generatePassword();
		$query = "UPDATE Bruger SET Kode = '" . md5($newPassword."musikteam") .
			"' WHERE BrugerId = " . $_POST['userId'];
		$result = doSQLQuery($query);

		// Send an email to the new user with the username and password
		$to = $_POST['email'];
		$subject = "Ny kode til ".$ROOT_URL."!";
		$body = "Hej!\n\nDin kode til http://".$ROOT_URL.", er blevet ændret og er nu: \"".$newPassword."\"\n".
			"Dit brugernavn er stadig det samme.\n\n".
			"Mvh\n\t ".$WEBMASTER_NAME."\n\n\nPS. Dette er en automatisk genereret e-mail.\n";
		$headers = 'From: '.$WEBMASTER_EMAIL . "\r\n" .
			'Reply-To: '.$WEBMASTER_EMAIL . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		if (mail($to, $subject, $body, $headers)) {
			echo "done";
		} else {
			echo("Der blev ikke sendt en mail til den nye bruger pga. fejl.");
		}

	// Save changed email and admin-status
	} else if ($_POST['action'] == "saveChanges") {
		if ($_POST['admin'] == "true") $admin = 1;
		else $admin = 0;
		$query = "UPDATE Bruger SET Email = '" . $_POST['email'] .
			"', Admin = " . $admin .
			" WHERE BrugerId = " . $_POST['userId'];
		$result = doSQLQuery($query);
		echo "done";

	// Delete slide-template
	} else if ($_POST['action'] == "deleteTemplate") {
		$name = $_POST['templateName'];
		// TODO: actually delete template
		echo "done";
	} else {
		echo "ugyldig handling";
	}

	closeDB();
} // session

?>
