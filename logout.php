<?php
// set the expiration date to one hour ago
unset($_COOKIE['DMGuser']);
unset($_COOKIE['DMGlogintime']);
setcookie("DMGuser", null, -1, '/');

setcookie("DMGlogintime", null, -1, '/');
echo 'logout';
header("location:unt.php");
?>