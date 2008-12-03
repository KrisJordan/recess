<?php
Library::import('blog.models.Post');
Library::import('blog.models.Comment');

/** !View Native, Prefix: home/ */
class PostsController extends Controller {
	
	protected $formats = array(Formats::xhtml, Formats::json);
	
	/** !Route GET */
	function home() {

		$this->latestPosts = Make::a('Post')->find()->orderBy('id DESC')->range(0,5);
		
	}
	
	/** !Route GET, new */
	function newPost () { }
	
	/** !Route POST, new */
	function createNewPost () {
		
		$post = new Post();
		$post->copy($this->request->post);
		$post->created = $post->modified = date( 'Y-m-d H:i:s', time() );
		$post->save();
		
		return $this->created('/blog/posts/' . $post->id, '/blog/');
		
	}
	
	/** !Route GET, comments/$postId */
	function comments($postId) {
		
		$this->post = Make::a('Post')->equal('id',$postId)->first();
		$this->comments = $this->post->comments();
		
	}
	
	/** !Route POST, comments/$postId */
	function newComment($postId) {
		
		$comment = Make::a('Comment')->copy($this->request->post);
		$comment->post_id = $postId;
		$comment->insert();
		
		return $this->created('/blog/comment/' . $comment->id, '/blog/comments/' . $postId);
		
	}
	
	/** !Route GET, comment/$commentId/delete/ */
	function deleteComment($commentId) {
		$comment = new Comment();
		$comment->id = $commentId;
		$post = $comment->post();
		$comment->delete();
		Library::import('recess.http.ForwardingResponse');
		return $this->forwardOk('/blog/comments/' . $post->id);
	}
	
}

?>