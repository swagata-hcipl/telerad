<?php

if(!isset($_SESSION['patientID']) && isset($_POST['pid'])) {
$gateway = $_POST['gateway'];
$patientID = $_POST['pid'];
$patientName = $_POST['pName'];
$conn = new mysqli("localhost","root","root","teleraddb");
$sql = "SELECT type FROM client_table WHERE gateway='$gateway'";
$result = $conn->query($sql);
$row = $result->fetch_object();
$gatewayType = $row->type;
date_default_timezone_set('Asia/Kolkata');
$datetime = date('Y-m-d H:i:s');
if($gatewayType == 2) {
	$sql = "SELECT patient_id FROM patient_table WHERE exit_id='$patientID'";
	$result = $conn->query($sql);
	session_start();
	if(!$result->num_rows) {
		// echo "new emr patient";
		$sql = "INSERT INTO patient_table (patient_id, name, dob, gender, datetime, exit_id) VALUES ('$patientID', '$patientName', '1990-01-01', '11', '$datetime', '$patientID')";
		$result = $conn->query($sql);
		if($result) {
			// echo 'successful';
			$sql = "SELECT id FROM patient_table WHERE exit_id='$patientID'";
			$result = $conn->query($sql);
			$row = $result->fetch_object();
			$patient_id = $gateway.$row->id;
			// echo $patient_id;
			$sql="UPDATE patient_table SET patient_id ='$patient_id' WHERE id='$row->id'";
			$result = $conn->query($sql);
			if($result) {
				// echo 'successful';
			} else {
				die('Error :'.$conn->error);
			}
			$_SESSION['gateway'] = $gateway;
			$_SESSION['patientID'] = $patient_id;
		} else {
			die('Error :'.$conn->error);
		}
		
		
		// $conn = new mysqli("localhost","root","root","teleraddb");
		$clientID = getIdByGateway($_SESSION['gateway']);
		$patientID = getIdByPatientID($_SESSION['patientID']);
		// echo $clientID;
		date_default_timezone_set('Asia/Kolkata');
		$datetime = date('Y-m-d H:i:s');
		$sql = "INSERT INTO client_patient_table (fk_client, fk_patient, datetime) VALUES ('$clientID', '$patientID', '$datetime')";
		$result = $conn->query($sql);
		if($result) {
		} else {
			die('Error :'.$conn->error);
		}
		$conn->close();
	}
	else {
		// echo "existing emr patient";
		$_SESSION['gateway'] = $gateway;
		$row = $result->fetch_object();
		$_SESSION['patientID'] = $row->patient_id;
	}
	// echo '<a href="emrexit.php" id="registerLink" class="btn btn-primary  pull-right">Exit</a>';
}

} else {
	include('session.php');
	//include('session2.php');
    
	/*echo '<a href="profile.php" id="registerLink" class="btn btn-primary btn-lg">Patients</a>';
	echo '<a href="logout.php" id="registerLink" class="btn btn-primary  pull-right">Log out</a>';*/
}

if(isset($_POST['patientID'])) {
// echo "coming from DX clients";
$patient_table_id = $_POST['patientID'];
$conn = new mysqli("localhost","root","root","teleraddb");
	$sql = "SELECT patient_id FROM patient_table WHERE id='$patient_table_id'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	
$_SESSION['patientID'] = $row->patient_id;
}
if(isset($_SESSION['patientID'])) {
    $conn = new mysqli("localhost","root","root","teleraddb");
    $client = $_SESSION['gateway'];
    $sql = "SELECT type FROM client_table WHERE gateway='$client'";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $gatewayType = $row->type;
    if($gatewayType == 2) {
        // echo '<a href="emrexit.php" id="registerLink" class="btn btn-primary  pull-right">Exit</a>';
    } else {
        echo '<a href="profile.php" id="registerLink" class="btn btn-primary btn-lg">Patients</a>';
        echo '<a href="logout.php" id="registerLink" class="btn btn-primary  pull-right">Log out</a>';
    }
}
echo '<div style="clear: both"><h1 style="float: left">'.getNameByGateway($_SESSION['gateway']).'</h1>
                                <h3 style="float: right">'.getNameByPatientID($_SESSION['patientID']).'</h3></div><br>';

// session_start();
/*error_reporting(E_ERROR | E_PARSE);
$doc = new DOMDocument();
$doc->validateOnParse = true;
$doc->loadHTMLFile('upload.php');
$tagname = $doc->getElementById('counter');
$counter = $tagname->getAttribute('value');
echo $counter;
if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['patientID']) and isset($_SESSION['counter'])) {
	$_SESSION['gateway'] = $_POST['gateway'];
	$temp_counter = $_SESSION['counter'] + 1;
	$_SESSION[$temp_counter]['patientID'] = $_POST['patientID'];
	$_SESSION['counter'] = $temp_counter;
	$tagname = $doc->getElementById('counter');
	$counter = $tagname->setAttribute('value',$temp_counter);
}
else if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['patientID'])) {
	$_SESSION['gateway'] = $_POST['gateway'];
	$_SESSION[0]['patientID'] = $_POST['patientID'];
	$_SESSION['counter'] = 0;
}
else {
	//$counter= document.getElementById('counter').value;
}*/
?>