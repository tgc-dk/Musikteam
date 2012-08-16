<?php
	include("db.php");
	session_start();
	
	switch($_POST["mode"])
	{
	case "addFavorite":
		$sangid = $_POST["sangid"];
		$brugerid = $_SESSION["brugerid"];
		$sql = "INSERT INTO SangBruger VALUES (".$brugerid.",".$sangid.")";
		openDB();
		doSQLQuery($sql);
		closeDB();
		echo "<img src=\"img/favorit_checked.gif\" alt=\"Fjern favorit\" width=\"16\" height=\"16\" style=\"cursor: pointer;\" onclick=\"FavoriteSong(".$sangid.",'removeFavorite');\" />";
		break;

	case "removeFavorite":
		$sangid = $_POST["sangid"];
		$brugerid = $_SESSION["brugerid"];
		$sql = "DELETE FROM SangBruger WHERE brugerid = ".$brugerid." AND sangid = ".$sangid;
		openDB();
		doSQLQuery($sql);
		closeDB();
		echo "<img src=\"img/favorit_unchecked.gif\" alt=\"Tilføj favorit\" width=\"16\" height=\"16\" style=\"cursor: pointer;\" onclick=\"FavoriteSong(".$sangid.",'addFavorite');\" />";
		break;

	default:
		echo "";
		break;
	}
	/*
	openDB();
	$sql = "SELECT DISTINCT Count(Sang.SangId) AS antal FROM Sang INNER JOIN Slide2 ON Sang.SangId = Slide2.SangID WHERE Slide2.Slidetekst Like '%".$_POST["textfield"]."%'";
	$rs = doSQLQuery($sql);

	odbc_fetch_row($rs);
	$number = db_result($rs, 1);;

	echo "<label id=\"labelSearchResult\">" . $number . "</label>";
	
	closeDB();*/
?>
