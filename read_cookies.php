<?php
     print_r($_COOKIE);    //output the contents of the cookie array variable
?>

<?php
$cookie_name = "DMGuser";
if(!isset($_COOKIE[$cookie_name])) {
     echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
     echo "Cookie '" . $cookie_name . "' is set!<br>";
     echo "Value is: " . $_COOKIE[$cookie_name];
}
?>