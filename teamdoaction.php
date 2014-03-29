<?php
if (isset($_SESSION['logget_ind'])) {

    $subpage = isset($_GET['subpage']) ? $_GET['subpage'] : '';
	// Check if we should do something (save or delete) realated to teams
	if ($subpage == "teams" && $_GET['action'] != "") {

		if ($_GET['action'] == "save") {
			// This is a new bruger being commited
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
						$query = "INSERT INTO TeamBruger (TeamID,BrugerID) VALUES ('".$id."','" . $i . "')";
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
				$query = "DELETE FROM TeamBruger WHERE TeamID=" . $_POST['teamID'] . ";";
				$result = doSQLQuery($query);

				// Save the new members
				for ($i = 0; $i < 100; $i++) { // Find a cleaner way to do this
					if ($_POST['member'.$i] !="") {
						$query = "INSERT INTO TeamBruger (TeamID,BrugerID) VALUES ('".$_POST['teamID']."','" . $i . "')";
						$result = doSQLQuery($query);
					}
				}
			}
		
		} else if ($_GET['action'] == "delete") {
			// First we delete all the old members in the database for this team
			$query = "DELETE FROM TeamBruger WHERE TeamID=" . $_POST['teamID'] . ";";
			$result = doSQLQuery($query);

			// And finally from the team table
			$query = "DELETE FROM Team WHERE TeamID=" . $_POST['teamID'] . ";";
			$result = doSQLQuery($query);
		}
	} // teams



	// Check if we should do something (save or delete) realated to persons
	if ($subpage == "persons" && $_GET['action'] != "") {

		if ($_GET['action'] == "save") {
			// This is a new person being commited
			if ($_POST['brugerID'] == -1) {
				// Save the contact info
				$query = "INSERT INTO Bruger (Fornavn,Efternavn,Adresse1,Adresse2,EMail,Telefon,Mobil) VALUES ('" 
						. addslashes($_POST['fornavn']) . "','" . addslashes($_POST['efternavn']) . "','" 
						. addslashes($_POST['adresse']) . "','" . addslashes($_POST['adresse2']) . "','" 
						. addslashes($_POST['email']) . "','" . addslashes($_POST['tlf']) . "','" 
						. addslashes($_POST['mobil']) . "');";
				$result = doSQLQuery($query);

				// Get the ID of the new person
				$query = "SELECT MAX(BrugerID) AS NewBrugerId FROM Bruger";
				$result = doSQLQuery($query);
				$res_arr = db_fetch_array($result);
				$id = $res_arr['NewBrugerId'];
				// Save the abilities
				for ($i = 0; $i < 100; $i++) { // Find a cleaner way to do this
					if ($_POST['ability'.$i] !="") {
						$query = "INSERT INTO BrugerRolle (BrugerID,RolleID) VALUES ('".$id."','" . $i . "')";
						$result = doSQLQuery($query);
					}
				}

			// This is an existing person being updated
			} else {

				// Update the contact info
				$query = "UPDATE Bruger SET Fornavn = '" . addslashes($_POST['fornavn']) .
						"', Efternavn = '" . addslashes($_POST['efternavn']) .
						"', Adresse1 = '" . addslashes($_POST['adresse']) .
						"', Adresse2 = '" . addslashes($_POST['adresse2']) .
						"', Mail = '" . addslashes($_POST['email']) .
						"', Telefon = '" . addslashes($_POST['tlf']) .
						"', Mobil = '" . addslashes($_POST['mobil']) .
						"' WHERE BrugerID = " . $_POST['brugerID'] . ";";

				$result = doSQLQuery($query);

				// First we delete all the old roles in the database for this person
				$query = "DELETE FROM BrugerRolle WHERE BrugerID=" . $_POST['BrugerID'] . ";";
				$result = doSQLQuery($query);

				// Save the new abilities
				for ($i = 0; $i < 100; $i++) { // Find a cleaner way to do this
					if ($_POST['ability'.$i] !="") {
						$query = "INSERT INTO BrugerRolle (BrugerID,RolleID) VALUES ('".$_POST['brugerID']."','" . $i . "')";
						$result = doSQLQuery($query);
					}
				}
			}
		
		} else if ($_GET['action'] == "delete") {
			// First we delete all the old roles in the database for this bruger
			$query = "DELETE FROM BrugerRolle WHERE BrugerID=" . $_POST['brugerID'] . ";";
			$result = doSQLQuery($query);

			// And then from the team(s)
			$query = "DELETE FROM TeamBruger WHERE BrugerID=" . $_POST['brugerID'] . ";";
			$result = doSQLQuery($query);

			// And also from the ProgramBruger table
			$query = "DELETE FROM ProgramBruger WHERE BrugerID=" . $_POST['brugerID'] . ";";
			$result = doSQLQuery($query);

			// And finally from the bruger table
			$query = "DELETE FROM Bruger WHERE BrugerID=" . $_POST['brugerID'] . ";";
			$result = doSQLQuery($query);
		}
	} //persons

    $newability = isset($_POST['newability']) ? $_POST['newability'] : '';
    $ability = isset($_POST['ability']) ? $_POST['ability'] : '';
	// Save new ability
	if ($newability != "") {
		$query = "INSERT INTO Rolle (Navn) VALUES ('" . addslashes($_POST['newability']) . "');";
		$result = doSQLQuery($query);

	// Delete ability
	} else if ($ability != "" && $_GET['action'] == "delete") {

		// Delete from BrugerRolle
		$query = "DELETE FROM BrugerRolle WHERE RolleID=" . $_GET['id'] . ";";
		$result = doSQLQuery($query);		

		// Delete from Rolle
		$query = "DELETE FROM Rolle WHERE RolleID=" . $_GET['id'] . ";";
		$result = doSQLQuery($query);		
		
	// Update old ability
	} else if ($ability != "") {

		$query = "UPDATE Rolle SET Navn = '" . addslashes($_POST['ability']) . "' WHERE RolleID = " . $_GET['id'] . ";";
		$result = doSQLQuery($query);
	}

} // session
?>
