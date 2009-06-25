<?php

class PathFinder {
	
	protected $paths = array();
	
	function addPath($path) {
		array_push($this->paths, $path);
	}
	
	function find($file) {
		for($i = count($this->paths) - 1; $i >= 0;  $i--) {
			$filePath = $this->paths[$i] . $file;
			if(file_exists($filePath)) {
				return $filePath;
			}
		}
		return false;
	}
	
}

?>