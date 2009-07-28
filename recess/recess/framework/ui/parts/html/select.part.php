<?php
Part::input($id,	'string');
Part::input($name,	'string');
Part::input($value, 'string', '');
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('html-select'));
Part::input($choices, 'array');
?><select <?php echo $classes; ?> id="<?php echo $id ?>" name="<?php echo $name ?>" <?php echo $attrs; ?>><?php 
$inOptGroup = false;
foreach($choices as $key => $choiceValue) {	
	if($choiceValue === null) {
		if($inOptGroup) {
			echo "</optgroup>\n";
		}
		$inOptGroup = true;
		echo "<optgroup label=\"$key\">\n";
		continue;
	}
	
	if((string)$choiceValue !== (string)$value) {
		echo "\t<option value=\"$choiceValue\">$key</option>\n";
	} else {
		echo "\t<option value=\"$choiceValue\" selected=\"selected\">$key</option>\n";
	}
}
if($inOptGroup) {
	echo "</optgroup>\n";
}
?>
</select>
