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


function removeWhiteSpace($data){
    $arrayToReturn =[];
    for ($i=0; $i < sizeof($data) ; $i++) {
        array_push($arrayToReturn, trim($data[$i], ' '));  
    }
    return $arrayToReturn;
}

function doBootstrap() {

    $errors = array();
	# need tmp_name -a temporary name create for the file and stored inside apache temporary folder- for proper read address
	$zip_file = $_FILES["bootstrap-file"]["tmp_name"];

	# Get temp dir on system for uploading
	$temp_dir = sys_get_temp_dir();

    # keep track of number of lines successfully processed for each file
    #Add your processed count here
	$prereq_processed=0;

	# check file size
	if ($_FILES["bootstrap-file"]["size"] <= 0)
		$errors[] = "input files not found";

	else {
	
		$zip = new ZipArchive;
		$res = $zip->open($zip_file);

		if ($res === TRUE) {
			$zip->extractTo($temp_dir);
			$zip->close();
            
            #Add your temp directory here
			$prereq_path = "$temp_dir/prerequisite.csv";
            
            #Add your @fopen here
			$prereq = @fopen($prereq_path, "r");
            
            #Add your emptys here
            # empty($prereq) || empty($course) ||.........
			if (empty($prereq) ){
                $errors[] = "input files not found";
                
                #add @unlinks and fclose
				if (!empty($prereq)){
					fclose($prereq);
					@unlink($prereq_path);
                } 
                
			}
			else {
				$connMgr = new ConnectionManager();
                $conn = $connMgr->getConnection();
                 # start processing
                
                /****************start Student****************/


                /****************end Student*****************/



                /****************start Course*****************/
                

                /****************end Course*****************/


                /****************start Section*****************/


                /****************end Section*****************/
                
                /************  Prerequisite Here *******************/
                # truncate current SQL tables here
				$prereqDAO = new PrereqDAO();
                $prereqDAO->removeAll();
                
				# process each line and check for errors
                
                // process each line, check for errors, then insert if no errors
                $header = fgetcsv($prereq);
                $lineCount = 1;
				while (($data = fgetcsv($prereq))!== false){

                    #function to remove all white spaces
                    $data = removeWhiteSpace($data);
                    $lineCount ++;

                    if ( sizeof(checkForEmptyCol($data, $header)) != 0 ) {
                        $errorInRow = checkForEmptyCol($data, $header);
                        $errorDetails = [
                            "file" => "prerequisite.csv",
                            "line" => $lineCount,
                            "message" => $errorInRow
                        ];
                        array_push($errors , $errorDetails);
                       
                    }
                    #Add Validations checker for Prerequisite here
                    #only after your line check for empty fields is working
                    else{
                        $prereqObj = new Prereq($data[0], $data[1]);
                        $prereqDAO->add($prereqObj);
                        $prereq_processed++;
                    }

				}

				// Remember to clean up
				fclose($prereq);
				@unlink($prereq_path);

                /**************** end of Prerequisite *********************** */


                /****************start Course_completed**************** */


                /****************end Course_completed**************** */


                /****************start Bid**************** */

                
                /****************end Bid**************** */

                
            }
        }
    }
}
?>