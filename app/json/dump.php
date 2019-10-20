<?php

require_once "../include/common.php";
require_once "protect_json.php";

$courseDAO = new CourseDAO();
$sectionDAO = new SectionDAO();
$studentDAO = new StudentDAO();
$prereqDAO = new PrereqDAO();
$course_completedDAO = new Course_CompletedDAO();
$bidDAO = new BidDAO();
$sectionStudentDAO = new SectionStudentDAO();
$roundDAO = new RoundDAO();

#get all
$courses = $courseDAO->retrieveAll();
$sections = $sectionDAO->retrieveAll();
$students = $studentDAO->retrieveAll();
$prereqs = $prereqDAO->retrieveAll();
$course_completed = $course_completedDAO->retrieveAll();
$bids = $bidDAO->retrieveAll();
$sectionStudents = $sectionStudentDAO->retrieveAll();
$round = $roundDAO->retrieveAll();

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

$prereqs_results = [];
foreach ($prereqs as $prereqObj){
    $prereqs_results[] = [
        "course" => $prereqObj->course,
        "prerequisite" => $prereqObj->prerequisite
    ];
}

$bid_results = [];
foreach ($bids as $bidObj){
    $bid_results[] = [
        "userid" => $bidObj->userid,
        "amount" => $bidObj->amount,
        "course" => $bidObj->code,
        "section" => $bidObj->section
    ];
}


$course_completed_results = [];
foreach ($course_completed as $coursec_Obj){
    $course_completed_results[] = [
        "userid" => $coursec_Obj->userid,
        "course" => $coursec_Obj->code
    ];
}

$section_student_results = [];
foreach ($sectionStudents as $secstu_Obj){
    $section_student_results[] = [
        "userid" => $secstu_Obj->userid,
        "course" => $secstu_Obj->code,
        "section" => $secstu_Obj->section,
        "amount" => $secstu_Obj->amount
    ];
}

$result = [
    "status" => "success",
    "course" => $course_results,
    "section" => $section_results,
    "student" => $student_results,
    "prerequisite" => $prereqs_results,
    "bid" => $bid_results,
    "completed-course" => $course_completed_results,
    "section-student" => $section_student_results
];


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);

?>