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
$flag2 = False;

$New_name = test_input($_POST["New_name"]);
$New_sec_name = test_input($_POST["New_sec_name"]);
$New_abrev = preg_replace("/[^a-zA-Z0-9]+/", "", test_input($_POST["New_abrev"]));

if($New_sec_name == ""){
	$New_sec_name = " ";
}



if ($_POST["bookable"] == 'on'){
    $is_bookable = '1';
}else{
    $is_bookable = '0';
}

$New_Full = $New_name ." ". $New_sec_name;

include 'db_connect.php';

//first test if name already exists
$sql = 'SELECT * FROM EQ_LIST';
$result = $conn_equipt->query($sql);

foreach($result as $value){
    //echo $value['fullname'];
    if ($value['fullname'] == $New_name){
        $flag = True;
        echo "Username taken!";
    }elseif($value['abbrev'] == $New_abrev){
        $flag = True;
        echo "Abbreviation taken!";
    }
}

if ($flag == True){
    // Do nothing
}elseif(strlen($New_name) < 1){
    echo "You need an equipment name!";
}elseif(strlen($New_abrev) < 1){
    echo "You need an abbreviation!";
}else{
    if (!($stmt = $conn_equipt->prepare("INSERT INTO EQ_LIST (fullname,abbrev,bookable,EQ_STAT) VALUES (?,?,?,'Operational')"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param("ssi",$New_Full,$New_abrev,intval($is_bookable));
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();

    $New_abrev = $conn_equipt->real_escape_string($New_abrev);
    $New_sec_name = $conn_equipt->real_escape_string($New_sec_name);
    $New_name = $conn_equipt->real_escape_string($New_name);

    
    $sql = "CREATE TABLE $New_abrev (Name varchar(255), Level varchar(255) )";
    $conn_equipt->query($sql);
    $sql = "INSERT INTO webcal_user VALUES('$New_abrev', '21232f297a57a5a743894a0e4a801fc3', '$New_sec_name', '$New_name', 'N', NULL, 'Y',NULL,NULL,NULL,NULL,NULL)";
    $conn_webcam->query($sql);
    //$sql = "INSERT INTO webcal_user VALUES('".$New_abrev."', '21232f297a57a5a743894a0e4a801fc3', '".$New_sec_name."', '".$New_name."', 'N', NULL, 'Y',NULL,NULL,NULL,NULL,NULL)";

	if($flag2==False){
    header("location:allequip.php");
	}

}

?>
<br>
<br>
<a href="createequip.php"> Back</a>

</body>
</html>

