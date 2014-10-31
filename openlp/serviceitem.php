<?php

class Header {
	public $xml_version;
	public $auto_play_slides_loop;
	public $auto_play_slides_once;
	public $will_auto_start;
	public $title;
	public $capabilities;
	public $theme;
	public $background_audio;
	public $icon;
	public $type;
	public $start_time;
	public $from_plugin;
	public $media_length;
	public $data;
	public $timed_slide_interval;
	public $audit;
	public $search;
	public $name;
	public $footer;
	public $notes;
	public $plugin;
	public $theme_overwritten;
	public $end_time;
	public $processor;
	
	function __construct($title, $author, $variant) {
		if (strlen(trim($author)) == 0) {
			$author = ".";
		}
		$this->xml_version = "";
		$this->auto_play_slides_loop = false;
		$this->auto_play_slides_once = false;
		$this->will_auto_start = false;
		$this->title = $title;
		$this->capabilities = array(2, 1, 5, 8, 9, 13);
		$this->theme = null;
		$this->background_audio = array();
		$this->icon = ":/plugins/plugin_songs.png";
		$this->type = 1;
		$this->start_time = 0;
		$this->from_plugin = false;
		$this->media_length = 0;
		// Clean title and variant for non-word chars
		$tmp_title = preg_replace('/[^\p{L}\p{N}]+/u',' ', $title);
		$tmp_title = trim(preg_replace('/\s+/',' ', $tmp_title));
		$tmp_variant = preg_replace('/[^\p{L}\p{N}]+/u',' ', $variant);
		$tmp_variant = trim(preg_replace('/\s+/',' ', $tmp_variant));
		$dataTitle = strtolower($tmp_title . "@");
		if (strlen($tmp_variant) > 0) {
			$dataTitle .= strtolower($tmp_title. " " . $tmp_variant);
		}
		$this->data = array("authors" => $author, "title" => $dataTitle);
		$this->timed_slide_interval = 0;
		$this->audit = array($title, array($author), "", "");
		$this->search = "";
		$this->name = "songs";
		$this->footer = array($title, $author, "");
		$this->notes = "";
		$this->plugin = "songs";
		$this->theme_overwritten = false;
		$this->end_time = 0;
		$this->processor = null;
	}
}

class Verse {
	public $verseTag;
	public $raw_slide;
	public $title;
}

class ServiceItem {

	public $header;
	public $data;

	function __construct($title, $author, $variant) {
		$this->header = new Header($title, $author, $variant);
		$this->data = array();
	}

	function addVerse($tag, $versetext) {
		$verse = new Verse();
		$verse->verseTag = $tag;
		$verse->title = mb_substr($versetext, 0, 30);
		$verse->raw_slide = $versetext;
		$this->data[] = $verse;
	}
}
/*
$serviceitem = new ServiceItem("Amazing Grace", "John Newton");
$serviceitem->addVerse("A", "Amazing Grace! how sweet the sound
That saved a wretch like me;
I once was lost, but now am found,
Was blind, but now I see.");

$serviceitem->addVerse("B", "'Twas grace that taught my heart to fear,
And grace my fears relieved;
How precious did that grace appear,
The hour I first believed!");

echo json_encode($serviceitem, JSON_PRETTY_PRINT);
*/
?>
