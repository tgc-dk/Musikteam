<?php
include("../db.php");
include("serviceCreator.php");

if(!isset($_GET["email"])) {
     echo "no email";
     exit();
} else {
    $email = $_GET["email"];
}

if(isset($_GET["date"])) {
    $date = $_GET["date"];
} else {
    $date = date("Y-m-d");
}

$query = "SELECT ProgramID FROM Program where Dato = '" . $date . "'";
try {
    openDB();
    $result = doSQLQuery($query);
    while ($line = db_fetch_array($result)) {
        $eventId = $line["ProgramID"];
        $service = new ServiceCreator();
        $service->createFromEventId($eventId);
        $service->sendTo($email, $WEBMASTER_EMAIL);
    }
} finally {
    closeDB();
}

?>
