<!DOCTYPE html>

<?php
function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a d, %h h, %i m and %s s left');
}

if(!isset($_COOKIE[DMGuser])) {
     header("location:/portal/unt.php");
}

include 'db_connect.php';
$result = $conn_users->query("SELECT * FROM USER_LIST WHERE usern = '".$_COOKIE[DMGuser]."'");
foreach($result as $value){
  $firstname = $value['firstname'];
  $is_admin = true;
}

$timeleft = '';
if(isset($_COOKIE[DMGlogintime])) {
	 $delt = $_COOKIE[DMGlogintime] - time();
     $timeleft = ' ('.secondsToTime($delt).')';
}

?>
<html>
<head>
<meta charset="iso-8859-1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  body { padding: 0; margin: 0; }
    ul.tb {
      /*border: 1px solid #e7e7e7;*/
      background-color: #bc8ad4;
      list-style-type: none;
      margin: 0;
      padding: 0;
      position: fixed;
      top: 0;
      width: 100%;
      z-index:3;
    }
    
    li.tb {
      float: left;
    }
    
    li.tb a.tb, .dropbtn {
      display: inline-block;
      color: white;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }
    
    li.tb a.tb:hover, .dropdown:hover .dropbtn {
      background-color: #9966ff;
    }
    
    li.dropdown {
      display: inline-block;
    }
    
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }
    
    .dropdown-content a.tb {
      color: #9966ff;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }
    
    .dropdown-content a.tb:hover {background-color: #f1f1f1;}
    
    .dropdown:hover .dropdown-content {
      display: block;
    }

    .navbar {
      overflow: hidden;
      background-color: rgb(114, 36, 145);
      font-family: Arial;
    }
    
    /* Links inside the navbar */
    .navbar a.tb {
      float: left;
      font-size: 16px;
      color: white;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }
    
    /* The dropdown container */
    .dropdown {
      float: left;
      overflow: hidden;
    }
    
    /* Dropdown button */
    .dropdown .dropbtn {
      font-size: 16px;
      border: none;
      outline: none;
      color: white;
      padding: 14px 16px;
      background-color: inherit;
      font-family: inherit; /* Important for vertical align on mobile phones */
      margin: 0; /* Important for vertical align on mobile phones */
    }
    
    /* Add a red background color to navbar links on hover */
    .navbar a.tb:hover, .dropdown:hover .dropbtn {
      background-color: #9966ff;
    }
    
    /* Dropdown content (hidden by default) */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }
    
    /* Links inside the dropdown */
    .dropdown-content a.tb {
      float: none;
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }
    
    /* Add a grey background color to dropdown links on hover */
    .dropdown-content a.tb:hover {
      background-color: #ddd;
    }
    
    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
      display: block;
    }
</style>
</head>
<body>
<?php

$sql = "SELECT * FROM ActionItems WHERE actiontype = 'eqreq'";
$result = $conn_equipt->query($sql);
$eqreq_tot = mysqli_num_rows($result);
$req_num = 0;
$eqreq_tot = 0;
foreach($result as $value){
  $eq = $value['whoto'];
  $sql_a = "SELECT Name FROM ".$eq." WHERE Level = 'Manager' AND Name = '".$_COOKIE['DMGuser']."'";
  $result_a = $conn_equipt->query($sql_a);
  $eqreq_tot += mysqli_num_rows($result_a);
}
if($is_admin){
  $sql_a = "SELECT * FROM ActionItems WHERE actiontype = 'usreq'";
  $result_a = $conn_equipt->query($sql_a);
  $usreq_tot = mysqli_num_rows($result_a);
}
$notif_tot = $usreq_tot + $eqreq_tot;
?>
<div class='navbar'>
  <ul class='tb'>
  <li class='tb'><a class='tb' >DMG Portal</a></li>
    <li class='tb'><a class='tb' href="/portal/">Home</a></li>
    <li class='tb'><a class='tb' href="/portal/notifications">Action Items <?php echo '('.$notif_tot.')' ?></a></li>
    <?php if($is_admin){
          ?>
    <div class="dropdown">
      <button class="dropbtn">Admin Tools
        <i class="fa fa-caret-down"></i>
      </button>

      <div class="dropdown-content">
           <a class='tb' href="/portal/allequip">All Equipment</a>
        <a class='tb' href="/portal/allusers">All Users</a>
        <a class='tb' href="/portal/createuser">Create New User</a>
        <a class='tb' href="/portal/createequip">Create New Equipment</a>
        <a class='tb' href="/portal/view_usage">View Equipment Usage</a>
          
      </div>
    </div>
    <?php
        }
        ?>
    <div class="dropdown" style="float:right">
    <button class="dropbtn"><?php if($firstname){echo $firstname.' ('.$_COOKIE['DMGuser'].')';}else{echo $_COOKIE['DMGuser'];}?>
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content">
        <a class='tb' href="/portal/changepass">Edit My Info</a>
        <a class='tb' href="/portal/logout">Log Out</a>
      </div>
      </div>
  </ul>
</div>
<br>
</body>
</html>