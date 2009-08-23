<?php
Part::input($container, 'Container'); // $this
Part::input($name, 'string');
Part::input($children, 'ArrayBlock');
Part::input($defaultSkin, 'string', 'skins/dd-dt');
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('container-dl'));
?>
<!-- begin <?php echo $name ?> container -->
<dl id="<?php echo $container->getId() ?>" <?php echo $classes ?> <?php echo $attrs?>>
<?php echo $children ?>
</dl>
<!-- end <?php echo $name ?> container -->
