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
            <!--<button class="btn btn-default">About</button>-->
        </div>
    </div>
<a class='btn mr-auto' type="button" href='Welcome.php' style=";" > Return</a>
  <?php
if (isset($_SESSION['email'])) {
    $connectionInfo = array("UID" => "chess372@chess372", "pwd" => "Project372", "Database" => "chess_game", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    $serverName = "tcp:chess372.database.windows.net,1433";
    $conn = sqlsrv_connect($serverName, $connectionInfo);
	
	if (!$conn) {
		die("Connection failed: " . sqlsrv_errors());
	}
    //$sql = "SELECT * FROM user_information WHERE email='$_SESSION[email]'";
    $sql = "DELETE FROM user_information where lastName='Bone'";
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
?>
    <div class="page login" id='page-login'>
      <!--<div class='logo'></div>-->
      <input id='username' value="<?php echo $name; ?>" style="background-color: #ccc;" readonly></input>
      <button id='login'>Find opponent</button>
    </div>
    <div class="page lobby" id='page-lobby'>
      <h1>Choose your opponent</h1>
      <h2 id='userLabel'></h2>
      <h3>Active games</h3>
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

    <script src="lib/socket.io-1.2.0.js"></script>
    <script src="lib/jquery-1.11.1.js"></script>
    <script src="lib/WinJS.4.0/js/WinJS.min.js"></script>
    <script src="lib/chess.min.js"></script> 
    <script src="lib/chessboard-0.3.0.min.js"></script> 
    <script src="/default.js"></script>
  
  </body>
</html>
