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
    } else if($_POST['formType'] == "register") {
		include 'centerregister.php';
		header("location: profile.php");
	}
}
?>
<script type="text/javascript"> 
        // wait for the DOM to be loaded 
        $(document).ready(function() { 

            $("#centerUsername").keyup(function (e) {
				//removes spaces from username
				$(this).val($(this).val().replace(/\s/g, ''));
				var username = $(this).val();
				if(username.length < 2){$("#user-result").html('');return;}
				
				if(username.length >= 2){
					$("#user-result").html('<img src="./img/ajax-loader.gif"/>');
					$.post('check_username.php', {'centerUsername':username}, function(data) {
					  $("#user-result").html(data);
					});
				}
			});	
        }); 
</script>
</head>
<body>
<div class="container">
<div class="row">
<br>
<div class="col-md-12">
<h2> Register Here </h2>
<form id="registerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="formType" value="register"/>
<table>
<tbody>
<div class="form-group">
<tr>
<td><label for="centerName">Name of Diagnostic Center</label></td>
<td><input class="form-control" id="centerName" type="text" name="centerName" required/></td>
</tr>
<tr>
<td><label for="centerType">Center Type</label></td>
<td><select class="form-control" id="centerType" name="centerType" required>
  <option value="1">Diagnostic Center</option>
  <option value="2">EMR</option>
</select></td>
</tr>
<tr>
<td><label for="centerAddress1">Address1</label></td>
<td><input class="form-control" id="centerAddress1" type="text" name="centerAddress1" required/></td>
</tr>
<tr>
<td><label for="centerAddress2">Address2</label></td>
<td><input class="form-control" id="centerAddress2" type="text" name="centerAddress2"/></td>
</tr>
</div>
<div class="form-group">
<tr>
<td><label for="centerUsername">Center Username</label></td>
<td><input class="form-control" id="centerUsername" type="text" name="centerUsername" required></input></td>
<td><span id="user-result"></span></td>
</tr>
<tr>
<td><label for="centerPassword">Password</label></td>
<td><input class="form-control" id="centerPassword" type="password" name="centerPassword" required/></td>
</tr>
<tr>
<td><label for="centerPassword1">Re-type Password</label></td>
<td><input class="form-control" id="centerPassword1" type="password" name="centerPassword1" required/></td>
</tr>
</div>
</tbody>
</table>
<button type="Submit" class="btn btn-primary">Register</button>
</form>
</div>

<div class="col-md-3">
<h2> Login Here </h2>
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
</div>
</body>
</html>