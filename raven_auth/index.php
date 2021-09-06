<?php
include '../db_connect.php';
if($_SERVER['REMOTE_USER']){
    $stmt = $conn_users->prepare("SELECT * FROM USER_LIST WHERE LOWER(usern) = ?");
    $stmt->bind_param("s", $_SERVER['REMOTE_USER']);
    $stmt->execute();
    $result = $stmt->get_result();
    if(mysqli_num_rows($result)>0){
        setcookie("DMGuser", $_SERVER['REMOTE_USER'], time() + (60*60*1), "/");
        setcookie('DMGlogintime', time() + (60*60*1), time() + (60*60*1), "/");
        header("location:../");
    }else{
        header("location:../ac_req");
    }
}
die();
?>