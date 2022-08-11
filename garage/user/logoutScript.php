<?php
require_once "../config.php";
session_start();

unset($_SESSION["currentUser"]);

//echo '<script type="text/javascript">window.location="../homepage.php"</script>';
header("Location: login.php");
?>