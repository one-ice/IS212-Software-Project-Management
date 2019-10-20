<?php

require_once "../include/common.php";
require_once "protect_json.php";
require_once "../include/token.php";

$r = $_REQUEST['r'];
$json_decoded = json_decode($r, true);
$course = $json_decoded['course'];
$section = $json_decoded['section'];

$result = [];
$message = [];

#Check if course is missing
if(!array_key_exists('course', $json_decoded))
{
    $message[] = 'missing course';
}
#Check if course is empty
elseif ($course == '')
{
    $message[] = 'blank course';
}
else
{
    #Check if course exist
    $courseDAO = new CourseDAO();
    $course_exist = $courseDAO->retrieve($course);;
    if($course_exist == False)
    {
        $message[] = 'invalid course';
    }
    else
    {   
        #Check if section is missing
        if(!array_key_exists('section', $json_decoded))
        {
            $message[] = 'missing section';
        }
        #Check if section is empty
        elseif($section == '')
        {
            $message[] = 'empty section';
        }
        else
        {
            #Check if section exist
            $sectionDAO = new SectionDAO();
            $section_exist = $sectionDAO->retrievebyCourseSection($course, $section);
            if($section_exist == null)
            {
                $message[] = 'invalid section';
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
?>