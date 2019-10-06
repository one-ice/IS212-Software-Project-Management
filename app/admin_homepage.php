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

<h3>Round 1</h3>
<form id='bootstrap-form' action="bootstrap-process.php" method="post" enctype="multipart/form-data">
	Bootstrap file: </br>
	<input id='bootstrap-file' type="file" name="bootstrap-file"></br>
	<input type="submit" name="submit" value="Import"></br>
</form>

<form action = "clear_r1.php" method ='post'>
    <input type="submit" name="Clear Round" value="Clear Round 1"></br>
</form>

<h3>Round 2</h3>
<form action = "R2.php" method ='post'>
    <input type="submit" name="Start Round 2" value="Start Round 2"></br>
</form>
<form action = "clear_r2.php" method ='post'>
    <input type="submit" name="Clear Round" value="Clear Round 2"></br>
</form>