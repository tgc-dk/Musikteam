
<?php
if (isset($_SESSION['logget_ind'])) {
global $DB_TYPE;

if ($_POST['brugerID'] != "") {

		$password = "";
		$query = "";
		if ($_POST['password1'] != "" && $_POST['password2'] != "" && $_POST['password1'] == $_POST['password2']) {
			$password = $_POST['password1'];
			$query = "UPDATE Bruger SET Email = '" . addslashes($_POST['email']) .
					"', PersonId = " . $_POST['personId'] .
					", Kode = '" . $_POST['password1'] .
					"' WHERE BrugerId = " . $_POST['brugerID'];
		} else {
			$query = "UPDATE Bruger SET Email = '" . addslashes($_POST['email']) .
					"', PersonId = " . $_POST['personId'] .
					" WHERE BrugerId = " . $_POST['brugerID'];
		}
		$result = doSQLQuery($query);	
	}

	$query = "SELECT Email,BrugerId,PersonId FROM Bruger WHERE Brugernavn = '".$_SESSION['brugernavn']."'";
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$brugernavn = $_SESSION['brugernavn'];
	$email = $line["Email"];
	$brugerid = $line["BrugerId"];
	$personid = $line["PersonId"];



?>


	<div class="block_2">
		<div class="sidebar_header">
Du er logget ind som: <strong><?php echo $_SESSION['brugernavn'] ?> </strong>
			<div class="log_help"><a href="logout.php">Log af</a> 
			<span id="help"><a href="help.html" onclick="NewWindow(this.href,'help_win','yes');return false;">Hjælp!</a></span></div>
		</div>

		<div id="sidebar">

<?php
	$now = time();
	$today = date('d', $now);
	$curmonth = date('m', $now);
	$curyear = date('Y', $now);

		if ($_GET['page'] == 'sange' || $_GET['page'] == '')  {

?>
			<div class="sidebar_top"> Sang top-ti:</div>
			<div class="sidebar_bg"> 
<?php

			if ($DB_TYPE == "mysql") {
				$query = "SELECT Count(Sang.SangID) AS SongCount,Sang.SangID,Sang.Titel
					FROM (Sang
					INNER JOIN ProgramPunkt ON Sang.SangId = ProgramPunkt.SangID)
					INNER JOIN Program ON Program.ProgramID = ProgramPunkt.ProgramID
					WHERE DateDiff('$curyear-$curmonth-$today', Program.Dato) < 365
					GROUP BY Sang.SangID,Sang.Titel
					ORDER BY Count(Sang.SangID) DESC LIMIT 10";

			} else if ($DB_TYPE == "odbc") {

				$query = "SELECT TOP 10 Count(Sang.SangID) AS SongCount,Sang.SangID,Sang.Titel
					FROM (Sang
					INNER JOIN ProgramPunkt ON Sang.SangId = ProgramPunkt.SangID)
					INNER JOIN Program ON Program.ProgramID = ProgramPunkt.ProgramID
					WHERE DateDiff('d', Program.Dato, '$curyear-$curmonth-$today') < 365
					GROUP BY Sang.SangID,Sang.Titel
					ORDER BY Count(Sang.SangID) DESC ";
			}

		$result = doSQLQuery($query);

		$lines = 0;
		while ($lines < 10 && ($line = db_fetch_array($result))) {
			$lines++;
			$count = $line["SongCount"];
			$songid = $line["SangID"];
			$songtitle = $line["Titel"];
			$songtitle = substr($songtitle,0,25);
			echo "				<a href=\"editSong.php?song=".$songid."\" onclick=\"NewWindow(this.href,'song_win','yes');return false;\">".$songtitle."...</a> (".$count.")<br />\n";
		}

?>
			</div>
			<div class="sidebar_bund"></div>
<?php
		} else if ($_GET['page'] == 'program')  {
?>
			<div class="sidebar_top">Kalender</div>
			<div class="sidebar_bg">
<?php
			//<div class="sidebar_bg"><img src="img/kalender_img.gif" width="160" height="165" /><br /></div>
			include('calendar.php');
?>
			</div>
			<div class="sidebar_bund"></div>
<?php
		}
		$now = time();
		$today = date('d', $now);
		$curmonth = date('m', $now);
		$curyear = date('Y', $now);
?>

			<div class="sidebar_top"> Senest tilføjet: </div>
			<div class="sidebar_bg"> 
<?php

	if ($DB_TYPE == "mysql") {
		$query = "SELECT SangId,Titel FROM Sang ORDER BY SangId DESC LIMIT 6;";
	} else if ($DB_TYPE == "odbc") {
		$query = "SELECT TOP 6 SangId,Titel FROM Sang ORDER BY SangId DESC;";
	}
	$result = doSQLQuery($query);
	while ($line = db_fetch_array($result)) {
		echo "				<a href=\"editSong.php?song=".$line["SangId"]."\" onclick=\"NewWindow(this.href,'song_win','yes');return false;\">".$line["Titel"]."</a><br />\n";
	}

?>
			</div>
			<div class="sidebar_bund"></div>
            
             <div class="sidebar_top"> Setlister hvor jeg er p&aring;: </div>
			<div class="sidebar_bg"> 
<?php




	if ($DB_TYPE == "mysql") {
		$query = "SELECT Program.Dato,Program.ProgramId,Program.Arrangement FROM Program INNER JOIN ProgramPerson ON ProgramPerson.ProgramID=Program.ProgramID WHERE ProgramPerson.PersonID=".$personid." AND DateDiff(Dato, '$curyear-$curmonth-$today') > -1 ORDER BY Dato LIMIT 3;";
	} else if ($DB_TYPE == "odbc") {
		$query = "SELECT TOP 3 Program.Dato,Program.ProgramId,Program.Arrangement FROM Program INNER JOIN ProgramPerson ON ProgramPerson.ProgramID=Program.ProgramID WHERE ProgramPerson.PersonID=".$personid." AND DateDiff('s', Dato, '$curyear-$curmonth-$today') < 1 ORDER BY Dato;";
	}
	$result = doSQLQuery($query);
	while ($line = db_fetch_array($result)) {
		
		$tmp = $line["Dato"];
		
		$eventDate = substr($tmp,8,2)."/".substr($tmp,5,2)."/".substr($tmp,0,4);
		 
		echo "			<a href=\"main.php?page=program&eventId=".$line["ProgramId"]."\">".$line["Arrangement"]." d. ".$eventDate."</a><br />\n";
		
		

	}
?>
		<?php
		echo "			<div class=\"sealle\">		<a href=\"main.php?page=mypage\">Se alle</a></div>\n";
		?>
        	</div>
			<div class="sidebar_bund"></div>
			
			<div class="sidebar_top"> Kommende setlister: </div>
			<div class="sidebar_bg"> 
<?php

	if ($DB_TYPE == "mysql") {
		$query = "SELECT Dato,ProgramId,Arrangement FROM Program WHERE DateDiff(Dato, '$curyear-$curmonth-$today') > -1 ORDER BY Dato LIMIT 5;";
	} else if ($DB_TYPE == "odbc") {
		$query = "SELECT TOP 5 Dato,ProgramId,Arrangement FROM Program WHERE DateDiff('s', Dato, '$curyear-$curmonth-$today') < 1 ORDER BY Dato;";
	}
	$result = doSQLQuery($query);
		while ($line = db_fetch_array($result)) {
			$tmp = $line["Dato"];

			$eventid = $line["ProgramId"];
			$eventtitle = $line["Arrangement"];
			$eventtitle = substr($eventtitle,0,22);
			
		$eventDate = substr($tmp,8,2)."/".substr($tmp,5,2);
		echo "				<a href=\"main.php?page=program&eventId=".$eventid."\">".$eventtitle." d. ".$eventDate."</a><br />\n";
	}
	


?>
<?php
		echo "			<div class=\"sealle\">		<a href=\"main.php?page=program\">Se alle</a></div>\n";
		?>
			</div>
		  <div class="sidebar_bund"></div>
            
           
		</div>
	</div>


<?php
} // Session
?>
