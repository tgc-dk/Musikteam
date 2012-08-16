<?php
session_start();


if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	openDB();

	echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";

	echo "<team>\n";
	$query = "SELECT Team.Navn,Team.Beskrivelse FROM Team WHERE Team.TeamID=".$_POST['id'];
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$teamName = utf8_encode(stripslashes($line["Navn"]));
	$teamDescr = utf8_encode(stripslashes($line["Beskrivelse"]));

	echo "<id>".$_POST['id']."</id>\n";
	echo "<teamName>".$teamName."</teamName>\n";
	echo "<description>".$teamDescr."</description>\n";
	echo "<members>\n";

	$memberQuery = "SELECT Person.PersonID FROM Person INNER JOIN TeamPerson ON Person.PersonID=TeamPerson.PersonID WHERE TeamPerson.TeamID=".$_POST['id'];
	$memberResult = doSQLQuery($memberQuery);
	while ($memberLine = db_fetch_array($memberResult)) {
		 echo "<member>".utf8_encode(stripslashes($memberLine["PersonID"]))."</member>\n";
	}

	echo "</members>\n";
	echo "</team>\n";

	closeDB();
} // session
?>
