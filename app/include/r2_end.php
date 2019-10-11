<?php 
    require_once 'common.php';
    require_once 'clearing1.php';

    first_clearing();
    $roundDAO = new roundDAO();
    $bidDAO = new bidDAO;
    $bidDAO->removeAll();
    $round = 2;
    $status = "Inactive";

    $update_status = $roundDAO->updateStatus($round,$status);
    if ($update_status){
        echo "Round 2 has ended!";
    }
    echo "<a href = '../admin_homepage.php'> Back </a>";
?>