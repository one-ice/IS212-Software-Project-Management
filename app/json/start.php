<?php
include_once "../include/common.php"; 
require_once "protect_json.php";

if (sizeof($errors) > 0){    
    $result = [ 
        "status" => "error",
        "message" => $errors
    ];
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
}

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();

$roundNow = $roundObj->round;
$statusNow = $roundObj->status;

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
    if ($roundNow == 0){

        $roundDAO->updateRound(1, "active");
   
        $result = [ 
            "status" => "success",
            "round" => 1
        ];
    }
    elseif ($roundNow == 1){
        $roundDAO->updateRound(2, "active");
        $bidDAO = new BidDAO();
        $bidDAO->removeAll();
        
        $result = [ 
            "status" => "success",
            "round" => 2
        ];

    }
    elseif ($roundNow == 2){

        $result = [ 
            "status" => "error",
            "message" => [ "round 2 ended" ]
        ];
    }

}



header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);



?>
