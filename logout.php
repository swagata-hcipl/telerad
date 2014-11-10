<?php
session_start();
unset($_SESSION["gateway"]);
unset($_SESSION["patientId"]);
if(session_destroy()) // Destroying All Sessions
{
header("Location: login.php"); // Redirecting To Home Page
}
?>