<?php

include("db.php");

openDB();

$sql = file_get_contents("database-setup.sql");

$ret = mysqli_multi_query($conn, $sql);

?>
