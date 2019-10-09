<?php
class section{
    public $course;
    public $section;
    public $day;
    public $start;
    public $end;
    public $instructor;
    public $venue;
    public $size;
    public $min_bid;

    public function __construct($course='', $section='',$day='',$start='',$end='',$instructor='',
    $venue='',$size='', $min_bid = '') {
        $this->course = $course;
        $this->section = $section;
        $this->day = $day;
        $this->start = $start;
        $this->end = $end;
        $this->instructor = $instructor;
        $this->venue = $venue;
        $this->size = $size;
        $this->min_bid = $min_bid;
    }

}

?>