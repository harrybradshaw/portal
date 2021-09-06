<?php
require_once('authenticate.php');

$name = null;
$date = date('c');
$desc = null;
$email = null;
$priority = 2;
$color = '#ffffff';

$result = array();
$result['error'] = array();

if (!empty($_POST["new-task-name"]))
    $name = $_POST["new-task-name"];
else 
    array_push($result['error'], 'Please specify a name for your task');

if (!empty($_POST["new-task-date"]))
    $date = new DateTime($_POST["new-task-date"]);
else 
    array_push($result['error'], 'Please specify a date for your task');

if (!empty($_POST["new-task-desc"]))
    $desc = $_POST["new-task-desc"];

if (!empty($_POST["new-task-email"]))
    $email = explode(',', $_POST["new-task-email"]);

if (!empty($_POST["new-task-priority"]))
    $priority = intval($_POST["new-task-priority"]);
else 
    array_push($result['error'], 'Please specify a valid priority for your task');

if (!empty($_POST["new-task-color"]))
    $color = $_POST["new-task-color"];

if(isset($result['error']) && count($result['error']) > 0){
    $result['success'] = false;
} else {
    $result['success'] = true;
    $result['name'] = $name;
    $result['date'] = $date->format('c');
    $result['desc'] = $desc;
    $result['email'] = implode(',', $email);
    $result['priority'] = $priority;
    $result['color'] = $color;
}

echo json_encode($result);
?>