
<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//echo $_SERVER["REQUEST_METHOD"];

$NewEmail = test_input($_POST["email"]);
$First = test_input($_POST['first']);
$Sur = test_input($_POST['sur']);
$username = $_COOKIE[DMGuser];


include 'db_connect.php';

if(strlen($NewEmail) > 0){
	$stmt = $conn_users->prepare("UPDATE USER_LIST SET email = ? WHERE usern = ?");
	$stmt->bind_param("ss",$NewEmail,$username);
	$stmt->execute();
	
	//$sql = "UPDATE USER_LIST SET email = '".$NewEmail."' WHERE usern = '".$username."'";
	//$conn_users->query($sql);	
}
if(strlen($First) > 0){
	$stmt = $conn_users->prepare("UPDATE USER_LIST SET firstname = ? WHERE usern = ?");
	$stmt->bind_param("ss",$First,$username);
	$stmt->execute();
	//$sql = "UPDATE USER_LIST SET firstname = '".$First."' WHERE usern = '".$username."'";
	//$conn_users->query($sql);	
}
if(strlen($Sur) > 0){
	$stmt = $conn_users->prepare("UPDATE USER_LIST SET surname = ? WHERE usern = ?");
	$stmt->bind_param("ss",$Sur,$username);
	$stmt->execute();
	//$sql = "UPDATE USER_LIST SET surname = '".$Sur."' WHERE usern = '".$username."'";
	//$conn_users->query($sql);	
}
header("location:changepass.php");
?>

