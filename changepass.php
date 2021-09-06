<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<style>
/* form {border: 3px solid #f1f1f1;} */
</style>
<title>DMG Portal - Edit My Info</title>
</head>
<style>
input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}
button:hover {
  opacity: 0.8;
}

button {
  background-color: #bc8ad4;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}
</style>
<body>

<?php

session_start();
$_SESSION['ref'] = "changepass.php";
include 'topbar.php';
$username = $_COOKIE[DMGuser];
include 'db_connect.php';
$result = $conn_users->query("SELECT * FROM USER_LIST WHERE usern = '".$username."'");
?>
<br>
<div class="bulk">
<form method="post" action="changeuserinfo.php" id="stdform">
  <fieldset style="width:50%">
  <legend><b>Update Information</b></legend>
  <?php
  foreach($result as $value){
    echo '<label for="fname">First Name (Current: '.$value['firstname'].')</label></br>';
  	echo '<input type="text" id="fname" name="first"><br>';
	echo 'Second Name (Current: '.$value['surname'].'): <br>';
	echo '<input type="text" id="fname" name="sur"><br>';
	echo 'Email (Current: '.$value['email'].'): <br>';
	echo '<input type="text" name="email"><br>';
  }
  ?>

<button type="submit" value="Submit">Update</button>
</fieldset>
	</form>


<br>
  <form method="post" action="changepassscript.php" id="stdform">
  
  <fieldset style="width:50%">
  <legend><b>Change Password</b></legend>
  Old Password:<br>
  <input type="password" name="OldP1">
  <br>
  New Password:<br>
  <input type="password" name="NewP1" >
  <br>
  New Password Again:<br>
  <input type="password" name="NewP2" >
  <br>
  <button type="submit" value="Submit">Update</button>
  </fieldset>
</form>


</body>
</html>