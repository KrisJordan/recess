<?php
Part::input($container, 'Container'); // $this
Part::input($name, 'string');
Part::input($children, 'ArrayBlock');
Part::input($defaultSkin, 'string', 'skins/default');
?><!-- BEGIN <?php echo $name ?> CONTAINER -->
<?php echo $children ?>
<!-- END <?php echo $name ?> FIELDSET -->
