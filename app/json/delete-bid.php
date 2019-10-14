<?php
include_once "../include/common.php"; 

$roundDAO = new RoundDAO();
$roundObj = $roundDAO->retrieveAll();
$roundNow = $roundObj->round;
$statusNow = $roundObj->status;

$errors = [];
$user = $_GET['userid'];
$course = $_GET['course']; 
$section = $_GET['section'];

if ($statusNow == 'active'){

    $courseDAO = new CourseDAO();
    if (! $courseDAO->retrieve($course) ){
        $errors[] = "invalid course"; 
    }

    $studentDAO = new StudentDAO();
    if (! $studentDAO->retrieve($userid)){
        $errors[] = "invalid userid";
    }

    $sectionDAO = new sectionDAO();
    if (! $sectionDAO->retrieve($course, $section)){
        $errors[] = "invalid section";
    }

    if (sizeof($errors) == 0){
        $bidDAO = new BidDAO();
        $student_bid = $bidDAO->retrieveBid($userid, $course);

        if ($student_bid && ($student_bid->section == $section) ){

            $bidDAO = new BidDAO();
            $bidded_amt = $student_bid->amount;

            $studentDAO = new StudentDAO();
            $student = $studentDAO->retrieve($userid);
            $studentedollar = $student->edollar;    
            $studentDAO->update($userid, $studentedollar + $bidded_amt);

            if ( $bidDAO->remove($userid, $course) ){
                $result = [ 
                    "status" => "success",
                ];
            };

        }
        else{
            $result = [ 
                "status" => "error",
                "message" => [ "no such bid" ]
            ];
        }
    }
    else{
        $result = [ 
            "status" => "error",
            "message" => $errors
        ];
    }

}else{

    $result = [ 
        "status" => "error",
        "message" => [ "round ended" ]
    ];

}


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);


?>