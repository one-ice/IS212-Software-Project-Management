<?php

class BidDAO {

    public  function retrieveAll() {
        $sql = 'SELECT * FROM bid ORDER BY code, section, amount DESC';
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch()) {
            $result[] = new bid($row['userid'], $row['amount'], $row['code'], $row['section'], $row['status']);
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

        $result = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new bid($row['userid'], $row['amount'], $row['code'], $row['section'], $row['status']);
        }
        
        return $result;
    }

    #get a distinct array of [code and section]
    public  function retrieveCodeSection() {
        $sql = 'SELECT DISTINCT code, section FROM bid ORDER BY code, section';
        
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

    #retrieve by code and section
    public  function retrieveBidForEachSection($code, $section) {
        $sql = 'SELECT * FROM bid WHERE code=:code and section=:section ORDER BY amount DESC';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new bid($row['userid'], $row['amount'], $row['code'], $row['section'], $row['status']);
        }
        
        return $result;
    }
  
    public  function retrieveBid($userid, $code) {
        $sql = 'SELECT * FROM bid WHERE userid=:userid AND code=:code';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new bid($row['userid'], $row['amount'], $row['code'], $row['section'], $row['status']);
        }
    }
  
    public function add($bid) {
        $sql = 'INSERT IGNORE INTO bid (userid, amount, code, section,status) VALUES (:userid, :amount, :code, :section,:status)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
		$stmt->bindParam(':code', $bid->code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
        $stmt->bindParam(':status', $bid->status, PDO::PARAM_STR);
        
        $isAddOK = False;
		
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }   

    #New function to update bid on status, during clearing round
    public function update($userid, $code, $status) {
        $sql = 'UPDATE bid SET status=:status WHERE userid=:userid and code = :code';      
        
        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);

        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }
        
    public function remove($userid, $code ) {
        $sql = 'DELETE FROM bid WHERE userid = :userid AND code = :code';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        
        $isRemoveOk = False;
        if ($stmt->execute()) {
            $isRemoveOk = True;
        }

        return $isRemoveOk;
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
