<?php
require_once "common.php";

# bid round 2 validation, done AFTER EACH BID
# Sort bid from highest to lowest -> SQL
# Assume that round 1 bids are deleted (Need to do)

#Find the number of vacancies in EACH section
#this function checks a USER's bid against the min bid

function second_bid_valid($userid, $code, $section, $amount){

    $sectionStudentDAO = new SectionStudentDAO();
    $sectionDAO = new SectionDAO();
    $bidDAO = new BidDAO();
    $studentDAO = new StudentDAO();
    
    $state = "";

    #get current vacancy for THIS section after round1
    $find_vacancy = $sectionDAO->retrieve($code,$section);
    $full_vacancy = $find_vacancy->size;
    $section_enrollment =  $sectionStudentDAO->retrieveVacancy($code, $section);
    $current_vacancy = $full_vacancy - $section_enrollment;

    #get current min bid in Min Bid table, default should be 10.
    $minBidNow = $sectionDAO->retrieveMinBid($code, $section);
    
  

    return $state;
}



?>