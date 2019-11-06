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
    
    if ($current_vacancy == 0){ 
        $state = 'Unsuccessful';
    }
    else{
        #find number of bids placed for THIS section
        $bids_now = $bidDAO->retrieveBidForEachSection($code, $section);
        $num_of_bids = sizeof($bids_now);

        #compare with current vacancy, and get the clearing price (need to +1 to the min bid)
        if($num_of_bids > $current_vacancy){
            $clearing_bid = $bids_now[$current_vacancy-1];
            $clearing_price = $clearing_bid->amount;

            if ($amount <= $clearing_price){
                $state = 'Unsuccessful';
            }
            else{
                $state = 'Successful'; 
                
                if($clearing_price > $minBidNow){
                #change minbid price
                    $sectionDAO->updateMinBid($clearing_price+1, $code, $section);
                    $minBidNow = $sectionDAO->retrieveMinBid($code, $section);
                }

            }
            #find the number of people at clearing price
       
        }
        else{
            $state = 'Successful';
        }

        
        
    }    

    return $state;
}



?>