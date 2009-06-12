<?php

class AcceptsList {
	
	protected $range;
	
	protected $types;
	
	protected $qs = false;	
	
	protected $key = 0;
	
	public function __construct($range) {
		$this->range = $range;
	}
	
	public function next() {
		if($this->qs === false) $this->init();
		
		if(isset($this->qs[$this->key])) {
			return $this->qs[$this->key++];
		} else {
			return false;
		}
	}
	
	public function reset() {
		$this->key = 0;
	}
	
	protected function init() {
		// Break apart each type
		$this->types = explode(',', $this->range);
		
		$qs = array();
		
		$count = count($this->types);
		
		// Iterate through each to clean-up and extract precedence
		for($t = 0; $t < $count; $t++) {
			$this->types[$t] = trim($this->types[$t]);
			
			$q = 1.0;
			
			// Break apart type parameters
			$params = explode(';', $this->types[$t]);
			
			$paramsCount = count($params);
			if(count($paramsCount > 1)) {
				for($p = 1; $p < $paramsCount; $p++) {
					$qPos = strpos($params[$p], 'q=');
					if($qPos !== false) {
						$qValue = trim(substr($params[$p], $qPos + 2));
						if(is_numeric($qValue)) {
							$q = $qValue;
						}
					}
				}
			}
			
			$lenParams0 = strlen($params[0]);
			if($lenParams0 > 0 && $params[0][$lenParams0-1] === '*') {
				$q -= 0.01;
				
				if($params[0][0] === '*') {
					$q -= 0.01;
				}
			}
			
			if(!isset($qs[$q])) {
				$qs[(string)$q] = array();
			}
			
			$qs[(string)$q][] = $params[0];
		}
		
		// Sort keys of q-value in descending order
		krsort($qs);
		
		// Re-key qs 0,1,..N for simple iteration
		$this->qs = array_combine(range(0,count($qs)-1), $qs);
	}
	
}

?>