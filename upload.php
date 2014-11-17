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
<p id="counter" style='display:none' value='0'></p>
<br>
<div class = "container">
<div class = "row">
<div class = "col-md-12">
<?php
include('functions.php');
include('requestconfig.php');
include('fileupload.php');
?>
<br>
<div id="progressss" class="progress progress-striped active" style="display:none;"><div id="barr_upload" class="progress-bar progress-bar-success" 
	role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div></div>
<div class="row">
<div class="col-md-6">
<div class="panel panel-primary">
<div class="panel-heading">
<h2>Upload as Files/zip to Server</h2>
</div>
<div class="panel-body">
<form role="form" id="myFormUpload" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
<div class="form-group">
<input type="hidden" value="myFormUpload" name="<?php echo ini_get("session.upload_progress.name"); ?>">
<label for="inputfile"><h4>File(s) Input</h4></label>
<input type="file" name="file[]" id="inputfile" multiple>
</div>
<button type="submit" class="btn btn-primary" form="myFormUpload" value="Upload Files">Upload</button>
</form>
<script type="text/javascript" src="js/script.js"></script>
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
<button type="submit" class="btn btn-primary" form="myForm1" value="Upload Files">Upload</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<br>

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
$ppid = getIdByPatientID($_SESSION['patientID']);
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
			<textarea rows="4" style="box-sizing: border-box; width:100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box;" name="email" id="email" form="form-search">
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
			  // alert("Data Save: " + data);
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

