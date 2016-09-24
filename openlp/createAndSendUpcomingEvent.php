<?php
include("../db.php");
include("serviceCreator.php");

if(!isset($_GET["key"]) || $_GET["key"] != $ACCESS_KEY) {
    echo "no access";
    exit();
}

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
    $count = 0;
    while ($line = db_fetch_array($result)) {
        $count = $count + 1;
        $eventId = $line["ProgramID"];
        $service = new ServiceCreator();
        $service->createFromEventId($eventId);
        if($count > 1) {
            $service->serviceName = str_replace(".osz", "-" . $count . ".osz", $service->serviceName);
        }
        $service->sendTo($email, $WEBMASTER_EMAIL);
    }
} finally {
    closeDB();
}

?>
