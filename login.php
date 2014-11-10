<!DOCTYPE html>
<html>
<head>
<title>Telerad Login</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js" ></script>
<script src="js/jquery-1.11.1.min.js" ></script>
</head>
<body>
<div class="container">
<div class="row">
<br>
<div class="col-sm-12 col-md-12 col-lg-12">
<h2> Login Here </h2>
<form id="loginForm" action="dxCentreHomepage.php" method="POST" role="form">
<div class="form-group">
<input type="hidden" name="formType" value="login">
<label for="username">Center Username</label>
<input id="username" type="text" name="username" required>
<label for="password">Password</label>
<input id="password" type="password" name="password" required>
</div>
<button type="Submit" class="btn btn-primary">Log-in</button>
</form>
<h2> Register Here </h2>

<form id="registerForm" action="dxCentreHomepage.php" method="POST">
<input type="hidden" name="formType" value="register"/>
<table cellspacing="10">
<tbody>
<div class="form-group">
<tr>
<td><label for="centerName">Name of Diagnostic Center</label></td>
<td><input id="centerName" type="text" name="centerName" required/></td>
</tr>
<tr>
<td><label for="centerType">Center Type</label></td>
<td><input id="centerType" type="text" name="centerType" required/></td>
</tr>
<tr>
<td><label for="centerAddress1">Address1</label></td>
<td><input id="centerAddress1" type="text" name="centerAddress1"/></td>
</tr>
<tr>
<td><label for="centerAddress2">Address2</label></td>
<td><input id="centerAddress2" type="text" name="centerAddress2"/></td>
</tr>
</div>
<div class="form-group">
<tr>
<td><label for="centerUsername">Center Username</label></td>
<td><input id="centerUsername" type="text" name="centerUsername"/></td>
</tr>
<tr>
<td><label for="centerPassword">Password</label></td>
<td><input id="centerPassword" type="password" name="centerPassword"/></td>
</tr>
<tr>
<td><label for="centerPassword1">Re-type Password</label></td>
<td><input id="centerPassword1" type="password" name="centerPassword1"/></td>
</tr>
</div>
</tbody>
</table>
<button type="Submit" class="btn btn-primary">Register</button>
</form>
<?php

?>
</div>
</div>
</div>
</body>
</html>