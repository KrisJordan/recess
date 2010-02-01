<?php
namespace made\up\space;

use recess\lang\Annotation;
class DummyAnnotation extends Annotation {

	public function usage() {
		return "";
	}
	
	public function isFor() {
		return Annotation::FOR_CLASS | Annotation::FOR_METHOD | Annotation::FOR_PROPERTY;
	}
	
	public function validate($class) {
		return true;
	}
	
	protected function expand($class, $reflection, $descriptor) {
		return $descriptor;
	}
}