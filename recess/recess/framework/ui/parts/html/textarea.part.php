<?php
Part::input($id,	'string');
Part::input($name,	'string');
Part::input($value,	'string', '');
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('html-textarea'));
?><textarea <?php echo $classes; ?> id="<?php echo $id ?>" name="<?php echo $name ?>" <?php echo $attrs; ?>>
<?php echo $value; ?>
</textarea>
