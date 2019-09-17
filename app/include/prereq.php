<?php

class Prereq {
    public $course;    
    public $prerequisite;

    public function __construct($course , $prerequisite) {
        $this->course = $course;
        $this->prerequisite = $prerequisite;
    }
}


?>