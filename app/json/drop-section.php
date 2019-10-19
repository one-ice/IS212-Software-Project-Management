<?php
include_once "../include/common.php"; 

$r = $_GET["r"];
$obj = json_decode($r);
$userid = $obj->userid;
$code = $obj->course;
$section = $obj->section;

$roundDAO = new RoundDAO();
$roundInfo = $roundDAO->retrieveAll();
$round = $roundInfo->round;
$status = $roundInfo->status;

#$studentDAO =  new StudentDAO();
#$sectionStudentDAO = new SectionStudentDAO();

if ($round == 2 && $status == 'active') {
$result = [ 
        "status" => "error",
		"message" => "round 2 active"
		
    ];
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);
	
} else {
	$result = [ 
        "status" => "error",
		"message" => "round not active"
		
    ];
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);
}

?>