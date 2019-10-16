<?php 
    require_once 'common.php';
    require_once 'clearing1.php';
    $roundDAO = new RoundDAO();
    $round = $roundDAO->retrieveAll();

    if ( ($round->round == 1) && ($round->status == 'active') ){
        first_clearing();
        $roundDAO = new RoundDAO();
        $bidDAO = new BidDAO();
        $bidDAO->removeAll();
        $round = 1;
        $status = "inactive";

        
        $update_status = $roundDAO->updateStatus($round,$status);
        if ($update_status){
            echo "Round 1 has ended!";
        }
        echo "<a href = '../admin_homepage.php'> Back </a>";
    }
    else{
        echo "round already ended";
        echo "<a href = '../admin_homepage.php'> Back </a>";
    }

    
?>