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
  <a class="navbar-brand" href="#" style="color:white;">BIOS: Drop Bid</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" style="color:white;" href="#">Home <span class="sr-only">(current)</span></a>
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
$username = $_SESSION["username"];
$name =[];
$name = explode(".", $username);
$firstName = ucfirst($name[0]);
$lastName = ucfirst($name[1]);
echo "<div class='card bg-light mb-3' style='margin-top:30px;'>";
echo "<div class='card-header'>Welcome to BIOS, $firstName $lastName</div>";
echo "<div class='card-body'>";

#Retrieve round
$roundDAO = new RoundDAO();
$round = (($roundDAO->retrieveAll())->round);
$status = (($roundDAO->retrieveAll())->status);

#Retrieve student's edollar
$studentDAO =  new StudentDAO();
$studentedollar = ($studentDAO->retrieve($username))->edollar;
echo "<h5 class='card-title'>Round $round</h5>";
echo "<p class='card-text'>You have $$studentedollar</p>";
echo "</div></div>";
echo "<div class='row' style='margin-top:30px;'>";
echo "<div class='col-sm'>";

if ($round == 1)
{
  #Retrieve modules
  $BidDAO = new BidDAO();
  $mods = [];
	$mods = $BidDAO->retrieve($username);
	echo "<div class='card bg-light mb-3'>";
	echo "<div class='card-header'>List of Modules Bidded</div>";
	echo " </div>";

  if ($status == 'active' && $mods != [])
  {
    echo "<table style='margin-bottom:30px;border-style:solid;border-width: 1.5px 1.5px 1.5px 1.5px;' class='table table-striped table-hover table-sm table-responsive'> 
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Module Code </th> 
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Section </th> 
    <th scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> Amount </th>";

    echo "<td scope='col' style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;'> </td>";
	
    foreach ($mods as $mods)
    {
	
        echo "<tr> 
				<td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$mods->code} </td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$mods->section} </td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> {$mods->amount} </td>
                <td style='border-style:solid;border-width:1.5px 1.5px 1.5px 1.5px;' class='font-weight-normal'> <a href = 'drop-bid.php?code={$mods->code}'> Select </a> </td> 
                </tr>";
    }
    echo "</table>";
  }
  else
  {
    echo "No bids available to drop";
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