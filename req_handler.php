<!DOCTYPE html>

<html>
<body>

<?php

include 'db_connect.php';
$reason = $_POST['reason'];
echo $reason;
if($reason == 'eqreq_can'){
    //cancel an old request
    $sql = "DELETE FROM ActionItems WHERE whoto = '".$_POST['req_whoto']."' AND whofrom = '".$_POST['req_whofrom']."'";
    //echo $sql;
    $conn_equipt->query($sql);
}elseif($reason == 'eqreq_req'){
    //create a new request
    $sql_b = "SELECT MAX(reqID) FROM ActionItems";
    $result_b = $conn_equipt->query($sql_b);
    foreach($result_b as $value_b){
        $rows = $value_b['MAX(reqID)'] + 1;
    }
    echo $rows;
    $sql = "INSERT INTO ActionItems (actiontype,whoto,whofrom,additional,reqID) VALUES ('eqreq','".$_POST['req_whoto']."','".$_POST['req_whofrom']."','',".$rows.")";
    //echo $sql;
    $conn_equipt->query($sql);
}elseif($reason == 'eqreq_apr'){
    //aprove/reject an old request
    if($_POST['eq_req_action'] == 'T'){
        $sql = "INSERT INTO ".$_POST['req_whoto']." (Name, Level) VALUES ('".$_POST['req_whofrom']."','Trainee')";
        $conn_equipt->query($sql);
        $sql = "INSERT INTO ".$_POST['req_whofrom']." (Equipment_Name, User_Level) VALUES ('".$_POST['req_whoto']."','Trainee')";
        $conn_users->query($sql);
    }elseif($_POST['eq_req_action'] == 'C'){
        $sql = "INSERT INTO ".$_POST['req_whoto']." (Name, Level) VALUES ('".$_POST['req_whofrom']."','C user')";
        $conn_equipt->query($sql);
        $sql = "INSERT INTO ".$_POST['req_whofrom']." (Equipment_Name, User_Level) VALUES ('".$_POST['req_whoto']."','C user')";
        $conn_users->query($sql);
    }elseif($_POST['eq_req_action'] == 'B'){
        $sql = "INSERT INTO ".$_POST['req_whoto']." (Name, Level) VALUES ('".$_POST['req_whofrom']."','B user')";
        $conn_equipt->query($sql);
        $sql = "INSERT INTO ".$_POST['req_whofrom']." (Equipment_Name, User_Level) VALUES ('".$_POST['req_whoto']."','B user')";
        $conn_users->query($sql);
    }elseif($_POST['eq_req_action'] == 'A'){
        $sql = "INSERT INTO ".$_POST['req_whoto']." (Name, Level) VALUES ('".$_POST['req_whofrom']."','superuser')";
        $conn_equipt->query($sql);
        $sql = "INSERT INTO ".$_POST['req_whofrom']." (Equipment_Name, User_Level) VALUES ('".$_POST['req_whoto']."','superuser')";
        $conn_users->query($sql);
    }elseif($_POST['eq_req_action'] == 'M'){
        $sql = "INSERT INTO ".$_POST['req_whoto']." (Name, Level) VALUES ('".$_POST['req_whofrom']."','Manager')";
        $conn_equipt->query($sql);
        $sql = "INSERT INTO ".$_POST['req_whofrom']." (Equipment_Name, User_Level) VALUES ('".$_POST['req_whoto']."','Manager')";
        $conn_users->query($sql);
    }
    //echo $sql;
    echo '<br>';
    $sql = "DELETE FROM ActionItems WHERE whoto = '".$_POST['req_whoto']."' AND whofrom = '".$_POST['req_whofrom']."'";
    //echo $sql;
    $conn_equipt->query($sql);
}elseif($reason == 'usreq'){
    if (isset($_POST['approve'])) {
        $sql = "SELECT * FROM ActionItems WHERE actiontype = 'usreq' AND reqID = '".$_POST['reqID']."'";
        $result = $conn_equipt->query($sql);
        foreach($result as $value){
            $New_username = $value['whofrom'];
            $info = explode(",",$value['additional']);
            $New_firstname = explode("=",$info[0])[1];
            $New_surname= explode("=",$info[1])[1];
            $New_email = explode("=",$info[2])[1];
            $New_password = explode("=",$info[3])[1];
            $stmt = $conn_users->prepare("INSERT INTO USER_LIST (usern,passw,role,firstname,surname,email) VALUES (?,?,'user',?,?,?)");
            $stmt->bind_param("sssss",$New_username,$New_password,$New_firstname,$New_surname,$New_email);
            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            $stmt->close();
            $New_username = $conn_users->real_escape_string($New_username);
            $conn_users->query("CREATE TABLE ".$New_username." (Equipment_Name varchar(255),User_Level varchar(255))");
        }
        $sql = "DELETE FROM ActionItems WHERE actiontype = 'usreq' AND reqID = '".$_POST['reqID']."'";
        //echo $sql;
        $conn_equipt->query($sql);
    }elseif(isset($_POST['reject'])){
        $sql = "DELETE FROM ActionItems WHERE actiontype = 'usreq' AND reqID = '".$_POST['reqID']."'";
        //echo $sql;
        $conn_equipt->query($sql);
    }
}



header("location:".$_POST['refpage']);

?>
</body>
</html>