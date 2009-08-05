<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.http.responses.ErrorResponse');
Library::import('recess.http.responses.NotFoundResponse');
Library::import('recess.http.responses.OkResponse');

Library::import('recess.apps.tools.models.RecessReflectorClass');
Library::import('recess.apps.tools.models.RecessReflectorPackage');
Library::import('recess.apps.tools.models.RecessReflectorProperty');
Library::import('recess.apps.tools.models.RecessReflectorMethod');

/**
 * !RespondsWith Layouts
 * !Prefix code/
 */
class RecessToolsCodeController extends Controller {
	
	public function init() {
		if(RecessConf::$mode == RecessConf::PRODUCTION) {
			throw new RecessResponseException('Tools are available only during development.', ResponseCodes::HTTP_NOT_FOUND, array());
		}
	}
	
	protected function checkTables() {
		try{ // This is so hacked it's embarrasing. Sorry folks.
			Model::createTableFor('RecessReflectorClass');
		} catch(Exception $e) {}
		try{
			Model::createTableFor('RecessReflectorPackage');
		} catch(Exception $e) {}
		
	}
	
	protected function checkIndex() {
		$this->recursiveIndex($_ENV['dir.apps']);
		$this->recursiveIndex($_ENV['dir.recess']);
	}
	
	/** !Route GET */
	public function home() {
		$this->checkTables();
		$this->classes = Make::a('RecessReflectorClass')->all()->orderBy('name');
		$this->packages = Make::a('RecessReflectorPackage')->all()->orderBy('name');		
	}
	
	/** !Route GET, index */
	public function index() {
		$this->checkTables();
		$this->checkIndex();
		return $this->forwardOk($this->urlTo('home'));
	}
	
	private function recursiveIndex($base, $dir = '') {
		$dirInfo = scandir($base . $dir);
		foreach($dirInfo as $item) {
			$location = $base . $dir . '/' . $item;
			if(is_dir($location) && $item[0] != '.') {
				$this->recursiveIndex($base, $dir . '/' . $item);
			} else {
				if($item[0] == '.' || strrpos($item, '.class.php') === false) { continue; }
				$fullyQualified = str_replace('/', '.', $dir . '/' . $item);
				if($fullyQualified[0] == '.') {
					$fullyQualified = substr($fullyQualified, 1);
				}
				$fullyQualified = str_replace('..', '.', $fullyQualified);
				$fullyQualified = str_replace('.class.php','',$fullyQualified);
				
				$this->indexClass($fullyQualified, $dir . '/' . $item);
			}
		}
	}
	
	private function indexClass($fullyQualifiedClassName, $dir) {
		if(!Library::classExists($fullyQualifiedClassName)) {
			return false;
		}

		$model = Library::getClassName($fullyQualifiedClassName);
		$reflectorClass = new RecessReflectorClass();
		$reflectorClass->name = $model;
		if(!$reflectorClass->exists()) {
			$reflectorClass->fromClass($model, $dir);
		}
		
		return $reflectorClass;
	}
	
	/** !Route GET, class/$class */
	public function classInfo($class) {
		$this->checkTables();
		$result = $this->indexClass($class, '');
		
		if($result === false) {
			return new NotFoundResponse($this->request);
		}
		
		$this->reflector = $result;
		
		$className = Library::getClassName($class);
		$reflection = new RecessReflectionClass($className);
		
		$this->reflection = $reflection;
		$this->className = $className;
		
		if($reflection->isSubclassOf('Model')) {
			$this->relationships = Model::getRelationships($className);
			$this->columns = Model::getColumns($className);
			$this->table = Model::tableFor($className);
			$this->source = Model::sourceNameFor($className);
		}
	}
	 
	/** !Route GET, package/$packageName */
	function packageInfo ($packageName) {
		Library::import('recess.apps.tools.models.RecessReflectorPackage');
		$package = new RecessReflectorPackage();
		$package->name = $packageName;
		$this->package = $package->find()->first();
		
	}
	
	
	/** !Route GET, class/$fullyQualifiedModel/create */
	function createTable ($fullyQualifiedModel) {
		if(!Library::classExists($fullyQualifiedModel)) {
			return new NotFoundResponse($this->request);
		}

		$class = Library::getClassName($fullyQualifiedModel);
		
		Model::createTableFor($class);
	}
	
}

?>