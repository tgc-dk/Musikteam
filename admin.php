
<?php
require("./odp-handler/odp-handler.php");

if (isset($_SESSION['logget_ind']) && isset($_SESSION['admin'])) {


	// Delete a user if requested
	if ($_GET['deleteUser'] != "") {
		$query = "DELETE FROM Bruger WHERE BrugerId = ".$_GET['deleteUser'];
		$result = doSQLQuery($query);

	// Create a user if requested
	} else if ($_POST['brugernavn'] != "") {

		// First make sure a user with this username doesn't exist
		$query = "SELECT BrugerId FROM Bruger WHERE Brugernavn = '".$_POST['brugernavn']."'";
		$result = doSQLQuery($query);
		$line = db_fetch_array($result);
		if ($line) {
			echo "<p>En bruger med det ønskede brugernavn findes allerede!</p>";
		} else {

			if ($_POST['admin'] != "") $admin = 1;
			else $admin = 0;
			$query = "INSERT INTO Bruger (Brugernavn,Kode,Admin,PersonId,Email) VALUES ('".$_POST['brugernavn']."','".md5($_POST['brugernavn']."1234"."musikteam")."',".$admin.",-1,'".$_POST['email']."')";
			$result = doSQLQuery($query);

			// Send an email to the new user with the username and password
			$to = $_POST['email'];
			$subject = "Velkommen til ".$ROOT_URL."!";
			$body = "Hej!\n\nDu er nu blevet oprettet som bruger på http://".$ROOT_URL.", hvilket betyder at du kan rette i sange setlister mm.\n\n".
				"Dit brugernavn er:'".$_POST['brugernavn']."', og dit kodeord er: '".$_POST['brugernavn']."1234'.\n\n".
				"Mvh\n\t ".$WEBMASTER_NAME."\n\n\nPS. Dette er en automatisk genereret e-mail.\n";
			$headers = 'From: '.$WEBMASTER_EMAIL . "\r\n" .
				'Reply-To: '.$WEBMASTER_EMAIL . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			if (mail($to, $subject, $body, $headers)) {
				//echo("<p>Message successfully sent!</p>");
			} else {
				echo("<p>Der blev ikke sendt en mail til den nye bruger pga. fejl.</p>");
			}
		}
		
	// Handle uploaded odp-template
	} else if ($_FILES['uploadTemplate']['name']!="") {
		
		$odp = basename( $_FILES['uploadTemplate']['name']);
		$uploadpath = getcwd() . "/odp-handler/upload/" . $odp;
		if (move_uploaded_file($_FILES['uploadTemplate']['tmp_name'], $uploadpath)) {
			date_default_timezone_set('Europe/Copenhagen');

			$handler = new NewLayoutHandler();
			$ret = $handler->init($uploadpath, $odp);
			if (strstr($ret, "ERROR") !== FALSE) {
				echo("<p>".$ret."</p>");
			} else {
				$ret = $handler->extract();
				if (strstr($ret, "ERROR") !== FALSE) {
					echo("<p>".$ret."</p>");
				} else {
					$ret = $handler->postProcess();
					if (strstr($ret, "ERROR") !== FALSE) {
						echo("<p>".$ret."</p>");
					}
				}
			}
		}
	}

?>
	<p></p>

	<table id="musiker_table" cellspacing="0" cellpadding="3" width="450">
	<tr background="img/tabletop_bg.gif">
		<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Brugernavn</strong></div></td>
		<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>E-mail</strong></div></td>
		<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Admin</strong></div></td>
		<td height="15" background="img/tabletop_bg.gif"><div align="center"><div align="center"><span class="style3">Slet</span></div></div></td>
		<td height="15" background="img/tabletop_bg.gif"></td>
		<td height="15" background="img/tabletop_bg.gif"></td>
	</tr>

<?php
	$query = "SELECT BrugerId,Brugernavn,Email,Admin FROM Bruger ORDER BY Brugernavn";
	$result = doSQLQuery($query);

	$colour = "";
	$user = 0;
	while ($line = db_fetch_array($result)) {
		$userId = $line["BrugerId"];
		echo "			<tr".$colour.">\n";
		echo "				<td>".stripslashes($line["Brugernavn"])."</td>\n"; // Username
		echo "				<td><input type=\"text\" id=\"email".$user."\" name=\"email".$user."\" value=\"".stripslashes($line["Email"])."\" size=30 /></td>\n"; // Email
		$checked = "";
		if ($line["Admin"] == 1) $checked = " checked";
		echo "				<td><div align=\"center\"><input type=\"checkbox\" id=\"admin".$user."\" name=\"admin".$user."\"".$checked." /> </div></td>\n"; // Email
		echo "				<td><a href=\"javascript:deleteUser(".$userId.")\"><img src=\"img/slet.gif\" alt=\"Ret\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n"; // delete-icons
		echo "				<td><input class=\"submit_btn\" type=\"button\" value=\"Gem ændringer\" onclick=\"javascript:saveUser(".$user.",".$userId.")\" /></td>";
		echo "				<td><input class=\"submit_btn\" type=\"button\" value=\"Ny kode\" onclick=\"javascript:newPassword(".$user.",".$userId.")\" /></td>";
		if ($colour == "") {
			$colour = " bgcolor=\"#f2f2f2\"";
		} else {
			$colour = "";
		}
		$user++;
	}
?>
	</table>

	<br />
	
	<form id="personform" name="personform" method="post" action="main.php?page=admin">
	<table id="musiker_table" cellspacing="0" cellpadding="3" width="550">
		<tr><td height="15" background="img/tabletop_bg.gif" colspan="2"><div align="left"><strong>Tilføj ny bruger:</strong></div></td></tr>
		<tr bgcolor="#f2f2f2">
			<td align="right">Brugernavn: <input type="text" id="brugernavn" name="brugernavn" size="45"></td>
		</tr>
		<tr>
			<td align="right">E-mail: <input type="text" id="email" name="email" size="45"></td>
		</tr>
		<tr bgcolor="#f2f2f2">
			<td align="center">Administrator: <input type="checkbox" id="admin" name="admin"></td>
		</tr>
		<tr>
			<td align="center"><input class="submit_btn" type="submit" name="Submit" value="Opret bruger" /></td>
		</tr>
	</table>
	</form>
	
	<p>
	
	<form id="slideform" name="slideform" method="post" action="main.php?page=admin" enctype="multipart/form-data">
	<table id="musiker_table" cellspacing="0" cellpadding="3" width="550">
		<tr><td height="15" background="img/tabletop_bg.gif" colspan="3"><div align="left"><strong>Slide skabeloner:</strong></div></td></tr>
<?php
	$colour = " bgcolor=\"#f2f2f2\"";
	if ($handle = opendir("./odp-handler/templates/")) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry == "." || $entry == "..") continue;
			
			echo "<tr $colour>\n<td>$entry</td>\n";
			echo "<td><a href=\"javascript:deleteTemplate('".$entry."')\"><img src=\"img/slet.gif\" alt=\"Ret\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
			if (file_exists("./odp-handler/templates/".$entry."/default")) {
				echo "<td>Valgt som standard</td>\n";
			} else {
				echo "<td><input class=\"submit_btn\" type=\"button\" value=\"Vælg som standard\" onclick=\"javascript:setDefaultTemplate('".$entry."')\" /></td>\n";
			}
			echo "</tr>\n";
			
			if ($colour == "") {
				$colour = " bgcolor=\"#f2f2f2\"";
			} else {
				$colour = "";
			}
		}
	}
	echo "<tr $colour>"; 
?>
		<td align="right" colspan="3">Tilføj ny skabelon: <input type="file" name="uploadTemplate" size="30"/> <input class="submit_btn" type="submit" name="Submit" value="Tilføj" /></td></tr>
	</table>
	</form>
	<p>

	<button name="send" onclick="window.location='odp-handler/createSlides.php?songcount=-1';" value="Generer præsentation med alle sange" class="submit_btn"> Generer præsentation med alle sange </button>
	<button name="send" onclick="window.location='openlp/createServiceFile.php?songcount=-1';" value="Generer OpenLP-fil med alle sange" class="submit_btn"> Generer OpenLP-fil med alle sange </button>


<?php
} // seesion and admin
?>

