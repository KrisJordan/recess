<?php include_once($viewsDir . 'common/header.php'); ?>

<ul>
<?php foreach($posts as $post): ?>
	<li><strong><?php echo get_class($post), ' #', $post->id; ?> - 
		<a href="<?php echo $controller->urlTo('editForm', $post->id); ?>">edit</a></strong>
		<ul>
			<li>Title: <?php echo $post->title; ?></li>
			<li>Body: <?php echo $post->body; ?></li>
			<li>Is Public: <?php echo $post->isPublic ? 'true' : 'false'; ?></li>
			<li>Modified At: <?php echo date(DATE_RFC822, $post->modifiedAt); ?></li>
			<li>Created On: <?php echo date(DATE_RFC822, $post->createdOn); ?></li>
			<li>
				<form method="POST" action="<?php echo $controller->urlTo('delete', $post->id); ?>">
					<input type="hidden" name="_METHOD" value="DELETE" />
					<input type="submit" name="delete" value="Delete" />
				</form>
			</li>
		</ul>
	</li>
<?php endforeach; ?>
</ul>
<a href="<?php echo $controller->urlTo('newForm'); ?>">Create New Post</a>

<?php include_once($viewsDir . 'common/footer.php'); ?>