<?php
    #page to update round table to show round 2!
    require_once 'common.php';

    $roundDAO = new RoundDAO();

    $round = 2;
    $status = "Active";

    $update_status = $roundDAO->updateRound($round,$status);
    if ($update_status){
        echo "Round 2 has started!";
    }
    echo "<a href = '../admin_homepage.php'> Back </a>"

?>