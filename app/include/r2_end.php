<?php 
require_once 'common.php';
require_once 'clearing1.php';

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();

$bidDAO = new BidDAO();
$bidDAO->removeAll();

$roundNow = $roundObj->round;
$statusNow = $roundObj->status;

$status = "";

if ($statusNow == 'inactive'){
    if ($roundNow == 1){
        $status = 'round 1 is already inactive';
    }
    elseif ($roundNow == 2){
        $status = 'round 2 is already inactive';
    }


    echo $status;
    echo "<a href = '../admin_homepage.php'> Back </a>"

}
else{
    
    if ($roundNow == 1){
        $status = "error, round 2 not started";
    }
    elseif ($roundNow == 2){
        first_clearing();

        $roundDAO->updateRound(2, "inactive");
        $status = "success! round 2 ended";
    }

    echo $status;
    echo "<a href = '../admin_homepage.php'> Back </a>";

}


    
?>