<?php

require_once "../include/common.php";
require_once "protect_json.php";
require_once "../include/token.php";

$r = $_REQUEST['r'];
$json_decoded = json_decode($r, true);
$userid = $json_decoded['userid'];

$result = [];
$message = [];

#Check if userid is missing
if(!array_key_exists('userid', $json_decoded))
{
    $message[] = 'missing userid';
}
#Check if userid is empty
elseif($userid == '')
{
    $message[] = 'blank userid';
}
else
{
    #Check if userid exist
    $studentDAO = new StudentDAO();
    $student = $studentDAO->retrieve($userid);
    if ($student == null)
    {
        $message[] = 'invalid userid';
    }
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
?>
