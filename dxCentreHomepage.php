<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if($_POST['formType'] == "login") {
    $userName = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $conn = new mysqli("localhost", "root", "root", "teleradauthdb");
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT IF(EXISTS(SELECT * FROM userauth_table where username = $userName and password = $password), 1, 0);";
    $result = $conn->query($sql);
    if($result == 1) {
      echo "success";
      session_start();
      $_SESSION["gateway"] = $userName;
      echo "Welcome".$_SESSION["gateway"];
      // start session
      // show table
    }
    else {

    }

    $conn->close();
  }
  else if($_POST['formType'] == "register") {

  }
}
?>