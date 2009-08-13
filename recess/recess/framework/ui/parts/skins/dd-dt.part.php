<?php
Part::input($htmlInput, 'PartBlock');
Part::input($label, 'string', '');

if($label != '') {
	$labelHtml = '<label';
	$id = $htmlInput->get('id');
	if($id != '') {
		$labelHtml .= " for=\"$id\"";
	}
	$labelHtml .= ">$label</label>";
} else {
	$labelHtml = '&nbsp;';
}
?>	<dt><?php echo $labelHtml ?></dt>
	<dd><?php echo $htmlInput ?></dd>
