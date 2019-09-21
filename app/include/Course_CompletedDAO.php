<?php

class Course_CompletedDAO {
    
    /**
     * Get all the possible course_completed types.
     * 
     * @return array of String
     */
    public function retrieveAll() {
        $sql = 'SELECT * FROM course_completed';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $arr = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arr[] = new Course_Completed(
                $row['userid'],
                $row['code']);
        }
        return $arr;
    }

    public function retrieve($userid) {
        $sql = 'select * from course_completed where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Course_Completed($row['userid'], $row['code']);
        }
        return $result;

    }

    public function add($course_completed) {
        $sql = "INSERT IGNORE INTO course_completed (userid, code) VALUES (:userid, :code)";
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $course_completed->userid, PDO::PARAM_STR);
        $stmt->bindParam(':code', $course_completed->code, PDO::PARAM_STR);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function removeAll() {
        $sql = 'TRUNCATE TABLE course_completed';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	


}
