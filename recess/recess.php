<?php
set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());

// Include the Autoloader
include 'recess/core/ClassLoader.class.php';

// Register Autoload Function
spl_autoload_register('recess\core\ClassLoader::load');