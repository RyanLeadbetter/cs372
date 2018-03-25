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
        alert("Yay you did it");
        alert($('#Medium').selected());
        alert($('#radio1').prop());
        $("myModal").style("display", "none");
    }
</script>
<div class="container-fluid chessheader">
        <div class="container text-center" style="background:none;">
            <br>
            <h1 class="tittle">The <br>Chess Game</h1>
            <!--<button class="btn btn-default">About</button>-->
        </div>
</div>

<br>
<div id="board" class="board"></div>
<div class="info">

    <select hidden id="search-depth">
        <option value="1" id="Easy">1</option>
        <option value="2" id="Medium" selected>2</option>
        <option value="3" id="Hard">3</option>
    </select>

    <br>
    <div hidden id="move-history" class="move-history">
    </div>
</div>
<div id="lightbox" class="modal" id="myModal" role="dialog" style="display: block; padding-top: 15%;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" id="messageHeader" style="box-shadow:0 0 0 1600px rgba(0,0,0,0.55);">
        <div class="modal-header">
          <h4 class="modal-title">Select a difficulty:</h4>
        </div>
        <div class="modal-body">
          <input id="radio1" type="radio" name="gender" value="male"> Easy<br>
          <input id="radio2" type="radio" name="gender" value="female"> Medium<br>
          <input id="radio3" type="radio" name="gender" value="other"> Hard
        </div>
        <div class="modal-footer">
          <button onclick="selectDifficulty()" type="button" class="btn btn-block" data-dismiss="modal">Ok</button>
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
