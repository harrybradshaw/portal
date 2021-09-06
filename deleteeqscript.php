<?php

include 'db_connect.php';

$del_eq = $_POST['del_eq'];

//Delete all calendar layers
$sql = "DELETE FROM webcal_user_layers WHERE cal_layeruser = '".$del_eq."'";
$conn_webcam->query($sql);
$sql = "DELETE FROM webcal_user_layers WHERE cal_login = '".$del_eq."'";
$conn_webcam->query($sql);

//Find all the users of equip.
$sql = 'SELECT Name FROM '.$del_eq;
$result = $conn_equipt->query($sql);

foreach($result as $value){
	//Delete eq from each table.
	$sql_eq = 'DELETE FROM '.$value['Name'].' WHERE Equipment_Name = "'.$del_eq.'"';
	echo $sql_eq.'<br>';
	$conn_users->query($sql_eq);
}

//Now delete equipment from main table and drop individual table. 
$sql_us = 'DELETE FROM EQ_LIST WHERE abbrev = "'.$del_eq.'"';
$conn_equipt->query($sql_us);
$sql_us = 'DROP TABLE '.$del_eq;
$conn_equipt->query($sql_us);
$conn_webcam->query("DELETE FROM webcal_user WHERE cal_login = '$del_eq'");

header("location:allequip.php");
?>