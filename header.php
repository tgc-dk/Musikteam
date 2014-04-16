<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

if (!$_SESSION['logget_ind']) {
    header("Location: /auth/thecity/");
    die(); 
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Musikteam - <?php echo $strTitle; ?></title>
<link href="musikstyle.css" type="text/css" rel="stylesheet" media="screen" />
<!--[if IE]>
<link href="musikstyleIE.css" type="text/css" rel="stylesheet" media="screen" />
<![endif]-->
<?php
if (isset($_GET['page'])) {
    if ($_GET['page'] == 'teams') echo '<script src="js/teams.js" type="text/javascript"></script>';
    if ($_GET['page'] == 'program') echo '<script src="js/program.js" type="text/javascript"></script>';
    if ($_GET['page'] == 'admin') echo '<script src="js/admin.js" type="text/javascript"></script>';}
?>

<script type="text/javascript">
 <!-- 
if (document.getElementById) { window.onload = swap };
function swap() {
var numimages=6;
rndimg = new Array("img/bg-header_1.gif", "img/bg-header_2.gif", "img/bg-header_3.gif", "img/bg-header_4.gif", "img/bg-header_5.gif","img/bg-header_6.gif"); 
x=(Math.floor(Math.random()*numimages));
randomimage=(rndimg[x]);
document.getElementById("header").style.backgroundImage = "url("+ randomimage +")"; 
}

function NewWindow(mypage, myname, scroll)
{
  var w=790;
  var h=screen.height;
  var winl = (screen.width - w) / 2;
  var wint = (screen.height - h) / 10;
  winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable';
  win = window.open(mypage, '', winprops);
  win.focus();
}
// -->
</script>
<script src="js/ajax.js"></script>
<script src="js/func.js"></script>
<style type="text/css">
<!--
.style3 {font-size: 9px}
-->
</style>
</head>
