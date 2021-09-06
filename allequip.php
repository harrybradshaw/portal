<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<title>DMG Portal - All Equipment</title>
</head>

<body>

<?php

session_start();
$_SESSION['ref'] = "allequip.php";
include 'topbar.php';
include 'db_connect.php';

$username = $_COOKIE[DMGuser];

$result = $conn_users->query("SELECT role FROM USER_LIST WHERE usern = '".$username."'");

foreach($result as $value){
    if($value['role'] != 'admin'){
         header("location:main.php");
    }
}

?>


<br>

<?php

$sql = 'SELECT * FROM EQ_LIST ORDER BY fullname';
$result = $conn_equipt->query($sql);
echo '<div class="bulk">';
echo "<table style='width:70%' id='customers'>";
echo "<tr>";

foreach($result as $value){
echo "<td>" . $value['fullname'] . "</td>";
echo "<td>" . $value['abbrev'] . "</td>";
echo "<td>" . $value['RA'] . "</td>";

if(empty( $value['Room'])){
    echo "<td>No room!</td>";
}else{
    echo "<td>" . $value['Room'] . "</td>";

}
echo "<td>";

echo '<form method="post" action="setadmineqcookie.php">';
echo '<input type="hidden" name="eq" value="'.$value["abbrev"].'">';
echo "<input type='submit' value='Manage' style='width:100%'>";
echo '</form>';
echo "</td>";

if($value['bookable']!= '0'){
echo '<td>';
echo '<form method="post" action="webcal/login.php">';
//echo '<form method="post" action="webcal/newacc.php">';

echo '<input type="hidden" name="eq" value="'.$value["abbrev"].'">';

/* if(($value["abbrev"] == "MiniHe3")||($value["abbrev"] == "MiniHe4")){
    echo '<input type="hidden" name="login" value="mini"/>';
}elseif (($value["abbrev"] == "TcR")||($value["abbrev"] == "NewP")){
    echo '<input type="hidden" name="login" value="dewar"/>';
}else{
    echo '<input type="hidden" name="login" value="'.$value["abbrev"].'"/>';
}*/

echo '<input type="hidden" name="login" value="'.$value["abbrev"].'">';
echo '<input type="hidden" name="password" value="admin">';
echo "<input type='submit' value='Booking' style='width:100%'>";
echo '</form>';
echo ' </td>';
}else{
echo "<td></td>";
}

echo "<td>";
echo '<form method="post" action="confirmdelete.php")>';
echo '<input type="hidden" name="del_eq" value="'.$value["abbrev"].'">';
echo '<input type="hidden" name="act" value="delete">';
echo '<input type="hidden" name="refpage" value="allequip.php">';
echo "<input type='submit' value='Delete Equipment' style='width:100%'>";
echo '</form>';
echo "</td>";

echo "</tr>";



}
echo "</table>";
echo '<br><a href="createequip.php"> Create New Equipment</a>';

?>

</div>
</body>
</html>
