<?php
set_include_path(__DIR__);

// Include the Autoloader
include 'recess/core/ClassLoader.class.php';

// Register Autoload Function
spl_autoload_register('recess\core\ClassLoader::load');