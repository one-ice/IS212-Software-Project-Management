<?php
spl_autoload_register(function($class){
    require_once "include/$class.php"; 
});

#if no errors, you show them success message, add into

function meetCriteria($stuID,$edollar,$courseCode,$section,$round){

    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $errors = [];

    
    $stuDAO = new StudentDAO();
    $student = $stuDAO->retrieve($stuID);

    // course_comp
    $courseCompDAO = new Course_CompletedDAO();
    $courseComp = $courseCompDAO->retrieve($stuID);

    // validate courseComp
    $courseC = [];
    foreach($courseComp as $cp){   
        if($cp->code == $courseCode){
            $errors[] = "course completed";
        }
        $courseC[] = $cp->code;
    }

        
    // validate the course in SectionStudent
    if($round->round == 2){
        $sectionStuDAO = new SectionStudentDAO();
        $sectionStu = $sectionStuDAO->retrieveByUserID($stuID);
        foreach($sectionStu as $sectStu){
            if($courseCode == $sectStu->code){
                $errors[] = "course completed";
            }
        }
    }
    // validate enough edollar
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
        
        foreach($prereq as $prereqCourse){            
            if(in_array($prereqCourse->prerequisite,$courseC) == FALSE){
                $errors[] = "incompleted prerequisite";
            }
        }  
    }


    // validate course and section
    $courseDAO = new CourseDAO();
    $studentDAO = new StudentDAO();
    $studentClass = $studentDAO->retrieve($stuID);
    if($courseClass = $courseDAO->retrieve($courseCode)){
        $sectionDAO = new SectionDAO();
        if($sectionDAO->retrieve($courseCode,$section)){

            // validate no clash time and one section per course
            $bidDAO = new BidDAO();
            $allBidded = $bidDAO->retrieve($stuID);
            
            if(count($allBidded) < 5){

                if(count($allBidded) > 0){
                    foreach($allBidded as $bid){
                        
                        // one section per course
                        $bidCourse = $bid->code;
                        $bidSection = $bid->section;
                        // var_dump($bidCourse);
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
    return $errors;

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