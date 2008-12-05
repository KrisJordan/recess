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
		try {
			$this->builder->select();
			$this->fail('Should throw.');
		} catch(RecessException $e) { 
			$this->pass('Empty select properly throws.');
		}
	}
	
	function testFrom() {
		$this->builder->from('table');
		$this->assertEqual($this->builder->select(), 'SELECT * FROM table');
	}
	
	function testFromChain() {
		$this->builder->from('table_a')->from('table_b');
		$this->assertEqual($this->builder->select(), 'SELECT * FROM table_b');
	}
	
	function testWhereWithoutFrom() {
		try {
			$this->builder->like('name','value');
			$this->fail('Should throw cannot have where without from exception.');
		} catch(Exception $e) {
			$this->pass('Exception Caught');
		}
	}
	
	function testMultipleWheres() {
		$this->builder->from('table')->greaterThan('height',5.2)->lessThan('age',100);
		$expected = 'SELECT * FROM table WHERE table.height > :table_height AND table.age < :table_age';
		$this->assertEqual($this->builder->select(), $expected);
	}
	
	function testOrderByFail() {
		$this->builder->orderBy('name ASC');
		try {
			$this->builder->select();
			$this->fail('Should throw, cannot have orderby without from.');
		} catch (Exception $e) {
			$this->pass();
		}
	}
	
	function testOffsetWithoutLimit() {
		$this->builder->from('table')->offset(25);
		try {
			$this->builder->select();
			$this->fail('Should throw, cannot have offset without limit.');
		} catch (Exception $e) {
			$this->pass();
		}
	}
	
	function testLimitOffset() {
		$this->builder->from('table')->limit(10)->offset(1);
		$expected = 'SELECT * FROM table LIMIT 10 OFFSET 1';
		$this->assertEqual($this->builder->select(), $expected);
	}
	
	function testLeftOuterJoin() {
		$this->builder->from('authors')->equal('first_name', 'John')->from('books')->leftOuterJoin('authors','authors.id','books.author_id');
		$expected = 'SELECT books.* FROM books LEFT OUTER JOIN authors ON authors.id = books.author_id WHERE authors.first_name  =  :authors_first_name';
		$this->assertEqual($this->builder->select(), $expected);
	}
	
	function testInsert() {
		$this->builder->into('authors')->assign('first_name','Kris');
		$expected = 'INSERT INTO authors (first_name) VALUES (:assgn_authors_first_name)';
		$this->assertEqual($this->builder->insert(),$expected);
	}
	
	function testUpdate() {
		$this->builder->table('authors')->equal('id',1)->assign('first_name','John');
		$expected = 'UPDATE authors SET first_name = :assgn_authors_first_name WHERE authors.id  =  :authors_id';
		$this->assertEqual($this->builder->update(),$expected);
	}
	
	function testDelete() {
		$this->builder->from('authors');
		$expected = 'DELETE FROM authors';
		$this->assertEqual($this->builder->delete(), $expected);
	}

	function testDeleteWhere() {
		$this->builder->from('authors')->equal('id',1);
		$expected = 'DELETE FROM authors WHERE authors.id  =  :authors_id';
		$this->assertEqual($this->builder->delete(), $expected);
	}
	
	function testClone() {
		$this->builder->from('authors')->equal('first_name', 'John')->from('books')->leftOuterJoin('authors','authors.id','books.author_id');
		$anotherBuilder = clone $this->builder;
		$anotherBuilder->leftOuterJoin('authors','authors.id','books.author_id');
		$this->assertNotEqual($this->builder->select(), $anotherBuilder->select());
	}
	
	function tearDown() {
		unset($this->builder);
	}
}
?>