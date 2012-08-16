<?php

// The root url of the site, must be for the form "musikteam.com" (do not include http://)
$ROOT_URL = "www.musikteam.com/";

// Name of the admin
$WEBMASTER_NAME = "Københavnerkirkens Musikteam";

// Email address of the admin
$WEBMASTER_EMAIL = "admin@musikteam.com";

// DB type, supported: "mysql", "odbc"
$DB_TYPE = "mysql";

$conn;

function db_fetch_array($result) {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		$ret = mysql_fetch_array($result);
	} else if ($DB_TYPE == "odbc") {
		$ret = odbc_fetch_array($result);
	}
	return $ret;
}

function db_result($result, $field) {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		$ret = mysql_result($result, 0, $field);
	} else if ($DB_TYPE == "odbc") {
		$ret = odbc_result($result, $field);
	}
	return $ret;

}

function openDB() {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		$user="username";
		$password="password";
		$database="musikteam";
		$host = "localhost";
		mysql_connect($host,$user,$password);
		mysql_select_db($database) or die( "Unable to select database");
	} else if ($DB_TYPE == "odbc") {
		global $conn;
		$conn = odbc_connect("MusikTeam", "", "");
	}

}

function closeDB() {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		mysql_close();
	} else if ($DB_TYPE == "odbc") {
		global $conn;
		odbc_close($conn);
	}
}

function doSQLQuery($query) {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		$result =  mysql_query($query) or die("Query failed : " . mysql_error());
	} else if ($DB_TYPE == "odbc") {
		global $conn;
		$result = odbc_exec($conn, $query) or die("Query failed : " . odbc_error());
	}
	return $result;
}

function CreateLikeClause($strField, $strCriteria) {

	$clause = "";
	$strTemp = "";

	$strCriteria = str_replace("  "," ", $strCriteria);

	//$strCriteria = preg_replace("\'","\'\'", $strCriteria);

//    foreach (split(" ", $strCriteria) as $strWord) {
    foreach (preg_split("/ /", $strCriteria) as $strWord) {
		$strTemp .= $strField . " LIKE '%" . $strWord . "%' AND ";
	}

	if ($strTemp != "") {
		$strTemp = substr($strTemp, 0, strlen($strTemp) - 5);
		$clause = "(" . $strTemp . ")";
	} else {
		$clause = "";
	}
	return $clause;
}

function db_fix_str($strIn) {
	$str = utf8_decode($strIn);
	$str = str_replace("‘", "'", $str);
	$str = str_replace("’", "'", $str);
	$str = str_replace("”", '"', $str);
	$str = str_replace("“", '"', $str);
	$str = str_replace("–", "-", $str);
	$str = str_replace("…", "...", $str);
	//$str = str_replace("'", "\'", $str);
	$str = mysql_real_escape_string($str);
	return $str;
}

function getPersonId()
{
	$query = "SELECT PersonId FROM Bruger WHERE Brugerid = ".$_SESSION['brugerid'];
	$result = doSQLQuery($query);
	if(db_fetch_array($result))
		return db_result($result,"PersonId");
	else
		return 0;
}

function convDate($dbDate)
{
	return substr($dbDate,8,2)."/".substr($dbDate,5,2)."/".substr($dbDate,0,4);
}
?>
