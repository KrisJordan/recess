<?php
Library::import('recess.framework.forms.FormInput');
class HiddenInput extends FormInput {	
	function render() {
		echo '<input type="hidden" name="', $this->name, '"';
		if($this->value != '') {
			echo ' value="', $this->value, '"';
		}
		echo ' />';
	}
}
?>