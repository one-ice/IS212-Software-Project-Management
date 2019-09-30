<?php
include_once "include/common.php"; 

$course = $_GET['course'];
$username = 'amy.ng.2009';
$studentDAO =  new StudentDAO();
#retrieve student's edollar
$studentedollar = ($studentDAO->retrieve($username))->edollar;
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
?>
    </form> </body> 
</html>

<?php

if (isset($_POST['back']))
{
    header("Location: bidhome.php");
    return;
}

?>