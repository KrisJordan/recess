<?php
Library::import('recess.lang.codegen.CodeGenClassFile');
Library::import('recess.lang.codegen.CodeGenImport');
Library::import('recess.lang.codegen.CodeGenClass');
Library::import('recess.lang.codegen.CodeGenDocComment');
Library::import('recess.lang.codegen.CodeGenClassMember', true);
Library::import('recess.lang.codegen.CodeGenMethod');
Library::import('recess.lang.codegen.CodeGenProperty');

class CodeGen {
	const PUB = 'public';
	const PROT = 'protected';
	const PRIV = 'private';
	
	const STAT = 'static';
	
	const NL = "\n";
	const TAB = "\t";
}

?>