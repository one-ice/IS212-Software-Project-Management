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