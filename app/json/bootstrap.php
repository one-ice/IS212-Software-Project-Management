<?php
require_once '../include/bootstrap.php';
if(!isset($_POST['token'])){ //accessed by the website by itself.
    echo "
<form action='bootstrap.php'  method='post' enctype='multipart/form-data'>
  File:
  <input type='file' name='bootstrap-file' /><br />
  <input type='text' name='token' value='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImFkbWluIiwiZGF0ZXRpbWUiOiIyMDE5LTEwLTE1IDE1OjM3OjA3In0.K5dDEd1O3CeRsHnBjLn6lsaXjTfBd2Aahoh3IqJAt7Y' />
  <!-- substitute the above value with a valid token -->
  <input type='submit' name = 'submit' value='Bootstrap' />
</form>";
}
else{ //accessed by either jsonchecker or after ^ submits
    if(isset($_POST['submit'])){ //if submitted via the website, then it will change the token to be whatever the user keyed in.
        $token = $_POST['token'];
    }
    require_once 'protect_json.php'; //checks the token
    doBootstrap(); // if correct then do the bootstrap
}

?>