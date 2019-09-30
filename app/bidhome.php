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

}
?>