<?php
/**
 * Recess! Framework is bootstrapped by delegating control to the Coordinator.
 * 
 * @author Kris Jordan
 */
$file = str_replace('\\','/',__FILE__);
$_ENV['dir.documentRoot'] = substr($file, 0, strrpos(str_replace('\\','/',$file) ,'/') + 1);
$_ENV['url.base'] = str_replace('bootstrap.php', '', $_SERVER['PHP_SELF']);
unset($file);

$bootstrapped = true;
require_once('./recess-config.php');

Library::import('recess.diagnostics.Diagnostics');
set_error_handler('Diagnostics::handleError', E_ALL);
set_exception_handler('Diagnostics::handleException');

Library::import('recess.framework.Coordinator');
Library::import('recess.http.Environment');
Coordinator::main(Environment::getRawRequest(), Config::$policy, Config::$applications, Config::getRouter(), Config::$plugins);
?>