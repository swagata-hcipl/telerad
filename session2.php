<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$conn = new mysqli("localhost", "root", "root", "teleraddb");
session_start();// Starting Session
// Storing Session
$check=$_SESSION['gateway'];
// SQL Query To Fetch Complete Information Of User
$ses_sql="select gateway from client_table where gateway='$check'";
$result = $conn->query($ses_sql);
$row = $result->fetch_object();
$login_session = $row->gateway;
if(!isset($login_session)){
$conn->close(); // Closing Connection
header('Location: login.php'); // Redirecting To Home Page
}
?>
