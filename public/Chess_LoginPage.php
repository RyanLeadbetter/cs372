<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<title>Login Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
if (isset($_POST["Login"]))
{

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "chess372@chess372", "pwd" => "Project372", "Database" => "chess_game", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:chess372.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
	
	if (!$conn) {
		die("Connection failed: " . sqlsrv_errors());
	}

    if (trim($_POST["email"]) != "" && trim($_POST["pwd"]) != "" && preg_match("/\S*[^A-Za-z]/", $_POST["pwd"], $matches))
    {
        $sql = "SELECT * FROM user_information WHERE email='$_POST[email]'";
	 $result = sqlsrv_query($conn, $sql);
	    echo "The query works";
	 $pass = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	    echo "The fetch works";
        if ($pass['password'] != NULL && $pass['password'] == $_POST["pwd"])
        {
	     echo "and it gets this far";
	     $user = $_POST['email'];
	     $_SESSION['email'] = $email;
	     header("Location: Welcome.html");
        }
        else
        {
            echo "<div style='text-align: center;'>Email or password is incorrect.</div>";
        }
    }
    else {
        echo "Error, one of the values you have entered is in an inappropriate format.";
    }
    
    if (sqlsrv_query($conn, $sql)) {
	} 
    else { 
		echo "Error: " . $sql . " " . sqlsrv_error($conn);
	}
	
	sqlsrv_close($conn);

}
?>
    
<form id="Login" style="width:500px" action="Chess_LoginPage.php" method="post" enctype="multipart/form-data">
  <div class="container">
    <label><b>Email</b></label>
    <label id="uname_msg" class="err_msg"></label>
    <input id="uname" type="text" placeholder="Enter Email" size="30" name="email">
	
    <label><b>Password</b></label>
     <label id="pswd_msg" class="err_msg"></label>
    <input  type="password" placeholder="Enter Password" size="30" name="pwd">
     <input type="submit" name="Login" value="Login"/>
     <a class="dropdown-item" href="#">Forgot password?</a>
     <a class="dropdown-item" href="Chess_SignupPage.php">New around here? Sign up</a>
  </div>
</form>
    
<script>
document.getElementById("Login").addEventListener("submit", LogInForm, false);
window.onload=document.getElementById("name").value= "";
</script>
</body>
</html>
