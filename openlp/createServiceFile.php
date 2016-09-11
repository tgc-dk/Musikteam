<?php
//ini_set("memory_limit","100M");
include("../db.php");
require("openlyrics.php");
require("serviceitem.php");

/*session_start();
setcookie("PHPSESSID",$_COOKIE['PHPSESSID'],time()+1800);

if (isset($_SESSION['logget_ind'])) {*/
openDB();
mb_internal_encoding("UTF-8");

class ServiceCreator {

	private $serviceItems;

    public $serviceName;

	function __construct()
	{
        $this->serviceName = "service.osz";
		$this->serviceItems = array();
	}
	
	function insertAllSongs()
	{
		$query = "SELECT DISTINCT SangId FROM Sang ORDER BY Titel";
		$result = doSQLQuery($query);
		while ($line = db_fetch_array($result)) {
			$this->insertSong($line['SangId']);
		}
		
	}
	
	function insertSong($songid)
	{
		$query = "SELECT Titel,Identifikation,Slides2,Udgave FROM Sang WHERE SangId=" . $songid;
		$result = doSQLQuery($query);
		
		if ($line = db_fetch_array($result)) {
			$title = stripslashes(htmlspecialchars($line["Titel"],ENT_NOQUOTES));
			$author = stripslashes(htmlspecialchars($line["Identifikation"],ENT_NOQUOTES));
			$variant = stripslashes(htmlspecialchars($line["Udgave"],ENT_NOQUOTES));
			$slides = $line["Slides2"];
		} else {
			return false;
		}
		
		$query = "SELECT MAX(Dato) as modified FROM Historik WHERE SangId=" . $songid;
		$result = doSQLQuery($query);
		$modified = "";
		if ($line = db_fetch_array($result)) {
			$modified = date_format(date_create($line['modified']), "c");
		}
		
		$serviceitem = new ServiceItem($title, $author, $variant);
		$ol = new OpenLyrics();
		if (strlen(trim($author)) > 0) {
			$ol->setAuthor($author);
		} else {
			$ol->setAuthor(".");
		}
		$ol->setTitle($title);
		$ol->setVariant($variant);
		$ol->setModified($modified);
		
		$query = "SELECT Slidetekst FROM Slide2 WHERE SangID=" . $songid ." ORDER BY SlideID";
		$result = doSQLQuery($query);
		$text4slides = array();
		
		$insertedSong = false;
		
		if ($slides != "") {
			$texts = array();
			while ($line = db_fetch_array($result)) {
				$insertedSong = true;
				$slideTekst = preg_replace("/^\s+|(?: ) | (?=\n)+|\s+$/", "", $line["Slidetekst"]);
				$slideTekst = stripslashes(htmlspecialchars($slideTekst,ENT_NOQUOTES));
				$texts[] = $slideTekst;
				$ol->addVerse($slideTekst,ENT_NOQUOTES);
			}
			$verseorder = "";
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
				$serviceitem->addVerse(("V".($index+1)), $texts[$index]);
				$verseorder .= "V".($index+1) . " ";
			}
			$ol->setVerseOrder(trim($verseorder));
		} else {
			$num = 1;
			while ($line = db_fetch_array($result)) {
				$insertedSong = true;
				$ol->addVerse(stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES)));
				$serviceitem->addVerse(("V" .$num), stripslashes(htmlspecialchars($line["Slidetekst"],ENT_NOQUOTES)));
				$num += 1;
			}
		}
		if ($insertedSong == true) {
			$serviceitem->header->xml_version = $ol->getXml();
			$this->serviceItems[] = $serviceitem;
		} else {
			$this->insertCustom($title, $author, "Ingen tekst til denne sang");
		}
		return true;
	}
	
	function insertCustom($title, $author, $text)
	{
		$serviceitem = new ServiceItem($title, $author, "");
		$serviceitem->addVerse(null, $text);
		
		// overwrite header stuff so that it matches custom slides
		$serviceitem->header->xml_version = null;
		$serviceitem->header->capabilities = array(2, 1, 5, 13, 8);
		$serviceitem->header->icon = ":/plugins/plugin_custom.png";
		$serviceitem->header->data = "";
		$serviceitem->header->audit = "";
		$serviceitem->header->name = "custom";
		$serviceitem->header->plugin = "custom";
		
		// insert into list
		$this->serviceItems[] = $serviceitem;
	}
	
	function getJSON()
	{
		$first = true;
		$json = " [ ";
		foreach($this->serviceItems as $item) {
			if ($first) {
				$first = false;
			} else {
				$json .= " , ";
			}
			$json .= "{ \"serviceitem\" : ";
			//$json .= json_encode($item, JSON_PRETTY_PRINT);
			$json .= json_encode($item);
			$json .= " } ";
		}
		$json .= " ] ";
		return $json;
	}
	
	function returnService() {
		$file = tempnam("tmp", "zip");
        $this->saveService($file);
		// Stream the file to the client
		header("Content-Type: application/octet-stream");
		header("Content-Length: " . filesize($file));
		header("Content-Disposition: attachment; filename=" . $this->serviceName);
		readfile($file);
		unlink($file); 

	}
	
	function saveService($filename) {
		$zip = new ZipArchive();
		// Zip will open and overwrite the file, rather than try to read it.
		$zip->open($filename, ZipArchive::OVERWRITE);
		$zip->addFromString('service.osj', $this->getJSON());
		$zip->close();
	}

    function sendTo($emailAddress, $fromEmailAddress) {
		$file = tempnam("tmp", "zip");
        $this->saveService($file);

        $content = file_get_contents($file);
        $content = chunk_split(base64_encode($content));

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;

        // main header (multipart mandatory)
        $headers = "From: " . $fromEmailAddress . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol;

        // message
        $headers .= "--" . $separator . $eol;
        $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 8bit" . $eol;
        $headers .= "Dagens program" . $eol;

        // attachment
        $headers .= "--" . $separator . $eol;
        $headers .= "Content-Type: application/octet-stream; name=\"".$this->serviceName."\"" . $eol;
        $headers .= "Content-Transfer-Encoding: base64" . $eol;
        $headers .= "Content-Disposition: attachment" . $eol;
        $headers .= $content . $eol;
        $headers .= "--" . $separator . "--";

        //SEND Mail
        if (mail($emailAddress, "Dagens program", "", $headers)) {
            echo "mail send ... OK"; // or use booleans here
        } else {
            echo "mail send ... ERROR!";
        }
    }
}

$content = new ServiceCreator();

$eventId = $_GET['eventId'];
// Should we do the whole database, or only some chosen ones.
if ($eventId) {
	$content->insertCustom(" ", "", "  ");

    $query = "SELECT SangID, Dato FROM ProgramPunkt INNER JOIN Program ON ProgramPunkt.ProgramID = Program.ProgramID WHERE Program.ProgramID = " . $eventId ." ORDER BY Raekkefoelge";
    $result = doSQLQuery($query);
    $line = db_fetch_array($result);
    $content->serviceName = substr($line['Dato'], 0, 10) . ".osz";
	do {
        if ($content->insertSong($line['SangID'])==false) {
			echo "ERROR when inserting $songid!";
			break;
		}
    	$content->insertCustom(" ", "", "  ");
    } while ($line = db_fetch_array($result));

} else {
	$content->insertAllSongs();
}

if($_GET['email']) {
    $content->sendTo($_GET['email'], $WEBMASTER_EMAIL);
} else {
    echo $content->returnService();
}

closeDB();

/*
}
*/

?>
