<?php
function getGatewayById($fk_client) {
	$conn = new mysqli("localhost","root","root","teleraddb");
	$sql = "SELECT gateway FROM client_table WHERE id='$fk_client'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	return $row->gateway;
}

function getIdByGateway($gateway) {
	$conn = new mysqli("localhost","root","root","teleraddb");
	$sql = "SELECT id FROM client_table WHERE gateway='$gateway'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	return $row->id;
}

function getIdByPatientID($pid) {
	$conn = new mysqli("localhost","root","root","teleraddb");
	$sql = "SELECT id FROM patient_table WHERE patient_id='$pid'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	return $row->id;
}

function getEverythingByPid($id) {
	$conn = new mysqli("localhost","root","root","teleraddb");
	$sql = "SELECT * FROM patient_table WHERE id='$id'";
	$result = $conn->query($sql);
	return $result;
}

function getNameByPatientID($pid) {
	$conn = new mysqli("localhost","root","root","teleraddb");
	$sql = "SELECT name FROM patient_table WHERE patient_id='$pid'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	return $row->name;
}
?>
