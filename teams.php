<?php
if (isset($_SESSION['logget_ind'])) {

include ("teamdoaction.php");

?>
<div class="teams_page">
<div class="sub_menu">

<div onclick="Teams()" id="Teams_menu">Teams</div> 
<div  onclick="Personer()" id="Personer_menu">Personer</div> 
<div   onclick="Evner()" id="Evner_menu">Evner / Instrumenter</div> 
	</div>

	<div id="team_menu">
		
		<div id="Teams">
			<div id="edit_team">
				<form id="teamform" name="teamform" method="post" action="main.php?page=teams&subpage=teams&action=save">
				<input type="hidden" name="teamID" id="teamID" value="-1">
				<table id="musiker_table" cellspacing="0" cellpadding="3" width="780">
				<tr><td height="15" background="img/tabletop_bg.gif" colspan="2"><div align="left"><strong>Ret team:</strong></div></td></tr>
				<tr>
					<td colspan="2">
						Team navn: <input type="text" id="teamnavn" name="teamnavn" size="20">&nbsp;&nbsp;&nbsp;&nbsp;
						Team Beskrivelse: <input type="text" id="teamdescr" name="teamdescr" size="50">
					<td>
				</tr>
				<tr><td align="left" bgcolor="#f2f2f2" colspan="2"><strong>Medlemmer:</strong> (dem med kryds i)</td></tr>

<?php
	$tmquery = "SELECT Person.PersonID,Person.Fornavn,Person.Efternavn FROM Person;";
	$tmresult = doSQLQuery($tmquery);
	$colour = "";
	while ($tmline = db_fetch_array($tmresult)) {
		$personid = $tmline["PersonID"];
		$firstName = $tmline["Fornavn"];
		$lastName = $tmline["Efternavn"];
		echo "<tr><td".$colour." align=\"left\" colspan=\"2\">";
		echo "<input class=\"checkbox\" type=\"checkbox\" id=\"member".$personid."\" name=\"member".$personid."\">";
		echo $firstName." ".$lastName." (";

		// Find and list abilities
		$abilityQuery = "SELECT Rolle.Navn FROM Rolle INNER JOIN PersonRolle ON Rolle.RolleID=PersonRolle.RolleID WHERE PersonRolle.PersonID=".$personid.";";
		$abilityResult = doSQLQuery($abilityQuery);
		$abilities = "";
		while ($abilityLine = db_fetch_array($abilityResult)) {
			if ($abilities != "") $abilities = $abilities . ", ";
			$abilities = $abilities . $abilityLine["Navn"];
		}
		
		echo $abilities.")"; // Abilities


		echo "</td></tr>\n";
		if ($colour == "") {
			$colour = " bgcolor=\"#f2f2f2\"";
		} else {
			$colour = "";
		}
	}

?>
					</div></td>
				</tr>
				<tr>
					<td<?php echo $colour; ?> align="center" colspan="2">
						<input class="submit_btn" type="submit" name="Submit" value="Gem teamdata" />&nbsp;&nbsp;
						<button name="send" value="Slet team" class="submit_btn_2" onClick="javascript:return deleteTeam();"> Slet team </button>&nbsp;&nbsp;
						<button name="send" value="Annuller" class="submit_btn_2" onClick="javascript:document.getElementById('edit_team').style.display='none';return false;"> Annuller </button>
					</td>
				</tr>
				</table>
				</form>
				<p>&nbsp;</p>
			</div> <!-- edit_person -->
            <p>&nbsp;</p>
			<strong>Eksisterende teams:</strong>
<?php
	$query = "SELECT TeamID,Navn,Beskrivelse FROM Team ORDER BY TeamID";

	$result = doSQLQuery($query);
	while ($line = db_fetch_array($result)) {
		$colour = " bgcolor=\"#f2f2f2\"";
		$teamID = $line["TeamID"];
		$teamName = $line["Navn"];
		$teamDesr = $line["Beskrivelse"];
		echo "				<table id=\"musiker_table\" cellspacing=\"0\" cellpadding=\"3\" width=\"780\">\n";
		echo "				<tr><td bgcolor=\"#f2f2f2\"><strong>Team: </strong>".$teamName . "</td></tr>\n";
		echo "				<tr><td ><strong>Beskrivelse: </strong>". $teamDesr . "</td></tr>\n";

		$tmquery = "SELECT Person.PersonID,Person.Fornavn,Person.Efternavn FROM Person INNER JOIN TeamPerson ON TeamPerson.PersonID=Person.PersonID WHERE TeamPerson.TeamID=".$teamID.";";
		$tmresult = doSQLQuery($tmquery);
		while ($tmline = db_fetch_array($tmresult)) {
			$personid = $tmline["PersonID"];
			$firstName = $tmline["Fornavn"];
			$lastName = $tmline["Efternavn"];
			echo "				<tr><td".$colour.">".$firstName." ".$lastName." (";

			// Find and list abilities
			$abilityQuery = "SELECT Rolle.Navn FROM Rolle INNER JOIN PersonRolle ON Rolle.RolleID=PersonRolle.RolleID WHERE PersonRolle.PersonID=".$personid.";";
			$abilityResult = doSQLQuery($abilityQuery);
			$abilities = "";
			while ($abilityLine = db_fetch_array($abilityResult)) {
				if ($abilities != "") $abilities = $abilities . ", ";
				$abilities = $abilities . $abilityLine["Navn"];
			}
			echo $abilities.")"; // Abilities


			echo "</td></tr>\n";
			if ($colour == "") {
				$colour = " bgcolor=\"#f2f2f2\"";
			} else {
				$colour = "";
			}
		}
		echo "				<tr><td".$colour."><button name=\"send\" value=\"Ret team\" class=\"submit_btn_2\" onClick=\"javascript:editTeam(".$teamID.");return false;\"> Ret team </button></td></tr>";
		echo "			</table><br />\n";
	}
?>
			<button name="send" value="Opret nyt team" class="submit_btn_2" onClick="javascript:newTeam();return false;"> Opret nyt team </button>
			<p>&nbsp;</p>

		</div> <!--Tteams -->
	 
<?php
	include("persons.php");
	include("abilities.php");
?>
	</div> <!-- team_menu -->
</div> <!-- teams_page -->
<script type="text/javascript">
	document.getElementById("edit_person").style.display="none";
	document.getElementById("edit_team").style.display="none";
<?php
	echo "	".$jsArrStr;
	if ($_GET['subpage'] == "persons") echo "	Personer();\n";
	else if ($_GET['subpage'] == "abilities") echo "	Evner();\n";
	else echo "	Teams();\n";
?>
</script>
<?php
} // session
?>
