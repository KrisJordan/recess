<?php
Library::import('recess.framework.forms.FormInput');
class TextInput extends FormInput {
	function render() {
		echo '<input type="text" name="', $this->name, '"', ' id="' . $this->name . '"';
		if($this->class != '') {
			echo ' class="', $this->class, '"';
		}
		
		if($this->value != '') {
			echo ' value="', $this->value, '"';
		}
		echo ' />';
	}
}
?>