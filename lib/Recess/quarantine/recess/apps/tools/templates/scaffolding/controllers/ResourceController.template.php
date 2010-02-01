<?php
Library::import('{{fullyQualifiedModel}}');
Library::import('recess.framework.forms.ModelForm');

/**
 * !RespondsWith Layouts
 * !Prefix {{routesPrefix}}/
 */
class {{modelName}}Controller extends Controller {
	
	/** @var {{modelName}} */
	protected ${{modelNameLower}};
	
	/** @var Form */
	protected $_form;
	
	function init() {
		$this->{{modelNameLower}} = new {{modelName}}();
		$this->_form = new ModelForm('{{modelNameLower}}', $this->request->data('{{modelNameLower}}'), $this->{{modelNameLower}});
	}
	
	/** !Route GET */
	function index() {
		$this->{{modelNameLower}}Set = $this->{{modelNameLower}}->all();
		if(isset($this->request->get['flash'])) {
			$this->flash = $this->request->get['flash'];
		}
	}
	
	/** !Route GET, ${{primaryKey}} */
	function details(${{primaryKey}}) {
		$this->{{modelNameLower}}->{{primaryKey}} = ${{primaryKey}};
		if($this->{{modelNameLower}}->exists()) {
			return $this->ok('details');
		} else {
			return $this->forwardNotFound($this->urlTo('index'));
		}
	}
	
	/** !Route GET, new */
	function newForm() {
		$this->_form->to(Methods::POST, $this->urlTo('insert'));
		return $this->ok('editForm');
	}
	
	/** !Route POST */
	function insert() {
		try {
			$this->{{modelNameLower}}->insert();
			return $this->created($this->urlTo('details', $this->{{modelNameLower}}->{{primaryKey}}));		
		} catch(Exception $exception) {
			return $this->conflict('editForm');
		}
	}
	
	/** !Route GET, ${{primaryKey}}/edit */
	function editForm(${{primaryKey}}) {
		$this->{{modelNameLower}}->{{primaryKey}} = ${{primaryKey}};
		if($this->{{modelNameLower}}->exists()) {
			$this->_form->to(Methods::PUT, $this->urlTo('update', ${{primaryKey}}));
		} else {
			return $this->forwardNotFound($this->urlTo('index'), '{{modelName}} does not exist.');
		}
	}
	
	/** !Route PUT, ${{primaryKey}} */
	function update(${{primaryKey}}) {
		$old{{modelName}} = new {{modelName}}(${{primaryKey}});
		if($old{{modelName}}->exists()) {
			$old{{modelName}}->copy($this->{{modelNameLower}})->save();
			return $this->forwardOk($this->urlTo('details', ${{primaryKey}}));
		} else {
			return $this->forwardNotFound($this->urlTo('index'), '{{modelName}} does not exist.');
		}
	}
	
	/** !Route DELETE, ${{primaryKey}} */
	function delete(${{primaryKey}}) {
		$this->{{modelNameLower}}->{{primaryKey}} = ${{primaryKey}};
		if($this->{{modelNameLower}}->delete()) {
			return $this->forwardOk($this->urlTo('index'));
		} else {
			return $this->forwardNotFound($this->urlTo('index'), '{{modelName}} does not exist.');
		}
	}
}
?>