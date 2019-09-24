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
    $student_processed = 0;
    $course_processed = 0;
    $section_processed = 0; 
    $prereq_processed=0;
    $course_completed_processed = 0;
    $bid_processed = 0;

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
            $student_path = "$temp_dir/student.csv";
            $course_path = "$temp_dir/course.csv";
            $section_path = "$temp_dir/section.csv";
            $prereq_path = "$temp_dir/prerequisite.csv";
            $course_completed_path = "$temp_dir/course_completed.csv";    
            $bid_path = "$temp_dir/bid.csv";
            
            #Add your @fopen here
            $student = @fopen($student_path, "r");
            $course = @fopen($course_path, "r");
            $section = @fopen($section_path, "r");
            $prereq = @fopen($prereq_path, "r");
            $course_completed = @fopen($course_completed_path, "r");
            $bid = @fopen($bid_path, "r" );
            
            #Add your emptys here
            # empty($prereq) || empty($course) ||.........
            if (empty($student) || empty($course)||empty($section) ||
                empty($prereq) || empty($course_completed)|| empty($bid)){
                $errors[] = "input files not found";
                
                #add @unlinks and fclose
                if (!empty($student)){
					fclose($student);
					@unlink($student_path);
                } 
                if (!empty($course)){
					fclose($course);
					@unlink($course_path);
                } 
                if (!empty($section)){
					fclose($section);
					@unlink($section_path);
                } 
				if (!empty($prereq)){
					fclose($prereq);
					@unlink($prereq_path);
                } 
                if (!empty($course_completed)){
					fclose($course_completed);
					@unlink($course_completed_path);
                } 
                if (!empty($bid)){
					fclose($bid);
					@unlink($bid_path);
                } 
			}
			else {
				$connMgr = new ConnectionManager();
                $conn = $connMgr->getConnection();
                 # start processing
                
                /****************start Student****************/
                $studentDAO = new StudentDAO();
                $studentDAO->removeAll();

                $header = fgetcsv($student);
                $lineCount = 1;
				while (($data = fgetcsv($student))!= false){
                    $data = removeWhiteSpace($data);
                    $lineCount++;
                    $errors_in_student = isStudentValid($data[0],$data[1],$data[2],$data[4]);
                    if (sizeof(checkForEmptyCol($data, $header)) !== 0 ) {
                        $errorInRow = checkForEmptyCol($data, $header);
                        $errorDetails = [
                            "file" => "student.csv",
                            "line" => $lineCount,
                            "message" => $errorInRow
                        ];
                        array_push($errors , $errorDetails);
                    }
                    #Add Validations checker for Student here
                    #only after your line check for empty fields is working
                    elseif(count($errors_in_student)>0)
                    {
                        $errorDetails = [
                            "file" => "student.csv",
                            "line" => $lineCount,
                            "message" => $errors_in_student
                        ];
                        array_push($errors , $errorDetails);
                    }
                    else{
                        $studentobj = new Student($data[0], $data[1], $data[2], $data[3], $data[4]);
                        $studentDAO->add($studentobj);
                        $student_processed++;
                    }
                }

                // Remember to clean up
                fclose($student);
				@unlink($student_path);
                /****************end Student*****************/



                /****************start Course*****************/
                $courseDAO =  new courseDAO();
                
                # truncate current SQL tables
                $courseDAO-> removeAll();
                $header = fgetcsv($course);   #to get past the first line
                
                $lineCount = 1;

                while (($data=fgetcsv($course))!= False){
                    
                    $data = removeWhiteSpace($data);
                    $lineCount ++;
            
                    if ( sizeof(checkForEmptyCol($data, $header)) != 0 ) {
                        $errorInRow = checkForEmptyCol($data, $header);
                        $errorDetails = [
                            "file" => "course.csv",
                            "line" => $lineCount,
                            "message" => $errorInRow
                        ];
                        array_push($errors , $errorDetails);
                    }
            
                    // elseif(){
                    // add validation!
                    // }
            
                    else{
                        $courseObj = new course($data[0], $data[1], $data[2], $data[3], 
                        $data[4], $data[5], $data[6]);
                        $courseDAO->add($courseObj);
                        $course_processed++;
                    }
                }
                 // Remember to clean up
				fclose($course);
                @unlink($course_path);

                /****************end Course*****************/


                /****************start Section*****************/
                # start processing
                $sectionDAO =  new sectionDAO();
                                
                # truncate current SQL tables
                $sectionDAO-> removeAll();
                $header = fgetcsv($section);   #to get past the first line

                $lineCount = 1;

                while (($data=fgetcsv($section))!= False){
                    $data = removeWhiteSpace($data);
                    $lineCount ++;
                    $error_in_section = isSectionValid($data[0], $data[1], $data[2], $data[3], 
                    $data[4], $data[5], $data[6], $data[7]);

                    if ( sizeof(checkForEmptyCol($data, $header)) != 0 ) {
                        $errorInRow = checkForEmptyCol($data, $header);
                        $errorDetails = [
                            "file" => "section.csv",
                            "line" => $lineCount,
                            "message" => $errorInRow
                        ];
                        array_push($errors , $errorDetails);
                    }
                    
                    //add extra validation here

                    else{
                        $sectionObj = new section($data[0], $data[1], $data[2], $data[3], 
                        $data[4], $data[5], $data[6], $data[7]);
                        $sectionDAO->add($sectionObj);
                        $section_processed++;
                    }
                }
                // Remember to clean up
                fclose($section);
                @unlink($section_path);

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
                       
                    }elseif(isPrerequisiteValid($data[0],$data[1])){
                        $errorInRow = isPrerequisiteValid($data[0],$data[1]); 
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
                $course_completedDAO = new Course_CompletedDAO();
                $course_completedDAO->removeAll();

                $header = fgetcsv($course_completed);
                $lineCount = 1;
				while (($data = fgetcsv($course_completed))!= false){
                    $data = removeWhiteSpace($data);
                    $lineCount ++;
                    $errors_in_course_completed = isCourse_CompletedValid($data[0],$data[1]);
                    if (sizeof(checkForEmptyCol($data, $header)) !== 0 ) {
                        $errorInRow = checkForEmptyCol($data, $header);
                        $errorDetails = [
                            "file" => "course_completed.csv",
                            "line" => $lineCount,
                            "message" => $errorInRow
                        ];
                        array_push($errors, $errorDetails);
                    }
                    #Add Validations checker for Course_completed here
                    #only after your line check for empty fields is working
                    elseif(count($errors_in_course_completed)>0)
                    {
                        $errorDetails = [
                            "file" => "course_completed.csv",
                            "line" => $lineCount,
                            "message" => $errors_in_course_completed
                        ];
                        array_push($errors , $errorDetails);
                    }
                    else{
                        $course_completedobj = new Course_Completed($data[0], $data[1]);
                        $course_completedDAO->add($course_completedobj);
                        $course_completed_processed++;
                    }
                }

                // Remember to clean up
				fclose($course_completed);
				@unlink($course_completed_path);

                /****************end Course_completed**************** */


                /****************start Bid**************** */
                $bidDAO = new bidDAO();
                $bidDAO->removeAll();

				#processing
				$header = fgetcsv($bid);
                $lineCount = 1;
				while (($data = fgetcsv($bid))!== false){
                    $data = removeWhiteSpace($data);
                    $lineCount ++;
                    if ( sizeof(checkForEmptyCol($data, $header)) != 0 ) {
                        $errorInRow = checkForEmptyCol($data, $header);
                        $errorDetails = [
                            "file" => "bid.csv",
                            "line" => $lineCount,
                            "message" => $errorInRow
                        ];
                        array_push($errors , $errorDetails);
                       
                    }
					else {
						#Validation Check#
						#Invalid userid
						#Invalid amount
						#Invalid Course
                        #Invalid section
                        $bidObj = new Bid($data[0], $data[1], $data[2], $data[3]);
						$bidDAO->add($bidObj);
                        $bid_processed++;
					}
                }	 

				fclose($bid);
				@unlink($bid_path);
                
                /****************end Bid**************** */

                
            }
        }
    }
    
	 
	if (!isEmpty($errors))
	{	
        //ignore these 2 lines below, next time then need to sort
		//$sortclass = new Sort();
		//$errors = $sortclass->sort_it($errors,"bootstrap");
		$result = [ 
            "status" => "error",
            "num-record-loaded" =>  [
                #Add processed count for your csv files, IN THIS FORMAT
                #["Name.csv" => $name_processed] ,
                [
                "student.csv" => $student_processed,
                "course.csv" => $course_processed,
                "section.csv" => $section_processed,
                "prerequisite.csv" => $prereq_processed,
                "course_completed.csv" => $course_completed_processed,
                "bid.csv" => $bid_processed
                ],
            ],
			"messages" => $errors
		];
    }
    
	else
	{	
		$result = [ 
			"status" => "success",
            "num-record-loaded" => [
                #Add processed count for your csv files, IN THIS FORMAT
                #["Name.csv" => $name_processed] ,
                "student.csv" => $student_processed,
                "course.csv" => $course_processed,
                "section.csv" => $section_processed,
                "prerequisite.csv" => $prereq_processed,
                "course_completed.csv" => $course_completed_processed,
                "bid.csv" => $bid_processed
			]
		];
    }
    
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);
	
}
?>