<?php

require_once "../include/common.php";
require_once "protect_json.php";
require_once '../include/clearing2.php';

$message = [];
$result = [];
$r = $_REQUEST['r'];    
$json_decoded = json_decode($r, true);
$course = trim($json_decoded['course']);
$section = trim($json_decoded['section']);

#Get round
$roundDAO = new RoundDAO();
$roundR= $roundDAO->retrieveAll();
$round = $roundR->round;
$status = $roundR->status;

#Check if course is missing
if(!array_key_exists('course', $json_decoded))
{
    $message[] = 'missing course';
}
#Check if course is empty
elseif ($course == '')
{
    $message[] = 'blank course';
}
#Check if section is missing
if(!array_key_exists('section', $json_decoded))
{
    $message[] = 'missing section';
}
#Check if section is empty
elseif($section == '')
{
    $message[] = 'empty section';
}
else
{
    #Check if course exist
    $courseDAO = new CourseDAO();
    $course_exist = $courseDAO->retrieve($course);;
    if($course_exist == False)
    {
        $message[] = 'invalid course';
    }
    else
    {   
        #Check if section exist
        $sectionDAO = new SectionDAO();
        $section_exist = $sectionDAO->retrievebyCourseAndSection($course, $section);
        if($section_exist == null)
        {
            $message[] = 'invalid section';
        }
    }
}

if($message == [])
{
    $students = [];
    $studentDAO = new StudentDAO();
    #get bids for course, section
    $bidDAO = new BidDAO();
    $bids = $bidDAO->retrieveBidForEachSection($course, $section);

    if($round == 1 && $status == "active")
    {
        #get vacancy
        $sectionDAO = new SectionDAO();
        $section_exist = $sectionDAO->retrievebyCourseAndSection($course, $section);
        $vacancy = $section_exist[0]->size;

        #get number of bids
        $no_of_bids = count($bids);

        $min_bid_price = 0;
        if($no_of_bids == 0)
        {
            $min_bid_price = 10;
        }
        elseif($no_of_bids < $vacancy)
        {
            $bid_amt = [];
            foreach($bids as $bid)
            {
                $bid_amt[] = $bid->amount;
            }
            $min_bid_price = min($bid_amt);
        }
        elseif($no_of_bids >= $vacancy)
        {
            $min_bid_price = $bids[$vacancy-1]->amount;
        }

        foreach($bids as $bid)
        {
            $bid_amt = $bid->amount;
            $studentDAO = new StudentDAO();
            $student = $studentDAO->retrieve($bid->userid);
            $balance = $student->edollar;
            $students[] = [
                "userid" => $bid->userid,
                "amount" => $bid_amt,
                "balance" => $balance,
                "status" => $bid->status
            ];
        }
    }
    elseif($round == 1 && $status == "inactive")
    {
        $lowest_sucessful_bid = 0;

        #get vacancy
        $sectionDAO = new SectionDAO();
        $section_exist = $sectionDAO->retrievebyCourseAndSection($course, $section);
        $size = $section_exist[0]->size;
        $sectionstudentDAO = new SectionStudentDAO();
        $enrolled = $sectionstudentDAO->retrieveVacancy($course, $section);
        $vacancy = $size - $enrolled;
        #lowest successful bid
        if ($enrolled > 0)
        {
            $enrolled_course = $sectionstudentDAO->retrieveAllCourseAndSection($course,$section);
            $successful_bids_amt = [];
            foreach($enrolled_course as $enroll)
            {
                $successful_bids_amt[] = $enroll->amount;
            }
            $min_bid_price = min($successful_bids_amt);
            var_dump($successful_bids_amt);
        }
        else
        {
            $min_bid_price = 10;
        }
        $enrolled_course = $sectionstudentDAO->retrieveAllCourseAndSection($course,$section);
        foreach($enrolled_course as $enroll)
        {
            $studentDAO = new StudentDAO();
            $student = $studentDAO->retrieve($enroll->userid);
            $balance = $student->edollar;
            $students[] = [
                "userid" => $enroll->userid,
                "amount" => $enroll->amount,
                "balance" => $balance,
                "status" => 'successful'
            ];
        }
        $fail_bidDAO = new Fail_BidDAO();
        $failed_bids = $fail_bidDAO->retrieveBidsbyCodeSection($course,$section);
        foreach($failed_bids as $fail)
        {
            $studentDAO = new StudentDAO();
            $student = $studentDAO->retrieve($fail->userid);
            $balance = $student->edollar;
            $students[] = [
                "userid" => $fail->userid,
                "amount" => $fail->amount,
                "balance" => $balance,
                "status" => 'unsuccessful'
            ];
        }

    }
    elseif($round == 2 && $status == 'active')
    {
        #get vacancy
        $sectionDAO = new SectionDAO();
        $section_exist = $sectionDAO->retrievebyCourseAndSection($course, $section);
        $vacancy = $section_exist[0]->size;
        foreach($bids as $bid)
        {
            $bid_amt = $bid->amount;
            $minBidInfo = $sectionDAO->retrieveByCourseAndSection($bid->code,$bid->section);
            $bid_status = second_bid_valid($bid->userid,$bid->code, $bid->section, $bid_amt);

            $studentDAO = new StudentDAO();
            $student = $studentDAO->retrieve($bid->userid);
            $students[] = [
                "userid" => $bid->userid,
                "amount" => $bid_amt,
                "balance" => $student->edollar,
                "status" => $bid_status
            ];

            $min_bid_price = $minBidInfo[0]->min_bid;
        }
        
        if($min_bid_price != null)
        {
            $min_bid_price = $minBidInfo[0]->min_bid;
        }
        else
        {
            $min_bid_price = 10;
        }
        
    }
    elseif($round == 2 && $status == 'inactive')
    {
        #get vacancy
        $sectionDAO = new SectionDAO();
        $section_exist = $sectionDAO->retrievebyCourseAndSection($course, $section);
        $size = $section_exist[0]->size;
        echo($size);
        $sectionstudentDAO = new SectionStudentDAO();
        $enrolled = $sectionstudentDAO->retrieveVacancy($course, $section);
        
        #lowest successful bid
        if ($enrolled != null)
        {
            if ($enrolled > 0)
            {
                $succesful_bids_amt = [];
                $enrolled_course = $sectionstudentDAO->retrieveAllCourseAndSection($course,$section);
                $no_of_enrollment = sizeof($enrolled_course);
                foreach($enrolled_course as $enroll)
                {
                    $studentDAO = new StudentDAO();
                    $student = $studentDAO->retrieve($enroll->userid);
                    $balance = $student->edollar;
                    $succesful_bids_amt[] = $enroll->amount;
                    

                    $students[] = [
                        "userid" => $enroll->userid,
                        "amount" => $bid_amt,
                        "balance" => $balance,
                        "status" => "successful"
                    ];
                }
                $vacancy = $size - $no_of_enrollment;
                $min_bid_price = min($succesful_bids_amt);
            }
            else
            {
                $min_bid_price = 10;
            }
        }
    }
    $result = 
    [
        "status" => "success",
        "vacancy" => $vacancy,
        "min_bid_amt" => $min_bid_price,
        "students" => $students
    ];
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);
?>
