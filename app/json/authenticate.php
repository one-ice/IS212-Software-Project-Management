<?php

require_once '../include/common.php';
require_once '../include/token.php';


// isMissingOrEmpty(...) is in common.php
$errors = [ isMissingOrEmpty ('username'), 
            isMissingOrEmpty ('password') ];
$errors = array_filter($errors);


if (!isEmpty($errors)) {
    $result = [
        "status" => "error",
        "messages" => array_values($errors)
        ];
}
else{
    $userid = $_POST['username'];
    $password = $_POST['password'];

    $studentDao = new StudentDAO();
    $student = $studentDao->retrieve($userid);
    
    if($student != null && $student->authenticate($password)){
        $generatedToken = generate_token($userid);
        $result = [
            "status" => "success",
            "token" => $generatedToken,
        ];
    }else{
        $result = [
            "status" => "error",
            "messages" => array_values(["invalid username/password"])
        ];
    }

}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);

?>