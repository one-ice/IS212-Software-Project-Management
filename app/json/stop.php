<?php
include_once "../include/common.php"; 
require_once "../include/clearing1.php";
require_once "protect_json.php";


if (isset($errors)){    
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

$round = 0;
$status = "";

if ($statusNow == 'active'){
    if ($roundNow == 1){ 
        $roundDAO->updateRound(1, "inactive");
        $result = [ 
            "status" => "success",
        ];

        first_clearing();
    }
    elseif ($roundNow == 2){
        $roundDAO->updateRound(2, "inactive");
        $result = [ 
            "status" => "success",
        ];

        first_clearing();
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
