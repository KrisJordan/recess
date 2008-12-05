<?php if(isset($bootstrapped)) unset($bootstrapped); else exit;

/* RECESS FRAMEWORK CONFIGURATION SETTINGS */

RecessConf::$mode = RecessConf::DEVELOPMENT; // or RecessConf::PRODUCTION

RecessConf::$applications 
	= array(	'recess.apps.tools.RecessToolsApplication',
				'blog.BlogApplication',
			);

RecessConf::$defaultTimeZone = 'America/New_York';

RecessConf::$defaultDatabase
	= array(	'sqlite:' . $_ENV['dir.documentRoot'] . 'recess/sqlite/default.db'
				// 'mysql:host=localhost;dbname=recess', 'recess', 'recess'
			);

RecessConf::$namedDatabases
	= array( 	// 'name' => array('sqlite:' . $_ENV['dir.documentRoot'] . 'recess/sqlite/default.db')
				// 'name' => array('mysql:host=localhost;dbname=recess', 'username', 'password')
//			 	'sqlite2' => 'sqlite:' . $_ENV['dir.documentRoot'] . 'recess/sqlite/sqlite2.db',
				'recess' => array(  
	                'mysql:host=localhost;dbname=recess',  
	                'recess',  
	                'recess'),
			);

// Paths to the recess and apps directories
RecessConf::$recessDir = $_ENV['dir.documentRoot'] . 'recess/';
RecessConf::$appsDir = $_ENV['dir.documentRoot'] . 'apps/';

RecessConf::$cacheProviders 
	= array(	
				// 'Apc',
				// 'Memcache',
				// 'Sqlite',
			);

RecessConf::$useTurboSpeed = false; // I wanna go FAST! (Note: Experimental feature.)

?>