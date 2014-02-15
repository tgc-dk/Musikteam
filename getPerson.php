<?php
session_start();
header("Content-Type: application/xml; charset=utf-8");

if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	openDB();

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

	echo "<person>\n";
	$query = "SELECT Person.Fornavn,Person.Efternavn,Person.Adresse1,Person.Adresse2,Person.Telefon,Person.Mobil,Person.Mail FROM Person WHERE Person.PersonID=".$_POST['id'];
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$firstName = stripslashes($line["Fornavn"]);
	$lastName = stripslashes($line["Efternavn"]);
	$address = stripslashes($line["Adresse1"]);
	$address2 = stripslashes($line["Adresse2"]);
	$phone = stripslashes($line["Telefon"]);
	$mobile = stripslashes($line["Mobil"]);
	$email = stripslashes($line["Mail"]);

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
	<firstname>Tomas</firstname>
	<lastname>Groth</lastname>
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
