<?php
/**
 * Unit Tests for recess.database.pdo.PdoDataSet
 * @author Kris Jordan
 * @see recess/sources/db/SelectedSet.class.php
 */
class PdoDataSetTest extends UnitTestCase {
	protected $source = null;
	
	function setUp() {
		$this->source = new PdoDataSource('sqlite::memory:');
		$this->source->beginTransaction();
		$this->source->exec('CREATE TABLE people (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, first_name STRING, last_name STRING, age INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, author_id INTEGER, title STRING)');
		$this->source->exec('CREATE TABLE genera (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, title INTEGER)');
		$this->source->exec('CREATE TABLE books_genera (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, book_id INTEGER, genera_id INTEGER)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Kris", "Jordan", 23)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Joel", "Sutherland", 23)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Clay", "Schossow", 22)');
		$this->source->exec('INSERT INTO people (first_name, last_name, age) VALUES ("Barack", "Obama", 47)');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"The Audacity of Hope")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"How to Be a Sketch Ball")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (2,"Steve Nash: A Modern Day Hero")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (1,"How Michael Scott Touched My Life, and Could Touch Yours Too")');
		$this->source->exec('INSERT INTO genera (title) VALUES ("Sports Healing")'); // 1
		$this->source->exec('INSERT INTO genera (title) VALUES ("Political Healing")'); // 2
		$this->source->exec('INSERT INTO genera (title) VALUES ("Social Healing")'); // 3
		$this->source->exec('INSERT INTO genera (title) VALUES ("Comedy Healing")'); // 4
		$this->source->exec('INSERT INTO books_genera (book_id, genera_id) VALUES (1, 3)');
		$this->source->exec('INSERT INTO books_genera (book_id, genera_id) VALUES (2, 3)');
		$this->source->exec('INSERT INTO books_genera (book_id, genera_id) VALUES (2, 4)');
		$this->source->exec('INSERT INTO books_genera (book_id, genera_id) VALUES (3, 1)');
		$this->source->exec('INSERT INTO books_genera (book_id, genera_id) VALUES (4, 4)');
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
		try {
			$this->assertTrue($results->isEmpty());
			$this->fail('Selection without specifying a table should fail.');
		} catch(RecessException $e) {
			$this->pass('Select without specified table correctly threw an exception.');
		}
	}
	
	function testJoin() {
		$results = $this->source
						->select('people')
						->greaterThan('age', 40)
						->from('books')
						->leftOuterJoin('people','people.id','books.author_id');
		$this->assertEqual($results->count(), 1);
		$this->assertEqual($results[0]->title, 'The Audacity of Hope');
	}
	
	function testAmbiguousWhere() {
		$results = $this->source
						->select('people')
						->lessThan('age',40)
						->from('books')
						->leftOuterJoin('people','people.id','books.author_id')
						->greaterThan('id',2);
		$this->assertEqual($results->count(), 2);		
	}
	
	function testAmbiguousOrderBy() {
		$results = $this->source
						->select('people')
						->lessThan('age',40)
						->from('books')
						->leftOuterJoin('people','people.id','books.author_id')
						->greaterThan('id',2)
						->orderBy('id');
		$this->assertEqual($results->count(), 2);	
	}
	
	function testEmpty() {
		$results = $this->source->select('books');
		$this->assertFalse($results->isEmpty());
	}
	
	function testGreaterThanEqualTo() {
		$results = $this->source
						->select('people')
						->greaterThanOrEqualTo('age',23);
		$this->assertEqual($results->count(), 3);
	}
	
	function testInnerJoin() {
		$results = $this->source
						->select('people')
						->greaterThan('age',23)
						->from('books')
						->innerJoin('people','people.id','books.author_id');
		$this->assertEqual($results->count(), 1);
	}
	
	function testPrefetch() {
		$results = $this->source
						->select('people')
						->equal('age',23)
						->leftOuterJoin('books','books.author_id','people.id')
						->orderBy('last_name');
		$this->assertEqual($results->count(),2);
	}
	
	function testJoinTable() {
		$results = $this->source
						->select('books')
						->innerJoin('books_genera', 'books.id', 'books_genera.book_id')
						->equal('books_genera.genera_id',4);
						
		$this->assertEqual($results->count(),2);
	}
	
	function testJoinTableCriteria() {
		$results = $this->source
						->select('genera')
						->equal('id',4)
						->from('books')
						->innerJoin('books_genera','books.id','books_genera.book_id')
						->innerJoin('genera','genera.id','books_genera.genera_id')
						->orderBy('title');
						
		$this->assertEqual($results->count(),2);	
	}
	
	function testJoinTablePrefetch() {
		$results = $this->source
						->select('genera')
						->equal('title','Comedy Healing')
						->from('books')
						->innerJoin('books_genera','books.id','books_genera.book_id')
						->innerJoin('genera','genera.id','books_genera.genera_id')
						->leftOuterJoin('people', 'people.id','books.author_id')
						->orderBy('title');
						
		$this->assertEqual($results->count(), 2);		
	}
	
	function testMultipleJoins() {
		$results = $this->source
						->select('people')
						->equal('first_name','Barack')
						->from('books')
						->innerJoin('people','people.id','books.author_id')
						->from('genera')
						->innerJoin('books_genera','genera.id','books_genera.genera_id')
						->innerJoin('books','books.id','books_genera.book_id');

		$this->assertEqual(count($results), 1);
	}
	
	function testClone() {
		$results = $this->source->select('people');
		$count = count($results);
		$results2 = clone $results;
		$results2 = $results2->equal('first_name','Barack');
		$this->assertNotEqual(count($results),count($results2));
	}
	
	function tearDown() {
		unset($this->source);
	}
}

?>