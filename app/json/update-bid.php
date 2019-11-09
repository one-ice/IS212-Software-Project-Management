<?php
require_once "../include/common.php";
require_once "../include/BidValidationFn.php";
require_once "protect_json.php";

$check = [];

$user_bid = $_GET['r'];
$json_decoded = json_decode($user_bid, true);
$fields = ['amount','course','section','userid' ];

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
else{
    $userid = trim($json_decoded['userid']);
    $amount = trim($json_decoded['amount']);
    $course = trim($json_decoded['course']);
    $section = trim($json_decoded['section']);
    
    $roundDAO = new RoundDAO();
    $round = $roundDAO->retrieveAll();

    $errors = [];
    $dataArray = [$userid, $amount, $course, $section, $round];
    $errors = bidValidation($dataArray);

    if (sizeof($errors) > 0){
        sort($errors);
        $result = [ 
            "status" => "error",
            "message" => $errors
        ];
        header('Content-Type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    else{
        $result = [
            'status' => "success"
        ];
        
        header('Content-Type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);
    }

}



?>