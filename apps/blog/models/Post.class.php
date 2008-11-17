<?php
Library::import('blog.models.Comment');

/**
 * !HasMany comments, OnDelete: Cascade
 * !BelongsTo author, Class: User
 * !HasMany tags, Through: PostsTags
 */
class Post extends Model { }

class BlogController extends Controller {
	/** !Route GET, /posts/ */
	function showPosts() {
		$this->posts = Make::a('Post')->all();
	}
}

?>