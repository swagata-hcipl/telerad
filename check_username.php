<?php

if(isset($_POST["centerUsername"]))
{
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }

    $connecDB = mysqli_connect("localhost", "root", "root","teleraddb") or die('could not connect to database');
   
    //trim and lowercase username
    $username =  strtolower(trim($_POST["centerUsername"]));
   
    //sanitize username
    $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
   
    //check username in db
    $results = mysqli_query($connecDB,"SELECT id FROM client_table WHERE gateway='$username'");
   
    //return total count
    $username_exist = mysqli_num_rows($results); //total records
   
    //if value is more than 0, username is not available
    if($username_exist) {
        echo '<img src="./img/not-available.png"/>';
    }else{
        echo '<img src="./img/available.png"/>';
    }
    //close db connection
    mysqli_close($connecDB);
}

?>