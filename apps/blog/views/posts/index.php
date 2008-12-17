<?php include_once($viewsDir . 'common/header.php'); ?>

<h3><a href="<?php echo $controller->urlTo('newForm'); ?>">Create New Post</a></h3>

<?php foreach($posts as $post): ?>

<form method="POST" action="<?php echo $controller->urlTo('delete', $post->id); ?>">
	<fieldset>
		<h3><?php echo get_class($post), ' #', $post->id; ?></h3>
		<p>
		<strong>Title</strong>: <?php echo $post->title; ?><br />
		<strong>Body</strong>: <?php echo $post->body; ?><br />
		<strong>Is Public</strong>: <?php echo $post->isPublic ? 'yes' : 'no'; ?><br />
		<strong>Modified At</strong>: <?php echo date(DATE_RFC822, $post->modifiedAt); ?><br />
		<strong>Created On</strong>: <?php echo date(DATE_RFC822, $post->createdOn); ?><br />
		</p>
		
		<a href="<?php echo $controller->urlTo('editForm', $post->id); ?>">Edit</a></strong>  - 
		<input type="hidden" name="_METHOD" value="DELETE" />
		<input type="submit" name="delete" value="Delete" />
	</fieldset>
</form>
<hr />
<?php endforeach; ?>

<?php include_once($viewsDir . 'common/footer.php'); ?>