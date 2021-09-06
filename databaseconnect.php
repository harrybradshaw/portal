<?php

require_once 'dbconfig.php';

$dsn= "mysql:host=$host;dbname=$db";

try{
 // create a PDO connection with the configuration data
 $conn = new PDO($dsn, $username, $password);

 // display a message if connected to database successfully
 if($conn){
 echo "Connected to the <strong>$db</strong> database successfully!";
        }
}catch (PDOException $e){
 // report error message
 echo $e->getMessage();
}


$sql = 'SELECT * FROM hlb54';
foreach ($conn->query($sql) as $row) {
    print $row['Equipment_Name'] . "\t";
    print $row['User_Level'] . "\n";
}
?>

