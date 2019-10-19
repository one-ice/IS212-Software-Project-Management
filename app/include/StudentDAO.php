<?php

class StudentDAO {
    
    public function retrieve($userid) {
        $sql = 'select * from student where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Student($row['userid'], $row['password'],$row['name'], $row['school'], $row['edollar']);
        }

    }

    public  function retrieveAll() {
        $sql = 'SELECT * from student ORDER BY userid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Student($row['userid'], $row['password'],$row['name'], $row['school'],$row['edollar']);
        }
        return $result;
    }

    public function add($student) {
        $sql = "INSERT IGNORE INTO student (userid, password, name, school, edollar) VALUES (:userid, :password, :name, :school, :edollar )";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        // $student->password = password_hash($student->password,PASSWORD_DEFAULT);

        $stmt->bindParam(':userid', $student->userid, PDO::PARAM_STR);
        $stmt->bindParam(':password', $student->password, PDO::PARAM_STR);
        $stmt->bindParam(':name', $student->name, PDO::PARAM_STR);
        $stmt->bindParam(':school', $student->school, PDO::PARAM_STR);
        $stmt->bindParam(':edollar', $student->edollar, PDO::PARAM_INT);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }
        
        return $isAddOK;
    }

     public function update($userid, $edollar) {
        $sql = 'UPDATE student SET edollar=:edollar WHERE userid=:userid';      
        
        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':edollar', $edollar, PDO::PARAM_INT);

        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }
	
	 public function removeAll() {
        $sql = 'SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE student; 
        SET FOREIGN_KEY_CHECKS = 1';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


