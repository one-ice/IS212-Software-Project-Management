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
        $roundDAO->updateRound(1, "inactive");
        $result = [ 
            "status" => "success",
        ];
    }
    elseif ($roundNow == 2){
        $roundDAO->updateRound(2, "inactive");
        $result = [ 
            "status" => "success",
        ];
    }

}
else{
    if ($roundNow == 1){
        $round = 1;
    }
    elseif ($roundNow == 2){
        $round = 2;
    }
    $result = [ 
        "status" => "error",
        "message" => "round already ended"
    ];

}



header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);


?>