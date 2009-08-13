<?php
Part::input($type,	'string');
Part::input($id,	'string');
Part::input($name,	'string');
Part::input($value,	'string', '');
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('nmc-controls-text'));
?><input <?php echo $classes; ?> id="<?php echo $id ?>" name="<?php echo $name ?>" type="<?php echo $type?>" <?php echo $attrs; ?><?php 
	if($value != ''):
		echo " value=\"$value\""; 
	endif ?>/>
