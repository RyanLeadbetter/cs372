<?php 
session_start();     
if (!isset($_SESSION['email']) || $_SESSION['email'] == '' ) {
    header("Location: Chess_LoginPage.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Welcome</title>
<script type="text/javascript" src="js/Chess_validatePage.js"> </script> 
<link rel="stylesheet" type="text/css" href="css/hw2style.css"/>
<link rel="stylesheet" href="css/bootstrap.css" />
<link rel="stylesheet" href="css/style.css" />
</head>
<body>

    <div class="container-fluid chessheader">
        <div class="container text-center" style="background:none;">
            <br>
            <h1 class="tittle">The <br>Chess Game</h1>
            <!--<button class="btn btn-default">About</button>-->
        </div>
    </div>

<br>

<?php
if (isset($_SESSION['email'])) {
    $connectionInfo = array("UID" => "chess372@chess372", "pwd" => "Project372", "Database" => "chess_game", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    $serverName = "tcp:chess372.database.windows.net,1433";
    $conn = sqlsrv_connect($serverName, $connectionInfo);
	
	if (!$conn) {
		die("Connection failed: " . sqlsrv_errors());
	}
    $sql = "SELECT * FROM user_information WHERE email='$_SESSION[email]'";
    $result = sqlsrv_query($conn, $sql);
    $pass = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    echo 'Welcome, ' . $pass['firstName'];
    
    if (sqlsrv_query($conn, $sql)) {
	} 
    else { 
		echo "Error: " . $sql . " " . sqlsrv_error($conn);
	}
	
	sqlsrv_close($conn);
}
    
if (isset($_GET["logout"]))
{
    session_destroy();
    $_SESSION = array();
    header('Location: Chess_LoginPage.php');
}
?>

<div class="container">
    <a class="btn btn-block btn-lg" type="button" href="pvp.php">Start PvP Match</a>
    </br>
    <a class="btn btn-block btn-lg" type="button" href="cpu.php">Start AI Match</a>
    </br>
    <a class="btn btn-block btn-lg" type="button" href="leaderboard.php">View Leaderboards</a>
    </br>
    <a class="btn btn-block btn-lg" type="button" href="Welcome.php?logout=true" name="logout">Logout</a>
</div>
    
</body>
</html>
