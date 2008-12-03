<?php 
$title = 'Home';
include_once($viewsDir . 'common/header.php');
?>
<a href="./new">Write New Post</a>
<?php

foreach($latestPosts as $post) {
	
	echo '<h1>' . $post->title . '</h1>';
	
	echo '<p>' . $post->body . '</p>';
	
	echo '<p><a href="comments/' . $post->id . '">' . $post->comments()->count() . ' Comments</a></p>';

}
?>
<?php include_once($viewsDir . 'common/footer.php'); ?>