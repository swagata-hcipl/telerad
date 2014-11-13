<!DOCTYPE html>
<html>
<head>
<title>Telerad Login</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js" ></script>
<script src="js/jquery-1.11.1.min.js" ></script>
<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($_POST['formType'] == "login") {
		$userName = $_POST['username'];
		$password = hash('sha256', $_POST['password']);
		$conn = new mysqli("localhost", "root", "root", "teleradauthdb");
		if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
		}
		$sql = 'SELECT * FROM userauth_table WHERE username = "'.$userName.'" AND auth_string = "'.$password.'"';
		$result = $conn->query($sql);
		// echo $userName;
		// echo $password;
		if($result->num_rows==1) {
			$row = $result->fetch_object();
			$_SESSION['gateway'] = $userName;
			header("location: profile.php");
		}
		else {
			echo '<script type="text/javascript"> 
			$(document).ready(function(){
				document.getElementById("wrongUP").style.display = "block";
			})
			</script>';
		}
		$conn->close();
    }/* else if($_POST['formType'] == "register") {
		include 'centerregister.php';
		header("location: profile.php");
	}*/
}
?>
</head>
<body>
<div class="container">
<div class="row">
<br>
<div class="col-sm-12 col-md-12 col-lg-12">
<h2> Login Here </h2>
<div class="col-xs-4">
<form id="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" role="form">
<div class="form-group">
<p id="wrongUP" style="display:none">Wrong Username or Password!</p>
<input type="hidden" name="formType" value="login">

<label for="username">Center Username</label>
<input class="form-control" id="username" type="text" name="username" required/>
<label for="password">Password</label>
<input class="form-control" id="password" type="password" name="password" required/>
</div>
<button type="Submit" class="btn btn-primary">Log-in</button>
</form>
</div>
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12 col-md-12 col-lg-12">
</div>
</div>
</div>
</body>
</html>