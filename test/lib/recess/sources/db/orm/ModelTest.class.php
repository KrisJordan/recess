<?php

Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.orm.Model');

/**
 * !HasMany books, ForeignKey: author_id
 * !HasMany novels, ForeignKey: author_id, Class: Book
 */
class Person extends Model { }

/**
 * !BelongsTo author, Class: Person
 * !HasAndBelongsToMany generas, Class: Genera
 */
class Book extends Model { }

/**
 * !HasAndBelongsToMany books, Class: Book
 * !Table genera
 */
class Genera extends Model { }

class ModelTest extends UnitTestCase {
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
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"The Audacity of Hope: Thoughts on Reclaiming the American Dream")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"How to Be a Sketch Ball")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (2,"Steve Nash: A Modern Day Hero")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (1,"How Michael Scott Touched My Life, and Could Touch Yours Too")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"Dreams from My Father: A Story of Race and Inheritance")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"Barack Obama: What He Believes In - From His Own Works")');
		$this->source->exec('INSERT INTO genera (title) VALUES ("Sports Healing")'); // 1
		$this->source->exec('INSERT INTO genera (title) VALUES ("Political Healing")'); // 2
		$this->source->exec('INSERT INTO genera (title) VALUES ("Social Healing")'); // 3
		$this->source->exec('INSERT INTO genera (title) VALUES ("Comedy Healing")'); // 4
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (1,3)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (2,3)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (2,4)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (3,1)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (4,4)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (5,3)');
		$this->source->exec('INSERT INTO books_genera (book_id,genera_id) VALUES (6,3)');
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
	
	function testFindTailCriteria() {
		$person = new Person();
		$people = $person->find()->greaterThan('age',22);
		$this->assertEqual(count($people),3);
	}
	
	function testHasManyRelationship() {
		$person = new Person();
		$person->id = 4;
		$books = $person->books()->orderBy('title');
		$this->assertEqual(count($books), 3);
	}
	
	function testHasManyRelationshipCriteria() {
		$person = new Person();
		$person->first_name = 'Barack';
		$person->last_name = 'Obama';
		$books = $person->books()->like('title','%Dream%')->orderBy('title ASC');
		$this->assertEqual(count($books), 2);	
		$this->assertEqual($books[0]->title, 'Dreams from My Father: A Story of Race and Inheritance');
	}
	
	function testAliasHasManyRelationship() {
		$person = new Person();
		$person->id = 4;
		$novels = $person->novels();
		$this->assertEqual(count($novels),3);
	}
	
	function testChainMultipleRelationships() {
		$person = new Person();
		$person->id = 4;
		// $generas = $person->books()->generas()
		$generas = $person
						->books()
						->from('genera')
						->innerJoin('books_genera','genera.id','books_genera.genera_id')
						->innerJoin('books','books.id','books_genera.book_id');
						
		$this->assertEqual(count($generas), 3);
	}
	
	function testBelongsTo() {
		$book = new Book();
		$book->id = 1;
		$barack = $book->author();
		$this->assertEqual(get_class($barack), 'Person');
		$this->assertEqual($barack->first_name, 'Barack');
		$baracksBooks = $barack->books();
		$this->assertEqual(count($baracksBooks), 3);
	}
	
	
	function tearDown() {
		unset($this->source);
	}
	
}

?>