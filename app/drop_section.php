<?php
spl_autoload_register(function($class){
    require_once "include/$class.php"; 
});
session_start();
$roundDAO = new RoundDAO();
$roundInfo = $roundDAO->retrieveAll();
$round = $roundInfo->round;
$status = $roundInfo->status;

$studentDAO =  new StudentDAO();
$sectionStudentDAO = new SectionStudentDAO();

$username = $_SESSION['username'];

if($round == 2 && $status == 'active'){
    $studentedollar = ($studentDAO->retrieve($username))->edollar;
    echo "$username, you have $$studentedollar in your account";
    if(count($bidInfos = $sectionStudentDAO->retrieveByUserID($username)) != 0){
        echo "<table border = 1px>
        <tr>
        <th>Course</th>
        <th>Section</th>
        <th>Amount</th>
        <th></th>
        </tr><form action = 'drop_section.php'>";
        foreach($bidInfos as $bidInfo){
            echo "<tr>
            <td>$bidInfo->code</td>
            <td>$bidInfo->section</td>
            <td>$bidInfo->amount</td>
            <td>
            <input type = 'checkbox' name = 'drop[]' value = '$bidInfo->code,$bidInfo->section,$bidInfo->amount'>drop
            </td>
            </tr>";
        }
        
        echo"</table><input type = 'submit'></form>";
    }else{
        echo "You did not bid a course in round1";
    }

}else{
    echo "You are not able to drop sections now";
}

if(isset($_GET['drop'])){
    $drops = $_GET['drop'];
    foreach($drops as $drop){
        $code = explode(",",$drop)[0];
        $section = explode(",",$drop)[1];
        $amount = explode(",",$drop)[2];


        // drop the sect from sectstu
        if($sectionStudentDAO->removeByStuAndSection($username,$code,$section)){
            // give stu money
            $studentedollar = ($studentDAO->retrieve($username))->edollar;
            $ed = $studentedollar +$amount;
            $studentDAO->update($username,$ed);
        };

        header("Location:drop_section.php");
    }
}
?>