<?php
Library::import('recess.framework.views.AbstractView');
Library::import('recess.http.Response');

class SmartyView extends AbstractView {
	protected $smarty = null;
	
	public function __construct() {
		Library::import('smarty.Smarty');
		
		if($this->smarty !== ''){
			$this->smarty = new Smarty();
		}
		
		$this->smarty->caching = 0;
		$this->smarty->assign('url_base', $_ENV['url.base']);
		$this->smarty->force_compile = RecessConf::$mode == RecessConf::DEVELOPMENT;
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
				foreach($response->data as $key => $value) {
					if($value instanceof ModelSet) {
						$response->data[$key] = $value->toArray();
					}
				}
				if(isset($response->data['application'])) unset ($response->data['application']);
				if(isset($response->data['controller'])) unset ($response->data['controller']);
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
		
		try {
			$this->smarty->display($this->smarty->template_dir . $response->meta->viewName . '.tpl');
		} catch(Exception $e) {
			//print_r($e); exit;
			if(!file_exists($this->smarty->cache_dir)) {
				mkdir($this->smarty->cache_dir,null,true);
			}
			if(!file_exists($this->smarty->compile_dir)) {
				mkdir($this->smarty->compile_dir,null,true);
			}
			$this->smarty->display($this->smarty->template_dir . $response->meta->viewName . '.tpl');
		}
	}
	
}

?>