<?php
Part::input($control,'Control');
Part::input($name,	'string');
Part::input($value, 'string',	'');
Part::input($label, 'string',	'');
Part::input($attrs, 'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('control-select'));
Part::input($choices, 'array');

$controlBlock  = Part::block('html/select',
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