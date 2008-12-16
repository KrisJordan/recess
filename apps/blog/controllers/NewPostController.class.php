<?php
Library::import('blog.models.Post');
Library::import('recess.framework.forms.Form');

/**
 * !View Native, Prefix: posts/
 * !RoutesPrefix new-posts/
 */
class NewPostController extends Controller {
	
	/** @var Post */
	protected $post;
	
	/** @var Form */
	protected $_form;
	
	function init() {
		$this->post = new Post();
		$this->_form = new ModelForm('post', $this->post, $this->request->var('post'));
	}
	
	/** !Route GET */
	function index() {
		$this->posts = $this->post->all();
	}
	
	/** !Route GET, $id */
	function details($id) {
		$this->editForm($id);
		return $this->ok('editForm');
	}
	
	/** !Route GET, new */
	function newForm() {
		$this->form->for($this, 'insert');
		return $this->ok('editForm');
	}
	
	/** !Route POST */
	function insert() {
		try {
			$this->post->insert();
			return $this->created($this->urlTo('details',$this->post->id));		
		} catch(ModelValidationException $exception) {
			$this->form->for('insert');
			$this->form->handle($exception);
			return $this->conflict('editForm');
		}
	}
	
	/** !Route GET, $id/edit */
	function editForm($id) {
		$this->post->id = $id;
		if($this->post->exists()) {
			$this->form->for($this, 'update', $id);
		} else {
			return $this->forwardNotFound($this->urlTo('index'), 'Post does not exist.');
		}
	}
	
	/** !Route PUT, $id */
	function update($id) {
		$this->post->id = $id;
		if($this->post->exists()) {
			$this->post->update();
			return $this->forwardOk($this->urlTo('details',$this->post->id));
		} else {
			return $this->forwardNotFound($this->urlTo('index'), 'Post does not exist.');
		}
	}
	
	/** !Route DELETE, $id */
	function delete($id) {
		$this->post->id = $id;
		if($this->post->delete()) {
			return $this->forwardOk($this->urlTo('index'));
		} else {
			return $this->forwardNotFound($this->urlTo('index'), 'Post does not exist.');
		}
	}
	
	
	function getPostForm($method, $action, $fillValues = array()) {
		Library::import('recess.framework.forms.Form');
		$form = new Form();
		$form->method = $method;
		$form->action = $action;
		$form->flash = "";
		$form->inputs['title'] = new TextInput('post[title]', '', '','');
		$form->inputs['body'] = new TextInput('post[body]', '', '','');
		$form->inputs['isPublic'] = new TextInput('post[isPublic]', '', '','');
		$form->inputs['modifiedAt'] = new TextInput('post[modifiedAt]', '', '','');
		$form->inputs['createdOn'] = new TextInput('post[createdOn]', '', '','');
		$form->fill($fillValues);
		return $form;
	}
}
?>