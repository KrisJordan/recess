<?php
/**
 * Recess! Framework is bootstrapped by delegating control to the Coordinator.
 * 
 * @author Kris Jordan
 */
require_once('../lib/recess/Recess.php');

$file = str_replace('\\','/',__FILE__);
$_ENV['dir.public'] = substr($file, 0, strrpos(str_replace('\\','/',$file) ,'/') + 1);
$_ENV['dir.base'] = substr($_ENV['dir.public'], 0, strrpos($_ENV['dir.public'] ,'/', -2) + 1);
unset($file);

require_once('./recess-config.php');

Library::import('recess.framework.Coordinator');
Library::import('recess.http.Environment');
Coordinator::main(Environment::getRawRequest(), Config::$policy, Config::$applications, Config::getRouter(), Config::$plugins);
?>