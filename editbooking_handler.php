<?php

$id = $_POST['id'];
$date=$_POST['date'];
$eq=$_POST['eq_name'];

$link = 'https://dmg-stairs.msm.cam.ac.uk/portal/webcal/view_entry.php?id='.$id.'&date='.$date;
header('location:'.$link)

?>