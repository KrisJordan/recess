<form method="POST">
	<input type="text" name="comment"></input>
	<input type="submit"></input>
</form>



<?php

echo '<a href="/blog/">Go Back</a>';
echo '<h2>' . $post->title . '</h2>';

foreach($comments as $comment) {
	
	echo '<p><a href="/blog/comment/' . $comment->id . '/delete/">X</a> ' . $comment->comment . '</p>';
	
	echo '<hr />';
	
}

?>