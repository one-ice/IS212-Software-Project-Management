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

    
    
  
}



?>