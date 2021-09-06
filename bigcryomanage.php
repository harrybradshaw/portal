<!DOCTYPE html>
<html>
<body>

<?php
include 'topbar.php';
?>

<header>
<h1>Big Cryo</h1>
</header>

<?php
include 'db_connect.php';

$result = mysqli_query($conn_equipt,"SELECT * FROM bigcryo");

function create_menus($name,$level){

if ($level == 'superuser'){
$menu = '<select name="newlev"> <option value="M">Promote to manager</option> <option value="B">Demote to B user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';
}elseif($level == 'B user'){
$menu = '<select name="newlev"> <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="C">Demote to C user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}elseif($level == 'C user'){
$menu = '<select name="newlev"> <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="B">Promote to B user</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}elseif($level == 'Trainee'){
$menu = '<select name="newlev"> <option value="M">Promote to manager</option> <option value="A">Promote to A user</option> <option value="B">Promote to B</option><option value="C">Promote to C</option><option value="REM">Remove</option></select>';

}
elseif($level == 'Manager'){
$menu = '<select name="newlev"> <option value="A">Demote to A</option> <option value="B">Demote to B</option> <option value="C">Demote to C</option><option value="T">Demote to Trainee</option><option value="REM">Remove</option></select>';

}
return $menu;
}

echo "<table style='width:15%'>
<tr>
<th>Name</th>
<th>Level</th>
<th>Action</th>
</tr>";

while($row = mysqli_fetch_array($result))
{

echo "<tr>";
echo "<td>" . $row['Name'] . "</td>";
$us = $row['Name'];
echo "<td>" . $row['Level'] . "</td>";
echo '<form method="post" action="edituserl.php">';
echo '<input type="hidden" name="eq" value="bigcryo"/>';
echo '<input type="hidden" name="pager" value="bigcryomanage.php"/>';
echo '<input type="hidden" name="user" value="'.$us.'"/>';
echo "<td>" . create_menus($row['Name'],$row['Level']) . "</td>";
echo "<td> <input type='submit' value='Update'> </td>";
echo '</form>';
echo "</tr>";
}
echo "</table>";

?>

<br>

<?php

function popu(){
include 'db_connect.php';
$queryUsers = "SELECT * FROM USER_LIST";
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

echo '<form method="post" action="edituserlevel.php">';
echo '<input type="hidden" name="eq" value="bigcryo"/>';
echo '<input type="hidden" name="pager" value="bigcryomanage.php"/>';
echo "<p> Add ". popu()." as a Trainee <input type='submit' value='Submit'></p>";
echo '</form>';


?>

</body>
</html>
