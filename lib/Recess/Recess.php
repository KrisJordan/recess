<?php
set_include_path(
		realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) 
		. PATH_SEPARATOR . get_include_path());

require 'Recess/Core/functions.php';
require 'Recess/Core/ClassLoader.php';
spl_autoload_register('Recess\Core\ClassLoader::load');