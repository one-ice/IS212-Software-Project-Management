<?php
    #page to update round table to show round 2!
    require_once 'common.php';

    $roundDAO = new RoundDAO();
    $round = $roundDAO->retrieveAll();
    
    $round = 2;
    $status = "active";

    $update_status = $roundDAO->updateRound($round,$status);
    if ($update_status){
        echo "Round 2 has started!";
    }
    echo "<a href = '../admin_homepage.php'> Back </a>"

?>