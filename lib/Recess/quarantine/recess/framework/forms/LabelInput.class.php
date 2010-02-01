<?php
Library::import('recess.framework.forms.FormInput');
class LabelInput extends FormInput {
	function render() {
		echo $this->value;
	}
}
?>