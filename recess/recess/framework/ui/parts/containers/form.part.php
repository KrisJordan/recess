<?php
Part::input($container, 'Container'); // $this
Part::input($name, 'string');
Part::input($children, 'ArrayBlock');
Part::input($defaultSkin, 'string', 'skins/default');
Part::input($method, 'string', 'POST');
Part::input($action, 'string', '');
Part::input($attrs,	'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('container-form'));
?><!-- begin <?php echo $name ?> form -->
<form id="<?php echo $container->getId() ?>" method="<?php echo $method ?>"<?php if($action != ''): ?> action="<?php echo $action ?>"<?php endif;?> <?php echo $classes ?> <?php echo $attrs?>>
<?php echo $children ?>
</form>
<!-- end <?php echo $name ?> form -->
