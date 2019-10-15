<?php 
    require_once 'common.php';
    require_once 'clearing1.php';

    first_clearing();
    $roundDAO = new roundDAO();
    $bidDAO = new bidDAO;
    $bidDAO->removeAll();
    $round = 1;
    $status = "inactive";

    $update_status = $roundDAO->updateStatus($round,$status);
    if ($update_status){
        echo "Round 1 has ended!";
    }
    echo "<a href = '../admin_homepage.php'> Back </a>";
?>