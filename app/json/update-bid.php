<?php
require_once "../include/common.php";
require_once "../meetCriteria.php";

$user_bid = $_GET['r'];
$json_decoded = json_decode($user_bid, true);

$userid = $json_decoded['userid'];
$amount = $json_decoded['amount'];
$course = $json_decoded['course'];
$section = $json_decoded['section'];

$errors = [];

if ( sizeof(checkValidUserID($userid)) > 0 ){
    $errors[] = 'invalid userid';
}   
if ( sizeof( checkValidCourse($course, $section)) > 0 ){
    $errors[] = 'invalid course';
}
if ( sizeof(checkValidSection($course, $section)) > 0 ){
    $errors[] = 'invalid section';
}
if ( sizeof(checkValidAmt($amount)) > 0 ){
    $errors[] = 'invalid amount';
}


if ( sizeof($errors) == 0 ){
    $errors = [];
    $bidDAO = new BidDAO();
    $roundDAO = new RoundDAO();
    $round = $roundDAO->retrieveAll();

    $sectionStuDAO = new SectionStudentDAO();
    $section_enrolment = $sectionStuDAO->retrieveByUserID($userid);

    if ($round->round == 2){
        foreach ($section_enrolment as $enrolment){
            if ( ($enrolment->course == $course) && ($enrolment->section == $section) ){
                $errors[] = "course enrolled";
            }
        }
    }
    
    if (sizeof($errors) != 0){
        $bidDAO->remove($userid, $course);

        $newbid = new Bid($userid, $amount, $course, $section, 'pending');
        $bidDAO->add($newbid);

    }
    #get 1 bid from the bid table
    $bids = $bidDAO->retrieveBid($userid, $course);

    #get enrolled course 
    #if bids exists
    if ($bids){

        $state = meetCriteria($userid,$amount,$course,$section,$round);

        if (sizeof ($state) == 0) {
            $bidDAO->update($userid, $course, $section, $amount, "pending");
        }
        else{

            foreach ($state as $error){
                $errors[] = $error;
            }
        }    
    }
    else{

        $state = meetCriteria($userid,$amount,$course,$section,$round);

        if (sizeof ($state) == 0) {
            $newbid = new Bid($userid, $amount, $course, $section, 'pending');
            $bidDAO->add($newbid);
        }
        else{

            foreach ($state as $error){
                $errors[] = $error;
            }
        }
    }


    if (sizeof($errors) == 0){
        $result = [ 
            "status" => "success"
        ];
    }
    else{
        $result = [ 
        "status" => "error",
        "message" => [ $errors ]
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
   
}
else{
    $result = [ 
        "status" => "error",
        "message" => $errors
    ];
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
}



?>