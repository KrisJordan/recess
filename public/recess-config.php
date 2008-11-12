<?php if(!isset($_ENV['dir.base'])) exit;

/* BEGIN RECESS APPS CONFIGURATION SETTINGS */

Config::$mode = Config::DEVELOPMENT;

Config::$useCache = false;

Config::$cacheProviders 
	= array(	// 'Apc',
				// 'Memcache',
				'Disk'
			);

Config::$applications 
	= array(	'frontend.FrontEndApplication',
				'backend.BackEndApplication',
				'recess.apps.ide.RecessIdeApplication',
				'blog.BlogApplication'
			);

//Config::$plugins 
//	= array( 	'lib.recess.framework.plugins.ContentCaching'
//			);

Config::$defaultDataSource 
	= array(	'sqlite:' . $_ENV['dir.base'] . '/data/default.db'
			);			
			
//Config::$namedDataSources 
//	= array( 	'name' => array('dsn'),
//				'name' => array('dsn','user','pass','options')
//			);

Config::$settings 
	= array(	'dir.temp' => $_ENV['dir.base'] . 'temp/',
				'dir.test' => $_ENV['dir.base'] . 'test/',
				'dir.apps' => $_ENV['dir.base'] . 'apps/'
			);

Library::import('recess.framework.DefaultPolicy');
Config::$policy
	= new DefaultPolicy();

/* END OF BASIC CONFIGURATION SETTINGS */

abstract class Config {

	const DEVELOPMENT = 0;
	const DEPLOYMENT = 1;
	
	public static $mode = self::DEPLOYMENT;
	
	public static $useCache = false;
	
	public static $cacheProviders = array('Disk');
	
	public static $applications = array();
	
	public static $plugins = array();
	
	public static $defaultDataSource = array();
	
	public static $namedDataSources = array();
	
	public static $settings = array();
	
	public static $policy;
	
	static function init() {
		
		if(isset(self::$settings['dir.temp'])) {
			$_ENV['dir.temp'] = self::$settings['dir.temp'];
		} else {
			$_ENV['dir.temp'] = $_ENV['dir.base'] . 'temp/';
		}
		
		if(!isset(self::$settings['dir.test'])) {
			$_ENV['dir.test'] = self::$settings['dir.test'];
		} else {
			$_ENV['dir.test'] = $_ENV['dir.base'] . 'test/';
		}
		
		if(!isset(self::$settings['dir.apps'])) {
			$_ENV['dir.apps'] = self::$settings['dir.apps'];
		} else {
			$_ENV['dir.apps'] = $_ENV['dir.base'] . 'apps/';
		}
		
		Library::addClassPath(self::$settings['dir.apps']);
		
		Config::$applications = array_map(create_function('$class','return Library::importAndInstantiate($class);'),Config::$applications);
		
		Library::import('recess.sources.db.DbSources');
		Library::import('recess.sources.db.orm.ModelDataSource');
		DbSources::setDefaultSource(new ModelDataSource(Config::$defaultDataSource[0]));
	}
	
	static function getRouter() {
		Library::import('recess.framework.routing.RoutingNode');
		Library::import('recess.framework.routing.Route');
		//apc_delete('router');
		//$router = apc_fetch('router');
		//if($router === false) {
			$router = new RoutingNode();
			foreach(self::$applications as $app) {
				$app->addRoutesToRouter($router);
			}
//			apc_store('router', $router);	
//		}

		return $router;
	}
	
}

Config::init();

?>