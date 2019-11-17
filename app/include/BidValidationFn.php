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
        $roundDAO = new RoundDAO();
        $roundStatus = $roundDAO->retrieveAll();

        if ($roundStatus->round == 1 && $roundStatus->status == 'active'){
            if (sizeof(bidOwnSchool($data[0],$data[2])) > 0 ){
                $errorDetails = bidOwnSchool($data[0],$data[2]);
                array_push($errors, $errorDetails[0]);
            }
        }
       
        $bidDAO = new BidDAO();
        $bidsPlaced = $bidDAO->retrieve($data[0]);
        if($bidDAO->retrieveBid($data[0], $data[2])){
            $bidsPlaced = $bidDAO->retrieveBidsForRepeatedCourse($data[0],$data[2]);
        }
        $sectionStudentDAO = new SectionStudentDAO();
        
        
        if($roundStatus->status == 'active'){
            $courseEnrolled = $sectionStudentDAO->retrieveByUserID($data[0]);
            if(sizeof($courseEnrolled) != 0){
                $bidsPlaced = array_merge($bidsPlaced, $courseEnrolled);
            }
        }
        if (sizeof(bidClass($bidsPlaced, $data[2], $data[3])) > 0){
            $errorDetails = bidClass($bidsPlaced, $data[2], $data[3]);
            array_push($errors, $errorDetails[0]);
        }
        
        if (sizeof(bidExam($bidsPlaced, $data[2])) > 0){
            $errorDetails = bidExam($bidsPlaced, $data[2]);
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
            $bidamounterror = 'insufficient e$';
            array_push($errors, $bidamounterror); 
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
    
        if ($data[4]->round == 2 && $roundStatus->status == 'active'){
            
            if ( $data[1] < $sectionDetails->min_bid ){
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

        foreach ($courseStuEnrolled as $course_stu){
            if ($course_stu->code == $data[2]){
                $errors[] = 'course enrolled';
            }
        }
        

    }
    if (sizeof($errors) == 0) {
        $studentDAO = new StudentDAO(); 
        $student_obj = $studentDAO->retrieve($data[0]);
        $existing_edollar = $student_obj->edollar;

        if ($bidDAO->retrieveBid($data[0], $data[2])){
            $bidDetails = $bidDAO->retrieveBid($data[0], $data[2]);
            $prevAmount = $bidDetails->amount;
            $studentDAO->update($data[0], $prevAmount + $existing_edollar);   

            $bidDAO = new BidDAO();
            $bidDAO->remove($data[0], $data[2]);
        }

        $bidObj = new Bid($data[0], $data[1], $data[2], $data[3], "pending");
        $bidDAO->add($bidObj);

        $student_obj = $studentDAO->retrieve($data[0]);
        $existing_edollar = $student_obj->edollar;

        $studentDAO->update($data[0], $existing_edollar - $data[1]);

        if($data[4]->round == 2){
            second_bid_valid($data[0],$data[2],$data[3],$data[1]);
        }

        
    }
    sort($errors);

    return $errors;

}

?>