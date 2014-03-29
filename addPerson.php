<?php
session_start();

include("db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
body
{
    background-color: #FFFFFF;
  	font-family: Arial, sans-serif;
}

#musiker_table {
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: outset outset outset outset;
	border-color: gray gray gray gray;
	border-collapse: collapse;
	background-color: white;	
}
.iframewrapper {
/*	margin:0 auto;*/
	text-align: left;
	font-size: 12px;
	
}
</style>
<script type="text/javascript">
 
<!-- 
function NewWindow(mypage, myname, scroll)
{
  var w=790;
  var h=screen.height;
  var winl = (screen.width - w) / 2;
  var wint = (screen.height - h) / 10;
  winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable';
  win = window.open(mypage, myname, winprops);
  win.focus();
}

function addPerson(name, abilities, abilitiesID, id)
{
	parent.addPerson(name, abilities, abilitiesID, id);
}

function addTeam(id)
{
	//parent.addTeam();
}

// -->
</script>
</head>
<html>
<body>
<strong>Teams:</strong>
	<div class="iframewrapper">

<?php

	openDB();

	$query = "SELECT TeamID,Navn,Beskrivelse FROM Team ORDER BY TeamID";
	$result = doSQLQuery($query);
	while ($line = db_fetch_array($result)) {
		$addteam = "";
		$colour = " bgcolor=\"#f2f2f2\"";
		$teamID = $line["TeamID"];
		$teamName = $line["Navn"];
		$teamDesr = $line["Beskrivelse"];
		echo "				<table id=\"musiker_table\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
		echo "				<tr bgcolor=\"#f2f2f2\"><td><strong>Team: </strong>".$teamName . "</td><td align=\"right\"><strong>Tilføj</strong></td></tr>\n";
		echo "				<tr><td colspan=\"2\"><strong>Beskrivelse: </strong>". $teamDesr . "</td></tr>\n";

		$tmquery = "SELECT Bruger.BrugerID,Bruger.Fornavn,Bruger.Efternavn FROM Bruger INNER JOIN TeamBruger ON TeamBruger.BrugerID=Bruger.BrugerID WHERE TeamBruger.TeamID=".$teamID.";";
		$tmresult = doSQLQuery($tmquery);
		while ($tmline = db_fetch_array($tmresult)) {
			$brugerid = $tmline["BrugerID"];
			$firstName = $tmline["Fornavn"];
			$lastName = $tmline["Efternavn"];
			echo "				<tr".$colour."><td>".$firstName." ".$lastName." (";

			// Find and list abilities
			$abilityQuery = "SELECT Rolle.Navn,Rolle.RolleID FROM Rolle INNER JOIN BrugerRolle ON Rolle.RolleID=BrugerRolle.RolleID WHERE BrugerRolle.BrugerID=".$brugerid.";";
			$abilityResult = doSQLQuery($abilityQuery);
			$abilities = "";
			$abilitiesID = "";
			while ($abilityLine = db_fetch_array($abilityResult)) {
				if ($abilities != "") $abilities = $abilities . ", ";
				$abilities = $abilities . $abilityLine["Navn"];
				if ($abilitiesID != "") $abilitiesID = $abilitiesID . ",";
				$abilitiesID = $abilitiesID . $abilityLine["RolleID"];
			}
			echo $abilities.")"; // Abilities

			$addperson = "addPerson('".$firstName." ".$lastName."','".$abilities."','".$abilitiesID."',".$brugerid.");";
			$addteam .= $addperson;
			echo "</td><td align=\"right\"><a href=\"javascript:".$addperson."\"><img src=\"img/list-add.gif\" alt=\"Tilf&oslash;j team\" width=\"14\" height=\"14\" border=\"0\" align=\"top\" /></a></td></tr>\n";
			if ($colour == "") {
				$colour = " bgcolor=\"#f2f2f2\"";
			} else {
				$colour = "";
			}
		}
		echo "				<tr".$colour."><td align=\"right\" colspan=\"2\"><a href=\"javascript:".$addteam."\"><img src=\"img/list-add.gif\" alt=\"Tilf&oslash;j team\" width=\"14\" height=\"14\" border=\"0\" align=\"top\" /></a>Tilføj hele teamet</td></tr>";
		echo "			</table><br />\n";
	}

?>
			<p>&nbsp;</p>

		</div> <!--Tteams -->
	</body>
</html>
