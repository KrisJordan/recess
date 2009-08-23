<?php
Part::input($container, 'Container'); // $this
Part::input($name, 'string');
Part::input($children, 'ArrayBlock');
Part::input($defaultSkin, 'string', 'skins/dd-dt');
Part::input($legend, 'string');
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('container-fieldset'));
?><!-- begin <?php echo $name ?> container -->
<fieldset id="<?php echo $container->getId() ?>" <?php echo $classes ?> <?php echo $attrs?>>
<legend><?php echo $legend ?></legend>
<dl class="containers-dl">
<?php echo $children ?>
</dl>
</fieldset>
<!-- end <?php echo $name ?> container -->
