<?php
Library::import('recess.framework.forms.FormInput');
class DateLabelInput extends FormInput {
	
	function render() {
		echo date(DATE_RFC822, $this->value);
	}
	
}
?>