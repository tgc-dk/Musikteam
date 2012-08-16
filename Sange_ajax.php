<?php 
	header('content-type: text/html; charset: utf-8');
	
	session_start();
	
	include("db.php");
	include("function.php");
	$sTextField = utf8_decode($_POST['textfield']);
	$sTitle = $_POST['searchTitle'];
	$sText = $_POST['searchText'];
	$sAuthor = $_POST['searchAuthor'];
	$sProTextOnly = $_POST['searchProTextOnly'];
	
	if ($sTitle != "on" && $sText != "on" && $sAuthor != "on" && $sProTextOnly != "on") {$sTitle = "on";}
	
	$str = "";
	
	$str .= "<table id=\"search_result_table_2\" cellspacing=\"0\" cellpadding=\"3\">";
	$str .= "	<tr>";
	$str .= "		<td height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"><strong>Titel</strong></div></td>";
	$str .= "		<td height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"><strong>Forfatter</strong></div></td>";
	
	$str .= "		<td height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"><strong>Udgave</strong></div></td>";
	$str .= "		<td height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"></div></td>";

	$str .= "		<td width=\"80\" height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"><strong>Sidst spillet d</strong></div></td>";
	$str .= "		<td height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"><strong>Favorit</strong></div></td>";
	$str .= "		<td height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"><strong>Lyt</strong></div></td>";
	$str .= "		<td height=\"15\" background=\"img/tabletop_bg.gif\"><div align=\"center\"><strong>Tilføj</strong></div></td>";

	$str .= "	</tr>";


	// Show all songs, or do a search
	if ($_POST['showall'] && $_POST['showall'] > 0) {
		if ($_POST['showall'] == 2) {
			$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave, sb.BrugerId,Sang.Lydfil FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId WHERE " . CreateLikeClause("Sang.ProTekst", "]") . " ORDER BY Sang.Titel";
		}
		elseif ($_POST['showall'] == 3) {
			$query =  "SELECT TOP 10 Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave, sb.BrugerId, Sang.Lydfil, Count(Sang.SangID) AS SongCount
			FROM ((Sang
			INNER JOIN ProgramPunkt ON Sang.SangId = ProgramPunkt.SangID) 
			INNER JOIN Program ON Program.ProgramID = ProgramPunkt.ProgramID)
			LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId 
			WHERE DateDiff('d', Program.Dato, '$curyear-$curmonth-$today') < 365
			GROUP BY Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil, sb.BrugerId
			ORDER BY Count(Sang.SangID) DESC ";
		}
		elseif ($_POST['showall']==4) {
			$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave, sb.BrugerId,Sang.Lydfil FROM Sang INNER JOIN SangBruger sb ON sb.SangId = Sang.SangId WHERE sb.BrugerId = ".$_SESSION['brugerid']." ORDER BY Sang.Titel";
		} 
		else {
			$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,sb.BrugerId,Sang.Lydfil FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId ORDER BY Sang.Titel";
		}
	} 
	else if ($_POST['textfield'] != '') // If a search text has been entered then do a search
	{
		// Show all songs, or do a search
		// Do the database search and list the result
		$strSearchIn = "";

		if ($sTitle == "on") {
			$strSearchIn .= CreateLikeClause("Sang.Titel", $sTextField);
		}

		if ($sAuthor == "on") {
			if ($strSearchIn != "") {
				$strSearchIn .= " OR ";
			}
			$strSearchIn .= CreateLikeClause("Sang.Identifikation", $sTextField);
		}

		if ($sText == "on") {
			if ($strSearchIn != "") {
				$strSearchIn .= " OR ";
			}
			$strSearchIn .= CreateLikeClause("Sang.ProTekst", $sTextField);
			$strSearchIn .= " OR ";
			$strSearchIn .= CreateLikeClause("Slide2.Slidetekst", $sTextField);
		}

		if ($sProTextOnly == "on") {
			if ($strSearchIn != "") {
				$strSearchIn = "(".$strSearchIn .") AND ";
			}
			$strSearchIn .= CreateLikeClause("Sang.ProTekst", "]");
		}

		$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,sb.BrugerId,Sang.Lydfil FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE (BrugerId = ".$_POST['brugerid']." OR BrugerId Is Null)) sb ON sb.SangId = Sang.SangId WHERE " . $strSearchIn . " ORDER BY Sang.Titel";
	}
	openDB();
	$result = doSQLQuery($query);
	$colour = "";
	while ($line = db_fetch_array($result)) {
		$songid = stripslashes($line["SangId"]);
		$str .= "		<tr".$colour.">\n";
		$str .= "			<td><a href=\"editSong.php?song=".$songid."\" onclick=\"NewWindow(this.href,'song_win','yes');return false;\">".$line["Titel"]."</a></td>\n"; // Title
		$str .= "			<td align=\"center\">".stripslashes($line["Identifikation"])."</td>\n"; // Author
		//$str .= "			<td".$colour."><div align=\"center\">&nbsp;</td>\n"; // Language
		$str .= "			<td align=\"center\">".stripslashes($line["Udgave"])."</td>\n"; // Edition
		$str .= "			<td align=\"center\">&nbsp;</td>\n"; // Toneart
		


	
		// Find the last date the song was used
		$lastQuery = "SELECT Program.Dato FROM Program LEFT OUTER JOIN ProgramPunkt ON Program.ProgramID = ProgramPunkt.ProgramID WHERE ProgramPunkt.SangID =".$songid." ORDER BY Program.Dato DESC";
		$lastResult = doSQLQuery($lastQuery);
		if ($lastLine = db_fetch_array($lastResult)) {
			$tmp = $lastLine["Dato"];
			$date = substr($tmp,8,2)."/".substr($tmp,5,2)."/".substr($tmp,0,4);
		} else {
			$date = "";
		}
		$str .= "			<td align=\"center\">".$date."</td>\n"; // Last played on
		
		// Favorit
		$str .= "<td id=\"favImg".$songid."\"><img style=\"cursor: pointer;\" alt=\"Fjern favorit\" width=\"16\" height=\"16\"";
		$fav = $line["BrugerId"];
		if($fav != "")
			$str .= " src=\"img/favorit_checked.png\" onclick=\"FavoriteSong(".$songid.",'removeFavorite');\" />";
		else
			$str .= " src=\"img/favorit_unchecked.png\" onclick=\"FavoriteSong(".$songid.",'addFavorite');\" />";
		$str .= "</td>";
		// Favorit end

		$audioFile = $line["Lydfil"];
		if ($audioFile != '')
			$str .= "			<td align=\"center\"><a href=\"".$audioFile."\" target=\"_blank\"><img src=\"img/mp3.gif\" alt=\"Lyt\" width=\"16\" height=\"16\" border=\"0\" /></a></div></td>\n"; // Link or audio file
		else
			$str .= "			<td align=\"center\">&nbsp;</td>\n"; // Link or audio file
		
		// Tilføj sang til setlist
		if ($_SESSION['setlist'] && isSongInProgram($songid, $_SESSION['setlist']))
			$str .= "			<td id=\"addImg".$songid."\" align=\"center\"><img src=\"img/list-minus.gif\" alt=\"Fjern sang\" width=\"14\" height=\"14\" border=\"0\" style=\"cursor: pointer;\" align=\"top\" onclick=\"removeSong(".$songid.")\" /></td>\n"; // Remove
		else
			$str .= "			<td id=\"addImg".$songid."\" align=\"center\"><img src=\"img/list-add.gif\" alt=\"Tilf&oslash;j sang\" width=\"14\" height=\"14\" border=\"0\" style=\"cursor: pointer;\" align=\"top\" onclick=\"addSong(".$songid.")\" /></td>\n"; // Add
		
		$str .= "		</tr>\n";


		if ($colour == "") {
			$colour = " bgcolor=\"#f2f2f2\"";
		} else {
			$colour = "";
		}
	}
	closeDB();
	$str .= "</table>";

	echo utf8_encode($str);
?>
	
	
