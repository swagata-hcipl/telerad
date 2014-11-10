<?php
$conn = new mysqli("localhost","root","root","teleraddb");
$clientID = getIdByGateway($_SESSION['gateway']);
$patientID = getIdByPatientID($_SESSION['patientId']);
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
?>
	