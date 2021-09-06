<!DOCTYPE html>
<html>
<head>
<title>DMG Portal - Login</title>
<style>
  body {font-family: Arial, Helvetica, sans-serif;}

  .login_form{
    border: 3px solid #f1f1f1;
    margin: 0 auto;
    width:600px;
    padding: 16px;

  }



  h1 {
      text-align: center;
  }

  p.disc {
    text-align: center;
  }

  a.disc {
    text-align: center;
  }

  #link {
    display:block;
    text-align:center;
  }


  input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
  }

  button {
    background-color: #bc8ad4;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
  }

  button.raven{
    background-color: #1D6470;
  }

  button:hover {
    opacity: 0.8;
  }

  p.central{
    text-align: center;
  }

  .cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
  }

  .imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
  }

  img.avatar {
    width: 40%;
    border-radius: 50%;
  }

  .container {
    padding: 16px;
  }

  span.psw {
    float: right;
    padding-top: 16px;
  }

  .center {
    margin-left: auto;
    margin-right: auto;
  }

  /* Change styles for span and cancel button on extra small screens */
  @media screen and (max-width: 300px) {
    span.psw {
      display: block;
      float: none;
    }
    .cancelbtn {
      width: 100%;
    }
  }
</style>

</head>
<body>

 <?php
 setcookie(eqpage, 'AFM', time() + 3600, '/');
 if(isset($_COOKIE[DMGuser])) {
      header("location:../portal");
 }

 ?>

<h1>DMG Portal</h1>

<?php
$DFM = FALSE;

session_start();
$_SESSION['ref']="";

if($_GET['err'] == 1){
	echo '<p> Error: User does not exist! </p>';
}elseif($_GET['err']==2){
	echo '<p> Error: Password incorrect! </p>';
}

if($DFM==FALSE){
  
  echo '<div class="login_form">';
  echo '<form method="post" action="passval.php" id="loginform">';
  
      
        echo '<label for="uname"><b>Username</b></label>';
        echo '<input type="text" placeholder="Enter Username" name="uname" required>';

        echo '<label for="psw"><b>Password</b></label>';
        echo '<input type="password" placeholder="Enter Password" name="psw" required>';
            
        echo '<button type="submit">Login</button>';
        echo '<label>';
        echo '<input type="checkbox" name="personal"> Remember me';
        echo '</label>';
      
    echo '<input type="hidden" name="ref" value='.$_SESSION['ref'].'>';
    echo '</form>';
    echo '<br>';
    echo '<p class="central"><b>Or</b></p>';
    echo '<form action="raven_auth/index.php">';
    echo '<button type="submit" class="raven">Login with Raven</button>';   
    echo '</form>';
    echo '</div>';
    
  
  echo '<br>';
}else{
echo '<h1> DMG Portal is curently down for maintenance, come back soon. </h1>';
}
?>

<p class="disc"> Disclaimer: This website uses cookies to serve content in a tasty manner. <br> The personal data you enter is stored on a UCS managed webserver. </p>
<!--
<div id="link-container">
<a id="link" href="/../wiki/index.php/Main_Page">Forgotten Password</a>
</div>
<br>
-->
<div id="link-container">
<a id="link" href="ac_req">Request an Account</a>
</div>
<br>
<div id="link-container">
  <a id="link" href="/../wiki/index.php/Main_Page">Back to DMG Wiki</a>
  </div>
</body>
</html>