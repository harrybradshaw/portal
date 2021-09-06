<?php

include 'db_connect.php';

$del_user = $_POST['del_user'];

//Find all the pieces of equipment the user is registered to use.
$sql = 'SELECT Equipment_Name FROM '.$del_user;
$result = $conn_users->query($sql);
echo $sql.'<br>';

foreach($result as $value){
	//Delete user from each table.
	$sql_eq = 'DELETE FROM '.$value['Equipment_Name'].' WHERE Name = "'.$del_user.'"';
	echo $sql_eq.'<br>';
	$conn_equipt->query($sql_eq);
}

//Now delete user from main user table and drop individual table. 

$sql_us = 'DELETE FROM USER_LIST WHERE usern = "'.$del_user.'"';
$conn_users->query($sql_us);
echo $sql_us.'<br>';
$sql_us = 'DROP TABLE '.$del_user;
$conn_users->query($sql_us);
echo $sql_us.'<br>';

header("location:allusers.php");
?>