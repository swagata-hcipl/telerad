<?php
$conn = new mysqli('localhost','root','root','teleraddb');
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$centerName = $_POST['centerName'];
$centerType = $_POST['centerType'];
$centerAddress1 = $_POST['centerAddress1'];
$centerAddress2 = $_POST['centerAddress2'];
date_default_timezone_set('Asia/Kolkata');
$datetime = date('Y-m-d H:i:s');
$centerUsername = $_POST['centerUsername'];
$centerPassword = $_POST['centerPassword'];
$centerPassword1 = $_POST['centerPassword1'];
if ($centerPassword != $centerPassword1) {
	die("wrong password ");
}
else {
	$password = hash('sha256', $centerPassword);
}
$sql = "INSERT INTO client_table (gateway, name, type, address1, address2, datetime) VALUES ('$centerUsername', '$centerName', '$centerType', '$centerAddress1', '$centerAddress2', '$datetime')";
$result = $conn->query($sql);
if($result) {
	echo 'insert into client table successful<br>';
	$sql = "SELECT id FROM client_table WHERE gateway = \"$centerUsername\"";
	$result = $conn->query($sql);
	while($row = $result->fetch_object())
	{
		$pid = $row->id;
	}
} else {
	die('Error :'.$conn->error);
}
$user_role = 1;
$conn = new mysqli('localhost','root','root','teleradauthdb');
$sql = "INSERT INTO userauth_table (user_role, username, auth_string, fk_user_id) VALUES ('$user_role', '$centerUsername', '$password', '$pid')";
$result = $conn->query($sql);
if($result) {
	echo 'insert into auth table successful<br>';
} else {
	die('Error :'.$conn->error);
}
$conn->close;
?>