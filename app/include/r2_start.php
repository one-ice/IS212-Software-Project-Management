<?php
#page to update round table to show round 2!
require_once 'common.php';

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();

$roundNow = $roundObj->round;
$statusNow = $roundObj->status;

if ($statusNow == 'active'){
    if ($roundNow == 1){
        $round = 1;
        $status = 'round 1 is active, unable to start round 2';
    }
    elseif ($roundNow == 2){
        $round = 2;
        $status = 'round 2 is already active';
    }

    $result = [ 
        "error" => $status,
        "current round" => $round
    ];

    echo $result;
    echo "<a href = '../admin_homepage.php'> Back </a>"

}
else{
    if ($roundNow == 1){
        $roundDAO->updateRound(2, "active");
        $bidDAO = new BidDAO();
        $bidDAO->removeAll();

        $result = [ 
            "status" => "success! round 2 started ",
            "current round" => 2
        ];

    }
    elseif ($roundNow == 2){

        $result = [ 
            "status" => "error",
            "message" => [ "round 2 ended" ]
        ];
    }

    echo $result;
    echo "<a href = '../admin_homepage.php'> Back </a>";

}


?>