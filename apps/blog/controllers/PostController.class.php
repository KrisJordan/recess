<?php
Library::import('blog.models.Post');

/**
 * !View Native, Prefix: posts/
 * !RoutesPrefix posts/
 */
class PostController extends Controller {
	
	protected $post;
	
	function init() {
		$this->post = new Post();
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
		$this->form = $this->getPostForm(Methods::POST, $this->urlTo('create'));
		return $this->ok('editForm');
	}
	
	/** !Route POST */
	function create() {
		$this->post->copy($this->request->post['post']);
		$this->post->insert();
		return $this->created($this->urlTo('details',$this->post->id), $this->urlTo('details',$this->post->id));
	}
	
	/** !Route GET, $id/edit */
	function editForm($id) {
		$this->post->id = $id;
		$this->post = $this->post->find()->first();
		
		$this->form = $this->getPostForm(Methods::PUT, $this->urlTo('update', $id), get_object_vars($this->post));
		
		if($this->post == false) {
			return $this->forwardNotFound($this->urlTo('index'), 'Post does not exist.');
		}
	}
	
	/** !Route PUT, $id */
	function update($id) {
		$this->post->id = $id;
		$this->post->copy($this->request->put['post']);
		$this->post->update();
		return $this->forwardOk($this->urlTo('details',$this->post->id));
	}
	
	/** !Route DELETE, $id */
	function delete($id) {
		$this->post->id = $id;
		$this->post->delete();
		return $this->forwardOk($this->urlTo('index'));
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