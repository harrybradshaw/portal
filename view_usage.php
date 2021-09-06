<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<title>DMG Portal - View</title>

</head>
<body>

<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

session_start();
$_SESSION['ref'] = "view_usage.php";
include 'topbar.php';

?>

<br>
<div class="bulk">
<header>

<?php
$username = test_input($_POST["usersl"]);
$equipment = test_input($_POST["eq"]);
$equipment = $_COOKIE[eqpage];
$eqmonth = $_COOKIE[eqmonth];
$eqyear = $_COOKIE[eqyear];

include 'db_connect.php';

$sql = 'SELECT * FROM EQ_LIST WHERE abbrev = "'.$equipment.'"';
//echo $sql;
$result = $conn_equipt->query($sql);

foreach($result as $value){
    $head = $value["fullname"];
	$stat = $value['EQ_STAT'];
	$comment = $value['EQ_COM'];
}


function MakeList($conn_equipt, $equipment, $eqmonth, $eqyear){
    echo '<form name="makelist" method="post" action="seteqcookieview.php"> <p> Change to ';
    echo '<select name="eq">';
    $sql = mysqli_query($conn_equipt, "SELECT * FROM EQ_LIST ORDER BY fullname");
    while ($row = $sql->fetch_assoc()){
		if($row['abbrev'] == $equipment){
			echo "<option value='".$row['abbrev']."' selected >" . $row['fullname'] . "</option>";
		}else{
			echo "<option value='".$row['abbrev']."'>" . $row['fullname'] . "</option>";
		}
        
    }
    echo '</select>';
	echo ' ';
	echo '<select name="month">';
	for( $i = 1 ; $i <= 12; $i++ ) {
        $dateObj   = DateTime::createFromFormat('!m', $i);
		$monthName = $dateObj->format('F');
		if($i == $eqmonth){
			echo '<option value='.$i.' selected >'.$monthName.'</option>';
		}else{
			echo '<option value='.$i.'>'.$monthName.'</option>';
		}
		
	}
	echo '</select>';

	echo '<select name="year">';
	for( $i = 2021 ; $i >= 2019; $i-- ) {
		if($i == $eqyear){
			echo '<option value='.$i.' selected >'.$i.'</option>';
		}else{
			echo '<option value='.$i.'>'.$i.'</option>';
		}
		
	}
	echo '</select>';

	echo '<input type="hidden" name="refpage" value="view_usage.php">';
	echo '' ;
	echo "<input type='submit' value='View Usage'>";
	echo '</p>';
    echo '</form>';

}

MakeList($conn_equipt, $equipment,$eqmonth, $eqyear);


echo '<header>';
echo "<h2>";
echo $head;
//echo $equipment;
echo ' - Usage List';
echo "</h2>";
echo '</header>';
echo '<p> Equipment is '.$stat;
if(strlen($comment) > 0){
	echo '<br> Managers Comment: '.$comment;
}
echo '<br>';
$dateObj   = DateTime::createFromFormat('!m', $eqmonth);
$monthName = $dateObj->format('F');
//echo $monthName;
//echo $eqyear;
echo '<br>';
$d = new DateTime( $eqyear.'-'.$eqmonth.'-'.'01'); 
$s = $d->format( 'Ymd' );
$e = $d->format( 'Ymt' );
echo '</p>';

//echo '"SELECT * FROM ".$equ';

echo "<table style='width:25%' id='customers'>
<tr>
<th>Name</th>
<th>Usage (Mins)</th>
<th># Bookings</th>
</tr>";

$sql_b = 'SELECT * FROM USER_LIST ORDER BY usern';
$res_b = mysqli_query($conn_users,$sql_b);
//Go through all registered users.
//Check that cal_date range

foreach($res_b as $value){
	$user = $value['usern'];
	
	$sql = "SELECT * FROM webcal_entry WHERE cal_create_by = '" . $equipment . "' AND DMGuser = '".$user."' AND cal_date >= $s AND cal_date <= $e ORDER BY DMGuser";
	$res = $conn_webcam->query($sql);
	$tot = 0;
	$numbook = 0;
	//Grab all entries for specified eq for user. 
	if($res){
		foreach($res as $row){
			$sql_c = "SELECT * FROM webcal_entry_user WHERE cal_id = " . $row['cal_id'];
			$res_c = $conn_webcam->query($sql_c);
			foreach ($res_c as $val_c) {
				$stat = $val_c['cal_status'];
				//echo $stat;
			}
			if ($stat != 'D') {
				$tot += $row['cal_duration'];
				$numbook++; 
			}
			
		}
		if($tot != 0){
				echo "<tr>";
				echo "<td>" . $user . "</td>";
				echo "<td>" . $tot . "</td>";
				echo "<td>" . $numbook . "</td>";
				echo "</tr>";
			
		}
		
	}
	
}

echo "</table>";
?>

<br>

</div>
</body>
</html>
