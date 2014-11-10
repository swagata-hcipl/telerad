<?php
include('session.php');
include('functions.php');
echo $_SESSION['gateway'].'<br>';
error_reporting(E_ALL && ~E_NOTICE);
$clientID = getIdByGateway($_SESSION['gateway']);
// $patientID = getIdByPatientID($_SESSION['patientId']);
// echo $patientID.'<br>';
echo $clientID.'<br>';
$conn = new mysqli("localhost","root","root","teleraddb");

	$patientName="swagata Biswas-XV";
	$dob="1990-09-14";
	$patientGender=2;
	$patientAddress1="Beliaghata";
	$patientAddress2="Kolkata";
	$patientUsername="swagata20";
	date_default_timezone_set('Asia/Kolkata');
	$datetime = date('Y-m-d H:i:s');
	
	$sql = "INSERT INTO patient_table (patient_id, name, dob, gender, address1, address2, datetime) VALUES ('$patientUsername', '$patientName', '$dob', '$patientGender', '$patientAddress1', '$patientAddress2', '$datetime')";
	$result = $conn->query($sql);
	if($result) {
		$_SESSION['patientId'] = $patientUsername;
		echo $_SESSION['patientId'].'<br>';
		$patientID = getIdByPatientID($_SESSION['patientId']);
		echo $patientID.'<br>';
		// echo 'insert into patient table successful<br>';
		/*$sql = "SELECT id FROM client_table WHERE gateway = \"$centerUsername\"";
		$result = $conn->query($sql);
		while($row = $result->fetch_object())
		{
			$pid = $row->id;
		}*/
	} else {
		die('Error :'.$conn->error);
	}
	$conn->close();
	// echo $_SESSION['gateway'];
	include('clientPatient.php');
?>