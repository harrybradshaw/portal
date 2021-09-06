<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<title>DMG Portal - Action Items</title>

</head>

<body>

<?php
session_start();
$_SESSION['ref'] = "main.php";
$user = $_COOKIE[DMGuser];
include 'topbar.php';
include 'db_connect.php';
echo '<div class="bulk">';
//Find out if user is site admin
$isadmin = FALSE;
$sql = "SELECT usern FROM USER_LIST WHERE role = 'admin'";
$result = $conn_users->query($sql);
foreach($result as $value){
    if($value['usern'] == $user){
        $isadmin = TRUE;
    }
}



//Equipment Access Requests
echo '<h3>Equipment Access Requests</h3>';
$sql = "SELECT * FROM ActionItems WHERE actiontype = 'eqreq'";
$result = $conn_equipt->query($sql);
$eqreq_tot = mysqli_num_rows($result);
$req_num = 0;
foreach($result as $value){
    $eq = $value['whoto'];
    $sql_a = "SELECT Name FROM ".$eq." WHERE Level = 'Manager'";
    $result_a = $conn_equipt->query($sql_a);
    foreach($result_a as $value_a){
        if($value_a['Name'] == $user){
            //Check that request is still valid, that hasn't been processed elsewhere. 
            $sql_b = "SELECT * FROM ".$eq." WHERE Name = '".$value['whofrom']."'";
            $result_b = $conn_equipt->query($sql_b);
            $rows_b = mysqli_num_rows($result_b);
            if($rows_b > 0){
                //Already added
                $sql_c = "DELETE FROM ActionItems WHERE whoto = '".$value['whoto']."' AND whofrom = '".$value['whofrom']."'";
                //echo $sql_c;
                $conn_equipt->query($sql_c);
            }else{
                //Then action item should be shown to user
                if($req_num == 0){
                    echo "<table style='width:40%'>";
                }
                $req_num += 1;
                echo '<tr>';
                echo '<td>'.$value['whofrom'].'</td>';
                $sql_b = "SELECT fullname FROM EQ_LIST WHERE abbrev = '".$eq."'";
                $result_b = $conn_equipt->query($sql_b);
                foreach($result_b as $value_b){
                    $eq_fullname = $value_b['fullname'];
                }
                echo '<td>'.$eq_fullname.'</td>';

                echo '<form method="post" action="req_handler.php">';
                echo '<td>';
                echo '<input type="hidden" name="reqID" value="'.$value['reqID'].'"/>';
                echo '<input type="hidden" name="reqtype" value="eqreq"/>';
                echo '<input type="hidden" name="req_whoto" value="'.$eq.'"/>';
                echo '<input type="hidden" name="req_whofrom" value="'.$value['whofrom'].'"/>';
                echo '<input type="hidden" name="reason" value="eqreq_apr"/>';
                echo '<input type="hidden" name="refpage" value="notifications.php"/>';
                echo '<select name="eq_req_action">';
                echo '<option value="T">Approve as Trainee</option>';
                echo '<option value="C">Approve as C user</option>';
                echo '<option value="B">Approve as B user</option>';
                echo '<option value="A">Approve as A user</option>';
                echo '<option value="M">Approve as Manager</option>';
                echo '<option value="R">Reject request</option>';
                echo '</select>';
                echo '</td>';
                echo '<td>';
                echo '<input type=submit value="Go">';
                echo '</td>';
                echo '</form>';
                echo '</tr>';
            }
        }
    }
}
if($req_num > 0){
    echo '</table>';
}else{
    echo '<p>No requests!</p>';
}

//User Access Requests
if($isadmin){
    echo '<h3>User Access Requests</h3>';
    $sql = "SELECT * FROM ActionItems WHERE actiontype = 'usreq'";
    $result = $conn_equipt->query($sql);
    $usreq_tot = mysqli_num_rows($result);
    $req_num = 0;
    foreach($result as $value){
        if($req_num == 0){
            echo "<table style='width:50%'>";
        }
        $req_num += 1;
        echo '<tr>';
        echo '<td>'.$value['whofrom'].'</td>';
        $info = explode(",",$value['additional']);
        $first = explode("=",$info[0])[1];
        $last = explode("=",$info[1])[1];
        $email = explode("=",$info[2])[1];
        echo '<td>'.$first.'</td>';
        echo '<td>'.$last.'</td>';
        echo '<td>'.$email.'</td>';
        echo '<td>';
        echo '<form method="post" action="req_handler.php">';
        echo '<td>';
        echo '<input type="hidden" name="reqID" value="'.$value['reqID'].'"/>';
        echo '<input type="hidden" name="reqtype" value="usreq"/>';
        echo '<input type="hidden" name="reason" value="usreq"/>';
        echo '<input type="hidden" name="refpage" value="notifications.php"/>';
        echo '<input type=submit name="approve" value="Approve"/>';
        echo '</td>';
        echo '<td>';
        echo '<input type=submit name="reject" value="Reject"/>';
        echo '</td>';
        echo '</form>';
        echo '</tr>';
    }

    if($req_num != 0){
        echo '</table>';
    }else{
        echo '<p>No requests!</p>';
    }
}
?>
</div>
</body>
</html>

