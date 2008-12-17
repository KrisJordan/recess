<?php
Library::import('blog.models.Post');
Library::import('recess.framework.forms.ModelForm');

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
		$this->_form = new ModelForm('post', $this->request->data('post'), $this->post);
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
		$this->_form->to(Methods::POST, $this->urlTo('insert'));
		return $this->ok('editForm');
	}
	
	/** !Route POST */
	function insert() {
		try {
			$this->post->insert();
			return $this->created($this->urlTo('details', $this->post->id));		
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
			$this->_form->to(Methods::PUT, $this->urlTo('update', $id));
		} else {
			return $this->forwardNotFound($this->urlTo('index'), 'Post does not exist.');
		}
	}
	
	/** !Route PUT, $id */
	function update($id) {
		$oldPost = new Post($id);
		if($oldPost->exists()) {
			$oldPost->copy($this->post)->save();
			return $this->forwardOk($this->urlTo('details', $id));
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
}
?>