<?php

// this will autoload the class that we need in our code
spl_autoload_register(function($class) {
 
    // we are assuming that it is in the same directory as common.php
    // otherwise we have to do
    // $path = 'path/to/' . $class . ".php"    
    require_once "$class.php"; 
  
});


// session related stuff

session_start();


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
            if (empty($value)) {
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
        if($endFormat = DateTime::createFromFormat('H:i',$examEnd)){
            if($endFormat>$startFormat){
                $examEndVali = TRUE;
            }   

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

#section validation
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
    if ($check != False){
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
        if($endFormat = DateTime::createFromFormat('H:i',$end)){
            if($endFormat>$startFormat){
                $endValid = TRUE; 
            }   
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
    else{
        if (!$sectionValid){
            $errors[] = "invalid section";
        }
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
    
    #check if code exist in course
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
    return $errors;
}
}

#Bid Validations

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
        $errors[] = "invalid course code";
    }
	else {
		$errors = checkValidSection($courseCode, $sectionID);
	}
	return $errors;
}

function checkValidSection($courseCode, $sectionID) {
	$errors = [];
	$sectionDAO = new SectionDAO();
	$result = $sectionDAO->retrieve($courseCode, $sectionID);
	if (!$result){
		$errors[] = "invalid section code";
	}
	return $errors;
}

function checkValidAmt($amt) {
    #Less than $10
    $errors = [];
	if ($amt < 10) {
		$errors[] = "invalid Bid Amount" ;
	}
	#Not Numeric in Amount
	elseif (!preg_match('/^-?[0-9]+(?:\.[0-9]{1,2})?$/', $amt)) {
	    $errors[] =  "invalid Bid Amount";
	}
	return $errors;

}

?>