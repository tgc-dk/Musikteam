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
    $page = isset($_GET['page']) ? $_GET['page']: '';

		if ($page == 'sange' || $page =='') {
			echo '		<div id="sange_img"><img src="img/sange_down.gif" alt="Sange" /></div>';
		} else {
			echo '		<div id="sange_img"><a href="main.php?page=sange" target="_top"><img src="img/sange_up.gif" alt="Sange" width="101" height="27" border="0" /></a></div>';
		}

		if ($page == 'program') {
			echo '		<div id="program_img"><img src="img/program_down.gif" alt="Program" /></div>';
		} else {
			echo '		<div id="program_img"><a href="main.php?page=program" target="_top"><img src="img/program_up.gif" alt="Program" width="101" height="27" border="0" /></a></div>';
		}

		if ($page == 'teams') {
			echo '		<div id="teams_img"><img src="img/teams_down.gif" alt="Teams" /></div>';
		} else {
			echo '		<div id="teams_img"><a href="main.php?page=teams" target="_top"><img src="img/teams_up.gif" alt="Teams" width="101" height="27" border="0" /></a></div>';
		}

		if ($page == 'mypage') {
			echo '		<div id="mypage_img"><img src="img/mypage_down.gif" alt="My side" /></div>';
		} else {
			echo '		<div id="mypage_img"><a href="main.php?page=mypage" target="_top"><img src="img/mypage_up.gif" alt="Min side" width="101" height="27" border="0" /></a></div>';
		}

		if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
			if ($page == 'admin') {
				echo '		<div id="admin_img"><img src="img/admin_down.gif" alt="Admin" /></div>';
			} else {
				echo '		<div id="admin_img"><a href="main.php?page=admin" target="_top"><img src="img/admin_up.gif" alt="Admin" width="101" height="27" border="0" /></a></div>';
			}
		}
		
?>

	</div> <!-- End of header -->

<?php

if (isset($_SESSION['logget_ind'])) {

	if ($page != 'teams') include('sidebar.php');
?>
	<div class="block_1">
		<div class="main">
<?php
	if ($page == 'program') {
		include('program.php');
	} else if ($page == 'teams') {
		include('teams.php');
	} else if ($page == 'mypage') {
		include('mypage.php');
	} else if ($page == 'admin' && isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
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
						<td align="right"><input name="brugernavn" id="brugernavn" type="text" ><script>document.getElementById('brugernavn').focus();</script></td>
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
<div class="footer"><script>
// Include the UserVoice JavaScript SDK (only needed once on a page)
UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/Wydo12CGpcI1wmN5TGdhdA.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

//
// UserVoice Javascript SDK developer documentation:
// https://www.uservoice.com/o/javascript-sdk
//

// Set colors
UserVoice.push(['set', {
  accent_color: '#808283',
  trigger_color: 'white',
  trigger_background_color: 'rgba(46, 49, 51, 0.6)'
}]);

// Identify the user and pass traits
// To enable, replace sample data with actual user traits and uncomment the line
UserVoice.push(['identify', {
  email:      '<?php echo $_SESSION['email'] ?>', // User’s email address
  //name:       'John Doe', // User’s real name
  //created_at: 1364406966, // Unix timestamp for the date the user signed up
  //id:         123, // Optional: Unique id of the user (if set, this should not change)
  //type:       'Owner', // Optional: segment your users by type
  //account: {
  //  id:           123, // Optional: associate multiple users with a single account
  //  name:         'Acme, Co.', // Account name
  //  created_at:   1364406966, // Unix timestamp for the date the account was created
  //  monthly_rate: 9.99, // Decimal; monthly rate of the account
  //  ltv:          1495.00, // Decimal; lifetime value of the account
  //  plan:         'Enhanced' // Plan name for the account
  //}
}]);

// Add default trigger to the bottom-right corner of the window:
UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'bottom-right' }]);

// Or, use your own custom trigger:
//UserVoice.push(['addTrigger', '#id', { mode: 'contact' }]);

// Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
UserVoice.push(['autoprompt', {}]);
</script></div>
</body>
</html>
