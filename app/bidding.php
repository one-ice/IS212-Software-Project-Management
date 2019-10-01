<?php
include_once "include/common.php"; 
include_once "meetCriteria.php";

$course = $_GET['course'];
$username = $_SESSION['username'];
$studentDAO =  new StudentDAO();
$studentedollar = ($studentDAO->retrieve($username))->edollar;
if (!isset($_POST['submit']))
{
    echo "<p> You have $$studentedollar.</p>";

    ?>
    <html>
        <body> <form method = 'post'>
    <?php
    echo "Course: <label for ='$course'>$course</label> <br>";
    echo "Section: <input type = 'text' name = 'section'></input><br>";
    echo "Bid Amount: <input type = 'text' name = 'bid_amt'></input> <br>";

    echo "<input type = 'submit' name = 'back' value = 'Back'> </input>";
    echo "<input type = 'submit' name = 'submit' value = 'Submit'></input>";
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
    $errors = meetCriteria($_SESSION['username'],$_POST['bid_amt'],$course,$_POST['section'],"Round 1");
    $bid_amt = $_POST['bid_amt'];
    $bid_section = $_POST['section'];
    // $errors = isMeetCriteria();

    if ($errors == [])
    {
        $bid = new Bid();
        $bid->userid = $username;
        $bid->amount = $bid_amt;
        $bid->code = $course;
        $bid->section = $bid_section;

        #Add bid
        $bidDAO = new BidDAO();
        $status = $bidDAO->add($bid);
        
        if($status == True)
        {

            $amount_left = $studentedollar - $bid_amt;
            echo "<p> Bid placed successfully! 
                      Amount left: $$amount_left
                </p>";
            $studentDAO->update($username, $amount_left);
        }
    }
    else{
            echo "<li>";
            foreach($errors as $error)
            {   
                echo "<ul> $error </ul>";
            }
            echo "</li>";
    }

}
?>

