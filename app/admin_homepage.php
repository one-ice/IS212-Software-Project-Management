<?php
require_once 'include/common.php';
#require_once 'include/protect.php';
?>

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