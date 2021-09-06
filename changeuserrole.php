<?php

$user = $_POST["usern"];
$newrole = $_POST["newrole"];

include 'db_connect.php';

$sql = 'UPDATE USER_LIST SET role = "'.$newrole.'" WHERE usern = "'.$user.'"';
echo $sql;
$result = $conn_users->query($sql);
header("location:allusers.php");

?>
