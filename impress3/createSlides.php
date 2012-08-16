<?php
//ini_set("memory_limit","100M");
//25.165.824 bytes exhausted (tried to allocate 24.903.741 bytes) 
include("../db.php");

/*session_start();
setcookie("PHPSESSID",$_COOKIE['PHPSESSID'],time()+1800);

if (isset($_SESSION['logget_ind'])) {*/
openDB();

class Presentation {

	var $slideNum;
	var $content;

	function init()
	{
		$this->slideNum = 0;
		// Load the XML header and style setup
		$this->content = implode('', file('header.txt'));
	}

	function insertWhitePage()
	{
		$this->slideNum++;
		$this->content .= '<draw:page draw:name="page'.$this->slideNum.'" draw:style-name="dp1" draw:master-page-name="Standard"><office:forms form:automatic-focus="false" form:apply-design-mode="false"/><draw:rect draw:style-name="gr1" draw:text-style-name="P1" draw:layer="layout" svg:width="28cm" svg:height="21cm" svg:x="0cm" svg:y="0cm"><text:p/></draw:rect><presentation:notes draw:style-name="dp2"><draw:page-thumbnail draw:style-name="gr2" draw:layer="layout" svg:width="14.848cm" svg:height="11.136cm" svg:x="3.075cm" svg:y="2.257cm" draw:page-number="'.$this->slideNum.'" presentation:class="page"/><draw:frame presentation:style-name="pr1" draw:layer="layout" svg:width="16.799cm" svg:height="13.365cm" svg:x="2.1cm" svg:y="14.107cm" presentation:class="notes" presentation:placeholder="true"><draw:text-box/></draw:frame></presentation:notes></draw:page>';
	}

	function getSMStext()
	{
		$sms = '<draw:frame draw:style-name="gr3" draw:text-style-name="P2" draw:layer="layout" svg:width="3.5cm" svg:height="2.384cm" svg:x="24.8cm" svg:y="0.2cm"><draw:text-box><text:p text:style-name="P2"><text:span text:style-name="T1">SMS:</text:span></text:p><text:p text:style-name="P2"><text:span text:style-name="T1"/></text:p><text:p text:style-name="P2"><text:span text:style-name="T1">606KIRKE</text:span></text:p></draw:text-box></draw:frame>';
		return $sms;
	}

	function insertBlankSlide()
	{
		$this->slideNum++;
		$this->content .= '<draw:page draw:name="page'.$this->slideNum.'" draw:style-name="dp1" draw:master-page-name="Standard"><office:forms form:automatic-focus="false" form:apply-design-mode="false"/><presentation:notes draw:style-name="dp2"><draw:page-thumbnail draw:style-name="gr2" draw:layer="layout" svg:width="14.848cm" svg:height="11.136cm" svg:x="3.075cm" svg:y="2.257cm" draw:page-number="'.$this->slideNum.'" presentation:class="page"/><draw:frame presentation:style-name="pr1" draw:layer="layout" svg:width="16.799cm" svg:height="13.365cm" svg:x="2.1cm" svg:y="14.107cm" presentation:class="notes" presentation:placeholder="true"><draw:text-box/></draw:frame></presentation:notes></draw:page>';
	}

	function insertSongFrontPage($author, $title)
	{
		$this->slideNum++;
		$this->content .= '<draw:page draw:name="page'.$this->slideNum.'" draw:style-name="dp1" draw:master-page-name="Standard"><office:forms form:automatic-focus="false" form:apply-design-mode="false"/><draw:custom-shape draw:style-name="gr4" draw:text-style-name="P4" draw:layer="layout" svg:width="28cm" svg:height="4.262cm" svg:x="0cm" svg:y="8.217cm"><text:p text:style-name="P3"><text:span text:style-name="T2">'.$title.'</text:span></text:p>';
		if ($author != "") $this->content .= '<text:p text:style-name="P3"><text:span text:style-name="T3">('.$author.')</text:span></text:p>';
		$this->content .= '<draw:enhanced-geometry svg:viewBox="0 0 21600 21600" draw:mirror-horizontal="false" draw:mirror-vertical="false" draw:type="mso-spt202" draw:enhanced-path="M 0 0 L 21600 0 21600 21600 0 21600 0 0 Z N"/></draw:custom-shape><presentation:notes draw:style-name="dp2"><draw:page-thumbnail draw:style-name="gr2" draw:layer="layout" svg:width="14.848cm" svg:height="11.136cm" svg:x="3.075cm" svg:y="2.257cm" draw:page-number="'.$this->slideNum.'" presentation:class="page"/><draw:frame presentation:style-name="pr1" draw:layer="layout" svg:width="16.799cm" svg:height="13.365cm" svg:x="2.1cm" svg:y="14.107cm" presentation:class="notes" presentation:placeholder="true"><draw:text-box/></draw:frame></presentation:notes></draw:page>';
	}

	function doWhiteSpaces($text)
	{

/*		// count any space-characters infront of the text
		$preSpaces = 0;
		if (strlen($text) > 0) {
			while ($text[$preSpaces] == ' ') {
				$preSpaces++;
			}
		}

		$text = trim($text);
*/
		// Check if there is any double spaces, which will need to be replaced by a xml-tag
		$tmptext = "";
		$index = 0;
		$pos = 0;
		if (strpos($text, "  ") !== FALSE) {
			while(1) {
				$pos = strpos($text, "  ", $index);
				if ($pos === FALSE) {
					$tmptext .= substr($text, $index);
					break;
				}
				$tmptext .= substr($text, $index, $pos-$index);
				
				$curPos = $pos;
				while($text[++$curPos] == ' ');
				$tmptext .= "<text:s text:c=\"".($curPos-$pos)."\"/>";
				
				$index = $curPos;
			}
		} else {
			$tmptext = $text;
		}

		// Replace tabs with tags
		$tmptext = str_replace("\t", "<text:span text:style-name=\"T4\"><text:tab/></text:span>", $tmptext);

		return $tmptext;
	}
	
	function insertSongTextPage($author, $title, $text)
	{
		$this->slideNum++;

		$this->content .= '<draw:page draw:name="page'.$this->slideNum.'" draw:style-name="dp1" draw:master-page-name="Standard"><office:forms form:automatic-focus="false" form:apply-design-mode="false"/><draw:custom-shape draw:style-name="gr5" draw:text-style-name="P6" draw:layer="layout" svg:width="25cm" svg:height="2.316cm" svg:x="0cm" svg:y="0.304cm"><text:p text:style-name="P5"><text:span text:style-name="T3">'.$title.'</text:span></text:p>';
		if ($author != "") $this->content .= '<text:p text:style-name="P5"><text:span text:style-name="T4">('.$author.')</text:span></text:p>';
		$this->content .= '<draw:enhanced-geometry svg:viewBox="0 0 21600 21600" draw:type="mso-spt202" draw:enhanced-path="M 0 0 L 21600 0 21600 21600 0 21600 0 0 Z N"/></draw:custom-shape><draw:custom-shape draw:style-name="gr6" draw:text-style-name="P8" draw:layer="layout" svg:width="24.002cm" svg:height="16.047cm" svg:x="0.498cm" svg:y="3.504cm">';

		$textLines = explode("\n",$text);
		$numLines = count($textLines);
		for($e = 0; $e < $numLines; $e++)
		{
/*			if ($e+1 != $numLines) {
				$line = substr($textLines[$e],0,-1);
			} else {*/
				$line = $textLines[$e];
			//}
			$this->content .= '<text:p text:style-name="P7"><text:span text:style-name="T5">'.$this->doWhiteSpaces($line).'</text:span></text:p>';
		}
		$this->content .= '<draw:enhanced-geometry svg:viewBox="0 0 21600 21600" draw:type="mso-spt202" draw:enhanced-path="M 0 0 L 21600 0 21600 21600 0 21600 0 0 Z N"/></draw:custom-shape><presentation:notes draw:style-name="dp2"><draw:page-thumbnail draw:style-name="gr2" draw:layer="layout" svg:width="14.848cm" svg:height="11.136cm" svg:x="3.075cm" svg:y="2.257cm" draw:page-number="'.$this->slideNum.'" presentation:class="page"/><draw:frame presentation:style-name="pr1" draw:layer="layout" svg:width="16.799cm" svg:height="13.365cm" svg:x="2.1cm" svg:y="14.107cm" presentation:class="notes" presentation:placeholder="true"><draw:text-box/></draw:frame></presentation:notes></draw:page>';
	}
	function insertSong($songid)
	{
		$query = "SELECT Titel,Identifikation,Slides2 FROM Sang WHERE SangId=" . $songid;
		$result = doSQLQuery($query);

//		if ($line = odbc_fetch_array($result)) {
		if ($line = mysql_fetch_array($result)) {
			$title = stripslashes(htmlspecialchars($line["Titel"],ENT_NOQUOTES));
			$author = stripslashes(htmlspecialchars($line["Identifikation"],ENT_NOQUOTES));
			$slides = $line["Slides2"];
		} else {
			return false;
		}

		$this->insertSongFrontPage($author,$title);
		
		$query = "SELECT Slidetekst FROM Slide2 WHERE SangID=" . $songid ." ORDER BY SlideID";
		$result = doSQLQuery($query);

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
//				$line = odbc_fetch_array($result, $index+1);
				//$line = mysql_fetch_array($result, $index+1);
				//$this->insertSongTextPage($author,$title,stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES)));
				$this->insertSongTextPage($author,$title,$texts[$index]);
			}
		} else {
//			while ($line = odbc_fetch_array($result)) {
			while ($line = mysql_fetch_array($result)) {
				$this->insertSongTextPage($author,$title,stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES)));
			}
		}
		return true;
	}
	function insertAllSongs()
	{
		$query = "SELECT DISTINCT SangId,Titel,Identifikation,Slides2 FROM Sang ORDER BY Titel";
		$result = doSQLQuery($query);

		$numsongs = 0;
//		while($line = odbc_fetch_array($result)) {
		while($line = mysql_fetch_array($result)) {
			$songid = $line["SangId"];
			$title = stripslashes(htmlspecialchars($line["Titel"],ENT_NOQUOTES));
			$author = stripslashes(htmlspecialchars($line["Identifikation"],ENT_NOQUOTES));
			$slides = $line["Slides2"];

			$numsongs++;
//			echo $songid.", ".$title.", ".$author.", ".$slides." \n";

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
//					$slideLine = odbc_fetch_array($slideResult, $index+1);
					//$slideLine = mysql_fetch_array($slideResult, $index+1);
					//$this->insertSongTextPage($author,$title,stripslashes(htmlspecialchars($slideLine["Slidetekst"],ENT_NOQUOTES)));
					$this->insertSongTextPage($author,$title,$texts[$index]);
				}
			} else {
//				while ($slideLine = odbc_fetch_array($slideResult)) {
				while ($slideLine = mysql_fetch_array($slideResult)) {
					$this->insertSongTextPage($author,$title,stripslashes(htmlspecialchars($slideLine["Slidetekst"],ENT_NOQUOTES)));
				}
			}
			//$this->insertBlankSlide();
		}
		return true;
	}

	function close()
	{
		$this->content .= '<presentation:settings presentation:mouse-visible="false"/></office:presentation></office:body></office:document-content>';
	}
	
	function printContent()
	{
		echo $this->content;
	}

	function getContent()
	{
		return utf8_encode($this->content);
	}
}


$content = new Presentation();
$content->init();

// Insert the new entries
$songcount = $_GET['songcount'];

// Should we do the whole database, or only some chosen ones.
if ($songcount == -1) {
	$content->insertAllSongs();	
} else {
	$content->insertWhitePage();
	$content->insertBlankSlide();
	for ($count = 0; $count < $songcount; $count++) {
		$index = 'Song'.$count;
		$songid = $_GET[$index];
		//echo "songid:".$index.":".$songid;
		$content->insertSong($songid);
		$content->insertBlankSlide();

	}
}
$content->close();
//$content->printContent();

closeDB();
//echo "here1\n";


ob_start();

//Load the Library
require('zipfile.php');
$zipfile = new zipfile();

$zipfile->addFile($content->getContent(), "content.xml");

// Add the dirs needed
$dirArr = array("Configurations2", "META-INF", "Pictures", "Thumbnails");
for ($e=0; $e < sizeof($dirArr); $e++)
{
	if($zipfile->addDirContent($dirArr[$e])) {
		echo 'Could not add directory';
		exit();
	}
}

$zipfile->addFileAndRead("settings.xml");
$zipfile->addFileAndRead("mimetype");
$zipfile->addFileAndRead("meta.xml");
$zipfile->addFileAndRead("styles.xml");

echo $zipfile->file(); 

/*
readfile($tmpfile);
@unlink($tmpfile);*/

/*} else {
	echo 'Ikke logget ind';
}*/

?>
