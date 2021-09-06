<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<title>DMG Portal - Create Equipment</title>


</head>

<body>
<?php

session_start();
$_SESSION['ref'] = "createequip.php";
include 'topbar.php';

$username = $_COOKIE[DMGuser];
include 'db_connect.php';
$result = $conn_users->query("SELECT role FROM USER_LIST WHERE usern = '".$username."'");

foreach($result as $value){
    if($value['role'] != 'admin'){
         header("location:main.php");
    }
}

?>
<div class="bulk">
<h2> New Equipment Form </h2>

<form method="post" action="newequipscript.php" id="stdform">
 Equipment First Name:
  <input type="text" name="New_name" required>
  <br><br>
  Equipment Second Name:
  <input type="text" name="New_sec_name">
  <br><br>
  Equipment Abrev:
  <input type="text" name="New_abrev" required>
  <br>
  (The equipment abbreviation cannot contain special characters!)
  <br><br>
  Bookable?
  <input type="checkbox" name="bookable" checked>
  <br><br>
  <input type="submit" value="Submit">

</form>
</div>
</body>
</html>
