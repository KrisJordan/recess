<?php include_once($viewsDir . 'common/header.php'); ?>

<h1>Create New Post</h1>

<form method="POST" action="./new">
	<table>
		<tr><td>Title</td><td><input type="text" name="title" /></td></tr>
		<tr><td>Body</td><td><input type="text" name="body" /></td></tr>
		<tr><td>Save</td><td><input type="submit" value="Post" /></td></tr>
	</table>
</form>

<?php include_once($viewsDir . 'common/footer.php'); ?>