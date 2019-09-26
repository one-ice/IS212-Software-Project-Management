<?php

class BidDAO {

    public  function retrieveAll() {
        $sql = 'SELECT * FROM bid ORDER BY `amount`';
        
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch()) {
            $result[] = new bid($row['userid'], $row['amount'], $row['code'], $row['section']);
        }
            
                 
        return $result;
    }
    
  
    public  function retrieve($userid) {
        $sql = 'SELECT * FROM bid WHERE userid=:userid';
        
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result = new bid($row['userid'], $row['amount'], $row['code'], $row['section']);
        }
        
        return $result;
    }
  
  
    public function add($bid) {
        $sql = 'INSERT IGNORE INTO bid (userid, amount, code, section) VALUES (:userid, :amount, :code, :section)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
		$stmt->bindParam(':code', $bid->code, PDO::PARAM_STR);
		$stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
        
        $isAddOK = False;
		
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    
        
    public function remove($userid) {
        $sql = 'DELETE FROM bid WHERE userid = :userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }

    public function removeAll() {
        $sql = 'TRUNCATE TABLE bid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
}
