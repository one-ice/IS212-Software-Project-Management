<?php
require_once 'token.php';
require_once 'common.php';

$username = '';
if  (!isset($_SESSION['username'])) {
  header("Location: Login.php");
  exit;
}

# check if the username session variable has been set 
# send user back to the login page with the appropriate message if it was not

$pathSegments = explode('/',$_SERVER['PHP_SELF']); # Current url
$numSegment = count($pathSegments);
$page = $pathSegments[$numSegment -1];

# add your code here 
$username = $_SESSION['username'];

if ($page == 'admin_homepage.php'){
  if ($username != 'admin'){
    header("Location: bidhome.php");
    exit;
  }
}
else{
  if ($username == 'admin'){
    header('Location: admin_homepage.php');
  }
}

?>