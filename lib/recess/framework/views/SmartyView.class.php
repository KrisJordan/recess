<?php
Library::import('smarty.Smarty');
Library::import('recess.framework.views.AbstractView');
Library::import('recess.http.Response');

class SmartyView extends AbstractView {
	protected $smarty = null;
	
	public function __construct() {
		//Fetch Smarty object
		if($this->smarty !== ''){
			$this->smarty = new Smarty();
		}
		
		// $this->smarty->assign('base_url', Environment::$base_url);
		$this->smarty->caching = 0;
	}
	
	public function setTemplateDir($template_dir) {
		$this->smarty->template_dir	= $template_dir;
		$this->smarty->cache_dir = $_ENV['dir.temp'] . 'smarty/cache/';
		$this->smarty->compile_dir = $_ENV['dir.temp'] . 'smarty/templates_c/';
	}
	
	public function getTemplateDir() {
		return $this->smarty->template_dir;
	}
	
	public function render(Response $response) {
		$this->setTemplateDir($response->meta->viewDir);
		
		switch($response->request->format) {
			case Formats::json:
				print json_encode($response->data);
				exit;
			default:
		}
		
		if($response->code == ResponseCodes::HTTP_CREATED) { exit; }
		
		// TODO: Fill variables here
		$this->smarty->assign('response', $response);
		$this->smarty->assign('request', $response->request);
		
		try {
			foreach($response->data as $key => $value) {
				$this->smarty->assign($key, $value);
			}
		} catch(Exception $e) {}
		
		$this->smarty->display($this->smarty->template_dir . $response->meta->viewName . '.tpl');
	}
	
}

?>