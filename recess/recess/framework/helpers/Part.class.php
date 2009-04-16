<?php
/**
 * @todo Exception handling
 * 
 * @author Kris
 */
class Part extends AbstractHelper {
	
	protected static $app;
	
	protected static $loaded = array();
	
	public static function init(AbstractView $view) {
		$response = $view->getResponse();
		self::$app = $response->meta->app;
	}
	
	public static function render() { 
		$args = func_get_args();
		$templateFile = self::$app->getViewsDir() . $args[0] . '.part.php';
	
		if(!isset(self::$loaded[$templateFile])) {
			$template = file_get_contents($templateFile);
			
			preg_match_all("/assert(?:\W*)\\((?:\W*)(?:is_(.*?)(?:\W*)\\((?:\W*)\\$(.*?)(?:\W*)\\)|\\$(\\w*?)(?:\W*)instanceof(?:\W*)(\\w*?))(?:\W*)\\)(?:\W*);/",
							$template,
							$matches);
			
			$parameters = array();
			foreach($matches[0] as $key => $value) {
				if(isset($matches[1][$key]) && $matches[1][$key] != '') {
					$parameters[$matches[2][$key]] = $matches[1][$key];
				} else if (isset($matches[3][$key])) {
					$parameters[$matches[3][$key]] = $matches[4][$key];
				}
			}
			
			self::$loaded[$templateFile] = $parameters;
		}
		
		$parameters = self::$loaded[$templateFile];
		$keys = array_keys($parameters);
		array_unshift($keys, '');
		$params = array_combine($keys, $args);
		extract($params);
		include($templateFile);
	}
	
}
?>