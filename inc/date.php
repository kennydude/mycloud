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

	function __set($key, $value){
		$d = new DateTime();
		$d->settimestamp($this->timestamp + '');
		switch($key){
			case "day":
				$d->setDate($value, $this->month, $this->year);
				break;
			case "month":
				$d->setDate($this->day, $value, $this->year);
				break;
			case "year":
				$d->setDate($this->day, $this->month, $value);
				break;
			case "hour":
				$d->setTime($value, $this->hour);
				break;
			case "minute":
				$d->setTime($this->minute, $value);
				break;
		}
		$this->timestamp = $d->gettimestamp();
	}
}