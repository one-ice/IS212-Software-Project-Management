
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>BIOS: Bootstrap Process</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<!-- Bootstrap.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #01579b">
  <a class="navbar-brand" href="#" style="color:white;">BIOS: Bootstrap Process</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" style="color:white;" href="admin_homepage.php">Home <span class="sr-only">(current)</span></a>
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
<div class="row">
<div class="col-sm">

<div class='card bg-light mb-3' style='margin-top:30px;'>
<div class='card-header'>Bootstrap Output Message</div>
<div class='card-body'>

<pre>
<?php
# edit the file included below. the bootstrap logic is there
require_once 'include/bootstrap.php';
$result = doBootstrap(true);
$json_string = json_decode($result,true);
$status = $json_string['status'];
$allRecords = $json_string['num-record-loaded'];
?>

        <?php 
        if(isset($_POST['submit'])){
        echo"<table class='bootstrap-form'>
        <thead>
            <th>Bootstrap Results</th>
        </thead>";  
          echo "<tr><td>Status</td><td>$status</td></tr>";
          foreach ($allRecords as $recordFile){
            foreach($recordFile as $fileName => $number){
             echo "<tr><td>$fileName</td><td>$number</td></tr>";
            }
          }
          if($status == 'error'){
            $messages = $json_string['messages'];
            foreach($messages as $message){
              $fileName = $message['file'];
              $lineNumber = $message['line'];
              $errors = "";
              foreach($message['message'] as $message1){
                $errors = $errors.$message1." ";
              }
              echo "<tr><td>$fileName</td><td>Line: $lineNumber </td> <td> $errors </td> </tr>";
            }
          }
        }
        
        
        ?>
</table>
</pre>
<br/>
<br/>
<a href="admin_homepage.php"><button type="button" class="btn btn-outline-dark">Back</button></a>
</div>
</div>
</div>
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