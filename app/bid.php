<?php
include_once "include/common.php"; 

$course = $_GET['course'];
$username = 'amy.ng.2009';
$studentDAO =  new StudentDAO();
#retrieve student's edollar
$studentedollar = ($studentDAO->retrieve($username))->edollar;
echo "<p> You have $$studentedollar.</p>";

?>
