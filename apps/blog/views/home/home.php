<?php

foreach($latestPosts as $post) {
	
	echo '<h1>' . $post->title . '</h1>';
	
	echo '<p>' . $post->body . '</p>';
	
	echo '<p><a href="comments/' . $post->id . '">' . $post->comments()->count() . ' Comments</a></p>';
	
	
}

?>