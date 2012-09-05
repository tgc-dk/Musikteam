<?php $strTitle="Password - login";?>

<?php include("header.php"); ?>


<body class="home">

<div class="wrapper">
	<div id="header"></div>

	<div class="block_1">
	
   
		
    <div class="login"> 
      <form action="login.php" method="post">
        <table width="250" border="0" align="center" cellpadding="3" cellspacing="0">
          <tr> 
            <td colspan="2"><strong>Log ind </strong></td>
          </tr>
          <tr> 
            <td>Brugernavn</td>
            <td align="right"><input name="brugernavn" id="brugernavn" type="text" ></td>
          </tr>
          <tr> 
            <td>Adgangskode</td>
            <td align="right"><input type="password" name="password"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td align="right"><input type="submit" name="login" value="Login" class="submit_btn_2"/></a> 
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td align="right"><br /> <a href="forgot.php">Glemt adgangskode? </a></td>
          </tr>
        </table>
      </form>
      <p>&nbsp;</p>
      <br />
    </div>
	</div>
</div>
<div class="footer"></div>
<script language="JavaScript">document.getElementById('brugernavn').focus();</script>
</body>
</html>