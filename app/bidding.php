<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>BIOS: Bidding</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<!-- Bootstrap.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #01579b">
  <a class="navbar-brand" href="#" style="color:white;">BIOS: Bidding</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" style="color:white;" href="bidhome.php">Bid Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:white;" href="logout.php">Logout</a>
      </li>
      </li>
    </ul>
  </div>
</nav>
<!-- Navigation Bar -->

<body class="d-flex flex-column h-100" style="background-color: #eeeeee;">
	<main role="main" class="flex-shrink-0">
<div class="container">


<?php
include_once "include/common.php"; 
include_once "meetCriteria.php";
require_once 'include/clearing2.php';

$course = $_GET['course'];
$username = $_SESSION['username'];
$name = explode(".", $username);
$firstName = ucfirst($name[0]);
$lastName = ucfirst($name[1]);
$studentDAO =  new StudentDAO();
$studentedollar = ($studentDAO->retrieve($username))->edollar;

?>
<html>
    <body> <form method = 'post'>
<?php

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

 
#Get section for selected course
$sectionDAO = new SectionDAO();

$sections = $sectionDAO->retrievebyCourse($course);
if (count($sections) != 0)
{
    echo "<br>
		<div class='container'>
		<div class='row'>
		<div class='col-sm'>
		<div class='card bg-light mb-3' style='margin-top:30px;'>
		<div class='card-header'>Course selected: <label for = 'course'>$course</label></div>
		<div class='card-body'>
		<table style='margin-bottom:30px;border-style:solid;border-width: 1.5px 1.5px 1.5px 1.5px;' class='table table-striped table-hover table-sm table-responsive'>
        <tr> 
		<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Section </th> 
		<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Day </th> 
		<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Start </th>
        <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> End </th> 
		<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Instructor </th> 
		<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Venue </th> 
		</tr>";
    foreach ($sections as $section)
    {
        echo "<tr>
				<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$section->section} </td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$days[$section->day -1]} </td> 
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$section->start} </td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$section->end} </td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$section->instructor} </td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$section->venue} </td>
			</tr>";
    }

    echo "</table></div></div></div>";

	echo "<div class='col-sm'>
			<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header'>$firstName $lastName, you have $$studentedollar in your account</div>
			<div class='card-body'>
			<div class='row'>
			<div class='col-sm'>
			Section: 
			<select name = 'section' style='width: 80px'>";
			foreach ($sections as $section)
    {
        echo " <option value = '$section->section'> $section->section </option>";
    }
	echo "</select>
		<br/>
		</div>";
			
	echo "<div class='col-sm'>
	Bid Amount: 
	<input type = 'text' name = 'bid_amt' style = 'width: 80px'></input>
	</div>
	</div>
	<br/>

	<div class='row'>
	<div class='col-sm align-self-start'>
	<a href='bidhome.php'><div type = 'submit' name = 'back' value = 'Back' class='btn btn-outline-dark'>Back </div></a>
	</div>
	<div class='col-smalign-self-end'>
	<input type = 'submit' name = 'submit' value = 'Submit' class='btn btn-outline-primary' style='margin-right: 10px;'></input>
	</div>
	</div>
	 
	</div>
	</div>
	</div>
	</div>
	</div>
	<br>";
}
else
{
    echo "<p> No sections available to bid </p>";
}
?>
    </form> </body> 
</html>

<?php



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
        if (($bid_exist != null) && ($bid_exist->status == 'pending') && ($round->round == 1) && ($round->status == 'active') && ($bid_exist->code == $course))
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
        elseif (($bid_exist != null) && ($bid_exist->status == 'unsuccessful') && ($round->round == 2) && ($round->status == 'active') && ($bid_exist->code == $course))
        {
            $bid_status = 'pending';
            $status = $bidDAO->updateAll($username,$course,$bid_section,$bid_amt, $bid_status);

            if ($status == True)
            {
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
                $amount_left = $studentedollar - $bid_amt;
                $studentDAO->update($username, $amount_left);
                echo "<p> Bid placed successfully! 
                    Amount left: $$amount_left </p>";
            }
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
                    // $bidDAO->update($username,$course,$bid_status);
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
            echo "</ul>";
    }

}
?>

</div>
		</div>
	</div>
	</main>
	</body>

<footer class="footer mt-auto py-3" style="background-color: #01579b;">
  <div class="container">
    <span style="float:left;color:white">&copy;2019 echo "T4";</span>
	<span style="float:right;color:white;">All Rights Reserved</span>
  </div>
</footer>