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

$studentDAO =  new StudentDAO();
$student= "";
$student = $studentDAO->retrieve($userid);
$courseDAO = new CourseDAO();
$course = "";
$course = $courseDAO->retrieve($code);
#$sectionStudentDAO = new SectionStudentDAO();
if ($round == 2 && $status == 'active') {		#round 2 & active
	
	if (count((array)$student) > 0) {			#user id valid
	
		if (is_Object($course) > 0) {
			$result = [ 
					"status" => "success",
					"message" => "valid course"
					];
					header('Content-Type: application/json');
					echo json_encode($result, JSON_PRETTY_PRINT);
		}
		else {

			$result = [ 
					"status" => "error",
					"message" => "invalid course"
					];
					header('Content-Type: application/json');
					echo json_encode($result, JSON_PRETTY_PRINT);
		
		}

	}
	 else 
	 {										#userid invalid
		$result = [ 
        "status" => "error",
		"message" => "invalid userid"
    ];
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);
	 }
	
} 
else 
{											#Not Round 2 or not active or both
	$result = [ 
        "status" => "error",
		"message" => "round not active"
    ];
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);
}

?>