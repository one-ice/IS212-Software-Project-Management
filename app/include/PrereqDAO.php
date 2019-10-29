<?php
class PrereqDAO{
    public function retrieveAll(){
        $sql = 'SELECT * FROM prerequisite ORDER BY course, prerequisite';

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
    public function retrieve($course){
        $sql = 'SELECT * FROM prerequisite WHERE course=:course';
        
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $result[] = new Prereq($row['course'],$row['prerequisite']);
        }

        return $result;
    }

    public function removeAll(){
        // can be truncated directly. Only contain two pk
        $sql = 'SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE prerequisite';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $sqlAddFK = 'SET FOREIGN_KEY_CHECKS = 1';

        $stmt = $conn->prepare($sqlAddFK);
        $stmt->execute();

        $cout = $stmt->rowCount();
    }

    public function update($prerequisite){
        $sql = "UPDATE prerequisite SET course = :course,prerequisite = :prerequisite";

        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':course', $prerequisite->course, PDO::PARAM_STR);
        $stmt->bindParam(':prerequisite', $prerequisite->prerequisite, PDO::PARAM_STR);

        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }

    public function add($prerequisite){
        $sql = 'INSERT IGNORE INTO prerequisite(course,prerequisite) VALUES (:course, :prerequisite)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':course', $prerequisite->course, PDO::PARAM_STR);
        $stmt->bindParam(':prerequisite', $prerequisite->prerequisite, PDO::PARAM_STR);


        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK; 
    }

}
?>