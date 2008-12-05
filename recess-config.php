<?php if(isset($bootstrapped)) unset($bootstrapped); else exit;

/* RECESS FRAMEWORK CONFIGURATION SETTINGS */

Config::$mode = Config::DEVELOPMENT; // or Config::PRODUCTION

Config::$applications 
	= array(	'recess.apps.tools.RecessToolsApplication',
				'blog.BlogApplication',
			);
			
Config::$defaultTimeZone = 'America/New_York';

Config::$defaultDataSource 
	= array(	'sqlite:' . $_ENV['dir.documentRoot'] . 'recess/sqlite/default.db'
				// 'mysql:host=localhost;dbname=recess', 'recess', 'recess'
			);

Config::$namedDataSources 
	= array( 	// 'name' => array('sqlite:' . $_ENV['dir.documentRoot'] . 'recess/sqlite/default.db')
				// 'name' => array('mysql:host=localhost;dbname=recess', 'username', 'password')
//			 	'sqlite2' => 'sqlite:' . $_ENV['dir.documentRoot'] . 'recess/sqlite/sqlite2.db',
				'recess' => array(  
	                'mysql:host=localhost;dbname=recess',  
	                'recess',  
	                'recess'),
			);
			
// Paths to the recess and apps directories
Config::$recessDir = $_ENV['dir.documentRoot'] . 'recess/';
Config::$appsDir = $_ENV['dir.documentRoot'] . 'apps/';

Config::$cacheProviders 
	= array(	
				// 'Apc',
				// 'Memcache',
				'Sqlite',
			);

Config::$useTurboSpeed = false; // I wanna go FAST! (Note: Experimental feature.)

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
		
		require_once(self::$recessDir . 'lib/recess/Recess.php');
		
		if(self::$useTurboSpeed) {
			Library::$useNamedRuns = true;
			$cacheProvidersReversed = array_reverse(self::$cacheProviders);
			foreach($cacheProvidersReversed as $provider) {
				$provider = $provider . 'CacheProvider';
				Cache::reportsTo(new $provider);
			}
		}
		
		Library::init();
		Library::beginNamedRun('recess');
		
		Library::addClassPath(self::$appsDir);
		
		Library::import('recess.framework.Application');
		foreach(self::$applications as $key => $app) {
			if(!Library::classExists($app)) {
				die('Application "' . $app . '" does not exist. Remove it from recess-config.php, Config::$applications array.');
			} else {
				$app = Library::getClassName($app);
				self::$applications[$key] = new $app;
			}
		}
		
		Library::import('recess.sources.db.DbSources');
		Library::import('recess.sources.db.orm.ModelDataSource');
		DbSources::setDefaultSource(new ModelDataSource(Config::$defaultDataSource));//,Config::$defaultDataSource[1],Config::$defaultDataSource[2]));
		
		if(!empty(Config::$namedDataSources)) {
			foreach(Config::$namedDataSources as $name => $sourceInfo) {
				DbSources::addSource($name, new ModelDataSource($sourceInfo));
			}
		}
		
		Library::import('recess.framework.DefaultPolicy');
		self::$policy = new DefaultPolicy();
	}
	
	const ROUTES_CACHE_KEY = 'Recess::Routes';
	
	static function getRoutes() {
		Library::import('recess.framework.routing.RtNode');
		Library::import('recess.framework.routing.Route');

		$router = Cache::get(self::ROUTES_CACHE_KEY);
		if($router === false) {
			$router = new RtNode();
			foreach(self::$applications as $app) {
				$app->addRoutesToRouter($router);
			}
			Cache::set(self::ROUTES_CACHE_KEY, $router);	
		}

		return $router;
	}
	
}

Config::init();

?>