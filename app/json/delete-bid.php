<?php
include_once "../include/common.php"; 
require_once "protect_json.php";

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();
$roundNow = $roundObj->round;
$statusNow = $roundObj->status;

$check = [];

$user_bid = $_GET['r'];
$json_decoded = json_decode($user_bid, true);
$fields = ['course','section','userid'];

foreach ($json_decoded as $key => $value){
    $check[] = $key;
        
    if ($value == ""){
        $errors[] = 'blank '. $key;
    }
}
foreach ($fields as $things){
    if (!in_array($things, $check)){
        $errors[] = 'missing ' . $things;
    }
}

if (sizeof($errors) > 0){ 

    $result = [ 
        "status" => "error",
        "message" => $errors
    ];
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
}


?>