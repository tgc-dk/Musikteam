<?php
session_start();
session_unset();
session_destroy();

$strTitle="Log af";
include("header.php");

?>


<body class="home">

<div class="wrapper">
	<div id="header"></div>
	<div class="block_1">
		<div class="main">
			<p>Du er nu logget af. <a href="index.php">Log ind igen</a>.</p>
		</div>
	</div>
</div> <!-- End of wrapper -->
<div class="footer"></div>
</body>
</html>