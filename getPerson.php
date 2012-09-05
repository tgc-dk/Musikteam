<?php
session_start();


if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	openDB();

	echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";

	echo "<person>\n";
	$query = "SELECT Person.Fornavn,Person.Efternavn,Person.Adresse1,Person.Adresse2,Person.Telefon,Person.Mobil,Person.Mail FROM Person WHERE Person.PersonID=".$_POST['id'];
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$firstName = utf8_encode(stripslashes($line["Fornavn"]));
	$lastName = utf8_encode(stripslashes($line["Efternavn"]));
	$address = utf8_encode(stripslashes($line["Adresse1"]));
	$address2 = utf8_encode(stripslashes($line["Adresse2"]));
	$phone = utf8_encode(stripslashes($line["Telefon"]));
	$mobile = utf8_encode(stripslashes($line["Mobil"]));
	$email = utf8_encode(stripslashes($line["Mail"]));

	echo "<id>".$_POST['id']."</id>\n";
	echo "<firstName>".$firstName."</firstName>\n";
	echo "<lastName>".$lastName."</lastName>\n";
	echo "<address1>".$address."</address1>\n";
	echo "<address2>".$address2."</address2>\n";
	echo "<phone>".$phone."</phone>\n";
	echo "<mobile>".$mobile."</mobile>\n";
	echo "<email>".$email."</email>\n";

	echo "<abilities>\n";

	$abilityQuery = "SELECT Rolle.RolleID FROM Rolle INNER JOIN PersonRolle ON Rolle.RolleID=PersonRolle.RolleID WHERE PersonRolle.PersonID=".$_POST['id'];
	$abilityResult = doSQLQuery($abilityQuery);
	while ($abilityLine = db_fetch_array($abilityResult)) {
		 echo "<ability>".utf8_encode(stripslashes($abilityLine["RolleID"]))."</ability>\n";
	}

	echo "</abilities>\n";
	echo "</person>\n";

	closeDB();
} // session
	/*
Output format (more or less)
<person>
	<id>42</id>
	<firstname>Fornavn</firstname>
	<lastname>Efternavn</lastname>
	<address></address>
	<address2></address2>
	<tlf></tlf>
	<mobile></mobile>
	<email></email>
	<abilities>
		<ability>Klaver</ability>
		<ability>Sanger</ability>
	</abilities>
</person>
*/
?>
