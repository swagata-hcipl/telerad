<!DOCTYPE html>
<?php
echo $_POST['bday'];
?>
<html>
<body>

<p>
Depending on browser support:<br>
A date picker can pop-up when you enter the input field.
</p>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
  Birthday:
  <input type="date" name="bday">
  <input type="submit" value="Send">
</form>

<p><b>Note:</b> type="date" is not supported in Internet Explorer.</p>

</body>
</html>