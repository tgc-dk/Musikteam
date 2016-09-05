<style>

    
    #divEditDateTitle
    {	border: 2px solid #ff6633;
        width: auto;
        height: auto;
    }
    #divEditDateTitle td
    {
        text-align: left;
        font-size: 12px;
    }
    
    #divEditDateTitle td span
    {
        font-style: italic;
        font-size: 10px;
    }
</style>

<?php
    if (isset($_SESSION['logget_ind'])) {
    global $DB_TYPE;
    
    // Delete event if requested
    $deleteEvent = isset($_GET['deleteEvent']) ? $_GET['deleteEvent'] : '';
    if ($deleteEvent) {
        $delEvent = $deleteEvent;
        $query = "DELETE FROM Program WHERE ProgramId=" . $delEvent;
        $result = doSQLQuery($query);
    
        $query = "DELETE FROM ProgramPunkt WHERE ProgramID=" . $delEvent;
        $result = doSQLQuery($query);
    
        $query = "DELETE FROM ProgramBruger WHERE ProgramID=" . $delEvent;
        $result = doSQLQuery($query);
    }
    
    $day = isset($_GET['day']) ? $_GET['day'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $eventId = isset($_GET['eventId']) ? $_GET['eventId'] : '';
    // Nothing chosen, so shown the new event form
    if ($day < 1 && $date == "" && $title == "" && $eventId == "") {
?>
<form id="form1" name="form1" method="post" action="main.php?page=program" onsubmit="return ValidateForm(form1.date.value);">
    <label>
			Tilføj arrangement: <br /> Titel: <input name="title" type="text" size="15" value="" />
    </label>
    <label>
			Dato (åååå-mm-dd): <input name="date" type="date" size="15" value="" />
    </label>
    <label>&nbsp;
        <input class="submit_btn" type="submit" name="Submit" value="Tilføj" />
    </label>
</form>
<div class="musiker_table_2">
    <table id="musiker_table" cellspacing="0" cellpadding="3" width="600">
        <tr>
            <td height="15" background="img/tabletop_bg.gif"><div align="left"><strong>Kommende setlister:</strong></div></td>
        </tr>

        <?php
            $now = time();
            $today = date('d', $now);
            $curmonth = date('m', $now);
            $curyear = date('Y', $now);
            if ($DB_TYPE == "mysql") {
                $query = "SELECT Dato,ProgramId,Arrangement FROM Program WHERE DateDiff(Dato, '$curyear-$curmonth-$today') > -1 ORDER BY Dato LIMIT 5;";
            } else if ($DB_TYPE == "odbc") {
                $query = "SELECT TOP 5 Dato,ProgramId,Arrangement FROM Program WHERE DateDiff('s', Dato, '$curyear-$curmonth-$today') < 1 ORDER BY Dato;";
            }
            $result = doSQLQuery($query);
            
            $colour = "";
            while ($line = db_fetch_array($result)) {
                echo "			<tr".$colour.">\n";
                $eventDate = dbDateTxt($line["Dato"]);
                echo "				<td><a href=\"main.php?page=program&eventId=".$line["ProgramId"]."\">".$line["Arrangement"]." d. ".$eventDate."</a></td>\n";
                echo "			</tr>\n";
            
                if ($colour == "") {
                    $colour = " bgcolor=\"#f2f2f2\"";
                } else {
                    $colour = "";
                }
            }
            
        ?>
    </table>
</div>

<?php
    
    } else {
    
        if ($_GET['eventId'] == "") $eventId = -1;
        else $eventId = $_GET['eventId'];
    
        $date = isset($_POST['date']) ? $_POST['date'] : '';
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        // A new event has been created
        if ($date != "" && $title != "") {
            $query = "INSERT INTO Program (Arrangement,Dato) VALUES ('".$_POST['title']."','" . $date . "')";
            $result = doSQLQuery($query);
            $query = "SELECT MAX(ProgramID) AS NewProgramId FROM Program";
            $result = doSQLQuery($query);
            $line = db_fetch_array($result);
    
            $eventId = $line["NewProgramId"];		
            /*if ($DB_TYPE == "mysql") {
                $eventId = $line["MAX(ProgramID)"];
            } else if ($DB_TYPE == "odbc") {
                $eventId = $line["ProgramID"];
            }*/
        }
    
        // We are not going to display a new and empty event, so we should have a date, but there might be more than
        // one event per date, so the user must chose which one to edit/view.
        if ($eventId == -1) {
            if ($DB_TYPE == "mysql") {
                $query = "SELECT ProgramID,Arrangement FROM Program WHERE DateDiff(Dato, '$year-$month-$day') = 0";
            } else if ($DB_TYPE == "odbc") {
                $query = "SELECT ProgramID,Arrangement FROM Program WHERE (Dato >= #00:00:00 $month/$day/$year#) and (Dato < #01:00:00 $month/$day/$year#)";
            }
            $result = doSQLQuery($query);
            echo "<br />Vælg et arrangement på den valgte dato:<br />";
            while ($line = db_fetch_array($result)) {
?>
<div id="sub_menu_1">
    <a href="main.php?page=program&eventId=<?php echo $line["ProgramID"]; ?>"> <?php echo $line["Arrangement"]; ?> </a></div>
<?php
        }
    } else {
    
    
        $query = "SELECT Arrangement,Dato FROM Program WHERE ProgramID=".$eventId;
        $result = doSQLQuery($query);
        $line = db_fetch_array($result);
        $eventTitle = $line["Arrangement"];
        $tmp = $line["Dato"];
        $eventDate = substr($tmp,8,2)."/".substr($tmp,5,2)."/".substr($tmp,0,4);
    
?>
<!-- Sange -->
<div class="arrangement">
    <button name="send" value="Tilføj arrangement" class="submit_btn" onclick="javascript:location.href='main.php?page=program';return false;"> Tilføj arrangement </button><br>
    <!--<a href="main.php?page=program" target="_top"><img src="img/arrangement.gif" border="0" alt="Sange" /></a>-->
</div>
<table id="setliste_table" cellspacing="0" cellpadding="3">
    <tr>
        <td background="img/tabletop_bg.gif" colspan="5">Setliste for: <strong><?php echo $eventTitle?> d. <?php echo $eventDate?></strong></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc"><strong>Sang: </strong></td>
        <td width="34" colspan="2" bgcolor="#cccccc"><div align="center"><span class="style3">Flyt</span></div></td>
        <td width="17" bgcolor="#cccccc"><div align="center"><span class="style3">Slet</span></div></td>
        <td bgcolor="#CCCCCC"><strong>Overskrift:</strong></td>
    </tr>
    <?php
        $query = "SELECT ProgramPunkt.SangID,Sang.Titel,ProgramPunkt.Overskrift FROM ProgramPunkt INNER JOIN Sang ON ProgramPunkt.SangID=Sang.SangId WHERE ProgramPunkt.ProgramID=".$eventId." ORDER BY ProgramPunkt.Raekkefoelge";
        $result = doSQLQuery($query);
        $songCount = 0;
        while ($line = db_fetch_array($result)) {
            if ($songCount % 2 == 1) {
                $color = " bgcolor='#F4F4F4'";
            } else {
                $color = " bgcolor='#FFFFFF'";
            }
    ?>
    <tr <?php echo $color; ?>>
        <td><a onclick="NewWindow(this.href,'song_win','yes');return false;" href="editSong.php?song=<?php echo $line["SangID"]; ?>"><?php echo $line["Titel"]; ?></a></td>
        <td width="17"><a href="javascript:moveUp(<?php echo $songCount; ?>)"><img src="img/go-up.gif" alt="Flyt op" width="16" height="16" border="0" /></a></td>
        <td width="17"><a href="javascript:moveDown(<?php echo $songCount; ?>)"><img src="img/go-down.gif" alt="Flyt op" width="16" height="16" border="0" /></a></td>
        <td width="17"><a href="javascript:deleteSong(<?php echo $songCount; ?>)"><img src="img/slet.gif" alt="Slet" width="16" height="16" border="0" /></a></td>
        <td onclick="editHeading(<?php echo $songCount; ?>)"><?php echo $line["Overskrift"]; ?></td>
    </tr>
    <?php
        $songCount++;
        }
    ?>

    <tr bgcolor="#cccccc">
        <td colspan="5">
            <table border="0" width="100%">
                <tr>
                    <td width="12%">
                        <a href="javascript:toggleSongIframe()"><img id="searchSongImg" src="img/list-add.gif" alt="Tilf&oslash;j sang" width="14" height="14" border="0" align="top" /></a>&nbsp;&nbsp; <span class="style3">Tilf&oslash;j sang</span>
                    </td>
                    <td width="88%" align="right">
                        <button name="send" value="Vis nu" class="submit_btn" onclick="javascript:webSlides('<?php echo $eventTitle?> d. <?php echo $eventDate?>');return false;"> <img src="img/slide_show_48.png" alt="Vis nu" width="18" height="19" border="0" align="absmiddle" /> Vis nu </button>
                        <button name="send" value="Udskriv alle sange" class="submit_btn" onclick="javascript:printSongs();return false;"> <img src="img/udskriv_lille.gif" alt="Udskriv alle sange" width="18" height="19" border="0" align="absmiddle" /> Udskriv alle sange </button>
                        <button name="send" value="Lav slides" class="submit_btn" onclick="javascript:slides('<?php echo $eventTitle?> d. <?php echo $eventDate?>');return false;"> <img src="img/oo-impress.gif" alt="Udskriv" width="18" height="19" border="0" align="absmiddle" /> Lav slides </button>
                        <button name="send" value="Lav sangblad" class="submit_btn" onclick="javascript:sangBlad('<?php echo $eventTitle?> d. <?php echo $eventDate?>');return false;"> <img src="img/pdf.gif" alt="Lav sangblad" width="18" height="19" border="0" align="absmiddle" /> Lav sangblad </button>
                        <button name="send" value="Lav OpenLP-fil" class="submit_btn" onclick="javascript:openLP(<?php echo $eventId?>);return false;"> <img src="img/openlp.png" alt="Lav OLP-fil" width="18" height="19" border="0" align="absmiddle" /> Lav OpenLP-fil </button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr bgcolor="#cccccc">
        <td id="songIframeTd" colspan="5"></td>
    </tr>
</table>

<p>&nbsp;</p>

<!-- Musikere -->
<table id="band_table" cellspacing="0" cellpadding="3">
    <tr>
        <td colspan="3" background="img/tabletop_bg.gif">Band til: <strong><?php echo $eventTitle?> d. <?php echo $eventDate?></strong></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc"><strong>Musikere: </strong></td>
        <td width="17" bgcolor="#cccccc"><div align="center"><span class="style3">Slet</span></div></td>
        <td bgcolor="#CCCCCC"><strong>Rolle: </strong></td>
    </tr>
    <?php
        $query = "SELECT ProgramBruger.BrugerID,Bruger.Fornavn,Bruger.Efternavn,ProgramBruger.RolleID FROM ProgramBruger INNER JOIN Bruger ON ProgramBruger.BrugerID=Bruger.BrugerId WHERE ProgramBruger.ProgramID=".$eventId;
        $result = doSQLQuery($query);
        $personCount = 0;
        while ($line = db_fetch_array($result)) {
            if ($personCount % 2 == 1) {
                $color = " bgcolor='#F4F4F4'";
            } else {
                $color = " bgcolor='#FFFFFF'";
            }
            $brugerid = $line["BrugerID"];
        
    ?>
    <tr <?php echo $color; ?>>
        <td><div id="<?php echo $line["BrugerID"]; ?>"><?php echo $line["Fornavn"]." ".$line["Efternavn"]; ?></div></td>
        <td width="17"><a href="javascript:deletePerson(<?php echo $personCount; ?>)"><img src="img/slet.gif" alt="Slet" width="16" height="16" border="0" /></a></td>
        <td>
            <select name="selectAbility">
                <option value="-1">Vælg en rolle</option>
                <?php
                    // Find and list abilities
                    $selectedAbility = $line["RolleID"];
                    $abilityQuery = "SELECT Rolle.RolleId,Rolle.Navn FROM Rolle INNER JOIN BrugerRolle ON Rolle.RolleID=BrugerRolle.RolleID WHERE BrugerRolle.BrugerID=".$brugerid.";";
                    $abilityResult = doSQLQuery($abilityQuery);
                    while ($abilityLine = db_fetch_array($abilityResult)) {
                        $abilityID = $abilityLine["RolleId"];
                        $abilityName = $abilityLine["Navn"];
                        if ($abilityID != $selectedAbility) echo "				<option value=\"".$abilityID."\">".$abilityName."</option>\n";
                        else echo "				<option value=\"".$abilityID."\" selected=\"selected\">".$abilityName."</option>\n";	
                        /*if ($abilities != "") $abilities = $abilities . ", ";
                        $abilities = $abilities . current($abilityLine);*/
                    }
                    
                ?>
            </select>
        </td>

    </tr>
    <?php
        $personCount++;
        }
    ?>
    <tr bgcolor="#cccccc">
        <td>
            <div align="left">
                <a href="javascript:togglePersonIframe()"><img id="addPersonImg" src="img/list-add.gif" alt="Tilf&oslash;j person" width="14" height="14" border="0" align="top" /></a>&nbsp;&nbsp; <span class="style3">Tilf&oslash;j person / team</span>
            </div>
        </td>
        <td colspan="2">
            <div align="right">
                <button name="send" value="Send setliste til bandet" class="submit_btn_2" onclick="javascript:sendSetlist(<?php echo $eventId; ?>,'<?php echo $eventTitle; ?> d. <?php echo $eventDate; ?>', '<?php echo $_SESSION['brugernavn']; ?>');return false;"> Send setliste til bandet </button>
            </div>
        </td>
    </tr>
    <tr bgcolor="#cccccc">
        <td id="personIframeTd" colspan="5"></td>
    </tr>
    <tr>
        <td align="center" colspan="5">
            <button onclick="javascript:printSetlist();return false;" name="send" value="Udskriv setliste" class="submit_btn"> <img src="img/udskriv_lille.gif" alt="Udskriv setliste" width="18" height="19" border="0" align="absmiddle" /> Udskriv setliste </button>
            <button name="send" value="Gem setliste og band" class="submit_btn_2" onclick="javascript:save(<?php echo $eventId; ?>);return false;"> Gem setliste og band </button>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="5">
            <button name="send" value="Slet dette arrangement" class="submit_btn_2" onclick="javascript:deleteEvent(<?php echo $eventId; ?>);return false;"> Slet dette arrangement </button>
            <button name="send" value="Ret dato og titel" class="submit_btn_2" onclick="EditDateTitle(<?php echo $eventId; ?>);return false;"> Ret dato og titel </button>
        </td>
    </tr>
</table>

<script type="text/javascript">
    <!-- 
    var numSongs = <?php echo $songCount; ?>;
    var numPersons = <?php echo $personCount; ?>;
    // -->
</script>
<?php
        }
    }
    
    } // session
?>

