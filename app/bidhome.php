<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>BIOS: Bid Home</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<!-- Bootstrap.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<?php

include_once "include/common.php";
$username = 'amy.ng.2009';
$round = 1;
$studentDAO =  new StudentDAO();
$studentedollar = ($studentDAO->retrieve($username))->edollar;
echo "<h1> Welcome to BIOS </h1>";
echo "<h3> Round $round </h3>";
echo "<p> You have $$studentedollar.</p>";

if ($round == 1)
{
    #Retrieve user's school
    $studentschool = ($studentDAO->retrieve($username))->school;

    #Retrieve courses by school
    $courseDAO = new CourseDAO();
    $courses = $courseDAO->retrieveAllbySchool($studentschool);

    echo "<table border = '1'> <th> Course </th> <th> School </th> <th> Title </th> <th> Description </th>
    <th> Exam Date </th> <th> Exam Start </th> <th> Exam End </th> <td> </td>";
    foreach ($courses as $course)
    {
        echo "<tr> <td> {$course->course} </td>
                <td> {$course->school} </td>
                <td> {$course->title} </td>
                <td> {$course->description} </td>
                <td> {$course->exam_date} </td>
                <td> {$course->exam_start} </td>
                <td> {$course->exam_end} </td>
                <td> <a href = 'bidding.php?course={$course->course}'> Select </a> </td> </tr>";
    }
    echo "</table>"; 
}

?>