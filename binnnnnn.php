  //first test if name already exists
  //$sql = 'SELECT "usern" FROM USER_LIST';
  //echo $conn->query($sql);

  //$sql = 'INSERT INTO USER_LIST VALUES ('.$username.','.$password.')';
  //$conn->exec($sql);

  //header("location:createuser.php");


<?php
require_once 'edbconfig.php';

$dsn= "mysql:host=$host;dbname=$db";

try{
// create a PDO connection with the configuration data
$conn = new PDO($dsn, $username, $password);

}catch (PDOException $e){
// report error message
echo $e->getMessage();
}

$sql = 'SELECT COUNT(Name) FROM bigcryo';
$result = $conn->prepare($sql);
$result->execute();
$number_of_rows = $result->fetchColumn();

?>

<table style="width:100%">
  <tr>
    <th> Username </th>
    <th> User Level</th>
    <th> Actions</th>
  </tr>

  <?php
  for ($num = 0,$num == $number_of_rows,$num += 1){

  ?>

  <tr>
    <th> Test </th>
  </tr>

  <?php

  }
?>

</table>