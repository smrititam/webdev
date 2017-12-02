<?php
session_start();
session_unset();
session_destroy();
echo "You have been successfully logged out!!";
?>
<a href="index.php"> Click here to log in again!</a>