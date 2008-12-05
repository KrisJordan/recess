<?php if(isset($bootstrapped)) unset($bootstrapped); else exit;
/**
 * Welcome to Recess! Let's have some fun.
 * 
 * Recess! is full-stack, PHP application development framework.
 * For tutorials, documentation, bug reports, feature suggestions
 * head over to: http://www.recessframework.com/
 * 
 * Enjoy! -Kris Jordan (http://www.krisjordan.com)
 */

RecessConf::$mode = RecessConf::DEVELOPMENT; // or RecessConf::PRODUCTION

RecessConf::$applications 
	= array(	'recess.apps.tools.RecessToolsApplication',
				'blog.BlogApplication',
			);

RecessConf::$defaultTimeZone = 'America/New_York';

RecessConf::$defaultDatabase
	= array(	'sqlite:' . $_ENV['dir.bootstrap'] . 'recess/sqlite/default.db'
				// 'mysql:host=localhost;dbname=recess', 'recess', 'recess'
			);

RecessConf::$namedDatabases
	= array( 	// 'name' => array('sqlite:' . $_ENV['dir.bootstrap'] . 'recess/sqlite/default.db')
				// 'name' => array('mysql:host=localhost;dbname=recess', 'username', 'password')
			);

// Paths to the recess and apps directories
RecessConf::$recessDir = $_ENV['dir.bootstrap'] . 'recess/';
RecessConf::$appsDir = $_ENV['dir.bootstrap'] . 'apps/';

RecessConf::$cacheProviders 
	= array(	
				// 'Apc',
				// 'Memcache',
				// 'Sqlite',
			);

RecessConf::$useTurboSpeed = false; // I wanna go FAST! (Note: Experimental feature.)
?>