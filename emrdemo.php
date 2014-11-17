<!DOCTYPE HTML>
<html> 
<head> 
<title>Sign-In</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<title>Dicom Files Upload to PACS</title>
<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="./bootstrap/js/bootstrap.min.js"></script>

</head>
<body> 
<div class="container">
<div id="Sign-In"> 
<h1>LOG-IN HERE</h1>

UserName <br><input id="user" type="text" name="user" size="40" required/><br> 
Password <br><input type="password" name="pass" size="40" required/><br> 
<button id="button" onclick=myFunction()>submit</button>

</div> 
<br/>
<p id="view" style='display:none'>
</p>
<script type="text/javascript">
function myFunction() {
	document.getElementById("view").style.display="block";
	document.getElementById("user").disabled = true;
    var pid = document.getElementById("user").value;
    var pname = "Satyanarayana";
    // document.getElementById("view").innerHTML="";
	document.getElementById("view").innerHTML="<p>Successfully logged in</p><br><div id=\"View-dicom\" ><form action=\"upload.php\" method=\"POST\" target=\"_blank\"><input type=\"hidden\" name=\"gateway\" value=\"HIPL\"></input><input type=\"hidden\" name=\"pName\" value=\""+pname+"\"></input><input type=\"hidden\" name=\"pid\" value=\""+pid+"\"></input><input id=\"vb\" type=\"submit\" value=\"View/Upload Dicom Images\">  </input></form></div>";
}
</script>
</div>
</body> 
</html> 