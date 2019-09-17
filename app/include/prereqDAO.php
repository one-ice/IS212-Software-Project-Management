<?php
class PrereqDAO{
    public function retrieveAll(){
        $sql = 'SELECT * FROM prerequisite ORDER BY course';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch()){
            $result[] = new Prereq($row['course'],$row['prerequisite']);
        }

        return $result;
    }  
    public function retireve($course){
        $sql = 'SELECT course, type FROM prerequisite WHERE course=:course';
        
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $result = new course($row['course'],$row['prerequisite']);
        }

        return $result;
    }



}
?>