<?php
include_once "../include/common.php"; 

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();


$roundNow = $roundObj->round;
$statusNow = $roundObj->status;

$round = 0;
$status = "";

if ($statusNow == 'active'){
    if ($roundNow == 1){
        $round = 1;
    }
    elseif ($roundNow == 2){
        $round = 2;
    }
    $status = "success";

    $result = [ 
        "status" => $status,
        "round" => $round
    ];

}
else{
    if ($roundNow == 1){
        $round = 1;
    }
    elseif ($roundNow == 2){
        $round = 2;
    }
    
    $status = "error";
    
    $result = [ 
        "status" => $status,
        "message" => "round $round ended"
    ];

}



header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);



?>
