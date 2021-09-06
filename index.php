
<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<title>DMG Portal - Home</title>

</head>

<body>

<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = strip_tags($data);
  $data = htmlspecialchars($data);
  return $data;
}

session_start();
$_SESSION['ref'] = "main.php";
include 'topbar.php';
include 'db_connect.php';

$sql = "UPDATE USER_LIST SET Last_Active = CURRENT_DATE() WHERE usern = '".$_COOKIE[DMGuser]."'";
$conn_users->query($sql);

function FutureBooking(){
	echo '<div class="bulk">';
    include 'db_connect.php';
	//Future Bookings Section
    echo "<h3> Your Upcoming Bookings </h3>";
    
    //$plusweek = date(Ymd) + 8;
    $plusweek = date('Ymd',strtotime('+8 days',strtotime(date('Ymd')))); 
    $sql = "SELECT * FROM webcal_entry WHERE DMGuser = '" . $_COOKIE['DMGuser'] . "' AND cal_date < " . $plusweek . " ORDER BY cal_date, cal_create_by";
	//echo $sql;
    $res = $conn_webcam->query($sql);
    $bookings = 0;
    if ($res) {
        foreach ($res as $value) {
        	
        	//Add in here code to check for events that overrun
        	$time = $value['cal_time'];
			$time_s = substr( $time, -2,2 );
			$time_m = substr( $time, -4,2 );
			//Include UTC fix in future!
			$time_h = (($time - $time_s - ($time_m * 100))/10000) + 1 ;
		
			//$time_done = $time + ($value['cal_duration']*100);
			//if($time_done > 240000){
			//$time_done += (-240000);
			//}
		
			$dur = $value['cal_duration'];
			$dur_h = floor($dur/60);
			$dur_m = $dur%60;
		
			$time_ds = substr( $time_done, -2,2 );
			$time_dm = $time_m+$dur_m;
			$time_dh = $time_h+$dur_h;
			
			$time_end = $time+($dur_h * 10000)+$dur_m;
			if($time_end > 240000){
				$days = floor($time_end/240000);
			}else{
				$days = 0;
			}
			
			$cal_date = $value['cal_date'];
			
			if($days == 1){
				$cal_date = date('Ymd',strtotime('+1 day',strtotime($cal_date))); 
			}elseif($days > 1){
				$st = '+'.$days.' days';
				$cal_date = date('Ymd',strtotime($st,strtotime($cal_date))); 
			}
			       	
        	if(($cal_date) >= date(Ymd)){
        	
				$sql_c = "SELECT * FROM webcal_entry_user WHERE cal_id = " . $value['cal_id'];
				$res_c = $conn_webcam->query($sql_c);
				foreach ($res_c as $val_c) {
					$stat = $val_c['cal_status'];
				}
				if ($stat != 'D') {
					if ($bookings == 0) {
						echo '<table id = "customers">';
						echo '<tr><th>Equipment</th><th>Title</th><th>Date</th><th>Start</th><th>End</th><th>Duration</th></tr>';
						$bookings = 1;
					}

					$sql_b = "SELECT * FROM EQ_LIST WHERE abbrev = '" . $value['cal_create_by'] . "'";
					$res_b = $conn_equipt->query($sql_b);
					$eq_name = 'N/A';
					foreach ($res_b as $val_b) {
						$eq_name = $val_b['fullname'];
					}
					echo '<tr>';
				
					$eq_abbrev = $value['cal_create_by'];
					$desc = test_input(($value['cal_description']));
					
					
					echo '<td>' . $eq_name . '</td>';
					echo '<td>';
					
					echo '<div class="tooltip">'.$value['cal_name'];
					if($value['cal_name']!=$desc){
					if(strlen($desc)>0){
						echo '<span class="tooltiptext">'.$desc;
					}else{
					}
					}
  					echo '</span></div>';
					
					
					echo '</td>';
				
					if($time_dm==0){
					$time_dm = '00';
					}elseif($time_dm<10){
					$time_dm = '0'.$time_dm;
					}
					
					if($dur_m==0){
					$dur_m = '00';
					}elseif($dur_m<10){
					$dur_m = '0'.$dur_m;
					}

					$date = DateTime::createFromFormat('Ymd', $value['cal_date']);
					echo '<td>' . date_format($date, 'd/m/Y ') . '</td>';
					echo '<td>' . $time_h .':'.$time_m.'</td>';
					echo '<td>' . ($time_dh%24) .':'.$time_dm . '</td>';
					echo '<td>' . $dur_h .':'.$dur_m. '</td>';
				
					echo '<td>' ; 
					$link = 'https://dmg-stairs.msm.cam.ac.uk/portal/webcal/view_entry.php?id='.$value["cal_id"].'&date='.$value["cal_date"];
					echo '<form method="post" action="webcal/login.php">';
					echo '<input type="hidden" name="login" value="'.$value['cal_create_by'].'"/>';
					echo '<input type="hidden" name="refpage" value="'.$link.'"/>';
					echo '<input type="hidden" name="password" value="admin"/>';
				
					echo "<input type='submit' value='Edit Booking' style='width:100%'>";
					echo '</form>';
				
					echo'</td>';
					echo '</tr>';
				}
            }

        }
        if ($bookings > 0) {
            echo '</table>';
        } else {
            echo '<p class="slim"> You have no bookings for the next 7 days! </p>';
        }

	}
	echo '</div>';
}


FutureBooking();

function YourEquipment(){
	echo '<div style="padding-left:30px">';
	echo "<h3>Your Equipment</h3>";
	include 'db_connect.php';
	$result = mysqli_query($conn_users,"SELECT * FROM ".$_COOKIE[DMGuser]." ORDER BY CASE
	WHEN User_Level = 'Manager' THEN '1'
	WHEN User_Level = 'superuser' THEN '2'
	WHEN User_Level = 'B user' THEN '3'
	WHEN User_Level = 'C user' THEN '4'
	WHEN User_Level = 'Trainee' THEN '5'
	ELSE User_Level END ASC, Equipment_Name");

	if(mysqli_num_rows($result) > 0){
		echo "<table style= cellpadding='4' id='customers'>
		<tr>
		<th>Name</th>
		<th>Level</th>
		<th>Equipment Status</th>
		</tr>";
	}else{
		echo '<p> You are not a registered user of any equipment. You can request access below. </p>';
	}

	while($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		$sql = "SELECT * FROM EQ_LIST WHERE abbrev = '".$row['Equipment_Name']."'";
		//echo $sql;
		$res = $conn_equipt->query($sql);


		//probably a better way to do this...
		foreach($res as $val){
			$fulln = $val['fullname'];
			$book = $val['bookable'];
			//echo $fulln;
			$stat = $val['EQ_STAT'];
			$RA_path = $val['RA'];
			$com = $val['EQ_COM'];
		}

		echo "<td>" . $fulln . "</td>";
		echo "<td>" . $row['User_Level'] . "</td>";
		echo "<td>";
		
		echo '<div class="tooltip">'.$stat;
		if(strlen($com)>0){
			echo '<span class="tooltiptext">'.$com;
		}else{
		}
		echo '</span></div>';
		echo "</td>";

		if(($row['User_Level']=='superuser')||($row['User_Level']=='Manager')){
		echo '<td>';
		echo '<form method="post" action="seteqcookie.php">';
		echo '<input type="hidden" name="eq" value="'.$row["Equipment_Name"].'"/>';
		echo "<input type='submit' value='Manage' style='width:100%'>";
		echo '</form>';
		echo '</td>';
		}else{
		echo "<td>";
		echo '<form method="post" action="seteqcookieview.php">';
		//echo "<a href='manage.php'> View User List</a>";
		echo '<input type="hidden" name="eq" value="'.$row["Equipment_Name"].'"/>';
		echo "<input type='submit' value='View User List' style='width:100%'>";
		echo '</form>';
		echo "</td>";
		}



		if(($row['User_Level']!='Trainee')&&($book != '0')){
			echo '<td>';
			echo '<form method="post" action="webcal/login.php">';
			//echo '<form method="post" action="webcal/newacc.php">';

			echo '<input type="hidden" name="eq" value="'.$row["Equipment_Name"].'"/>';

			/* if(($row["Equipment_Name"] == "MiniHe3")||($row["Equipment_Name"] == "MiniHe4")){
				echo '<input type="hidden" name="login" value="mini"/>';
			}elseif (($row["Equipment_Name"] == "TcR")||($row["Equipment_Name"] == "NewP")){
				echo '<input type="hidden" name="login" value="dewar"/>';
			}else{
				echo '<input type="hidden" name="login" value="'.$row["Equipment_Name"].'"/>';
			} */

			echo '<input type="hidden" name="login" value="'.$row["Equipment_Name"].'"/>';

			echo '<input type="hidden" name="password" value="admin"/>';
			echo "<input type='submit' value='Booking' style='width:100%'>";
			echo '</form>';
			echo ' </td>';
		}else{
			echo "<td></td>";
		}
			
		if($RA_path != Null && strlen($RA_path)>0){
			echo '<td>';
			echo '<form method="get" action="'.$RA_path.'">';
			echo '<button type="submit" style="width:100%">Download Risk Assessment</button>';
			echo '</form>';
			echo '</td>';
		}else{
			//echo "<td></td>";
		}
		
		
		echo "</tr>";
	}

	if($result){
		echo "</table>";
	} else{
		echo "<p> You have no equipment available to book.<br> <a href='allequipment.php' class='stdlink' >Click here to view all available equipment.</a> </p>";
	}
	echo '</div>';
}

YourEquipment();
?>

<div style="padding-left:30px">
<header>
    <h3>Helpful Links</h3>
</header>
<a href="allequipment"> Request Equipment Access</a>
<br>
<!--
<a href="webcal/publicac.php"> View All Sputter Room Bookings</a>
<br>
-->
<a href="viewuserlist"> View a User List</a>
<br>
<a href="webcal/login?login=rm_-1p015L"> View a Room</a>
<br><br>
<a href="uploads/DMG_050821.pdf">DMG Directory</a>
<br>
<a href="uploads/DMG_DeskPlan_050821.pdf">DMG Desk Plan</a>
<br><br>
<a href="../wiki/">DMG Wiki</a>
<header>
<h3>What do training levels mean?</h3>
</header>
<p> Manager: Takes responsibility, organises training, organises equipment repairs and servicing, decides who is allowed to become a trainee, promotes people to next user tier.  </p>
<p> Superuser (A user): Trains people, helps fix and maintain equipment, promotes people to next user tier.  </p>
<p> B user: Allowed to use the system anytime.  </p>
<p> C user: Allowed to use the system between 9am and 5pm. Supervision of a B user required out of hours.   </p>
<p> Trainee: Supervision required at all times. </p>

<?php


$sqlc = 'SELECT * FROM USER_LIST WHERE usern = "'.$_COOKIE[DMGuser].'"';
$resultc = $conn_users->query($sqlc);

foreach($resultc as $value){
	if(($value['email']!="") && ($value['email']!=NULL)){
	}else{
	
echo '<script language="javascript">';
echo 'if(confirm("DMGPortal: Update Email!")){
window.location.replace("https://dmg-stairs.msm.cam.ac.uk/portal/changepass.php");}';
echo 'else{window.location.replace("https://dmg-stairs.msm.cam.ac.uk/portal/changepass.php");}';
echo '</script>';

	
	}
	
}

?>
</div>
</body>
</html>

