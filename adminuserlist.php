<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<title>DMG Portal - Manage</title>
</head>

<body>

<?php

session_start();
$_SESSION['ref'] = "adminuserlist.php";
include 'topbar.php';
include 'db_connect.php';

echo '<div class="bulk">';
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


$username = $_COOKIE[DMGuser];
$is_admin = FALSE;
$sql = "SELECT role FROM USER_LIST WHERE usern = '".$username."'";
$result = $conn_users->query($sql);

foreach($result as $value){
    if($value['role'] != 'admin'){
        //header("location:main.php");
    }else{
		if($_COOKIE[eqrights]=='admin'){
			$is_admin = TRUE;
		}
		
	}
}
?>


<?php

$equipment = $_COOKIE[eqpage];
$username = $_COOKIE[DMGuser];

$sql = 'SELECT * FROM EQ_LIST WHERE abbrev = "'.$equipment.'"';
//echo $sql;
$result = $conn_equipt->query($sql);

foreach($result as $value){
    $head = $value["fullname"];
	$stat = $value['EQ_STAT'];
	$comment = $value['EQ_COM'];
	$rapath = $value['RA'];
	$bookable = $value['bookable'];
	$room = $value['Room'];
}

echo '<header>';
echo "<h2>";
echo $head;
echo "</h2>";
echo '</header>';

if($is_admin){
	echo '<p> Viewing as admin </p>';
}

echo '<p> Equipment is '.$stat;
if(strlen($comment) > 0){
	echo '<br> Managers Comment: '.$comment;
}
echo '</p>';

$sqlb = "SELECT Level FROM ".$equipment." WHERE Name = '".$username."'";
//echo $sql;
$result = $conn_equipt->query($sqlb);
foreach($result as $value){
	$loginlevel = $value['Level'];
}
if(($loginlevel!='superuser')&($loginlevel!='Manager')&($is_admin!=TRUE)){
	header('location:viewuserlist.php');
}

$result = mysqli_query($conn_equipt,"SELECT * FROM ".$equipment." ORDER BY CASE
WHEN Level = 'Manager' THEN '1'
WHEN Level = 'superuser' THEN '2'
WHEN Level = 'B user' THEN '3'
WHEN Level = 'C user' THEN '4'
WHEN Level = 'Trainee' THEN '5'
ELSE Level END ASC");

function create_menus($name,$level,$loginlevel,$is_admin){
if(($loginlevel == 'superuser')&&($is_admin == FALSE)){
	if ($level == 'superuser'){
		$menu = '<select name="newlev" > <option value="B">Demote to B user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
	}elseif($level == 'B user'){
		$menu = '<select name="newlev">  <option value="A">Promote to A user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
	}elseif($level == 'C user'){
		$menu = '<select name="newlev" > <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
	}elseif($level == 'Trainee'){
		$menu = '<select name="newlev" > <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="C">Promote to C user</option><option value="REM">Remove</option></select>';	
	}
	elseif($level == 'Manager'){
		$menu = '<select name="newlev"> <option value="M">Promote to manager</option> </select>';
	}
}else{
	if ($level == 'superuser'){
		$menu = '<select name="newlev" > <option value="M">Promote to manager</option><option value="B">Demote to B user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
		}elseif($level == 'B user'){
		$menu = '<select name="newlev"> <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
		
		}elseif($level == 'C user'){
		$menu = '<select name="newlev" > <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
		
		}elseif($level == 'Trainee'){
		$menu = '<select name="newlev" > <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="C">Promote to C user</option><option value="REM">Remove</option></select>';
		
		}
		elseif($level == 'Manager'){
		$menu = '<select name="newlev"> <option value="A">Demote to A</option> <option value="B">Demote to B</option> <option value="C">Demote to C</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
		
		}else{
			$menu = '<select name="newlev"> <option value="REM">Remove</option></select>';
		
		}
}

return $menu;
}

echo "<table style='width:15%' id='customers'>
<tr>
<th>Name</th>
<th>Level</th>
<th>Action</th>
</tr>";

$all_user_email = "";

while($row = mysqli_fetch_array($result))
{

echo "<tr>";
echo "<td>";
$sql_b = 'SELECT * FROM USER_LIST WHERE usern = "'.$row['Name'].'"';
$res_b = mysqli_query($conn_users,$sql_b);
foreach($res_b as $value){
	$first = $value['firstname'];
	$last = $value['surname'];
}
$us = $row['Name'];
echo '<div class="tooltip">'.$us;
	if(strlen($first)>0){
  		echo '<span class="tooltiptext">'.$first.' '.$last;
  	}else{
		  echo '<span class="tooltiptext"> No Name!';
	}

echo "</td>";
//Populate the email list of all users.

$sqlc = 'SELECT * FROM USER_LIST WHERE usern = "'.$us.'"';
$resultc = $conn_users->query($sqlc);

foreach($resultc as $value){
	if(($value['email']!="") && ($value['email']!=NULL)){
		$all_user_email .= $value['email']."; ";
	}
}


echo "<td>" . $row['Level'] . "</td>";
echo '<form method="post" action="edituserl.php">';
echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
echo '<input type="hidden" name="pager" value="'.$equipment.'manage.php"/>';
echo '<input type="hidden" name="user" value="'.$us.'"/>';
echo '<input type="hidden" name="admin" value="'.$is_admin.'"/>';
echo "<td>" . create_menus($row['Name'],$row['Level'],$loginlevel,$is_admin) . "</td>";
echo "<td> <input type='submit' value='Update'> </td>";
echo '</form>';
echo "</tr>";
}
echo "</table>";

echo '<br>';


function popu(){
include 'db_connect.php';
$queryUsers = "SELECT * FROM USER_LIST ORDER BY usern";
$resultUsers = $conn_users->query($queryUsers);
$a = '<select name="usersl">';
while ($rowCerts = $resultUsers->fetch_assoc()) {
    $a .='<option value='.$rowCerts['usern'].'>';
    $a .=$rowCerts['usern'];
    $a .='</option>';
}

$a .='</select>';
return $a;
}

function popf(){
include 'db_connect.php';
$dir = "uploads/";
$a = array_diff(scandir($dir), array('..', '.'));
$c = '<select name="filesl">';
foreach($a as $b){
	$c .='<option value='.$b.'>';
	$c .= $b;
	$c .='</option>';
}
$c .='</select>';
return $c;
}

function pop_rooms($room){
	include 'db_connect.php';
	$sql = 'SELECT * FROM Rooms ORDER BY RoomID';
	$res = $conn_equipt->query($sql); 
	$c = '<select name="room">';
	if(empty($room)){
		$c .= '<option value="" selected disabled hidden>Choose here</option>';
	}
	foreach($res as $value){
		$b = $value['RoomID'];
		$c .='<option value='.$b;
		if($b == $room){
			$c .= ' selected ';
		}	
		$c .='>';
		$c .= $value['RoomName'].' ('.$b.')';
		$c .='</option>';
	}
	$c .='</select>';
	return $c;
	}

function eq_stat($stat){
	$b ='<select name="eqstat">';
	if($stat=='Operational'){
		$b .= '<option value="Operational" selected>Operational</option>"';
		$b .= '<option value="Under Maintenance - Short Term">Under Maintenance - Short Term</option>"';
		$b .= '<option value="Under Maintenance - Long Term">Under Maintenance - Long Term</option>"';
		$b .= '<option value="Not Operational">Not Operational</option>"';
	}elseif($stat=='Under Maintenance - Short Term'){
		$b .= '<option value="Operational">Operational</option>"';
		$b .= '<option value="Under Maintenance - Short Term" selected>Under Maintenance - Short Term</option>"';
		$b .= '<option value="Under Maintenance - Long Term">Under Maintenance - Long Term</option>"';
		$b .= '<option value="Not Operational">Not Operational</option>"';
	}elseif($stat=='Under Maintenance - Long Term'){
		$b .= '<option value="Operational">Operational</option>"';
		$b .= '<option value="Under Maintenance - Short Term">Under Maintenance - Short Term</option>"';
		$b .= '<option value="Under Maintenance - Long Term" selected>Under Maintenance - Long Term</option>"';
		$b .= '<option value="Not Operational">Not Operational</option>"';
	}elseif($stat=='Not Operational'){
		$b .= '<option value="Operational">Operational</option>"';
		$b .= '<option value="Under Maintenance - Short Term">Under Maintenance - Short Term</option>"';
		$b .= '<option value="Under Maintenance - Long Term" >Under Maintenance - Long Term</option>"';
		$b .= '<option value="Not Operational" selected>Not Operational</option>"';
	}
	
	$b .= '</select>';
	
	return $b;
}

//Manager Tools Section

$sql = "SELECT Level FROM ".$equipment." WHERE Name = '".$username."'";
$result = $conn_equipt->query($sql);
foreach($result as $value){
	$this_user_level = $value['Level'];
}
if (($this_user_level == 'Manager')||($is_admin)){

	echo '<h2> Manager Tools </h2>';

	echo '<form method="post" action="edituserlevel.php">';
	echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
	echo '<input type="hidden" name="pager" value="'.$equipment.'manage.php"/>';
	echo '<input type="hidden" name="refpage" value="adminuserlist.php"/>';
	echo '<input type="hidden" name="user" value="'.$us.'"/>';
	echo '<input type="hidden" name="admin" value="'.$is_admin.'"/>';
	echo "<p> Add ". popu()." as a Trainee <input type='submit' value='Submit'></p>";
	echo '</form>';


	echo '<form method="post" action="editeqstat.php">';
	echo '<p> Edit Status: '.eq_stat($stat).' <br> Comment: ';
	echo "<input type='text' name='com' value = '".$comment."'> ";
	echo '<input type="hidden" name="refpage" value="adminuserlist.php"/>';
	echo '<input type="hidden" name="reason" value="eqstat"/>';
	echo "<input type='submit' value='Update'> </p>";
	echo '</form>';

	echo '<form method="post" action="editeqstat.php">';
	echo '<p> Room:';
	echo pop_rooms($room);
	echo '<input type="hidden" name="refpage" value="adminuserlist.php"/>';
	echo ' ';
	echo '<input type="hidden" name="reason" value="room"/>';
	echo "<input type='submit' value='Update'> </p>";
	echo '</form>';

	echo '<form method="post" action="togbook.php">';
	echo '<p> Change booking status: ';
	echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
	echo '<input type="hidden" name="refpage" value="adminuserlist.php"/>';
	echo "<input type='submit' value='Toggle Bookable'>";


	if($bookable=='1'){
		echo '(Currently: BOOKABLE)';
	}else{
		echo '(Currently: NOT BOOKABLE)';
	}
	echo '</form> </p>';

	echo '<form method="post" action="editrapath.php">';
			echo '<p> Select Risk Assessment (Current: '.$rapath.'): '.popf().' ';
			echo "<input type='submit' value='Update'> </p>";
			echo '<input type="hidden" name="refpage" value="adminuserlist.php"/>';
			echo '</form>';

	echo '<form method="post" action="shiftbookings.php">';
	echo '<p> Shift bookings: ';
	echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
	echo '<input type="hidden" name="refpage" value="adminuserlist.php"/>';
	echo "<input type='submit' value='Shift Bookings' disabled>";
	echo '</form>';

	//echo '<form action="upload.php" method="post" enctype="multipart/form-data">
		//Upload a RA:
		//<input type="file" name="fileToUpload" id="fileToUpload">
		//<input type="submit" value="Upload" name="submit">
	//</form>';

	echo '<br><a href = "mailto:'.$all_user_email.'"> Email All Users </a>';
	//echo $all_user_email;

}
echo '<br><br><a href="allequip.php"> Back to All Equipment</a><br>';
?>
</div>
</body>
</html>
