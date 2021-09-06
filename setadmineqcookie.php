
<!DOCTYPE html>

<?php
$cookie_name = "eqpage";
$equipment = $_POST["eq"];
setcookie($cookie_name, $equipment, time() + 3600, '/');

$cookie_name = "eqrights";
$equipment = 'admin';
setcookie($cookie_name, $equipment, time() + 3600, '/');
?>

<html>
<body>

<?php
echo 'hi';
header("location:adminuserlist");
?>

</body>
</html>

