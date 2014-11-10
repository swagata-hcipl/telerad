<!DOCTYPE html>
<html>
<head>
<title>Dicom Files Upload to PACS</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="js/jquery-1.11.1.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<link href="datatables/css/jquery.dataTables.min.css" rel="stylesheet" media="screen">
<script src="datatables/js/jquery.dataTables.min.js"></script>
</head>
<body>
<div class="container">
<div class="row">
<br>
<div class="col-sm-12 col-md-12 col-lg-12">
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
    $sql = 'SELECT * FROM userauth_table WHERE username = "'.$userName.'" AND auth_string = "'.$password.'"';
    $result = $conn->query($sql);
    if($result->num_rows) {
		while($row = $result->fetch_object())
		{
			echo "Welcome ".$row->username;
			session_start();
			$_SESSION['gateway'] = $userName;
		}
    }
    else {
		$newURL = $_SERVER['HTTP_REFERER'];
		header('Location: '.$newURL);
		echo "<script language='javascript'>alert('thanks!');</script>"; 
		die();
		
    }

    $conn->close();
    } else if($_POST['formType'] == "register") {
		include 'centerregister.php';
		
	}
	
	include 'showPatients.php';
	
}
?>
</div>
</div>
</div>
</body>
</html>