<!DOCTYPE html>

<html>
<body>

<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//echo $_SERVER["REQUEST_METHOD"];

$flag = False;
$New_username = test_input($_POST["New_username"]);
$password = test_input($_POST["New_password"]);
$New_firstname = test_input($_POST["n_first"]);
$New_surname = test_input($_POST["n_surn"]);
$New_email = test_input($_POST["n_email"]);
$New_password = password_hash($password, PASSWORD_DEFAULT);

//echo $New_username.'\n';

include 'db_connect.php';

$sql = 'SELECT * FROM USER_LIST';
$result = $conn_users->query($sql);

foreach($result as $value){
    if ($value['usern'] == $New_username){
        $flag = True;
    }
}

if ($flag == True){
    echo "Username taken!";
}elseif(strlen($New_username) < 1){
    echo "Username too short!";
}else{
    if($_POST['approval']=='Yes'){
        echo "Making User";
        $stmt = $conn_users->prepare("INSERT INTO USER_LIST (usern,passw,role,firstname,surname,email) VALUES (?,?,'user',?,?,?)");
        $stmt->bind_param("sssss",$New_username,$New_password,$New_firstname,$New_surname,$New_email);
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->close();
        $New_username = $conn_users->real_escape_string($New_username);
        $conn_users->query("CREATE TABLE ".$New_username." (Equipment_Name varchar(255),User_Level varchar(255))");
        header("location:admintools.php");  
    }else{
        $sql_b = "SELECT MAX(reqID) FROM ActionItems";
        $result_b = $conn_equipt->query($sql_b);
        foreach($result_b as $value_b){
            $rows = $value_b['MAX(reqID)'] + 1;
        }
        
        //echo $rows;
        $add = 'first='.$New_firstname.',last='.$New_surname.',email='.$New_email.',pass='.$New_password;
        $sql = "INSERT INTO ActionItems (actiontype,whoto,whofrom,additional,reqID) VALUES ('usreq','admin','".$New_username."','".$add."',".$rows.")";
        //echo $sql;
        $conn_equipt->query($sql);
        header("location:main.php");  
    }
    
}

echo '<br>';
echo '<br>';
if($_POST['approval']=='Yes'){ 
    echo '<a href="createuser.php"> Back</a>';
}else{
    echo '<a href="ac_req.php"> Back</a>';
}

?>


</body>
</html>

