<?php
#page to update round table to show round 2!
require_once 'common.php';

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();

$roundNow = $roundObj->round;
$statusNow = $roundObj->status;
$status = "";

if ($statusNow == 'active'){
    if ($roundNow == 1){

        $status = 'round 1 is active, unable to start round 2';
    }
    elseif ($roundNow == 2){

        $status = 'round 2 is already active';
    }
    echo $status;
    echo "<a href = '../admin_homepage.php'> Back </a>"

}
else{
    if ($roundNow == 1){
        $roundDAO->updateRound(2, "active");
        $bidDAO = new BidDAO();
        $bidDAO->removeAll();

        $status = "success! round 2 started ";

    }
    elseif ($roundNow == 2){
        $status = "round 2 ended";
    }

    echo $status;
    echo "<a href = '../admin_homepage.php'> Back </a>";

}


?>