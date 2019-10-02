<?php

#Class for Bid
class Bid {
	public $userid;
	public $amount;
	public $code;
	public $section;
	public $status;

	
	public function __construct($userid = '', $amount = '', $code = '', $section = '', $status = '') {
		$this->userid = $userid;
		$this->amount = $amount;
		$this->code = $code;
		$this->section = $section;
		$this->status = $status;
	}

}

?>