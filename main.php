<?php
session_start();

$strTitle="forside";
include("header.php");
include("db.php");
include("function.php");

openDB();

?>

<body class="home">
<div class="wrapper">
	<div id="header">
<?php
// Sets the tab

		if ($_GET['page'] == 'sange' || $_GET['page'] =='') {
			echo '		<div id="sange_img"><img src="img/sange_down.gif" alt="Sange" /></div>';
		} else {
			echo '		<div id="sange_img"><a href="main.php?page=sange" target="_top"><img src="img/sange_up.gif" alt="Sange" width="101" height="27" border="0" /></a></div>';
		}

		if ($_GET['page'] == 'program') {
			echo '		<div id="program_img"><img src="img/program_down.gif" alt="Program" /></div>';
		} else {
			echo '		<div id="program_img"><a href="main.php?page=program" target="_top"><img src="img/program_up.gif" alt="Program" width="101" height="27" border="0" /></a></div>';
		}

		if ($_GET['page'] == 'teams') {
			echo '		<div id="teams_img"><img src="img/teams_down.gif" alt="Teams" /></div>';
		} else {
			echo '		<div id="teams_img"><a href="main.php?page=teams" target="_top"><img src="img/teams_up.gif" alt="Teams" width="101" height="27" border="0" /></a></div>';
		}

		if ($_GET['page'] == 'mypage') {
			echo '		<div id="mypage_img"><img src="img/mypage_down.gif" alt="My side" /></div>';
		} else {
			echo '		<div id="mypage_img"><a href="main.php?page=mypage" target="_top"><img src="img/mypage_up.gif" alt="Min side" width="101" height="27" border="0" /></a></div>';
		}

		if ($_SESSION['admin'] == 1) {
			if ($_GET['page'] == 'admin') {
				echo '		<div id="admin_img"><img src="img/admin_down.gif" alt="Admin" /></div>';
			} else {
				echo '		<div id="admin_img"><a href="main.php?page=admin" target="_top"><img src="img/admin_up.gif" alt="Admin" width="101" height="27" border="0" /></a></div>';
			}
		}
		
?>

	</div> <!-- End of header -->

<?php

if (isset($_SESSION['logget_ind'])) {

	if ($_GET['page'] != 'teams') include('sidebar.php');
?>
	<div class="block_1">
		<div class="main">
<?php
	if ($_GET['page'] == 'program') {
		include('program.php');
	} else if ($_GET['page'] == 'teams') {
		include('teams.php');
	} else if ($_GET['page'] == 'mypage') {
		include('mypage.php');
	} else if ($_GET['page'] == 'admin' && $_SESSION['admin'] == 1) {
		include('admin.php');
	} else {
		include('sange.php');
	}

	closeDB();
} else {
?>
	<div class="block_1">
		<div class="main">
			<h1>Du er ikke logget ind</h1>

			<div class="login">
				<form action="login.php" name="loginform" method="post">

					<table width="250" border="0" align="center" cellpadding="3" cellspacing="0">
					<tr>
						<td colspan="2"><strong>Log ind </strong></td>
					</tr>
					<tr>
						<td>Brugernavn</td>
						<td align="right"><input name="brugernavn" id="brugernavn" type="text" ><script language="JavaScript">document.getElementById('brugernavn').focus();</script></td>
					</tr>
					<tr>
						<td>Adgangskode</td>
						<td align="right"><input type="password" name="password"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="right"><input type="submit" name="login" value="Login" class="submit_btn_2"/></a></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="right"><br /><a href="forgot.php">Glemt adgangskode? </a></td>
					</tr>
					</table>
					<input name="target" type="hidden" value="<?php echo $_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING']; /*$_SERVER['REQUEST_URI'];*/ ?>">
				</form>
				<p>&nbsp;</p>
				<br />
			</div> <!-- login -->
<?php
}
?>
		</div> <!-- End of main -->
	</div> <!-- End of block_1 -->

</div> <!-- End of wrapper -->
<div class="footer"></div>
</body>
</html>
