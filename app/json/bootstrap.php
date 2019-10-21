<?php
require_once '../include/bootstrap.php';
require_once "protect_json.php";
# complete bootstrap


if (sizeof($errors) > 0){    
    $result = [ 
        "status" => "error",
        "message" => $errors
    ];
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
}
else{
    doBootstrap();
}