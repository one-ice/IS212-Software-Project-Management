<?php 
require_once 'common.php';
require_once 'clearing1.php';

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();

$bidDAO = new BidDAO();
$bidDAO->removeAll();

$roundNow = $roundObj->round;
$statusNow = $roundObj->status;

if ($statusNow == 'inactive'){
    if ($roundNow == 1){
        $round = 1;
        $status = 'round 1 is already inactive';
    }
    elseif ($roundNow == 2){
        $round = 2;
        $status = 'round 2 is already inactive';
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
       
        $result = [ 
            "status" => "error, round 2 not started"
        ];

    }
    elseif ($roundNow == 2){
        first_clearing();

        $roundDAO->updateRound(2, "inactive");
        
        $result = [ 
            "status" => "success! round 2 ended",
        ];
    }

    echo $result;
    echo "<a href = '../admin_homepage.php'> Back </a>";

}


    
?>