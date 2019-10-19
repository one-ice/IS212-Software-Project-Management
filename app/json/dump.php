<?php

require_once "../include/common.php";
require_once "protect_json.php";

$courseDAO = new CourseDAO();
$sectionDAO = new SectionDAO();
$studentDAO = new StudentDAO();

#get all
$courses = $courseDAO->retrieveAll();
$sections = $sectionDAO->retrieveAll();
$students = $studentDAO->retrieveAll();

$course_results  = [];
foreach ($courses as $courseObj){
    $course_results[] = [
        "course" => $courseObj->course, 
        "school" => $courseObj->school,
        "title" => $courseObj->title,
        "description" => $courseObj->description,
        "exam date" => $courseObj->exam_date,
        "exam start" => $courseObj->exam_start,
        "exam end" => $courseObj->exam_end
    ];
}

$section_results = [];
foreach ($sections as $sectionObj){
    $section_results[] = [
        "course" => $sectionObj->course ,
        "section" => $sectionObj->section ,
        "day" => $sectionObj->day ,
        "start" => $sectionObj->start , 
        "end" => $sectionObj->end ,
        "instructor" => $sectionObj->instructor ,
        "venue" => $sectionObj->venue ,
        "size" => $sectionObj->size
    ];
}

$student_results = [];
foreach ($students as $studentObj){
    $student_results[] = [
        "userid"=> $studentObj->userid,
        "password" =>  $studentObj->password ,
        "name" =>  $studentObj->name ,
        "school" =>  $studentObj->school ,
        "edollar" =>  $studentObj->edollar
    ] ;
}

$result = [
    "status" => "success",
    "course" => $course_results,
    "section" => $section_results,
    "student" => $student_results
];

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);

?>