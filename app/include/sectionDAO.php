<?php
class sectionDAO{
    public  function retrieveAll() {
        #get all the entries in section table
        $sql = 'SELECT * FROM section ORDER BY "course","section" ';
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
    
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
    
        $result = array();
    
        while($row = $stmt->fetch()) {
            $result[] = new Section($row['course'], $row['section'],$row['day'],$row['start'],
            $row['end'], $row['instructor'],$row['venue'],$row['size'], $row['min_bid']);
        }
    }

    public  function retrievebyCourse($course) {
        #get all the entries in section table
        $sql = 'SELECT * FROM section where course = :course';
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
    
        $result = array();
    
        while($row = $stmt->fetch()) {
            $result[] = new Section($row['course'], $row['section'],$row['day'],$row['start'],
            $row['end'], $row['instructor'],$row['venue'],$row['size'], $row['min_bid']);
        }
        
        return $result;
    }
    
    public function add($section) {
        #to add a row into the section table where $section is a an object in section

        $sql = "INSERT IGNORE INTO section (course, section, day, start, end, instructor, venue, size, min_bid) 
        VALUES (:course, :section, :day, :start, :end, :instructor, :venue, :size, :min_bid)";
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':course', $section->course, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section->section, PDO::PARAM_STR);
        $stmt->bindParam(':day', $section->day, PDO::PARAM_INT);
        $stmt->bindParam(':start', $section->start, PDO::PARAM_STR); 
        $stmt->bindParam(':end', $section->end, PDO::PARAM_STR);
        $stmt->bindParam(':instructor', $section->instructor, PDO::PARAM_STR);
        $stmt->bindParam(':venue', $section->venue, PDO::PARAM_STR);
        $stmt->bindParam(':size', $section->size, PDO::PARAM_INT);
        $stmt->bindParam(':min_bid', $section->min_bid, PDO::PARAM_INT);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    
    public function removeAll() {
        #removing data in the table
        $sql = 'SET FOREIGN_KEY_CHECKS = 0; 
        TRUNCATE TABLE section'; #need to disable foreign key
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();

        $sqlAddFK = 'SET FOREIGN_KEY_CHECKS = 1';

        $stmt = $conn->prepare($sqlAddFK);
        $stmt->execute();
        $count = $stmt->rowCount();


    }

    public  function retrieve($course,$section) {
        #identify unique row using PK of course and section
        $sql = 'SELECT * FROM section WHERE course=:course and section = :section';
        $result = "";
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result = new Section($row['course'], $row['section'],$row['day'],$row['start'],
            $row['end'], $row['instructor'],$row['venue'],$row['size'], $row['min_bid']);
        }
        
        return $result;
    }
    
    public  function retrieveMinBid($course, $section) {
        #get all the entries in section table
        $sql = 'SELECT min_bid FROM section where course = :course and section = :section';
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
    
        while($row = $stmt->fetch()) {
            return $row['min_bid'];
        }
    }

    public  function updateMinBid($amount, $course, $section) {
        #get all the entries in section table
        $sql = 'UPDATE section SET min_bid =:min_bid WHERE course = :course and section = :section';
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':min_bid', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }


}

?>