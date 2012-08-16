<?php
if (isset($_SESSION['logget_ind'])) {

?>
		<div id="Evner">
			<table id="musiker_table" cellspacing="0" cellpadding="3" width="780">
				<tr><td bgcolor="#f2f2f2"><strong>Eksisterende evner/instrumenter</strong></td></tr>
<?php
	$query = "SELECT RolleID,Navn FROM Rolle ORDER BY RolleID";

	$result = doSQLQuery($query);
	$colour = "";
	while ($line = db_fetch_array($result)) {
		$roleID = $line["RolleID"];
		$roleName = $line["Navn"];
		echo "				<tr><td".$colour.">\n";
		echo "					<form id=\"abilityform".$roleID."\" name=\"abilityform".$roleID."\" method=\"post\" action=\"main.php?page=teams&subpage=abilities&id=".$roleID."\">\n";
		echo "						<input type=\"text\" size=\"30\" value=\"".$roleName."\" name=\"ability\">&nbsp;&nbsp;\n";
		echo "						<input class=\"submit_btn\" type=\"submit\" name=\"Submit\" value=\"Gem ændringer\" />&nbsp;&nbsp;\n";
		echo "						<button name=\"send\" value=\"Slet evne\" class=\"submit_btn_2\" onClick=\"javascript:deleteAbility(".$roleID.");document.abilityform".$roleID.".submit();\"> Slet evne </button>\n";
		echo "					</form>\n";
		echo "				</td></tr>\n";
		if ($colour == "") {
			$colour = " bgcolor=\"#f2f2f2\"";
		} else {
			$colour = "";
		}
	}
	echo "				<tr><td".$colour.">\n";
	echo "					<form id=\"newabilityform\" name=\"newabilityform\" method=\"post\" action=\"main.php?page=teams&subpage=abilities\">\n";
	echo "						<input type=\"hidden\" name=\"newability\" id=\"newability\" value=\"\">\n";
	echo "						<button name=\"send\" value=\"Opret ny evne\" class=\"submit_btn_2\" onClick=\"javascript:return newAbility();\"> Opret ny evne </button>\n";
	echo "					</form>\n";
	echo "				</td></tr>\n";
?>

			</table>
		</div> <!--  Evner -->
<?php
} // session
?>
