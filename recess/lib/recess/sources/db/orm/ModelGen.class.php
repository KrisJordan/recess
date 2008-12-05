<?php
Library::import('recess.lang.codegen.CodeGen', true);

class ModelGen {
	static function toCode(ModelDescriptor $descriptor) {
		$classFile = new CodeGenClassFile();

		$class = new CodeGenClass($descriptor->modelClass);
		$class->setExtends('Model');
		$classFile->addClass($class);
		
		$classDocComment = new CodeGenDocComment();
		$classDocComment->addLine('!Source ' . $descriptor->source);
		$classDocComment->addLine('!Table ' . $descriptor->getTable());
		$class->setDocComment($classDocComment);
		
		foreach($descriptor->properties as $prop) {
			$property = new CodeGenProperty($prop->name);
			$propertyDocComment = new CodeGenDocComment('!Type ' . $prop->type);
			$property->setDocComment($propertyDocComment);
			$class->addProperty($property);
		}
		
		return $classFile->toCode();
	}
}
?>