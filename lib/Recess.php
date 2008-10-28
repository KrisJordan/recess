<?php
// Recess Bootstrap File

$_ENV['recess_base_dir'] = substr(__FILE__, 0, strrpos(str_replace('\\','/',__FILE__) ,'/') + 1);
require_once($_ENV['recess_base_dir'] . 'Library.class.php');
Library::addClassPath($_ENV['recess_base_dir']);

Library::import('recess.diagnostics.Diagnostics');
set_error_handler('Diagnostics::handleError', E_ALL);
set_exception_handler('Diagnostics::handleException');
?>