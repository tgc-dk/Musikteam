<?php

function getNextRank($programid)
{
//	$sql = "SELECT TOP 1 raekkefoelge FROM programpunkt WHERE programid = ".$programid." ORDER BY raekkefoelge DESC";
	$sql = "SELECT Raekkefoelge FROM ProgramPunkt WHERE ProgramID = ".$programid." ORDER BY Raekkefoelge DESC LIMIT 1";
	$rs = doSQLQuery($sql);
	if(db_fetch_array($rs))
		return db_result($rs,"raekkefoelge") + 1;
	else
		return 0;
}
function getSangTitel($sangid)
{
	$sql = "SELECT Titel FROM Sang WHERE SangId = ".$sangid;
	$rs = doSQLQuery($sql);
	if(db_fetch_array($rs))
		return db_result($rs,"titel");
	else
		return "";
}

function getProgramNavn($programid)
{
	$sql = "SELECT * FROM Program WHERE ProgramID = " . $programid;
	$rs = doSQLQuery($sql);
	if(db_fetch_array($rs))
		return db_result($rs, "Arrangement")." ".convDate(db_result($rs, "dato"));
	else
		return "";
}

function isSongInProgram($songid, $programid)
{
	if($songid == NULL || $programid == NULL)
		return false;
		
	$sql = "SELECT * FROM ProgramPunkt WHERE ProgramID = ".$programid." AND SangID = ".$songid;
	$rs = doSQLQuery($sql);
	return db_fetch_array($rs);
}

function dbDateTxt($dato)
{
	$str = substr($dato,0,4);
	$str = substr($dato,5,2) . "/" . $str;
	$str = substr($dato,8,2) . "/" . $str;
	return $str;
}

?>
