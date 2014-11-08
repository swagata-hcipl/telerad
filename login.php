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
<div class="col-lg-12">
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
<form id="registerForm" action="<?php  ?>" method="POST">
<div class="form-group">
<input type="hidden" name="formType" value="register">
<label for="centerName">Name of Diagnostic Center</label>
<input id="centerName" type="text" name="centerName" required/> <br>
<label for="centerType">Password</label>
<input id="centerType" type="text" name="centerType" required/> <br>
<label for="centerAddress1">Address1</label>
<input id="centerAddress1" type="text" name="centerAddress1"/> <br>
<label for="centerAddress2">Address2</label>
<input id="centerAddress2" type="text" name="centerAddress2"/> <br>
</div>
<div class="form-group">
<label for="centerUsername">Center Username</label>
<input id="centerUsername" type="text" name="centerUsername"/> <br>
<label for="centerAddress1">Password</label>
<input id="centerAddress1" type="text" name="centerAddress1"/> <br>
<label for="centerAddress1">Re-type Password</label>
<input id="centerAddress1" type="text" name="centerAddress1"/> <br>
</div>
<button type="Submit" class="btn btn-primary">Register</button>
</form>
<?php

?>
</div>
</div>
</div>
</body>
</html>