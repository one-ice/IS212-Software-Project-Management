<?php
require_once 'common.php';

#Use this function to check for empty columns. (See below)
#This function returns an ARRAY of error message for any empty fields
#E.g ["Blank Title", "Blank Description"]
function checkForEmptyCol( $data, $header){
    $errors = [];
    for ($i=0; $i < sizeof($data); $i++) { 
        if (empty($data[$i])){
            $fieldname = "Blank " . $header[$i];
            array_push($errors , $fieldname);
        }
    }
    return $errors;
}


?>