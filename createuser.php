<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<style>
/* form {border: 3px solid #f1f1f1;} */

button {
  background-color: #bc8ad4;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 50%;
}

button:hover {
  opacity: 0.8;
}

.container {
  padding: 16px;
}

input[type=text], input[type=password] {
  width: 50%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

</style>
<title>DMG Portal - Create User</title>
</head>

<body>

<?php
include 'topbar.php';

$username = $_COOKIE[DMGuser];
session_start();
$_SESSION['ref'] = "createuser.php";
include 'db_connect.php';

$result = $conn_users->query("SELECT role FROM USER_LIST WHERE usern = '".$username."'");

foreach($result as $value){
    if($value['role'] != 'admin'){
         header("location:main.php");
    }
}

?>

<div class="bulk">
<h2> New User Form </h2>

<form method="post" action="newuserscript.php" id="loginform">
<div class="container">
  <label for="New_username">Username: </label><br>
  <input type="text" name="New_username" required>
  <br>
  <label for="New_password">Password: </label><br>
  <input type="password" id = "New_password" name="New_password" required>
  <br>
  
  First Name:<br>
  <input type="text" name="n_first" required>
  <br>
  Surname:<br>
  <input type="text" name="n_surn" required>
  <br>
  Email Address:<br>
  <input type="text" name="n_email" required>
  <br><br>
  <button type="submit" value="Submit">Add User</button>
</div>
<input type='hidden' name='approval' value='Yes'>

</form>
</div>
</body>
</html>