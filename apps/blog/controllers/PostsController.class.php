<?php

Library::import('blog.models.Post');
Library::import('blog.models.Comment');

/** !View Native, Prefix: home/
*/
class PostsController extends Controller {
	
	/** !Route GET, /blog/ */
	function home() {

		// $this->latestPosts = Make::a('Post')->find()->orderBy('id DESC')->range(0,5);
		$this->latestPosts = array();
		
	}
	
	/** !Route GET, alpha/ */
	function alphabetical() {
		
		$this->latestPosts = Make::a('Post')->find()->orderBy('title')->range(0,5);
		
		return $this->ok('home');
		
	}
	
	/** !Route GET, comments/$postId */
	function comments($postId) {
		
		$this->comments = Make::a('Post')->equal('id',$postId)->comments();
		
	}
	
	/** !Route POST, comments/$postId */
	function newComment($postId) {
		
		$comment = Make::a('Comment')->copy($this->request->post);
		$comment->post_id = $postId;
		$comment->insert();
		
		return $this->created('/blog/comment/' . $comment->id, '/blog/comments/' . $postId);
		
	}
	
}

?>