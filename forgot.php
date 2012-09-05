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

$strTitle="Glemt kode";

include("header.php");
include("db.php")
?>

<body class="home">

<div class="wrapper">
	<div id="header"></div>

	<div class="block_1">
	

		<div class="login">
<?php
$email = addslashes($_POST['email']);

if ($email == "") {
?>
			<form action="forgot.php" method="post">

				<table width="250" border="0" align="center" cellpadding="3" cellspacing="0">
				<tr>
					<td colspan="2">Indtast din email adresse, så vil du få tilsendt en ny kode</td>
				</tr>
				<tr>
					<td>E-mail</td>
					<td align="right"><input name="email" type="text" ></td>
				</tr>
				<tr>
					<td rowspan="2">&nbsp;</td>
					<td align="right"><p>
					  <input type="submit" name="newpassword" value="Send ny kode" class="submit_btn_2"/>
					</p>
				  </a></td>
				</tr>
				<tr>
				  <td><em>OBS Tjek spam-mappen  i dit email- program, hvis du ikke umiddelbart modtager den nye kode.</em></td>
				  </tr>
				</table>
		  </form>
<?php
} else {
	openDB();
	// See if the mentioned email exists in the database
	$query = "SELECT Brugernavn,BrugerId FROM Bruger WHERE Email='".$email."'";
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$brugernavn = $line["Brugernavn"];
	if ($brugernavn != "") {

		$newPassword = generatePassword();
		$query = "UPDATE Bruger SET Kode = '" . md5($newPassword."musikteam") ."' WHERE Brugernavn = '" . $brugernavn."'";
		$result = doSQLQuery($query);

		// Send an email to the new user with the username and password
		$subject = "Ny kode til ".$ROOT_URL."!";
		$body = "Hej!\n\nDin kode til http://".$ROOT_URL.", er blevet ændret og er nu: \"".$newPassword."\"\n".
			"Dit brugernavn er stadig '".$brugernavn."'.\n\n".
			"Mvh\n\t ".$WEBMASTER_NAME."\n\n\nPS. Dette er en automatisk genereret e-mail.\n";
		$headers = 'From: '. $WEBMASTER_NAME . "\r\n" .
			'Reply-To: '.$WEBMASTER_EMAIL . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		if (mail($email, $subject, $body, $headers)) {
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Der er nu blevet sendt en email med en nyt adgangskode til emailadressen.";
		} else {
			echo("Der blev ikke sendt en email pga. fejl på serveren, kontakt venligst webmasteren.");
		}
	} else {
		echo "Den oplyste email-adresse eksisterer ikke i databasen.";
	}
?>
	
<?php
}
?>
			<p>&nbsp;</p>
			<br />

		</div>
	</div>
</div>
<div class="footer"></div>
</body>
</html>
