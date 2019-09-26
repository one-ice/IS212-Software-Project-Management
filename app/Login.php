<?php
// require_once 'include/common.php';
// require_once 'include/token.php';

$error = '';

if ( isset($_GET['error']) ) {
    $error = $_GET['error'];
} 
elseif ( isset($_POST['username']) && isset($_POST['password']) ) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username != "admin"){
        $dao = new studentDAO();
        $student = $dao->retrieve($username);
    
        if ( $student != null && $user->authenticate($password) ) {
            $_SESSION['username'] = $username; 
            header("Location: bidding.php");# change to boss bidding page!
            return;
    
        } else {
            $error = 'Incorrect username or password!';
        }
    }
    else{
        if($password == "password"){
            $_SESSION['username'] = $username; 
            header("Location: bootstrap.php"); #change to bootstrap page!
        }
        else{
            $error = 'Incorrect username or password!';
        }
    }
}



?>

<html>
    <head>            
        <link rel="stylesheet" type="text/css" href="loginstyle.css">   
    </head>
    
    <body>
    <div class="flex-wrap">
 
        <form action method='POST' action='Loginui.php'>
        
            <input type="radio" name="rg" id="Login" checked/>   

            <label for="Login">Welcome to BIOS!</label>

            <input name = 'username' class="Login" placeholder="Username" />
            <input name = 'password' class="Login" type="password" placeholder ="Password" />
            <button>Submit</button>        
            
        </form>
   
    </div>
    </body>
</html>

