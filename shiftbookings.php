<!DOCTYPE html>

<html>

<head>
<link rel = "stylesheet" href = "style.css">

</head>
<body>


<?php

include 'topbar.php';
$ref_page = $_POST['refpage'];

//echo '<label for="start">Shift from: </label>';
echo '<p> Shift from: ';
echo '<input type="date" id="start" name="trip-start"
       value="2018-07-22""> </p>';

echo '<form method="post" action="shiftbookings.php">';
echo '<input type="hidden" name="eq" value="'.$equipment.'"/>';
echo '<input type="hidden" name="refpage" value="manageuserlist.php"/>';
echo '<p> By ';
echo '<input type="number" name="numdays" min="1"/>';
echo ' days </p>';
echo "<input type='submit' value='Shift'>";
echo '</form>';


//header("location:".$ref_page);

?>

</body>
</html>