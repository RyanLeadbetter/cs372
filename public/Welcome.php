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
    $conn = mysqli_connect("localhost", "root", "", "chess_game");
	
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
    $sql = "SELECT * FROM user_information WHERE email='$_SESSION[email]'";
    $result = mysqli_query($conn, $sql);
	 $pass = mysqli_fetch_assoc($result);
    echo 'Welcome, ' . $pass['firstName'];
    
    if (mysqli_query($conn, $sql)) {
	} 
    else { 
		echo "Error: " . $sql . " " . mysqli_error($conn);
	}
	
	mysqli_close($conn);
}
    
if (isset($_GET["logout"]))
{
    session_destroy();
    $_SESSION = array();
    header('Location: Chess_LoginPage.php');
}
?>

<div class="container">
    <a class="btn btn-block btn-lg" type="button" href="pvp.html">Start PvP Match</a>
    </br>
    <a class="btn btn-block btn-lg" type="button" href="cpu.html">Start AI Match</a>
    </br>
    <a class="btn btn-block btn-lg" type="button" href="leaderboard.php">View Leaderboards</a>
    </br>
    <a class="btn btn-block btn-lg" type="button" href="Welcome.php?logout=true" name="logout">Logout</a>
</div>
    
</body>
</html>