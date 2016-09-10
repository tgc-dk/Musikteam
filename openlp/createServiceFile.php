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
    $content->createFromEventId($eventId);
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
