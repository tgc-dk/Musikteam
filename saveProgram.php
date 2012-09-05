<?php
include("db.php");

session_start();


if (isset($_SESSION['logget_ind'])) {

	openDB();

	$eventid = $_POST['eventid'];

	// Valid id?
	if ($eventid == "" || $eventid < 0) {
		echo "invalid eventid";
		exit;
	}

	// Delete the old entries in the ProgramPunkt table
	$query = "DELETE FROM ProgramPunkt WHERE ProgramID=" . $eventid;
	$result = doSQLQuery($query);

	// Insert the new entries
	$songcount = $_POST['songcount'];
	for ($count = 0; $count < $songcount; $count++) {
		$index = 'Song'.$count;
		$headIndex = 'Heading'.$count;
		$songid = $_POST[$index];
		$heading = db_fix_str($_POST[$headIndex]);
		//echo "songid:".$index.":".$songid;
		$query = "INSERT INTO ProgramPunkt (ProgramID,SangID,Overskrift,Raekkefoelge) VALUES (".$eventid.",".$songid.",'".$heading."',".$count.");";
		$result = doSQLQuery($query);
	}

	// Delete the old entries in the ProgramPerson table
	$query = "DELETE FROM ProgramPerson WHERE ProgramID=" . $eventid;
	$result = doSQLQuery($query);

	// Insert the new entries
	$personcount = $_POST['personcount'];
	for ($count = 0; $count < $personcount; $count++) {
		$personid = $_POST['Person'.$count];
		$roleid = $_POST['Rolle'.$count];
		$query = "INSERT INTO ProgramPerson (ProgramID,PersonID,RolleID) VALUES (".$eventid.",".$personid.",".$roleid.");";
		$result = doSQLQuery($query);
	}

	echo 'done';
	closeDB();
} else {
	echo 'Ikke logget ind';
}
?>
