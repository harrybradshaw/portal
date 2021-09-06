<?php

$username = $password = "";
$count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  echo('post');
  $username = strtolower(test_input($_POST["uname"]));
  $password = test_input($_POST["psw"]);
  $personal = $_POST['personal'];

  include 'db_connect.php';
  $stmt = $conn_users->prepare("SELECT passw FROM USER_LIST WHERE LOWER(usern) = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if($result->num_rows === 0) {
      header("location:unt.php?err=1");
  }else{
      while($value = $result->fetch_assoc()){
            if(password_verify($password,$value['passw'])){
                  $cookie_name = "DMGuser";
                  if($personal){
                  setcookie($cookie_name, $username, time() + (60*60*24*7), "/");
                  setcookie('DMGlogintime', time() + (60*60*24*7), time() + (60*60*24*7), "/");
                  }else{
                  setcookie($cookie_name, $username, time() + (60*60*1), "/");
                  setcookie('DMGlogintime', time() + (60*60*1), time() + (60*60*1), "/");
                  }
                  
                  if(strlen($_POST['ref'])== 0){
                    header("location:main.php");
                  }else{
                    header("location:".$_POST['ref']);
                  }
                  
            }else{
                header("location:unt.php?err=2");
            }
      }
  }
$stmt->close();
}else{
  echo 'hi';
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>