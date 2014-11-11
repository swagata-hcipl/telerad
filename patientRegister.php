<?php
if(isset($_REQUEST))
{
	include('session.php');
	include('functions.php');
    $conn = new mysqli("localhost","root","root","teleraddb");
	error_reporting(E_ALL && ~E_NOTICE);

	$patientName=$_POST['patientName'];
	$dob=$_POST['dob'];
	$patientGender=$_POST['patientGender'];
	$patientAddress1=$_POST['patientAddress1'];
	$patientAddress2=$_POST['patientAddress2'];
	$patientUsername=$_POST['patientUsername'];
	date_default_timezone_set('Asia/Kolkata');
	$datetime = date('Y-m-d H:i:s');
	
	$sql = "INSERT INTO patient_table (patient_id, name, dob, gender, address1, address2, datetime) VALUES ('$patientUsername', '$patientName', '$dob', '$patientGender', '$patientAddress1', '$patientAddress2', '$datetime')";
	$result = $conn->query($sql);
	if($result) {
		/* $_SESSION['patientId'] = $patientUsername;
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
	// include('clientPatient.php');
	
	$conn = new mysqli("localhost","root","root","teleraddb");
	$clientID = getIdByGateway($_SESSION['gateway']);
	$patientID = getIdByPatientID($patientUsername);
	// echo $clientID;
	date_default_timezone_set('Asia/Kolkata');
	$datetime = date('Y-m-d H:i:s');
	$sql = "INSERT INTO client_patient_table (fk_client, fk_patient, datetime) VALUES ('$clientID', '$patientID', '$datetime')";
	$result = $conn->query($sql);
	if($result) {
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
	
	
}
?>