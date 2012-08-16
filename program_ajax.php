<?php

include("db.php");
include("function.php");
openDB();
session_start();

$sangid = $_POST['sangid'];
$str = "";

if($_POST['mode'] == "removeSetlist")
{

	$_SESSION['setlist'] = NULL;
	return "";
}
else if($_POST['setlist'] != NULL)
{

	$_SESSION['setlist'] = $_POST['setlist'];
	$str .= "Aktuel setliste: <a href=\"main.php?page=program&eventId=".$_SESSION['setlist']."\">".getProgramNavn($_SESSION['setlist'])."</a>";
	if ($_POST['from'] != "edit") {
		$str .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:removeSetlist();\">fjern</a>";
	} else {
		if (isSongInProgram($sangid, $_SESSION['setlist'])) {
			$str .= "<br/><span id=\"setlist\"><a href=\"javascript:removeSong(".$sangid.");\">Fjern sangen fra setlisten</a></span>";
		} else {
			$str .= "<br/><span id=\"setlist\"><a href=\"javascript:addSong(".$sangid.");\">Tilføj sangen til setlisten</a></span>";
		}
	}

	$str .= "<script>hideSetlistMenu();</script>";

	if ($_POST['from'] != "edit") {
		$str .= "<script>document.getElementById('setListId').value = ".$_SESSION['setlist'].";</script>";
		$str .= "<script>addSong(".$sangid.");</script>";
	}
}
else if($_POST['mode'] == 'allSetlist' || $_POST['mode'] == 'Setlist') //ingen default setliste
{

	if($_POST['mode'] == 'allSetlist')
		$query = "SELECT p.* FROM Program p WHERE p.Dato > Now() ORDER BY p.Dato ASC";
	else
		$query = "SELECT p.* FROM Program p INNER JOIN ProgramPerson pp ON pp.ProgramID = p.ProgramID WHERE pp.PersonID = ".getPersonId()." AND p.Dato > Now() ORDER BY p.Dato ASC";

	$rs = doSQLQuery($query);
	$str .= "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	$str .= "	<tr>";
	$str .= "		<th background=\"img/tabletop_bg.gif\">";
	$str .= "Vælg en setliste!";
	$str .= "		</th>";
	$str .= "	</tr>";
	$str .= "   <tr>";
	$str .= "     <td>";
	$str .= "		<div>";
	$str .= "		  <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">";

	while($res = db_fetch_array($rs))
	{
		$arr = $res["Arrangement"]; //db_result($res,"Arrangement");
		$dato = convDate($res["Dato"]); //db_result($res,"Dato")
		$id = $res["ProgramID"]; //db_result($res,"ProgramID");
		$str .= "	<tr>";
		$str .= "		<td>";
		$str .=	"			<a onclick=\"setSetlist(".$id.",".$sangid.");\">";
		$str .= 				$arr." - ".$dato;
		$str .= "			</a>";
		$str .= "		</td>";
		$str .= "	</tr>";
	}
	
	if($_POST['mode'] != 'allSetlist')
	{
		$str .= "			<tr>";
		$str .= "			  <td align=\"center\">";
		$str .=	"				<a onclick=\"ajaxRequest('divSetlist','program_ajax.php','sangid=".$sangid."&mode=allSetlist');\">--> Vis alle <--</a>";
		$str .= "			  </td>";
		$str .= "			</tr>";
	}
	
	$str .= "		  </table>";
	$str .= "		</div>";
	$str .= "     </td>";
	$str .= "   </tr>";
	$str .= "</table>";
	$str .= "<postscript>createBackground(document.getElementById('divSetlist'));</postscript>";
}
else if($_POST['mode'] == 'add')
{

	$query = "INSERT INTO ProgramPunkt (ProgramID, SangID, Raekkefoelge) VALUES (".$_SESSION['setlist'].",".$sangid.",".getNextRank($_SESSION['setlist']).")";
	$str .= "<script>document.getElementById('divSetListStatusBar').innerHTML='Sangen \"".getSangTitel($sangid)."\" er tilføjet til programmet!';</script>";
	$str .= "<img src=\"img/list-minus.gif\" alt=\"Fjern sang\" width=\"14\" height=\"14\" border=\"0\" style=\"cursor: pointer;\" align=\"top\" onclick=\"removeSong(".$sangid.")\" />";
	doSQLQuery($query);
}
else if($_POST['mode'] == 'remove')
{
	$query = "DELETE FROM ProgramPunkt WHERE ProgramID = ".$_SESSION['setlist']." AND SangID = ".$sangid;
	$str .= "<script>document.getElementById('divSetListStatusBar').innerHTML='Sangen \"".getSangTitel($sangid)."\" er fjernet fra programmet!';</script>";
	$str .= "<img src=\"img/list-add.gif\" alt=\"Fjern sang\" width=\"14\" height=\"14\" border=\"0\" style=\"cursor: pointer;\" align=\"top\" onclick=\"addSong(".$sangid.")\" />";
	doSQLQuery($query);
}
else if($_POST['mode'] == 'editDateTitle')
{
	$query = "SELECT * FROM Program WHERE ProgramID = ".$_POST['programid'];
	$rs = doSQLQuery($query);
//	if(odbc_fetch_row($rs)) {
	if(db_fetch_array($rs)) {
		$str =  
				'<table>' .
				'	<tr>' .
				'		<td><label for="tbText">Titel:</label></td>' .
				'		<td>' .
				'			<input type="text" value="'.db_result($rs,'Arrangement').'" size="15" id="tbText" />' .
				'		</td>' .
				'	</tr>' .
				'	<tr>' .
				'		<td nowrap><label for="tbDato">Dato <span>(dd/mm/åååå)</span>:</label></td>' .
				'		<td>' .
				'			<input type="text" value="'.dbDateTxt(db_result($rs,'Dato')).'" size="15" id="tbDato" />' .
				'		</td>' .
				'	</tr>' .
				'	<tr>' .
				'		<td>' .
				'			<input type="button" class="submit_btn" value="Annuller" onclick="destroyElement(document.getElementById(\'divEditDateTitle\'));return false;" />' .
				'		</td>' .
				'		<td>' .
				'			<input type="button" class="submit_btn" value="Ret" onclick="retDatoTitle('.$_POST['programid'].');return false;" />' .
				'		</td>' .
				'	</tr>' .
				'</table>';
				
		$str .= '<postscript>createBackground(document.getElementById("divEditDateTitle"), 90);</postscript>';
	}
	else
		$str = '<postscript>destroyElement(document.getElementById("divEditDateTitle"));</postscript>';
}
else if($_POST['mode'] == 'saveDateTitle')
{
	$query = "UPDATE Program SET Arrangement = '".utf8_decode($_POST['arrangement'])."', Dato = '".$_POST['dato']."' WHERE ProgramID = ".$_POST['programid'];
	doSQLQuery($query);

	$query = "SELECT * FROM Program WHERE ProgramID = ".$_POST['programid'];
	$rs = doSQLQuery($query);

	$str =  '<table>' .
			'	<tr>' .
			'		<td><label for="tbText">Title:</label></td>' .
			'		<td>'.db_result($rs,'Arrangement').'</td>' .
			'	</tr>' .
			'	<tr>' .
			'		<td nowrap><label for="tbDato">Dato <span>(dd/mm/åååå)</span>:</label></td>' .
			'		<td>'.dbDateTxt(db_result($rs,'Dato')).'</td>' .
			'	</tr>' .
			'	<tr>' .
			'		<td>&nbsp;</td>' .
			'		<td>' .
			'			<input type="button" class="submit_btn" value="Luk" onclick="window.location.reload();" />' .
			'		</td>' .
			'	</tr>' .
			'</table>';
	$str .= '<postscript>createBackground(document.getElementById("divEditDateTitle"), 90);</postscript>';
}

closeDB();
echo utf8_encode($str);
?>
