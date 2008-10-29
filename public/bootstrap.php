<?php
/**
 * Recess! Framework is bootstrapped by delegating control to the Coordinator.
 * 
 * @author Kris Jordan
 */

require_once('../lib/Recess.php');
Library::addClassPath('../'); // base directory of 'application' folder
Library::import('recess.Coordinator');
Coordinator::main(Environment::getRawRequest());
?>