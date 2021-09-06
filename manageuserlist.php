<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "style.css">
<title>DMG Portal - Manage</title>

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
$_SESSION['ref'] = "manageuserlist.php";
include 'topbar.php';

$equipment = $_COOKIE[eqpage];
$username = $_COOKIE[DMGuser];

include 'db_connect.php';

$sql = 'SELECT * FROM EQ_LIST WHERE abbrev = "'.$equipment.'"';
//echo $sql;
$result = $conn_equipt->query($sql);

foreach($result as $value){
    $head = $value["fullname"];
	$stat = $value['EQ_STAT'];
	$comment = $value['EQ_COM'];
	$rapath = $value['RA'];
	$bookable = $value['bookable'];
}


echo '<header>';
echo "<h2>";
echo $head;
echo ' - User Management';
echo "</h2>";
echo '</header>';

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

if(($loginlevel!='superuser')&($loginlevel!='Manager')){
	header('location:viewuserlist.php');
}

$result = mysqli_query($conn_equipt,"SELECT * FROM ".$equipment." ORDER BY CASE
WHEN Level = 'Manager' THEN '1'
WHEN Level = 'superuser' THEN '2'
WHEN Level = 'B user' THEN '3'
WHEN Level = 'C user' THEN '4'
WHEN Level = 'Trainee' THEN '5'
ELSE Level END ASC, Name");

function create_menus($name,$level){

if ($level == 'superuser'){
$menu = '<select name="newlev" > <option value="B">Demote to B user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
}elseif($level == 'B user'){
$menu = '<select name="newlev">  <option value="A">Promote to A user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}elseif($level == 'C user'){
$menu = '<select name="newlev" >  <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}elseif($level == 'Trainee'){
$menu = '<select name="newlev" >  <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="C">Promote to C user</option><option value="REM">Remove</option></select>';

}
elseif($level == 'Manager'){
$menu = '<select name="newlev"> <option value="A">Demote to A</option> <option value="B">Demote to B</option> <option value="C">Demote to C</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}
return $menu;
}

function create_menus_man($name,$level){

if ($level == 'superuser'){
$menu = '<select name="newlev" > <option value="M">Promote to manager</option><option value="B">Demote to B user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
}elseif($level == 'B user'){
$menu = '<select name="newlev"> <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}elseif($level == 'C user'){
$menu = '<select name="newlev" > <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}elseif($level == 'Trainee'){
$menu = '<select name="newlev" > <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="B">Promote to B</option><option value="C">Promote to C</option><option value="REM">Remove</option></select>';

}
elseif($level == 'Manager'){
$menu = '<select name="newlev"> <option value="A">Demote to A</option> <option value="B">Demote to B</option> <option value="C">Demote to C</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

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
$sql_b = 'SELECT * FROM USER_LIST WHERE usern = "'.$row['Name'].'"';
$res_b = mysqli_query($conn_users,$sql_b);
foreach($res_b as $value){
	$first = $value['firstname'];
	$last = $value['surname'];
}
echo "<tr>";
$us = $row['Name'];

echo "<td>"; 
echo '<div class="tooltip">'.$us;
	if(strlen($first)>0){
  		echo '<span class="tooltiptext">'.$first.' '.$last;
  	}else{
  		echo '<span class="tooltiptext"> No Name!';}
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


if ($loginlevel == 'Manager'){
echo "<td>" . create_menus_man($row['Name'],$row['Level']) . "</td>";
}else{
echo "<td>" . create_menus($row['Name'],$row['Level']) . "</td>";
}

echo "<td> <input type='submit' value='Update'> </td>";
echo '</form>';
echo "</tr>";
}
echo "</table>";

echo '<br>';


function popu($equipment){
include 'db_connect.php';
$queryUsers = "SELECT usern FROM USER_LIST ORDER BY usern";
$resultUsers = $conn_users->query($queryUsers);
$a = '<select name="usersl">';
while ($rowCerts = $resultUsers->fetch_assoc()) {
	$userTest = $conn_equipt->query("SELECT Name FROM ".$equipment." WHERE Name = '".$rowCerts['usern']."'");
	if(mysqli_num_rows($userTest)==0){
		$a .='<option value='.$rowCerts['usern'].'>';
		$a .=$rowCerts['usern'];
		$a .='</option>';
	}
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



function eq_stat(){
	$b ='<select name="eqstat">';
	$b .= '<option value="Operational">Operational</option>"';
	$b .= '<option value="Under Maintenance - Short Term">Under Maintenance - Short Term</option>"';
	$b .= '<option value="Under Maintenance - Long Term">Under Maintenance - Long Term</option>"';
	$b .= '</select>';
	
	return $b;
}


//Manager Tools Section

$sql = "SELECT Level FROM ".$equipment." WHERE Name = '".$username."'";
$result = $conn_equipt->query($sql);

foreach($result as $value){
	if ($value['Level'] == 'Manager'){
		echo '<h2> Manager Tools </h2>';

		echo '<form method="post" action="edituserlevel.php">';
		echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
		echo '<input type="hidden" name="pager" value="'.$equipment.'manage.php"/>';
		echo '<input type="hidden" name="refpage" value="manageuserlist.php"/>';
		echo "<p> Add ". popu($equipment)." as a Trainee <input type='submit' value='Submit'></p>";
		echo '</form>';
		
		echo '<form method="post" action="editeqstat.php">';
		echo '<p> Edit Status: '.eq_stat().' <br> Comment: ';
		echo "<input type='text' name='com' value = '".$comment."'> ";
		echo "<input type='submit' value='Update'> </p>";
		echo '<input type="hidden" name="refpage" value="manageuserlist.php"/>';
		echo '</form>';

		echo '<form method="post" action="togbook.php">';
		echo '<p> Change booking status: ';
		echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
		echo '<input type="hidden" name="refpage" value="manageuserlist.php"/>';
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
		echo '<input type="hidden" name="refpage" value="manageuserlist.php"/>';
		echo '</form>';
	
		echo '<form method="post" action="shiftbookings.php">';
		echo '<p> Shift bookings: ';
		echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
		echo '<input type="hidden" name="refpage" value="manageuserlist.php"/>';
		echo "<input type='submit' value='Shift Bookings' disabled>";
		echo '</form>';
		
		echo '<a href = "mailto:'.$all_user_email.'"> Email All Users </a>';
		
		// Graham - I added this since mailto: doesn't work for a lot of people
		echo "<p>Or copy/paste email list: <i>$all_user_email</i><br>If an email doesn't appear, it's because that person hasn't put their email in the system!</p>";
		



	}
}



?>

</body>
</html>
