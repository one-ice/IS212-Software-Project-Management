<?php
    require_once 'include/common.php';
    $error1 = "";
    $error2 = "";
    $details = "";

    if(isset($_POST["username"]) && isset($_POST["password"])){
        $data = array(
      'username' => $_POST["username"],
      'password' => $_POST["password"]
        );
        
        $result = curl_result("authenticate.php", $data);
        if ($result["status"] == 'success'){
            session_start();
            $_SESSION["username"] = $_POST["userid"];
            $_SESSION["token"] = $result["token"];
            if($_POST["userid"] == "admin"){
                header("Location: bootstrap.php");
            }else{
                header("Location: bidhome.php");
            }
            
        }else{
            $messages = $result["messages"];
            if(sizeof($messages) == 2){
                $error1 = $messages[0];
                $error2 = $messages[1];
            }else{
            // Else 1 error message
                $error2 = $messages[0];
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
 
        <form action method='POST' action='Login.php'>
        
            <input type="radio" name="rg" id="Login" checked/>   

            <label for="Login">Welcome to BIOS!</label>

            <input name = 'username' class="Login" placeholder="Username" />
            <p id = 'error1'> <?= $error1 ?></p>
            <input name = 'password' class="Login" type="password" placeholder ="Password" />
            <p id = 'error2'> <?= $error2 ?></p>
            <button>Submit</button>        
            
        </form>
   
    </div>
    </body>
</html>

