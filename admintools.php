<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "style.css">
<title>DMG Portal - Admin Tools</title>

</head>

<body>


<?php
session_start();
$_SESSION['ref'] = "admintools.php";
include 'topbar.php';
include 'db_connect.php';
$username = $_COOKIE[DMGuser];

$result = $conn_users->query("SELECT role FROM USER_LIST WHERE usern = '".$username."'");

foreach($result as $value){
    if($value['role'] == 'admin'){
        echo '<br>';
        echo '<a href="allequip.php" id="NormLink"> All Equipment Admin Pages</a><br>';
        echo '<a href="allusers.php" id="NormLink"> All Users</a><br>';
        echo '<a href="createuser.php" id="NormLink"> Create New User</a><br>';
        echo '<a href="createequip.php" id="NormLink"> Create New Equipment</a><br>';
        echo '<a href="view_usage.php" id="NormLink"> View Equipment Usage</a>';
    }
}

?>

<br>
<a href="changepass.php" id="NormLink"> Edit My Info</a><br>

</body>
</html>
