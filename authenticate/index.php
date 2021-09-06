<?php
session_start();

$time = $_SERVER['REQUEST_TIME'];

$timeout_duration = 60*5;

if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
}

/**
$_SESSION['LAST_ACTIVITY'] = $time;
*/

require('authenticate.php');


?>
<!DOCTYPE html>
<html lang="en">
<body>

<h1>User Page</h1>
<p> Welcome <?php
echo $_SESSION["user"]
?>! <br>
Seconds Left:

<?php
echo ($timeout_duration - ($time - $_SESSION['LAST_ACTIVITY']))
?>
</p>

</body>
</html>