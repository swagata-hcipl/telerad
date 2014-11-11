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
		if($result->num_rows==1) {
			$row = $result->fetch_object();
			$_SESSION['gateway'] = $userName;
			header("location: profile.php");
		}
		else {
			$newURL = $_SERVER['HTTP_REFERER'];
			header('Location: '.$newURL); 
			die();
		}
		$conn->close();
    } else if($_POST['formType'] == "register") {
		include 'centerregister.php';
		header("location: profile.php");
	}
}
?>
