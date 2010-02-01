<?php
Library::import('recess.framework.forms.FormInput');
class BooleanInput extends FormInput {
	function setValue($value) {
		if (is_numeric($value)) {
			$this->value = $value == 1;
		} else {
			$this->value = $value;
		}
	}
	
	function render() {
		echo '<input type="radio" name="', $this->name, '"', $this->value == true ? ' checked="checked" ' : '', ' value="1" />Yes</input>';
		echo '<input type="radio" name="', $this->name, '"', $this->value == true ? '' : ' checked="checked" ', ' value="0" />No</input>';
	}
}
?>