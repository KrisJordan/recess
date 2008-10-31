<?php

Library::import('recess.framework.orm.ModelBase');
Library::import('recess.sources.db.DbSources');

/**
 * !HasMany books, ForeignKey: author_id, OrderBy: title
 */
class Person extends ModelBase { }

/**
 * !BelongsTo author, Class: Person
 */
class Book extends ModelBase { }

class ModelBaseTest extends UnitTestCase {
	protected $source;
	
	function setUp() {
		$this->source = new PdoDataSource('sqlite::memory:');
		DbSources::setDefaultSource($this->source);
		$this->source->beginTransaction();
		$this->source->exec('CREATE TABLE persons (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, first_name STRING, last_name STRING, age INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, author_id INTEGER, title STRING)');
		$this->source->exec('CREATE TABLE genera (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, title INTEGER)');
		$this->source->exec('CREATE TABLE books_genera (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, book_id INTEGER, genera_id INTEGER)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Kris", "Jordan", 23)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Joel", "Sutherland", 23)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Clay", "Schossow", 22)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Barack", "Obama", 47)');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"The Audacity of Hope")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"How to Be a Sketch Ball")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (2,"Steve Nash: A Modern Day Hero")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (1,"How Michael Scott Touched My Life, and Could Touch Yours Too")');
		$this->source->exec('INSERT INTO genera (title) VALUES ("Sports Healing")'); // 1
		$this->source->exec('INSERT INTO genera (title) VALUES ("Political Healing")'); // 2
		$this->source->exec('INSERT INTO genera (title) VALUES ("Social Healing")'); // 3
		$this->source->exec('INSERT INTO genera (title) VALUES ("Comedy Healing")'); // 4
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (1,3)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (2,3)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (2,4)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (3,1)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (4,4)');
		$this->source->commit();
	}
	
	function testFind() {
		$person = new Person();
		$people = $person->find();
		$this->assertEqual(count($people),4);	
	}
	
	function testFindCriteria() {
		$person = new Person();
		$person->age = 23;
		$people = $person->find()->orderBy('last_name DESC');
		$this->assertEqual(count($people),2);
		$this->assertEqual($people[0]->last_name, 'Sutherland');
		$this->assertEqual(get_class($people[0]), 'Person');
	}
	
	function testRelationship() {
		$person = new Person();
		$person->id = 1;
		$books = $person->books()->orderBy('title');
	}
	
	function tearDown() {
		unset($this->source);
	}
	
}

?>