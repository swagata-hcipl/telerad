<?php 
if(isset($_REQUEST))
{
    mysql_connect("localhost","root","root");
	mysql_select_db("teleraddb");
	error_reporting(E_ALL && ~E_NOTICE);

	$email=$_POST['editName'];
	$dob=$_POST['editDOB'];
	$add1=$_POST['editadd1'];
	$add2=$_POST['editadd2'];
	$rowId=intval($_POST['pkid']);
	$sql="UPDATE patient_table SET name ='$email', dob='$dob', address1='$add1', address2='$add2' WHERE id='$rowId'";
	$result=mysql_query($sql);
	/*if($result){
	echo "You have been successfully subscribed.";
	}*/
}
?>