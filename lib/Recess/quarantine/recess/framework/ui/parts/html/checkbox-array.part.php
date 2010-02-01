<?php
Part::input($id,	'string');
Part::input($name,	'string');
Part::input($value,	'array', array());
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('nmc-controls-checkbox-array'));
Part::input($choices, 'array');

$i = 0;
$name = $name . '[]';
foreach($choices as $key => $choiceValue) {
	$i++;
	$boxId = $id . '_' . $choiceValue;
	
	$attrs = new HtmlAttributes();
	if(in_array($choiceValue, $value)) {
		$attrs->set('checked', 'checked');
	}
	
	Part::draw('html/input', 'checkbox', $boxId, $name, (string)$choiceValue, $attrs, $classes);
	echo '<label for="' . $boxId . '">' . $key . '</label><br />';
}
?>