
<?php

//echo $_SERVER["REQUEST_METHOD"];


$Status = $_POST['eqstat'];
$username = $_COOKIE[DMGuser];
$equipment = $_COOKIE[eqpage];
$refpage = $_POST['refpage'];
$rapath = $_POST['filesl'];


echo $rapath;

include 'db_connect.php';

$sql = "UPDATE EQ_LIST SET RA = 'uploads/".$rapath."' WHERE abbrev = '".$equipment."'";
echo $sql;
$conn_equipt->query($sql);

$sql = "UPDATE EQ_LIST SET RA_update = CURRENT_DATE() WHERE abbrev = '".$equipment."'";
$conn_equipt->query($sql);

echo $refpage;
header("location:".$refpage);


?>

