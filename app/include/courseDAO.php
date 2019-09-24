<?php
class CourseDAO{
    public function retrieveAll(){
        $sql = 'SELECT * FROM course ORDER BY course';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch()){
            $result[] = new course($row['course'],$row['school'],$row['title'],$row['description'],$row['examDate'],$row['examStart'],$row['examEnd']);
        }

        return $result;
    }
    
    public  function retrieve($course) {
        $sql = 'SELECT * from course where course = :course';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Course($row['course'], $row['school'],$row['title'], $row['description'], $row['exam_date'], $row['exam_start'], $row['exam_end']);
        }
        return False;
    }


    public function update($course){
        $sql = "UPDATE course SET course = :course, school = :school, title = :title, description =:description, examDate = :examDate, examStart = :examStart, examEnd =:examEnd";

        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':course', $course->course, PDO::PARAM_STR);
        $stmt->bindParam(':school', $course->school, PDO::PARAM_STR);
        $stmt->bindParam(':title', $course->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $course->description, PDO::PARAM_STR);
        $stmt->bindParam(':examDate', $course->examDate, PDO::PARAM_STR);
        $stmt->bindParam(':examStart', $course->examStart, PDO::PARAM_STR);
        $stmt->bindParam(':examEnd', $course->examEnd, PDO::PARAM_STR);

        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }

    public function removeAll(){
        $sql = 'SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE course';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sqlAddFK = 'SET FOREIGN_KEY_CHECKS = 1';

        $stmt = $conn->prepare($sqlAddFK);
        $stmt->execute();

        $count = $stmt->rowCount();
    }

    public function add($course){
        $sql = 'INSERT IGNORE INTO course(course,school,title,description,exam_date,exam_start,exam_end) 
        VALUES (:course, :school,:title,:description,:examDate,:examStart,:examEnd)';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':course', $course->course, PDO::PARAM_STR);
        $stmt->bindParam(':school', $course->school, PDO::PARAM_STR);
        $stmt->bindParam(':title', $course->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $course->description, PDO::PARAM_STR);
        $stmt->bindParam(':examDate', $course->examDate, PDO::PARAM_STR);
        $stmt->bindParam(':examStart', $course->examStart, PDO::PARAM_STR);
        $stmt->bindParam(':examEnd', $course->examEnd, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK; 
    }

}