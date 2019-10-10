<?php
include_once "include/common.php"; 
include_once "meetCriteria.php";
require_once 'include/clearing2.php';

$course = $_GET['course'];
$username = $_SESSION['username'];
$studentDAO =  new StudentDAO();
$studentedollar = ($studentDAO->retrieve($username))->edollar;
if (!isset($_POST['submit']))
{
    echo "<p> You have $$studentedollar.</p>";
}

?>
<html>
    <body> <form method = 'post'>
<?php

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
echo "Course: <label for = 'course'>$course</label> <br>";

#Get section for selected course
$sectionDAO = new SectionDAO();

$sections = $sectionDAO->retrievebyCourse($course);
if (count($sections) != 0)
{
    echo "<br><table border = '1'>
        <tr> <th> Section </th> <th> Day </th> <th> Start </th>
        <th> End </th> <th> Instructor </th> <th>	Venue </th> </tr>";
    foreach ($sections as $section)
    {
        echo "<tr> <td> {$section->section} </td>
                    <td> {$days[$section->day -1]} </td> 
                    <td> {$section->start} </td>
                    <td> {$section->end} </td>
                    <td> {$section->instructor} </td>
                    <td> {$section->venue} </td></tr>";
    }

    echo "</table><table border = '1'>";
    echo "<tr> <td colspan  = '5'> Section: </td> <td> <select name = 'section' style='width: 80px'>";
    foreach ($sections as $section)
    {
        echo " <option value = '$section->section'> $section->section </option>";
    }
    echo "</select></td></tr>";
    echo "<tr> <td colspan = '5'> Bid Amount: </td> <td> <input type = 'text' name = 'bid_amt' style = 'width: 80px'></input> </td></tr></table>";
    echo "<tr> <td> <input type = 'submit' name = 'back' value = 'Back' > </input> </td>";
    echo "<td> <input type = 'submit' name = 'submit' value = 'Submit'></input> </td> </tr>";
    echo"</table><br>";
}
else
{
    echo "<p> No sections available to bid </p>";
}
?>
    </form> </body> 
</html>

<?php

if (isset($_POST['back']))
{
    header("Location: bidhome.php");
    return;
}

if(isset($_POST['submit']))
{
    $difference = 0;
    $errors = [];
    $bid_amt = 0;
    $bidDAO = new BidDAO();
    $roundDAO = new RoundDAO();
    $round = $roundDAO->retrieveAll();
    #this part the $round retrieves an OBJECT so that we know the round and the status
    $errors = meetCriteria($_SESSION['username'],$_POST['bid_amt'],$course,$_POST['section'],$round);
    $bid_amt = $_POST['bid_amt'];
    $bid_section = $_POST['section'];

    if ($errors == [])
    {
        $bid_exist = $bidDAO->retrieveBid($username,$course);
        if (($bid_exist != null) && ($bid_exist->status == 'pending') && ($round->round == 1) && ($round->status == 'active') )
        {
            $bid_status = 'pending';
            $status = $bidDAO->updateAll($username,$course,$bid_section,$bid_amt, $bid_status);

            $previous_bid_amt = $bid_exist->amount;
            if ($previous_bid_amt >= $bid_amt)
            {
                $difference = $previous_bid_amt - $bid_amt;
                $amount_left = $studentedollar + $difference;
                $studentDAO->update($username, $amount_left);
            }
            elseif ($bid_amt >= $previous_bid_amt)
            {
                $difference = $bid_amt - $previous_bid_amt;
                $amount_left = $studentedollar - $difference;
                $studentDAO->update($username, $amount_left);
            }   
            echo "<p> Bid updated successfully! 
                    Amount left: $$amount_left </p>";
        }
        elseif ($bid_exist == null)
        {
            $bid = new Bid();
            $bid->userid = $username;
            $bid->amount = $bid_amt;
            $bid->code = $course;
            $bid->section = $bid_section;
            $bid->status = "pending";

            #Add bid
            $bidDAO = new BidDAO();
            $add_status = $bidDAO->add($bid);

            if($add_status == True)
            {
                //if ( ($round->round == 1)  && ($round->status == 'active') ){
                    $amount_left = $studentedollar - $bid_amt;
                    $studentDAO->update($username, $amount_left);
                    echo "<p> Bid placed successfully! 
                                Amount left: $$amount_left </p>";
                //}
                if ( ($round->round == 2) && ($round->status == 'active') ){
                    $state = second_bid_valid($_SESSION['username'], $course, $_POST['section'], $_POST['bid_amt']);
                    if ($state == 'Successful')
                    {
                        $bid_status = 'successful';
                    }
                    else
                    {
                        $bid_status = 'unsuccessful';
                    }
                    $bidDAO->update($username,$course,$bid_status);
                }
            }
        }
    }
    else{
            echo "<ul>";
            foreach($errors as $error)
            {   
                echo "<li> $error </li>";
            }
            echo "<ul>";
    }

}
?>

