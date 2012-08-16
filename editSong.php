<?php
session_start();


if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	include("function.php");
	openDB();

	$intSongId = $_GET['song'];
	if ($intSongId > -1) {
		$query = "SELECT Titel,Identifikation,Udgave,ProTekst,CommentsOver,CommentsUnder,Lydfil,Kilde,Slides2 FROM Sang WHERE (SangId = " . $intSongId . ") ORDER BY Titel";
		$result = doSQLQuery($query);
		$res_arr = db_fetch_array($result);
		$strTitle = stripslashes($res_arr["Titel"]);
		$strAuthor = stripslashes($res_arr["Identifikation"]);
		$strEdition = stripslashes($res_arr["Udgave"]);
		$strProText = stripslashes($res_arr["ProTekst"]);
		$strCommentsOver = stripslashes($res_arr["CommentsOver"]);
		$strCommentsUnder = stripslashes($res_arr["CommentsUnder"]);
		$strLydfil = stripslashes($res_arr["Lydfil"]);
		$strSource = stripslashes($res_arr["Kilde"]);
		$strSlides = stripslashes($res_arr["Slides2"]);
	}
	include("popupHeader.php");
?>

<script language="JavaScript" type="text/javascript" src="js/ajax.js"></script>
<script language="JavaScript" type="text/javascript" src="js/func.js"></script>
<script type="text/javascript">

window.onload = init;
function init() {
  if (window.Event) {
    document.captureEvents(Event.MOUSEMOVE);
  }
  document.onmousemove = getXY;
}

function getXY(e) {
  x = (window.Event) ? e.pageX : event.clientX;
  y = (window.Event) ? e.pageY : event.clientY;

  // Use x and y to do what ever you want
}

<!--
function NewWindow(mypage, myname, scroll)
{
  var w=400;
  var h=200;
  var winl = x - 500;
  var wint = y;
  winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
  win = window.open(mypage, myname, winprops)
  
  
}
		
	function addSong(sangid)
	{
		ajaxRequest('','program_ajax.php','sangid='+sangid+'&mode=add');
		document.getElementById("setlist").innerHTML = "<a href=\"javascript:removeSong(" + sangid + ");\">Fjern sangen fra setlisten</a>";
	}
	
	function removeSong(sangid)
	{
		ajaxRequest('','program_ajax.php','sangid='+sangid+'&mode=remove');
		document.getElementById("setlist").innerHTML = "<a href=\"javascript:addSong(" + sangid + ");\">Tilføj sangen til setlisten</a>";
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

	var divSetlist;
	var divSetlistOpacity;
	
	function selectSetlist(sangid)
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

		ajaxRequest('divSetlist','program_ajax.php','sangid=' + sangid+'&mode=Setlist&from=edit');
		hideSetlistMenuOut();
	}

	function setSetlist(setlistid, sangid)
	{
		createShadow(divSetlist);
		ajaxRequest('setListBar','program_ajax.php','setlist='+setlistid+'&from=edit&sangid='+sangid);
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
// -->

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
	font-size: 12px;
}

.divSetlist table div
{
	overflow-y: auto;
	overflow-x: hidden;
	height: 129px;
}

-->
</style>
<body class="home" onload = "first_load()">
<div id="divSetlist" class="divSetlist"></div>

	<table width="790" border="0">
      <tr>
        <td><div class="wrapper_2">
		<div id="header_2">
			<div id="skriv_sang_nav">
				<a href="#" onClick="javascript:text_input();"><img src="img/tekst_down.gif" width="101" height="27" border="0" id="text_img" /></a><a onClick="javascript:layout_edit();" href="#"><img src="img/layout_up.gif" width="101" height="27" border="0" id="layout_img" /></a><a href="#" onClick="javascript:slides_edit();"><img src="img/slides_up.gif" width="101" height="27" border="0" id="slides_img" /></a>
			
          </div>
		</div>

		<div class="block_1">
		
			<div id="sidebar_2">
				<div class="sidebar_top"> Stamdata </div>
				<div class="sidebar_bg"> 

					<div align="left" class="stamdata">
						<p>
							<strong>Titel:</strong><br>
							<input name="Titel" id="Titel" type="text" value="<?php echo $strTitle; ?>" size="18" />
						</p>
						<p>
							<strong>Forfatter:</strong><br>
							<input name="Forfatter" id="Forfatter" type="text" value="<?php echo $strAuthor; ?>" size="18" />
						</p>
						<p>
							<strong>Udgave: </strong><br>
							<input name="Udgave" id="Udgave" type="text" value="<?php echo $strEdition; ?>" size="18" />
						</p>
						<p>
							<strong>Kilde: </strong><br>
							<input name="Kilde" id="Kilde" type="text" value="<?php echo $strSource; ?>" size="18" />
						</p>
						<strong>Lydfil:</strong> 
							<div id="audiofile">

<?php 
	if ($strLydfil=="") {
		echo "Ingen lydfil tilknyttet.";
	} else {
		if (strrpos($strLydfil,"mp3") == (strlen($strLydfil)-3)) {
			echo "<object type=\"application/x-shockwave-flash\" data=\"player_mp3_maxi.swf\" width=\"150\" height=\"20\">";
     		echo "<param name=\"movie\" value=\"player_mp3_maxi.swf\" />";
     		echo "<param name=\"FlashVars\" value=\"mp3=".$strLydfil."\" /></object><br/>";
		}
		echo '<a href="'.$strLydfil.'" target="_new" style="text-decoration:none">Download:&nbsp; <img src="img/mp3.gif" alt="Lyt" border="0"></a>'; 
	}
?>
							</div>
							<button name="send" value="Upload lydfil" class="submit_btn_2" onClick="javascript:uploadAudio();return false;"> Upload lydfil </button>
							<button name="send" value="Indtast URL" class="submit_btn_2" onClick="javascript:giveAudioURL();return false;"> Indtast URL </button>
						</p>
						<p>
<?php
	if ($_SESSION['setlist'] != NULL){
		echo "<span id=\"setListBar\">Aktuel setliste: <a target=\"_new\" href=\"main.php?page=program&eventId=".$_SESSION['setlist']."\">".getProgramNavn($_SESSION['setlist'])."</a>";
		if (isSongInProgram($sangid, $_SESSION['setlist'])) {
			echo "<br/><span id=\"setlist\"><a href=\"javascript:removeSong(".$sangid.");\">Fjern sangen fra setlisten</a></span>";
		} else {
			echo "<br/><span id=\"setlist\"><a href=\"javascript:addSong(".$sangid.");\">Tilføj sangen til setlisten</a></span>";
		}
		echo "</span>";
	} else {
		echo "<span id=\"setListBar\"></span>";
	}
	echo "<br/><a href=\"javascript:selectSetlist(".$intSongId.");\">Vælg setliste</a>";


?>
						  <!--<p><button name="send" value="Gem stamdata" class="submit_btn_2" onClick="javascript:saveMeta();return false;"> Gem stamdata </button></p>-->
					  </p>
					  <p>
					    <button type="submit" name="send" value="Udskriv" class="submit_btn" onClick="javascript:printSong();return false;"> <img src="img/udskriv_lille.gif" alt="Udskriv" width="18" height="19" border="0" align="absmiddle" /> Udskriv sang </button>
					  </p>
						<p><button name="send" value="Slet denne sang" class="submit_btn_2" onClick="javascript:deleteSong();return false;"> Slet denne sang </button></p>
						<p><button name="send" value="Opret ny version af denne" class="submit_btn_2" onClick="javascript:copyToNew();return false;"> Opret ny version af denne </button></p>
                        <p><button name="send" value="Gem alt" class="submit_btn_2" onClick="javascript:saveAll();return false;"> Gem alt </button></p>
                        <p><strong>Sidste ændringer:</strong><br/>
<?php
		$query = "SELECT Bruger.Brugernavn,Historik.Dato FROM Historik INNER JOIN Bruger ON Historik.BrugerId=Bruger.BrugerId WHERE (Historik.SangId = " . $intSongId . ") ORDER BY Historik.Dato";
		$result = doSQLQuery($query);
		while ($res_arr = db_fetch_array($result)) {
			echo $res_arr["Brugernavn"]. " " .$res_arr["Dato"]."<br/>";
		}

?>                        
                        </p>
				  </div>
				</div>
				<div class="sidebar_bund"></div>

			</div> <!-- sidebar_2 -->
			<div class="tekst">
				<div id="text_input">
					<span class="stamdata">Kommentar over tekst: </span><br />
					<textarea cols="60" rows="2" id="commentOver"><?php print($strCommentsOver); ?></textarea><br />
					<span class="stamdata"><strong>Tekst med akkorder: </strong></span>
					<span style="position:relative;">
					<span id="term1" class="help_popup">Her er det muligt at indtaste sangen med akkorder (i &quot;pro&quot; format). Eventuelle kommentarer skal skrives i feltet over eller under sangteksten. 
					<ul>
						<li> Hvis teksten allerede findes i systemet kan den  hentes ved tryk på&nbsp; 
						  <button name="send" value="Kopier tekst fra slides" class="submit_btn" onClick="#"> Hent tekst fra slides </button>
							  Det er muligt at ændre tonearten på sangens akkorder ved at trykke på <img src="img/plus.gif" alt="+" width="12" height="16" align="absbottom"> eller <img src="img/minus.gif" alt="-" width="12" height="16" align="absbottom"> under teksten</li>
					</ul>
					<strong>Skriv akkorder:</strong> Akkorden angives i kantede parenteser ( <strong>[</strong> og <strong>]</strong> ). (Genvejstast til <strong>[ ] : Ctrl+Alt+T</strong> )<img src="img/above-all---pro-html.png" alt="above all - pro - html format" width="374" height="60"><br>
					Akkorden skal indsættes i sangteksten lige <strong>før</strong> det bogstav akkorden skal stå over. <br>
					Klik p&aring;&nbsp; 
					<button name="send" value="Opdatér" class="submit_btn_2" onClick="#"><img src="img/refresh_firefox.gif" alt="Opdat&eacute;r" width="18" height="19" align="absmiddle"/> Opdatér </button>  for at se resultatet.<br><br>
					<strong>Tabulator:</strong> Hvis man ønsker at sangen vises sådan at bestemte elementer (f.eks akkorder) står lige under hinanden kan man benytte en lodret streg ( <strong>| </strong>). Dette vil fungere som tabulator. Det er muligt at indsætte flere lodrette streger på en linje.<br>
					Genvejstast til <strong>| </strong>:  <strong>Ctrl+Alt+D</strong>.<br><br>
                    <strong>Lydfil:</strong> Under "Stamdata" kan der tilknyttes en lydfil til hver sang. Det sker enten ved at uploade en .mp3 eller ved at linke til lydfilen et andet sted på internettet.  </span><a href="javascript:void(0);" onMouseover="ShowPop('term1');" onMouseout="HidePop('term1');"><img src="img/help_22x22.png" alt="Hjælp!" width="20" height="22" border="0" class="help_btn"></a></span> 
					<br />   
					<textarea cols="60" rows="25" id="editArea"><?php print($strProText); ?></textarea><br />
					<span class="stamdata">Kommentar under tekst: </span><br />
				  <textarea cols="60" rows="2" id="commentUnder"><?php print($strCommentsUnder); ?></textarea>
				</div>

<?php
	include("layout.php");
	include("slides.php");
	
?>

				<div id="modpro_txt">
				  <div id="refresh">
				    <span id="Btn1">
                        <!--<button name="send" value="Gem sangtekst" class="submit_btn_2" onClick="javascript:saveLyrics();return false;"> Gem sangtekst </button>-->
						&nbsp;&nbsp;&nbsp;&nbsp;
						<button name="send" value="Opdatér" class="submit_btn_2" onClick="javascript:update();return false;"><img src="img/refresh_firefox.gif" alt="Opdat&eacute;r" width="18" height="19" align="absmiddle"/> Opdatér </button> 
						&nbsp;&nbsp;&nbsp;&nbsp;
						<button name="send" value="Kopier tekst fra slides" class="submit_btn_2" onClick="javascript:copyFromSlides();return false;"> Hent tekst fra slides </button>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<button name="send" value="Kopier tekst fra slides" class="submit_btn_2" onClick="javascript:chordConverter();return false;"> Konverter akkorder </button>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<strong>Toneart:</strong>
						<a href="javascript:changeTone('lower')"><img src="img/minus.gif" name="skalam" width="16" height="23" border="0" align="top" id="skalam" /></a>
						<a href="javascript:changeTone('higher')"><img src="img/plus.gif" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a>

                      </span>
						
						      
					</div>

					<div id="tekst_m_akk" onload = "update()">
						
					</div>
					<div id="refresh">
						<p><button type="submit" name="send" value="Udskriv" class="submit_btn" onClick="javascript:printSong();return false;"> <img src="img/udskriv_lille.gif" alt="Udskriv" width="18" height="19" border="0" align="absmiddle" /> Udskriv sang </button>
						</p>
						<p>asdf<br />
						&nbsp;</p>
				  </div>
				</div> <!-- modpro_txt -->
			</div> <!-- tekst -->


		</div> <!-- block_1 -->
	</div> <!-- wrapper_2 -->
</td>
      </tr>
    </table>
	<p>&nbsp;</p>


</body>
</html>


<?php
	closeDB();
} // session
?>
