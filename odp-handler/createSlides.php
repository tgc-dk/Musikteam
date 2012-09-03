<?php
//ini_set("memory_limit","100M");
//25.165.824 bytes exhausted (tried to allocate 24.903.741 bytes) 
include("../db.php");
require("odp-handler.php");

/*session_start();
setcookie("PHPSESSID",$_COOKIE['PHPSESSID'],time()+1800);

if (isset($_SESSION['logget_ind'])) {*/
openDB();

class PresentationCreator {

	var $presentation;

	function init()
	{
		$this->presentation = new Presentation();
		$ret = $this->presentation->init();
		if ($ret!="") {
			echo $ret;
		}
		$this->presentation->createStartSlides();
		//$presentation->createSongSlides($title, $author, $slideTextArr);
		//$this->presentation->createEndSlides();
		//$this->presentation->createODPFile();
	}
	
	function insertSong($songid)
	{
		$query = "SELECT Titel,Identifikation,Slides2 FROM Sang WHERE SangId=" . $songid;
		$result = doSQLQuery($query);

		if ($line = mysql_fetch_array($result)) {
			$title = utf8_encode(stripslashes(htmlspecialchars($line["Titel"],ENT_NOQUOTES)));
			$author = utf8_encode(stripslashes(htmlspecialchars($line["Identifikation"],ENT_NOQUOTES)));
			$slides = $line["Slides2"];
		} else {
			return false;
		}

		$query = "SELECT Slidetekst FROM Slide2 WHERE SangID=" . $songid ." ORDER BY SlideID";
		$result = doSQLQuery($query);
		$text4slides = array();

		if ($slides != "") {
			$texts = array();
			while ($line = mysql_fetch_array($result)) {
				$texts[] = utf8_encode(stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES)));
			}
			for($slideIndex = 0; $slideIndex < strlen($slides); $slideIndex++) {
				switch ($slides[$slideIndex]) {
				case 'A':
				    $index = 0;
				    break;
				case 'B':
				    $index = 1;
				    break;
				case 'C':
				    $index = 2;
				    break;
				case 'D':
				    $index = 3;
				    break;
				case 'E':
				    $index = 4;
				    break;
				case 'F':
				    $index = 5;
				    break;
				case 'G':
				    $index = 6;
				    break;
				case 'H':
				    $index = 7;
				    break;
				}

				//$this->insertSongTextPage($author,$title,$texts[$index]);
				$text4slides[] = $texts[$index];
			}
		} else {
			while ($line = mysql_fetch_array($result)) {
				//$this->insertSongTextPage($author,$title,stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES)));
				$text4slides[] = utf8_encode(stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES)));
			}
		}
		$this->presentation->createSongSlides($title, $author, $text4slides);
		return true;
	}
	
	function insertAllSongs()
	{
		$query = "SELECT DISTINCT SangId,Titel,Identifikation,Slides2 FROM Sang ORDER BY Titel";
		$result = doSQLQuery($query);

		$text4slides = array();

		$numsongs = 0;
		while($line = mysql_fetch_array($result)) {
			$songid = $line["SangId"];
			$title = stripslashes(htmlspecialchars($line["Titel"],ENT_NOQUOTES));
			$author = stripslashes(htmlspecialchars($line["Identifikation"],ENT_NOQUOTES));
			$slides = $line["Slides2"];

			$numsongs++;

			$this->insertSongFrontPage($author,$title);

			$slideQuery = "SELECT Slidetekst FROM Slide2 WHERE SangID=" . $songid ." ORDER BY SlideID";
			$slideResult = doSQLQuery($slideQuery);
			if ($slides != "") {
				$texts = array();
				while ($line = mysql_fetch_array($result)) {
					$texts[] = stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES));
				}
				for($slideIndex = 0; $slideIndex < strlen($slides); $slideIndex++) {
					switch ($slides[$slideIndex]) {
					case 'A':
					    $index = 0;
					    break;
					case 'B':
					    $index = 1;
					    break;
					case 'C':
					    $index = 2;
					    break;
					case 'D':
					    $index = 3;
					    break;
					case 'E':
					    $index = 4;
					    break;
					case 'F':
					    $index = 5;
					    break;
					case 'G':
					    $index = 6;
					    break;
					case 'H':
					    $index = 7;
					    break;
					}
					$text4slides[] = $texts[$index];
				}
			} else {
				while ($slideLine = mysql_fetch_array($slideResult)) {
					$text4slides[] = stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES));
				}
			}
		}
		$this->presentation->createSongSlides($title, $author, $text4slides);
		return true;
	}
	
	function returnSlides() {
		return $this->presentation->returnODPFile();
	}

	function saveSlides() {
		$this->presentation->createODPFile("test-slides.odp");
	}

}



$content = new PresentationCreator();
$content->init();

// Insert the new entries
$songcount = $_GET['songcount'];

// Should we do the whole database, or only some chosen ones.
if ($songcount == -1) {
	$content->insertAllSongs();	
} else {
	for ($count = 0; $count < $songcount; $count++) {
		$index = 'Song'.$count;
		$songid = $_GET[$index];
		if ($content->insertSong($songid)==false) {
			echo "ERROR when inserting $songid!";
			break;
		}
		
	}
}
//$content->saveSlides();
echo $content->returnSlides();

closeDB();
//echo "here1\n";




/*
readfile($tmpfile);
@unlink($tmpfile);*/

/*} else {
	echo 'Ikke logget ind';
}*/

?>
