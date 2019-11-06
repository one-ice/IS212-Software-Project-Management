<?php
include_once "../include/common.php"; 
require_once "protect_json.php";


$r = $_GET["r"];
$obj = json_decode($r);
$code = $obj->course;
$section = $obj->section;
$sectionStudentDAO = new SectionStudentDAO();
$sectionStudentObj = $sectionStudentDAO->retrieveAllCourseAndSection($code,$section);
$courseDAO = new CourseDAO();
$course = "";
$course = $courseDAO->retrieve($code);
$sectionDAO = new SectionDAO();
$sectionValid = $sectionDAO->retrieve($code,$section);
$message = ["invalid section"];

$fields = ['course','section' ];
$json_decoded = json_decode($r, true);

foreach ($json_decoded as $key => $value){
    $check[] = $key;
        
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
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);
}
else {
	if (is_Object($course) > 0) {
	if (is_Object($sectionValid) > 0) {
		if (sizeof($sectionStudentObj)>0) {
		$values = array();
		for($row = 0; $row < sizeof($sectionStudentObj); $row++)
		{
			$values[$row]['userid'] = $sectionStudentObj[$row]->userid;
			$values[$row]['amount'] = (float)$sectionStudentObj[$row]->amount;
		}
	
	$result = [ 
        "status" => "success",
		"students" => $values
		
    ];
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION );
		} 
	}
	else {
	$result = [ 
            "status" => "error",
            "message" => [ "invalid section" ]
        ];
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION );
		}
}
else {
	$result = [ 
			"status" => "error",
			"message" => [ "invalid course" ]
			];
			header('Content-Type: application/json');
			echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);
}

}

?>
