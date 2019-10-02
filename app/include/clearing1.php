<?php
require_once "common.php";

#CLEARING ROUND 1

#end of round 1, sort bids from highest to lowest (DESC) -> use SQL statement in DAO
#get all bids from database and put into $bid_obj
#need to get a unqiue list of bidded code and section
function first_clearing(){
    #code_section = [code, section]
  
    #all bids from a course's section. array of objects
       
    #get vacancy for this section
    
    #derive min. clearing price based on vacancies

}

#a clearing price only if there are at least n_vac or more bids for a particular section
#if only 1 bid at clearing price, will be successful. orelse, all bids at clearing price will be dropped

?>
