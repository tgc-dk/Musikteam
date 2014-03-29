<?php
session_start();
header("Content-Type: application/xml; charset=utf-8");


if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	openDB();

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

	echo "<team>\n";
	$query = "SELECT Team.Navn,Team.Beskrivelse FROM Team WHERE Team.TeamID=".$_POST['id'];
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$teamName = stripslashes($line["Navn"]);
	$teamDescr = stripslashes($line["Beskrivelse"]);

	echo "<id>".$_POST['id']."</id>\n";
	echo "<teamName>".$teamName."</teamName>\n";
	echo "<description>".$teamDescr."</description>\n";
	echo "<members>\n";

	$memberQuery = "SELECT Bruger.BrugerID FROM Bruger INNER JOIN TeamBruger ON Bruger.BrugerID=TeamBruger.BrugerID WHERE TeamBruger.TeamID=".$_POST['id'];
	$memberResult = doSQLQuery($memberQuery);
	while ($memberLine = db_fetch_array($memberResult)) {
		 echo "<member>".utf8_encode(stripslashes($memberLine["BrugerID"]))."</member>\n";
	}

	echo "</members>\n";
	echo "</team>\n";

	closeDB();
} // session
?>
