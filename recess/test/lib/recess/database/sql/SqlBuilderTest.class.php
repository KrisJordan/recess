<?php
Library::import('recess.database.sql.SqlBuilder');
Library::import('recess.database.sql.Criterion');
/**
 * Unit Tests for recess.database.sql.SqlBuilder
 * @author Kris Jordan
 * @see lib/recess/sources/db/sql/SqlBuilder.class.php
 */
class SqlBuilderTest extends UnitTestCase  {
	
	protected $builder = null;
	
	function setUp() {
		$this->builder = new SqlBuilder();
	}
	
	function testEmpty() {
		$this->assertEqual('', $this->builder->getSql());
	}
	
	function testFrom() {
		$this->builder->from('table');
		$this->assertEqual($this->builder->getSql(), 'SELECT * FROM table');
	}
	
	function testFromChain() {
		$this->builder->from('table_a')->from('table_b');
		$this->assertEqual($this->builder->getSql(), 'SELECT * FROM table_b');
	}
	
	function testWhereWithoutFrom() {
		$this->builder->like('name','value');
		try {
			echo $this->builder->getSql(); 
			$this->fail('Should throw cannot have where without from exception.');
		} catch(Exception $e) {
			$this->pass('Exception Caught');
		}
	}
	
	function testMultipleWheres() {
		$this->builder->from('table')->greaterThan('height',5.2)->lessThan('age',100);
		$expected = 'SELECT * FROM table WHERE height > :height AND age < :age';
		$this->assertEqual($this->builder->getSql(), $expected);
	}
	
	function testOrderByFail() {
		$this->builder->orderBy('name ASC');
		try {
			$this->builder->getSql();
			$this->fail('Should throw, cannot have orderby without from.');
		} catch (Exception $e) {
			$this->pass();
		}
	}
	
	function testOffsetWithoutLimit() {
		$this->builder->from('table')->offset(25);
		try {
			$this->builder->getSql();
			$this->fail('Should throw, cannot have offset without limit.');
		} catch (Exception $e) {
			$this->pass();
		}
	}
	
	function testLimitOffset() {
		$this->builder->from('table')->limit(10)->offset(1);
		$expected = 'SELECT * FROM table LIMIT 10 OFFSET 1';
		$this->assertEqual($this->builder->getSql(), $expected);
	}
	
	function testLeftOuterJoin() {
		$this->builder->from('authors')->equal('first_name', 'John')->from('books')->leftOuterJoin('authors','authors.id','books.author_id');
		$expected = 'SELECT books.* FROM books LEFT OUTER JOIN authors ON authors.id = books.author_id WHERE authors.first_name = :authors_first_name';
		$this->assertEqual($this->builder->getSql(), $expected);
	}
	
	function tearDown() {
		unset($this->builder);
	}
}
?>