<?php

include("db.php");

session_start();


if (isset($_SESSION['logget_ind'])) {
openDB();

define('FPDF_FONTPATH','font/');

require('fpdf/fpdf.php');

class Songs extends FPDF
{
//Current column
var $col=0;
//Ordinate of column start
var $y0;

function Header()
{
    //Page header
    global $title;

    $this->SetFont('Arial','B',15);
    $w=$this->GetStringWidth($title)+6;
//    $this->SetX((210-$w)/2);
    $this->SetX((297-$w)/2);
//    $this->SetDrawColor(0,80,180);
    $this->SetDrawColor(0,0,0);
//    $this->SetFillColor(200,220,255);
    $this->SetFillColor(200,200,200);
    //$this->SetTextColor(220,50,50);
    $this->SetLineWidth(1);
    $this->Cell($w,9,$title,1,1,'C',1);
    $this->Ln(5);
    //Save ordinate
    $this->y0=$this->GetY();
}

function Footer()
{
    //Page footer
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10,'Side '.$this->PageNo(),0,0,'C');
}

function SetCol($col)
{
    //Set position at a given column
    $this->col=$col;
//    $x=10+$col*65;
    $x=10+$col*92;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    //Method accepting or not automatic page break
    if($this->col<2)
    {
        //Go to next column
        $this->SetCol($this->col+1);
        //Set ordinate to top
        $this->SetY($this->y0);
        //Keep on page
        return false;
    }
    else
    {
        //Go back to first column
        $this->SetCol(0);
        //Page break
        return true;
    }
}

function SongText($text)
{
	//Font
    $this->SetFont('Times','',11);
    //Output text in a 9 cm width column
    $this->MultiCell(90,5,utf8_decode($text));
    $this->Ln();
}

function SongTitle($label)
{
    //Title
    $this->SetFont('Arial','',12);
    $this->SetFillColor(200,200,200);
//    $this->SetFillColor(200,220,255);
    $this->Cell(90,5,utf8_decode("$label"),0,1,'L',1);
    $this->Ln();
}

function SongHeading($heading)
{
    //Title
    $this->Ln();
    $this->SetFont('Arial','',14);
    $this->Cell(90,5,utf8_decode("$heading"),0,0,'C');
    $this->Ln();
}

function PrintSong($title,$text,$heading)
{
    //Add song
	if ($heading != "") $this->SongHeading($heading);
    $this->SongTitle($title);
    $this->SongText($text);
}
}

$title = $_GET['eventName'];

$pdf = new Songs("L");
$pdf->SetTitle($title);
$pdf->SetAuthor($WEBMASTER_NAME);
$pdf->AddPage();

// Insert the new entries
$songcount = $_GET['songcount'];
for ($count = 0; $count < $songcount; $count++) {
	$index = 'Song'.$count;
	$songid = $_GET[$index];
	//echo "songid:".$index.":".$songid;
	$headIndex = 'Heading'.$count;
	$heading = $_GET[$headIndex];

	$query = "SELECT Titel,ProTekst FROM Sang WHERE SangId=" . $songid;
	$result = doSQLQuery($query);
	while ($line = db_fetch_array($result)) {
		$songTitle = stripslashes($line["Titel"]);
		$songText = stripslashes($line["ProTekst"]);

		// Remove protext stuff
		$newtxt = "";
		for ($i=0; $i < strlen($songText); $i++) {
			if (substr($songText,$i,1) == "|") {
				$newtxt .= " ";
			} else if (substr($songText,$i,1) == "[") {
				$i++;
				while (substr($songText,$i,1) != "]" && $i < strlen($songText)) {
					$i++;
				}
			} else {
				$newtxt .= substr($songText,$i,1);
			}
		}
		$pdf->PrintSong($songTitle, $newtxt, $heading);
	}
}
$pdf->Output("sangblad.pdf","D");

closeDB();



} else {
	echo 'Ikke logget ind';
}

?>
