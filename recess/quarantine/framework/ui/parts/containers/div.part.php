<?php
Part::input($name, 'string');
Part::input($children, 'ArrayBlock');
Part::input($defaultSkin, 'string', 'skins/default');
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('containers-div'));
?><!-- begin <?php echo $name ?> container -->
<div <?php echo $classes ?> <?php echo $attrs?>>
<?php echo $children ?>
</div>
<!-- end <?php echo $name ?> container -->
