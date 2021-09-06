<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">

<title>DMG Portal - All Equipment</title>
</head>

<body>


<?php

session_start();
$_SESSION['ref'] = "allequip.php";
include 'topbar.php';
include 'db_connect.php';
echo '<div class="bulk">';
echo '<h2> All Equipment </h2>';

$username = $_COOKIE[DMGuser];

$result = $conn_users->query("SELECT role FROM USER_LIST WHERE usern = '".$username."'");

$sql = 'SELECT * FROM EQ_LIST ORDER BY fullname';
$result = $conn_equipt->query($sql);

echo "<table style='width:70%' id='customers'>";
echo '<tr><th>Name</th><th>Manager</th></tr>';
echo "<tr>";

foreach($result as $value){
    echo "<td>" . $value['fullname'] . "</td>";
    echo "<td>";
    $sql_a = 'SELECT * FROM '.$value['abbrev'].' WHERE Level = "Manager"';
    $result_a = $conn_equipt->query($sql_a);
    $man_count = 0;
    $email_list  = '';
    foreach($result_a as $value_a){
        if($man_count!=0){
            echo ', ';
        }
        $sql_b = 'SELECT firstname, surname, email FROM USER_LIST WHERE usern = "'.$value_a['Name'].'" ';
        $result_b = $conn_users->query($sql_b);
        foreach($result_b as $value_b){
            if($value_b['firstname']){
                echo $value_b['firstname'] . ' ' . $value_b['surname'] . ' (' . $value_a['Name'] . ')';
                $email_list = $email_list.$value_b['email'].',';
            }elseif($value_b['email']){
                echo $value_a['Name'];
                $email_list = $email_list.$value_b['email'].',';
            }
            else{
                echo $value_a['Name'];
            }
        }
        $man_count +=1;
    }

    echo  "</td>";
    echo '<td>';
    $sql_a = "SELECT * FROM ".$value['abbrev']." WHERE Name = '".$username."'";
    $result_a = $conn_equipt->query($sql_a);
    $rows = mysqli_num_rows($result_a);
    if($rows>0){
        echo '<button type="submit" disabled>Already User</button>';
    }else{
        //Now test to see if already has a request.
        echo '<form method="post" action="req_handler.php">';
        echo '<input type="hidden" name="refpage" value="allequipment.php"/>';
        $sql_b = "SELECT * FROM ActionItems WHERE whofrom = '".$username."' AND whoto = '".$value['abbrev']."'";
        $result_b = $conn_equipt->query($sql_b);
        $rows = mysqli_num_rows($result_b);
        if($rows>0){
            echo '<input type="hidden" name="reason" value="eqreq_can"/>';
            echo '<input type="hidden" name="req_whoto" value="'.$value['abbrev'].'"/>';
            echo '<input type="hidden" name="req_whofrom" value="'.$username.'"/>';
            echo '<input type="submit" value="Cancel Request"/>';
        }else{
            echo '<input type="hidden" name="reason" value="eqreq_req"/>';
            echo '<input type="hidden" name="req_whoto" value="'.$value['abbrev'].'"/>';
            echo '<input type="hidden" name="req_whofrom" value="'.$username.'"/>';
            echo '<input type="submit" value="Send Request"/>';

        }
        echo '</form>';
        
    }
    
    echo '</td>';

    echo "</tr>";
}
echo "</table>";
echo '<br><a href="createequip.php"> Create New Equipment</a>';

?>

</div>
</body>
</html>
