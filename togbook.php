<?php

include "db_connect.php";

$eq = $_POST['eq'];
$ref_page = $_POST['refpage'];

$sql = 'SELECT bookable FROM EQ_LIST WHERE abbrev = "'.$eq.'"';
$result = $conn_equipt->query($sql);
$old_book = '1';
foreach($result as $value){
    $old_book = $value['bookable'];
}

if($old_book != '1'){
    $new_book = '1';
}else{
    $new_book = '0';
}



$sql = "UPDATE EQ_LIST SET bookable = '".$new_book."' WHERE abbrev = '".$eq."'";
if ($conn_equipt->query($sql) === TRUE) {
    echo "New record created successfully";
    header("location:".$ref_page);

} else {
    echo "Error: " . $sql . "<br>" . $conn_equipt->error;
}

?>

