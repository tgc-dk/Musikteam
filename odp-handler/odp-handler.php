<?php

require('zipfile.php');
require_once dirname(__FILE__)."/dUnzip2.inc.php"; 

/**
 * This code should be able to:
 * 1. Handle a OpenDocument Presentation being uploaded, 
 *    parse the frontpage, blankpage, titlepage and textpage.
 * 2. Create slides for worship based on a uploaded slide and songs
 *    from the database.
 * 
 * 

 */


/**
 * First: handle the upload.
 * 1. create a folder named ny the users name for the layout. (<name>)
 * 2. unzip the odp into the newly created folder: "<name>/odp/".
 * 3. content.xml is slices into 6 parts: Header, frontpage, blankpage,
 *    titlepage, textpage and footer, and saved as 6 different files 
 *    in <name>/
 */ 
class NewLayoutHandler {

    var $filename;
    var $name;
    var $templatedir;

    function init ($filename, $name) {
        $this->templatedir = getcwd()."/odp-handler/templates/";
        $this->filename = $filename;
        $this->name = $name;
        
        // Check if the file exists
        if (!file_exists($this->filename)) {
            return "ERROR: File $filename does not exist on server!";
        }
        
        // Check if the layoutname is already used
        if (file_exists($this->templatedir.$this->name)) {
            return "ERROR: A layout names $name already exists on server!";
        }
        
        return "";
    }
    
    function extract() {
        // Extract the odp using builtin ZipArchive (requires PECL)
        /*$zip = new ZipArchive();
        if ($zip->open($this->filename)) {
            mkdir($this->templatedir.$this->name."/odp", 0777, true);
            if (!$zip->extractTo($this->templatedir.$this->name."/odp/")) {
                $zip->close();
                return "ERROR: Could not unpack ODP-file!";
            }
            $zip->close();
        } else {
            return "ERROR: Could not open ODP-file!";
        }*/
        
        // Extract the odp using dUnZip2 (PHP implementation)
        $zip = new dUnzip2($this->filename);        
        mkdir($this->templatedir.$this->name."/odp", 0777, true);
        $zip->unzipAll($this->templatedir.$this->name."/odp");
        $zip->close();
    }
    
    function postProcess() {
        // Check if the file context.xml exists
        $contentFile = $this->templatedir.$this->name."/odp/content.xml";
        if (!file_exists($contentFile)) {
            return "ERROR: File $contentFile does not exist inside the ODP!";
        }
        
        // open context.xml
        $contentXmlStr = file_get_contents($contentFile);
        $filenames = array("header.xml", "frontpage.xml", 
            "blankpage.xml", "titlepage.xml", "mainpage.xml", "footer.xml");

        // split it into 6 files

        // Find the header xml
        $start = 0;
        $idx = strpos($contentXmlStr, "<draw:page ", $start+1);
        if ($idx < 1) {
            return "ERROR: The ODP does not seem to contain the needed number of slides!";
        }
        $xmlFragment = substr($contentXmlStr, $start, $idx - $start);
        file_put_contents($this->templatedir.$this->name."/header.xml", $xmlFragment);
        $start = $idx;

        // Find the frontpage xml
        $idx = strpos($contentXmlStr, "<draw:page ", $start+1);
        if ($idx < 1) {
            return "ERROR: The ODP does not seem to contain the needed number of slides!";
        }
        $xmlFragment = substr($contentXmlStr, $start, $idx - $start);
        file_put_contents($this->templatedir.$this->name."/frontpage.xml", $xmlFragment);
        $start = $idx;

        // Find the blankpage xml
        $idx = strpos($contentXmlStr, "<draw:page ", $start+1);
        if ($idx < 1) {
            return "ERROR: The ODP does not seem to contain the needed number of slides!";
        }
        $xmlFragment = substr($contentXmlStr, $start, $idx - $start);
        file_put_contents($this->templatedir.$this->name."/blankpage.xml", $xmlFragment);
        $start = $idx;

        // Find the titlepage xml
        $idx = strpos($contentXmlStr, "<draw:page ", $start+1);
        if ($idx < 1) {
            return "ERROR: The ODP does not seem to contain the needed number of slides!";
        }
        $xmlFragment = substr($contentXmlStr, $start, $idx - $start);
        file_put_contents($this->templatedir.$this->name."/titlepage.xml", $xmlFragment);
        $start = $idx;

        // Find the mainpage xml
        $idx = strpos($contentXmlStr, "</draw:page>", $start+1);
        if ($idx < 1) {
            return "ERROR: The ODP does not seem to contain the needed number of slides!";
        }
        $xmlFragment = substr($contentXmlStr, $start, ($idx - $start) + 12); // 12 = length of "</draw:page>"
        file_put_contents($this->templatedir.$this->name."/mainpage.xml", $xmlFragment);
        $start = $idx + 12;

        // get the last xml to the footer
        $xmlFragment = substr($contentXmlStr, $start, strlen($contentXmlStr) - $start);
        file_put_contents($this->templatedir.$this->name."/footer.xml", $xmlFragment);
        
        // Remove content.xml since now we have extracted the needed xml
        unlink($contentFile);
    }
}

class Presentation {
    
    var $header = "";
    var $frontpage = "";
    var $blankpage = "";
    var $titlepage = "";
    var $mainpage = "";
    var $footer = "";
    
    var $slideNum = 1;
    
    var $slides = "";
    
    var $layoutPath = "";
    
    var $templatedir = "";
    
    // Check that the default layout exists, and load it in
    function init () {
        
        $this->templatedir = getcwd()."/templates/";
        
        // Get a list of folders in the layout folder
        //$layouts = scandir(getcwd()."odp-handler/templates/");
        $layouts = scandir(getcwd()."/templates/");
        if ($layouts === FALSE) {
            return "ERROR: No layouts seems to be installed/uploaded!";
        }

        $default = -1;
        for ($i = 2; $i < count($layouts); $i++) {
            if (is_dir($this->templatedir.$layouts[$i])) {
                if (file_exists($this->templatedir.$layouts[$i]."/default")) {
                    $default = $i;
                    break;
                }
            }
        }
        if ($default == -1) {
            return "ERROR: No layouts seems to be marked as default!";
        }

        $folder = $this->templatedir.$layouts[$default]."/";
        $this->layoutPath = $folder;
        
        $this->header = file_get_contents($folder."header.xml");
        $this->frontpage = file_get_contents($folder."frontpage.xml");
        $this->blankpage = file_get_contents($folder."blankpage.xml");
        $this->titlepage = file_get_contents($folder."titlepage.xml");
        $this->mainpage = file_get_contents($folder."mainpage.xml");
        $this->footer = file_get_contents($folder."footer.xml");

        if ($this->header === FALSE || $this->frontpage === FALSE
            || $this->blankpage === FALSE || $this->titlepage === FALSE 
            || $this->mainpage === FALSE || $this->footer === FALSE) {
            
            return "ERROR: The default layout seems to be incomplete!";
        }
    }

    function insertPageNumber($page) {
        // replace the "draw:name="pageX" part
        $newpage = preg_replace('/(.+draw:name=)"page\d+(".+)/i', "\\1\"page".$this->slideNum."\\2", $page);
        // replace the draw:page-number="X" part
        $newpage = preg_replace('/(.+draw:page-number=)"\d+"(.+)/i', "\\1\"".$this->slideNum."\"\\2", $newpage);
        $this->slideNum++;
        return $newpage;
    }

    function insertTitleAuthor($page, $title, $author) {
		/*if ($author == "") {
			$pattern = '/<text:span .+?>.+?songauthor.+?<\/text:span>/';
			$replace = "";
			$newpage = preg_replace($pattern, $replace, $page);			
		} else {
			$newpage = str_replace("songauthor", $author, $page);
		}*/
		$newpage = str_replace("songauthor", $author, $page);
        $newpage = str_replace("songtitle", $title, $newpage);
        return $newpage;
    }

    function doWhiteSpaces($text)
    {


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

    function insertSongText($page, $songText) {
        $songArr = explode("\n", $songText);
        
        $pattern = '/(.+>)(<text:p .+?><text:span .+?>)songtext(<\/text:span>.*?<\/text:p>)(.+)/';

        $replace = "\\1";
        for ($i = 0; $i < count($songArr); $i++) {
            $replace .= "\\2 ".$this->doWhiteSpaces($songArr[$i])."\\3";
        }
        $replace .= "\\4";
        $newpage = preg_replace($pattern, $replace, $page);
        
        return $newpage;
    }


    function createSongSlides($title, $author, $slideTextArr) {
        // First the title page
        $newTitlePage = $this->insertTitleAuthor($this->titlepage, $title, $author);
        $songSlides = $this->insertPageNumber($newTitlePage);
        
        // Then create the slides for the song text
        for ($i = 0; $i < count($slideTextArr); ++$i) {
            $newTextSlide = $this->insertTitleAuthor($this->mainpage, $title, $author);
            $newTextSlide = $this->insertSongText($newTextSlide, $slideTextArr[$i]);
            $songSlides .= $this->insertPageNumber($newTextSlide);
        }
        
        // And then insert a blank slide after the song
        $songSlides .= $this->insertPageNumber($this->blankpage);
        
        $this->slides .= $songSlides;
    }
    
    function createStartSlides() {
        $this->slides = $this->header;
        $this->slides .= $this->insertPageNumber($this->frontpage);
        $this->slides .= $this->insertPageNumber($this->blankpage);
    }

    function createEndSlides() {
        $this->slides .= $this->footer;
    }
    
    function createODPFile($filename) {
        $zipfile = new zipfile();
        $zipfile->addDirContent($this->layoutPath."odp");
        $zipfile->addFile($this->slides, "content.xml");
        file_put_contents($filename, $zipfile->file());
    }
    
    function returnODPFile() {
        $zipfile = new zipfile();
        $zipfile->addDirContent($this->layoutPath."odp");
        $zipfile->addFile($this->slides, "content.xml");
        return $zipfile->file();
    }
}


class RemoveTemplate {

	function checkIfdefault() {
	}
}

/*
date_default_timezone_set('Europe/Copenhagen');

$handler = new NewLayoutHandler();
$ret = $handler->init("testslides.odp", "test-layout3");
if (strstr($ret, "ERROR") !== FALSE) {
    //echo $ret."\n";
    exit();
}

$ret = $handler->extract();
if (strstr($ret, "ERROR") !== FALSE) {
    //echo $ret."\n";
    exit();
}

$ret = $handler->postProcess();

//echo "result: $ret\n";

$title = "Test titel";
$author = "Test Forfatter";
$slideTextArr = array();
$slideTextArr[0] = 
"tralalalala1
tralalalala2
tralalalala3
    tralalalala4
    tralalalala5";

$slideTextArr[1] = 
"tralalalala6
tralalalala7
tralalalala8
tralalalala9
tralalalala10";


$presentation = new Presentation();
$ret = $presentation->init();

$presentation->createStartSlides();
$presentation->createSongSlides($title, $author, $slideTextArr);
$presentation->createEndSlides();
$presentation->createODPFile("testodp.odp");
*/
?>
