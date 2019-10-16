<?php
spl_autoload_register(function($class){
    require_once "include/$class.php"; 
});
include_once "meetCriteria.php";
require_once 'include/clearing2.php';
// session_start();
# round 0, round 1 active, round 1 inactive, round 2 active, round 2 inactive
# in round 0, students can only see "not avalible"
# in round 1 active, all the status of courses should be "pending" (retrieve from "bidDAO")
# in round 1 inactive and round 2 inactive, stu can see "successful" and "unsuccessful" (retrieve from sectionStudent and fail_bid)
# in round 2 active, student can see "successful" "unsuccessful"
$roundDAO = new RoundDAO();
$roundR= $roundDAO->retrieveAll();
$round = $roundR->round;
$status = $roundR->status;
// echo $round;
// echo $status;

    # retrieve the courses the user successfully bid
    $sectionStuDAO = new SectionStudentDAO();
    $successInfo = $sectionStuDAO->retrieveByUserID($_SESSION['username']);

    # retrieve the courses the user fail to bid
    $failBidDAO = new Fail_BidDAO();
    $failBidInfo = $failBidDAO->retrieveByUserID($_SESSION['username']);

    # retrieve from bid table -- pending
    $BidDAO = new BidDAO();
    $pendingInfo = $BidDAO->retrieve($_SESSION["username"]);
    
    echo $_SESSION['username'];
    echo "</br></br>";

# showing the status in each round and status
if($round == 0){
    echo "not avalible";
}elseif($round == 1 && $status == "active" && empty($pendingInfo)){
    echo "<br/>you haven't bidded a course";
}elseif($round == 1 && $status == "inactive" && empty($successInfo)){
    echo "<br/>you haven't bidded a course";
}elseif($round == 2 && $status == "active" && empty($pendingInfo) && empty($failBidInfo) && empty($successIno)){
    // var_dump($successInfo);
    echo "<br/>you haven't bidded a course";
}
elseif($round == 1 && $status == "active"){
    echo "<html><body><table border = '1'>
    <tr><td>Course</td>
        <td>Section</td>
        <td>Bid Amount</td>
        <td>Status</td></tr>";
    foreach ($pendingInfo as $pending){
        if ($pending->status == "pending"){
            echo"<tr><td>{$pending->code}</td>
                <td>{$pending->section}</td>
                <td>{$pending->amount}</td>
                <td>pending</td>
            </tr>";
        }
    }
    echo "</table></body></html>";
}elseif($round == 2 && $status == "active"){
    #the first table showed in round2 active, showing the round 1 successfully bided
    echo "round1 result:";
    echo "<html><body><table border = '1'>
    <tr><td>Course</td>
        <td>Section</td>
        <td>Bid Amount</td>
        <td>Status</td></tr>";
        if($successInfo){
            foreach ($successInfo as $success){
                echo"<tr><td>{$success->code}</td>
                    <td>{$success->section}</td>
                    <td>{$success->amount}</td>
                    <td>success</td>
                </tr>";
            }
        }
        if($failBidInfo){
            foreach ($failBidInfo as $fail){
                echo"<tr><td>{$fail->code}</td>
                    <td>{$fail->section}</td>
                    <td>{$fail->amount}</td>
                    <td>fail</td>
                </tr>";
            }
        }

    echo "</table></body></html>";
    
}elseif(($round == 1 && $status == "inactive") || ($round == 2 && $status == "inactive")){

    echo "<html><body><table border = '1'>
    <tr><td>Course</td>
        <td>Section</td>
        <td>Bid Amount</td>
        <td>Status</td></tr>";
    if($successInfo){
        foreach ($successInfo as $success){
            echo"<tr><td>{$success->code}</td>
                <td>{$success->section}</td>
                <td>{$success->amount}</td>
                <td>success</td>
            </tr>";
        }
    }
    if($failBidInfo){
        foreach ($failBidInfo as $fail){
            echo"<tr><td>{$fail->code}</td>
                <td>{$fail->section}</td>
                <td>{$fail->amount}</td>
                <td>fail</td>
            </tr>";
        }
    }
    echo "</table></body></html>";
}

# the second table for courses bided in round 2
if($round == 2 && $status == "active"){
    echo "<br/>round2 status:";
    echo "<html><body><table border = '1'>
    <tr><td>Course</td>
    <td>Section</td>
    <td>Bid Amount</td>
    <td>Status</td>
    <td>minimum bid</td></tr>";

    # retrieve from minbid table
    $SectionDAO = new SectionDAO();
    $bidDAO = new BidDAO();

    if($pendingInfo){
        foreach ($pendingInfo as $pending){
            $minBidInfo = $SectionDAO->retrieveByCourseAndSection($pending->code,$pending->section);
            $R2status = second_bid_valid($_SESSION['username'],$pending->code, $pending->section, $pending->amount);

            echo"<tr><td>{$pending->code}</td>
                <td>{$pending->section}</td>
                <td>{$pending->amount}</td>
                <td>{$R2status}</td>
                <td>{$minBidInfo[0]->min_bid}</td>
                </tr>";
        }
    }
    echo "</table></body></html>";
}
?>


