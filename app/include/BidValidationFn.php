<?php
require_once 'common.php';
require_once 'clearing2.php';


function bidValidation($data){
    //data[0] = userid
    // data[1] = amount
    // data[2] = course
    // data[3] = section
    // data[4] = round
    $errors = [];
    $errorDetails = checkValidUserID($data[0]);
    if (sizeof($errorDetails) > 0) {
        array_push($errors, $errorDetails[0]);
    }

    #Invalid amount
    $errorDetails = checkValidAmt($data[1]);
    if (sizeof($errorDetails) > 0 ) {
        array_push($errors, $errorDetails[0]);
    }

    #Invalid Course / Course & Section
    $errorDetails = checkValidCourse($data[2], $data[3]);
    if (sizeof($errorDetails) > 0) {
        array_push($errors , $errorDetails[0]);
    }  
    #check valid section only after course is valid
    if (sizeof($errorDetails) == 0){
        $errorDetails = checkValidSection($data[2], $data[3]);
        if (sizeof($errorDetails) > 0) {
            array_push($errors , $errorDetails[0]);
        }  
    }

    if (sizeof($errors)>0){
        sort($errors);
        //return error
        return $errors;
    }

    if (sizeof($errors) == 0){
        if (sizeof(bidOwnSchool($data[0],$data[2])) > 0 && $data[4]->round == 1){
            $errorDetails = bidOwnSchool($data[0],$data[2]);
            array_push($errors, $errorDetails[0]);
        }

        $bidDAO = new BidDAO();
        if (sizeof(bidClass($bidDAO->retrieve($data[0]), $data[2], $data[3])) > 0 && !$bidDAO->retrieveBid($data[0], $data[2])){
            $errorDetails = bidClass($bidDAO->retrieve($data[0]), $data[2], $data[3]);
            array_push($errors, $errorDetails[0]);
        }
        
        if (sizeof(bidExam($bidDAO->retrieve($data[0]), $data[2])) > 0 && !$bidDAO->retrieveBid($data[0], $data[2])){
            $errorDetails = bidExam($bidDAO->retrieve($data[0]), $data[2]);
            array_push($errors, $errorDetails[0]);
        }

        if (sizeof(bidPrerequisite($data[0],$data[2])) > 0 ){
            $errorDetails = bidPrerequisite($data[0],$data[2]);
            array_push($errors, $errorDetails[0]);
        }

        if (sizeof(bidCourseCompleted($data[0], $data[2]))> 0){
            $errorDetails = bidCourseCompleted($data[0], $data[2]);
            array_push($errors, $errorDetails[0]);
        }


        if (sizeof(bidEnoughDollar($data[0], $data[2], $data[1])) > 0){
            $errorDetails = bidEnoughDollar($data[0], $data[2], $data[1]);
            array_push($errors, $errorDetails[0]);
        }
        $sectionDAO = new SectionDAO();
        $sectionDetails = $sectionDAO->retrieve($data[2], $data[3]); 
    
        
        $sectionStudentDAO = new SectionStudentDAO();
        $sectionDAO = new SectionDAO();
        $bidDAO = new BidDAO();
        $studentDAO = new StudentDAO();
        $find_vacancy = $sectionDAO->retrieve($data[2],$data[3]);
        $full_vacancy = $find_vacancy->size;
        $section_enrollment =  $sectionStudentDAO->retrieveVacancy($data[2], $data[3]);
        $current_vacancy = $full_vacancy - $section_enrollment;
    
        $bids_now = $bidDAO->retrieveBidForEachSection($data[2], $data[3]);
        $num_of_bids = sizeof($bids_now);
        $slots = $current_vacancy - $num_of_bids;
    
        if ($data[4]->round == 2 && $slots <= 0){
            
            if ( $data[1] <= $sectionDetails->min_bid ){
                $errors[] = 'bid too low';
            }
    
        }
    
        if ($data[4]->status == 'inactive'){
            $errors[] = 'round ended';
        }
        #add vacancy validation
        $sectionstuDAO = new SectionStudentDAO();
        $taken = $sectionstuDAO->retrieveVacancy($data[2], $data[3]);
        $sectionObj = $sectionDAO->retrieve($data[2], $data[3]);
        $full_size = $sectionObj->size;
        $vacancy = $full_size - $taken;
        if ($vacancy <= 0){
            $errors[] = 'no vacancy';
        }

        $courseStuEnrolled = $sectionstuDAO->retrieveByUserID($data[0]);
        $currentBids = $bidDAO->retrieve($data[0]);
        $totalNumberOfCourses = sizeof($courseStuEnrolled) + sizeof($currentBids);
        if($totalNumberOfCourses >= 5 && !$bidDAO->retrieveBid($data[0],$data[2])){
            $errors[] = 'section limit reached';
        }
    }
    
    sort($errors);

    return $errors;

}



?>