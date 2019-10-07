<?php
session_start();
$_SESSION['round'] = 2;

header("Location:admin_homepage.php")
?>