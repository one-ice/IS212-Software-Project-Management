<?php 
require_once 'common.php';
require_once 'clearing1.php';

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();

$roundNow = $roundObj->round;
$statusNow = $roundObj->status;
$status = "";

if ($statusNow == 'inactive'){
    if ($roundNow == 1){
        $round = 1;
        $status = 'round 1 is already inactive';
    }
    elseif ($roundNow == 2){
        $round = 2;
        $status = 'round 2 is already inactive';
    }

    
    echo $status;
    echo "<a href = '../admin_homepage.php'> Back </a>";

}
else{


    if ($roundNow == 1){
        first_clearing();
        $roundDAO->updateRound(1, "inactive");
 
        $status =  "status: success! round 1 ended " ;
    }
    elseif ($roundNow == 2){

        $status = "round 2 not started" ;
    }

    echo $status;
    echo "<a href = '../admin_homepage.php'> Back </a>";

}


?>