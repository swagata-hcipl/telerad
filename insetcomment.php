<?php 
if(isset($_REQUEST))
{
    mysql_connect("localhost","root","root");
	mysql_select_db("teleraddb");
	error_reporting(E_ALL && ~E_NOTICE);

	$email=$_POST['email'];
	$rowId=intval($_POST['pkid']);
	$sql="UPDATE study_table SET comment ='$email' WHERE id='$rowId'";
	$result=mysql_query($sql);
	/*if($result){
	echo "You have been successfully subscribed.";
	}*/
	echo 'successful';
}
?>