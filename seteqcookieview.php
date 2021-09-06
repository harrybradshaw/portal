
<?php
$cookie_name = "eqpage";
$equipment = $_POST["eq"];
$month = $_POST["month"];
$year = $_POST["year"];
$refpage = $_POST["refpage"];
setcookie($cookie_name, $equipment, time() + 3600, '/');
$cookie_name = "eqmonth";
setcookie($cookie_name, $month, time() + 3600, '/');
$cookie_name = "eqyear";
setcookie($cookie_name, $year, time() + 3600, '/');
?>

<html>
<body>

<?php
echo 'hi';
echo $equipment;
echo $refpage;

if($refpage){
header("location:".$refpage);
}else{
header("location:viewuserlist.php");}
?>

</body>
</html>

