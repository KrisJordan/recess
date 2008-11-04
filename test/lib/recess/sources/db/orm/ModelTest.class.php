<?php

Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.orm.Model');
Library::import('recess.sources.db.orm.ModelDataSource');

/**
 * !HasMany books, ForeignKey: author_id
 * !HasMany novels, ForeignKey: author_id, Class: Book
 */
class Person extends Model { }

/**
 * !BelongsTo author, Class: Person
 * !HasAndBelongsToMany generas
 */
class Book extends Model { }

/**
 * !HasAndBelongsToMany books, CascadeDelete: true
 * !HasAndBelongsToMany movies
 */
class Genera extends Model { }

/**
 * !HasAndBelongsToMany generas
 */
class Movie extends Model { }

class ModelTest extends UnitTestCase {
	protected $source;
	
	function setUp() {
		$this->source = new ModelDataSource('sqlite::memory:');
		DbSources::setDefaultSource($this->source);
		$this->source->beginTransaction();
		$this->source->exec('CREATE TABLE persons (id INTEGER PRIMARY KEY AUTOINCREMENT, first_name TEXT, last_name TEXT, age TEXT)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, author_id INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE movies (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, author_id INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE generas (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, title INTEGER)');
		$this->source->exec('CREATE TABLE books_generas (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, book_id INTEGER, genera_id INTEGER)');
		$this->source->exec('CREATE TABLE generas_movies (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, movie_id INTEGER, genera_id INTEGER)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Kris", "Jordan", 23)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Joel", "Sutherland", 23)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Clay", "Schossow", 22)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Barack", "Obama", 47)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("Josh", "Lockhart", 22)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age) VALUES ("John", "McCain", 72)');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"The Audacity of Hope: Thoughts on Reclaiming the American Dream")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"How to Be a Sketch Ball")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (2,"Steve Nash: A Modern Day Hero")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (1,"How Michael Scott Touched My Life, and Could Touch Yours Too")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"Dreams from My Father: A Story of Race and Inheritance")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"Barack Obama: What He Believes In - From His Own Works")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"Hoop Dreams, The Clay Schossow Story")');
		$this->source->exec('INSERT INTO movies (author_id, title) VALUES (3,"Hoop Dreams, The Clay Schossow Story, The Movie")');
		$this->source->exec('INSERT INTO movies (author_id, title) VALUES (3,"Clay Schossow: Unleashed")');
		$this->source->exec('INSERT INTO movies (author_id, title) VALUES (3,"LeBron James and Other Assorted Dreams of Clay Schossow")');
		$this->source->exec('INSERT INTO generas (title) VALUES ("Sports Healing")'); // 1
		$this->source->exec('INSERT INTO generas (title) VALUES ("Political Healing")'); // 2
		$this->source->exec('INSERT INTO generas (title) VALUES ("Social Healing")'); // 3
		$this->source->exec('INSERT INTO generas (title) VALUES ("Comedy Healing")'); // 4
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (1,3)');
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (2,3)');
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (2,4)');
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (3,1)');
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (4,4)');
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (5,3)');
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (6,3)');
		$this->source->exec('INSERT INTO books_generas (book_id,genera_id) VALUES (7,1)');
		$this->source->exec('INSERT INTO generas_movies (movie_id,genera_id) VALUES (1,1)');
		$this->source->exec('INSERT INTO generas_movies (movie_id,genera_id) VALUES (1,4)');
		$this->source->exec('INSERT INTO generas_movies (movie_id,genera_id) VALUES (2,4)');
		$this->source->exec('INSERT INTO generas_movies (movie_id,genera_id) VALUES (3,1)');
		$this->source->commit();
	}
	
	function testAll() {
		$person = new Person();
		$people = $person->all();
		$this->assertEqual(count($people),6);	
		
		$genera = Make::a('Genera')->equal('title','Social Healing')->first();
		$this->assertEqual($genera->title, 'Social Healing');
		$this->assertEqual($genera->id, 3);
		
// Prototype code:
//		$book = new Book();
//		$book->title = 'Hark the raven, nevermore.';
//		$book->generas()->add($genera);
//		$book->save();
//		
//		$book->addTo('generas',$genera);
//		
//		$author = $people[0];
//		$author->books()->add($book);
//		$author->books()->remove($book);
//		$author->books()->clear();
// if modelset carried around the last used relation, this could work
		
//		SELECT books.* FROM books 
//		INNER JOIN people ON people.id = books.author_id 
//		WHERE people.id = 1
//		
//		$author->addTo('books', $book);

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
		$this->assertEqual(count($people),4);
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
		$person->id = 3;
		$generas = $person->books()->generas();
		$this->assertEqual(count($generas), 3);
		
		$person = new Person();
		$generas = $person->equal('age',22)->books()->generas();
		$this->assertEqual(count($generas), 3);
		
		$person = new Person();
		$generas = $person->equal('age',22)->books()->like('title','%Dream%')->generas();
		$this->assertEqual(count($generas), 1);
	}
	
	function testMultipleJoinTables() {		
		$movies = Make::a('Person')
						->equal('age',22)
						->books()
						->like('title','%Dream%') 
						->generas()
						->movies();
		
		$this->assertEqual(count($movies),2);
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
	
	function testHasAndBelongsToMany() {
		$book = new Book();
		$book->id = 2;
		$generas = $book->generas();
		$this->assertEqual(count($generas), 2);
		
		$books = $generas[0]->books();
		$this->assertEqual(count($books),4);
	}
	
	function testHasAndBelongsToManyWithCriteria() {
		$book = new Book();
		$generas = $book->generas()->like('books.title', '%Dream%');
		$this->assertEqual(count($generas),2);
	}
	
	function testDelete() {
		$people = Make::a('Person')->all();
		$people_count = count($people);
		$this->assertTrue($people[0]->delete());
		
		$people = Make::a('Person')->all();
		$this->assertEqual($people_count - 1, count($people));
	}
	
	function testInsert() {
		$people = Make::a('Person')->all();
		$people_count = count($people);
		
		$person = new Person();
		$person->first_name = 'Joe';
		$person->last_name = 'Biden';
		$person->age = 65;
		$person->insert();
		
		$people = Make::a('Person')->all();
		$this->assertEqual($people_count + 1, count($people));
	}
	
	function testUpdate() {
		$people = Make::a('Person')->all();
		$person = $people[0];
		$name = $person->first_name;
		
		$person->first_name = 'UPDATE!';
		$person->update();
		
		$people = Make::a('Person')->all();
		
		$this->assertEqual($person->first_name, $people[0]->first_name);
	}
	
	function tearDown() {
		unset($this->source);
	}
	
}

?>