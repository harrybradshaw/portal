
<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//echo $_SERVER["REQUEST_METHOD"];


$Status = $_POST['eqstat'];
$username = $_COOKIE[DMGuser];
$equipment = $_COOKIE[eqpage];
$refpage = $_POST['refpage'];
$comment = test_input($_POST['com']);
$room = test_input($_POST['room']);
$reason = $_POST['reason'];

echo 'Comment: '.$comment;
echo 'Status: '.$Status;
echo 'Piece of Eq: '.$equipment;
echo 'Reason for form: '.$reason;
echo 'Refering page: '.$refpage;

include 'db_connect.php';

if($reason == 'eqstat'){
  if(!empty($Status)){
    $sql = "UPDATE EQ_LIST SET EQ_STAT = '".$Status."' WHERE abbrev = '".$equipment."'";
    echo $sql;
    $conn_equipt->query($sql);
  }


  echo '<br>';
  $sql = "UPDATE EQ_LIST SET EQ_COM = '".$comment."' WHERE abbrev = '".$equipment."'";
  echo $sql;
  $conn_equipt->query($sql);
}

if($reason == 'room'){
  if(!empty($room)){
    
    //Delete previous room layers
    $sql = "DELETE FROM webcal_user_layers WHERE cal_layeruser = '".$equipment."'";
    $conn_webcam->query($sql);
    $sql = "DELETE FROM webcal_user_layers WHERE cal_login = '".$equipment."'";
    $conn_webcam->query($sql);
    //Enable layers
    $sql = "SELECT * FROM webcal_user_pref WHERE cal_setting = 'LAYERS_STATUS' AND cal_login = '".$equipment."'";
    $res = $conn_webcam->query($sql);
    $row_cnt = $res->num_rows;
    if($row_cnt == 0){
      $sql = "INSERT INTO webcal_user_pref (cal_login, cal_setting, cal_value) VALUES ('".$equipment."', 'LAYERS_STATUS', 'Y')";
      echo '<br>';
    }else{
      $sql = "UPDATE webcal_user_pref SET cal_value = 'Y' WHERE cal_setting = 'LAYERS_STATUS' AND cal_login = '".$equipment."'";
    }
    $conn_webcam->query($sql);

    $sql = "SELECT * FROM webcal_user_pref WHERE cal_setting = 'LAYERS_STATUS' AND cal_login = '".$room."'";
    $res = $conn_webcam->query($sql);
    $row_cnt = $res->num_rows;
    if($row_cnt == 0){
      $sql = "INSERT INTO webcal_user_pref (cal_login, cal_setting, cal_value) VALUES ('".$room."', 'LAYERS_STATUS', 'Y')";
      echo '<br>';
    }else{
      $sql = "UPDATE webcal_user_pref SET cal_value = 'Y' WHERE cal_setting = 'LAYERS_STATUS' AND cal_login = '".$room."'";
    }
    $conn_webcam->query($sql);


    $conn_webcam->query($sql);
    //Add new room layers
    //First get max layer ID
    $sql = "SELECT MAX(cal_layerid) FROM webcal_user_layers";
    $res = $conn_webcam->query($sql);
    foreach($res as $value){
      $newint = $value['MAX(cal_layerid)'] + 1;
    }
    $sql = "INSERT INTO webcal_user_layers (cal_layerid, cal_login, cal_layeruser, cal_color, cal_dups) VALUES (".$newint.",'".$room."','".$equipment."','#FF0000','N')";
    $conn_webcam->query($sql);
    //Grab all equipment in that room and add onto that 
    $sql = "SELECT abbrev FROM EQ_LIST WHERE Room = '".$room."' AND abbrev <> '".$equipment."'";
    $res = $conn_equipt->query($sql);
    foreach($res as $value){
      $newint += 1;
      $sql = "INSERT INTO webcal_user_layers (cal_layerid, cal_login, cal_layeruser, cal_color, cal_dups) VALUES (".$newint.",'".$value['abbrev']."','".$equipment."','#FF0000','N')";
      $conn_webcam->query($sql);
      $newint += 1;
      $sql = "INSERT INTO webcal_user_layers (cal_layerid, cal_login, cal_layeruser, cal_color, cal_dups) VALUES (".$newint.",'".$equipment."','".$value['abbrev']."','#FF0000','N')";
      $conn_webcam->query($sql);
    }
    //Update the main eq table with room info.
    $sql = "UPDATE EQ_LIST SET Room = '".$room."' WHERE abbrev = '".$equipment."'";
    echo $sql;
    $conn_equipt->query($sql);

  }
}

header("location:".$refpage);


?>

