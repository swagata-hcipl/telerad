<!DOCTYPE html>
<?php
include('session.php');
?>
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
	echo '<h1>Welcome '.$_SESSION['gateway'].'</h1>';
	echo '<h4 align="right"><a href="logout.php">Logout</a></h4>';
	include 'showPatients.php';
?>
</div>
</div>
</div>
</body>
</html>