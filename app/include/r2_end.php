<?php 
    require_once 'common.php';
    $roundDAO = new roundDAO();

    $round = 2;
    $status = "Inactive";

    $update_status = $roundDAO->updateStatus($round,$status);
    if ($update_status){
        echo "Round 2 has started!";
    }
    echo "<a href = '../admin_homepage.php'> Back </a>";
?>