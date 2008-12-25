<?php
/**
 * Recess! Framework is bootstrapped by passing control to Recess::main().
 * 
 * @author Kris Jordan
 */
$_ENV['dir.bootstrap'] = str_replace('\\','/',realpath(dirname(__FILE__))) . '/';
$_ENV['url.base'] = str_replace('bootstrap.php', '', $_SERVER['PHP_SELF']);

$bootstrapped = true;
require_once('./recess-conf.php');
RecessConf::init();

Library::import('recess.diagnostics.Diagnostics');
set_error_handler(array('Diagnostics','handleError'));
set_exception_handler(array('Diagnostics','handleException'));

Library::import('recess.http.Environment');
Library::import('recess.Recess');

// Entry point to Recess!
Recess::main(Environment::getRawRequest(), RecessConf::$policy, RecessConf::$applications, RecessConf::getRoutes(), RecessConf::$plugins);

// RecessConf follows...

abstract class RecessConf {

	const DEVELOPMENT = 0;
	const PRODUCTION = 1;
	
	public static $mode = self::PRODUCTION;
	
	public static $recessDir = '';
	
	public static $appsDir = '';
	
	public static $useTurboSpeed = false;
	
	public static $cacheProviders = array();
	
	public static $applications = array();
	
	public static $plugins = array();
	
	public static $defaultDatabase = array();
	
	public static $namedDatabases= array();
	
	public static $settings = array();
	
	public static $defaultTimeZone = 'America/New_York';
	
	public static $policy;
	
	static function init() {
		if(self::$mode == self::PRODUCTION) {
			self::$useTurboSpeed = true;
		}
		
		$_ENV['dir.recess'] = self::$recessDir;
		$_ENV['dir.apps'] = self::$appsDir;
		$_ENV['dir.test'] = self::$recessDir . 'test/';
		$_ENV['dir.temp'] = self::$recessDir . 'temp/';
		$_ENV['dir.lib'] = self::$recessDir . 'lib/';
		if(!isset($_ENV['url.content'])) {
			$_ENV['url.content'] = $_ENV['url.base'] . 'content/';
		}
		
		date_default_timezone_set(self::$defaultTimeZone);
		
		require_once($_ENV['dir.lib'] . 'recess/lang/Library.class.php');
		Library::addClassPath($_ENV['dir.lib']);
		
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
				die('Application "' . $app . '" does not exist. Remove it from recess-conf.php, RecessConf::$applications array.');
			} else {
				$app = Library::getClassName($app);
				self::$applications[$key] = new $app;
			}
		}
		
		Library::import('recess.database.Databases');
		Library::import('recess.database.orm.ModelDataSource');
		Databases::setDefaultSource(new ModelDataSource(RecessConf::$defaultDatabase));//,RecessConf::$defaultDatabase[1],RecessConf::$defaultDatabase[2]));
		
		if(!empty(RecessConf::$namedDatabases)) {
			foreach(RecessConf::$namedDatabases as $name => $sourceInfo) {
				Databases::addSource($name, new ModelDataSource($sourceInfo));
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
				try {
					$app->addRoutesToRouter($router);
				} catch (DuplicateRouteException $e) {
					throw new RecessErrorException("Conflicting routes found: " . $e->getMessage(), 0, 0, $e->file, $e->line, array());
				}
			}
			Cache::set(self::ROUTES_CACHE_KEY, $router);	
		}

		return $router;
	}
	
}

?>