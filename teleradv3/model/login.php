<?php
session_start();
 ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$userName = $_POST['username'];
	$password = hash('sha256', $_POST['password']);
	$conn = new mysqli("localhost", "root", "root", "teleradauthdb");
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}
	$sql = 'SELECT * FROM userauth_table WHERE username = "'.$userName.'" AND auth_string = "'.$password.'"';
	$result = $conn->query($sql);
	if($result->num_rows==1) {
		$row = $result->fetch_object();
		$_SESSION['gateway'] = $userName;
		//header("location: profile.php");
		echo 'true';
	}
	else {
		echo 'false';
		//header("location: ./../html/login.html");
		
	}
	$conn->close();
}
session_unset();
session_destroy();
?>