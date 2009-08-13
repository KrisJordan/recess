<?php
Part::input($control,'Control');
Part::input($name,	'string');
Part::input($value, 'array',	array());
Part::input($label, 'string',	'');
Part::input($attrs, 'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('control-checkbox-array'));
Part::input($choices, 'array');

$controlBlock  = Part::block('html/checkbox-array',
						$control->getId(),
						$control->getFormName(),
						$value,
						$attrs,
						$classes,
						$choices);
						
Part::draw( $control->getSkin(),
			$controlBlock,
			$label);
?>