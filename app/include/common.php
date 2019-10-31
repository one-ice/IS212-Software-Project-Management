<?php
$API_DIRECTORY = "http://localhost/project-g5t4/app/json/";
// $API_DIRECTORY = "http://18.221.10.143/app/json/";
// this will autoload the class that we need in our code
spl_autoload_register(function($class) {
 
    // we are assuming that it is in the same directory as common.php
    // otherwise we have to do
    // $path = 'path/to/' . $class . ".php"    
    require_once "$class.php"; 
  
});


// session related stuff

session_start();


function curl_result($location, $post){
    $url = $GLOBALS['API_DIRECTORY'] . $location;
    $ch = curl_init();
    $curlConfig = array(
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS     => $post,
    );
    curl_setopt_array($ch, $curlConfig);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, TRUE);
}

function printErrors() {
    if(isset($_SESSION['errors'])){
        echo "<ul id='errors' style='color:red;'>";
        
        foreach ($_SESSION['errors'] as $value) {
            echo "<li>" . $value . "</li>";
        }
        
        echo "</ul>";   
        unset($_SESSION['errors']);
    }    
}



function isMissingOrEmpty($name) {
    if (!isset($_REQUEST[$name])) {
        return "$name cannot be empty";
    }

    // client did send the value over
    $value = $_REQUEST[$name];
    if (empty($value)) {
        return "$name cannot be empty";
    }
}

# check if an int input is an int and non-negative
function isNonNegativeInt($var) {
    if (is_numeric($var) && $var >= 0 && $var == round($var))
        return TRUE;
}

# check if a float input is is numeric and non-negative
function isNonNegativeFloat($var) {
    if (is_numeric($var) && $var >= 0)
        return TRUE;
}

# this is better than empty when use with array, empty($var) returns FALSE even when
# $var has only empty cells
function isEmpty($var) {
    if (isset($var) && is_array($var))
        foreach ($var as $key => $value) {
            if ($value== "") {
               unset($var[$key]);
            }
        }

    if (empty($var))
        return TRUE;
}


#course validation
function checkCourseVali($title,$description,$examDate,$examStart,$examEnd){
    # Does not check for Course and School! (takes in 5 columns of data only!!!)
    $errors_in_course = [];
    $examDateVali = FALSE;
    $examEndVali = FALSE;
    $examStartVali = FALSE;
    $titleVali =FALSE;
    $descriptionVali = FALSE;
    
    if(strlen($description) <= 1000){
        $descriptionVali = TRUE;
    }
    if(strlen($title) <= 100){
        $titleVali = TRUE;
    }
    $examDate = strval($examDate);
    if(strlen($examDate) == 8){

        $year = substr($examDate,0,4);
        $month = substr($examDate,4,2);
        $day = substr($examDate,6,2);
        if(checkdate($month,$day,$year)){
            $examDateVali = True;
        }
    }

    if($startFormat = DateTime::createFromFormat('H:i',$examStart)){
        $examStartVali = TRUE;
       
    }
        
    if($endFormat = DateTime::createFromFormat('H:i',$examEnd)){
        $examEndVali = TRUE;
        if($endFormat<$startFormat && $startFormat){
            $examEndVali = FALSE;
        }   

    }
    if(!$descriptionVali){
        $errors_in_course[] = "invalid description";
    }
    if(!$titleVali){
        $errors_in_course[] = "invalid title";
    }
    if(!$examStartVali){
        $errors_in_course[] = "invalid exam start";
    }
    if(!$examDateVali){
        $errors_in_course[] = "invalid exam date";
    }
    if(!$examEndVali){
        $errors_in_course[] = "invalid exam end";
    }
    return $errors_in_course;
}

#function to check if section is valid
function isSectionValid($course,$section,$day,$start,$end,$instructor,$venue,$size){
    $courseValid = False;
    $sectionValid = False;
    $dayValid = False;
    $startValid = False;
    $endValid = False;
    $instructorValid = False;
    $venueValid  = False;
    $sizeValid  = False;
    $errors = [];

    #establish connection to DB
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    #generating courseDAO
    $courseDAO = new CourseDAO();
    # compare to course for courseValid
    $check = $courseDAO -> retrieve($course);
    //check if array is not empty. if array is not empty, course exists
    if ($check){
        $courseValid = True;
        #section validity (placed here as it should only be checked if course is valid)
        if ($section[0]=="S"){
            #getting the num string of section
            $num = substr($section,1);
            #check if the numbers of the section is between 1 to 99
            if ($num>=1 and $num<=99){
                $sectionValid = True;
            }
        }
    }

    
    #day validity
    # check if day is between 1-7
    if($day>=1 && $day<=7)
    {
        $dayValid=True;
    }
    #check start and end validity
    if($startFormat = DateTime::createFromFormat('H:i',$start)){
        $startValid = TRUE;
    }

    if($endFormat = DateTime::createFromFormat('H:i',$end)){
        $endValid = TRUE;
        if($endFormat<$startFormat && $startValid){
            $endValid = FALSE;
        }   
    }

    #check instructor length
    $instructor_count = strlen($instructor);
    if ($instructor_count<=100){
        $instructorValid= True;
    }

    #Check Venue length
    $venue_count = strlen($venue);
    if($venue_count<=100){
        $venueValid = True;
    }

    #check size validity by making sure it is greater than 0
    if($size>0){
        $sizeValid = True;
    }

    #appending error messages

    if (!$courseValid){
        $errors[] = "invalid course";
    }
    elseif (!$sectionValid){
        $errors[] = "invalid section";
    }
    if (!$dayValid){
        $errors[] = "invalid day";
    }
    if (!$startValid){
        $errors[] = "invalid start";
    }
    if (!$endValid){
        $errors[] = "invalid end";
    }
    if (!$instructorValid){
        $errors[] = "invalid instructor";
    }
    if (!$venueValid){
        $errors[] = "invalid venue";
    }
    if (!$sizeValid){
        $errors[] = "invalid size";
    }

    return $errors;
}

#prereq validation
function isPrerequisiteValid($course,$prereq){
    $courseValid = FALSE;
    $prereqValid = FALSE;
    $errors = [];

    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    #generating courseDAO
    $courseDAO = new CourseDAO();
    # compare to course for courseValid
    $check_course = $courseDAO -> retrieve($course);
    if ($check_course){
        $courseValid = True;
    }
    
    $check_prereq = $courseDAO -> retrieve($prereq);
    if ($check_prereq){
        $prereqValid = True;
    }
    if(!$courseValid){
        $errors[] = "invaild course";
    }
    if(!$prereqValid){
        $errors[] = "invalid prerequisite";
    }

    return $errors;
}


#Validations for student
function isStudentValid($userid, $password, $name, $edollar)
{
    $errors = [];
    
    #Check whether userid is valid
    if(strlen($userid) > 128)
    {
        $errors[] = "invalid userid";
    }

    #Check for duplicate userid
    $studentDAO = new StudentDAO();
    $result = $studentDAO->retrieve($userid);
    if ($result)
    {
        $errors[] = "duplicate userid";
    }

    #Check whether edollar is valid
    #Convert edollar (decimal) to string
    $stringedollar = strval($edollar);
    $explodestringedollar = explode('.', $edollar);
    if($edollar > 0.0)
    {
        if (count($explodestringedollar) == 2)
        {
            if ((strlen($explodestringedollar[1])) != 2)
            {
                $errors[] = "invalid e-dollar";
            }
        }  
        elseif (count($explodestringedollar) > 2){
            $errors[] = "invalid e-dollar";
        }
    } 
    else
    {
        $errors[] = "invalid e-dollar";
    }

    #Check whether password is valid
    if(strlen($password) > 128)
    {
        $errors[] = "invalid password";
    }

    #Check whether name is valid
    if(strlen($name) > 100)
    {
        $errors[] = 'invalid name';
    }

    return $errors;
}
#Validations for course_completed
function isCourse_CompletedValid($userid,$code)
{
    $errors = [];
	    #check if userid exist in student
    $studentDAO = new StudentDAO();
    $result = $studentDAO->retrieve($userid);
    if (!$result)
    {
        $errors[] = "invalid userid";
    }
	$courseDAO = new CourseDAO();
    $result = $courseDAO->retrieve($code);
    if (!$result)
    {
        $errors[] = "invalid course";
    }


    else{
        $course_completedDAO = new Course_CompletedDAO();
        $prereqDAO = new PrereqDAO();
        $prereq = $prereqDAO->retrieve($code);
        // echo json_encode($prereq);
        if($prereq)
        {
            $count = 0;
            $course_completed = [];
            #get the courses completed
            $courses_completed = $course_completedDAO->retrieve($userid);
            $prereqCodeList = [];
            foreach($prereq as $prereqCode){
                $prereqCodeList[] = $prereqCode->prerequisite;
            }
            // echo json_encode($prereqCodeList);
            foreach ($courses_completed as $course)
            {
                $completed_code = $course->code;
                #check whether course completed in array of prerequisites for the course
                if(in_array($completed_code,$prereqCodeList))
                {
                    $count++;
                }
            }
            if($count != count($prereq))
            {
                $errors[] = 'invalid course completed';
            }   
        }
    }
    return $errors;

}

#bid validation
function checkValidUserID($userID){
    $errors = [];
    $studentDAO = new StudentDAO();
    $result = $studentDAO->retrieve($userID);
    if (!$result)
    {
        $errors[] = "invalid userid";
    }

	return $errors;
}

function checkValidCourse($courseCode, $sectionID){
	$errors = [];
    $courseDAO = new CourseDAO();
    $result = $courseDAO->retrieve($courseCode);
    if (!$result)
    {
        $errors[] = "invalid course";
    }
	return $errors;
}

#Bug found
function checkValidSection($courseCode, $sectionID) {
	$errors = [];
	$sectionDAO = new SectionDAO();
	$result = $sectionDAO->retrieve($courseCode, $sectionID);
	if (!$result){
		$errors[] = "invalid section";
	}
	return $errors;
}

function checkValidAmt($amt) {
    #Less than $10
    $errors = [];
	if ($amt < 10) {
		$errors[] = "invalid amount" ;
	}
	#Not Numeric in Amount
	elseif (!preg_match('/^-?[0-9]+(?:\.[0-9]{1,2})?$/', $amt)) {
	    $errors[] =  "invalid amount";
	}
	return $errors;

}


#Bid Logic validations

#Only round 1, checking whether student's school == module's school
#when inputting the parameters, use DAO 
#userID take from bid csv $data[0]
#mod_code take from bid csv $data[2]
function bidOwnSchool($userID, $mod_code){
    $errors = [];
    $studentDAO = new StudentDAO();
    $student_obj = $studentDAO->retrieve($userID);
    $student_sch = $student_obj->school;

    $courseDAO = new CourseDAO();
    $course_obj = $courseDAO->retrieve($mod_code);
    $mod_sch = $course_obj->school;

    if ($student_sch != $mod_sch){
        $errors[] = 'not own school course';
    }
    return $errors;
}

#check if student has already completed the course which is bidded
#userID take from bid csv $data[0]
#mod_code take from bid csv $data[2]
function bidCourseCompleted($userID, $mod_code){
    $errors = [];
    $course_completed = new Course_CompletedDAO();
    $course_completed_obj = $course_completed->retrieve($userID);
    
    foreach ($course_completed_obj as $course_complete){
        if ($course_complete->code ==  $mod_code){
                $errors[] = 'course completed';
            }
    }
    return $errors;
}

#get an array of bids using bidDAO->retrieve($data[0])
function bidSectionLimit($array_of_bid_objs){
    $errors = [];
    if (sizeof($array_of_bid_objs) >= 5){
        $errors[] = 'section limit reached';
    }
    return $errors;
}

#userID from bid csv $data[0]
#bid_amount $data[1]
function bidEnoughDollar($userID, $code, $bid_amount){
    $errors = [];
    $bidDAO = new BidDAO();
    $studentDAO = new StudentDAO();
    $student_obj = $studentDAO->retrieve($userID);
    $student_edollar = $student_obj->edollar;

    #if there is a previous bid already stored in the table

    if ($bidDetails = $bidDAO->retrieveBid($userID, $code)){
        $prevAmount = $bidDetails->amount;
        #check whether student has enough $ to bid if you were to refund the student
        $refund = $prevAmount + $student_edollar;
        if ($refund < $bid_amount){
            $errors[] = 'not enough e-dollar';
        }
    }else{
        #if no previous bid
        if ($student_edollar < $bid_amount){
            $errors[] = 'not enough e-dollar';
        }
    }
    return $errors;
}

#after checking , in the bootstrap.php
#refund the student with the bid and then deduct $ from student after checking

#get userID from data[0] and code from data[2]
function bidPrerequisite($userID, $code){
    $errors = [];
    $prereqDAO = new PrereqDAO();
    $prereq_array_of_obj = $prereqDAO->retrieve($code);
    $course_completedDAO = new Course_CompletedDAO();
    $course_array_of_obj = $course_completedDAO->retrieve($userID);
    $c_course_array = [];
    foreach ($course_array_of_obj as $course){
        $c_course_array[] = $course->code;
    }
    foreach ($prereq_array_of_obj as $prereq){
       if (!in_array($prereq->prerequisite , $c_course_array)){
           $errors[] = 'incomplete prerequisites';
           break;
       }
    }
    return $errors;
}

#get an array of bids using  bidDAO->retrieve($data[0])
#get current section details $data[2] and $data[3]
function bidClass($array_of_bid_objs, $code, $section){
    $errors = [];
    $sectionDAO = new SectionDAO();
    $sections = [];
    $sectionDetails = $sectionDAO->retrieve($code, $section);
    foreach ($array_of_bid_objs as $bids){
        $sections[] = $sectionDAO->retrieve($bids->code, $bids->section);
    }
    if($sectionDetails){
        foreach ($sections as $section){
            if ($sectionDetails->day == $section->day){
                $start_now = $sectionDetails->start;
                $start_prev = $section->start;
                $end_now = $sectionDetails->end;
                $end_prev = $section->end;

                if (!(($start_now >= $end_prev)||($end_now <= $start_prev))){
                    $errors[] = 'class timetable clash';
                    break;
                }
            }
        }
    }
    return $errors;
}
#get an array of bids using  bidDAO->retrieve($data[0])
#get code from data[2]
function bidExam($array_of_bid_objs, $code){
    $errors = [];
    $courseDAO = new CourseDAO();
    $courseDetails = $courseDAO->retrieve($code);
    $courses = [];
    foreach ($array_of_bid_objs as $bids){
        $courses[] = $courseDAO->retrieve($bids->code);
    }

    foreach ($courses as $course){
        if ($courseDetails->exam_date == $course->exam_date){
            $start_now = $courseDetails->exam_start;
            $start_prev = $course->exam_start;
            $end_now = $courseDetails->exam_end;
            $end_prev = $course->exam_end;

            if (!(($start_now >= $end_prev)||($end_now <= $start_prev))){
                $errors[] = 'exam timetable clash';
            }
        }
    }
    return $errors;
}


?>