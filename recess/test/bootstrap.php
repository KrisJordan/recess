<?php

date_default_timezone_set('America/New_York');

$_ENV['dir.bootstrap'] = str_replace('\\','/',realpath(dirname(__FILE__) . '/../../')) . '/';
$_ENV['url.base'] = str_replace('AllTests.php', '', $_SERVER['PHP_SELF']);

$_ENV['dir.recess'] = $_ENV['dir.bootstrap'] . 'recess/';
$_ENV['dir.apps'] = $_ENV['dir.bootstrap'] . 'apps/';
$_ENV['dir.test'] = $_ENV['dir.recess'] . 'test/';
$_ENV['dir.temp'] = $_ENV['dir.recess'] . 'temp/';
$_ENV['dir.lib'] = $_ENV['dir.recess'] . 'lib/';
$_ENV['url.content'] = $_ENV['url.base'] . 'content/';

require_once($_ENV['dir.lib'] . 'recess/lang/Library.class.php');
Library::addClassPath($_ENV['dir.lib']);

?>