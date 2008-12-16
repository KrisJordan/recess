<?php
class DateTimeInput extends FormInput {
	
	public $showDate = true;
	public $showTime = true;
	
	protected static $months = array(1 => 'Jan',
									 2 => 'Feb', 
									 3 => 'Mar',
									 4 => 'Apr',
									 5 => 'May',
									 6 => 'June',
									 7 => 'July', 
									 8 => 'Aug', 
									 9 => 'Sept', 
									 10 => 'Oct', 
									 11 => 'Nov',
									 12 => 'Dec');
									 
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
			$month = isset($value[DateInput::MONTH]) ? $value[DateInput::MONTH] : 0;
			$day = isset($value[DateInput::DAY]) ? $value[DateInput::DAY] : 0;
			$year = isset($value[DateInput::YEAR]) ? $value[DateInput::YEAR] : 0;
			$hour = isset($value[DateInput::HOUR]) ? $value[DateInput::HOUR] : 0;
			$minute = isset($value[DateInput::MINUTE]) ? $value[DateInput::MINUTE] : 0;
			$meridiem = isset($value[DateInput::meridiem]) ? $value[DateInput::meridiem] : 0;
			
			if($meridiem == DateInput::PM) {
				$hour += PM_HOURS;
			}
			
			$this->value = mktime($hour,$minute,0,$month,$day,$year);
		}
	}
	
	function render() {
		
		if($this->showDate) {
			$this->printMonthInput();
			$this->printDateInput();
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
	
	function printDateInput() {
		$this->printSelect($this->name . '[' . self::MONTH . ']', range(1,31), date('j', $this->value));
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