<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$username = test_input($_POST["usersl"]);
$equipment = test_input($_POST["eq"]);
$refpage = $_POST['refpage'];
$admin = $_POST['admin'];
//echo $username;
//echo $equipment;

$flag = False;

include 'db_connect.php';
$result = mysqli_query($conn_equipt,"SELECT * FROM ".$equipment);

foreach($result as $value){
    echo $value['usern'];
    if ($value['Name'] == $username){
        $flag = True;
    }
}

if ($flag == True){
    echo "Already user <br>";

    if($admin == 'y'){
        echo '"redirectadmin"';
        header("location:adminuserlist.php");
    }else{
        echo '"redirectnorm"';
        header("location:manageuserlist.php");
    }
}else{

    $sql = "INSERT INTO ".$equipment." (Name,Level) VALUES ('".$username."','Trainee')";
    echo $sql;
    if ($conn_equipt->query($sql) === TRUE) {
        echo "New record created successfully";

    } else {
        echo "Error: " . $sql . "<br>" . $conn_equipt->error;
    }

    $sql = "INSERT INTO ".$username." (Equipment_Name,User_Level) VALUES ('".$equipment."','Trainee')";
    if ($conn_users->query($sql) === TRUE) {
            echo "New record created successfully";

        } else {
            echo "Error: " . $sql . "<br>" . $conn_users->error;
        }

    
    header("location:".$refpage);


}

?>