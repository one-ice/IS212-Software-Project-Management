<?php
require_once 'token.php';
require_once 'common.php';

$username = '';
if  (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
}

# check if the username session variable has been set 
# send user back to the login page with the appropriate message if it was not

# add your code here 
else{
	header("Location: login.php?error=Please login");
	exit;
}
?>