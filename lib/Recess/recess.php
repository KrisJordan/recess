<?php
set_include_path(realpath(__DIR__ . '/../') . PATH_SEPARATOR . get_include_path());

require 'Recess/Core/ClassLoader.php';
spl_autoload_register('Recess\Core\ClassLoader::load');