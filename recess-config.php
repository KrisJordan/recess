<?php if(isset($bootstrapped)) unset($bootstrapped); else exit;

/* RECESS FRAMEWORK CONFIGURATION SETTINGS */

Config::$mode = Config::DEVELOPMENT; // Config::PRODUCTION

// Paths to the recess and apps directories
Config::$recessDir = $_ENV['dir.documentRoot'] . 'recess/';
Config::$appsDir = $_ENV['dir.documentRoot'] . 'apps/';

Config::$defaultTimeZone = 'America/New_York';

Config::$defaultDataSource 
	= array(	'sqlite:' . $_ENV['dir.documentRoot'] . '/recess/sqlite/default.db'
				// 'mysql:host=localhost;dbname=recess', 'recess', 'recess'
			);

Config::$cacheProviders 
	= array(	// 'Apc',
				// 'Memcache',
				// 'Disk'
			);

Config::$useTurboSpeed = false; // I wanna go FAST!

Config::$applications 
	= array(	'recess.apps.tools.RecessToolsApplication',
				'blog.BlogApplication'
			);

//Config::$plugins 
//	= array( 	'recess.framework.plugins.ContentCaching'
//			);


			
//Config::$namedDataSources 
//	= array( 	'name' => array('dsn'),
//				'name' => array('dsn','user','pass','options')
//			);

/* END OF BASIC CONFIGURATION SETTINGS */

abstract class Config {

	const DEVELOPMENT = 0;
	const PRODUCTION = 1;
	
	public static $mode = self::PRODUCTION;
	
	public static $recessDir = '';
	
	public static $appsDir = '';
	
	public static $useTurboSpeed = false;
	
	public static $cacheProviders = array();
	
	public static $applications = array();
	
	public static $plugins = array();
	
	public static $defaultDataSource = array();
	
	public static $namedDataSources = array();
	
	public static $settings = array();
	
	public static $defaultTimeZone = 'America/New_York';
	
	public static $policy;
	
	static function init() {
		$_ENV['dir.recess'] = self::$recessDir;
		$_ENV['dir.apps'] = self::$appsDir;
		$_ENV['dir.test'] = self::$recessDir . 'test/';
		$_ENV['dir.temp'] = self::$recessDir . 'temp/';
		$_ENV['url.content'] = $_ENV['url.base'] . 'content/';
		
		date_default_timezone_set(self::$defaultTimeZone);
		
		if(self::$useTurboSpeed) {
			Library::$useNamedRuns = true;
			$cacheProvidersReversed = array_reverse(self::$cacheProviders);
			foreach($cacheProvidersReversed as $provider) {
				$provider = $provider . 'CacheProvider';
				Cache::reportsTo(new $provider);
			}
		}
		
		require_once(self::$recessDir . 'lib/recess/Recess.php');
		
		Library::init();
		Library::beginNamedRun('recess');
		
		Library::addClassPath(self::$appsDir);
		
		// TODO: GET RID OF THIS
		Library::import('recess.framework.Application');
		self::$applications = array_map(create_function('$class','return Library::importAndInstantiate($class);'),Config::$applications);
		
		Library::import('recess.sources.db.DbSources');
		Library::import('recess.sources.db.orm.ModelDataSource');
		DbSources::setDefaultSource(new ModelDataSource(Config::$defaultDataSource[0]));//,Config::$defaultDataSource[1],Config::$defaultDataSource[2]));
		
		Library::import('recess.framework.DefaultPolicy');
		self::$policy = new DefaultPolicy();
	}
	
	static function getRouter() {
		Library::import('recess.framework.routing.RoutingNode');
		Library::import('recess.framework.routing.Route');

		$router = Cache::get('router');
		if($router === false) {
			$router = new RoutingNode();
			foreach(self::$applications as $app) {
				$app->addRoutesToRouter($router);
			}
			Cache::set('router', $router);	
		}

		return $router;
	}
	
}

Config::init();

?>