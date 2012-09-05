<?php
if (isset($_SESSION['logget_ind'])) {
	$query = "SELECT SlideID,Slidetekst FROM Slide2 WHERE (SangID = " . $intSongId . ") ORDER BY SlideID";
	$result = doSQLQuery($query);
	$slide_arr = array();
	while ($res_arr = db_fetch_array($result)) {
		$tmp = $res_arr["SlideID"];
		array_push($slide_arr, $res_arr["Slidetekst"]);
	}
?>
				<div id="slides_input">
					<span class="stamdata">Afspilningsrækkefølge: </span><br />
					<input name="slides" id="slides" type="text" value="<?php echo $strSlides; ?>" size="18" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button name="send" value="Kopier tekst fra sangtekst" class="submit_btn" onClick="javascript:copyToSlides();return false;"> Kopier tekst fra sangtekst </button>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<!--<button name="send" value="Gem slides" class="submit_btn_2" onClick="javascript:saveSlides();return false;"> Gem slides </button>-->
					<button name="send" value="Generer præsentation" class="submit_btn" onClick="javascript:window.location = 'odp-handler/createSlides.php?songcount=1&Song0=<?php echo $intSongId; ?>';return false;"> <img src="img/oo-impress.gif" alt="Udskriv" width="18" height="19" border="0" align="absmiddle" /> Generer præsentation </button>
					<br />
					<span class="stamdata">Slide A: </span><br />
					<textarea cols="60" rows="8" id="slideA"><?php print(stripslashes($slide_arr[0])); ?></textarea><br />
					<span class="stamdata">Slide B: </span><br />
					<textarea cols="60" rows="8" id="slideB"><?php print(stripslashes($slide_arr[1])); ?></textarea><br />
					<span class="stamdata">Slide C: </span><br />
					<textarea cols="60" rows="8" id="slideC"><?php print(stripslashes($slide_arr[2])); ?></textarea><br />
					<span class="stamdata">Slide D: </span><br />
					<textarea cols="60" rows="8" id="slideD"><?php print(stripslashes($slide_arr[3])); ?></textarea><br />
					<span class="stamdata">Slide E: </span><br />
					<textarea cols="60" rows="8" id="slideE"><?php print(stripslashes($slide_arr[4])); ?></textarea><br />
					<span class="stamdata">Slide F: </span><br />
					<textarea cols="60" rows="8" id="slideF"><?php print(stripslashes($slide_arr[5])); ?></textarea><br />
					<span class="stamdata">Slide G: </span><br />
					<textarea cols="60" rows="8" id="slideG"><?php print(stripslashes($slide_arr[6])); ?></textarea><br />
					<span class="stamdata">Slide H: </span><br />
					<textarea cols="60" rows="8" id="slideH"><?php print(stripslashes($slide_arr[7])); ?></textarea><br />
					<div id="refresh">
						<p>	&nbsp;<br />&nbsp;</p>
					</div>
				</div>

<?php
} // session
?>
