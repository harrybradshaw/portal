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
$_SESSION['ref'] = "viewuserlist.php";
include 'topbar.php';

?>
<div class="bulk">
<?php
$username = test_input($_POST["usersl"]);
$equipment = test_input($_POST["eq"]);
$equipment = $_COOKIE[eqpage];

include 'db_connect.php';

$sql = 'SELECT * FROM EQ_LIST WHERE abbrev = "'.$equipment.'"';
//echo $sql;
$result = $conn_equipt->query($sql);

foreach($result as $value){
    $head = $value["fullname"];
	$stat = $value['EQ_STAT'];
	$comment = $value['EQ_COM'];
}

function MakeList($conn_equipt,$equipment){
    echo '<form name="makelist" method="post" action="seteqcookieview.php"> <p> Change to ';
    echo '<select name="eq">';
    $sql = mysqli_query($conn_equipt, "SELECT * FROM EQ_LIST ORDER BY fullname");
    while ($row = $sql->fetch_assoc()){
        if($row['abbrev'] == $equipment){
            echo "<option value='".$row['abbrev']."' selected>" . $row['fullname'] . "</option>";
        }else{
            echo "<option value='".$row['abbrev']."'>" . $row['fullname'] . "</option>";
        }
        
    }
    echo '</select>';
    echo ' ';
    echo "<input type='hidden' name='refpage' value='viewuserlist.php'>";
    echo "<input type='submit' value='View User List'>";
    echo '</form>';
    echo '</p>';

}

MakeList($conn_equipt,$equipment);


echo '<header>';
echo "<h2>";
echo $head;
echo ' - User List';
echo "</h2>";
echo '</header>';

echo '<p> Equipment is '.$stat;
if(strlen($comment) > 0){
	echo '<br> Managers Comment: '.$comment;
}
echo '</p>';


//echo '"SELECT * FROM ".$equ';

$result = mysqli_query($conn_equipt,"SELECT * FROM ".$equipment." ORDER BY CASE
WHEN Level = 'Manager' THEN '1'
WHEN Level = 'superuser' THEN '2'
WHEN Level = 'B user' THEN '3'
WHEN Level = 'C user' THEN '4'
WHEN Level = 'Trainee' THEN '5'
ELSE Level END ASC, Name");


echo "<table style='width:15%' id='customers'>
<tr>
<th>Name</th>
<th>Level</th>
</tr>";


while($row = mysqli_fetch_array($result))
{
echo "<tr>";
$sql_b = 'SELECT * FROM USER_LIST WHERE usern = "'.$row['Name'].'"';
$res_b = mysqli_query($conn_users,$sql_b);
foreach($res_b as $value){
	$first = $value['firstname'];
	$last = $value['surname'];
}
$us = $row['Name'];
echo "<td>"; 
echo '<div class="tooltip">'.$us;
	if(strlen($first)>0){
  		echo '<span class="tooltiptext">'.$first.' '.$last;
  	}else{
  		echo '<span class="tooltiptext"> No Name!';}
echo "</td>";

echo "<td>" . $row['Level'] . "</td>";
echo "</tr>";
}

echo "</table>";
?>

<br>

</div>
</body>
</html>
