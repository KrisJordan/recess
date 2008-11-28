<?php
Library::import('recess.lang.Annotation');

abstract class ModelPropertyAnnotation extends Annotation {
	
	abstract function massage(ModelProperty $property);
	
}

?>