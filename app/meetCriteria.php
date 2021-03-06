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
                $errors[] = "course enrolled";
            }
        }
    }
    // validate enough edollar
    $dollarAmount = $student->edollar;
    if( ($edollar < 10.00) || (!preg_match('/^-?[0-9]+(?:\.[0-9]{1,2})?$/', $edollar)) ){
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
                        if($bidCourse != $courseCode){
                             // exam time not clashed
                            // $courseDAO = new CourseDAO();
                            $bidCourseClass = $courseDAO->retrieve($bidCourse);
                            $addCourseClass = $courseDAO->retrieve($courseCode);
                            if($bidCourseClass->exam_date == $addCourseClass->exam_date){
                                if (($bidCourseClass->exam_end < $addCourseClass->exam_start || $addCourseClass->exam_end < $bidCourseClass->exam_start) == FALSE){
                                    $errors[] = "exam timetable clash";
                                }
                            }

                                    // course time not clashed
                            $bidSectionClass = $sectionDAO->retrieve($bidCourse,$bidSection);
                            $addSectionClass = $sectionDAO->retrieve($courseCode,$section);
                            if($bidSectionClass->day == $addSectionClass->day){
                                if (($bidSectionClass->end < $addSectionClass->start || $addSectionClass->end < $bidSectionClass->start) == FALSE){
                                    $errors[] = "class timetable clash";
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
    $sectionDetails = $sectionDAO->retrieve($courseCode, $section); 

    
    $sectionStudentDAO = new SectionStudentDAO();
    $sectionDAO = new SectionDAO();
    $bidDAO = new BidDAO();
    $studentDAO = new StudentDAO();
    $find_vacancy = $sectionDAO->retrieve($courseCode,$section);
    $full_vacancy = $find_vacancy->size;
    $section_enrollment =  $sectionStudentDAO->retrieveVacancy($courseCode, $section);
    $current_vacancy = $full_vacancy - $section_enrollment;

    $bids_now = $bidDAO->retrieveBidForEachSection($courseCode, $section);
    $num_of_bids = sizeof($bids_now);
    $slots = $current_vacancy - $num_of_bids;

    if ($round->round == 2 && $slots <= 0){
        
        if ( $edollar <= $sectionDetails->min_bid ){
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