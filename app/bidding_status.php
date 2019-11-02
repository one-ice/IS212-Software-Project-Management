<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>BIOS: Bidding Status</title>

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
  <a class="navbar-brand" href="bidhome.php" style="color:white;">BIOS: Bidding Status</a>
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
spl_autoload_register(function($class){
    require_once "include/$class.php"; 
});
require_once "include/protect.php";
require_once 'include/clearing2.php';
// session_start();
# round 0, round 1 active, round 1 inactive, round 2 active, round 2 inactive
# in round 0, students can only see "not avalible"
# in round 1 active, all the status of courses should be "pending" (retrieve from "bidDAO")
# in round 1 inactive and round 2 inactive, stu can see "successful" and "unsuccessful" (retrieve from sectionStudent and fail_bid)
# in round 2 active, student can see "successful" "unsuccessful"
$roundDAO = new RoundDAO();
$roundR= $roundDAO->retrieveAll();
$round = $roundR->round;
$status = $roundR->status;
// echo $round;
// echo $status;

    # retrieve the courses the user successfully bid
    $sectionStuDAO = new SectionStudentDAO();
    $successInfo = $sectionStuDAO->retrieveByUserID($_SESSION['username']);

    # retrieve the courses the user fail to bid
    $failBidDAO = new Fail_BidDAO();
    $failBidInfo = $failBidDAO->retrieveByUserID($_SESSION['username']);

    # retrieve from bid table -- pending
    $BidDAO = new BidDAO();
    $pendingInfo = $BidDAO->retrieve($_SESSION["username"]);
	$name = $_SESSION["username"];
    
	echo "<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header' style='font-size:1.10em;'>
			Welcome $name <br/>Here are your Bidding Results
		</div>
		</div>";

# showing the status in each round and status
if($round == 0){
    echo "not avalible";
}elseif($round == 1 && $status == "active" && empty($pendingInfo)){
	echo "<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header' style='font-size:1.25em;color:red;font-weight:bold'>
		Error: You haven't bidded a course<br/><br/>
		</div>
		</div>";
}elseif($round == 1 && $status == "inactive" && empty($successInfo) && empty($failBidInfo)){
    echo "<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header' style='font-size:1.25em;color:red;font-weight:bold'>
		Error: You haven't bidded a course<br/><br/>
		</div>
		</div>";
}elseif($round == 2 && $status == "active" && empty($pendingInfo) && empty($failBidInfo) && empty($successInfo)){
    echo "<div class='card bg-light mb-3' style='margin-top:30px;'>
			<div class='card-header' style='font-size:1.25em;color:red;font-weight:bold'>
		Error: You haven't bidded a course<br/><br/>
		</div>
		</div>";
}
elseif($round == 1 && $status == "active"){
    echo "<html>
	<body>
	<div class='card bg-light mb-3' style='margin-top:30px;'>
	<div class='card-header'>
	<table style='margin-bottom:30px;border-style:solid;border-width: 1.5px 1.5px 1.5px 1.5px;' class='table table-striped table-hover table-sm table-responsive'>
    <tr>
	<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Course</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Section</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Bid Amount</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Status</th>
	</tr>";
    foreach ($pendingInfo as $pending){
        if ($pending->status == "pending"){
            echo"<tr>
			<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$pending->code}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$pending->section}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$pending->amount}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;color:yellow;font-weight:bold;'>Pending</td>
            </tr>";
        }
    }
    echo "</table></div></div></body></html>";
}elseif($round == 2 && $status == "active"){
    #the first table showed in round2 active, showing the round 1 successfully bidded
    echo "<html>
	<body>
	<div class='card bg-light mb-3' style='margin-top:30px;'>
	<div class='card-header'>
	<h5>Round 1 Bidding Result</h5>
	</div>
	<div class='card-body'>
	<table style='margin-bottom:30px;border-style:solid;border-width: 1.5px 1.5px 1.5px 1.5px;' class='table table-striped table-hover table-sm table-responsive'>
    <tr>
	<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Course</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Section</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Bid Amount</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Status</th>
	</tr>";
        if($successInfo){
            foreach ($successInfo as $success){
                echo"<tr>
				<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$success->code}</td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$success->section}</td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$success->amount}</td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;font-weight:bold;color:green;'>Successful</td>
                </tr>";
            }
        }
        if($failBidInfo){
            foreach ($failBidInfo as $fail){
                echo"<tr>
				<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$fail->code}</td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$fail->section}</td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$fail->amount}</td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;color:red;font-weight:bold;'>Fail</td>
                </tr>";
            }
        }

    echo "</table></div></div></body></html>";
    
}elseif(($round == 1 && $status == "inactive") || ($round == 2 && $status == "inactive")){

    echo "<html>
	<body>
	<div class='card bg-light mb-3' style='margin-top:30px;'>
	<div class='card-header'>
	<table style='margin-bottom:30px;border-style:solid;border-width: 1.5px 1.5px 1.5px 1.5px;' class='table table-striped table-hover table-sm table-responsive'>
    <tr>
	<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Course</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Section</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Bid Amount</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Status</th>
	</tr>";
    if($successInfo){
        foreach ($successInfo as $success){
            echo"<tr>
			<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$success->code}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$success->section}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$success->amount}</td>
			<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;font-weight:bold;color:green;'>Successful</td>
            </tr>";
        }
    }
    if($failBidInfo){
        foreach ($failBidInfo as $fail){
            echo"<tr>
			<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$fail->code}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$fail->section}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$fail->amount}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;font-weight:bold;color:red;'>Fail</td>
            </tr>";
        }
    }
    echo "</table></div></div></body></html>";
}

# the second table for courses bided in round 2
if($round == 2 && $status == "active"){
    echo "<html>
	<body>
	<div class='card bg-light mb-3' style='margin-top:30px;'>
	<div class='card-header'>
	<h5>Round 2 Bidding Result</h5>
	</div>
	<div class='card-body'>
	<table style='margin-bottom:30px;border-style:solid;border-width: 1.5px 1.5px 1.5px 1.5px;' class='table table-striped table-hover table-sm table-responsive'>
    <tr>
	<th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Course</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Section</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Bid Amount</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Status</th>
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'>Minimum bid</th>
	</tr>";

    # retrieve from minbid table
    $SectionDAO = new SectionDAO();
    $bidDAO = new BidDAO();

    if($pendingInfo){
        foreach ($pendingInfo as $pending){
            $minBidInfo = $SectionDAO->retrieveByCourseAndSection($pending->code,$pending->section);
            $R2status = second_bid_valid($_SESSION['username'],$pending->code, $pending->section, $pending->amount);

            echo"<tr>
			<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$pending->code}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$pending->section}</td>
            <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$pending->amount}</td>";
			if ($R2status == "Successful") {
				echo "<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;font-weight:bold;color:green;'>{$R2status}</td>";
			}
			else {
				echo "<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;font-weight:bold;color:red;'>{$R2status}</td>";
			}
           
            echo "<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'>{$minBidInfo[0]->min_bid}</td>
            </tr>";
        }
    }
    echo "</table></div></div></body></html>";
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

