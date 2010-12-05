<?php
namespace Recess\Core;

require_once __DIR__ . '/../../../Core/functions.php';

/**
 * @group Recess\Core
 */
class CallableTest extends \PHPUnit_Framework_TestCase {
	
	function testEach() {
		$out = '';
		each(array(),function($item)use(&$out){$out.=$item;}); 
		$this->assertEquals('',$out);
		
		each(array(1,2,3,4,5),function($item)use(&$out){$out.=$item;}); 
		$this->assertEquals("12345",$out);
	}
	
	function testFilter() {
		$in = array(1,2,3,4,5);
		$out = filter($in,function($a){return true;});
		$this->assertEquals($in,$out);
		
		$out = filter($in,function($a){return false;});
		$this->assertEquals(array(),$out);
		
		$in = array(1,2,3,4,5);
		$out = filter($in,function($a){return $a % 2 === 0;});
		$this->assertEquals(array(1=>2,3=>4),$out);
	}
	
	function testMap() {
		$in = array();
		$out = map($in,function($a){return $a;});
		$this->assertEquals($in,$out);
		
		$in = array(1,2,3,4,5);
		$out = map($in,function($a){return $a;});
		$this->assertEquals($in,$out);
		
		$in = array(1,2,3,4,5);
		$out = map($in,function($a){return $a+1;});
		$this->assertEquals(array(2,3,4,5,6),$out);
	}
	
	function testReduceIdentity() {
		$this->assertEquals(1,reduce(array(),function($a,$b){return $a+$b;},1));
		$this->assertEquals(1,reduce(array(1),function($a,$b){return $a+$b;},1));
	
		$this->assertEquals(2,reduce(array(1,1),function($a,$b){return $a+$b;},1));

		$this->assertEquals(3,reduce(array(1,1,1),function($a,$b){return $a+$b;},1));	
		$this->assertEquals(10,reduce(array_fill(0,10,1),function($a,$b){return $a+$b;},1));
	}
}
