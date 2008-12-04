<?php
Library::import('recess.framework.controllers.Controller');

/**
 * !View Native, Prefix: apps/
 * !RoutesPrefix apps/
 */
class RecessToolsAppsController extends Controller {
	
	/** !Route GET */
	public function home() {
		$this->apps = Config::$applications;
		if(isset($this->request->get->flash)) {
			$this->flash = $this->request->get['flash'];
		}
	}
	
	/** !Route GET, uninstall/$appClass */
	public function uninstall($appClass) {
		//Library::getFullyQualifiedClassName($appClass);
		$this->app = new $appClass;
	}
	
	/** !Route GET, new */
	public function newApp() {
		$writeable = is_writable($_ENV['dir.apps']);
		
		$this->appsDirWriteable = $writeable;
		if($this->appsDirWriteable) {
			$this->form = $this->getNewAppForm();
			return $this->ok('newAppWizard');
		} else {
			return $this->ok('newAppInstructions');
		}
	}
	
	/** !Route POST, new */
	public function newAppPost() {
		$form = $this->getNewAppForm($this->request->post);
		$form->assertNotEmpty('appName');
		$form->assertNotEmpty('programmaticName');
		if($form->hasErrors()) {
			$this->form = $form;
			return $this->conflict('newAppWizard');
		} else {
			Library::import('recess.lang.Inflector');
			$this->form = $this->getNewAppStep2Form($this->request->post);
			$this->form->routingPrefix = Inflector::toCamelCaps($this->form->programmaticName) . '/';
			return $this->ok('newAppWizardStep2');
		}
	}
	
	/** !Route POST, new/step2 */
	function newAppStep2 () {
		$form = $this->getNewAppStep2Form($this->request->post);
		$form->assertNotEmpty('routingPrefix');
		if($form->hasErrors()) {
			$this->form = $form;
			return $this->conflict('newAppWizardStep2');
		} else {
			$this->generateApp();
			return $this->ok('newAppWizardComplete');
		}
	}
	
	private function generateApp() {
		Library::import('recess.lang.Inflector');
		
		$appName = $this->request->post['appName'];
		$programmaticName = Inflector::toProperCaps($this->request->post['programmaticName']);
		$camelProgrammaticName = Inflector::toCamelCaps($programmaticName);
		
		$this->applicationClass = $programmaticName . 'Application';
		$this->applicationFullClass = $camelProgrammaticName . '.' . $this->applicationClass;
		$this->appName = $appName;
		
		$routesPrefix = $this->request->post['routingPrefix'];
		if(substr($routesPrefix,-1) != '/') { $routesPrefix .= '/'; }
		$appDir = $_ENV['dir.apps'] . $camelProgrammaticName;
		
		$this->messages = array();
		$this->messages[] = $this->tryCreatingDirectory($appDir, 'application');
		
		$appReplacements = array('appName' => $appName, 'programmaticName' => $programmaticName, 'camelProgrammaticName' => $camelProgrammaticName, 'routesPrefix' => $routesPrefix);
		$this->messages[] = $this->tryGeneratingFile('Application Class', $this->application->codeTemplatesDir . 'Application.template.php', $appDir . '/' . $programmaticName . 'Application.class.php', $appReplacements);
		
		$this->messages[] = $this->tryCreatingDirectory($appDir . '/models', 'models');
		
		$this->messages[] = $this->tryCreatingDirectory($appDir . '/controllers', 'controllers');
		$this->messages[] = $this->tryGeneratingFile('Home Controller', $this->application->codeTemplatesDir . 'HomeController.template.php', $appDir . '/controllers/' . $programmaticName . 'HomeController.class.php', $appReplacements);
		
		$this->messages[] = $this->tryCreatingDirectory($appDir . '/views', 'views');
		$this->messages[] = $this->tryCreatingDirectory($appDir . '/views/common', 'common views');
		$this->messages[] = $this->tryGeneratingFile('Header View Template', $this->application->codeTemplatesDir . 'header.template.php', $appDir . '/views/common/header.php', $appReplacements);
		$this->messages[] = $this->tryGeneratingFile('Navigation Template', $this->application->codeTemplatesDir . 'navigation.template.php', $appDir . '/views/common/navigation.php', $appReplacements);
		$this->messages[] = $this->tryGeneratingFile('Footer Template', $this->application->codeTemplatesDir . 'footer.template.php', $appDir . '/views/common/footer.php', $appReplacements);
		$this->messages[] = $this->tryCreatingDirectory($appDir . '/views/home', 'home views');
		$this->messages[] = $this->tryGeneratingFile('Home Template', $this->application->codeTemplatesDir . 'home.template.php', $appDir . '/views/home/home.php', $appReplacements);
	}
	
	private function tryCreatingDirectory($path, $name) {
		$message = '';
		try { 
			$message = 'Creating ' . $name . ' dir "' . $path . '" ... ';
			mkdir($path);
			$message .= 'ok.';
		} catch (Exception $e) {
			if(file_exists($path)) $message .= ' already exists.';
			else $message .= 'failed.';
		}
		return $message;
	}
	
	private function tryGeneratingFile($name, $template, $outputFile, $values) {
		$templateContents = file_get_contents($template);
		$search = array_keys($values);
		foreach($search as $key => $value) {
			$search[$key] = '/\{\{' . $value . '\}\}/';
		}
		$replace = array_values($values);
		foreach($replace as $key => $value) {
			$replace[$key] = addslashes($value);
		}
		$output = preg_replace($search,$replace,$templateContents);
		
		$message = '';
		try {
			$message = 'Generating ' . $name . ' at "' . $outputFile . '" ... ';
			if(file_exists($outputFile)) {
				throw new Exception('file exists');
			}
			file_put_contents($outputFile, $output);
			$message .= 'ok.';
		} catch(Exception $e) {
			if(file_exists($outputFile)) $message .= ' already exists. Not overwriting.';
			else $message .= 'failed.';
		}
		return $message;
	}
	
	private function getNewAppForm($fillValues = array()) {
		Library::import('recess.framework.forms.Form');
		$form = new Form();
		$form->method = "POST";
		$form->flash = "";
		$form->action = $this->urlToMethod('newApp');
		$form->inputs['appName'] = new TextInput('appName', '', '','');
		$form->inputs['programmaticName'] = new TextInput('programmaticName', '', '','');
		$form->fill($fillValues);
		return $form;
	}
	
	private function getNewAppStep2Form($fillValues = array()) {
		Library::import('recess.framework.forms.Form');
		$form = new Form();
		$form->method = "POST";
		$form->flash = "";
		$form->action = $this->urlToMethod('newAppStep2');
		$form->inputs['appName'] = new HiddenInput('appName', '');
		$form->inputs['programmaticName'] = new HiddenInput('programmaticName', '');
		$form->inputs['routingPrefix'] = new TextInput('routingPrefix', '','','');
		$form->fill($fillValues);
		return $form;
	}

	/** !Route GET, $appClass */
	public function app($appClass) {
		$application = $this->getApplication($appClass);
		if(!$application instanceof Application) {
			return $application; // App not found
		}
		
		$this->app = $application;
	}
	
	/** !Route GET, model/gen */
	public function createModel() {
		
	}
	
	/** !Route GET, controller/gen */
	public function createController() {
		
	}
	
	private function getApplication($appClass) {
		foreach(Config::$applications as $app) {
			if(get_class($app) == $appClass) {
				return $app;
			}
		}
		return $this->forwardNotFound($this->urlToMethod('home'), 'Application ' . $appClass . ' does not exist or is not enabled.');
	}
	
}

?>