<?php
Library::import('recess.framework.forms.FormInput');
class TextAreaInput extends FormInput {
	function render() {
		echo '<textarea name="', $this->name, '"', ' id="' . $this->name . '"';
		if($this->class != '') {
			echo ' class="', $this->class, '"';
		}
		echo '>';
	
		if($this->value != '') {
			echo $this->value;
		}
		
		echo '</textarea>';
	}
}
?>