<?php
class Fail_BidDAO {

public  function retrieveAll() {
    $sql = 'SELECT * FROM `fail_bid` ORDER BY code, section DESC';
        
    $connMgr = new ConnectionManager();      
    $conn = $connMgr->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $result = array();

    while($row = $stmt->fetch()) { 
        $result[] = new Fail_Bid($row['userid'] , $row['code'] , $row['section'], $row['amount']);
    }
        
    return $result;
}

public  function retrieveByUserID($userid) {
    $sql = 'SELECT * FROM `fail_bid` WHERE userid = :userid ORDER BY code, section DESC';
        
    $connMgr = new ConnectionManager();      
    $conn = $connMgr->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $result = array();

    while($row = $stmt->fetch()) { 
        $result[] = new Fail_Bid($row['userid'] , $row['code'] , $row['section'], $row['amount']);
    }
        
    return $result;
}
#get a distinct array of [code and section]
public  function retrieveCodeSection() {
    $sql = 'SELECT DISTINCT code, section FROM `fail_bid` ORDER BY code, section';
    
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

# $student is an object
public function add($fail_bidObj) {
    $sql = 'INSERT IGNORE INTO `fail_bid` (userid, code, section, amount) VALUES (:userid, :code,:section, :amount)';
    
    $connMgr = new ConnectionManager();       
    $conn = $connMgr->getConnection();
     
    $stmt = $conn->prepare($sql); 

    $stmt->bindParam(':userid', $fail_bidObj->userid, PDO::PARAM_STR);
    $stmt->bindParam(':code', $fail_bidObj->code, PDO::PARAM_STR);
    $stmt->bindParam(':section', $fail_bidObj->section, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $fail_bidObj->amount, PDO::PARAM_INT);
    
    $isAddOK = False;
    
    if ($stmt->execute()) {
        $isAddOK = True;
    }

    return $isAddOK;
}   

    public function removeAll() {
        $sql = 'TRUNCATE TABLE `fail_bid`';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    

}

?>