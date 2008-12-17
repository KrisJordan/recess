<?php
class DateTimeInput extends FormInput {
	
	public $showDate = true;
	public $showTime = true;
	
	protected static $months = array('Jan',
									 'Feb', 
									 'Mar',
									 'Apr',
									 'May',
									 'June',
									 'July', 
									 'Aug', 
									 'Sept', 
									 'Oct', 
									 'Nov',
									 'Dec');
									 
	protected static $meridiems = array(
									 self::AM,
									 self::PM
										);
	const MONTH = 'month';
	const DAY = 'day';
	const YEAR = 'year';
	const HOUR = 'hour';
	const MINUTE = 'minute';
	const MERIDIEM = 'meridiem';
	const AM = 'am';
	const PM = 'pm';
	const PM_HOURS = 12;
	
	function getValue() {
		return $this->value;
	}
	
	function setValue($value) {
		if(is_array($value)) {
			$month = isset($value[self::MONTH]) ? $value[self::MONTH] : 0;
			$day = isset($value[self::DAY]) ? $value[self::DAY] : 0;
			$year = isset($value[self::YEAR]) ? $value[self::YEAR] : 0;
			$hour = isset($value[self::HOUR]) ? $value[self::HOUR] : 0;
			$minute = isset($value[self::MINUTE]) ? $value[self::MINUTE] : 0;
			$meridiem = isset($value[self::MERIDIEM]) ? $value[self::MERIDIEM] : 0;
			
			if($meridiem == self::PM) {
				$hour += self::PM_HOURS;
			}
			
			$this->value = mktime($hour,$minute,1,$month,$day,$year);
		} else {
			$this->value = $value;
		}
	}
	
	function render() {
		
		if($this->showDate) {
			$this->printMonthInput();
			$this->printDayInput();
			$this->printYearInput();
		}
		
		if($this->showTime) {
			$this->printHourInput();
			$this->printMinuteInput();
			$this->printmeridiemInput();
		}
		
	}
	
	function printMonthInput() {
		$this->printSelect($this->name . '[' . self::MONTH . ']', self::$months, date('n', $this->value));
	}
	
	function printDayInput() {
		$this->printSelect($this->name . '[' . self::DAY . ']', range(1,31), date('j', $this->value));
	}
	
	function printYearInput() {
		$this->printText($this->name . '[' . self::YEAR . ']', date('Y', $this->value));
	}
	
	function printHourInput() {
		$this->printSelect($this->name . '[' . self::HOUR . ']', range(1,12), date('g', $this->value));
	}
	
	function printMinuteInput() {
		$this->printSelect($this->name . '[' . self::MINUTE . ']', range(0,60,15), (int)date('i', $this->value));
	}
	
	function printMeridiemInput() {
		$this->printSelect($this->name . '[' . self::MERIDIEM . ']', self::$meridiems, date('a', $this->value));
	}
	
	function printSelect($name, $values, $selected) {
		echo '<select name="', $name, '">';
		
		foreach($values as $key => $value) {
			$key++;
			echo '<option value="', $key, '"';
			if($key == $selected) {
				echo ' selected="selected"';
			}
			echo '>', $value, '</option>', "\n";
		}
		
		echo '</select>';
	}
	
	function printText($name, $value = '') {
		echo '<input class="text short" name="' . $name . '" value="' . $value . '" />';
	}
}
?>