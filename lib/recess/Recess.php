<?php
/**
 * Recess.php sets up environment variables, the library, and exception handlers.
 * @author Kris Jordan
 */
$file = str_replace('\\','/',__FILE__);
$_ENV['dir.recess'] = substr($file, 0, strrpos($file ,'/') + 1);
$_ENV['dir.lib'] = substr($_ENV['dir.recess'], 0, strrpos($_ENV['dir.recess'] ,'/', -2) + 1);
unset($file);

require_once($_ENV['dir.recess'] . 'lang/Library.class.php');
Library::addClassPath($_ENV['dir.lib']);
?>