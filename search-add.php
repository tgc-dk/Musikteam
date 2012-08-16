<?php
session_start();

include("db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
body
{
    background-color: #FFFFFF;
  	font-family: Arial, sans-serif;
}

#form1 {
/*	position:relative;*/
	left:10px;
	top: 10px;
}

.iframewrapper {
/*	margin:0 auto;*/
	text-align: left;
	font-size: 12px;
	
}
#search_result_table {
	width: 100%;/*615px;*/
	/*position:relative;*/
	top: 50px;
	/*left: 10px;*/
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: outset outset outset outset;
	border-color: gray gray gray gray;
	border-collapse: collapse;
	background-color: white;	
}

.submit_btn {
	font-size: 11px;;
	font-family:Arial, Helvetica, sans-serif;
	font-weight: bold;
	width: auto;
	height: 22px;
	border: 1px solid #999;
  	background: white url(img/knap_2.gif) repeat-x;
    padding: 0 .3em;
    overflow: visible;
	z-index:1;
	cursor:pointer;

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

function addSong(name, id)
{
	parent.addSong(name,id);
}

// -->
</script>
</head>

<?php

if (isset($_SESSION['logget_ind'])) {
	$sTitle = $_POST['searchTitle'];
	$sText = $_POST['searchText'];
	$sAuthor = $_POST['searchAuthor'];
	$sProTextOnly = $_POST['searchProTextOnly'];

	if ($sTitle != "on" && $sText != "on" && $sAuthor != "on") {
		$sTitle = "on";
	}

?>
<body>
	<div class="iframewrapper">

		<form id="form1" name="form1" method="post" action="search-add.php">
			<label>
				<input name="textfield" type="text" size="50" value="<?php echo $_POST['textfield']; ?>"/>
			</label>
			<label>&nbsp;
				<input class="submit_btn" type="submit" name="Submit" value="Søg" />

			</label>
			<br />
			<span class="style2">
				Søg i: 
				<INPUT type="checkbox" name="searchTitle" class="checkbox"<?php echo ($sTitle == "on" ? ' checked="true"' : ''); ?>>Titel&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="checkbox" name="searchAuthor" class="checkbox"<?php echo($sAuthor == "on" ? ' checked="true"' : ''); ?>>Forfatter&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="checkbox" name="searchText" class="checkbox"<?php echo ($sText == "on" ? ' checked="true"' : ''); ?>>Tekst&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="checkbox" name="searchProTextOnly" class="checkbox"<?php echo ($sProTextOnly == "on" ? ' checked="true"' : ''); ?>>Søg kun i sange med akkorder
			</span>
			<p>
				<button name="send" value="Vis alle sange" class="submit_btn" onClick="javascript:location.href='search-add.php?page=sange&showall=1';return false;"> Vis alle sange </button>&nbsp;&nbsp;&nbsp;&nbsp;
				<button name="send" value="Vis alle sange m. akkorder" class="submit_btn" onClick="javascript:location.href='search-add.php?page=sange&showall=2';return false;"> Vis alle sange m. akkorder </button>&nbsp;&nbsp;&nbsp;&nbsp;
				<button name="send" value="Vis mine favorit sange" class="submit_btn" onClick="javascript:location.href='search-add.php?page=sange&showall=4';return false;"> Vis mine favorit sange </button>
			</p>
		</form>

<?php
	// If a search text has been entered then do a search
	if ($_POST['textfield'] != '' || $_GET['showall'] > 0) {
?>

		<table id="search_result_table" cellspacing="0" cellpadding="3">
		<tr>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Titel</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Forfatter</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Udgave</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"></div></td>
			<td width="80" height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Sidst spillet d</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Lyt</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Tilføj</strong></div></td>
		</tr>

<?php
		// Show all songs, or do a search
		if ($_GET['showall'] > 0) {
			if ($_GET['showall'] == 2) {
				$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil, sb.BrugerId FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId WHERE " . CreateLikeClause("Sang.ProTekst", "]") . " ORDER BY Sang.Titel";
			} 
			else if($_GET['showall'] == 4) {
				$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil, sb.BrugerId FROM Sang INNER JOIN SangBruger sb ON sb.SangId = Sang.SangId WHERE sb.BrugerId = ".$_SESSION['brugerid']." ORDER BY Sang.Titel";
			}
			else {
				$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil,sb.BrugerId FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId ORDER BY Sang.Titel";
			}
		} else {
			// Do the database search and list the result
			$strSearchIn = "";

			if ($sTitle == "on") {
				$strSearchIn .= CreateLikeClause("Sang.Titel", $_POST['textfield']);
			}

			if ($sAuthor == "on") {
				if ($strSearchIn != "") {
					$strSearchIn .= " OR ";
				}
				$strSearchIn .= CreateLikeClause("Sang.Identifikation", $_POST['textfield']);
			}

			if ($sText == "on") {
				if ($strSearchIn != "") {
					$strSearchIn .= " OR ";
				}
				$strSearchIn .= CreateLikeClause("Sang.ProTekst", $_POST['textfield']);
				$strSearchIn .= " OR ";
				$strSearchIn .= CreateLikeClause("Slide2.Slidetekst", $_POST['textfield']);
			}

			if ($sProTextOnly == "on") {
				if ($strSearchIn != "") {
					$strSearchIn = "(".$strSearchIn .") AND ";
				}
				$strSearchIn .= CreateLikeClause("Sang.ProTekst", "]");
			}
			$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil,sb.BrugerId FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId WHERE " . $strSearchIn . " ORDER BY Sang.Titel";
//			$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil FROM Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID WHERE " . $strSearchIn . " ORDER BY Sang.Titel";
		}

		openDB();

		//$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil FROM Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID WHERE " . $strSearchIn . " ORDER BY Sang.Titel";

		$result = doSQLQuery($query);
		$colour = "";
		while ($line = db_fetch_array($result)) {
			echo "		<tr>\n";
			$songid = $line["SangId"];
			$songtitle = stripslashes($line["Titel"]);
			echo "			<td".$colour."><a href=\"editSong.php?song=".$songid."\" onclick=\"NewWindow(this.href,'song_win','yes');return false;\">".$songtitle."</a></td>\n"; // Title
			echo "			<td".$colour."><div align=\"center\">".stripslashes($line["Identifikation"])."</td>\n"; // Author
			echo "			<td".$colour."><div align=\"center\">".stripslashes($line["Udgave"])."</td>\n"; // Edition
			echo "			<td".$colour."><div align=\"center\">&nbsp;</td>\n"; // Toneart

			// Find the last date the song was used
			$lastQuery = "SELECT Program.Dato FROM Program LEFT OUTER JOIN ProgramPunkt ON Program.ProgramID = ProgramPunkt.ProgramID WHERE ProgramPunkt.SangID =".$songid." ORDER BY Program.Dato DESC";
			$lastResult = doSQLQuery($lastQuery);
			if ($lastLine = db_fetch_array($lastResult)) {
				$tmp = $lastLine["Dato"];
				$date = substr($tmp,8,2)."/".substr($tmp,5,2)."/".substr($tmp,0,4);
			} else {
				$date = "";
			}
			echo "			<td".$colour."><div align=\"center\">".$date."</td>\n"; // Last played on
			$audioFile = $line["Lydfil"];
			if ($audioFile != '') {
				echo "			<td".$colour."><div align=\"center\"><a href=\"".$audioFile."\" target=\"_blank\"><img src=\"img/mp3.gif\" alt=\"Lyt\" width=\"16\" height=\"16\" border=\"0\" /></a></div></td>\n"; // Link or audio file
			} else {
				echo "			<td".$colour."><div align=\"center\">&nbsp;</td>\n"; // Link or audio file
			}
			echo "			<td".$colour."><div align=\"center\"><a href=\"javascript:addSong('".addslashes($songtitle)."', ".$songid.")\"><img src=\"img/list-add.gif\" alt=\"Tilf&oslash;j sang\" width=\"14\" height=\"14\" border=\"0\" align=\"top\" /></a></td>\n"; // Add
			echo "		</tr>\n";


			if ($colour == "") {
				$colour = " bgcolor=\"#f2f2f2\"";
			} else {
				$colour = "";
			}
		}
?>
		</table>
		<p>&nbsp;</p>


<?php
	} // Search
?>
	</div> <!-- wrapper-->
</body>
</html>
<?php
} else {// Session
	echo "no session";
}
?>
