<?php
require_once "common.php";

#CLEARING ROUND 1

#end of round 1, sort bids from highest to lowest (DESC) -> use SQL statement in DAO
#get all bids from database and put into $bid_obj
#need to get a unqiue list of bidded code and section
function first_clearing(){

    $bidDAO = new BidDAO();
    $code_section = $bidDAO->retrieveCodeSection();
    $studentDAO = new StudentDAO();

    #code_section = [code, section]
    foreach ($code_section as $array){
        #all bids from a course's section. array of objects
        $section_bid = $bidDAO->retrieveBidForEachSection($array[0], $array[1]);
        
        #get vacancy for this section
        $sectionDAO = new SectionDAO();
        $find_vacancy = $sectionDAO->retrieve($array[0],$array[1]);

        $vacancy = $find_vacancy->size;
        #derive min. clearing price based on vacancies
        $sectionstudentDAO = new SectionStudentDAO();
        $enrollment = $sectionstudentDAO->retrieveVacancy($array[0],$array[1]);

        $roundDAO = new RoundDAO();
        $round = $roundDAO->retrieveAll();
        if ($round->round == 2){
            $vacancy = $vacancy - $enrollment;
        }

        if (sizeof($section_bid) >= $vacancy){
            $clearing_price = $section_bid[$vacancy-1]->amount;
 
            $count = 0;
            foreach ($section_bid as $bid_obj){
                if ($bid_obj->amount == $clearing_price){
                    $count +=1;
                }
            }

            if ($count > 1){

                foreach ($section_bid as $bid_obj){ 
                    #to allow clearing only if status is pending
                    // if ( $bid_obj->status == 'pending'){ 
                        if ( ($bid_obj->amount <= $clearing_price) ){
                            $bidDAO->update($bid_obj->userid, $array[0], 'unsuccessful');
                            $student_obj = $studentDAO->retrieve($bid_obj->userid);
                            $existing_edollar = $student_obj->edollar;

                            $studentDAO->update($bid_obj->userid, $existing_edollar + $bid_obj->amount);
                            #update status, then refund them bid amount
                            
                            $fail_bidDAO = new Fail_BidDAO();
                            $fail_bidDAO->add($bid_obj);

                        }
                        else{
                            $bidDAO->update($bid_obj->userid, $array[0], 'successful');
                            $sectionstudentDAO = new SectionStudentDAO();
                            $sectionstudentObj = new SectionStudent($bid_obj->userid, $bid_obj->code, $bid_obj->section, $bid_obj->amount);
                            $sectionstudentDAO->add($sectionstudentObj);
                        }
                    // }
                }
            }else{
                foreach ($section_bid as $bid_obj){ 
                    // if ($bid_obj->status == 'pending') { 
                        if ( ($bid_obj->amount < $clearing_price) ){
                            $bidDAO->update($bid_obj->userid, $array[0], 'unsuccessful');
                            $student_obj = $studentDAO->retrieve($bid_obj->userid);
                            $existing_edollar = $student_obj->edollar;

                            $studentDAO->update($bid_obj->userid, $existing_edollar + $bid_obj->amount);
                            #update status, then refund them the bid amount
                            $fail_bidDAO = new Fail_BidDAO();
                            $fail_bidDAO->add($bid_obj);
                        }
                        else{
                            #bid pass for all here, update status with success, add into section-student table
                            $bidDAO->update($bid_obj->userid, $array[0], 'successful');
                            $sectionstudentDAO = new SectionStudentDAO();
                            $sectionstudentObj = new SectionStudent($bid_obj->userid, $bid_obj->code, $bid_obj->section, $bid_obj->amount);
                            $sectionstudentDAO->add($sectionstudentObj);
                        }
                    // }
                }
            }

        }else{

            
            foreach ($section_bid as $bid_obj){ 
                // if  ($bid_obj->status == 'pending') { 
                    $bidDAO->update($bid_obj->userid, $array[0], 'successful');
                    $sectionstudentDAO = new SectionStudentDAO();
                    $sectionstudentObj = new SectionStudent($bid_obj->userid, $bid_obj->code, $bid_obj->section, $bid_obj->amount);
                    $sectionstudentDAO->add($sectionstudentObj);
                // }
            }
        }
    }    
}

#a clearing price only if there are at least n_vac or more bids for a particular section
#if only 1 bid at clearing price, will be successful. orelse, all bids at clearing price will be dropped

?>
