<?php
/**
 * Unit Tests for recess.sources.db.SelectedSet
 * @author Kris Jordan
 * @see recess/sources/db/SelectedSet.class.php
 */
class SelectedSetTest extends UnitTestCase {
	protected $source = null;
	
	function setUp() {
		$this->source = new PdoDataSource('sqlite::memory:');
		$this->source->beginTransaction();
		$this->source->exec('CREATE TABLE people (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, first_name STRING, last_name STRING, age INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, author_id INTEGER, title STRING)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Kris", "Jordan", 23)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Joel", "Sutherland", 23)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Clay", "Schossow", 22)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Barack", "Obama", 47)');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"The Audacity of Hope")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"How to Be a Sketch Ball")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (2,"Steve Nash: A Modern Day Hero")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (1,"How Michael Scott Touched My Life, and Could Touch Yours Too")');
		$this->source->commit();
	}
	
	function testSelectAll() {
		$results = $this->source->select()->from('people');
		$this->assertEqual($results->count(), 4);
	}
	
	function testSelectCriteria() {
		$results = $this->source->select()->from('people')->lessThan('age', 40);
		$this->assertEqual($results->count(), 3);
	}
	
	function testSelectOne() {
		$results = $this->source->select()->from('people')->equal('first_name', 'Kris');
		$this->assertEqual($results[0]->last_name, 'Jordan');
	}
	
	function testForEach() {
		$results = $this->source->select()->from('people');
		$firstNames = array('Kris', 'Joel', 'Clay', 'Barack');
		foreach($results as $key => $result) {
			$this->assertEqual($firstNames[$key], $result->first_name);
		}
	}

	function testEmptySelect() {
		$results = $this->source->select();
		$this->assertTrue($results->isEmpty());
		$this->assertEqual($results->count(), 0);
	}
	
	function testJoin() {
		$results = $this->source->select('people')->greaterThan('age', 40)->from('books')->leftOuterJoin('people','people.id','books.author_id');
		$this->assertEqual($results->count(), 1);
		$this->assertEqual($results[0]->title, 'The Audacity of Hope');
	}
	
	function testEmpty() {
		$results = $this->source->select('books');
		$this->assertFalse(empty($results));
	}
	
	function tearDown() {
		unset($this->source);
	}
}

?>