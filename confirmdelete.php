<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" href = "style.css">
</head>

<body>

<?php
include 'topbar.php';
include 'db_connect.php';

$refpage = $_POST['refpage'];

echo '<h2> Confirm Delete </h2>';

if($refpage=='allusers.php'){
    if($_POST['act']=='passreset'){
        echo '<p> You have chosen to password reset the user "'.$_POST["reset_user"].'".<br> Confirm?</p>';
        echo '<form method="post" action="changepassscript.php")>';
        echo '<input type="hidden" name="reset_user" value="'.$_POST["reset_user"].'"/>';
        echo '<input type="hidden" name="refpage" value="allusers.php"/>';
        echo "<input type='submit' value='Reset Password'>";
        echo '</form>';
    }elseif ($_POST['act']=='delete'){
        echo '<p> You have chosen to delete the user "'.$_POST["del_user"].'".<br> Confirm?</p>';
        echo '<form method="post" action="deleteuserscript.php")>';
        echo '<input type="hidden" name="del_user" value="'.$_POST["del_user"].'"/>';
        echo '<input type="hidden" name="refpage" value="allusers.php"/>';
        echo "<input type='submit' value='Delete User'>";
        echo '</form>';
    }
}elseif($refpage=='allequip.php'){
    if($_POST['act']=='delete'){
        echo '<p> You have chosen to delete the equipment "'.$_POST["del_eq"].'". Confirm ?</p>';
        echo '<form method="post" action="deleteeqscript.php")>';
        echo '<input type="hidden" name="del_eq" value="'.$_POST["del_eq"].'"/>';
        echo '<input type="hidden" name="refpage" value="allequip.php"/>';
        echo "<input type='submit' value='Delete Equipment'>";
        echo '</form>';
    }
}

?>


</body>
</html>