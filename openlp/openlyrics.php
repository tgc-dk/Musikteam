<?php

class OpenLyrics {

	private $title;
	private $author;
	private $verseOrder;
	private $verses;
	private $variant;
	private $modified;
	
	function __construct() {
		$this->title = "";
		$this->author = "";
		$this->verses = array();
	}

	function setAuthor($in) {
		$this->author = $in;
	}

	function setTitle($in) {
		$this->title = $in;
	}

	function setVerseOrder($in) {
		$this->verseOrder = $in;
	}

	function addVerse($verse) {
		//$this->verses[] = str_replace("\n", "&#10;", $verse);
		$this->verses[] = $verse;
	}
	
	function setVariant($in) {
		$this->variant = $in;
	}
	
	function setModified($in) {
		$this->modified = $in;
	}


	function getXml() {
		$song = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><song xmlns=\"http://openlyrics.info/namespace/2009/song\" version=\"0.8\" createdIn=\"Musikteam1\" modifiedIn=\"Musikteam1\"></song>");
		/*$song->addAttribute("xmlns", "http://openlyrics.info/namespace/2009/song");
		$song->addAttribute("version", "0.8");
		$song->addAttribute("createdIn", "Musikteam1");
		$song->addAttribute("modifiedIn", "Musikteam1");*/
		$song->addAttribute("modifiedDate", $this->modified);
		
		$properties = $song->addChild("properties");
		echo $properties;
		// Add title
		$properties->addChild("titles", "");
		$properties->titles->addChild("title", $this->title);
		// OpenLP doesn't use the variant tag but allows for multiple titles
		if (strlen($this->variant) > 0) {
			$properties->titles->addChild("title", $this->title . " - " . $this->variant);
		}

		// Add author
		$properties->addChild("authors");
		$properties->authors->addChild("author", $this->author);

		// Add variant order
		if (strlen($this->variant) > 0) {
			$properties->addChild("variant", $this->variant);
		}

		// Add version
		if (strlen($this->modified) > 0) {
			$properties->addChild("version", $this->modified);
		}

		// Add verse order
		if (strlen($this->verseOrder) > 0) {
			$properties->addChild("verseOrder", $this->verseOrder);
		}
		
		// Add the lyrics
		$lyrics = $song->addChild("lyrics");
		$num = 1;
		foreach ($this->verses as $versetext) {
			$newVerse = $lyrics->addChild("verse");
			$newVerse->addAttribute("name", ("v".$num));
			$lines = $newVerse->addChild("lines", $versetext);
			$num++;
		}
		//return $song->asXML();
		return str_replace("<br />\n", "<br />", str_replace("><br />", ">", nl2br($song->asXML())));
	}

}

/*
$ol = new OpenLyrics();

$ol->setTitle("Amazing Graze");
$ol->setAuthor("John Newton");
$ol->setVerseOrder("AB");

$ol->addVerse("Amazing Grace! how sweet the sound
That saved a wretch like me;
I once was lost, but now am found,
Was blind, but now I see.");

$ol->addVerse("'Twas grace that taught my heart to fear,
And grace my fears relieved;
How precious did that grace appear,
The hour I first believed!");

echo $ol->getXml();
*/
?>
