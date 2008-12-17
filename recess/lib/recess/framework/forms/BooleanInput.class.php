<?php
class BooleanInput extends FormInput {
	function render() {
		echo '<input type="radio" name="', $this->name, '"', $this->value === true ? ' checked="checked" ' : '', ' value="1" />Yes</input>';
		echo '<input type="radio" name="', $this->name, '"', $this->value === true ? '' : ' checked="checked" ', ' value="0" />No</input>';
	}
}
?>