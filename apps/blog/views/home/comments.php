<?php

foreach($comments as $comment) {
	
	echo '<p>' . $comment->comment . '</p>';
	
	echo '<hr />';
	
}

?>

<form method="POST">
	<input type="text" name="comment"></input>
	<input type="submit"></input>
</form>