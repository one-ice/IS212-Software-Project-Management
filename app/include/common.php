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