<?php
spl_autoload_register(function($class){
    require_once "app/include/$class.php"; 
});

// $stuID = $_SESSION['username'];
// $edollar = $_POST['edollar'];
// $courseCode = $_POST['coursecode'];
// $section = $_POST['sectionnum'];
// $status = 'round 1';

#if no errors, you show them success message, add into

function meetCriteria($stuID,$edollar,$courseCode,$section,$round){

    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $errors = [];

    // validate enough edollar
    $stuDAO = new StudentDAO();
    $student = $stuDAO->retrieve($stuID);

    $dollarAmount = $student->edollar;

    if($edollar < 10.00){
        $errors[] = 'invalid amount';
    }
    elseif($dollarAmount < $edollar){
        $errors[] = 'not enough e-dollar';
    }

    // validate finishing prereq:
    $prereqDAO = new PrereqDAO();
    if ($prereq = $prereqDAO->retrieve($courseCode)){
        $courseCompDAO = new Course_CompletedDAO();
        $courseComp = $courseCompDAO->retrieve($stuID);
        if(in_array($prereq,$courseComp) == FALSE){
            $errors[] = "incompleted prerequisite";
        }
    }
    // validate course and section
    $courseDAO = new CourseDAO();
    $studentDAO = new StudentDAO();
    $studentClass = $studentDAO->retrieve($stuID);
    if($courseClass = $courseDAO->retrieve($courseCode)){
        $sectionDAO = new SectionDAO();
        if($round->round == 1){
            if($courseClass->school == $studentClass->school ){
                $errors[] = "not own school course";
            }
        }

        if($sectionDAO->retrieve($courseCode,$section)){

            // validate no clash time and one section percourse
            $bidDAO = new BidDAO();
            $allBidded = $bidDAO->retrieve($stuID);

            if(count($allBidded) < 5){

                if(count($allBidded) > 0){
                    foreach($allBidded as $bid){
                        
                        // one section per course
                        $bidCourse = $bid->code;
                        $bidSection = $bid->section;
                        if($bidCourse == $courseCode){
                            $errors[] = "course completed";
                        }
                        else{
                            // exam time not clashed
                            // $courseDAO = new CourseDAO();
                            $bidCourseClass = $courseDAO->retrieve($bidCourse);
                            $addCourseClass = $courseDAO->retrieve($courseCode);
                            if($bidCourseClass->exam_date == $addCourseClass->exam_date){
                                if (($bidCourseClass->exam_end < $addCourseClass->exam_start || $addCourseClass->exam_end < $bidCourseClass->exam_start) == FALSE){
                                    $errors[] = "exam timetable clash";
                                }
                                else{

                                    // course time not clashed
                                    $bidSectionClass = $sectionDAO->retrieve($bidCourse,$bidSection);
                                    $addSectionClass = $sectionDAO->retrieve($courseCode,$section);
                                    if($bidSectionClass->day == $addSectionClass->day){
                                        if (($bidSectionClass->end < $addCourseClass->start || $addCourseClass->end < $bidCourseClass->start) == FALSE){
                                            $errors[] = "exam timetable clash";
                                        }
                                        else{
                                            $bid = new Bid($stuID,$edollar,$courseCode,$section,$status);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else{
                $errors[] = "section limit reached";
            }
           
        }
        else{
            $errors[] = "invalid section";
        }
    }
    else{
        $errors[] = "not school course";
    }

    #added round 2 validation

    $sectionDAO = new SectionDAO();
    $min_bid = $sectionDAO->retrieveMinBid($courseCode, $section); 

    if ($round->round == 2){
        
        if ($edollar < $min_bid){
            $errors[] = 'bid too low';
        }

    }

    if ($round->status == 'inactive'){
        $errors[] = 'round ended';
    }
    #add vacancy validation
    $sectionstuDAO = new SectionStudentDAO();
    $taken = $sectionstuDAO->retrieveVacancy($courseCode, $section);
    $sectionObj = $sectionDAO->retrieve($courseCode, $section);
    $full_size = $sectionObj->size;
    $vacancy = $full_size - $taken;
    if ($vacancy <= 0){
        $errors[] = 'no vacancy';
    }

    return $errors;
}
?>