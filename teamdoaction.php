<?php
if (isset($_SESSION['logget_ind'])) {

	// Check if we should do something (save or delete) realated to teams
	if ($_GET['subpage'] == "teams" && $_GET['action'] != "") {

		if ($_GET['action'] == "save") {
			// This is a new person being commited
			if ($_POST['teamID'] == -1) {
				// Save the team info
				$query = "INSERT INTO Team (Navn,Beskrivelse) VALUES ('" 
						. addslashes($_POST['teamnavn']) . "','" . addslashes($_POST['teamdescr']) . "');";
				$result = doSQLQuery($query);

				// Get the ID of the new team
				$query = "SELECT MAX(TeamID) AS NewTeamId FROM Team";
				$result = doSQLQuery($query);
				$res_arr = db_fetch_array($result);
				$id = $res_arr['NewTeamId'];
				/*if ($DB_TYPE == "mysql") {
					$id = $res_arr['MAX(TeamID)'];
				} else if ($DB_TYPE == "odbc") {
					$id = $res_arr['TeamID'];
				}*/
				// Save the members
				for ($i = 0; $i < 100; $i++) { // Find a cleaner way to do this
					if ($_POST['member'.$i] !="") {
						$query = "INSERT INTO TeamPerson (TeamID,PersonID) VALUES ('".$id."','" . $i . "')";
						$result = doSQLQuery($query);
					}
				}

			// This is an existing team being updated
			} else {

				// Update the team info
				$query = "UPDATE Team SET Navn = '" . addslashes($_POST['teamnavn']) .
						"', Beskrivelse = '" . addslashes($_POST['teamdescr']) .
						"' WHERE TeamID = " . $_POST['teamID'] . ";";
				$result = doSQLQuery($query);

				// First we delete all the old members in the database for this team
				$query = "DELETE FROM TeamPerson WHERE TeamID=" . $_POST['teamID'] . ";";
				$result = doSQLQuery($query);

				// Save the new members
				for ($i = 0; $i < 100; $i++) { // Find a cleaner way to do this
					if ($_POST['member'.$i] !="") {
						$query = "INSERT INTO TeamPerson (TeamID,PersonID) VALUES ('".$_POST['teamID']."','" . $i . "')";
						$result = doSQLQuery($query);
					}
				}
			}
		
		} else if ($_GET['action'] == "delete") {
			// First we delete all the old members in the database for this team
			$query = "DELETE FROM TeamPerson WHERE TeamID=" . $_POST['teamID'] . ";";
			$result = doSQLQuery($query);

			// And finally from the team table
			$query = "DELETE FROM Team WHERE TeamID=" . $_POST['teamID'] . ";";
			$result = doSQLQuery($query);
		}
	} // teams



	// Check if we should do something (save or delete) realated to persons
	if ($_GET['subpage'] == "persons" && $_GET['action'] != "") {

		if ($_GET['action'] == "save") {
			// This is a new person being commited
			if ($_POST['personID'] == -1) {
				// Save the contact info
				$query = "INSERT INTO Person (Fornavn,Efternavn,Adresse1,Adresse2,Mail,Telefon,Mobil) VALUES ('" 
						. addslashes($_POST['fornavn']) . "','" . addslashes($_POST['efternavn']) . "','" 
						. addslashes($_POST['adresse']) . "','" . addslashes($_POST['adresse2']) . "','" 
						. addslashes($_POST['email']) . "','" . addslashes($_POST['tlf']) . "','" 
						. addslashes($_POST['mobil']) . "');";
				$result = doSQLQuery($query);

				// Get the ID of the new person
				$query = "SELECT MAX(PersonID) AS NewPersonId FROM Person";
				$result = doSQLQuery($query);
				$res_arr = db_fetch_array($result);
				$id = $res_arr['NewPersonId'];
				/*if ($DB_TYPE == "mysql") {
					$id = $res_arr['MAX(PersonID)'];
				} else if ($DB_TYPE == "odbc") {
					$id = $res_arr['PersonID'];
				}*/
				// Save the abilities
				for ($i = 0; $i < 100; $i++) { // Find a cleaner way to do this
					if ($_POST['ability'.$i] !="") {
						$query = "INSERT INTO PersonRolle (PersonID,RolleID) VALUES ('".$id."','" . $i . "')";
						$result = doSQLQuery($query);
					}
				}

			// This is an existing person being updated
			} else {

				// Update the contact info
				$query = "UPDATE Person SET Fornavn = '" . addslashes($_POST['fornavn']) .
						"', Efternavn = '" . addslashes($_POST['efternavn']) .
						"', Adresse1 = '" . addslashes($_POST['adresse']) .
						"', Adresse2 = '" . addslashes($_POST['adresse2']) .
						"', Mail = '" . addslashes($_POST['email']) .
						"', Telefon = '" . addslashes($_POST['tlf']) .
						"', Mobil = '" . addslashes($_POST['mobil']) .
						"' WHERE PersonID = " . $_POST['personID'] . ";";

				$result = doSQLQuery($query);

				// First we delete all the old roles in the database for this person
				$query = "DELETE FROM PersonRolle WHERE PersonID=" . $_POST['personID'] . ";";
				$result = doSQLQuery($query);

				// Save the new abilities
				for ($i = 0; $i < 100; $i++) { // Find a cleaner way to do this
					if ($_POST['ability'.$i] !="") {
						$query = "INSERT INTO PersonRolle (PersonID,RolleID) VALUES ('".$_POST['personID']."','" . $i . "')";
						$result = doSQLQuery($query);
					}
				}
			}
		
		} else if ($_GET['action'] == "delete") {
			// First we delete all the old roles in the database for this person
			$query = "DELETE FROM PersonRolle WHERE PersonID=" . $_POST['personID'] . ";";
			$result = doSQLQuery($query);

			// And then from the team(s)
			$query = "DELETE FROM TeamPerson WHERE PersonID=" . $_POST['personID'] . ";";
			$result = doSQLQuery($query);

			// And also from the ProgramPerson table
			$query = "DELETE FROM ProgramPerson WHERE PersonID=" . $_POST['personID'] . ";";
			$result = doSQLQuery($query);

			// And finally from the person table
			$query = "DELETE FROM Person WHERE PersonID=" . $_POST['personID'] . ";";
			$result = doSQLQuery($query);
		}
	} //persons

	// Save new ability
	if ($_POST['newability'] != "") {
		$query = "INSERT INTO Rolle (Navn) VALUES ('" . addslashes($_POST['newability']) . "');";
		$result = doSQLQuery($query);

	// Delete ability
	} else if ($_POST['ability'] != "" && $_GET['action'] == "delete") {

		// Delete from PersonRolle
		$query = "DELETE FROM PersonRolle WHERE RolleID=" . $_GET['id'] . ";";
		$result = doSQLQuery($query);		

		// Delete from Rolle
		$query = "DELETE FROM Rolle WHERE RolleID=" . $_GET['id'] . ";";
		$result = doSQLQuery($query);		
		
	// Update old ability
	} else if ($_POST['ability'] != "") {

		$query = "UPDATE Rolle SET Navn = '" . addslashes($_POST['ability']) . "' WHERE RolleID = " . $_GET['id'] . ";";
		$result = doSQLQuery($query);
	}

} // session
?>
