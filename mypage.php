
<?php
if (isset($_SESSION['logget_ind'])) {
    $brugerid = isset($_POST['brugerID']) ? $_POST['brugerID']: '';
	if ($brugerid != "") {
		$query = "UPDATE Bruger SET Fornavn = '" . addslashes($_POST['fornavn']) ."',".
				" Efternavn = '" . addslashes($_POST['efternavn']) ."'".
				" WHERE BrugerId = " . $_POST['brugerID'];
                echo $query;
		$result = doSQLQuery($query);	
	}

	$query = "SELECT Email,BrugerId,ForNavn, Efternavn FROM Bruger WHERE Brugerid = '".$_SESSION['brugerid']."'";
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$brugernavn = $_SESSION['brugernavn'];
	$email = $line["Email"];
	$brugerid = $line["BrugerId"];

?>
	<p></p>
	<form id="mypform" name="mypform" method="post" action="main.php?page=mypage">

		<input type="hidden" name="brugerID" id="brugerID" value="<?php echo $brugerid; ?>">
		<table id="musiker_table" cellspacing="0" cellpadding="3" width="600">
			<tr><td height="15" background="img/tabletop_bg.gif" colspan="2"><div align="left"><strong>Om mig:</strong></div></td></tr>
			<tr bgcolor="#f2f2f2">
				<td align="left">Fornavn: <input type="text" name="fornavn" value="<?php echo $line["ForNavn"]; ?>" /> Efternavn: <input type="text" name="efternavn" value="<?php echo $line["Efternavn"]; ?>" /></td>
			</tr>
			<tr>
				<td align="left">E-mail: <?php echo $email; ?></td>
			</tr>
			<tr bgcolor="#f2f2f2">
				<td align="center"><input class="submit_btn" type="submit" name="Submit" value="Gem ændringer" /></td>
			</tr>

		</table>
	</form>

	<p></p>

	<table id="musiker_table" cellspacing="0" cellpadding="3" width="600">
	<tr>
		<td height="15" background="img/tabletop_bg.gif"><div align="left"><strong>Setlister hvor jeg er på:</strong></div></td>
	</tr>

<?php
	if ($DB_TYPE == "mysql") {
		$query = "SELECT Program.Dato,Program.ProgramId,Program.Arrangement FROM Program INNER JOIN ProgramBruger ON ProgramBruger.ProgramID=Program.ProgramID WHERE ProgramBruger.BrugerID=".$brugerid." AND DateDiff(Dato, '$curyear-$curmonth-$today') > -1 ORDER BY Dato LIMIT 3;";
	} else if ($DB_TYPE == "odbc") {
		$query = "SELECT TOP 3 Program.Dato,Program.ProgramId,Program.Arrangement FROM Program INNER JOIN ProgramBruger ON ProgramBruger.ProgramID=Program.ProgramID WHERE ProgramBruger.BrugerID=".$brugerid." AND DateDiff('s', Dato, '$curyear-$curmonth-$today') < 1 ORDER BY Dato;";
	}
	$result = doSQLQuery($query);

	$colour = "";
	while ($line = db_fetch_array($result)) {
		echo "			<tr".$colour.">\n";
		$tmp = $line["Dato"];
		$eventDate = substr($tmp,8,2)."/".substr($tmp,5,2)."/".substr($tmp,0,4);
		echo "				<td><a href=\"main.php?page=program&eventId=".$line["ProgramId"]."\">".$line["Arrangement"]." d. ".$eventDate."</a></td>\n";
		echo "			</tr>\n";

		if ($colour == "") {
			$colour = " bgcolor=\"#f2f2f2\"";
		} else {
			$colour = "";
		}
	}
?>
	</table>

<?php

} // seesion and admin
?>
