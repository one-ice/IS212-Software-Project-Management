<?php

class RoundDAO{

    public  function retrieveAll() {
        $sql = 'SELECT * FROM round ';
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $result = "";

        while($row = $stmt->fetch()) {
            $result= new Round( $row['round'], $row['status']);
        }        
        return $result;
    }

    
    public function add($round) {
        $sql = 'INSERT IGNORE INTO round (round, status) VALUES (:round, :status)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':round', $round->round, PDO::PARAM_INT);
        $stmt->bindParam(':status', $round->status, PDO::PARAM_STR);
	
        $isAddOK = False;
		
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }   

    public  function retrieveStatus($round) {
        $sql = 'SELECT status FROM round WHERE round=:round';
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $result = "";

        while($row = $stmt->fetch()) {
            $result= new Round( $row['round'], $row['status']);
        }        
        return $result;
    }

    
  
}



?>