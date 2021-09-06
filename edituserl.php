<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function run_q($c,$sq){
if ($c->query($sq) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sq . "<br>" . $c->error;
}

}

$newlev = test_input($_POST["newlev"]);
$equipment = test_input($_POST["eq"]);
$user = test_input($_POST["user"]);
$admin = $_POST['admin'];

echo $newlev;
echo $equipment;
echo $user;
echo $admin;

include 'db_connect.php';

if($newlev == 'M'){
$level = 'Manager';
}elseif($newlev=='A'){
$level = 'superuser';
}elseif($newlev=='B'){
$level = 'B user';
}elseif($newlev=='C'){
$level = 'C user';
}elseif($newlev=='T'){
$level = 'Trainee';
}elseif($newlev=='REM'){
    $sql = "DELETE FROM ".$equipment." WHERE Name = '".$user."'";
    echo $sql;
    $level = 'Removed';
    //header("location:manageuserlist.php");
    run_q($conn_equipt,$sql);
    $sql = "DELETE FROM ".$user." WHERE Equipment_Name = '".$equipment."'";
    run_q($conn_users,$sql);

    
    header("location:adminuserlist.php");


}

if($newlev != 'REM'){
    $sql = "UPDATE ".$equipment." SET Level = '".$level."' WHERE Name = '".$user."'";
    echo $sql;
    run_q($conn_equipt,$sql);
    $sql = "UPDATE ".$user." SET User_Level = '".$level."' WHERE Equipment_Name = '".$equipment."'";
    run_q($conn_users,$sql);

    
    header("location:adminuserlist.php");
    
}

//header("location:manageuserlist.php");
?>