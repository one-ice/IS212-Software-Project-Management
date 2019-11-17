<?php
include_once "../include/common.php"; 
require_once "protect_json.php";

$r = $_GET["r"];
$obj = json_decode($r);
$userid = $obj->userid;
$code = $obj->course;
$section = $obj->section;

$roundDAO = new RoundDAO();
$roundInfo = $roundDAO->retrieveAll();
$round = $roundInfo->round;
$status = $roundInfo->status;

$studentDAO =  new StudentDAO();
$student= "";
$student = $studentDAO->retrieve($userid);
$courseDAO = new CourseDAO();
$course = "";
$course = $courseDAO->retrieve($code);
$sectionDAO = new SectionDAO();
$sectionValid = $sectionDAO->retrieve($code,$section);

$fields = ['course','section','userid' ];
$json_decoded = json_decode($r, true);
foreach ($json_decoded as $key => $value){
    $check[] = $key;
	$value = trim($value);
    if ($value == ""){
        $errors[] = 'blank '. $key;
    }
}
foreach ($fields as $things){
    if (!in_array($things, $check)){
        $errors[] = 'missing ' . $things;
    }
}

if (sizeof($errors) > 0){ 

    $result = [ 
        "status" => "error",
        "message" => $errors
    ];
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
}
else{
	$userid = trim($obj->userid);
	$code = trim($obj->course);
	$section = trim($obj->section);
	$errors = [];

	if ($status != 'active') {	
		$errors[] = 'round not active';
	}
	
	if  (sizeof(checkValidUserID($userid)) > 0 ){
		$errors[] = 'invalid userid';
	}

	if (sizeof(checkValidCourse($code, $section)) > 0){
		$errors[] = 'invalid course';
	}
	else{
		if (sizeof(checkValidSection($code, $section)) > 0){
			$errors[] = 'invalid section';
		}
	}

	if (sizeof($errors)> 0){
		$result = [ 
			"status" => "error",
			"message" => $errors
		];

		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}
	else{
		
		$sectionStudentDAO = new SectionStudentDAO();
		$sectionStudent = $sectionStudentDAO->retrieveByUserIDCourseSection($userid,$code,$section);
		$studentDAO = new StudentDAO();
		$student_objj = $studentDAO->retrieve($userid);
		$current_amt = $student_objj->edollar;
		foreach ($sectionStudent as $stu){
			$bid_amount = $stu->amount;
			$studentDAO->update($userid, $current_amt + $bid_amount);
		}
		#remove record
		$sectionStudentDAO->removeByStuAndSection($userid,$code,$section);
		$result = [ 
			"status" => "success"
		];
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}
}





?>