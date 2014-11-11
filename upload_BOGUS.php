<!DOCTYPE html>
<?php
include('session.php');
// include('session2.php');
include('functions.php');
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
<p id="counter" style='display:none' value='0'></p>
<br>
<div class = "container">
<?php
echo $_SESSION['gateway'];

// session_start();
error_reporting(E_ERROR | E_PARSE);
$doc = new DOMDocument();
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
	$tagname->setAttribute('value',$temp_counter);
}
else if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['patientID'])) {
	$_SESSION['gateway'] = $_POST['gateway'];
	$_SESSION[0]['patientID'] = $_POST['patientID'];
	$_SESSION['counter'] = 0;
}
echo $_SESSION[$counter]['patientID'];

if(isset($_POST['patientID'])) {
$patient_table_id = $_POST['patientID'];
$conn = new mysqli("localhost","root","root","teleraddb");
	$sql = "SELECT patient_id FROM patient_table WHERE id='$patient_table_id'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	
$_SESSION[$counter]['patientID'] = $row->patient_id;
}

?>
<div class = "row">
<div class = "col-md-12">
<?php
$total=0;
$count=0;
$studyArray = array();
 
// This is PHP function to convert a user-supplied URL to just the domain name,
// which I use as the link text.
 
// Remember you still need to use htmlspecialchars() or similar to escape the
// result.
 
function url_to_domain($url)
{
    $host = @parse_url($url, PHP_URL_HOST);
 
    // If the URL can't be parsed, use the original URL
    // Change to "return false" if you don't want that
    if (!$host)
        $host = $url;
 
    // The "www." prefix isn't really needed if you're just using
    // this to display the domain to the user
    if (substr($host, 0, 4) == "www.")
        $host = substr($host, 4);
 
    // You might also want to limit the length if screen space is limited
    if (strlen($host) > 50)
        $host = substr($host, 0, 47) . '...';
 
    return $host;
}

function dcmsndFucntion($tmpkey,$key, $studyArray) {
	$cmd_str = 'dcmsnd DCM4CHEE@localhost:11112 ';
	$cmd_str .= $tmpkey;
	$output = array();
	exec($cmd_str,$output,$return);
	// echo $cmd_str.'<br>';
	// echo $return.'<br>';
	if(!$return) {
		GLOBAL $count;
		$count++;
		$objStr = substr($output[4],19);
		// echo $objStr."<br>";
		if(!in_array($objStr,$studyArray)) {
		GLOBAL $studyArray;
		$studyArray[] = $objStr;
		}
	}
	else {
		echo '<div class="alert alert-danger" role="alert">Error in uploading the file to PACS '.$key.'</div>';
	}
	GLOBAL $total;
	GLOBAL $count;
	$percent = intval(($count/$total * 50)+50)."%";
	echo '<script language="javascript">
	document.getElementById("information").innerHTML="'.$count.'/'.$total.' images(s) processed.";
	document.getElementById("barr").setAttribute("style","width:'.$percent.'");
	</script>';
	// This is for the buffer achieve the minimum size in order to flush data
	echo str_repeat(' ',1024*64);
	// Send output to browser immediately
	ob_flush();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['file'])) {
	echo '<div id="information" style="width"></div>';
	echo '<div id="progresss" class="progress progress-striped active"><div id="barr" class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:50%;"></div></div>';
	$start_time = microtime('true');
	
	$totalSize=0;
	
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
			echo 'Error in uploading the file to server '.$_FILES['file']['name'][$key];
			switch($_FILES['file']['error']) {
				case 1:
					echo '<div class="alert alert-danger" role="alert">Error Code-1: The uploaded file exceeds the maximum file size of 50 MB</div>';
				case 2:
					echo '<div class="alert alert-danger" role="alert">Error Code-2: The uploaded file exceeds the maximum file size of 50 MB</div>';
				case 3:
					echo '<div class="alert alert-danger" role="alert">Error Code-3: The uploaded file was only partially uploaded</div>';
				case 4:
					echo '<div class="alert alert-danger" role="alert">Error Code-4: No file was uploaded</div>';
				case 5:
					echo '<div class="alert alert-danger" role="alert">Error Code-5: Contact for Help</div>';
				case 6:
					echo '<div class="alert alert-danger" role="alert">Error Code-6: Contact for Help</div>';
				case 7:
					echo '<div class="alert alert-danger" role="alert">Error Code-7: Contact for Help</div>';
				case 8:
					echo '<div class="alert alert-danger" role="alert">Error Code-8: Contact for Help</div>';
			}
		}
	}
	echo '<script language="javascript">';
	echo 'document.getElementById("progresss").className = "progress progress-striped"';
	echo '</script>';
	echo '<script language="javascript">';
	echo 'document.getElementById("progresss").setAttribute("style","display:none")';
	echo '</script>';
	echo '<script language="javascript">';
	echo 'document.getElementById("barr").setAttribute("style","width:0%")';
	echo '</script>';
	ob_flush();
	ob_end_flush();
	$end_time = microtime('true');
	$upload_time = $end_time - $start_time;
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
			echo 'pacsid = '.$pacsid.'<br>';
			// echo 'counter = '.$counter.'<br>';
			$ppid = getIdByPatientID($_SESSION[$counter]['patientID']);
			echo 'ppid = '.$ppid.'<br>';
			echo $_SESSION['gateway'];
			$cpid = getIdByGateway($_SESSION['gateway']);
			echo 'cpid = '.$cpid.'<br>';
			$sql = 'SELECT id FROM client_patient_table WHERE fk_client="'.$cpid.'" AND fk_patient="'.$ppid.'"';
			mysql_select_db('teleraddb');
			$retval = mysql_query( $sql, $conn );
			$row = mysql_fetch_array($retval, MYSQL_ASSOC);
			$cppid = $row['id'];
			echo $cppid.'<br>';
			$sql = 'SELECT id FROM study_table WHERE fk_client_patient="'.$cppid.'" AND fk_study="'.$pacsid.'"';
			mysql_select_db('teleraddb');
			$retval = mysql_query( $sql, $conn );
			$row1 = mysql_fetch_array($retval, MYSQL_ASSOC);
			if(empty($row1)) {
				
				$sql1 = "INSERT INTO study_table (fk_client_patient, fk_study) VALUES ('".$cppid."','".$pacsid."')";
				echo $sql1;
				mysql_query( $sql1, $conn );
			}
		}
	}
	mysql_close($conn);
	if($count == $total) {
		//echo '<script language="javascript">document.getElementById("information").innerHTML="All files uploaded successfully"</script>';
		echo '<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Well done! You have successfully uploaded all the files</div>';
	} else {
		//echo '<script language="javascript">document.getElementById("information").innerHTML=""</script>';
		echo '<div class="alert alert-warning" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Warning! Only '.$count.' out of '.$total.' completed</div>';
	}
}
?>
<div id="progresss" class="progress progress-striped active" style="display:none;"><div id="barr" class="progress-bar progress-bar-success" 
	role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;";></div></div>
<div class="row">
<div class="col-md-6">
<div class="panel panel-primary">
<div class="panel-heading">
<h2>Upload as Files/zip to Server</h2>
</div>
<div class="panel-body">
<form role="form" id="myForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
<div class="form-group">
<input type="hidden" value="myForm" name="<?php echo ini_get("session.upload_progress.name"); ?>">
<label for="inputfile"><h4>File(s) Input</h4></label>
<input type="file" name="file[]" id="inputfile" multiple>
</div>
<button type="submit" class="btn btn-primary" value="Upload Files">Submit</button>
</form>
<script type="text/javascript" src="script.js"></script>
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-primary">
<div class="panel-heading">
<h2>Upload as Folder to PACS Server</h2>
</div>
<div class="panel-body">
<form role="form" id="myForm1" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
<div class="form-group">
<label for="inputfile1"><h4>Folder Input</h4></label>
<input type="file" name="file[]" id="inputfile1" multiple="" directory="" webkitdirectory="" mozdirectory="">
</div>
<button type="submit" class="btn btn-primary" value="Upload Files">Submit</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<br>
<?php
// echo 'counter = '.$counter.'<br>';
?>
<!-- Study Table Starts here -->
<?php
$conn = mysql_connect('localhost:3306','root','root');
	if(! $conn )
	{
	  die('Could not connect: ' . mysql_error());
	}
echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col-md-12">';
$ppid = getIdByPatientID($_SESSION[$counter]['patientID']);
$cpid = getIdByGateway($_SESSION['gateway']);
$sql = 'SELECT id FROM client_patient_table WHERE fk_client="'.$cpid.'" AND fk_patient="'.$ppid.'"';
mysql_select_db('teleraddb');
$retval = mysql_query( $sql, $conn );
$row = mysql_fetch_array($retval, MYSQL_ASSOC);
$cppid = $row['id'];
$sql = 'SELECT * FROM study_table WHERE fk_client_patient="'.$cppid.'"';
mysql_select_db('teleraddb');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
$onlyonce = 0;
$row = mysql_fetch_array($retval, MYSQL_ASSOC);
if(empty($row)) {
	echo '<div class="alert alert-info" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>You are a first time user</div>';
}
$rno = 0;
while($row)
{
	if($onlyonce==0) {
		// echo '<div class="panel panel-info">';
		// echo '<div class="panel-heading">';
		echo '<h2>DICOM  Studies</h2>';
		// echo '</div>';
		echo '<table id="studyTable" class="table table-striped table bordered display" cellspacing="0" width="100%">';
		echo '<thead>';
		echo '<tr>';
		// echo '<th><h4><strong>Patient name</strong></h4></th>';
		echo '<th>Study</th>';
		echo '<th>Modality</th>';
		echo '<th>Conducted On</th>';
		echo '<th>Uploaded On</th>';
		echo '<th>Comments</th>';
		echo '<th>View Study</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$onlyonce=1;
	}
	$sql1 = "SELECT * FROM study WHERE pk='".$row['fk_study']."'";
	mysql_select_db('pacsdb');
	$retval1 = mysql_query( $sql1, $conn );
	$row1 = mysql_fetch_array($retval1, MYSQL_ASSOC);
	$sql2 = "SELECT * FROM patient WHERE pk='".$row1['patient_fk']."'";
	mysql_select_db('pacsdb');
	$retval2 = mysql_query( $sql2, $conn );
	$row2 = mysql_fetch_array($retval2, MYSQL_ASSOC);
	echo '<tr>';
	// echo '<td>'.$row2['pat_name'].'</td>';
	echo '<td>'.$row1['study_desc'].'</td>';
	echo '<td>'.$row1['mods_in_study'].'</td>';
	echo '<td>'.$row1['study_datetime'].'</td>';
	echo '<td>'.$row1['updated_time'].'</td>';
	// echo '<td id = "cmnt'.$row['id'].'">'.$row['comments'].'<button id = "'.$row['id'].'" class="open-MyModal btn btn-primary btn-xs pull-right" data-toggle="modal" data-comment="'.$row['comments'].'" data-row-id="'.$row['id'].'" data-target="#myModal">Edit</button></td>';
	$study_url = 'http://'.$_SERVER['SERVER_NAME'].':8080/weasis/samples/applet.jsp?commands=%24dicom%3Aget%20-w%20http%3A//'.$_SERVER['SERVER_NAME'].'%3A8080/weasis-pacs-connector/manifest%3FstudyUID%3D'.$row1['study_iuid'];
	echo '<td id = "cmnt'.$row['id'].'"><button id = "'.$row['id'].'" class="open-MyModal btn btn-primary btn-sm" data-toggle="modal" data-comment="'.$row['comment'].'" data-row-id="'.$row['id'].'" data-target="#myModal">Comments</button></td>';
	echo '<td><a class="btn btn-info pull-right" target="_blank" href='.$study_url.'> View Study</a></td>';
	echo '</tr>';
	$rno++;
	$row = mysql_fetch_array($retval, MYSQL_ASSOC);
}
mysql_close($conn);
echo '</tbody>';
echo '</table>';
// echo '</div>';
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button id="closeIcon" type="button" class="btn btn-default close " 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               Comments
            </h4>
         </div>
         <div class="modal-body">
            <form id="form-search" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<input type="hidden" name="pkid" id="pkID"></input>
			<!-- <input name="email" type="text" id="email" style="width:100%"></input> -->
            </form>
			<textarea rows="4" cols="78" name="email" id="email" form="form-search">
			</textarea>
         </div>
         <div class="modal-footer">
            <button id="save" type="button" class="btn btn-primary" >
               Save
            </button>
			<!-- <button id='closeButtton' type="button" class="btn btn-default" 
               data-dismiss="modal">Cancel
            </button> -->
         </div>
      </div><!-- /.modal-content onclick="return fun() -->
</div><!-- /.modal -->
</div>
</div>
</div>
</div>

<script type="text/javascript">
 $(document).on('click','#save',function(e) {
  // document.write($("form-search").serialize());
  var data = $("#form-search").serialize();
  var mess = document.getElementById("email").value;
  var mess1 = document.getElementById("pkID").value;
  var str = "cmnt";
  var mess11 = str.concat(mess1);
  var inht = "<button id = \"".concat(mess1,"\" class=\"open-MyModal btn btn-primary btn-sm\" data-toggle=\"modal\" data-comment=\"",mess,"\" data-row-id=\"",mess1,"\"  data-target=\"#myModal\">Comments</button>");
  // var str1 = "	<button id = "'.$row['id'].'" class="open-MyModal btn btn-primary btn-xs pull-right" data-toggle="modal" data-id="'.$row['id'].'" data-target="#myModal">Edit</button>";
  $.ajax({
         data: data,
         type: "post",
         url: "insetcomment.php",
         success: function(data){
			  // alert("Data Save: " + inht);
              document.getElementById("closeIcon").click();
			  // location.reload("true");
			  document.getElementById(mess11).innerHTML=inht;
         }
});
 });
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#studyTable').DataTable();
} );
 </script>
 
<!--  <script type="text/javascript">
$(document).on("click", ".open-MyModal", function () {
     var myId = $(this).data('row-id');
     $(".modal-body #pkID").val( myId );
});
 </script> -->
 
<script type="text/javascript">
$('#myModal').on('show.bs.modal', function(e) {
	var comm = $(e.relatedTarget).data('comment');
    $(e.currentTarget).find('textarea[id="email"]').val(comm);
    var bookId = $(e.relatedTarget).data('row-id');
    $(e.currentTarget).find('input[id="pkID"]').val(bookId);
	/*var descId = "desc";
	descId = descId.concat(bookId);
	var desc = document.getElementById("descId").get
	document.getElementById("myModalLabel").innerHTML();*/
});
</script>

</body>
</html>

