<?php
$total=0;
$count=0;
$studyArray = array();
echo '<br><br><br>';
function dcmsndFucntion($tmpkey,$key, $studyArray) {
	$cmd_str = 'dcmsnd DCM4CHEE@localhost:11112 ';
	$cmd_str .= $tmpkey;
	$output = array();
	exec($cmd_str,$output,$return);
	if(!$return) {
		GLOBAL $count;
		$count++;
		$objStr = substr($output[4],19);
		if(!in_array($objStr,$studyArray)) {
            GLOBAL $studyArray;
            $studyArray[] = $objStr;
		}
	}
	else {
		echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error in uploading the file to PACS '.$key.'</div>';
	}
	GLOBAL $total;
	GLOBAL $count;
	$percent = intval(($count/$total * 50)+50)."%";
	echo '<script language="javascript">
	document.getElementById("information").innerHTML="'.$count.'/'.$total.' images(s) processed.";
	document.getElementById("barr").setAttribute("style","width:'.$percent.'");
	</script>';
	echo str_repeat(' ',1024*64); 
	ob_flush();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['file'])) {
	echo '<div class="alert alert-info" role="alert" id="informationdiv" style="width"><a href="#" class="close" data-dismiss="alert">&times;</a><p id="information"></p></div>';
	echo '<div id="progresss" class="progress progress-striped active">
    <div id="barr" class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:50%;">
    </div>
    </div>';
	foreach($_FILES['file']['size'] as $eachFile)
	{
		 if($eachFile > 0)
			$total++;
	}
	foreach ($_FILES["file"]["error"] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {
			$zip = new ZipArchive;
			$extName = explode('.',$_FILES['file']['name'][$key]);
			$ext = 'none';
			if(array_key_exists(1,$extName))
				$ext = $extName[1];
			if($ext!='none' && $ext == 'zip') {
				$res = $zip->open($_FILES['file']['tmp_name'][$key]);
				if($res==TRUE) {
					$zip->extractTo('./');
					$total--;
					$total += $zip->numFiles;
					for($i = 0; $i < $zip->numFiles; $i++) {
						dcmsndFucntion($zip->getNameIndex($i), $zip->getNameIndex($i), $studyArray);
						unlink($zip->getNameIndex($i));
					}
					$zip->close();	
				} else {
				echo '<div class="alert alert-warning" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>File you have uploaded is not a zip file but has zip extension</div>';
				}
			} else {
					dcmsndFucntion($_FILES['file']['tmp_name'][$key], $_FILES['file']['name'][$key], $studyArray);
			}
		}
		else {
			echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error in uploading the file to server '.$_FILES['file']['name'][$key].'</div>';
			switch($_FILES['file']['error']) {
				case 1:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-1: The uploaded file exceeds the maximum file size of 50 MB</div>';
				case 2:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-2: The uploaded file exceeds the maximum file size of 50 MB</div>';
				case 3:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-3: The uploaded file was only partially uploaded</div>';
				case 4:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-4: No file was uploaded</div>';
				case 5:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-5: Contact for Help</div>';
				case 6:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-6: Contact for Help</div>';
				case 7:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-7: Contact for Help</div>';
				case 8:
					echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Error Code-8: Contact for Help</div>';
			}
		}
	}
	echo '<script language="javascript">';
	echo 'document.getElementById("progresss").className = "progress progress-striped"';
	echo '</script>';
	echo '<script language="javascript">';
	echo 'document.getElementById("progresss").setAttribute("style","display:none")';
	echo '</script>';
	/*echo '<script language="javascript">';
	echo 'document.getElementById("barr").setAttribute("style","width:0%")';
	echo '</script>';*/
	ob_flush();
	ob_end_flush();
	$conn = mysql_connect('localhost:3306','root','root');
	if(! $conn )
	{
	  die('Could not connect: ' . mysql_error());
	}
	foreach($studyArray as $value) {
		if(!empty($value)) {
			$sql = 'SELECT pk FROM study WHERE study_iuid="'.$value.'"';
			mysql_select_db('pacsdb');
			$retval = mysql_query( $sql, $conn );
			$row = mysql_fetch_array($retval, MYSQL_ASSOC);
			$pacsid = $row['pk'];
			$ppid = getIdByPatientID($_SESSION['patientID']);
			$cpid = getIdByGateway($_SESSION['gateway']);
			$sql = 'SELECT id FROM client_patient_table WHERE fk_client="'.$cpid.'" AND fk_patient="'.$ppid.'"';
			mysql_select_db('teleraddb');
			$retval = mysql_query( $sql, $conn );
			$row = mysql_fetch_array($retval, MYSQL_ASSOC);
			$cppid = $row['id'];
			$sql = 'SELECT id FROM study_table WHERE fk_client_patient="'.$cppid.'" AND fk_study="'.$pacsid.'"';
			mysql_select_db('teleraddb');
			$retval = mysql_query( $sql, $conn );
			$row1 = mysql_fetch_array($retval, MYSQL_ASSOC);
			if(empty($row1)) {
				$sql1 = "INSERT INTO study_table (fk_client_patient, fk_study) VALUES ('".$cppid."','".$pacsid."')";
				mysql_query( $sql1, $conn );
			}
		}
	}
	mysql_close($conn);
    /*echo '<script language="javascript">';
	echo 'document.getElementById("informationdiv").innerHTML="<a href="#" class="close" data-dismiss="alert">&times;</a><p id="information">'.$count.'/'.$total.' images(s) processed.</p>"';
	echo '</script>';*/
	if($count == $total && $total != 0) {
		echo '<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Well done! You have successfully uploaded all the files</div>';
	} else {
		echo '<div class="alert alert-warning" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Warning! Only '.$count.' out of '.$total.' completed</div>';
	}
}
?>