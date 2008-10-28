<?php

Library::import('smarty.Smarty');
Library::import('recess.View');
Library::import('recess.http.Response');

abstract class SmartyView extends View {
	protected $smarty = null;
	
	public function __construct() {
		
		//Fetch Smarty object
		if($this->smarty !== ''){
			$this->smarty = new Smarty();
		}
		
		//Configure Smarty
		$this->setTemplateDir(Application::getSetting('Smarty.template_dir'));
		
		// $this->smarty->assign('base_url', Environment::$base_url);
		$this->smarty->caching = 0;
	}
	
	public function setTemplateDir($template_dir) {
		$this->smarty->template_dir	= $template_dir;
		$this->smarty->plugins_dir[] = $template_dir . 'smarty_plugins/';
		$this->smarty->cache_dir = $template_dir . 'cache/';
		$this->smarty->compile_dir = $template_dir . 'templates_c/';
	}
	
	public function getTemplateDir() {
		return $this->smarty->template_dir;
	}
	
	protected abstract function templateLocationFor(Response $response);
	
	public function render(Response $response) {
		switch($response->request->format) {
			case Formats::json:
				print json_encode($response->data);
				exit;
			default:
		}
		
		if($response->code == ResponseCodes::HTTP_CREATED) {
			exit;
		}
		
		// TODO: Fill variables here
		$this->smarty->assign('response', $response);
		$this->smarty->assign('data', $response->data);
		$this->smarty->assign('parameters', $response->meta['function_args']);
		
		if(is_array($response->data)) {
			foreach(array_keys($response->data) as $key) {
				$this->smarty->assign($key, $response->data[$key]);
			}
		}
		$this->smarty->display($this->templateLocationFor($response));
	}
	
}

?>