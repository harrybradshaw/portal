
<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//echo $_SERVER["REQUEST_METHOD"];

$OldP1 = test_input($_POST["OldP1"]);
$NewP1 = test_input($_POST["NewP1"]);
$NewP2 = test_input($_POST["NewP2"]);
$username = $_COOKIE[DMGuser];
$refpage = $_POST["refpage"];

include 'db_connect.php';

if($refpage == "allusers.php"){
$user = $_POST["reset_user"];
$New_password = password_hash($user, PASSWORD_DEFAULT);
$sql = "UPDATE USER_LIST SET passw = '".$New_password."' WHERE usern = '".$user."'";
$conn_users->query($sql);
header("location:allusers.php");

}else{
	if(strlen($OldP1) > 0){
		if(strlen($NewP1)>0){
		$result = $conn_users->query("SELECT passw FROM USER_LIST WHERE usern = '".$username."'");
		if($result->num_rows == 0) {
		  //header("location:unt.php");
		  echo 'conf';
		} else {
		  foreach($result as $value){
				if(password_verify($OldP1,$value['passw'])){
					  if($NewP1 == $NewP2){
						$New_password = password_hash($NewP1, PASSWORD_DEFAULT);
						$sql = "UPDATE USER_LIST SET passw = '".$New_password."' WHERE usern = '".$username."'";
						$conn_users->query($sql);
						echo 'Password changed';
						echo '<br>';
						echo '<a href="main.php"> Main Page</a>';

					  }else{
					  //header("location:main.php");
					  echo 'not changed: new didnt match';
					  echo '<br>';
					  echo '<a href="changepass.php"> Back</a>';
					  }

				}else{
					//header("location:unt.php");
					echo 'not changed: old didnt match';
					  echo '<br>';
					  echo '<a href="changepass.php"> Back</a>';
				}
		  }
		  }
		}
}else{
header("location:changepass.php");
}
}
?>

