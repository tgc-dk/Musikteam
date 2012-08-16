<?php
session_start();


function deleteOriginal($songId) {
	$folder = "musik/";
	$folderList = @opendir($folder);

	// loop through the items
	while ($ourItem = readdir($folderList)) {
		// check to see if it is a file
		if (is_file($ourItem)) {
			if (strpos($file,$songId.".") === true) {
				if (file_exists($folder . $fileName)) {
					@unlink($fileName);
				}				
			}
		}
	}
	closedir($ourDirList);

}

if (isset($_SESSION['logget_ind'])) {

	include("db.php");
	openDB();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Musikteam - lydfil</title>
</head>

<body>

<?php
if ($_GET['mode'] == "upload") {
	if (isset($_POST['upload']) && is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {
		$target_path = "musik/";
		deleteOriginal($_GET['songid']);
		$orgFilename = $_FILES['uploadedfile']['name'];
		$type = substr($orgFilename, strrpos($orgFilename, ".") - strlen($orgFilename));
		$target_path = $target_path . $_GET['songid'] . $type; 

		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			
?>
<script type="text/javascript">

<!--
window.opener.updateAudioFile('http://<?php echo $ROOT_URL."/".$target_path; ?>');
window.opener.focus();
window.close();
// -->

</script>
<?php
		} else{
		    echo "Der opstod en fejl under upload.";
		}
	} else {
?>
<p>
<form enctype="multipart/form-data" action="" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
Vælg lydfilen som skal uploades: <input name="uploadedfile" type="file" /><br />
<input type="submit" name="upload" onClick="document.getElementById('info').innerHTML='Vent venligst mens filen uploades...'" value="Upload fil" />
</form>
<div id="info"></div>
<br/>
</p>
<?php
	}
} else if ($_GET['mode'] == "url") {
	if ($_POST['fileurl'] != "") {
		if ($_GET['songid'] != -1) deleteOriginal($_GET['songid']);

?>
<script type="text/javascript">

<!--
window.opener.updateAudioFile('<?php echo $_POST['fileurl']; ?>');
window.opener.focus();
window.close();
// -->

</script>
<?php
	} else {
?>
<form enctype="multipart/form-data" action="" method="POST">
Indtast URL til lydfilen: <input name="fileurl" type="text" value="<?php echo $_GET['oldurl']; ?>" />
<input type="submit" value="Gem URL" />
</form>
<?php
	}
}
?>

</body>
</html>


<?php
	closeDB();
} // session
?>
