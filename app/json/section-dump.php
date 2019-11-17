<?php
include_once "../include/common.php"; 
require_once "protect_json.php";


$r = $_REQUEST["r"];
$obj = json_decode($r, true);


$course = trim($obj['course']);
$section = trim($obj['section']);

$result = [];
$message = [];


#Check if course is missing
if(!array_key_exists('course', $obj))
{
    $message[] = 'missing course';
}
#Check if course is empty
elseif ($course == '')
{
    $message[] = 'blank course';
}
#Check if section is missing
if(!array_key_exists('section', $obj))
{
    $message[] = 'missing section';
}
#Check if section is empty
elseif($section == '')
{
    $message[] = 'empty section';
}

else {
	#Check if course exist
	$courseDAO = new CourseDAO();
	$course_exist = $courseDAO->retrieve($course);
	if ($course_exist == False) {
		$message[] = 'invalid course';
	}
	else {

		#Check if section exist
	 	$sectionDAO = new SectionDAO();
        $section_exist = $sectionDAO->retrievebyCourseAndSection($course, $section);
        if($section_exist == null)
        {
            $message[] = 'invalid section';
        }
	 }

}

if ($message == []) {
$sectionStudentDAO = new SectionStudentDAO();
$sectionStudentObj = $sectionStudentDAO->getCourseAndSection($course,$section);

	if (count($sectionStudentObj)>0){
		for($row = 0; $row < sizeof($sectionStudentObj); $row++)
		{
			$values[$row]['userid'] = $sectionStudentObj[$row]->userid;
			$values[$row]['amount'] = (float)$sectionStudentObj[$row]->amount;
		}
		$result = ["status" => 'success',
                "students" => $values
            ];
	}
	else{
		$result = ["status" => 'success',
                "students" => []
            ];
	}
	 

} 

else {
 $result = [ 
        "status" => "error",
        "message" => $message
    ];
}
header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);

?>
