<?php
// Date class

class Date{
	public $timestamp = 0;

	public function __construct($in = ''){
		if($in == ''){
			$this->timestamp = mktime();
		} elseif(is_numeric($in)){
			$this->timestamp = $in;
		} else{
			throw new Exception("Error Processing Request: $in", 1);
		}
	}

	function fdate($format){
		return date($format, $this->timestamp);
	}

	function __get($key){
		switch($key){
			case "day":
				return $this->fdate("d") * 1;
			case "month":
				return $this->fdate("m") * 1;
			case "year":
				return $this->fdate("Y") * 1;
			case "hour":
				return $this->fdate("G") * 1;
			case "minute":
				return $this->fdate("i") * 1;
		}
	}
}