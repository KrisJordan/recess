<?php
/**
 * PathFinder is a utility class that stores a stack of paths and finds the location
 * of a file relative to each of the paths in the reverse order they were added. So, the 
 * most recently added path has the highest precedence.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 */
class PathFinder {
	
	protected $paths = array();
	
	/**
	 * Add a path to the stack that takes higher look-up precedence than previously added paths.
	 * 
	 * @param string $path
	 * @return PathFinder
	 */
	function addPath($path) {
		array_push($this->paths, $path);
		return $this;
	}
	
	/**
	 * Find the path to a file by searching down the stack.
	 * 
	 * @param string $file Relative file name.
	 * @return string or bool The location of the file or false if it cannot be found.
	 */
	function find($file) {
		for($i = count($this->paths) - 1; $i >= 0;  $i--) {
			$filePath = $this->paths[$i] . $file;
			if(file_exists($filePath)) {
				return $filePath;
			}
		}
		return false;
	}
	
	/**
	 * Get the most preferred path as a string.
	 * 
	 * @return string
	 */
	function __toString() {
		if(!empty($this->paths)) {
			return end($this->paths);
		} else {
			return '';
		}
	}
}
?>