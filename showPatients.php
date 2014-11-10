<table id="patientTable">
<thead>
<tr>
<td>Patients Name</td>
<td>Date Of Birth</td>
<td>Gender</td>
<td></td>
<td></td>
</tr>
</thead>
<tbody>
<?php 
include('functions.php');
$conn = new mysqli("localhost","root","root","teleraddb");
$clientID = getIdByGateway($_SESSION['gateway']);
$sql = "SELECT fk_patient FROM client_patient_table WHERE fk_client='$clientID'";
$result = $conn->query($sql);
while($row = $result->fetch_object())
{
	$pid = $row->fk_patient;
	$result1 = getEverythingByPid($pid);
	while($row1 = $result1->fetch_object()) {
		echo '<tr>';
		echo '<td>'.$row1->name.'</td>';
		echo '<td>'.$row1->dob.'</td>';
		$gender = $row1->gender==1 ? "Male":"Female";
		echo '<td>'.$gender.'</td>';
		echo '<td>Edit</td>';
		echo '<td>Studies</td>';
		echo '</tr>';
	}
}
?>
</tbody>
</table>
<button id = "newPatient" class="open-patientRegisterModal btn btn-primary btn-lg" data-toggle="modal" data-target="#patientRegisterModal">New Patient..</button>
<!-- Modal -->
<div class="modal fade" id="patientRegisterModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button id="closeIcon" type="button" class="btn btn-default close " 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               Register New Patient
            </h4>
         </div>
         <div class="modal-body">
            <form id="patientRegisterForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<input type="hidden" name="formType" value="register"/>
			<table>
			<tbody>
			<div class="form-group">
			<tr>
			<td><label for="patientName">Name of the Patient</label></td>
			<td><input id="patientName" type="text" name="patientName" required/></td>
			</tr>
			<tr>
			<td><label for="dob">Date of Birth</label></td>
			<td><input id="dob" type="text" name="dob" required/></td>
			</tr>
			<tr>
			<td><label for="patientGender">Gender</label></td>
			<td><input id="patientGender" type="text" name="patientGender" required/></td>
			</tr>
			<tr>
			<td><label for="patientAddress1">Address1</label></td>
			<td><input id="patientAddress1" type="text" name="patientAddress1"/></td>
			</tr>
			<tr>
			<td><label for="patientAddress2">Address2</label></td>
			<td><input id="patientAddress2" type="text" name="patientAddress2"/></td>
			</tr>
			</div>
			<div class="form-group">
			<tr>
			<td><label for="patientUsername">Patient Username</label></td>
			<td><input id="patientUsername" type="text" name="patientUsername"/></td>
			</tr>
			</div>
			</tbody>
			</table>
			<!-- <button type="Submit" class="btn btn-primary">Register</button> -->
            </form>
         </div>
         <div class="modal-footer">
            <button id="patientRegister" type="button" class="btn btn-primary" >
               Register
            </button>
			<!-- <button id='closeButtton' type="button" class="btn btn-default" 
               data-dismiss="modal">Cancel
            </button> -->
         </div>
      </div><!-- /.modal-content onclick="return fun() -->
</div><!-- /.modal -->

<script type="text/javascript">
 $(document).on('click','#patientRegister',function(e) {
  // document.write($("form-search").serialize());
  var data = $("#patientRegisterForm").serialize();
  var patientName = document.getElementById("patientName").value;
  var patientDOB = document.getElementById("dob").value;
  var patientGender = (document.getElementById("patientGender").value==1)?"Male":"Female";
  // var mess1 = document.getElementById("pkID").value;
  // var str = "cmnt";
  // var mess11 = str.concat(mess1);
  // var inht = "<button id = \"".concat(mess1,"\" class=\"open-MyModal btn btn-primary btn-sm\" data-toggle=\"modal\" data-comment=\"",mess,"\" data-row-id=\"",mess1,"\"  data-target=\"#myModal\">Comments</button>");
  // var str1 = "	<button id = "'.$row['id'].'" class="open-MyModal btn btn-primary btn-xs pull-right" data-toggle="modal" data-id="'.$row['id'].'" data-target="#myModal">Edit</button>";
  $.ajax({
         data: data,
         type: "post",
         url: "patientRegister.php",
         success: function(data){
			  alert("Data Save: ");
			  // document.getElementById("patientRegisterForm").reset();
              // document.getElementById("closeIcon").click();
			  /*var patientTable = document.getElementById("patientTable");
			  var row = patientTable.insertRow(-1);
			  row.insertCell(0).innerHTML = patientName;
			  row.insertCell(1).innerHTML = patientDOB;
			  row.insertCell(2).innerHTML = patientGender;
			  row.insertCell(3).innerHTML = "Edit";
			  row.insertCell(4).innerHTML = "Studies";*/
			  // location.reload("true");
			  // document.getElementById(mess11).innerHTML=inht;
			  
         }
});
 });
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#patientTable').DataTable();
} );
</script>

 
