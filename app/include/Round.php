<?php

class Round {
    public $round;
	public $status;
	
	public function __construct( $round = '', $status = '') {
		$this->round = $round;
        $this->status = $status;
	}
}

?>