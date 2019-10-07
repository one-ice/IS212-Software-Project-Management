<?php
require_once 'include/common.php';
#require_once 'include/protect.php';
?>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>BIOS: Admin</title>

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
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #01579b;">
  <a class="navbar-brand" href="#" style="color:white;">BIOS: Admin</a>
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

<!-- Round 1 -->
<div class="row" style="margin-top:30px">
	<div class="col-sm">
		<div class="card">
			<div class="card-header">
			Round 1: Bootstrap
		</div>
		<div class="card-body">
		<p class="card-text">Upload Bootstrap file here</p>
			<div class="row">
				<div class="col-sm">
					<form id='bootstrap-form' action="bootstrap-process.php" method="post" enctype="multipart/form-data">
						<input class="btn btn-outline-secondary" id='bootstrap-file' type="file" name="bootstrap-file">
						<input class="btn btn-outline-primary" type="submit" name="submit" value="Import">
					</form>

				</div>
			</div>
		</div>
		</div>
	</div>

	<div class="col-sm">
	<div class="card">
			<div class="card-header">
			Round 1: Clearing
			</div>
			<div class="card-body">
			<p class="card-text">To clear Round 1: Click the button below</p>
			<form action="clear_r1.php" method='post'>
			<input type="submit" name="Clear Round" value="Clear Round 1" class="btn btn-primary mb-2"></br>
			</form>
		</div>
		
		</div>
	</div>
</div>
<!-- End of Round 1 -->

<!-- Round 2 -->

<div class="row" style="margin-top:30px">
	<div class="col-sm">
		<div class="card">
			<div class="card-header">
			Round 2: Starting
		</div>
		<div class="card-body">
		<p class="card-text">Upload Bootstrap file here</p>
			<div class="row">
				<div class="col-sm">
					<br/>
					<form action = "R2.php" method ='post'>
						<input type="submit" name="Start Round 2" value="Start Round 2" class="btn btn-primary mb-2">
						<?php
						$_SESSION['round'] = 2
						?></br>
					</form>
				</div>
			</div>
		
		
			</div>
		</div>
	</div>
	<div class="col-sm">
	<div class="card">
			<div class="card-header">
			Round 2: Clearing
			</div>
			<div class="card-body">
			<p class="card-text">To clear Round 2: Click the button below</p>
			<br/>
			<form action = "clear_r2.php" method ='post'>
				<input type="submit" name="Clear Round" value="Clear Round 2" class="btn btn-primary mb-2"></br>
			</form>
		</div>
		
		</div>
	</div>
</div>

<!-- End of Round 2 -->

	</div>
	</main>
</body>
<footer class="footer mt-auto py-3" style="background-color: #01579b;">
  <div class="container">
    <span style="float:left;color:white;">&copy;2019 echo "T4";</span>
	<span style="float:right;color:white;">All Rights Reserved</span>
  </div>
</footer>