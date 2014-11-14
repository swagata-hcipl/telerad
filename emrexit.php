<?php
session_start();
unset($_SESSION['gateway']);
unset($_SESSION['patientID']);
// session_destroy();
if(session_destroy()) // Destroying All Sessions
{
echo "<script>window.close();</script>";
//header("Location: .php"); // Redirecting To Home Page
}
?>