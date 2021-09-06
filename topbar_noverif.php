<!DOCTYPE html>


<?php
function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a d, %h h, %i m and %s s left');
}


include 'db_connect.php';
$result = $conn_users->query("SELECT * FROM USER_LIST WHERE usern = '".$_COOKIE[DMGuser]."'");
foreach($result as $value){
	$firstname = $value['firstname'];
}

$timeleft = '';
if(isset($_COOKIE[DMGlogintime])) {
	 $delt = $_COOKIE[DMGlogintime] - time();
     $timeleft = ' ('.secondsToTime($delt).')';
}

?>
<html>
<body style="padding:50px;">
<table style="border-collapse: collapse; width:100%;height:39px;">
  <tr style="border-bottom: 2px solid #dddddd;text-align: center;border-top: 2px solid #dddddd;">
    <th style="font-size:16pt; width:25%;font-family:Calibri,Helvetica, Arial, sans-serif;">Welcome! </th>
    <th style="width:25%;"> <a href="/portal/main.php" style="color: #0645AD;font-size:16pt;font-family:Calibri, Helvetica, Arial, sans-serif;">Home</a></th>
    <th style="width:25%;"> <a href="/portal/admintools.php" style="color: #0645AD;font-size:16pt;font-family:Calibri, Helvetica, Arial, sans-serif;">Admin Tools</a></th>
    <th style="width:25%;"> <a href="/portal/unt.php" style="color: #0645AD;font-size:16pt;font-family:Calibri, Helvetica, Arial, sans-serif;"> Log In</a></th>
  </tr>
</table>
<br>
</body>
</html>