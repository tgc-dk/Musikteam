<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

if($_SERVER["SERVER_NAME"] == "localhost" && isset($_REQUEST["thecityid"])){
    include("db.php");
    openDB();
    
    $query = "SELECT * FROM Bruger WHERE onTheCityId = ".$_REQUEST["thecityid"];
    $result = doSQLQuery($query);
    
    session_start();
    $_SESSION['logget_ind']=1;
    $_SESSION['brugerid'] = db_result($result, "BrugerId");
    $_SESSION['brugernavn'] = db_result($result, "Brugernavn"); //$brugernavn;
    $_SESSION['password'] = db_result($result, 'password'); //$password;
    $_SESSION['email'] = db_result($result, "Email");
    $_SESSION['admin'] = db_result($result, "Admin");

    closeDB();
    header("Location: main.php");
}
//if ($_POST['target'] != "") {
//    header("Location: ".$_POST['target']);
//} else {
//    header("Location: main.php");
//}
// 
//    session_start();
//    $strTitle="Passwordkontrol";
    include("header.php");
//    include("db.php");
//    openDB();
//    $username = addslashes($_POST['brugernavn']);
//    $password = addslashes($_POST['password']);
//    
//    $query = "SELECT Admin, BrugerId, Email FROM Bruger WHERE Brugernavn ='".$username."' AND Kode = '".md5($password.'musikteam')."'";
//    $result = doSQLQuery($query);

//    $admin = db_result($result, "Admin");
//    if ($admin != "") {
//        $_SESSION['logget_ind']=1;
//        $_SESSION['brugerid'] = db_result($result, "BrugerId");
//        $_SESSION['brugernavn'] = $_POST['brugernavn']; //$brugernavn;
//        $_SESSION['password'] = md5($_POST['password'].'musikteam'); //$password;
//        $_SESSION['email'] = db_result($result, "Email");
//        $_SESSION['admin'] = $admin;
//    } else {
//        echo "<h1>Ukorrekt login</h1><p>Du skal være logget ind for at se disse sider. <a href=\"default.php\">Log in</a></p>";
//    }
//    closeDB();
?>




<body class="home">
    <div class="wrapper">
        <div id="header"></div>

        <div class="block_1">
                            <!--[if lte IE 6]></p>
                                <style type="text/css">
                                    #ie6msg{border:3px solid #090; margin:8px 0; background:#cfc; color:#000;}
                                    #ie6msg h4{margin:8px; padding:0;}
                                    #ie6msg p{margin:8px; padding:0;}
                                    #ie6msg p a.getie7{font-weight:bold; color:#006;}
                                    #ie6msg p a.ie6expl{font-weight:normal; color:#006;}
                                </style>
                                <div id="ie6msg">
                                <h4>OBS: Du har en ældre version af browseren Internet Explorer.</h4>
                                <p>
                                        For at få en bedst mulig oplevelse af at bruge denne webside,<br />
                                        kan du gratis <a class="getie7" href="http://www.microsoft.com/danmark/windows/downloads/ie/getitnow.mspx" target="_blank">hente en nyere version af Internet Explorer</a>,<br />
                                        eller bruge en anden browser som <a class="getie7" href="http://getfirefox.com">Mozilla Firefox</a> eller <a class="getie7" href="http://www.google.com/chrome/?hl=da">Google Chrome</a>.<br/>
                                        Bruger du en arbejds-PC bør du kontakte den IT-ansvarlige.
                                    </p>
                                <p>
                                    </p>
                                </div>
                                <p>< ![endif]-->
        </div>
    </div>

    <div class="footer"></div>
</body>
</html>
