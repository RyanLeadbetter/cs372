<?php 
session_start();     
if (!isset($_SESSION['email']) || $_SESSION['email'] == '' ) {
    header("Location: Chess_LoginPage.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="lib/chessboardjs/css/chessboard-0.3.0.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<script>
    function selectDifficulty() {
        if ( $("input[name=difficulty]:checked").val() == "easy") {
            $('#Easy').attr("selected", true);
        }
        else if ( $("input[name=difficulty]:checked").val() == "medium") {
            $('#Medium').attr("selected", true);
        }
        else if ( $("input[name=difficulty]:checked").val() == "hard") {
            $('#Hard').attr("selected", true);
        }
        $("#lightbox").css("display", "none");
    }

    function promptUserLeave() {
	    alert("this works");
    }
</script>
<div class="container-fluid chessheader">
        <div class="container text-center" style="background:none;">
            <br>
            <h1 class="tittle">The <br>Chess Game</h1>
            <!--<button class="btn btn-default">About</button>-->
        </div>
</div>

<?php
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
        $sql = "UPDATE user_information SET aiWins=aiWins + 1, aiGamesPlayed=aiGamesPlayed + 1 WHERE email='$_SESSION[email]'";
    }
    else if ($_POST["result"] == "draw") {
        $sql = "UPDATE user_information SET aiDraws=aiDraws + 1, aiGamesPlayed=aiGamesPlayed + 1 WHERE email='$_SESSION[email]'";
    }
    else if ($_POST["result"] == "lose") {
        $sql = "UPDATE user_information SET aiGamesPlayed=aiGamesPlayed + 1 WHERE email='$_SESSION[email]'";
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
    
<br>
<div id="board" class="board"></div>
<div class="info">

    <select hidden id="search-depth">
        <option value="1" id="Easy">1</option>
        <option value="2" id="Medium">2</option>
        <option value="3" id="Hard">3</option>
    </select>

    <br>
    <div hidden id="move-history" class="move-history">
    </div>
</div>
<div>
	<button id="forfeit" onclick="promptUserLeave()" type="button" class="btn btn-block">Forfeit and Return to Main Menu</button>
</div>
<div id="lightbox" class="modal" id="myModal" role="dialog" style="display: block; padding-top: 15%;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="box-shadow:0 0 0 1600px rgba(0,0,0,0.55);">
        <div class="modal-header">
          <h4 id="messageHeader" class="modal-title">Select a difficulty:</h4>
        </div>
        <div id="messageBody" class="modal-body">
          <input id="radio1" type="radio" name="difficulty" value="easy"> Easy<br>
          <input id="radio2" type="radio" name="difficulty" value="medium"> Medium<br>
          <input id="radio3" type="radio" name="difficulty" value="hard"> Hard
        </div>
        <div class="modal-footer">
          <button id="button1" onclick="selectDifficulty()" type="button" class="btn btn-block" data-dismiss="modal">Ok</button>
        </div>
      </div>
      
    </div>
  </div>
<script src="lib/jquery/jquery-3.2.1.min.js"></script>
<script src="lib/chessboardjs/js/chess.js"></script>
<script src="lib/chessboardjs/js/chessboard-0.3.0.js"></script>
<script src="ai.js"></script>

</body>
</html>
