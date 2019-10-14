<?php

class SectionStudentDAO {

    public  function retrieveAll() {
        $sql = 'SELECT * FROM `section-student` ORDER BY code, section DESC';
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $result = array();

        while($row = $stmt->fetch()) { 
            $result[] = new SectionStudent($row['userid'], $row['code'], $row['section'], $row['amount']);
        }
            
        return $result;
    }

    # for each stu, retrieve info
    public  function retrieveByUserID($userid){
        $sql = 'SELECT * FROM `section-student` WHERE userid = :userid ORDER BY code, section DESC';
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $result = array();

        while($row = $stmt->fetch()) { 
            $result[] = new SectionStudent($row['userid'], $row['code'], $row['section'], $row['amount']);
        }
            
        return $result;
    }    

    #get a distinct array of [code and section]
    public  function retrieveCodeSection() {
        $sql = 'SELECT DISTINCT code, section FROM `section-student` ORDER BY code, section';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $result = array();

        while($row = $stmt->fetch()) {
            $result[] = [$row['code'] , $row['section']] ;
        }       
        return $result;
    }

    #get vacancy of section
    public  function retrieveVacancy($code, $section) {
        $sql = 'SELECT * FROM `section-student` WHERE code = :code and section = :section';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $result = array();

        while($row = $stmt->fetch()) {
            $result[] = [$row['code'] , $row['section']] ;
        }      
        $vacancy = sizeof($result); 
        return $vacancy;
    }

    # $student is an object
    public function add($sectionstudentObj) {
        $sql = 'INSERT IGNORE INTO `section-student` (userid, code, section, amount) VALUES (:userid, :code,:section, :amount)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $sectionstudentObj->userid, PDO::PARAM_STR);
		$stmt->bindParam(':code', $sectionstudentObj->code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $sectionstudentObj->section, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $sectionstudentObj->amount, PDO::PARAM_INT);
        
        $isAddOK = False;
		
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }   
    

}

?>