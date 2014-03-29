<?php
if (isset($_SESSION['logget_ind'])) {

?>
		<div id="Personer">
			<div id="edit_person">
				<form id="personform" name="personform" method="post" action="main.php?page=teams&subpage=persons&action=save">
				<input type="hidden" name="brugerID" id="personID" value="-1">
				<table id="musiker_table" cellspacing="0" cellpadding="3" width="780">
				<tr><td height="15" background="img/tabletop_bg.gif" colspan="2"><div align="left"><strong>Ret person:</strong></div></td></tr>
				<tr>
					<td colspan="2"><strong>Navn og kontaktoplysninger:</strong></td>
				</tr>
				<tr>
					<td align="right" bgcolor="#f2f2f2">Fornavn: <input type="text" id="fornavn" name="fornavn" size="45"></td>
					<td align="right" bgcolor="#f2f2f2">Efternavn: <input type="text" id="efternavn" name="efternavn" size="45"></td>
				</tr>
				<tr>
					<td align="right">Adresse: <input type="text" id="adresse" name="adresse" size="45"></td>
					<td align="center">Telefon: <input type="text" id="tlf" name="tlf" size="10">&nbsp;&nbsp;&nbsp;&nbsp; Mobil: <input type="text" id="mobil" name="mobil" size="10"></td>
				</tr>
				<tr>
					<td align="right" bgcolor="#f2f2f2"><input type="text" id="adresse2" name="adresse2" size="45"></td>
					<td align="left" bgcolor="#f2f2f2">E-mail: <input type="text" id="email" name="email" size="20"></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Evner:</strong></td>
				</tr>
				<tr>
					<td align="left" bgcolor="#f2f2f2" colspan="2"><div id="abilityBoxes">
<?php
	echo '						';
	$query = "SELECT RolleID,Navn FROM Rolle ORDER BY RolleID";

	$result = doSQLQuery($query);
	$jsArrStr = "var arrAbilities = new Array(";
	$i = 0;
	while ($line = db_fetch_array($result)) {
		$roleID = $line["RolleID"];
		$roleName = $line["Navn"];
		echo '<input class="checkbox" type="checkbox" id="ability'.$roleID.'" name="ability'.$roleID.'">'.$roleName.'&nbsp;&nbsp;&nbsp;&nbsp;';
		$jsArrStr = $jsArrStr.'"'.$roleName.'",';
		$i++;
	}
	$jsArrStr = substr($jsArrStr, 0, -1); 
	$jsArrStr = $jsArrStr.");";
?>
					</div></td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input class="submit_btn" type="submit" name="Submit" value="Gem persondata" />&nbsp;&nbsp;
						<button name="send" value="Slet person" class="submit_btn_2" onClick="javascript:return deletePerson();"> Slet person </button>&nbsp;&nbsp;
						<button name="send" value="Annuller" class="submit_btn_2" onClick="javascript:document.getElementById('edit_person').style.display='none';return false;"> Annuller </button>
					</td>
				</tr>
				</table>
				</form>
				
			</div> <!-- edit_person -->
			<p>&nbsp;</p>
			<table id="musiker_table" cellspacing="0" cellpadding="3" width="780">
			<tr>
				<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Navn</strong></div></td>
				<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Adresse</strong></div></td>
				<td height="15" background="img/tabletop_bg.gif"><div align="left"><strong>Tlf/Mobil</strong></div></td>
				<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Mail</strong></div></td>
				<td height="15" background="img/tabletop_bg.gif"><div align="left"><strong>Evner/Instrumenter</strong></div></td>
				<td height="15" background="img/tabletop_bg.gif"><div align="left"><strong>Teams</strong></div></td>
				<td height="15" background="img/tabletop_bg.gif"><div align="center"><div align="center"><span class="style3">Ret</span></div></div></td>
			</tr>
<?php
	$query = "SELECT Bruger.BrugerID,Bruger.Fornavn,Bruger.Efternavn,Bruger.Adresse1,Bruger.Adresse2,Bruger.Telefon,Bruger.Mobil,Bruger.EMail FROM Bruger ORDER BY Bruger.Efternavn";

	$result = doSQLQuery($query);
	$colour = "";
	while ($line = db_fetch_array($result)) {
		$brugerid = stripslashes($line["BrugerID"]);
		echo "			<tr>\n";
		echo "				<td".$colour."><div align=\"left\">".stripslashes($line["Fornavn"])." ".stripslashes($line["Efternavn"])."</td>\n"; // Name
		echo "				<td".$colour."><div align=\"left\">".stripslashes($line["Adresse1"])." ".stripslashes($line["Adresse2"])."</td>\n"; // Adress
		$phone = trim(stripslashes($line["Telefon"]));
		$mobile = trim(stripslashes($line["Mobil"]));
		$phone = $phone . ($phone != "" && $mobile != "" ? " / ".$mobile : $mobile);
		echo "				<td".$colour."><div align=\"left\">".$phone."</td>\n"; // Telephone and mobile
		echo "				<td".$colour."><div align=\"left\">".stripslashes($line["EMail"])."</td>\n"; // email

		// Find and list abilities
		echo "			<td".$colour."><div align=\"left\">";
		$abilityQuery = "SELECT Rolle.Navn FROM Rolle INNER JOIN BrugerRolle ON Rolle.RolleID=BrugerRolle.RolleID WHERE BrugerRolle.BrugerID=".$brugerid.";";
		$abilityResult = doSQLQuery($abilityQuery);
		$abilities = "";
		while ($abilityLine = db_fetch_array($abilityResult)) {
			if ($abilities != "") $abilities = $abilities . ", ";
			$abilities = $abilities . $abilityLine["Navn"];
		}
		echo $abilities."</td>\n"; // Abilities

		// Find and list teams
		echo "			<td".$colour."><div align=\"left\">";
		$teamsQuery = "SELECT Team.Navn FROM Team INNER JOIN TeamBruger ON Team.TeamID=TeamBruger.TeamID WHERE TeamBruger.BrugerID=".$brugerid.";";
		$teamsResult = doSQLQuery($teamsQuery);
		$teams = "";
		while ($teamsLine = db_fetch_array($teamsResult)) {
			if ($teams != "") $teams = $teams . ", ";
			$teams = $teams . $teamsLine["Navn"];
		}
		echo $teams."</td>\n"; // Teams

		echo "				<td".$colour."><div align=\"left\"><a href=\"javascript:editPerson(".$brugerid.")\">Ret</a></td>\n"; // edit-icons

		echo "			</tr>\n"; // end of row
		if ($colour == "") {
			$colour = " bgcolor=\"#f2f2f2\"";
		} else {
			$colour = "";
		}
	}
?>
			</table>
<p align="center">
				<button name="send" value="Opret ny person" class="submit_btn_2" onClick="javascript:newPerson();return false;"> Opret ny person </button>
			</p>

		</div> <!--  personer -->
<?php
} // session
?>
