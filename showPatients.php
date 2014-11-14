<table id="patientTable">
<thead>
<tr>
<th>Patients Name</th>
<th>Date Of Birth</th>
<th>Gender</th>
<th>Edit Patient</th>
<th>View</th>
</tr>
</thead>
<tbody>
<?php 
// include('session.php');
include('functions.php');
echo '<a href="logout.php" id="registerLink" class="btn btn-primary  pull-right">Log out</a>';
echo '<h1>'.getNameByGateway($_SESSION['gateway']).'</h1>';
echo '<h1>'.$_SESSION['gateway'].'</h1>';
echo '<br>';
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
		echo '<td id = "name'.$row1->id.'">'.$row1->name.'</td>';
		echo '<td id = "dob'.$row1->id.'">'.$row1->dob.'</td>';
		$gender = $row1->gender==1 ? "Male":"Female";
		echo '<td>'.$gender.'</td>';
		echo '<td id = "cmnt'.$row1->id.'"><button id = "'.$row1->id.'" class="open-MyModal btn btn-primary" data-toggle="modal" data-name="'.$row1->name.'" data-add1="'.$row1->address1.'" data-add2="'.$row1->address2.'" data-dob="'.$row1->dob.'" data-row-id="'.$row1->id.'" data-target="#myModal">Edit</button></td>';
		echo '<td><form action="upload.php" method="POST"><input type="hidden" name="patientID" value="'.$row1->id.'"/><button class="btn btn-primary">Studies</button></form></td>';
		echo '</tr>';
	}
}
?>
</tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button id="closeIconEdit" type="button" class="btn btn-default close " 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabelEdit">
               Patient Details
            </h4>
         </div>
         <div class="modal-body">
            <form id="form-search" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<input type="hidden" name="pkid" id="pkID"></input>
			<table>
			<tbody>
			<div class="form-group">
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="editName">Patient's Name</label></td>
			<td><input class="form-control" id="editName" type="text" name="editName" required/></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="editDOB">Date of Birth</label></td>
			<td><input class="form-control" id="editDOB" type="text" name="editDOB" required/></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="editadd1">Address1</label></td>
			<td><input class="form-control" id="editadd1" type="text" name="editadd1"/></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="editadd2">Address2</label></td>
			<td><input class="form-control" id="editadd2" type="text" name="editadd2"/></td>
			</tr>
			</div>
			</tbody>
			</table>
            </form>
         </div>
         <div class="modal-footer">
            <button id="saveEdit" type="button" class="btn btn-primary" >
               Save
            </button>
         </div>
      </div><!-- /.modal-content onclick="return fun() -->
</div><!-- /.modal -->
</div>

<script type="text/javascript">
 $(document).on('click','#saveEdit',function(e) {
  // document.write($("form-search").serialize());
  var data = $("#form-search").serialize();
  var mess = document.getElementById("editName").value;
  var mess1 = document.getElementById("pkID").value;
  var mess2 = document.getElementById("editDOB").value;
  var mess3 = document.getElementById("editadd1").value;
  var mess4 = document.getElementById("editadd2").value;
  var str = "cmnt";
  var str1 = "name";
  var str2 = "dob";
  var mess11 = str.concat(mess1);
  var mess22 = str1.concat(mess1);
  var mess33 = str2.concat(mess1);
  var inht = "<button id = \"".concat(mess1,"\" class=\"open-MyModal btn btn-primary\" data-toggle=\"modal\" data-name=\"",mess,"\" data-add1=\"",mess3,"\" data-add2=\"",mess4,"\" data-dob=\"",mess2,"\" data-row-id=\"",mess1,"\"  data-target=\"#myModal\">Edit</button>");
  // var str1 = "	<button id = "'.$row['id'].'" class="open-MyModal btn btn-primary btn-xs pull-right" data-toggle="modal" data-id="'.$row['id'].'" data-target="#myModal">Edit</button>";
  $.ajax({
         data: data,
         type: "post",
         url: "editpatient.php",
         success: function(data){
			  // alert("Data Save: " + inht);
              document.getElementById("closeIconEdit").click();
			  // location.reload("true");
			  document.getElementById(mess11).innerHTML=inht;
			  document.getElementById(mess22).innerHTML=mess;
			  document.getElementById(mess33).innerHTML=mess2;
         }
});
 });
</script>

<script type="text/javascript">
$('#myModal').on('show.bs.modal', function(e) {
	var comm = $(e.relatedTarget).data('name');
    $(e.currentTarget).find('input[id="editName"]').val(comm);
	var comm = $(e.relatedTarget).data('dob');
    $(e.currentTarget).find('input[id="editDOB"]').val(comm);
	var comm = $(e.relatedTarget).data('add1');
    $(e.currentTarget).find('input[id="editadd1"]').val(comm);
	var comm = $(e.relatedTarget).data('add2');
    $(e.currentTarget).find('input[id="editadd2"]').val(comm);
    var bookId = $(e.relatedTarget).data('row-id');
    $(e.currentTarget).find('input[id="pkID"]').val(bookId);
});
</script>

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
               Patient Registration
            </h4>
         </div>
         <div class="modal-body">
            <form id="patientRegisterForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<input type="hidden" name="formType" value="register"/>
			<table>
			<tbody>
			<div class="form-group">
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="patientName">Patient's Name</label></td>
			<td><input class="form-control" id="patientName" type="text" name="patientName" required/></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="patientdob">Date of Birth</label></td>
			<td><input class="form-control" id="patientdob" type="date" name="dob" required/></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="patientGender">Gender</label></td>
			<td><div class="input-group" id="patientGender">
                  <input type="radio" id="patientgender1" name="patientGender" value="1">Male</input>
                  <input type="radio" id="patientgender2" name="patientGender" value="2">Female</input>
                  <input type="radio" id="patientgender3" name="patientGender" value="3">Unspecified</input>
            </div></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="patientAddress1">Address1</label></td>
			<td><input class="form-control" id="patientAddress1" type="text" name="patientAddress1"/></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="patientAddress2">Address2</label></td>
			<td><input class="form-control" id="patientAddress2" type="text" name="patientAddress2"/></td>
			</tr>
			<tr>
			<td style="width:10%"></td>
			<td style="width:40%"><label for="patientPincode">Pincode</label></td>
			<td><input class="form-control" id="patientPincode" type="text" name="patientPincode" maxlength="6" required/></td>
			</tr>
			</div>
			</tbody>
			</table>
            </form>
         </div>
         <div class="modal-footer">
            <button id="patientRegister" type="button" class="btn btn-primary" >
               Register
            </button>
         </div>
      </div><!-- /.modal-content onclick="return fun() -->
</div>
</div><!-- /.modal -->

<script type="text/javascript">

 $(document).on('click','#patientRegister',function(e) {
  // document.write($("form-search").serialize());
  var data = $("#patientRegisterForm").serialize();
  var patientName = document.getElementById("patientName").value;
  var patientDOB = document.getElementById("patientdob").value;
  //alert("name: "+patientName);
  //alert("dob: "+patientDOB);
  if (document.getElementById('patientgender1').checked) {
	var patientGender = "Male";
  } else if (document.getElementById('patientgender2').checked) {
	var patientGender = "Female";
  } else if (document.getElementById('patientgender3').checked) {
	var patientGender = "Unspecified";
  }
  // alert("gender: "+patientGender);
  /*var patientGender = (document.getElementByName("patientGender").value==1)?"Male":"Female";
  alert("gender: "+patientGender);
  /*alert("name: "+patientName);
			  alert("dob: "+patientDOB);*/
			  
  var patientAddress1 = document.getElementById("patientAddress1").value;
  var patientAddress2 = document.getElementById("patientAddress2").value;
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
            // alert("Data Save: "+data);
            //alert("name: "+patientName);
            //alert("dob: "+patientDOB);
            // alert("address: "+patientAddress1);
            // document.getElementById("patientRegisterForm").reset();
            // document.getElementById("closeIcon").click();
            /*var patientTable = document.getElementById("patientTable");
            /*var row = patientTable.insertRow(-1);
            row.insertCell(0).innerHTML = patientName;
            row.insertCell(1).innerHTML = patientDOB;
            row.insertCell(2).innerHTML = patientGender;
            row.insertCell(3).innerHTML = "Edit";
            row.insertCell(4).innerHTML = "Studies";*/
            // location.reload("true");
            // document.getElementById(mess11).innerHTML=inht;
            var newRow = '<tr><td id="name'+data+'">'+patientName+'</td><td id = "dob'+data+'">'+patientDOB+'</td><td>'+patientGender+'</td><td id = "cmnt'+data+'"><button id = "'+data+'" class="open-MyModal btn btn-primary" data-toggle="modal" data-name="'+patientName+'" data-add1="'+patientAddress1+'" data-add2="'+patientAddress2+'" data-dob="'+patientDOB+'" data-row-id="'+data+'" data-target="#myModal">Edit</button></td><td><form action="upload.php" method="POST"><input type="hidden" name="patientID" value="'+data+'"/><button class="btn btn-primary">Studies</button></form></td></tr>';
            // var newRow1 = '<td id = "cmnt'+data+'"><button id = "'+data+'" class="open-MyModal btn btn-primary" data-toggle="modal" data-name="'+patientName+'" data-add1="'+patientaddress1+'" '; 
            // var newRow3 = 'data-add2="'+patientaddress2+'" data-dob="'+patientDOB+'" data-row-id="'+data+'" data-target="#myModal">Edit</button></td>';
            // var newRow2 = '<tr><td><form action="upload.php" method="POST"><input type="hidden" name="patientID" value="'+data+'"/><button class="btn btn-primary">Studies</button></form></td></tr>';
            // alert("td : "+newRow);

            $('#patientTable').DataTable().row.add($(newRow)).draw();// "<tr><td id=\"name"+data+"\">"+patientName+"</td><td id = \"dob"+data+"\">"+patientDOB+"</td><td>"+patientGender+"</td><td id = \"cmnt"+data+"\"><button id = \""+data+"\" class=\"open-MyModal btn btn-primary\" data-toggle=\"modal\" data-name=\""+patientName+"\" data-add1=\""+patientaddress1+"\" data-add2=\""+patientaddress2+"\" data-dob=\""+patientDOB+"\" data-row-id=\""+data+"\" data-target=\"#myModal\">Edit</button></td><td><form action=\"upload.php\" method=\"POST\"><input type=\"hidden\" name=\"patientID\" value=\""+data+"\"/><button class=\"btn btn-primary\">Studies</button></form></td></tr>")).draw();
            //document.getElementById("closeIcon").click();
            /*var namevar = '<td id = "name'+data+'">'+patientName+'</td>';
            var dobvar = '<td id = "dob'+data+'">'+patientDOB+'</td>';
            var gendervar = '<td>'+patientGender+'</td>';
            var editvar = '<td id = "cmnt'+data+'"><button id = "'+data+'" class="open-MyModal btn btn-primary" data-toggle="modal" data-name="'+patientName+'" data-add1="'+patientAddress1+'" data-add2="'+patientAddress2+'" data-dob="'+patientDOB+'" data-row-id="'+data+'" data-target="#myModal">Edit</button></td>';
            var studiesvar = '<td><form action="upload.php" method="POST"><input type="hidden" name="patientID" value="'+data+'"/><button class="btn btn-primary">Studies</button></form></td>';
            $('#patientTable').DataTable().row.add([namevar,dobvar,gendervar,editvar,studiesvar]).draw();
            /*var namevar1 = 'hello', dobvar1 = 'satya', gendervar1='how', editvar1='are', studiesvar1='you'; 
            $('#patientTable').DataTable().fnAddData( [
            namevar1,
            dobvar1,
            gendervar1,
            editvar1,
            studiesvar1
            ] ).draw();*/
            document.getElementById("closeIcon").click();
            }
	});
});
</script>
<script type="text/javascript">
 $(document).ready(function() {
    var t = $('#patientTable').DataTable();
} );
 </script>
