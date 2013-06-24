
<?php
if (isset($_SESSION['logget_ind'])) {
	$sTitle = $_POST['searchTitle'];
	$sText = $_POST['searchText'];
	$sAuthor = $_POST['searchAuthor'];
	$sProTextOnly = $_POST['searchProTextOnly'];

	if ($sTitle != "on" && $sText != "on" && $sAuthor != "on" && $sProTextOnly != "on") {
		$sTitle = "on";
	}
?>
<script language="JavaScript">
	function showall(nr)
	{
		shadowTable();
		ajaxRequest('divSearchTable','Sange_ajax.php','showall='+nr);
	}
	
	function shadowTable()
	{
		if(document.getElementById('search_result_table_2') == null)
			return;
		
		var elm;
		var target = document.getElementById('search_result_table_2');
		try {
			elm = document.createElement('<div id="divResultTableShadow" class="shadow" />');
		}
		catch (e) {
			elm = document.createElement('div');
			elm.setAttribute('id','divResultTableShadow');
			elm.setAttribute('class', 'shadow');
		}
		elm.style.width = target.offsetWidth + 'px';
		elm.style.height = target.offsetHeight + 'px';
		elm.style.left = target.offsetLeft + 'px';
		elm.style.top = target.offsetTop + 'px';
		
		document.getElementById('divSearchTable').appendChild(elm);
		
		elm.style.display = 'block';
	}
	
	function FavoriteSong(songid, mode)
	{
		ajaxRequest('favImg'+songid,'ajaxFunction.php', 'mode='+mode+'&sangid='+songid);
	}
	
	var t;
	function findSong()
	{
		clearTimeout(t);
		if(document.getElementById('textfield').value.length > 0)
			t = setTimeout("findSongEx()",300);
		else
			document.getElementById('divSearchTable').innerHTML = "";
	}
	function findSongEx()
	{
		createShadow(document.getElementById('search_result_table_2'));
		ajaxRequest('divSearchTable','Sange_ajax.php','textfield='+document.getElementById('textfield').value+
													'&searchTitle='+(document.getElementById('searchTitle').checked == true?'on':'')+
													'&searchText='+(document.getElementById('searchText').checked == true?'on':'')+
													'&searchAuthor='+(document.getElementById('searchAuthor').checked == true?'on':'')+
													'&searchProTextOnly='+(document.getElementById('searchProTextOnly').checked == true?'on':'')+
													'&brugerid=<?php echo $_SESSION['brugerid']; ?>');
	}
	
	var divSetlist;
	var divSetlistOpacity;
	
	function addSong(sangid)
	{
		if(document.getElementById('setListId').value == "")
		{
			cancelHideSetlistMenu();
			if(divSetlist == null)
				divSetlist = document.getElementById('divSetlist');
			
			divSetlist.style.left = (X+15) +'px';
			divSetlist.style.top = Y + 'px';
						
			if (divSetlist.addEventListener) {
				divSetlist.addEventListener ("mouseout",hideSetlistMenuOut,false);
				divSetlist.addEventListener ("mouseover",cancelHideSetlistMenu,false);
			}
			else if(divSetlist.attachEvent) {
				divSetlist.attachEvent ("onmouseout",hideSetlistMenuOut);
				divSetlist.attachEvent ("onmouseover",cancelHideSetlistMenu);
			}
			else {
				divSetlist.onmouseout = hideSetlistMenuOut;
				divSetlist.onmouseover = cancelHideSetlistMenu;
			}
			
			divSetlist.style.display = 'block';
			createBackground(divSetlist);

			ajaxRequest('divSetlist','program_ajax.php','sangid=' + sangid+'&mode=Setlist');
			hideSetlistMenuOut();
		}
		else
			ajaxRequest('addImg'+sangid,'program_ajax.php','sangid='+sangid+'&mode=add');
	}
	
	function removeSong(sangid)
	{
		ajaxRequest('addImg'+sangid,'program_ajax.php','sangid='+sangid+'&mode=remove');
	}
	
	function setSetlist(setlistid, sangid)
	{
		createShadow(divSetlist);
		ajaxRequest('divSetListBar','program_ajax.php','setlist='+setlistid+'&sangid='+sangid);
	}
	
	function removeSetlist()
	{
		document.getElementById('setListId').value = "";
		document.getElementById('divSetListBar').innerHTML = "";
		ajaxRequest('','program_ajax.php','mode=removeSetlist');
	}
	
	
	function hideSetlistMenu()
	{
		divSetlist.style.display = 'none';
	}
	
	var timerSetlisteMenu
	function hideSetlistMenuOut()
	{
		cancelHideSetlistMenu();
		timerSetlisteMenu = setTimeout("hideSetlistMenu()",2000);
	}
	
	function cancelHideSetlistMenu()
	{
		clearTimeout(timerSetlisteMenu);
	}
		
	
	var X, Y;
	function getMouseXY(e)
	{
		if (e.pageX || e.pageY) 	{
			X = e.pageX;
			Y = e.pageY;
		}
		else if (e.clientX || e.clientY) 	{
			X = e.clientX + document.body.scrollLeft
				+ document.documentElement.scrollLeft;
			Y = e.clientY + document.body.scrollTop
				+ document.documentElement.scrollTop;
		}
	}
	
	if (document.addEventListener)
		document.addEventListener ("mousemove",getMouseXY,false);
	else if (document.attachEvent)
		document.attachEvent ("onmousemove",getMouseXY);
	else
		document.onmouseout = getMouseXY;
</script>
<style type="text/css">
<!--
.style2 {font-style: italic}
.style3 {
	font-size: 9px;
	font-style: italic;
}

.divSetlist th
{
	height: 21px;
	text-align: center;
	cursor: default;
}

.divSetlist td
{
	cursor: pointer;
}

.divSetlist
{
	width: 150px;
	height: 150px;
	position: absolute;
	display: none;
	border: 1px outset gray;
	border-collapse: collapse;
}

.divSetlist table div
{
	overflow-y: auto;
	overflow-x: hidden;
	height: 129px;
}


#divSetListStatusBar
{
	height: 0px;
	font-style: italic;
	color: #666666;
	font-size: 10px;
}

-->
</style>
		<form id="form1" name="form1" method="post" action="main.php?page=sange&subpage=search">
			<label>
				<input name="textfield" id="textfield" type="text" class="textfield" value="<?php echo $_POST['textfield']; ?>" onKeyUp="findSong(this)" />
				<script language="JavaScript">document.getElementById('textfield').focus();</script>
			</label>
			<label>&nbsp;
				<input class="submit_btn" type="submit" name="Submit" value="Søg" />
			</label>
			<br />
			<span class="style2">
				Søg i: 
				<INPUT type="checkbox" name="searchTitle" id="searchTitle" onChange="findSong();" class="checkbox"<?php echo ($sTitle == "on" ? ' checked="true"' : ''); ?>>Titel&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="checkbox" name="searchAuthor" id="searchAuthor" onChange="findSong();" class="checkbox"<?php echo($sAuthor == "on" ? ' checked="true"' : ''); ?>>Forfatter&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="checkbox" name="searchText" id="searchText" onChange="findSong();" class="checkbox"<?php echo ($sText == "on" ? ' checked="true"' : ''); ?>>Tekst&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="checkbox" name="searchProTextOnly" id="searchProTextOnly" onChange="findSong();" class="checkbox"<?php echo ($sProTextOnly == "on" ? ' checked="true"' : ''); ?>>Søg kun i sange m. akkorder			</span>
			<p>
            <table width="610px" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>
				  <button name="send" value="Vis alle sange" class="submit_btn4" onClick="showall(1);return false;"> Vis alle sange </button>&nbsp;&nbsp;&nbsp;&nbsp;
				  <button name="send" value="Vis alle sange m. akkorder" class="submit_btn5" onClick="showall(2);return false;"> Vis alle sange m. akkorder </button>&nbsp;&nbsp;&nbsp;&nbsp;
				  <button name="send" value="Vis mine favorit sange" class="submit_btn6" onClick="showall(4);return false;"> Vis mine favorit sange </button></td>
				<td></td>
				<td width="100" colspan="2"><button name="send" onclick="var song_win=NewWindow('editSong.php?song=-1','song_win','yes');return false;" value="Tilføj ny sang" class="submit_btn7"> Tilføj ny sang </button></td>
			  </tr>
		      <tr>
			    <td>
				  <div id="divSetListBar">
					<?php
						if ($_SESSION['setlist'] != NULL){
							echo "Aktuel setliste: <a href=\"main.php?page=program&eventId=".$_SESSION['setlist']."\">".getProgramNavn($_SESSION['setlist'])."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:removeSetlist();\">fjern</a>";
						}
					?>
				  </div>
				  <input id="setListId" type="hidden" value="<?php echo $_SESSION['setlist']?>" />
				  <div id="divSetListStatusBar"></div>
				</td>
				<td>	  </td>
	  			<td width="16"><p><img src="img/important.png" alt="NB!"/>&nbsp;</p></td>
				<td width="84" valign="middle"><span class="style3">Husk at sikre dig at sangen ikke allerede findes i systemet!</span></td>
		      </tr>
			</table>
</p>
<p>&nbsp;
</p>

		</form>
<div id="divSetlist" class="divSetlist"></div>
<div id="divSetlistOpacity" class="divSetlistOpacity"></div>
<div id="divSearchTable">
<?php
	// If a search text has been entered then do a search
	if ($_POST['textfield'] != '' || $_GET['showall'] > 0) {
?>
               
		<table id="search_result_table_2" cellspacing="0" cellpadding="3">
<tr>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Titel</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Forfatter</strong></div></td>
			<!--<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Sprog</strong></div></td>-->
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Udgave</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"></div></td>

			<td width="80" height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Sidst spillet d</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Favorit</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Lyt</strong></div></td>
			<td height="15" background="img/tabletop_bg.gif"><div align="center"><strong>Tilføj</strong></div></td>
		</tr>

<?php

		// Show all songs, or do a search
		if ($_GET['showall'] > 0) {
			if ($_GET['showall'] == 2) {
				$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave, sb.BrugerId,Sang.Lydfil FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId WHERE " . CreateLikeClause("Sang.ProTekst", "]") . " ORDER BY Sang.Titel";
			}
			elseif ($_GET['showall'] == 3) {
				$query =  "SELECT TOP 10 Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave, sb.BrugerId, Sang.Lydfil, Count(Sang.SangID) AS SongCount
				FROM ((Sang
				INNER JOIN ProgramPunkt ON Sang.SangId = ProgramPunkt.SangID) 
				INNER JOIN Program ON Program.ProgramID = ProgramPunkt.ProgramID)
				LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId 
				WHERE DateDiff('d', Program.Dato, '$curyear-$curmonth-$today') < 365
				GROUP BY Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,Sang.Lydfil, sb.BrugerId
				ORDER BY Count(Sang.SangID) DESC ";
			}
			elseif ($_GET['showall']==4) {
				$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave, sb.BrugerId,Sang.Lydfil FROM Sang INNER JOIN SangBruger sb ON sb.SangId = Sang.SangId WHERE sb.BrugerId = ".$_SESSION['brugerid']." ORDER BY Sang.Titel";
			} 
			else {
				$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,sb.BrugerId,Sang.Lydfil FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId ORDER BY Sang.Titel";
			}
		} else {
			// Do the database search and list the result
			$strSearchIn = "";

			if ($sTitle == "on") {
				$strSearchIn .= CreateLikeClause("Sang.Titel", $_POST['textfield']);
			}

			if ($sAuthor == "on") {
				if ($strSearchIn != "") {
					$strSearchIn .= " OR ";
				}
				$strSearchIn .= CreateLikeClause("Sang.Identifikation", $_POST['textfield']);
			}

			if ($sText == "on") {
				if ($strSearchIn != "") {
					$strSearchIn .= " OR ";
				}
				$strSearchIn .= CreateLikeClause("Sang.ProTekst", $_POST['textfield']);
				$strSearchIn .= " OR ";
				$strSearchIn .= CreateLikeClause("Slide2.Slidetekst", $_POST['textfield']);
			}

			if ($sProTextOnly == "on") {
				if ($strSearchIn != "") {
					$strSearchIn = "(".$strSearchIn .") AND ";
				}
				$strSearchIn .= CreateLikeClause("Sang.ProTekst", "]");
			}
				
			$query = "SELECT DISTINCT Sang.SangId,Sang.Titel,Sang.Identifikation,Sang.Udgave,sb.BrugerId,Sang.Lydfil FROM (Sang LEFT OUTER JOIN Slide2 ON Sang.SangID = Slide2.SangID) LEFT JOIN (SELECT * FROM SangBruger WHERE BrugerId = ".$_SESSION['brugerid'].") sb ON sb.SangId = Sang.SangId  WHERE " . $strSearchIn . " ORDER BY Sang.Titel";
		}

		$result = doSQLQuery($query);
		$colour = "";
		while ($line = db_fetch_array($result)) {
			$songid = stripslashes($line["SangId"]);
			
			$title = $line["Titel"];
			echo "		<tr".$colour.">\n";
			echo "			<td><a href=\"editSong.php?song=".$songid."\" onclick=\"NewWindow(this.href,'song_win','yes');return false;\">".$title."</a></td>\n"; // Title
			echo "			<td align=\"center\">".stripslashes($line["Identifikation"])."</td>\n"; // Author
			//echo "			<td".$colour."><div align=\"center\">&nbsp;</td>\n"; // Language
			echo "			<td align=\"center\">".stripslashes($line["Udgave"])."</td>\n"; // Edition
			echo "			<td align=\"center\">&nbsp;</td>\n"; // Toneart
			


		
			// Find the last date the song was used
			$lastQuery = "SELECT Program.Dato FROM Program LEFT OUTER JOIN ProgramPunkt ON Program.ProgramID = ProgramPunkt.ProgramID WHERE ProgramPunkt.SangID =".$songid." ORDER BY Program.Dato DESC";
			$lastResult = doSQLQuery($lastQuery);
			if ($lastLine = db_fetch_array($lastResult)) {
				$tmp = $lastLine["Dato"];
				$date = substr($tmp,8,2)."/".substr($tmp,5,2)."/".substr($tmp,2,2);
			} else {
				$date = "";
			}
			echo "			<td align=\"center\">".$date."</td>\n"; // Last played on
			
			echo "<td align=\"center\" id=\"favImg".$songid."\"><img style=\"cursor: pointer;\" alt=\"Fjern favorit\" width=\"16\" height=\"16\"";
			$fav = $line["BrugerId"];
			if($fav != "")
				echo " src=\"img/favorit_checked.png\" onclick=\"FavoriteSong(".$songid.",'removeFavorite');\" />";
			else
				echo " src=\"img/favorit_unchecked.png\" onclick=\"FavoriteSong(".$songid.",'addFavorite');\" />";
			echo "</td>";

			$audioFile = $line["Lydfil"];
			if ($audioFile != '') {
				echo "			<td align=\"center\"><a href=\"".$audioFile."\" target=\"_blank\"><img src=\"img/mp3.gif\" alt=\"Lyt\" width=\"16\" height=\"16\" border=\"0\" /></a></div></td>\n"; // Link or audio file
			} else {
				echo "			<td align=\"center\">&nbsp;</td>\n"; // Link or audio file
			}

		// Tilføj sang til setlist
			if (isSongInProgram($songid, $_SESSION['setlist']))
				echo "			<td id=\"addImg".$songid."\" align=\"center\"><img src=\"img/list-minus.gif\" alt=\"Fjern sang\" width=\"14\" height=\"14\" border=\"0\" style=\"cursor: pointer;\" align=\"top\" onclick=\"removeSong(".$songid.")\" /></td>\n"; // Remove
			else
				echo "			<td id=\"addImg".$songid."\" align=\"center\"><img src=\"img/list-add.gif\" alt=\"Tilf&oslash;j sang\" width=\"14\" height=\"14\" border=\"0\" style=\"cursor: pointer;\" align=\"top\" onclick=\"addSong(".$songid.")\" /></td>\n"; // Add

			echo "		</tr>\n";


			if ($colour == "") {
				$colour = " bgcolor=\"#f2f2f2\"";
			} else {
				$colour = "";
			}
		}
?>
        </table>

        <p>
          <?php
	} // Search
	echo "</div>";
} // Session
?>
</p>
       
		
