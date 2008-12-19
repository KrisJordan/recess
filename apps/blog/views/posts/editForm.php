<?php include_once($viewsDir . 'common/header.php'); ?>

<?php $_form->begin(); ?>
<fieldset>
		<legend>Edit Post</legend>
		<?php $_form->input('id'); ?>
		<p>
		<label for="title">Title</label><br />
		<?php $_form->input('title'); ?>
		</p>
		<p>
		<label for="body">Body</label><br />
		<?php $_form->input('body'); ?>
		</p>
		<p>
		<label for="title">Is Public</label><br />
		<?php $_form->input('isPublic'); ?>
		</p>
		<p>
		<label for="title">Modified At</label><br />
		<?php $_form->input('modifiedAt'); ?>
		</p>
		<p>
		<label for="title">Created On</label><br />
		<?php $_form->input('createdOn'); ?>
		</p>
		<input type="submit" value="Save" />
	</fieldset>
<?php $_form->end; ?>

<a href="<?php echo $controller->urlTo('index'); ?>">Show All Posts</a>

<?php include_once($viewsDir . 'common/footer.php'); ?>