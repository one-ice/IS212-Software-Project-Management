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
       

    $start = explode(":", $courseObj->exam_start);
    $start = $start[0] . $start[1];

    if ($start[0] == '0'){
        $start = ltrim($start, '0');
    }

    $end = explode(":", $courseObj->exam_end);
    $end = $end[0] . $end[1];

    if ($end[0] == '0'){
        $end = ltrim($end, '0');
    }

    $e_date = explode("-", $courseObj->exam_date);
    $e_date = $e_date[0] . $e_date[1] .$e_date[2];

    $course_results[] = [
        "course" => $courseObj->course, 
        "school" => $courseObj->school,
        "title" => $courseObj->title,
        "description" => $courseObj->description,
        "exam date" => $e_date,
        "exam start" => $start,
        "exam end" => $end
    ];
}

$section_results = [];
foreach ($sections as $sectionObj){

    $start = explode(":", $sectionObj->start);
    $start = $start[0] . $start[1];
    
    if ($start[0] == '0'){
        $start = ltrim($start, '0');
    }

    $end = explode(":", $sectionObj->end);
    $end = $end[0] . $end[1];

    if ($end[0] == '0'){
        $end = ltrim($end, '0');
    }

    $section_results[] = [
        "course" => $sectionObj->course ,
        "section" => $sectionObj->section ,
        "day" => $sectionObj->day ,
        // "start" => $sectionObj->start , 
        // "end" => $sectionObj->end ,
        "start" => $start,
        "end" => $end,
        "instructor" => $sectionObj->instructor ,
        "venue" => $sectionObj->venue ,
        "size" => intval($sectionObj->size)
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