<?php
include_once "include/common.php";

$course = $_GET['code'];
$userid = $_SESSION['username'];

#retrieve bid amount
$bidDAO = new BidDAO();
$bid= $bidDAO->retrieveBid($userid, $course);
$bidded_amt = $bid->amount;

#retrieve student current edollar balance
$studentDAO = new StudentDAO();
$student = $studentDAO->retrieve($userid);
$studentedollar = $student->edollar; 

#retrieve round
$roundDAO = new RoundDAO();
$roundstatus = $roundDAO->retrieveAll()->status;

#drop bid
if ($roundstatus == 'active')
{
    $bid_dropstatus = $bidDAO->remove($userid, $course);
    
    if ($bid_dropstatus == True)
    {
        $edollar_left = $studentedollar + $bidded_amt;
        echo "Bid drop successfully! You have $$edollar_left left.";
        $studentDAO->update($userid, $edollar_left);
        echo "<a href='drop-bid-form.php'><type = 'submit' name = 'back' value = 'Back'> Back </a>";
    }
    else
    {
        echo "Unable to drop bid.";
    }
}
else
{
    echo "Unable to drop bid.";
}

?>
