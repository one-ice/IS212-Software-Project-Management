<?php

require_once "../include/common.php";
require_once "protect_json.php";

$r = $_REQUEST['r'];
$json_decoded = json_decode($r, true);
$course = trim($json_decoded['course']);
$section = trim($json_decoded['section']);

$result = [];
$message = [];

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
    #Get current round
    $roundDAO = new RoundDAO();
    $current_round = $roundDAO->retrieveAll();
    $round = $current_round->round;
    $status = $current_round->status;

    if ($status == 'active')
    {
        $bidDAO = new BidDAO();
        $bids = $bidDAO->retrieveBidForEachSection($course, $section);

        $totalbids = 0;
        $totalbids = count($bids);
        $result = [];
        $values = [];
        if($totalbids > 0)
        {
            $bidcount = 0;

            foreach($bids as $bid)
            {
                $bidcount++;
                if($bid->status == 'successful')
                {
                    $values[] = [
                                    'row' => $bidcount,
                                    'userid' => $bid->userid,
                                    'amount' => $bid->amount,
                                    'result' => 'in'
                                ];
                }
                elseif($bid->status == 'unsuccessful')
                {
                    $values[] = [
                                    'row' => $bidcount,
                                    'userid' => $bid->userid,
                                    'amount' => $bid->amount,
                                    'result' => 'out'
                                ];
                }
                else
                {
                    $values[] = [
                                    'row' => $bidcount,
                                    'userid' => $bid->userid,
                                    'amount' => $bid->amount,
                                    'result' => '-'
                                ];
                }
            }

        }
        $result = ["status" => 'success',
                "bids" => $values];
            
    }
    elseif($status == 'inactive')
    {
        $sectionstudentDAO = new SectionStudentDAO();
        $successbids = $sectionstudentDAO->retrieveAllCourseAndSection($course,$section);

        $totalsuccessbids = 0;
        $totalsuccessbids = count($successbids);

        $failbidDAO = new Fail_BidDAO();
        $failbids =  $failbidDAO->retrieveBidsbyCodeSection($course,$section);

        $totalfailbids = 0;
        $totalbids = count($failbids);

        $result = [];
        $values = [];
        if($totalsuccessbids > 0 || $totalfailbids > 0)
        {
            $bidcount = 0;

            foreach($successbids as $successbid)
            {
                $bidcount++;
                $values[] = [
                                'row' => $bidcount,
                                'userid' => $successbid->userid,
                                'amount' => $successbid->amount,
                                'result' => 'in'
                            ];
            }
            foreach($failbids as $failbid)
            {
                $bidcount++;
                $values[] = [
                                'row' => $bidcount,
                                'userid' => $failbid->userid,
                                'amount' => $failbid->amount,
                                'result' => 'out'
                            ];
            
            }
        }
        $result = ["status" => 'success',
                        "bids" => $values];
    }
}
else
{       sort($message);
        $result = ["status" => 'error', 
                    "message" => $message];
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
?>