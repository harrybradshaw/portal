<html>
<body>

<?php

include_once 'includes/init.php';

$logout = true;
$return_path = '';
SetCookie ( 'webcalendar_login', '', 0 );
SetCookie ( 'webcalendar_last_view', '', 0 );
header("location:login.php?action=logout");
//header("location:week.php?");

?>

</body>
</html>

