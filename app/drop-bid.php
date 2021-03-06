<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>BIOS: Drop Bid</title>

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
  <a class="navbar-brand" href="bidhome.php" style="color:white;">BIOS: Bidding</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" style="color:white;" href="bidhome.php">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" style="color:white;" href="drop-bid-form.php">Drop Bid<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" style="color:white;" href="drop_section.php">Drop Section<span class="sr-only">(current)</span></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" style="color:white;" href="bidding_status.php">Bidding Status</a>
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
require_once "include/protect.php";
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
        $studentDAO->update($userid, $edollar_left);
		echo "<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header' style='font-size:1.25em;color:green;font-weight:bold'>
		Success: Bid drop successfully! You have $$edollar_left left.<br/><br/>
		<a href='drop-bid-form.php'><button type='button' class='btn btn-info'>Back</button></a>
		</div>
		</div>";
    }
    else
    {
        echo "<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header' style='font-size:1.25em;color:red;font-weight:bold'>
		Error: Unable to drop bid<br/><br/>
		<a href='drop-bid-form.php'><button type='button' class='btn btn-info'>Back</button></a>
		</div>
		</div>";
    }
}
else
{
	 echo "<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header' style='font-size:1.25em;color:red;font-weight:bold'>
		Error: Unable to drop bid<br/><br/>
		<a href='drop-bid-form.php'><button type='button' class='btn btn-info'>Back</button></a>
		</div>
		</div>";

}

?>


</div>
</main>
</body>

<footer class="footer mt-auto py-3" style="background-color: #01579b;">
  <div class="container">
    <span style="float:left;color:white">&copy;2019 echo "T4";</span>
	<span style="float:right;color:white;">All Rights Reserved</span>
  </div>
</footer>