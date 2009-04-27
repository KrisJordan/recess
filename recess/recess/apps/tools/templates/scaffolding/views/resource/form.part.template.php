<?php
assert($form instanceof ModelForm);
assert(is_string($title));
?>
<?php $form->begin(); ?>
	<fieldset>
		<legend><?php echo $title ?></legend>
		<?php $form->input('{{primaryKey}}'); ?>		
		{{editFields}}
		<input type="submit" value="Save" />
	</fieldset>
<?php $form->end(); ?>