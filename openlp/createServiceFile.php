<?php
//ini_set("memory_limit","100M");
include("../db.php");
include("serviceCreator.php");
require("openlyrics.php");
require("serviceitem.php");

/*session_start();
setcookie("PHPSESSID",$_COOKIE['PHPSESSID'],time()+1800);

if (isset($_SESSION['logget_ind'])) {*/
openDB();
mb_internal_encoding("UTF-8");

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
