<?php
if (isset($_SESSION['logget_ind'])) {

global $DB_TYPE;

// Extract days from php arguments, or default to current date. If we're wandering through months, with no selected date (day==-1) mark the current date when passing through the current month.
$now = time();

$today = date('d', $now);
$curmonth = date('m', $now);
$curyear = date('Y', $now);

if ($_GET['day'] != 0 && $_GET['month'] != 0 && $_GET['year'] != 0) {
	$day = $_GET['day'];
	$month = $_GET['month'];
	$year = $_GET['year'];
} else {
	$day = 0;
	$month = $curmonth;
	$year = $curyear;
}

if ($month !=  $curmonth || $year != $curyear) $today = 0;

$first_day = mktime(0,0,0,$month, 1, $year);

$title = date('F', $first_day);

$day_of_week = date('D', $first_day);

switch($day_of_week){
    case "Mon": $blank = 0; break;
    case "Tue": $blank = 1; break;
    case "Wed": $blank = 2; break;
    case "Thu": $blank = 3; break;
    case "Fri": $blank = 4; break;
    case "Sat": $blank = 5; break;
    case "Sun": $blank = 6; break;
}

$days_in_month = date('d', mktime(0, 0, 0, $month + 1, 0, $year));
//$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Get the days in this month where something has been planned
$nextmonth = $month + 1;
$nextyear = $year;
if ($nextmonth > 12) {
	$nextyear++;
	$nextmonth = 1;
}

$lastmonth = $month - 1;
$lastyear = $year;
if ($lastmonth < 1) {
	$lastyear--;
	$lastmonth = 12;
}

// I wonder why I placed this query here... commented out for now...
//$query = "SELECT Dato,Arrangement FROM Program WHERE DateDiff(Dato, '$year-$month-01') >= 0 and DateDiff(Dato, '$nextyear-$nextmonth-01') < 0 ORDER BY Dato;";

echo "<div align='center'>";
echo "<table border=0 align=center id=calendar>";
echo "<tr><th colspan=7> <a href='main.php?page=program&day=-1&month=$lastmonth&year=$lastyear'>&lt;&lt;</a> $title $year <a href='main.php?page=program&day=-1&month=$nextmonth&year=$nextyear'>&gt;&gt;</a> </th></tr>";
echo "<tr><td width=38>M</td><td width=38>T</td><td width=38>O</td><td width=38>T</td><td width=38>F</td><td width=38>L</td><td width=38>S</td></tr>";

$day_count = 1;

echo "<tr>";

while ($blank > 0)
{
	echo "<td></td>";
	$blank = $blank-1;
	$day_count++;
}

$day_num = 1;

if ($DB_TYPE == "mysql") {
	$query = "SELECT Dato,Arrangement FROM Program WHERE DateDiff(Dato, '$year-$month-01') > 0 and DateDiff(Dato, '$nextyear-$nextmonth-01') < 0 ORDER BY Dato;";
} else if ($DB_TYPE == "odbc") {
	$query = "SELECT Dato,Arrangement FROM Program WHERE (Dato >= #$month/01/$year#) and (Dato < #$nextmonth/01/$nextyear#) ORDER BY Dato;";
}
$result = doSQLQuery($query);

$arr = "";
$datetime = "";
$arrDate = 0;
if ($line = db_fetch_array($result)) {
	$datetime = $line["Dato"];
	$arrDate = substr($datetime, 8, 2);
	$arr = $line["Arrangement"];
}

while ($day_num <= $days_in_month)
{

	$class = "days";
	if ($day_num == $today) $class = today;
	else if ($day_num == $arrDate) $class = days;

/*	if ($day_num == $today) {
		echo "<td class=today>$day_num</td>";
		// If there is something happening to day we skip it
		while ($day_num == $arrDate) {
			if ($line = db_fetch_array($result)) {
				$datetime = current($line);
				$arrDate = substr($datetime, 8, 2);
				$arr = next($line);
			} else {
				$arrDate = 0;
			}
		}

	} else {*/
		if ($day_num == $arrDate) {
			// Go to the next event date. If more than one event per date we skip through them, but add the title to the hover text
			$hover = "";
			while ($day_num == $arrDate) {
				if ($hover == "") $hover = $arr;
				else $hover = $hover . "," . $arr;

				if ($line = db_fetch_array($result)) {
					$datetime = $line["Dato"];
					$arrDate = substr($datetime, 8, 2);
					$arr = $line["Arrangement"];
				} else {
					$arrDate = 0;
				}
			}
			echo "<td class=$class><a href='main.php?page=program&day=$day_num&month=$month&year=$year'><strong><span title='$hover'>$day_num</span></strong></a></td>";

		} else {
			echo "<td class=$class>$day_num</td>";
		}
	//}

	$day_num++;
	$day_count++;

	if ($day_count > 7) {
		echo "</tr><tr>";
		$day_count = 1;
	}
}

while ($day_count >1 && $day_count <=7)
{
	echo "<td> </td>";
	$day_count++;
}

echo "</tr></table></div>";
} // session
?>
