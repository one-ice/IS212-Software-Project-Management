<?php

class SectionStudent {
	public $userid;
	public $code;
	public $section;
	public $amount;
	
	public function __construct($userid = '', $code = '', $section = '', $amount = '') {
		$this->userid = $userid;
		$this->code = $code;
        $this->section = $section;		
        $this->amount = $amount;
	}



}

?>