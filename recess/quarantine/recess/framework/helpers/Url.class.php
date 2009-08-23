<?php
Library::import('recess.framework.AbstractHelper');

/**
 * The URL helper is used in views to generate URLs to many aspects
 * of an application (controller actions, assets, etc)
 * 
 * @author Joshua Paine
 * @author Kris Jordan
 */
class Url extends AbstractHelper {

	protected static $assetUrl;
	
	protected static $app;
	
	/**
	 * Initialize the helper with state from the View
	 * @param $view
	 */
	public static function init(AbstractView $view){
		$request = $view->getRequest();
		self::setApp($request->meta->app);
	}
	
	/**
	 * Change the application this helper refers to.
	 * @param $app
	 */
	public static function setApp(Application $app) {
		self::$app = $app;
		self::$assetUrl = self::$app->getAssetUrl();
	}
	
	/**
	 * Change the assetUrl this helper uses.
	 * 
	 * @param $url
	 */
	public static function setAssetUrl($urlPrefix) {
		self::$assetUrl = $urlPrefix;
	}
	
	/**
	 * Appends suffix parameter to the base URL to generate an absolute URL.
	 * 
	 * @param $suffix
	 * @return string Absolute URL
	 */
	public static function base($suffix = ''){
		return $_ENV['url.base'] . $suffix;
	}
	
	/**
	 * Returns the URL to an asset in the assets directory.
	 * 
	 * @param $file
	 * @return string URL to an asset.
	 */
	public static function asset($file = ''){
		return self::$assetUrl . $file;
	}
	
	/**
	 * Returns the URL to an action (Controller/Method pair). Usage example:
	 * url::action('Controller::method'[, 'arg1', ...]);
	 * 
	 * @param $actionControllerMethodPair
	 * @return string URL to an application action.
	 */
	public static function action($actionControllerMethodPair) {
		try {
			$args = func_get_args();
			return call_user_func_array(array(self::$app,'urlTo'),$args);
		} catch(Exception $e) {
			throw new RecessFrameworkException("No URL for $actionControllerMethodPair exists.", 1);
		}
	}
	
}
?>