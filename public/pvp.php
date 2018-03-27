<?php 
session_start();     
if (!isset($_SESSION['email']) || $_SESSION['email'] == '' ) {
    header("Location: Chess_LoginPage.php");
}
?>
<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=410; user-scalable=no; minimal-ui">
    <title>Real Chess</title>
    <link rel="stylesheet" href="lib/chessboard-0.3.0.min.css">
    <link rel="stylesheet" href="lib/WinJS.4.0/css/ui-light.css" />
     <link rel="stylesheet" href="/default.css" />
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
   
  </head>
  <body>
     <div class="container-fluid chessheader page login">
        <div class="container text-center" style="background:none;">
            <br>
            <h1 class="tittle">The <br>Chess Game</h1>
            <button class="btn btn-default" onclick="window.open('https://en.wikipedia.org/wiki/Rules_of_chess');" target="_blank">Don't know what to do? See the rules</button>
	    <br>
        </div>
    </div>
<a id="returnButton" class='btn mr-auto' type="button" href='Welcome.php'> Return</a>
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
    $name = $pass['firstName'];
    
    if (sqlsrv_query($conn, $sql)) {
	} 
    else { 
		echo "Error: " . $sql . " " . sqlsrv_error($conn);
	}
	
	sqlsrv_close($conn);
}
if (isset($_POST["result"]))
{
// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "chess372@chess372", "pwd" => "Project372", "Database" => "chess_game", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:chess372.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
	
	if (!$conn) {
		die("Connection failed: " . sqlsrv_errors());
	}
	
    if  ($_POST["result"] == "win")
    {
        $sql = "UPDATE user_information SET pvpWins=pvpWins + 1, pvpGamesPlayed=pvpGamesPlayed + 1 WHERE email='$_SESSION[email]'";
    }
    else if ($_POST["result"] == "draw") {
        $sql = "UPDATE user_information SET pvpDraws=pvpDraws + 1, pvpGamesPlayed=pvpGamesPlayed + 1 WHERE email='$_SESSION[email]'";
    }
    else if ($_POST["result"] == "lose") {
        $sql = "UPDATE user_information SET pvpGamesPlayed=pvpGamesPlayed + 1 WHERE email='$_SESSION[email]'";
    }
    else {
        echo "An error has occurred in retrieving the result";
    }
    
    if (sqlsrv_query($conn, $sql)) {
	   header("Location: Welcome.php");
	} 
    else { 
		echo "Error: " . $sql . " " . sqlsrv_error($conn);
	}
	
	sqlsrv_close($conn);
}
?>
    <div class="page login" id='page-login'>
      <!--<div class='logo'></div>-->
      <input id='username' value="<?php echo $name; ?>" style="background-color: #ccc;" readonly></input>
      <button id='login'>Find opponent</button>
    </div>
    <div class="page lobby" id='page-lobby'>
      <h1>Choose your opponent</h1>
      <h2 id='userLabel'></h2>
      <h3 style="display: none">Active games</h3>
      <div id='gamesList'>
        No active games
      </div>
      <h3>Online players</h3>
       <div id='userList'>
         No users online
      </div>
    </div>
    <div class="page game" id='page-game'>
      <button id='game-back'>Back</button>
      <button id='game-resign'>Resign</button>
      <div id='game-board' style="width: 400px">
      </div>
    </div>
<div id="lightbox" class="modal" id="myModal" role="dialog" style="display: none; padding-top: 15%;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="box-shadow:0 0 0 1600px rgba(0,0,0,0.55);">
        <div class="modal-header">
          <h4 id="messageHeader" class="modal-title">Select a difficulty:</h4>
	  <button id="close" onclick="cancel()" type="button" class="close" data-dismiss="modal" style="display: none;">&times;</button>
        </div>
        <div id="messageBody" class="modal-body">
        </div>
        <div class="modal-footer">
          <button id="button1" onclick="selectDifficulty()" type="button" class="btn btn-block" data-dismiss="modal">Ok</button>
        </div>
      </div>
    <script src="lib/socket.io-1.2.0.js"></script>
    <script src="lib/jquery/jquery-3.2.1.min.js"></script>
    <script src="lib/WinJS.4.0/js/WinJS.min.js"></script>
    <script src="lib/chess.min.js"></script> 
    <script src="lib/chessboard-0.3.0.min.js"></script> 
    <script src="/default.js"></script>
  
  </body>
</html>
