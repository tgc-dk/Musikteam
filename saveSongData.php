<?php
include("db.php");

session_start();


if (isset($_SESSION['logget_ind'])) {

	openDB();

	$type = $_POST['type'];
	$id = $_POST['id'];
	$newsong = false;
	$done = false;

	if ($id == -1) {
		$newsong = true;
		$query = "INSERT INTO Sang (Titel) VALUES ('" . db_fix_str($_POST['title']). "');";
		$result = doSQLQuery($query);
		
		// Get the ID of the new song
		$query = "SELECT MAX(SangId) AS NewSangId FROM Sang";
		$result = doSQLQuery($query);
		$res_arr = db_fetch_array($result);
		$id = $res_arr["NewSangId"];
		/*if ($DB_TYPE == "mysql") {
			$id = $res_arr["MAX(SangId)"];
		} else if ($DB_TYPE == "odbc") {
			$id = $res_arr["SangId"];
		}*/
	}

	// Add some historik
	date_default_timezone_set('Europe/Copenhagen');
	$date = date("Y-m-d H:i:s");
	$query = "INSERT INTO Historik (SangId,BrugerId,Dato) VALUES ('" . $id . "','".$_SESSION['brugerid']."','".$date."');";
	$result = doSQLQuery($query);
	if ($type == "slides" || $type == "all") {
		
		// First we delete all the old slides in the database for this song
		$query = "DELETE FROM Slide2 WHERE SangID=" . $id . ";";
		$result = doSQLQuery($query);

		// Now insert the new slides
		for($c='A'; $c < 'I';$c++) {
			$query = "INSERT INTO Slide2 (SangID,SlideID,Slidetekst) VALUES ";
			if ($_POST['slide'.$c] != "") {
				$query = $query . "(". $id .",'".$c."','" . db_fix_str($_POST['slide'.$c]) . "');";

				$result = doSQLQuery($query);
			}
		}
		
		// Update the playlist
		$query = "UPDATE Sang SET Slides2 = '" . $_POST['playlist']. "' WHERE SangId = " . $id . ";";
		$result = doSQLQuery($query);

		$done = true;

	}

	if ($type == "lyrics"  || $type == "all") {

		// Update the modpro text and the over and under comments
		$query = "UPDATE Sang SET ProTekst = '" . db_fix_str($_POST['lyrics']) . 
				"', CommentsOver = '" . db_fix_str($_POST['commentOver']). 
				"', CommentsUnder = '" . db_fix_str($_POST['commentUnder']). 
				"' WHERE SangId = " . $id . ";";
		$result = doSQLQuery($query);

		$done = true;
	} 

	if ($type == "meta"  || $type == "all") {
		$query = "UPDATE Sang SET Titel = '" . db_fix_str($_POST['title']) . 
				"', Identifikation = '" . db_fix_str($_POST['author']). 
				"', Udgave = '" . db_fix_str($_POST['edition']). 
				"', Kilde = '" . db_fix_str($_POST['source']). 
				"', Lydfil = '" . db_fix_str($_POST['file']). 
				"' WHERE SangId = " . $id . ";";
		$result = doSQLQuery($query);

		$done = true;
	}

	if ($type == "delete") {
		// First we delete all the slides in the database for this song
		$query = "DELETE FROM Slide2 WHERE SangID=" . $id . ";";
		$result = doSQLQuery($query);

		// And then  we delete all the occurences in the ProgramPunkt table
		$query = "DELETE FROM ProgramPunkt WHERE SangID=" . $id . ";";
		$result = doSQLQuery($query);

		// Then we delete the entry in the Sang table
		$query = "DELETE FROM Sang WHERE SangID=" . $id . ";";
		$result = doSQLQuery($query);

		// Then we delete the entry in the Historik table
		$query = "DELETE FROM Historik WHERE SangID=" . $id . ";";
		$result = doSQLQuery($query);

		$done = true;
	} 

	if ($done == true) {
		echo "done";
		if ($newsong == true) {
			echo ':' . $id;
		}
	} else {
		echo "Type ikke angivet";
	}
	
	closeDB();
} else {
	echo 'Ikke logget ind';
}
?>
