<?php

// The root url of the site, must be for the form "musikteam.com" (do not include http://)
$ROOT_URL = "localhost/musikteam/";

// Name of the admin
$WEBMASTER_NAME = "Københavnerkirkens Musikteam";

// Email address of the admin
$WEBMASTER_EMAIL = "admin@musikteam.com";

// DB type, supported: "mysql", "odbc"
$DB_TYPE = "mysql";

// temp mysql array, used by the db_result function
$result_arr;

// DB Connection
$conn;

function db_fetch_array($result) {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		$ret = mysqli_fetch_array($result);
	} else if ($DB_TYPE == "odbc") {
		$ret = odbc_fetch_array($result);
	}
	return $ret;
}

function db_result($result, $field) {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		global $conn;
		global $result_arr;
		if ($result_arr == "") {
			$result_arr = mysqli_fetch_array($result);
		}
		return $result_arr[$field];
		//$ret = mysqli_result($conn, $result, 0, $field);
	} else if ($DB_TYPE == "odbc") {
		$ret = odbc_result($result, $field);
	}
	return $ret;

}

function openDB() {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		global $conn;
		$user="musikteam";
		$password="musikteamkode";
		$database="musikteam";
		$host = "localhost";
		$conn = mysqli_connect($host,$user,$password);
		mysqli_select_db($conn, $database) or die( "Unable to select database");
	} else if ($DB_TYPE == "odbc") {
		global $conn;
		$conn = odbc_connect("MusikTeam", "", "");
	}

}

function closeDB() {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		global $conn;
		mysqli_close($conn);
	} else if ($DB_TYPE == "odbc") {
		global $conn;
		odbc_close($conn);
	}
}

function doSQLQuery($query) {
	global $DB_TYPE;
	if ($DB_TYPE == "mysql") {
		global $conn;
		global $result_arr;
		$result =  mysqli_query($conn, $query) or die("Query failed : " . mysqli_error($conn));
		$result_arr = "";
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
	global $conn;
	$str = utf8_decode($strIn);
	$str = str_replace("‘", "'", $str);
	$str = str_replace("’", "'", $str);
	$str = str_replace("”", '"', $str);
	$str = str_replace("“", '"', $str);
	$str = str_replace("–", "-", $str);
	$str = str_replace("…", "...", $str);
	//$str = str_replace("'", "\'", $str);
	$str = mysqli_real_escape_string($conn, $str);
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
