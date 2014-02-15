<?php
session_start();
header("Content-Type: application/xml; charset=utf-8");

if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	openDB();

	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

	echo "<song>\n";
	$query = "SELECT Titel,Identifikation,ProTekst,CommentsOver,CommentsUnder FROM Sang WHERE SangId=".$_POST['song'];
	$result = doSQLQuery($query);
	$line = db_fetch_array($result);
	$songTitle = stripslashes($line["Titel"]);
	$songAuthor = stripslashes($line["Identifikation"]);
	$songText = stripslashes($line["ProTekst"]);
	$commentsAbove = stripslashes($line["CommentsOver"]);
	$commentsBelow = stripslashes($line["CommentsUnder"]);

	echo "<num>".$_POST['num']."</num>\n";
	echo "<songTitle>".$songTitle."</songTitle>\n";
	echo "<songAuthor>".$songAuthor."</songAuthor>\n";
	echo "<songText>".$songText."</songText>\n";
	echo "<songCommentsAbove>".$commentsAbove."</songCommentsAbove>\n";
	echo "<songCommentsBelow>".$commentsBelow."</songCommentsBelow>\n";
	echo "</song>\n";

	closeDB();
} // session
	/*
Output format (more or less)
<song>
	<num>1</num>
	<songTitle>Nu falmer skoven trindt om land</songTitle>
	<songAuthor>Grundtvig</songAuthor>\n";
	<songText>...</songText>
	<songCommentsAbove>...</songCommentsAbove>
	<songCommentsBelow>...</songCommentsBelow>
</song>
*/
?>
