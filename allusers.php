<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" href = "/portal/style.css?v=<?=time();?>">
<title>DMG Portal - All Users</title>
</head>

<body>

<?php

session_start();
$_SESSION['ref'] = "allusers.php";
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

<div class="bulk">

<br>
<?php

$sql = 'SELECT * FROM USER_LIST ORDER BY usern';
$result = $conn_users->query($sql);
$all_emails = '';

echo "<table style='width:100%' id='customers'>";
echo "<tr>";

foreach($result as $value){
echo "<td>" . $value['usern'] . "</td>";
echo "<td>" . $value['firstname'] . "</td>";
echo "<td>" . $value['surname'] . "</td>";
echo "<td>" . $value['email'] . "</td>";

if($value['email'] != Null){
$all_emails .= $value['email'].',';
}
echo "<td>" . $value['Last_Active'] . "</td>";
echo "<td>" . $value['role'] . "</td>";
echo "<td>";

//Make Admin/User section
if($value['role']=='user'){
echo '<form method="post" action="changeuserrole.php")>';
echo '<input type="hidden" name="usern" value="'.$value["usern"].'"/>';
echo '<input type="hidden" name="newrole" value="admin"/>';
echo "<input type='submit' value='Make Admin' style='width:100%'>";
echo '</form>';
}else{
echo '<form method="post" action="changeuserrole.php")>';
echo '<input type="hidden" name="usern" value="'.$value["usern"].'"/>';
echo '<input type="hidden" name="newrole" value="user"/>';
echo "<input type='submit' value='Make User' style='width:100%'>";
echo '</form>';
}
echo "</td>";

//Delete user section
echo "<td>";
echo '<form method="post" action="confirmdelete.php")>';
echo '<input type="hidden" name="del_user" value="'.$value["usern"].'"/>';
echo '<input type="hidden" name="refpage" value="allusers.php"/>';
echo '<input type="hidden" name="act" value="delete"/>';
echo "<input type='submit' value='Delete User' style='width:100%'>";
echo '</form>';
echo "</td>";

//Password reset section
echo "<td>";
echo '<form method="post" action="confirmdelete.php">';
echo '<input type="hidden" name="reset_user" value="'.$value["usern"].'"/>';
echo '<input type="hidden" name="refpage" value="allusers.php"/>';
echo '<input type="hidden" name="act" value="passreset"/>';
echo "<input type='submit' value='Reset Password' style='width:100%'>";
echo '</form>';
echo "</td>";

echo "</tr>";
}
echo "</table>";

echo '<br><a href="createuser.php" > Create New User</a><br>';
echo '<a href = "mailto:'.$all_emails.'"> Email All Users </a>';
?>
</div>
</body>
</html>
